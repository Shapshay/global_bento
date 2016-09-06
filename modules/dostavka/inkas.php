<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");


$polises_ok = TRUE;

$pol_arr = preg_split("/,/", $_POST['sList'], -1, PREG_SPLIT_NO_EMPTY);

foreach($pol_arr as $v){
	$rows = $dbc->dbselect(array(
			"table"=>"cour_polis",
			"select"=>"id",
			"where"=>"c_id = ".$_POST['cur']." AND polis_id = ".$v,
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows <= 0) {
		$polises_ok = FALSE;
	}
}


if(!$polises_ok){
	$out_row['result'] = 'Err';
}
else{
	$out_row['result'] = 'OK';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
