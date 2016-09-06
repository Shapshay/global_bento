<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

if(isset($_POST['ROOT_ID'])){
	$dbc->element_create("oper_log", array(
		"oper_id" => $_POST['ROOT_ID'],
		"oper_act_type_id" => 5,
		"oper_act_id" => 12,
		"pause_id" => $_POST['PauseType'],
		"date_log" => 'NOW()'));
	$html ='<div>
<input type="hidden" id="PauseType" value="'.$_POST['PauseType'].'">
</div>
<p><button class="btn_pero" onclick="javascript:ClosePause();" style="margin-top:100px;">ЗАВЕРШИТЬ ПАУЗУ</button></p>';
	$out_row['result'] = 'OK';
	$out_row['html'] = $html;
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
