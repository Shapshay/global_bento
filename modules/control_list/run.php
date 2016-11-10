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