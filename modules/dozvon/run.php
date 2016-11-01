<?php
# SETTINGS #############################################################################

$moduleName = "dozvon";

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
$dateStart = date('d-m-Y');
$tpl->assign("EDT_DATE_START", $dateStart);

$offices='';
$office = ROOT_OFFICE;
$rows = $dbc->dbselect(array(
		"table"=>"offices",
		"select"=>"id, title"
	)
);
foreach($rows as $row){
	if($row['id']==ROOT_OFFICE){
		$sel_of = ' selected="selected"';
	}
	else{
		$sel_of = '';
	}
	$offices.='<option value="'.$row['id'].'"'.$sel_of.'>'.$row['title'];
}
$tpl->assign("OFFICES_ROWS", $offices);




$tpl->parse("META_LINK", ".".$moduleName."grid");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
