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
			COUNT(control)-sum(control) as bad,
			COUNT(control_err_log.id) as errs",
		"joins"=>"LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id
			LEFT OUTER JOIN control_err_log ON control_log.id = control_err_log.control_log_id",
		"where"=>"DATE_FORMAT(control_log.date,'%Y-%m-%d')='".date("Y-m-d",strtotime($_POST['date_start']))."'",
		"group"=>"croots.name"));
}
else{
	$tpl->assign("DATE_NOW", date("Y-m-d"));
	$rows = $dbc->dbselect(array(
		"table"=>"control_log",
		"select"=>"croots.name AS control, 
			COUNT(control) as amount,
			sum(control) as good,
			COUNT(control)-sum(control) as bad,
			COUNT(control_err_log.id) as errs",
		"joins"=>"LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id
			LEFT OUTER JOIN control_err_log ON control_log.id = control_err_log.control_log_id",
		"where"=>"DATE_FORMAT(control_log.date,'%Y-%m-%d')='".date("Y-m-d")."'",
		"group"=>"croots.name"));
}

$all_count = 0;
$all_good = 0;
$all_bad = 0;
$all_err = 0;
//echo $dbc->outsql;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){

		$tpl->assign("SUPERVISER", $row['control']);
		$tpl->assign("AMOUNT", $row['amount']);
		$tpl->assign("GOOD", $row['good']);
		$tpl->assign("BAD", $row['bad']);
        $tpl->assign("ERRS_COUNT", $row['errs']);
		$all_count+= $row['amount'];
		$all_good+= $row['good'];
		$all_bad+= $row['bad'];
        $all_err+= $row['errs'];

		$tpl->parse("SUPERVISER_ROWS", ".".$moduleName."car_row");
	}
}
else{
	$tpl->assign("SUPERVISER_ROWS", '');
}
$tpl->assign("ALL_RESULT1", $all_count);
$tpl->assign("ALL_RESULT2", $all_good);
$tpl->assign("ALL_RESULT3", $all_bad);
$tpl->assign("ALL_RESULT4", $all_err);

$tpl->parse("GRAF", ".".$moduleName."graf");





$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>