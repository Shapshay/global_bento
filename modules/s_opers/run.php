<?php
# SETTINGS #############################################################################

$moduleName = "s_opers";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "r_add" => $prefix . "r_add.tpl",
	$moduleName . "r_edit" => $prefix . "r_edit.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid2" => $prefix . "grid2.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "baned_r" => $prefix . "baned_r.tpl",
	$moduleName . "baned_r2" => $prefix . "baned_r2.tpl",
	$moduleName . "rights" => $prefix . "rights.tpl",
	$moduleName . "rights_row" => $prefix . "rights_row.tpl",
	$moduleName . "ch_row" => $prefix . "ch_row.tpl",
	$moduleName . "view" => $prefix . "view.tpl",
	$moduleName . "frodo_row" => $prefix . "frodo_row.tpl",
	$moduleName . "frodo2_row" => $prefix . "frodo2_row.tpl",
	$moduleName . "oper_row" => $prefix . "oper_row.tpl",
	$moduleName . "log_call_row" => $prefix . "log_call_row.tpl",
	$moduleName . "oper_log_row" => $prefix . "oper_log_row.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));

$size_x = 200;
$size_y = 200;
$size_x2 = 50;
$size_y2 = 50;
$group_id = 1;
$out = '';
$maxFileSize = 500000;

$_order = " ORDER BY data_reg DESC";				// ������� ����������
$_anonsCount = 50;

# MAIN #################################################################################

if(!isset($_GET['act'])){
	$_GET['act'] = 'default';
}
switch ($_GET['act']) {

	case 'r_edit': {
		// info
		$row = $dbc->element_find('users',$_GET['r_id']);
		$tpl->assign("R_ID", $row['id']);
		$tpl->assign("R_LOGIN", $row['login']);
		$tpl->assign("R_PHONE", $row['phone']);
		$tpl->assign("R_PSW", $row['password']);
		$tpl->assign("R_DATE_REG", date("d-m-Y H:i:s", strtotime($row['reg_date'])));
		$tpl->assign("R_NAME", $row['name']);
		$tpl->assign("R_LOGIN_1C", $row['login_1C']);


		// shkala
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_log",
			"select"=>"date_log",
			"where"=>"DATE_FORMAT(date_log, '%Y%m%d') = ".date("Ymd")." AND oper_id = ".$row['id']));
		$arr_log_time = array();
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$arr_log_time[] = date("YmdHis", strtotime($row2['date_log']));
			}
		}
		$arr_interval_time = array();
		$H2 = 9;
		$m2 = 30;
		$m_str = 30;
		for($a=1;$a<=102;$a++){
			if($m2<10){
				$m_str = '0'.$m2;
			}
			else{
				$m_str = $m2;
			}
			if($H2<10){
				$H2_str = '0'.$H2;
			}
			else{
				$H2_str = $H2;
			}
			$arr_interval_time[$a][0] = date("Ymd").$H2_str.$m_str."00";
			$m2+=5;
			if($m2==60){
				$m2 = 0;
				$H2++;
			}
			if($m2<10){
				$m_str = '0'.$m2;
			}
			else{
				$m_str = $m2;
			}
			if($H2<10){
				$H2_str = '0'.$H2;
			}
			else{
				$H2_str = $H2;
			}
			$arr_interval_time[$a][1] = date("Ymd").$H2_str.$m_str."00";
			$arr_interval_time[$a][2] = 0;
		}
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_log",
			"select"=>"DISTINCT date_log",
			"where"=>"DATE_FORMAT(date_log, '%Y%m%d') = ".date("Ymd")." AND oper_id = ".$row['id']." AND oper_act_id <> 11"));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				for($i=1;$i<sizeof($arr_interval_time);$i++){
					$log_date = date("YmdHis",strtotime($row2['date_log']));
					if($log_date>=$arr_interval_time[$i][0]&&$log_date<$arr_interval_time[$i][1]){
						$arr_interval_time[$i][2] = 1;
					}
				}
			}
		}
		$time_divs = '';
		$job_divs = '';
		$H = 9;
		$m = 30;

		$after = true;
		for($i=1;$i<=102;$i++){
			if(floor($i/6)==$i/6){
				$m+=30;
				if($m==60){
					$m = 0;
					$H++;
				}
				if($m==0){
					$m_str = '00';
				}
				else{
					$m_str = $m;
				}

				$time_divs.= '<div class="time_item">'.$H.':'.$m_str.'</div>';

			}
			if($arr_interval_time[$i][2] == 1){
				$job_divs.= '<div class="shkala_item1"></div>';
			}
			else{
				if($arr_interval_time[$i][0]<=date("YmdHis")){
					$job_divs.= '<div class="shkala_item2"></div>';
				}
				else{
					$job_divs.= '<div class="shkala_item3"></div>';
				}
			}
		}
		$tpl->assign("ITEM_TIME_DIVS", $time_divs);
		$tpl->assign("JOB_DIVS", $job_divs);
		
		
		// list pauses
		$pause_rows = '';
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_log",
			"select"=>"oper_log.date_log AS date_log,
					pause_type.title AS pause,
					oper_acts.title AS oper_act",
			"joins"=>"LEFT OUTER JOIN pause_type ON oper_log.pause_id = pause_type.id
					LEFT OUTER JOIN oper_acts ON oper_log.oper_act_id = oper_acts.id",
			"where"=>"DATE_FORMAT(oper_log.date_log, '%Y%m%d') = ".date("Ymd")." AND oper_log.oper_id = ".$row['id']."
					AND (oper_log.oper_act_id = 12 OR oper_log.oper_act_id = 13)",
			"order"=>"date_log"));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$pause_rows.= date("H:i:s", strtotime($row2['date_log'])).' - '.$row2['oper_act'].' ('.$row2['pause'].')<br>';
			}
		}
		$tpl->assign("PAUSE_ROWS", $pause_rows);
		
		// oper log
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_log",
			"select"=>"oper_log.id AS id, 
					 oper_log.date_log AS date_log, 
					 oper_log.comment AS comment,
					 oper_act_type.title AS oper_act_type, 
					 oper_acts.title AS oper_acts",
			"joins"=>"LEFT OUTER JOIN oper_act_type ON oper_log.oper_act_type_id = oper_act_type.id
					LEFT OUTER JOIN oper_acts ON oper_log.oper_act_id = oper_acts.id",
			"where"=>"oper_log.oper_id = ".$_GET['r_id'],
			"order"=>"date_log",
			"order_type"=>"DESC"));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$tpl->assign("OPER_LOG_ID", $row2['id']);
				$tpl->assign("OPER_LOG_DATE", $row2['date_log']);
				$tpl->assign("OPER_LOG_CH", $row2['oper_act_type']);
				$tpl->assign("OPER_LOG_ACT", $row2['oper_acts']);
				$tpl->assign("OPER_LOG_DESC", $row2['comment']);

				$tpl->parse("TABLE_OPER_LOG_ROWS", ".".$moduleName."oper_log_row");
			}
		}
		else{
			$tpl->assign("TABLE_OPER_LOG_ROWS", '');
		}

		// log calls
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_calls",
			"select"=>"id, 
					 call_date, 
					 phone1,
					 size, 
					 rating,
					 link",
			"where"=>"phone2 = ".$row['phone'],
			"order"=>"call_date",
			"order_type"=>"DESC"));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$audio_link = '<a href="javascript:PlayCall(\''.$row2['link'].'\');">'.$row2['link'].'</a>';
				$tpl->assign("OPER_CALL_ID", $row2['id']);
				$tpl->assign("OPER_CALL_DATE", $row2['call_date']);
				$tpl->assign("OPER_CALL_RATING", $row2['rating']);
				$tpl->assign("OPER_CALL_PHONE", $row2['phone1']);
				$tpl->assign("OPER_CALL_SIZE", $row2['size']);
				$tpl->assign("OPER_CALL_LINK", $audio_link);

				$tpl->parse("TABLE_OPER_CALLS_ROWS", ".".$moduleName."oper_log_calls_row");
			}
		}
		else{
			$tpl->assign("TABLE_OPER_CALLS_ROWS", '');
		}


		$tpl->parse("META_LINK", ".".$moduleName."grid2");

		$tpl->parse(strtoupper($moduleName), ".".$moduleName."r_edit");
		break;
	}

	

	default:{
		$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"users.id AS id,
				users.reg_date AS reg_date, 
				users.phone AS phone, 
				users.login AS login, 
				users.name AS name"));
		foreach($rows as $row) {
			$edt_url = '/'.getItemCHPU($_GET['menu'], 'pages').'/?act=r_edit&r_id='.$row['id'];
			$tpl->assign("OPER_ID", $row['id']);
			$tpl->assign("R_EDIT_URL", $edt_url);
			$tpl->assign("OPER_DATE_REG", $row['reg_date']);
			$tpl->assign("OPER_LOGIN", $row['login']);
			$tpl->assign("OPER_NAME", $row['name']);
			$tpl->assign("OPER_PHONE", $row['phone']);

			$tpl->parse("TABLE_OPER_ROWS", ".".$moduleName."oper_row");
		}



		$tpl->parse("META_LINK", ".".$moduleName."grid");

		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
		break;
	}
}
?>
