<?php
# SETTINGS #############################################################################
$moduleName = "game_lider_today";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "u_row" => $prefix . "u_row.tpl",
		$moduleName . "u_row2" => $prefix . "u_row2.tpl",
));
# MAIN #################################################################################

if(date("His")<'180000'){
	header("Location: /".getItemCHPU(2202,'pages'));
	exit;
}

$rows = $dbc->dbselect(array(
		"table"=>"calls_log, users",
		"select"=>"users.name AS oper,
			users.av AS av,
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td",
		"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
			calls_log.oper_id = users.id AND 
			DATE_FORMAT(calls_log.date_start, '%Y%m%d') = '".date("Ymd")."'",
		"group"=>"oper",
		"order"=>"td",
		"order_type"=>"DESC",
		"limit"=>3
	)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	$i=1;
	foreach($rows as $row){
		$tpl->assign("LIDER_NAME", $row['oper']);
		$tpl->assign("LIDER_AMOUNT", $row['td']);
		if($row['av']==''){
			$av = 'images/gollum.jpg';
		}
		else{
			$av = 'uploads/avatars/full/'.$row['av'];
		}

		$tpl->assign("LIDER".$i."_AV", $av);

		$tpl->parse("LIDERS_ROWS", ".".$moduleName."u_row");
		$i++;
	}
}
else{
	$tpl->assign("LIDERS_ROWS",'');
}


$rows3 = $dbc->dbselect(array(
		"table"=>"polises",
		"select"=>"users.name AS oper,
			users.av AS av,
			COUNT(polises.id) as pcount",
		"joins"=>"LEFT OUTER JOIN users ON polises.oper_id = users.id ",
		"where"=>"polises.status > 0 AND DATE_FORMAT(polises.date_write,'%Y-%m-%d') = '".date('Y-m-d')."'",
		"group"=>"oper",
		"order"=>"pcount",
		"order_type"=>"DESC",
		"limit"=>3
	)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows3 as $row3){
		$tpl->assign("P_LIDER_NAME", $row3['oper']);
		$tpl->assign("P_LIDER_AMOUNT", $row3['pcount']);
		if($row3['av']==''){
			$av = 'images/gollum.jpg';
		}
		else{
			$av = 'uploads/avatars/full/'.$row['av'];
		}

		$tpl->assign("LIDER".$i."_AV", $av);

		$tpl->parse("LIDERS2_ROWS", ".".$moduleName."u_row2");
		$i++;
	}
}
else{
	$tpl->assign("LIDERS2_ROWS",'');
}

//$url = "http://192.168.0.128/system.php?menu=219"; //здесь в кавычках вводите ссылку
$url = "/".getItemCHPU($_GET['menu'],'pages');
$tpl->assign("META_LINK", '<meta http-equiv="refresh" content="30; url='.$url.'" />');

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>