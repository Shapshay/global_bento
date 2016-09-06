<?
	# SETTINGS #############################################################################
	
	$moduleName = "call_more";
	
	$prefix = "./modules/".$moduleName."/";
	
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			$moduleName . "graf" => $prefix . "graf.tpl",
	));
	
	# MAIN #################################################################################
	
	if(isset($_POST['edt_s_phone'])){
		$date_arr = '';
		$rows3 = $dbc->dbselect(array(
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
					calls_log.res = res_calls.id AND 
					oper_calls.phone1 = '".$_POST['edt_s_phone']."'",
				"order"=>"calls_log.date_start",
				"order_type"=>"DESC"
			)
		);

		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows3 as $row3){
				
				if($row3['res']=='Точная дата'){
					$rows2 = $dbc->dbselect(array(
						"table"=>"phones",
						"select"=>"date_end",
						"joins"=>"LEFT OUTER JOIN clients ON phones.client_id = clients.id",
						"where"=>"phone = '".$row3['phone']."'",
						"limit"=>1
					));
					$row2 = $rows2[0];
					$td = date("d-m-Y",strtotime($row2['date_end']));
				}
				else{
					$td = '-';
				}
				if($td == '-'){
					$audio_link = '<a href="javascript:PlayCall(\''.$row3['link'].'\', \''.$row3['oper_id'].'\', \''.$row3['phone'].'\', \''.$row3['res'].'\', \''.$row3['res_id'].'\');">'.$row3['link'].'</a>';
				}
				else{
					$audio_link = '<a href="javascript:PlayCall2(\''.$row3['link'].'\', \''.$row3['oper_id'].'\', \''.$row3['phone'].'\', \''.$row3['res'].'\', \''.$td.'\', \''.$row3['res_id'].'\');">'.$row3['link'].'</a>';
				}
				
				
				
				$date_arr.='<tr><td>'.$row3['oper'].'</td><td>'.$row3['date_start'].'</td><td>'.$row3['date_end'].'</td><td>'.$row3['res'].'</td><td>'.$audio_link.'</td><td>'.$td.'</td></tr>';
			}
		}
		
		
		if($date_arr != ''){
			$tpl->assign("R_NORM_OPERS", $date_arr);

			$tpl->parse("GRAF", ".".$moduleName."graf");
		}
		else{
			$tpl->assign("GRAF", "<strong>Интервал статистики пуст !</strong>");
		}
		$tpl->assign("EDT_SEARCH_PHONE", $_POST['edt_s_phone']);
		$tpl->assign("GRAF_TITLE", 'Статистика за '.$_POST['edt_s_phone']);
		
	}
	else{
		$tpl->assign("GRAF", '');
		$tpl->assign("EDT_SEARCH_PHONE", '');
		
		
	}
	
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	
?>
