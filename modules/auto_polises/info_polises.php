<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 05.12.2016
 * Time: 10:43
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['info_polis_num'])){
    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    //$params["Code1C"] = LOGIN_1C;
    $params["PolicNumber"] = $_POST['info_polis_num'];
    $result = $client->GetPolicInfo($params);
    $array = objectToArray($result);
    $u_arr = $array['return']['PolicInfo'];

    if($u_arr['BSO']!=''){
        $Oplachen = '<img src="images/no.png" width="30" />';
        $Prov = '<img src="images/no.png" width="30" />';
        $Printed = '<img src="images/no.png" width="30" />';
        if($u_arr['Oplachen']){
            $Oplachen = '<img src="images/yes.png" width="30" />';
        }
        if($u_arr['Prov']){
            $Prov = '<img src="images/yes.png" width="30" />';
        }
        if($u_arr['Printed']){
            $Printed = '<img src="images/yes.png" width="30" />';
        }

        $polises_table = '<p><strong>БСО</strong><br>
            '.$u_arr['BSO'].'
            <p><strong>Клиент</strong><br>
            '.$u_arr['Client'].'
            <p><strong>Менеджер</strong><br>
            '.$u_arr['Manager'].'
            <p><strong>Дата</strong><br>
            '.date("d-m-Y",strtotime($u_arr['Date'])).'
            <p><strong>Статус</strong><br>
            '.$u_arr['Status'].'
            <p><strong>Оплачен</strong><br>
            '.$Oplachen.'
            <p><strong>Проведен</strong><br>
            '.$Prov.'
            <p><strong>Распечатан</strong><br>
            '.$Printed.'
            <p><strong>Курьер</strong><br>
            '.$u_arr['Curier'].'
            <p><strong>Сумма</strong><br>
            '.$u_arr['Summa'].'';
    }
    else{
        $polises_table = '<font color="#f00"><strong>Полис с данным номером ненайден !</strong></font>';
    }
    $out_row['result'] = 'OK';
    $out_row['html'] = $polises_table;
}
else{
    $out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;