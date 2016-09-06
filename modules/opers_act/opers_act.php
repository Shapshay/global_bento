<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################
$activ_opers = '';
$i = 0;
$x = 0;
$y = 0;
$rows = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"*"));
foreach($rows as $row){
	$rows2 = $dbc->dbselect(array(
		"table"=>"oper_log",
		"select"=>"MAX(date_log) AS date_log,
				SUM(noact_count) AS noact_count",
		"where"=>"DATE_FORMAT(date_log, '%Y%m%d') = ".date("Ymd")." AND oper_id = ".$row['id']));
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row2 = $rows2[0];
		$date_log = date("H:i:s d-m-Y", strtotime($row2['date_log']));
		if('06:00:00 01-01-1970'==$date_log){
			$date_log = '-';
			$noact_count = '0';
			$activ_opers.= '<tr>
					<td class="yelow" align="left">'.$date_log.'</td>
					<td class="yelow" align="left">'.$row['name'].'</td>
					<td class="yelow" align="left">'.$noact_count.'</td>
					<td class="yelow" align="left">-</td>
					</tr>';
			$y++;
		}
		else{
			$rows3 = $dbc->dbselect(array(
				"table"=>"oper_log",
				"select"=>"oper_log.oper_act_id AS oper_act_id,
						pause_type.title AS pause",
				"where"=>"oper_log.date_log >= ADDDATE(NOW(), INTERVAL -5 MINUTE) AND  oper_log.oper_id = ".$row['id'],
				"order"=>"oper_log.date_log",
				"order_type"=>"DESC",
				"limit"=>1));
			$numRows = $dbc->count;
			if ($numRows > 0) {
				$row3 = $rows3[0];
				if($row3['oper_act_id']!=11&&$row3['oper_act_id']!=12){
					$dbc->element_update('oper_log',$row['id'],array(
						"noact_count" => 0));
					$x++;
					$noact_count = $row2['noact_count'];
					$activ_opers.= '<tr>
						<td align="left">'.$date_log.'</td>
						<td class="grey" align="left">'.$row['name'].'</td>
						<td class="grey" align="left">0</td>
						<td class="grey" align="left">-</td>
						</tr>';
				}
				else{
					$noact_count = $row2['noact_count'];
					$y++;
					if($row3['oper_act_id']==12){
						$activ_opers.= '<tr>
							<td class="red" align="left">'.$date_log.'</td>
							<td class="red" align="left">'.$row['name'].'</td>
							<td class="red" align="left">'.$noact_count.'</td>
							<td class="red" align="left">'.$row3['pause'].'</td>
							</tr>';
					}
					else{
						$activ_opers.= '<tr>
							<td class="red" align="left">'.$date_log.'</td>
							<td class="red" align="left">'.$row['name'].'</td>
							<td class="red" align="left">'.$noact_count.'</td>
							<td class="red" align="left">-</td>
							</tr>';
					}
				}
				
			}
			else{
				$noact_count = $row2['noact_count']+1;
				$dbc->element_create("oper_log", array(
					"oper_id" => $row['id'],
					"oper_act_type_id" => 5,
					"oper_act_id" => 11,
					"date_log" => 'NOW()',
					"noact_count" => 1));
				$y++;
				$activ_opers.= '<tr>
						<td class="red" align="left">'.$date_log.'</td>
						<td class="red" align="left">'.$row['name'].'</td>
						<td class="red" align="left">'.$noact_count.'</td>
						<td class="red" align="left">-</td>
						</tr>';
			}
		}
	}
	else{
		$date_log = '-';
		$y++;
		$noact_count = '0';
		$dbc->element_create("oper_log", array(
			"oper_id" => $row['id'],
			"oper_act_type_id" => 5,
			"oper_act_id" => 11,
			"date_log" => 'NOW()',
			"noact_count" => 1));
		db_query($sql4);
		$activ_opers.= '<tr>
				<td class="yelow" align="left">'.$date_log.'</td>
				<td class="yelow" align="left">'.$row['name'].'</td>
				<td class="yelow" align="left">'.$noact_count.'</td>
				<td class="yelow" align="left">-</td>
				</tr>';
	}
	
	
	$i++;
}







$out_row['activ_opers'] =  $activ_opers;
$out_row['activ_act_itog'] = $x;
$out_row['activ_noact_itog'] = $y;
$out_row['activ_itog'] = $i;
$out_row['result'] = 'OK';
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
