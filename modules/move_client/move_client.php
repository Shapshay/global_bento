<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 29.09.2016
 * Time: 11:36
 */
error_reporting (E_ALL);
ini_set("display_errors", "1");
date_default_timezone_set ("Asia/Almaty");

// SOAP
function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}
function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key => &$field){
        if(is_object($field))$field = $this->stdToArray($field);
    }
    return $rc;
}



if(isset($_POST['client_code1c'])){
    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params["client_code1c"] = $_POST['client_code1c'];
    $params["super_code1c"] = $_POST['super_code1c'];
    $params["manager_code1c"] = $_POST['manager_code1c'];
    $params["description"] = $_POST['description'];
    $result = $client->MoveClient($params);
    $array = objectToArray($result);
    $u_arr = $array['return'];

    $out_row['result'] = 'OK';
    $out_row['return'] = $u_arr;
}
else{
    $out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);