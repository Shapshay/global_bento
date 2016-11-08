<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 04.11.2016
 * Time: 17:07
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

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
    $out_row['result'] = 'OK';
    $out_row['sql'] = $dbc->outsql;
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;