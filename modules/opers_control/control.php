<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

$dbc->element_create("control_log",array(
    "root_id" => $_POST['ROOT_ID'],
    "oper_id" => $_POST['oper_id'],
    "phone" => $_POST['phone'],
    "control" => $_POST['Ocenka'],
    "res" => $_POST['res_id'],
    "date" => 'NOW()'));

$out_row['result'] = 'OK';
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
