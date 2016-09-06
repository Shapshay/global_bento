<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

$rows = $dbc->dbselect(array(
	"table"=>"pryanik",
	"select"=>"pryanik.*,
			users.name as oper",
	"joins"=>"LEFT OUTER JOIN users ON pryanik.oper_id = users.id",
	"where"=>"date > ADDDATE(NOW(), INTERVAL -10 MINUTE) AND obrab = 0",
	"order"=>"date",
	"order_type"=>"DESC"));
$numRows = $dbc->count;
if ($numRows > 0) {
	$i = 0;
	$out_row['result'] = 'OK';
	foreach($rows as $row){
		$out_row['urod'][$i]['id'] = $row['id'];
		$out_row['urod'][$i]['date'] = $row['date'];
		$out_row['urod'][$i]['oper'] = $row['oper'];
		$out_row['urod'][$i]['phone'] = $row['phone'];
		$out_row['urod'][$i]['date_start'] = $row['date_start'];
		if($row['date_call']=='1970-01-01 06:00:00'){
			$out_row['urod'][$i]['date_call'] = '-';
		}
		else{
			$out_row['urod'][$i]['date_call'] = $row['date_call'];
		}
		if($row['post_timer_start']=='1970-01-01 06:00:00'){
			$out_row['urod'][$i]['post_timer_start'] = '-';
		}
		else{
			$out_row['urod'][$i]['post_timer_start'] = $row['post_timer_start'];
		}
		
		//$out_row['urod'][$i]['soedinenie'] = $row['soedinenie'];
		
		$i++;
	}
}
else{
	$out_row['result'] = 'Err';
}

//$out_row['gift']['id'] = $row_id;
//$out_row['gift']['title'] = $row['title'];
//$out_row['gift']['summa'] = $row['summa'];
//$out_row['gift']['itog'] = $itog_sum;

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>