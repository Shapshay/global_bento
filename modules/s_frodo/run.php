<?php
# SETTINGS #############################################################################

$moduleName = "s_frodo";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "r_add" => $prefix . "r_add.tpl",
	$moduleName . "r_edit" => $prefix . "r_edit.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid2" => $prefix . "grid2.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "baned_r" => $prefix . "baned_r.tpl",
	$moduleName . "baned_r2" => $prefix . "baned_r2.tpl",
	$moduleName . "rights" => $prefix . "rights.tpl",
	$moduleName . "rights_row" => $prefix . "rights_row.tpl",
	$moduleName . "ch_row" => $prefix . "ch_row.tpl",
	$moduleName . "view" => $prefix . "view.tpl",
	$moduleName . "frodo_row" => $prefix . "frodo_row.tpl",
	$moduleName . "frodo2_row" => $prefix . "frodo2_row.tpl",
	$moduleName . "oper_row" => $prefix . "oper_row.tpl",
	$moduleName . "log_call_row" => $prefix . "log_call_row.tpl",
	$moduleName . "oper_log_row" => $prefix . "oper_log_row.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));

$size_x = 200;
$size_y = 200;
$size_x2 = 50;
$size_y2 = 50;
$group_id = 1;
$out = '';
$maxFileSize = 500000;

$_order = " ORDER BY data_reg DESC";				// ������� ����������
$_anonsCount = 50;

# MAIN #################################################################################

if(!isset($_GET['act'])){
	$_GET['act'] = 'default';
}
switch ($_GET['act']) {
	default:{

		$rows = $dbc->dbselect(array(
			"table"=>"frodo2",
			"select"=>"DISTINCT frodo2.oper_id AS oper_id,
						users.name AS name",
			"joins"=>"LEFT OUTER JOIN users ON frodo2.oper_id = users.id",
			"where"=>"DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd")));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach ($rows as $row) {
				$tpl->assign("FRODO_OPER", $row['name']);
				$tpl->assign("FRODO_NABOR", OperCallFieldCount('nabor', $row['oper_id']));
				$tpl->assign("FRODO_MORE9", OperCallFieldCount('more9', $row['oper_id']));
				$tpl->assign("FRODO_EM", OperCallFieldCount('em', $row['oper_id']));

				$tpl->parse("FRODO2_ROWS", ".".$moduleName."frodo2_row");
			}
		}
		else{
			$tpl->assign("FRODO2_ROWS", '');
		}

		

		$tpl->parse("META_LINK", ".".$moduleName."grid");

		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
		break;
	}
}
?>
