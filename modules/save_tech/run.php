<?php
	# SETTINGS #############################################################################
	$moduleName = "save_tech";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "main2" => $prefix . "main2.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
			
	));
	# MAIN #################################################################################
	if(isset($_SESSION['tech_id'])){
		$rows = $dbc->dbselect(array(
				"table"=>"clients, tech",
				"select"=>"clients.code_1C as code_1C,
				tech.tech_date as tech_date,
				tech.polis as polis,
				tech.gn as gn,
				tech.dost as dost,
				tech.dost_adres as dost_adres",
				"where"=>"clients.id = ".$c_id." AND  tech.id = ".$tech_id,
				"limit"=>1
			)
		);
		$row = $rows[0];
		
		$tech_id = $_SESSION['tech_id'];
		
		// Сохраняем в 1С
		$client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		
		$params7['TechOsmotr']["Code1C"] = $row['code_1C'];
		$params7['TechOsmotr']["ManagerCode"] = LOGIN_1C;
		$params7['TechOsmotr']["DateTech"] = date("Y-m-d");
		$params7['TechOsmotr']["Gosnomer"] = $row['gn'];
		if($row['dost']==1){
			$params7['TechOsmotr']["Dostavka"] = $row['dost'];
			$params7['TechOsmotr']["Address"] = $row['dost_adres'];
		}
		else{
			$params7['TechOsmotr']["Dostavka"] = $row['dost'];
			$params7['TechOsmotr']["Address"] = '';
		}
		
		$result7 = $client7->SaveTech($params7); 
		$array_save = objectToArray($result7);
		$res_save_1c = $array_save['return'];
		
		if($res_save_1c['Error_exp']=='Success'){
			$dbc->element_create("oper_log", array(
				"oper_id" => ROOT_ID,
				"oper_act_type_id" => 1,
				"oper_act_id" => 1,
				"date_log" => 'NOW()',
				"comment" => "Длительность: ".$_GET['call_lenght']));

			$dbc->element_create("oper_log", array(
				"oper_id" => ROOT_ID,
				"oper_act_type_id" => 4,
				"oper_act_id" => 7,
				"date_log" => 'NOW()',
				"comment" => $row['code_1C']));
			
			
			// Сохраняем звонок в 1С
			$с_id = $_SESSION['с_id'];
			$dbc->element_create("calls", array(
				"oper_id" => ROOT_ID,
				"client_id" => $с_id,
				"call_lenght" => $_GET['call_lenght'],
				"date_call" => 'NOW()',
				"res_call_id" => 5,
				"date_next_call" => date("d.m.Y", strtotime(date("Y-m-d H:i:s", mktime()) . " + 358 day")),
				"comment" => ''));

			$log = getOperCurentMaxLog(ROOT_ID);
			$dbc->element_update('calls_log',$log,array(
				"res" => 5,
				"date_end" => 'NOW()'));

			ini_set("soap.wsdl_cache_enabled", "0" ); 
			
			$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
				array( 
				'login' => 'ws', 
				'password' => '123456', 
				'trace' => true
				) 
			);
			

			$params2["Call"]["Code1C"] = $row['code_1C'];
			$params2["Call"]["ManagerCode"] = LOGIN_1C;
			$params2["Call"]["DateContact"] = date('Y-m-d\TH:i:s',strtotime(date("Y-m-d H:i:s", mktime()) . " + 358 day"));
			$params2["Call"]["Result"] = 5;
			$params2["Call"]["Comment"] = $row['dost_adres'];
			$params2["Call"]["Duration"] = $_GET['call_lenght'];
			$params2["Call"]["Horosh"] = true;
			
			$result = $client2->SaveCallTech($params2); 
			
			header("Location: /".getItemCHPU(2180, 'pages'));
			exit;
		}
		else{
			// Ошибка сохранения в 1C
			$tpl->assign("POLIS_SAVE_ERR", "Ошибка сохранения в 1C !<br>".$res_save_1c['Error_exp']);
		}
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	}
	else{
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main2");
	}
?>