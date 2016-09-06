<?php
# SETTINGS #############################################################################
$moduleName = "offices";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "item_row" => $prefix . "item_row.tpl",
));
# MAIN #################################################################################


if(isset($_POST['item_id'])){
	
	switch($_POST['item_id']){
		case 0:{
			$dbc->element_create("offices",array(
				"city_id" => $_POST['city_id'], 
				"title" => $_POST['title'],
				"fsw_ip" => $_POST['fsw_ip'],
				"adres" => $_POST['adres']));
			break;
		}
		default:{
			$dbc->element_update('offices',$_POST['item_id'],array(
				"city_id" => $_POST['city_id'], 
				"title" => $_POST['title'],
                "fsw_ip" => $_POST['fsw_ip'],
				"adres" => $_POST['adres']));
			break;
		}
	}
	header("Location: system.php?menu=".$_GET['menu']);
	exit;
}


$rows = $dbc->dbselect(array(
			"table"=>"offices",
			"select"=>"offices.id as id, 
				city.title as city,
				offices.title as title, 
				offices.adres as adres",
			"joins"=>"LEFT OUTER JOIN city ON offices.city_id = city.id"
			)
		);
$numRows = $dbc->count; 
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("ITEM_ID", $row['id']);
		
		$tpl->assign("EDT_CITY_ID", $row['city']);
		$tpl->assign("EDT_TITLE", $row['title']);
		$tpl->assign("EDT_ADRES", $row['adres']);
								
		
		$tpl->parse("ITEM_ROWS", ".".$moduleName."item_row");
	}
}
else{
	$tpl->assign("ITEM_ROWS", '');
}
$tpl->assign("DATE_NOW", date("d-m-Y H:i"));

$rows2 = $dbc->dbselect(array(
		"table"=>"city",
		"select"=>"*"
	)
);
$city_sel = '';
foreach($rows2 as $row2){
	$city_sel.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
}
$tpl->assign("EDT_CITY_SEL", $city_sel);

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), ".".$moduleName);
?>