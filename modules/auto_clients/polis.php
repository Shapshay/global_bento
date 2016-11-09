<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 08.11.2016
 * Time: 14:32
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

//############### SOAP ###################################################

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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['client_id'])){
    $is_car = 0;
    if(isset($_POST['car'])){
        $is_car = 1;
    }
    $is_dost = 0;
    if(isset($_POST['vp4_dost'])){
        $is_dost = 1;
    }
    $is_yur = 0;
    if(isset($_POST['vp4_yur'])){
        $is_yur = 1;
    }
    $is_ev = 0;
    if(isset($_POST['vp4_ev'])){
        $is_ev = 1;
    }
    $is_korgau = 0;
    if(isset($_POST['vp4_korgau'])){
        $is_korgau = 1;
    }
    $dbc->element_update('clients',$_POST['client_id'],array(
        "name" => addslashes($_POST['name']),
        "fio" => addslashes($_POST['name']),
        "iin" => $_POST['iin'],
        "email" => $_POST['email'],
        "city" => $_POST['city'],
        "is_car" => $is_car,
        "gn" => $_POST['gn'],
        "rating" => $_POST['rating'],
        "premium" => $_POST['premium'],
        "real_premium" => $_POST['real_premium'],
        "strach_id" => $_POST['strah'],
        "is_dost" => $is_dost,
        "is_yur" => $is_yur,
        "is_ev" => $is_ev,
        "is_korgau" => $is_korgau,
        "comment" => addslashes($_POST['call_comment']),
        "date_end" => date("Y-m-d",strtotime($_POST['date_end']))));

    // POLIS

    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params["ManagerCode"] = $_POST['LOGIN_1C'];
    $params["Company"] = 3;
    $result = $client->GetPolicyNumber($params);
    $array = objectToArray($result);
    $polis_num = $array['return'];
    
    $dbc->element_create("polises",array(
        "oper_id" => $_POST['ROOT_ID'],
        "client_id" => $_POST['client_id'],
        "bso_number" => $polis_num,
        "strach_comp_id" => 3,
        "office_id" => $_POST['ROOT_OFFICE'],
        "premium" => $_POST['premium'],
        "gn" => $_POST['gn'],
        "date_oform" => date("Y-m-d H:i"),
        "recalc" => 1,
        ));

    $p_id =$dbc->ins_id;

    $out_row['result'] = 'OK';
    $out_row['sql'] = $dbc->outsql;
    $out_row['polis'] = $p_id;
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;