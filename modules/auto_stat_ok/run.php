<?php
# SETTINGS #############################################################################
$moduleName = "auto_stat_ok";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
unset($_SESSION['c_id']);
unset($_SESSION['polis']);
unset($_SESSION['tech_id']);
unset($_SESSION['1C']);
unset($_SESSION['dozvon']);
$url = "/?count=1"; //здесь в кавычках вводите ссылку
$tpl->assign("META_LINK", '<meta http-equiv="refresh" content="3; url='.$url.'" />');



$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>