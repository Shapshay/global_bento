<?php
# SETTINGS #############################################################################

$moduleName = "sto_oper_log";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "view" => $prefix . "view.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));


$_order = " ORDER BY data_reg DESC";
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

		$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
		$dateStart = date('d-m-Y',strtotime(date("d-m-Y", mktime()) . " - 3 day"));
		$tpl->assign("EDT_DATE_START", $dateStart);
		$tpl->assign("EDT_DATE_END", date("d-m-Y"));
		$res_calls='';
		$rows = $dbc->dbselect(array(
				"table"=>"sto_res_call",
				"select"=>"id, title"
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
			if(in_array(11,$this_role)){
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
