<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 15.08.2016
 * Time: 16:03
 */
# SETTINGS #############################################################################
$moduleName = "print_cour_err";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
    $moduleName => $prefix . $moduleName.".tpl",
    $moduleName . "main" => $prefix . "main.tpl",
    $moduleName . "html" => $prefix . "html.tpl",
    
));
# MAIN #################################################################################

if(isset($_POST['pc_err'])){
    $pol_arr = '';
    $c_id = getUserIdFromCode1C($_POST['cour_id']);
    $client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
        $params2["bso_number"] = getPolisBsoFromId($_POST['pc_err']);
        $params2["manager_code"] = $c_id;
        $result = $client2->PutPolicToTrash($params2);
        $array_save = objectToArray($result);
        $res_save_1c = $array_save['return'];

        if($res_save_1c=='Успешно') {

            $dbc->element_update('polises',$_POST['pc_err'],array(
                "status" => 5,
                "date_err" => 'NOW()'));
            $SQL = "UPDATE cour_polis SET
				stat_ok = 2
				WHERE 
				c_id = ".$_POST['cour_id']." AND 
				polis_id = ".$_POST['pc_err'];
            $dbc->element_free_update($SQL);
        }
        else{
            echo "<p>Ошибка сохранения в 1C !<br>".$res_save_1c;
        }
}

if(isset($_POST['pc_err2'])){
    $pol_arr = '';
    $c_id = getUserIdFromCode1C($_POST['cour_id']);
    $client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params2["polic_number"] = getPolisBsoFromId($_POST['pc_err2']);
    $params2["manager_code"] = $c_id;
    $params2["clear"] = 0;
    $result = $client2->ClearSetPolicCurier($params2);
    $array_save = objectToArray($result);
    $res_save_1c = $array_save['return'];

    if($res_save_1c=='Успешно') {

        $dbc->element_update('polises',$_POST['pc_err2'],array(
            "status" => 3,
            "type_cour_err" => 0,
            "date_err" => 'NOW()'));
        $SQL = "UPDATE cour_polis SET
				stat_ok = 0
				WHERE 
				c_id = ".$_POST['cour_id']." AND 
				polis_id = ".$_POST['pc_err2'];
        $dbc->element_free_update($SQL);
    }
    else{
        echo "<p>Ошибка сохранения в 1C !<br>".$res_save_1c;
    }
}




$tpl->parse("META_LINK", ".".$moduleName."html");



$rows = $dbc->dbselect(array(
    "table"=>"polises",
    "select"=>"polises.id AS id, 
				polises.bso_number AS bso_number, 
				polises.date_cour_err AS date_cour_err, 
				users.name AS cour,
				users.id AS cour_id,
				err_types.title AS err",
    "joins"=>"LEFT OUTER JOIN users ON polises.cour_dost = users.id 
				LEFT OUTER JOIN err_types ON polises.type_cour_err = err_types.id ",
    "where"=>"(polises.status = '8' OR polises.status = '9') AND polises.office_id = ".ROOT_OFFICE,
    "order"=>"date_write"));

$numRows = $dbc->count;
$table_rows = '';
$edt_url = '/'.getItemCHPU($_GET['menu'], 'pages').'/?polis_view=';
if ($numRows > 0) {
    foreach($rows as $row){
        $table_rows.= '<tr>
								<td>'.$row['cour'].'</td>
								<td>'.$row['bso_number'].'</td>
								<td>'.date("d-m-Y",strtotime($row['date_cour_err'])).'</td>
								<td>'.$row['err'].'</td>
                                <td>
                                <form method="post">
                                <input type="hidden" name="pc_err" value="'.$row['id'].'">
                                <input type="hidden" name="cour_id" value="'.$row['cour_id'].'">
                                <button type="submit" class="btn_cour_err">Отработано</button>
                                </form>
                                </td>
                                <td>
                                <form method="post">
                                <input type="hidden" name="pc_err2" value="'.$row['id'].'">
                                <input type="hidden" name="cour_id" value="'.$row['cour_id'].'">
                                <button type="submit" class="btn_cour">Вернуть</button>
                                </form>
                                </td>
				</tr>';
    }
}
$tpl->assign("TABLE_ROWS", $table_rows);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");