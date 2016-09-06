<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

$row = $dbc->element_find('gift_types',$_POST['GiftID']);
$row2 = $dbc->element_find('polises',$_POST['POLIS_ID']);

$rows3 = $dbc->dbselect(array(
	"table"=>"gifts",
	"select"=>"gifts.id AS id,
				gift_types.title AS  gift,
				gift_types.uchet AS  uchet,
				gift_types.summa AS  summa",
	"where"=>"gifts.polis_id = ".$_POST['POLIS_ID']));
$gifts_sum = 0;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows3 as $row3){
		if($row3['uchet']==1){
			$gifts_sum+= $row3['summa'];
		}
	}
}

$sum_limit = $row2['premium']/100*$_POST['GIFT_PROC'];
$sum_limit = ceil($sum_limit/50) * 50;
if($row['uchet']==1){
	$itog_sum = $gifts_sum + $row['summa'];
}
else{
	$itog_sum = $gifts_sum;
}
if($row['id']==23){
	$ognet = 1;
}
else{
	$ognet = 0;
}

$row5 = $dbc->element_find('users',$_POST['ROOT_ID']);
$l_limit = $row5['l_limit']*100;
$sum_limit = min($sum_limit, $l_limit);

if($itog_sum>$sum_limit||($row2['premium']<10000&&$itog_sum>0)||($row2['premium']<10000&&$ognet>0)){
	$out_row['result'] = 'Err';
	$sum_limit = $sum_limit/100;
	$out_row['sum_limit'] = $sum_limit;
}
else{
	$dbc->element_create("gifts", array(
		"gift_type_id" => $_POST['GiftID'],
		"polis_id" => $_POST['POLIS_ID']));
	$row_id = $dbc->ins_id;

	$dbc->element_update('polises',$_POST['POLIS_ID'],array(
		"recalc" => 1,
		"summa"=>($row2['premium']-$itog_sum)));
	

	$out_row['result'] = 'OK';
	$out_row['gift']['id'] = $row_id;
	$out_row['gift']['title'] = $row['title'];
	$out_row['gift']['summa'] = $row['summa'];
	$out_row['gift']['itog'] = $itog_sum;
	
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
