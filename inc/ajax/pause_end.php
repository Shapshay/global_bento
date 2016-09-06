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
		"oper_act_id" => 13,
		"pause_id" => $_POST['PauseType'],
		"date_log" => 'NOW()'));
	$rows = $dbc->dbselect(array(
			"table"=>"pause_type",
			"select"=>"id, title"
		)
	);
	$pause_types = '';
	foreach($rows as $row){
		$pause_types.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
	}
	$out_row['result'] = 'OK';
	$out_row['html'] = '<div id="close_response"><a href="javascript:void();" onclick="closePause();"><img src="images/close.png" /></a></div>
Укажите причину паузы:
<div>
<select id="PauseType" class="PauseType">
'.$pause_types.'
</select>
</div>
<a href="javascript:StartPause();" class="myButton2" style="margin-top:40px;">ПАУЗА</a>';
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
