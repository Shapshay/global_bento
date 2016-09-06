<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

if(isset($_POST['OperCode1C'])){
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"*",
			"where"=>"id = '".$_POST['OperCode1C']."' AND stek = '1' AND date_stek>(NOW() - INTERVAL 2 MINUTE)",
			"limit"=>1
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		$out_row['result'] = 'OK';
		$rows3 = $dbc->dbselect(array(
				"table"=>"clients",
				"select"=>"*",
				"where"=>"oper_id = '".$_POST['OperCode1C']."' AND code_1C = '".$row['code_1C']."'",
				"limit"=>1
			)
		);
		$row3 = $rows3[0];
		$out_row['name'] = $row3['name'];
		$rows2 = $dbc->dbselect(array(
				"table"=>"phones",
				"select"=>"id, phone",
				"where"=>"client_id = ".$row3['id'],
				"limit"=>1
			)
		);
		$phones = '';
		foreach($rows2 as $row2){
			$phones.=$row2['phone'].' ';
		}
		$out_row['phones'] = $phones;
		$out_row['id'] = $row3['id'];

		$dbc->element_update('users',$row['id'],array(
			"stek" => 0));
	}
	else{
		$out_row['result'] = 'Err1';
	}

	
}
else{
	$out_row['result'] = 'Err2';
}

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);
?>
