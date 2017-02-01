<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 31.01.2017
 * Time: 15:35
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

// Текст ошибки по коду
function getPolisErrForCode($code) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"err_types",
            "select"=>"title",
            "where"=>"id = '".$code."'",
            "limit"=>"1"
        )
    );
    $row = $rows[0];
    return $row['title'];
}

// ID телефона клиента по номеру
function verCourPolis($c_id, $p_id) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"cour_polis",
            "select"=>"id",
            "where"=>"c_id = '".$c_id."' AND polis_id = '".$p_id."'",
            "limit"=>"1"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row = $rows[0];
        return $row['id'];
    }
    else{
        return 0;
    }
}
######################################################################################################################

if(isset($_GET['code1C'])){
    $u_id = getOperCode1CId($_GET['code1C']);
        if($u_id>0){
            $row = $dbc->element_find('users',$u_id);
            $out_row['success'] = 1;
            $out_row['u_id'] = $u_id;
            $out_row['name'] = $row['name'];
            $out_row['message'] = "Пользователь найден !";
        }
        else{
            $out_row['success'] = 2;
            $out_row['message'] = "Пользователь ненайден !";
        }
}
else{
    if(isset($_GET)) {
        $out_row['success'] = 3;
        $out_row['message'] = "No GET !";
    }
    else{
        $out_row['success'] = 4;
        $out_row['message'] = "GET=".$_GET;
    }
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);