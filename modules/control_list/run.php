<?php
# SETTINGS #############################################################################
$moduleName = "control_list";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "grid" => $prefix . "grid.tpl",
		$moduleName . "p_view" => $prefix . "p_view.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "insurer_row" => $prefix . "insurer_row.tpl",
		$moduleName . "car_row" => $prefix . "car_row.tpl",
		$moduleName . "graf" => $prefix . "graf.tpl",
		$moduleName . "graf2" => $prefix . "graf2.tpl",
	$moduleName . "list_row" => $prefix . "list_row.tpl",
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



if(isset($_POST['stat_send2'])){
	$tpl->assign("DATE_NOW2", date("Y-m-d",strtotime($_POST['date_pryan_start'])));
	$rows = $dbc->dbselect(array(
		"table"=>"pryanik",
		"select"=>"croots.name AS oper, 
			COUNT(date_start) as do_min,
			SUM(CASE WHEN post_timer_start='1970-01-01 06:00:00' THEN 0 ELSE 1 END) as posle_min,
			SUM(CASE WHEN obrab='1' THEN 1 ELSE 0 END) as count_obrab",
		"joins"=>"LEFT OUTER JOIN users as croots ON pryanik.oper_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d",strtotime($_POST['date_pryan_start']))."'",
		"group"=>"croots.name"));
}
else{
	$tpl->assign("DATE_NOW2", date("Y-m-d"));
	$rows = $dbc->dbselect(array(
		"table"=>"pryanik",
		"select"=>"croots.name AS oper, 
			COUNT(date_start) as do_min,
			SUM(CASE WHEN post_timer_start='1970-01-01 06:00:00' THEN 0 ELSE 1 END) as posle_min,
			SUM(CASE WHEN obrab='1' THEN 1 ELSE 0 END) as count_obrab",
		"joins"=>"LEFT OUTER JOIN users as croots ON pryanik.oper_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d")."'",
		"group"=>"croots.name"));
}

$all_pryan_count = 0;
$all_posle_min = 0;
$all_count_obrab = 0;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){

		$tpl->assign("OPERATOR", $row['oper']);
		$tpl->assign("DO_MIN", $row['do_min']);
		$tpl->assign("POSLE_MIN", $row['posle_min']);
		$tpl->assign("COUNT_OBRAB", $row['count_obrab']);
		$all_pryan_count+= $row['do_min'];
		$all_posle_min+= $row['posle_min'];
		$all_count_obrab+= $row['count_obrab'];


		$tpl->parse("PRAYNIK_ROWS", ".".$moduleName."insurer_row");
	}
}
else{
	$tpl->assign("PRAYNIK_ROWS", '');
}
$tpl->assign("ALL_PRYAN_RESULT1", $all_pryan_count);
$tpl->assign("ALL_PRYAN_RESULT2", $all_posle_min);
$tpl->assign("ALL_PRYAN_RESULT3", $all_count_obrab);

$tpl->parse("GRAF2", ".".$moduleName."graf2");




$rows = $dbc->dbselect(array(
	"table"=>"control_log",
	"select"=>"control_log.id AS id, 
				control_log.date AS date,
				croots.name AS control, 
				users.name AS oper,
				control_log.phone AS phone,
				control_log.control AS control_res",
	"joins"=>"LEFT OUTER JOIN users ON control_log.oper_id = users.id 
			LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id",
	"order"=>"date",
	"order_type"=>"DESC"));
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		if($row['control_res']==1){
			$control = "ХОРОШО";
		}
		else{
			$control = "ПЛОХО";
		}
		$tpl->assign("CONTROL_ALL_ID", $row['id']);
		$tpl->assign("CONTROL_ALL_DATE", $row['date']);
		$tpl->assign("CONTROL_ALL_SV", $row['control']);
		$tpl->assign("CONTROL_ALL_OPER", $row['oper']);
		$tpl->assign("CONTROL_ALL_PHONE", $row['phone']);
		$tpl->assign("CONTROL_ALL_OCENKA", $control);

		$tpl->parse("CONTROL_ALL_ROWS", ".".$moduleName."list_row");
	}
}
else{
	$tpl->assign("CONTROL_ALL_ROWS", '');
}



$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>