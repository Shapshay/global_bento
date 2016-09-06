<?php
# SETTINGS #############################################################################
$moduleName = "opers_act";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."html");

$rows5 = $dbc->dbselect(array(
		"table"=>"oper_log",
		"select"=>"users.name AS name, 
				MAX(oper_log.date_log) AS date_log,
				SUM(oper_log.noact_count) AS noact_count",
		"joins"=>"LEFT OUTER JOIN users ON oper_log.oper_id = users.id",
		"where"=>"oper_log.date_log >= ADDDATE(NOW(), INTERVAL -5 MINUTE)",
		"group"=>"oper_log.oper_id",
		"order"=>"date_log",
		"order_type"=>"DESC"
	)
);
$activ_opers = '';
$i = 0;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows5 as $row5){
		$activ_opers.= '<tr>
				<td align="left">'.date("H:i:s d-m-Y",strtotime($row5['date_log'])).'</td>
				<td class="grey" align="left">'.$row5['name'].'</td>
				<td class="grey" align="left">'.$row5['noact_count'].'</td>
				</tr>';
		$i++;
	}
}
$tpl->assign("R_ACTIV_OPERS", $activ_opers);
$tpl->assign("R_ACTIV_ITOG", $i);


$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>