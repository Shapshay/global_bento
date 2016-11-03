<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 03.11.2016
 * Time: 10:03
 */
# SETTINGS #############################################################################
$moduleName = "call_assign";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
    $moduleName => $prefix . $moduleName.".tpl",
    $moduleName . "main" => $prefix . "main.tpl",
    $moduleName . "html" => $prefix . "html.tpl",
    
));
# MAIN #################################################################################

if(isset($_POST['telnumber'])){
    // polis lider

    $rows3 = $dbc->dbselect(array(
            "table"=>"polises",
            "select"=>"users.name AS oper, 
                users.login_1C AS login_1C,
                COUNT(polises.id) as pcount ",
            "joins"=>"LEFT OUTER JOIN users ON polises.oper_id = users.id ",
            "where"=>"polises.status > 0 AND 
                DATE_FORMAT(polises.date_write,'%Y-%m-%d') = '".date('Y-m-d')."' AND
                users.office_id = ".$_POST['office_id'],
            "group"=>"oper",
            "order"=>"pcount",
            "order_type"=>"DESC",
            "limit"=>3
        )
    );
    if($dbc->count>0){
        $rand_arr = array();
        $i = 0;
        foreach ($rows3 as $row3){
            $rand_arr[$i] = $row3['login_1C'];
            $i++;
        }
        $oper_key = array_rand($rand_arr, 1);
        $oper_code = $rand_arr[$oper_key];
    }
    else{
        switch ($_POST['office_id']){
            case '1':
                $oper_code = '0715-0166-02';
                break;
            case '2':
                $oper_code = '0715-0166-02';
                break;
            case '3':
                $oper_code = '0516-2133-30';
                break;
            case '4':
                $oper_code = '0916-0962-03';
                break;
            case '5':
                $oper_code = '1016-0275-61';
                break;
            case '6':
                $oper_code = '0816-1860-03';
                break;
            case '7':
                $oper_code = '0816-1860-03';
                break;
            default:
                $oper_code = '0715-0166-02';
                break;
        }
    }

    // 1C
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params["telnumber"] = $_POST['telnumber'];
    $params["Code1C"] = $oper_code;
    $params["Debt"] = $_POST['office_code'];
    //print_r($params);
    $result = $client->OrderCallBack($params);
    $array = objectToArray($result);
    $tpl->assign("ASSIGN_RESULT", $array['return']);

}
else{
    $tpl->assign("ASSIGN_RESULT", '');
}
$tpl->parse("META_LINK", ".".$moduleName."html");

$offices='';
$office = ROOT_OFFICE;
$rows = $dbc->dbselect(array(
        "table"=>"offices",
        "select"=>"id, title, code1c"
    )
);
$i=1;
$office_code = '';
foreach($rows as $row){
    if($i==1){
        $office_code = $row['code1c'];
    }
    if($row['id']==ROOT_OFFICE){
        $sel_of = ' selected="selected"';
        $office_code = $row['code1c'];
    }
    else{
        $sel_of = '';
    }
    $offices.='<option value="'.$row['id'].'"'.$sel_of.'>'.$row['title'].'</option>';
    $i++;
}
$tpl->assign("OFFICES_ROWS", $offices);
$tpl->assign("OFFICE_CODE", $office_code);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");