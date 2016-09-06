<?php
# SETTINGS #############################################################################
$moduleName = "print_menu";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################

$rows = $dbc->dbselect(array(
	"table"=>"pages",
	"select"=>"*",
	"where"=>"parent_id = 2200 OR id = 2200",
	"order"=>"sortfield"));
$sv_menu = '';
foreach($rows as $row){
	$url = "/".getItemCHPU($row['id'],'pages'); //здесь в кавычках вводите ссылку
	$sv_menu.= '<p><a href="'.$url.'" class="sv_menu">'.$row['title'].'</a></p>';
}
$tpl->assign("PRINT_LINKS", $sv_menu);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>