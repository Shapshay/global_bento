<?php
# SETTINGS #############################################################################
$moduleName = "stat_post_ok";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
unset($_SESSION['c_id']);
unset($_SESSION['post_id']);
unset($_SESSION['1C']);
$url = "/?count=1"; //здесь в кавычках вводите ссылку
$tpl->assign("META_LINK", '<meta http-equiv="refresh" content="3; url='.$url.'" />');



$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>