<?php
# SETTINGS #############################################################################

$moduleName = "post_oper_log";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));

# MAIN #################################################################################

$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
$dateStart = date('d-m-Y',strtotime(date("d-m-Y", mktime()) . " - 3 day"));
$tpl->assign("EDT_DATE_START", $dateStart);
$tpl->assign("EDT_DATE_END", date("d-m-Y"));



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
