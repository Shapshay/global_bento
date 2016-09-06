<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

function getOperTodayFrodo($oper_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"frodo",
			"select"=>"id",
			"where"=>"oper_id = ".$oper_id." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

function getOperLastUserToday($oper_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"frodo2",
			"select"=>"user_id",
			"where"=>"oper_id = ".$oper_id." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"order"=>"date",
			"order_type"=>"DESC",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['user_id'];
	}
	else{
		return 0;
	}
}

function getIDOperUserToday($oper_id, $u_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"frodo2",
			"select"=>"id",
			"where"=>"oper_id = ".$oper_id." AND user_id = ".$u_id." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"order"=>"date",
			"order_type"=>"DESC",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

function callLength($ID) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"frodo2",
			"select"=>"date",
			"where"=>"id = '".$ID."'",
			"limit"=>"1"
		)
	);
	$row = $rows[0];

	$date_start = $row['date'];
	$date_end = date("Y-m-d H:i:s");
	$sec = strtotime($date_end) - strtotime($date_start);
	
	return $sec;
}

######################################################################################################################
if(isset($_POST['ROOT_ID'])&&isset($_POST['RES_TYPE'])&&isset($_POST['U_ID'])){
	
	if(getOperLastUserToday($_POST['ROOT_ID'])==$_POST['U_ID']){
		
		$ID = getIDOperUserToday($_POST['ROOT_ID'], $_POST['U_ID']);
		switch ($_POST['RES_TYPE']) {
			case '1':
			case 1:
				$sql = "UPDATE frodo2 SET 
									nabor = nabor + 1,
									date = NOW()
							WHERE id = ".$ID;
				$dbc->element_create("oper_log", array(
					"oper_id" => $_POST['ROOT_ID'],
					"oper_act_type_id" => 1,
					"oper_act_id" => 10,
					"date_log" => 'NOW()'));
				break;
			case '2':
			case 2:
				if(callLength($ID)>3){
					$sql = "UPDATE frodo2 SET 
										more9 = more9 + 1
								WHERE id = ".$ID;
				}
				break;
			case '3':
			case 3:
				$sql = "UPDATE frodo2 SET 
									em = em + 1
							WHERE id = ".$ID;
				break;
			
		}
		$dbc->element_free_update($sql);
		
	}
	else{
		switch ($_POST['RES_TYPE']) {
			case '1':
			case 1:
				$dbc->element_create("frodo2", array(
					"oper_id" => $_POST['ROOT_ID'],
					"nabor" => 1,
					"user_id" => $_POST['U_ID'],
					"date" => 'NOW()'));
				$dbc->element_create("oper_log", array(
					"oper_id" => $_POST['ROOT_ID'],
					"oper_act_type_id" => 1,
					"oper_act_id" => 10,
					"date_log" => 'NOW()'));
				$out_row['result'] = 'Call';
				break;
		}
	}
	$out_row['result'] = 'Res: '.$_POST['RES_TYPE'];
	
}
else{
	$out_row['result'] = 'Error';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);
?>
