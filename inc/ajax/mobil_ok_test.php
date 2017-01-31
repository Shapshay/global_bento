<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 31.01.2017
 * Time: 9:09
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
// ID клиента по коду 1С
function getOperCode1CId($code) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"id",
            "where"=>"login_1C = '".$code."'",
            "limit"=>"1"
        )
    );
    $row = $rows[0];
    return $row['id'];
}

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
######################################################################################################################

if(isset($_GET['code1C'])){
    $u_id = getOperCode1CId($_GET['code1C']);
    if($u_id>0){
        if(isset($_GET['BSO'])&&$_GET['BSO']!=''){
            if($_GET['BSO']='123456'){
                $res_save_1c = 'Успешно';
            }
            else{
                $res_save_1c = 'Полис неназначен курьеру !';
            }

            if($res_save_1c=='Успешно') {
                $out_row['success'] = 1;
                $out_row['message'] = "Полису присвоен статус: Доставлен !";
            }
            else{
                $out_row['success'] = 2;
                $out_row['message'] = $res_save_1c;
            }
        }
        else{
            $out_row['success'] = 2;
            $out_row['message'] = "Полис с указанным БСО ненайден !";
        }
    }
    else{
        $out_row['success'] = 2;
        $out_row['message'] = "Пользователь ненайден !";
    }
}
else{
    $out_row['success'] = 2;
    $out_row['message'] = "Пользователь ненайден !";
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);