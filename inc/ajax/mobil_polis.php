<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 04.06.2016
 * Time: 11:35
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

if(isset($_POST['code1C'])){
    if(!isset($_POST['status'])){
        // проверка оператора на существование в базе
        $u_id = getOperCode1CId($_POST['code1C']);
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
        if($_POST['status']==1){
            // Полис доставлен
            $u_id = getOperCode1CId($_POST['code1C']);
            if($u_id>0){
                $row = $dbc->element_find('users',$u_id);
                $row2 = $dbc->element_find_by_field('polises','bso_number',$_POST['BSO']);
                $numRows = $dbc->count;
                if($numRows>0){
                    if(verCourPolis($u_id, $row2['id'])>0){
                        $dbc->element_update('polises',$row2['id'],array(
                            "status" => 7,
                            "cour_dost" => $u_id,
                            "date_cour_dost" => 'NOW()'));
                        /*
                        $client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
                            array(
                                'login' => 'ws',
                                'password' => '123456',
                                'trace' => true
                            )
                        );
                        $params7['bso_number'] = $_POST['BSO'];
                        $params7['manager_code'] = $_POST['code1C'];
                        $result7 = $client7->PutPolicToDelivered($params7);
                        $params7['bso_number'] = $_POST['BSO'];
                        $params7['manager_code'] = $_POST['code1C'];
                        $result7 = $client7->PutPolicToDelivered($params7);
                        $array_save = objectToArray($result7);
                        $res_save_1c = $array_save['return'];

                        if($res_save_1c=='Успешно') {
                        */
                            $out_row['success'] = 1;
                            $out_row['message'] = "Полису присвоен статус: Доставлен !";
                        /*}
                        else{
                            $out_row['success'] = 2;
                            $out_row['message'] = $res_save_1c;
                        }*/
                    }
                    else{
                        $out_row['success'] = 2;
                        $out_row['message'] = "Полис назначен в доставку не Вам !";
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
            // Полис на ошибку
            $u_id = getOperCode1CId($_POST['code1C']);
            if($u_id>0){
                $row = $dbc->element_find('users',$u_id);
                $row2 = $dbc->element_find_by_field('polises','bso_number',$_POST['BSO']);
                $numRows = $dbc->count;
                if($numRows>0){
                    if(verCourPolis($u_id, $row2['id'])>0){
                        $dbc->element_update('polises',$row2['id'],array(
                            "status" => 8,
                            "cour_err" => $u_id,
                            "type_cour_err"=>$_POST['err_type'],
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
                        $params7['error_code'] = getPolisErrForCode($_POST['err_type']);
                        //$result7 = $client7->PutPolicToError($params7);
                        $result7 = $client7->ReWorkPolic($params7);
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
                        $out_row['message'] = "Полис назначен в доставку не Вам !";
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
    }
    
}
else{
    if(isset($_POST)) {
        $out_row['success'] = 3;
        $out_row['message'] = "No POST !";
    }
    else{
        $out_row['success'] = 4;
        $out_row['message'] = "POST=".$_POST;
    }
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);