<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 14.11.2016
 * Time: 10:22
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

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
if(isset($_REQUEST['LOGIN_1C'])){
    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params["Code1C"] = $_REQUEST['LOGIN_1C'];

    $result = $client->GetMyCalls($params);
    $array = objectToArray($result);
    $u_arr = $array['return'];

    $calls_table = '<table id="stat_table_calls" class="display">
        <thead>
            <tr>
                <th>Дата</th>
                <th>Клиент</th>
                <th>Телефоны</th>
                <th>Статистика</th>
                <th>Длительность</th>
                <th>Комментарий</th>
            </tr>
        </thead>
        <tbody>';
    
    $i = 0;
    foreach($u_arr as $row2){
        foreach($row2 as $row){
            $calls_table.= '<tr>
					<td>'.date("H:i d-m-Y",strtotime($row['Date'])).'</td>
					<td>'.$row['Client'].'</td>
					<td>'.$row['Phones'].'</td>
					<td>'.$row['Statistics'].'</td>
					<td>'.$row['Duration'].'</td>
					<td>'.$row['Comments'].'</td>
					</tr>';
        }
    }
    $calls_table.= '</tbody></table>';
    $out_row['result'] = 'OK';
    $out_row['html'] = $calls_table;
    //echo $calls_table;
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;