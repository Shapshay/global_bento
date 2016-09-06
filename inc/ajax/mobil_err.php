<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 07.06.2016
 * Time: 11:38
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
//$_POST['code1C'] = '0515-0347-4';
//$_POST['BSO'] = 'sdfdsfds';
if(isset($_POST['code1C'])){
    $u_id = getOperCode1CId($_POST['code1C']);
    if($u_id>0){
        $row = $dbc->element_find('users',$u_id);
        $row2 = $dbc->element_find_by_field('polises','bso_number',$_POST['BSO']);
        $numRows = $dbc->count;
        if($numRows>0){
            $dbc->element_update('polises',$row2['id'],array(
                "status" => 8,
                "cour_err" => $u_id,
                "date_cour_err" => 'NOW()'));

            $client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
                array(
                    'login' => 'ws',
                    'password' => '123456',
                    'trace' => true
                )
            );

            $params7['bso_number'] = $_POST['BSO'];
            $params7['manager_code'] = $_POST['code1C'];
            $result7 = $client7->PutPolicToError($params7);
            
            /*$params7['bso_number'] = $_POST['BSO'];
            $params7['manager_code'] = $_POST['code1C'];
            $result7 = $client7->PutPolicToError($params7);*/
            $array_save = objectToArray($result7);
            $res_save_1c = $array_save['return'];

            if($res_save_1c=='Успешно') {
                $out_row['success'] = 1;
                $out_row['message'] = "Полису присвоен статус: Отказ клиента !";
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
/*header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;*/