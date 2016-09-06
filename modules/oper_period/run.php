<?php
# SETTINGS #############################################################################

$moduleName = "oper_period";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "stat_row" => $prefix . "stat_row.tpl",
));

# MAIN #################################################################################
$tpl->parse("META_LINK", ".".$moduleName."html");
if(isset($_POST['stat_send'])){
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"res_calls.id as res_id,
				res_calls.title as result,
				COUNT(DISTINCT calls_log.id) as calls",
			"joins"=>"LEFT OUTER JOIN calls_log ON users.id = calls_log.oper_id
				LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id",
			"where"=>"users.id = ".$_POST['oper_id']." AND
				calls_log.date_end <> '0000-00-00 00:00:00' AND
				DATE_FORMAT(calls_log.date_end, '%Y%m%d%H%i') >= '".date("YmdHi",strtotime($_POST['date_start']))."' AND 
				DATE_FORMAT(calls_log.date_end, '%Y%m%d%H%i') <= '".date("YmdHi",strtotime($_POST['date_end']))."'",
			"group"=>"calls_log.res"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$i = 1;
		foreach($rows as $row){
			$rows2 = $dbc->dbselect(array(
					"table"=>"control_log",
					"select"=>"COUNT(DISTINCT control_log.id) as control,
						IFNULL(SUM(CASE WHEN control_log.control=1 THEN 1 ELSE 0 END),0) as hor,
						IFNULL(SUM(CASE WHEN control_log.control=0 THEN 1 ELSE 0 END),0) as ploh",
					"where"=>"res = ".$row['res_id']." AND
						oper_id = ".$_POST['oper_id']." AND
						DATE_FORMAT(control_log.date, '%Y%m%d%H%i') >= '".date("YmdHi",strtotime($_POST['date_start']))."' AND 
						DATE_FORMAT(control_log.date, '%Y%m%d%H%i') <= '".date("YmdHi",strtotime($_POST['date_end']))."'",
					"limit"=>1
				)
			);
			$row2 = $rows2[0];

			$tpl->assign("RES_CALL", $row['result']);
			$tpl->assign("COUNT_CALL", $row['calls']);
			$tpl->assign("CONTROL_CALL", '<td align="center">'.$row2['control'].'</td>');
			$tpl->assign("HOR_CALL", '<td align="center">'.$row2['hor'].'</td>');
			$tpl->assign("PLOH_CALL", '<td align="center">'.$row2['ploh'].'</td>');

			$tpl->parse("STAT_ROWS", ".".$moduleName."stat_row");
			$i++;
		}
	}
	else{
		$tpl->assign("STAT_ROWS", '<tr><td colspan=5 align=center>Нет данных в этом периоде!</td></tr>');
	}
	$tpl->assign("O_DATE_START", $_POST['date_start']);
	$tpl->assign("O_DATE_END", $_POST['date_end']);
}
else{
	$tpl->assign("STAT_ROWS", '');
	$tpl->assign("O_DATE_START", date("Y-m-d H:i"));
	$tpl->assign("O_DATE_END", date("Y-m-d H:i"));
}


$rows6 = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"id, name",
		"order"=>"name"
	)
);
$o_sel = '';
$i=1;
foreach($rows6 as $row6){
	if(isset($_POST['oper_id'])&&$_POST['oper_id']==$row6['id']){
		$o_sel.= '<option value="'.$row6['id'].'" selected="1">'.$row6['name'].'</option>';
	}
	else{
		$o_sel.= '<option value="'.$row6['id'].'">'.$row6['name'].'</option>';
	}
	$i++;
}
$tpl->assign("OPER_SEL", $o_sel);


$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
