<?php
# SETTINGS #############################################################################

$moduleName = "s_oper_log";

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

	case 'log_view': {
		$rows = $dbc->dbselect(array(
			"table"=>"calls_log",
			"select"=>"calls_log.id,
				 users.name as oper,
				 calls_log.date_start,
				 calls_log.date_end,
				 res_calls.title as res",
			"joins"=>"LEFT OUTER JOIN users ON calls_log.oper_id = users.id
				LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id",
			"where"=>"calls_log.id = ".$_GET['contact']));
		$row = $rows[0];

		$tpl->assign("R_CONTACT", $row['id']);
		$tpl->assign("CONTACT_OPER", $row['oper']);
		$tpl->assign("CONTACT_START", $row['date_start']);
		$tpl->assign("CONTACT_END", $row['date_end']);
		$tpl->assign("CONTACT_RES", $row['res']);

		// log calls
		$rows2 = $dbc->dbselect(array(
			"table"=>"oper_calls",
			"select"=>"id, 
					 call_date, 
					 phone1,
					 size, 
					 rating,
					 link",
			"where"=>"calls_log_id = ".$_GET['contact'],
			"order"=>"call_date",
			"order_type"=>"DESC"));
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows2 as $row2){
				$audio_link = '<a href="javascript:PlayCall(\''.$row['link'].'\');">'.$row['link'].'</a>';
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

		$tpl->parse("META_LINK", ".".$moduleName."grid3");

		$tpl->parse(strtoupper($moduleName), ".".$moduleName."view");
		break;
	}

	default:{
		/*
		$rows = $dbc->dbselect(array(
			"table"=>"calls_log, oper_calls, users, res_calls",
			"select"=>"calls_log.id as id, 
				calls_log.date_start as date_start,
				calls_log.date_end as date_end,
				oper_calls.link as link,
				users.name as oper,
				calls_log.oper_id as oper_id,
				oper_calls.phone1 as phone,
				res_calls.title as res,
				res_calls.id as res_id",
			"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
				calls_log.id = oper_calls.calls_log_id AND
				calls_log.oper_id = users.id AND 
				calls_log.res = res_calls.id",
			"group"=>"calls_log.id",
			"order"=>"calls_log.date_start",
			"order_type"=>"DESC",
			"limit"=>2000));
		//echo $dbc->outsql;
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach ($rows as $row) {
				if ($row['res'] == 'Точная дата') {
					$rows2 = $dbc->dbselect(array(
						"table" => "phones",
						"select" => "clients.date_end as date_end",
						"joins" => "LEFT OUTER JOIN clients ON phones.client_id = clients.id",
						"where" => "phone = '" . $row['phone'] . "'",
						"limit" => 1));
					$row2 = $rows2[0];
					//echo $dbc->outsql."<br>";
					$td = date("d-m-Y", strtotime($row2['date_end']));
					//$td = $row2['date_end'];
				} else {
					$td = '-';
				}
				$view_log_url = '/' . getItemCHPU($_GET['menu'], 'pages') . '/?act=log_view&contact=' . $row['id'];
				$audio_link = '<a href="javascript:PlayCall(\'' . $row['link'] . '\', \'' . $row['oper_id'] . '\', \'' . $row['phone'] . '\', \'' . $row['res'] . '\', \'' . $row['res_id'] . '\');">' . $row['link'] . '</a>';
				$tpl->assign("LOG_ID", $row['id']);
				$tpl->assign("LOG_EDIT_URL", $view_log_url);
				$tpl->assign("LOG_OPER", $row['oper']);
				$tpl->assign("LOG_START", $row['date_start']);
				$tpl->assign("LOG_END", $row['date_end']);
				$tpl->assign("LOG_RES", $row['res']);
				$tpl->assign("LOG_PHONE", $row['phone']);
				$tpl->assign("LOG_LINK", $audio_link);
				$tpl->assign("LOG_TD", $td);

				$tpl->parse("TABLE_LOG_CALLS_ROWS", "." . $moduleName . "log_call_row");
			}
		}
		else{
			$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
		}
		*/
		$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
		$dateStart = date('d-m-Y',strtotime(date("d-m-Y", mktime()) . " - 3 day"));
		$tpl->assign("EDT_DATE_START", $dateStart);
		$tpl->assign("EDT_DATE_END", date("d-m-Y"));
		$res_calls='';
		$rows = $dbc->dbselect(array(
				"table"=>"res_calls",
				"select"=>"id, title",
				"where"=>"view=1"
			)
		);
		foreach($rows as $row){
			$res_calls.='<option value="'.$row['id'].'">'.$row['title'];
		}
		$tpl->assign("RES_CALLS_ROWS", $res_calls);


		$oper_rows='';
		$rows = $dbc->dbselect(array(
				"table"=>"users",
				"select"=>"users.*, GROUP_CONCAT(r_user_role.role_id) as role",
				"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id",
				"group"=>"users.id",
				"order"=>"users.name"
			)
		);
		foreach($rows as $row){
			$this_role = explode(",",$row['role']);
			if(in_array(1,$this_role)){
				$oper_rows.='<option value="'.$row['id'].'">'.$row['name'];
			}
		}
		$tpl->assign("OPERS_ROWS", $oper_rows);
		

		$tpl->parse("META_LINK", ".".$moduleName."grid");

		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
		break;
	}
}
?>
