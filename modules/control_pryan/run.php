<?php
# SETTINGS #############################################################################
$moduleName = "control_pryan";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "grid" => $prefix . "grid.tpl",
		$moduleName . "insurer_row" => $prefix . "insurer_row.tpl",
		$moduleName . "graf2" => $prefix . "graf2.tpl",
));
# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."grid");

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


$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>