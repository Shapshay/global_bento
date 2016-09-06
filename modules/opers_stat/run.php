<?php
# SETTINGS #############################################################################

$moduleName = "opers_stat";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "stat_row" => $prefix . "stat_row.tpl",
));

# MAIN #################################################################################
$tpl->parse("META_LINK", ".".$moduleName."html");

$rows = $dbc->dbselect(array(
		"table"=>"calls_log",
		"select"=>"users.name as oper, 
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td,
			SUM(CASE WHEN calls_log.res=5 THEN 1 ELSE 0 END) as zun,
			SUM(CASE WHEN calls_log.res=6 THEN 1 ELSE 0 END) as hz,
			SUM(CASE WHEN calls_log.res=3 THEN 1 ELSE 0 END) as pos,
			SUM(CASE WHEN calls_log.res=1 THEN 1 ELSE 0 END) as nd,
			SUM(CASE WHEN calls_log.res=2 THEN 1 ELSE 0 END) as err,
			SUM(CASE WHEN calls_log.res=7 THEN 1 ELSE 0 END) as 4vpd,
			SUM(CASE WHEN calls_log.res=8 THEN 1 ELSE 0 END) as 4vph",
		"joins"=>"LEFT OUTER JOIN users ON calls_log.oper_id = users.id",
		"where"=>"DATE_FORMAT(date_end, '%Y%m%d') = '".date("Ymd")."' AND users.name<>''",
		"group"=>"oper",
		"order"=>"oper"
	)
);
$numRows = $dbc->count;
$all_td = 0;
$all_zun = 0;
$all_hz = 0;
$all_pos = 0;
$all_nd = 0;
$all_err = 0;
$all_4vpd = 0;
$all_4vph = 0;
$global_call = 0;
if ($numRows > 0) {
	$i = 1;
	foreach($rows as $row){
		$all_call = $row['oper']+$row['td']+$row['zun']+$row['hz']+$row['pos']+$row['nd']+$row['err']+$row['4vpd']+$row['4vph'];
		$tpl->assign("OPER", $row['oper']);
		$tpl->assign("TD", $row['td']);
		$tpl->assign("ZUN", $row['zun']);
		$tpl->assign("HZ", $row['hz']);
		$tpl->assign("POS", $row['pos']);
		$tpl->assign("ND", $row['nd']);
		$tpl->assign("ERR", $row['err']);
		$tpl->assign("4VPD", $row['4vpd']);
		$tpl->assign("4VPH", $row['4vph']);
		$tpl->assign("COUNT_CALL", $all_call);
		$all_td+= $row['td'];
		$all_zun+= $row['zun'];
		$all_hz+= $row['hz'];
		$all_pos+= $row['pos'];
		$all_nd+= $row['nd'];
		$all_err+= $row['err'];
		$all_4vpd+= $row['4vpd'];
		$all_4vph+= $row['4vph'];
		$global_call+= $all_call;

		$tpl->parse("STAT_ROWS", ".".$moduleName."stat_row");
		$i++;
	}
}
else{
	$tpl->assign("STAT_ROWS", '<tr><td colspan=9 align=center>Нет данных в этом периоде!</td></tr>');
}

$tpl->assign("ALL_TD", $all_td);
$tpl->assign("ALL_ZUN", $all_zun);
$tpl->assign("ALL_HZ",$all_hz);
$tpl->assign("ALL_POS", $all_pos);
$tpl->assign("ALL_ND", $all_nd);
$tpl->assign("ALL_ERR", $all_err);
$tpl->assign("ALL_4VPD", $all_4vpd);
$tpl->assign("ALL_4VPH",$all_4vph);
$tpl->assign("ALL_COUNT_CALL", $global_call);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
