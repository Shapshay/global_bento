<?php
# SETTINGS #############################################################################

$moduleName = "post_oper_calls";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));


$_order = " ORDER BY data_reg DESC";
$_anonsCount = 50;

# MAIN #################################################################################

$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
$dateStart = date('d-m-Y',strtotime(date("d-m-Y", mktime()) . " - 3 day"));
$tpl->assign("EDT_DATE_START", $dateStart);
$tpl->assign("EDT_DATE_END", date("d-m-Y"));
$res_calls='';
$rows = $dbc->dbselect(array(
		"table"=>"res_calls_post",
		"select"=>"id, title"
	)
);
foreach($rows as $row){
	$res_calls.='<option value="'.$row['id'].'">'.$row['title'];
}
$tpl->assign("RES_CALLS_ROWS", $res_calls);


$oper_rows='';
$rows = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"users.*, GROUP_CONCAT(r_user_role.role_id) as role",
		"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id",
		"group"=>"users.id",
		"order"=>"users.name"
	)
);
foreach($rows as $row){
	$this_role = explode(",",$row['role']);
	if(in_array(10,$this_role)){
		$oper_rows.='<option value="'.$row['id'].'">'.$row['name'];
	}
}
$tpl->assign("OPERS_ROWS", $oper_rows);


$tpl->parse("META_LINK", ".".$moduleName."grid");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
