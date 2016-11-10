<?php
# SETTINGS #############################################################################
$moduleName = "control_svod";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "grid" => $prefix . "grid.tpl",
		$moduleName . "graf" => $prefix . "graf.tpl",
	    $moduleName . "car_row" => $prefix . "car_row.tpl",
));
# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."grid");

if(isset($_POST['stat_send'])){
	$tpl->assign("DATE_NOW", date("Y-m-d",strtotime($_POST['date_start'])));
	$rows = $dbc->dbselect(array(
		"table"=>"control_log",
		"select"=>"croots.name AS control, 
			COUNT(control) as amount,
			sum(control) as good,
			COUNT(control)-sum(control) as bad",
		"joins"=>"LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d",strtotime($_POST['date_start']))."'",
		"group"=>"croots.name"));
}
else{
	$tpl->assign("DATE_NOW", date("Y-m-d"));
	$rows = $dbc->dbselect(array(
		"table"=>"control_log",
		"select"=>"croots.name AS control, 
			COUNT(control) as amount,
			sum(control) as good,
			COUNT(control)-sum(control) as bad",
		"joins"=>"LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d")."'",
		"group"=>"croots.name"));
}

$all_count = 0;
$all_good = 0;
$all_bad = 0;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){

		$tpl->assign("SUPERVISER", $row['control']);
		$tpl->assign("AMOUNT", $row['amount']);
		$tpl->assign("GOOD", $row['good']);
		$tpl->assign("BAD", $row['bad']);
		$all_count+= $row['amount'];
		$all_good+= $row['good'];
		$all_bad+= $row['bad'];


		$tpl->parse("SUPERVISER_ROWS", ".".$moduleName."car_row");
	}
}
else{
	$tpl->assign("SUPERVISER_ROWS", '');
}
$tpl->assign("ALL_RESULT1", $all_count);
$tpl->assign("ALL_RESULT2", $all_good);
$tpl->assign("ALL_RESULT3", $all_bad);

$tpl->parse("GRAF", ".".$moduleName."graf");





$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>