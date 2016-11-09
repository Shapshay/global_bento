<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

$dbc->element_delete('gifts',$_POST['GiftID']);


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

$row2 = $dbc->element_find('polises',$_POST['POLIS_ID']);

$dbc->element_update('polises',$_POST['POLIS_ID'],array(
	"recalc" => 1,
	"summa"=>($row2['premium']-$gifts_sum)));

$out_row['result'] = 'OK';
$out_row['gift']['itog'] = $gifts_sum;

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
