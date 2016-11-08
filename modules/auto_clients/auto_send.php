<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 02.11.2016
 * Time: 15:21
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['DOZVON_ID'])){
    $row = $dbc->element_find('dozvon_log', $_POST['DOZVON_ID']);
    if($row['dozvon']==1){
        $out_row['result'] = 'OK';
    }
    else{
        $out_row['result'] = 'Send';
    }
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);