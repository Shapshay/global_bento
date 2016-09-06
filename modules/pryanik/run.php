<?php
# SETTINGS #############################################################################
	
	$moduleName = "pryanik";
	
	$prefix = "./modules/".$moduleName."/";
	
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "result_row" => $prefix . "result_row.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "result" => $prefix . "result.tpl",
	));


# MAIN ##################################################################################
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	//echo date("Y-M-d H:i:s");
	
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>