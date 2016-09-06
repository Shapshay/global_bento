<?php
# SETTINGS #############################################################################
$moduleName = "game_oper_today";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "u_row" => $prefix . "u_row.tpl",
));
# MAIN #################################################################################
//$GAME_IP = array('192.168.1.51','192.168.1.144');
//if(in_array($_SERVER["REMOTE_ADDR"], $GAME_IP)){
if(date("His")>='180000'){
	header("Location: https://192.168.0.128/komanda_lider_za_segodnya");
	exit;
}
$rows3 = $dbc->dbselect(array(
	"table"=>"polises",
	"select"=>"users.name AS oper,
		COUNT(polises.id) as pcount",
		"joins"=>"LEFT OUTER JOIN users ON polises.oper_id = users.id ",
	"where"=>"polises.status > 0 AND DATE_FORMAT(polises.date_write,'%Y-%m-%d') = '".date('Y-m-d')."'",
		"group"=>"oper",
	"order"=>"pcount",
	"order_type"=>"DESC",
		"limit"=>5
)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows3 as $row3){
		$tpl->assign("LIDER_NAME", $row3['oper']);
		$tpl->assign("LIDER_AMOUNT", $row3['pcount']);

		$tpl->parse("LIDERS_ROWS", ".".$moduleName."u_row");
	}
}
else{
	$tpl->assign("LIDERS_ROWS",'');
}

$rows = $dbc->dbselect(array(
		"table"=>"calls_log, users",
		"select"=>"users.name AS oper,
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td",
		"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
			calls_log.oper_id = users.id AND 
			DATE_FORMAT(calls_log.date_start, '%Y%m%d') = '".date("Ymd")."'",
		"group"=>"oper",
		"order"=>"td",
		"order_type"=>"DESC",
		"limit"=>5
	)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$tpl->assign("LIDER_NAME", $row['oper']);
		$tpl->assign("LIDER_AMOUNT", $row['td']);

		$tpl->parse("LIDERS_ROWS2", ".".$moduleName."u_row");
	}
}
else{
	$tpl->assign("LIDERS_ROWS2",'');
}

//$url = "https://192.168.0.128/system.php?menu=210"; //здесь в кавычках вводите ссылку
$url = "/".getItemCHPU($_GET['menu'],'pages');
$tpl->assign("META_LINK", '<meta http-equiv="refresh" content="10; url='.$url.'" />');

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

/*
}
else{
	header("Location: system.php");
	exit;
}
*/
?>