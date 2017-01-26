<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 05.12.2016
 * Time: 10:43
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['info_id'])&&$_POST['info_id']!=0){
    // запрос и инфо клиента

    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );

    $row = $dbc->element_find('clients',$_POST['info_id']);
    //$row['code_1C'] = 'C01448208';
    $params["ClientCode1C"] =$row['code_1C'];
    $result = $client->GetClientInfo($params);
    $array = objectToArray($result);
    $u_arr2 = $array['return'];
    $polises_table = '';
    if(isset($u_arr2['ClientInfo'][0])){
        foreach ($u_arr2['ClientInfo'] as $u_arr){
            $PolicDrivers = '';
            if(is_array($u_arr['LastPolicDrivers'])){
                foreach($u_arr['LastPolicDrivers'] as $v){
                    $PolicDrivers.= $v.'<br>';
                }
            }
            else{
                $PolicDrivers = $u_arr['LastPolicDrivers'];
            }


            $LastPolicCars = '';
            if(is_array($u_arr['LastPolicCars'])){
                $u_arr['LastPolicCars'] = array_unique($u_arr['LastPolicCars']);
                foreach($u_arr['LastPolicCars'] as $v){
                    $LastPolicCars.= $v.'<br>';
                }
            }
            else{
                $LastPolicCars.= $u_arr['LastPolicCars'].'<br>';
            }
            $polises_table.= '<p><strong>Номер полиса:</strong><br>
                '.$u_arr['LastPolicNumber'].'
                <p><strong>Дата последнего полиса:</strong><br>
                '.$u_arr['LastPolicDate'].'
                <p><strong>Премия:</strong><br>
                '.$u_arr['LastPolicPremium'].'
                <p><strong>Сумма к оплате:</strong><br>
                '.$u_arr['LastPolicSumm'].'
                <p><strong>Курьер:</strong><br>
                '.$u_arr['LastPolicCourier'].'
                <p><strong>Застрахованные:</strong><br>
                '.$PolicDrivers.'
                <p><strong>Автомобили:</strong><br />
                '.$LastPolicCars.'<br><hr><br>';
        }
    }
    else{
        if(isset($u_arr2['ClientInfo'])){
            $u_arr = $u_arr2['ClientInfo'];
            $PolicDrivers = '';
            if(is_array($u_arr['LastPolicDrivers'])){
                foreach($u_arr['LastPolicDrivers'] as $v){
                    $PolicDrivers.= $v.'<br>';
                }
            }
            else{
                $PolicDrivers = $u_arr['LastPolicDrivers'];
            }


            $LastPolicCars = '';
            if(is_array($u_arr['LastPolicCars'])){
                $u_arr['LastPolicCars'] = array_unique($u_arr['LastPolicCars']);
                foreach($u_arr['LastPolicCars'] as $v){
                    $LastPolicCars.= $v.'<br>';
                }
            }
            else{
                $LastPolicCars.= $u_arr['LastPolicCars'].'<br>';
            }
            $polises_table.= '<p><strong>Номер полиса:</strong><br>
                    '.$u_arr['LastPolicNumber'].'
                    <p><strong>Дата последнего полиса:</strong><br>
                    '.$u_arr['LastPolicDate'].'
                    <p><strong>Премия:</strong><br>
                    '.$u_arr['LastPolicPremium'].'
                    <p><strong>Сумма к оплате:</strong><br>
                    '.$u_arr['LastPolicSumm'].'
                    <p><strong>Курьер:</strong><br>
                    '.$u_arr['LastPolicCourier'].'
                    <p><strong>Застрахованные:</strong><br>
                    '.$PolicDrivers.'
                    <p><strong>Автомобили:</strong><br />
                    '.$LastPolicCars;
        }
        else{
            $polises_table = 'Нет данных в 1С!';
        }
    }




    $out_row['ClientCode1C'] = $params["ClientCode1C"];
    $out_row['result'] = 'OK';
    $out_row['html'] = $polises_table;
    //$out_row['html'] = $u_arr;
}
else{
    $out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;