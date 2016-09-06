<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

if(isset($_POST['start_timer'])){
	$start_mil = $_POST['start_timer'];
	$start_seconds = $start_mil / 1000;
	$start =  date("Y-m-d H:i:s", $start_seconds);
}
else{
	$start =  '1970-01-01 06:00:00';
}

if(isset($_POST['stop_timer'])){
	$stop_mil = $_POST['stop_timer'];
	$stop_seconds = $stop_mil / 1000;
	$stop =  date("Y-m-d H:i:s", $stop_seconds);
}
else{
	$stop =  '1970-01-01 06:00:00';
}

if(isset($_POST['post_timer_start'])){
	$post_timer_start_mil = $_POST['post_timer_start'];
	$post_timer_start_seconds = $post_timer_start_mil / 1000;
	$post_timer_start =  date("Y-m-d H:i:s", $post_timer_start_seconds);
}
else{
	$post_timer_start =  '1970-01-01 06:00:00';
}

$soedinenie = $_POST['Soedinenie'];

switch($soedinenie){
	case 0:
		$dbc->element_create("pryanik", array(
			"oper_id" => $_POST['ROOT_ID'],
			"phone" => $_POST['phoneControl'],
			"date_start" => $start,
			"date_call" => $stop,
			"post_timer_start" => $post_timer_start,
			"soedinenie" => $soedinenie
			));
	break;
	case 1:
		$sql = "UPDATE pryanik SET 
			date = NOW(), 
			date_call = '".$stop."',
			soedinenie = '".$soedinenie."'
			WHERE oper_id = '".$_POST['ROOT_ID']."' AND phone = '".$_POST['phoneControl']."'";
		$dbc->element_free_update($sql);
	break;
	case 2:
		$sql = "UPDATE pryanik SET 
			date = NOW(), 
			post_timer_start = '".$post_timer_start."',
			soedinenie = '".$soedinenie."'
			WHERE oper_id = '".$_POST['ROOT_ID']."' AND phone = '".$_POST['phoneControl']."'";
		$dbc->element_free_update($sql);
	break;
}


$out_row['result'] = 'OK';
$out_row['sql'] = '$sql = '.$sql;

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>