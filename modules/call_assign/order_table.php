<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 10.11.2016
 * Time: 17:34
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['LOGIN_1C'])){
    $row = $dbc->element_find('offices',$_POST['ROOT_OFFICE']);
    ini_set("soap.wsdl_cache_enabled", "0");
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params["telnumber"] = $_POST['phone'];
    $params["Code1C"] = $_POST['LOGIN_1C'];
    $params["Debt"] = $row['code1c'];
    //print_r($params);
    $result = $client->OrderCallBack($params);

    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);