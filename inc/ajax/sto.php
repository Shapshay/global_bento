<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 29.08.2016
 * Time: 14:13
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

// SOAP объект в массив
function objectToArray($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        return array_map(__FUNCTION__, $d);
    }
    else {
        return $d;
    }
}

// SOAP std в массив
function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key => &$field){
        if(is_object($field))$field = $this->stdToArray($field);
    }
    return $rc;
}

##############################################################################

if(!isset($_GET['type'])){
    // выгрузка клиентов
    $rows = $dbc->dbselect(array(
            "table"=>"sto",
            "select"=>"sto_tochka_id, code_1C, name,
                iin, gn, pn, mark, model, born, phone,
                email, date_to_end, date_dog",
            "where"=>"res_call_id = 1 OR res_call_id = 6"
        )
    );
    $numRows = $dbc->count;
    if($numRows>0){
        $i=0;
        foreach ($rows as $row){
            foreach ($row as $key=>$value){
                $out_row['clients'][$i][$key] = $value;
            }
            $out_row['clients'][$i]['visit'] = 0;
            $i++;
        }
        $out_row['res'] = 1;
    }
    else{
        $out_row['res'] = 0;
    }
}
else{
    switch ($_GET['type']){
        case 'sync':
            $client = array();
            //print_r($_GET);
            /*foreach ($_GET as $key=>$v){
                $client[$key] = base64_decode($v);
            }*/
            foreach ($_POST as $key=>$v){
                $client[$key] = $v;
            }
            $code_sto = $client['sto_code'];
            unset($client['type']);
            unset($client['id']);
            unset($client['sto_tochka_id']);
            unset($client['controller']);
            unset($client['action']);
            unset($client['sto_code']);
            unset($client['oper_id']);
            $row = $dbc->element_find_by_field('sto','code_1C',$client['code_1C']);

            if($dbc->count>0&&$client['code_1C']!=''){
                // client update
                $sto_id = $row['id'];
                $dbc->element_update('sto',$row['id'],$client);
                $row2 = $dbc->element_find_by_field('sto_tochka','code',$code_sto);
                $dbc->element_update('sto',$row['id'],array(
                    "sto_tochka_id" => $row2['id']));

                ini_set("soap.wsdl_cache_enabled", "0" );
                $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                    array(
                        'login' => 'ws',
                        'password' => '123456',
                        'trace' => true
                    )
                );
                $row = $dbc->element_find('sto',$sto_id);
                $params2["Client"]["Name"] = $row['name'];
                $params2["Client"]["Iin"] = $row['iin'];
                $params2["Client"]["GosNomer"] = $row['gn'];
                $params2["Client"]["Mark"] = $row['mark'];
                $params2["Client"]["Model"] = $row['model'];
                $params2["Client"]["GodVypusk"] = $row['born'];
                $params2["Client"]["StatusTO"] = $row['status_to'];
                $params2["Client"]["DateOfEndTO"] = date("Y-m-d",strtotime($row['date_to_end']));
                $params2["Client"]["Summa"] = $row['summa'];
                if($row['strach']==0){
                    $strach = false;
                }
                else{
                    $strach = true;
                }
                $params2["Client"]["HasPolic"] = $strach;
                $params2["Client"]["InsuranseCompany"] = $row['strach_company'];
                $params2["Client"]["DateBegin"] = date("Y-m-d",strtotime($row['date_strach_start']));
                $params2["Client"]["DateEnd"] = date("Y-m-d",strtotime($row['date_strach_end']));
                $params2["Client"]["Telefon"] = $row['phone'];
                $params2["Client"]["Email"] = $row['email'];
                $params2["Client"]["Sto"] = $code_sto;
                $params2["Client"]["Code1C"] = $row['code_1C'];
                $result = $client2->SaveClient2($params2);
            }
            else{
                // client new
                $row3 = $dbc->element_find_by_field('sto','gn',$client['gn']);
                $numRows = $dbc->count;
                if (!($numRows > 0)) {

                    $row2 = $dbc->element_find_by_field('sto_tochka', 'code', $code_sto);

                    $dbc->element_create('sto', array(
                        "sto_tochka_id" => $row2['id'],
                        "name" => $client['name'],
                        "oper_id" => 0,
                        "iin" => $client['iin'],
                        "gn" => $client['gn'],
                        "pn" => $client['pn'],
                        "mark" => $client['mark'],
                        "model" => $client['model'],
                        "born" => $client['born'],
                        "phone" => $client['phone'],
                        "email" => $client['email'],
                        "date_to_end" => date("Y-m-d", strtotime($client['date_to_end'])),
                        "status_to" => $client['status_to'],
                        "summa" => $client['summa'],
                        "bn" => $client['bn'],
                        "strach" => $client['strach'],
                        "strach_company" => $client['strach_company'],
                        "date_strach_start" => date("Y-m-d", strtotime($client['date_strach_start'])),
                        "date_strach_end" => date("Y-m-d", strtotime($client['date_strach_end'])),
                        "visit" => 1,
                        "date_visit" => date("Y-m-d", strtotime($client['date_visit']))
                    ));
                    $c_id = $dbc->ins_id;
                    $out_row['outsql'] = $dbc->outsql;
                    $row = $dbc->element_find('sto', $c_id);

                    ini_set("soap.wsdl_cache_enabled", "0");
                    $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                        array(
                            'login' => 'ws',
                            'password' => '123456',
                            'trace' => true
                        )
                    );

                    $params2["Client"]["Name"] = $row['name'];
                    $params2["Client"]["Iin"] = $row['iin'];
                    $params2["Client"]["GosNomer"] = $row['gn'];
                    $params2["Client"]["Mark"] = $row['mark'];
                    $params2["Client"]["Model"] = $row['model'];
                    $params2["Client"]["GodVypusk"] = $row['born'];
                    $params2["Client"]["StatusTO"] = $row['status_to'];
                    $params2["Client"]["DateOfEndTO"] = date("Y-m-d", strtotime($row['date_to_end']));
                    $params2["Client"]["Summa"] = $row['summa'];
                    if ($row['strach'] == 0) {
                        $strach = false;
                    } else {
                        $strach = true;
                    }
                    $params2["Client"]["HasPolic"] = $strach;
                    $params2["Client"]["InsuranseCompany"] = $row['strach_company'];
                    $params2["Client"]["DateBegin"] = date("Y-m-d", strtotime($row['date_strach_start']));
                    $params2["Client"]["DateEnd"] = date("Y-m-d", strtotime($row['date_strach_end']));
                    $params2["Client"]["Telefon"] = $row['phone'];
                    $params2["Client"]["Email"] = $row['email'];
                    $params2["Client"]["Sto"] = $code_sto;
                    $params2["Client"]["Code1C"] = $row['code_1C'];
                    //print_r($params2);
                    $result = $client2->SaveClient2($params2);
                    $array_save = objectToArray($result);
                    $res_save_1c = $array_save['return'];
                    $dbc->element_update('sto', $row['id'], array(
                        "code_1C" => $res_save_1c));
                }
                else{
                    // client update
                    $sto_id = $row3['id'];
                    $dbc->element_update('sto',$row3['id'],$client);
                    $row2 = $dbc->element_find_by_field('sto_tochka','code',$code_sto);
                    $dbc->element_update('sto',$row3['id'],array(
                        "sto_tochka_id" => $row2['id']));

                    ini_set("soap.wsdl_cache_enabled", "0" );
                    $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                        array(
                            'login' => 'ws',
                            'password' => '123456',
                            'trace' => true
                        )
                    );
                    $row = $dbc->element_find('sto',$sto_id);
                    $params2["Client"]["Name"] = $row['name'];
                    $params2["Client"]["Iin"] = $row['iin'];
                    $params2["Client"]["GosNomer"] = $row['gn'];
                    $params2["Client"]["Mark"] = $row['mark'];
                    $params2["Client"]["Model"] = $row['model'];
                    $params2["Client"]["GodVypusk"] = $row['born'];
                    $params2["Client"]["StatusTO"] = $row['status_to'];
                    $params2["Client"]["DateOfEndTO"] = date("Y-m-d",strtotime($row['date_to_end']));
                    $params2["Client"]["Summa"] = $row['summa'];
                    if($row['strach']==0){
                        $strach = false;
                    }
                    else{
                        $strach = true;
                    }
                    $params2["Client"]["HasPolic"] = $strach;
                    $params2["Client"]["InsuranseCompany"] = $row['strach_company'];
                    $params2["Client"]["DateBegin"] = date("Y-m-d",strtotime($row['date_strach_start']));
                    $params2["Client"]["DateEnd"] = date("Y-m-d",strtotime($row['date_strach_end']));
                    $params2["Client"]["Telefon"] = $row['phone'];
                    $params2["Client"]["Email"] = $row['email'];
                    $params2["Client"]["Sto"] = $code_sto;
                    $params2["Client"]["Code1C"] = $row['code_1C'];
                    $result = $client2->SaveClient2($params2);
                }
            }





            $out_row['res'] = 1;
            break;
        case 'edt':
            //$array = objectToArray(json_decode($_GET['client']));
            $client = array();
            //print_r($_GET);
            foreach ($_GET as $key=>$v){
                $client[$key] = base64_decode($v);
            }
            $code_sto = $client['sto_code'];
            unset($client['type']);
            unset($client['id']);
            unset($client['sto_tochka_id']);
            unset($client['controller']);
            unset($client['action']);
            unset($client['sto_code']);
            unset($client['oper_id']);
            //print_r($client);

            $row = $dbc->element_find_by_field('sto','code_1C',$client['code_1C']);
            //echo $dbc->outsql."\n";
            $sto_id = $row['id'];
            $dbc->element_update('sto',$row['id'],$client);
            //echo $dbc->outsql;
            $row2 = $dbc->element_find_by_field('sto_tochka','code',$code_sto);
            $dbc->element_update('sto',$row['id'],array(
                "sto_tochka_id" => $row2['id']));

            ini_set("soap.wsdl_cache_enabled", "0" );
            $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                array(
                    'login' => 'ws',
                    'password' => '123456',
                    'trace' => true
                )
            );
            $row = $dbc->element_find('sto',$sto_id);
            $params2["Client"]["Name"] = $row['name'];
            $params2["Client"]["Iin"] = $row['iin'];
            $params2["Client"]["GosNomer"] = $row['gn'];
            $params2["Client"]["Mark"] = $row['mark'];
            $params2["Client"]["Model"] = $row['model'];
            $params2["Client"]["GodVypusk"] = $row['born'];
            $params2["Client"]["StatusTO"] = $row['status_to'];
            $params2["Client"]["DateOfEndTO"] = date("Y-m-d",strtotime($row['date_to_end']));
            $params2["Client"]["Summa"] = $row['summa'];
            if($row['strach']==0){
                $strach = false;
            }
            else{
                $strach = true;
            }
            $params2["Client"]["HasPolic"] = $strach;
            $params2["Client"]["InsuranseCompany"] = $row['strach_company'];
            $params2["Client"]["DateBegin"] = date("Y-m-d",strtotime($row['date_strach_start']));
            $params2["Client"]["DateEnd"] = date("Y-m-d",strtotime($row['date_strach_end']));
            $params2["Client"]["Telefon"] = $row['phone'];
            $params2["Client"]["Email"] = $row['email'];
            $params2["Client"]["Sto"] = $code_sto;
            $params2["Client"]["Code1C"] = $row['code_1C'];
            //echo "\n";
            //print_r($params2);
            $result = $client2->SaveClient2($params2);
            $array_save = objectToArray($result);
            $res_save_1c = $array_save['return'];
            //print_r($res_save_1c);
            $out_row['res'] = 1;
            break;
        case 'new':
            $client = array();
            //print_r($_GET);
            foreach ($_GET as $key=>$v){
                $client[$key] = base64_decode($v);
            }
            $code_sto = $client['sto_code'];
            unset($client['type']);
            unset($client['id']);
            unset($client['sto_tochka_id']);
            unset($client['controller']);
            unset($client['action']);
            unset($client['sto_code']);
            //print_r($client);

            /*$row = $dbc->element_find_by_field('sto','code_1C',$client['code_1C']);
            $dbc->element_update('sto',$row['id'],$client);*/
            //echo $dbc->outsql;
            $row2 = $dbc->element_find_by_field('sto_tochka','code',$code_sto);

            $dbc->element_create('sto',array(
                "sto_tochka_id" => $row2['id'],
                "name" => $client['name'],
                "oper_id" => 0,
                "iin" => $client['iin'],
                "gn" => $client['gn'],
                "pn" => $client['pn'],
                "mark" => $client['mark'],
                "model" => $client['model'],
                "born" => $client['born'],
                "phone" => $client['phone'],
                "email" => $client['email'],
                "date_to_end" => date("Y-m-d",strtotime($client['date_to_end'])),
                "status_to" => $client['status_to'],
                "summa" => $client['summa'],
                "bn" => $client['bn'],
                "strach" => $client['strach'],
                "strach_company" => $client['strach_company'],
                "date_strach_start" => date("Y-m-d",strtotime($client['date_strach_start'])),
                "date_strach_end" => date("Y-m-d",strtotime($client['date_strach_end'])),
                "visit" => 1,
                "date_visit" => 'NOW()'
            ));
            $c_id = $dbc->ins_id;

            $row = $dbc->element_find('sto',$c_id);

            ini_set("soap.wsdl_cache_enabled", "0" );
            $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                array(
                    'login' => 'ws',
                    'password' => '123456',
                    'trace' => true
                )
            );

            $params2["Client"]["Name"] = $row['name'];
            $params2["Client"]["Iin"] = $row['iin'];
            $params2["Client"]["GosNomer"] = $row['gn'];
            $params2["Client"]["Mark"] = $row['mark'];
            $params2["Client"]["Model"] = $row['model'];
            $params2["Client"]["GodVypusk"] = $row['born'];
            $params2["Client"]["StatusTO"] = $row['status_to'];
            $params2["Client"]["DateOfEndTO"] = date("Y-m-d",strtotime($row['date_to_end']));
            $params2["Client"]["Summa"] = $row['summa'];
            if($row['strach']==0){
                $strach = false;
            }
            else{
                $strach = true;
            }
            $params2["Client"]["HasPolic"] = $strach;
            $params2["Client"]["InsuranseCompany"] = $row['strach_company'];
            $params2["Client"]["DateBegin"] = date("Y-m-d",strtotime($row['date_strach_start']));
            $params2["Client"]["DateEnd"] = date("Y-m-d",strtotime($row['date_strach_end']));
            $params2["Client"]["Telefon"] = $row['phone'];
            $params2["Client"]["Email"] = $row['email'];
            $params2["Client"]["Sto"] = $code_sto;
            $params2["Client"]["Code1C"] = $row['code_1C'];
            //print_r($params2);
            $result = $client2->SaveClient2($params2);
            $array_save = objectToArray($result);
            $res_save_1c = $array_save['return'];
            $dbc->element_update('sto',$row['id'],array(
                "code_1C" => $res_save_1c));
            //print_r($res_save_1c);
            $out_row['res'] = 1;
            break;
        case 'sum':
            $cost = array();
            //print_r($_GET);
            foreach ($_GET as $key=>$v){
                $cost[$key] = base64_decode($v);
            }
            $code_sto = $cost['sto_code'];
            unset($cost['type']);
            unset($cost['id']);
            unset($cost['sto_tochka_id']);
            unset($cost['controller']);
            unset($cost['action']);
            unset($cost['sto_code']);
            //print_r($cost);
            $row2 = $dbc->element_find_by_field('sto_tochka','code',$code_sto);

            $dbc->element_create('costs',array(
                "sto_tochka_id" => $row2['id'],
                "title" => $cost['title'],
                "summa" => $cost['summa'],
                "date_cost" => $cost['date_cost']
            ));

            ini_set("soap.wsdl_cache_enabled", "0" );
            $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
                array(
                    'login' => 'ws',
                    'password' => '123456',
                    'trace' => true
                )
            );

            $params2["Rashod"]["Name"] = $cost['title'];
            $params2["Rashod"]["Sto"] = $code_sto;
            $params2["Rashod"]["Summa"] = $cost['summa'];
            //print_r($params2);
            $result = $client2->SaveRashod($params2);

            $out_row['res'] = 1;
            break;
    }
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);