<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");


if(!isset($_POST['liter'])){
	$rows3 = $dbc->dbselect(array(
		"table"=>"salem_models",
		"select"=>"make, make_id",
		"group"=>"make_id"));
}
else{
	$rows3 = $dbc->dbselect(array(
		"table"=>"salem_models",
		"select"=>"make, make_id",
		"where"=>"make LIKE '".$_POST['liter']."%'",
		"group"=>"make_id"));
}
$marks = array();
$numRows = $dbc->count;
if ($numRows > 0) {
	$i=0;
	$out_row['result'] = 'OK';
	foreach($rows3 as $row3){
		$out_row['marks'][$i]['id'] = $row3['make_id'];
		$out_row['marks'][$i]['title'] = $row3['make'];
		$i++;
	}
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>
