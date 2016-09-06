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

######################################################################################################################
if(isset($_POST['ROOT_ID'])&&isset($_POST['RES_TYPE'])){
	if(getOperTodayFrodo($_POST['ROOT_ID'])!=0){
		switch ($_POST['RES_TYPE']) {
			case '1':
			case 1:
				$sql = "UPDATE frodo SET 
									calls = calls + 1
							WHERE oper_id = ".$_POST['ROOT_ID']." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd");
				break;
			case '2':
			case 2:
				$sql = "UPDATE frodo SET 
									nabor = nabor + 1
							WHERE oper_id = ".$_POST['ROOT_ID']." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd");
				break;
			case '3':
			case 3:
				$sql = "UPDATE frodo SET 
									sama = sama + 1
							WHERE oper_id = ".$_POST['ROOT_ID']." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd");
				break;
			case '4':
			case 4:
				$sql = "UPDATE frodo SET 
									client = client + 1
							WHERE oper_id = ".$_POST['ROOT_ID']." AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd");
				break;
		}
		$dbc->element_free_update($sql);
		
	}
	else{
		switch ($_POST['RES_TYPE']) {
			case '1':
			case 1:
				$dbc->element_create("frodo", array(
					"calls" => 1,
					"oper_id" => $_POST['ROOT_ID'],
					"date" => 'NOW()'));
				$out_row['result'] = 'Call';
				break;
			case '2':
			case 2:
				$dbc->element_create("frodo", array(
					"nabor" => 1,
					"oper_id" => $_POST['ROOT_ID'],
					"date" => 'NOW()'));
				$out_row['result'] = 'Nabor';
				break;
			case '3':
			case 3:
				$dbc->element_create("frodo", array(
					"sama" => 1,
					"oper_id" => $_POST['ROOT_ID'],
					"date" => 'NOW()'));
				$out_row['result'] = 'Sama';
				break;
			case '4':
			case 4:
				$dbc->element_create("frodo", array(
					"client" => 1,
					"oper_id" => $_POST['ROOT_ID'],
					"date" => 'NOW()'));
				$out_row['result'] = 'Client';
				break;
		}
	}

}
else{
	$out_row['result'] = 'Error';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);
?>
