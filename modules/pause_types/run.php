<?php
# SETTINGS #############################################################################
$moduleName = "pause_types";
# MAIN #################################################################################
$rows = $dbc->dbselect(array(
		"table"=>"pause_type",
		"select"=>"id, title"
	)
);
$pause_types = '';
foreach($rows as $row){
	$pause_types.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
}

$tpl->assign(strtoupper($moduleName), $pause_types);
		
?>