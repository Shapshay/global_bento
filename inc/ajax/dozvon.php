<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 31.10.2016
 * Time: 14:12
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################
if(isset($_POST['DOZVON_ID'])) {
    $dbc->element_update('dozvon_log',$_POST['DOZVON_ID'],array(
        "dozvon" => 1));
}
$out_row['result'] = 'DOZVON_ID_OK';

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);