<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################
$dbc->element_update('pryanik',$_POST['PryanID'],array(
    "obrab" => 1));

$out_row['result'] = 'OK';

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>