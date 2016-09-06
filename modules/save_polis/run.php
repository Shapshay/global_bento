<?php
# SETTINGS #############################################################################
$moduleName = "save_polis";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "main2" => $prefix . "main2.tpl",
		$moduleName . "html" => $prefix . "html.tpl",

));
# MAIN #################################################################################

if(isset($_SESSION['polis'])){
	$polis_id = $_SESSION['polis'];

	$rows = $dbc->dbselect(array(
		"table"=>"polises",
		"select"=>"polises.*,
			strach_company.title AS strach_comp,
			strach_periods.title AS strach_period,
			pay_types.title AS pay_type,
			pays.title AS pay",
		"joins"=>"LEFT OUTER JOIN strach_company ON polises.strach_comp_id = strach_company.id
			LEFT OUTER JOIN strach_periods ON polises.period_id = strach_periods.id
			LEFT OUTER JOIN pay_types ON polises.pay_type_id = pay_types.id
			LEFT OUTER JOIN pays ON polises.pay_id = pays.id",
		"where"=>"polises.id = ".$polis_id,
		"limit"=>1));
	$row = $rows[0];

	$dbc->element_update('polises',$polis_id,array(
		"date_write" => 'NOW()'));

	if($row['sms']!=''){
		if($row['lng_sms']==0){
			$sms_body = urlencode('Ваш полис #'.$row['bso_number'].' передан в службу доставки. 8-727-3286660. Автоклуб');
		}
		else{
			$sms_body = urlencode('Сыздын полисыныз #'.$row['bso_number'].' курьерге берилды. 8-727-3286660. Автоклуб');
		}
		$sms_url = "http://smsc.kz//sys/send.php?login=Tigay84@list.ru&psw=94120593&&phones=".$row['sms']."&charset=utf-8&mes=".$sms_body;
		$result = get_web_page( $sms_url );
	}

	$dbc->element_update('polises',$polis_id,array(
		"status" => 1,
		"date_write" => 'NOW()'));

	// Сохраняем в 1С


	$client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);

	$row8 = $dbc->element_find('clients',$_SESSION['c_id']);

	if($row8['email']!=''){
		$url = 'https://app.getresponse.com/add_contact_webform.html?u=BIXpW';
		$postdata = 'name='.$row8['name'].'&email='.$row8['email'].'&webform_id=13939302';
		$result = post_content( $url, $postdata );
		$html2 = $result['content'];
	}


	$params7['Polic']["ManagerCode"] = LOGIN_1C;
	$params7['Polic']["Company"] = $_SESSION['strach_comp'];
	$params7['Polic']["PolicyNumber"] = $_SESSION['bso'];
	$params7['Polic']["ClientCode1C"] = $row8['code_1C'];
	$params7['Polic']["DateOfBegin"] = date("Y-m-d", strtotime($row['date_start']));
	$params7['Polic']["DateOfEnd"] = date("Y-m-d", strtotime($row['date_end']));
	$params7['Polic']["Cash"] = $row['pay_type_id'];

	if(!in_array(5,$USER_ROLE)){
		$dost = 1;
	}
	else{
		$dost = 0;
	}
	$params7['Polic']["Delivery"] = $dost;
	$params7['Polic']["Pereoformlenie"] = $row['rewrite'];
	if($row['rewrite']==1){
		$params7['Polic']["Premium"] = $row['premium']-$row['not_gained_premium'];
	}
	else{
		$params7['Polic']["Premium"] = $row['premium'];
	}
	$params7['Polic']["Summa"] = $row['summa'];
	$params7['Polic']["Address"] = $row['dost_address'];
	$params7['Polic']["DateOfDelivery"] = date("Y-m-d", strtotime($row['date_dost']));

	$rows3 = $dbc->dbselect(array(
			"table"=>"gifts",
			"select"=>"gifts.id AS id,
					gift_types.title AS  gift,
					gift_types.summa AS  summa",
			"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
			"where"=>"gifts.polis_id = ".$_SESSION['polis']
		)
	);
	$numRows = $dbc->count;
	$gifts_sum = 0;
	$i=0;
	if ($numRows > 0) {
		foreach($rows3 as $row3){
			$params7['Polic']["Presents"][$i]['Name'] = $row3['gift'];
			$params7['Polic']["Presents"][$i]['Stoimost'] = $row3['summa'];
			if($row3['uchet']==0){
				$params7['Polic']["Presents"][$i]['Otvertki'] = true;
			}
			else{
				$params7['Polic']["Presents"][$i]['Otvertki'] = false;
			}
			$i++;
		}
	}

	$params7['Polic']['Drivers'][0]['Name'] = $row8['fio'];


	$rows9 = $dbc->dbselect(array(
			"table"=>"salem_models",
			"select"=>"*",
			"where"=>"make_id = ".$row['mark_id']." AND model_id = ".$row['model_id'],
			"limit"=>1
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row9 = $rows9[0];
		$mark = $row9['make'];
		$model = $row9['model'];
		$url = 'http://melchior.kz/api/getSI?hash='. md5(date("d.m.Y")).'&make_id='.$mark.'&model_id='.$model.'&year='.$row['car_year'];
		$result = get_web_page( $url );
		$html2 = $result['content'];
		$obj=json_decode($html2);
		$stoim = $obj->summ;
	}
	else{
		$mark = '';
		$model = '';
		$stoim = 0;
	}

	$params7['Polic']['Cars'][0]['Gosnumber'] = $row['gn'];
	$params7['Polic']['Cars'][0]['Nomertp'] = '';
	$params7['Polic']['Cars'][0]['Year'] = $row['car_year'];
	$params7['Polic']['Cars'][0]['Marka'] = $mark;
	$params7['Polic']['Cars'][0]['Marka_id'] = $row['mark_id'];
	$params7['Polic']['Cars'][0]['Model'] = $model;
	$params7['Polic']['Cars'][0]['Model_id'] = $row['model_id'];
	$params7['Polic']['Cars'][0]['Stoimost'] = $stoim;

	$client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);

	$result7 = $client7->SavePolic($params7);
	$array_save = objectToArray($result7);
	$res_save_1c = $array_save['return'];

	if($res_save_1c['Error_exp']=='Success'){
		if(!in_array(5,$USER_ROLE)){
			$dbc->element_create("oper_log",array(
				"oper_id" => ROOT_ID,
				"oper_act_type_id" => 1,
				"oper_act_id" => 1,
				"comment" => "Длительность: ".$_GET['call_lenght'],
				"date_log" => 'NOW()'));
			if($row['rewrite']==1){
				$dbc->element_create("oper_log",array(
					"oper_id" => ROOT_ID,
					"oper_act_type_id" => 4,
					"oper_act_id" => 8,
					"comment" => $row8['code_1C'],
					"date_log" => 'NOW()'));
			}
			else{
				$dbc->element_create("oper_log",array(
					"oper_id" => ROOT_ID,
					"oper_act_type_id" => 4,
					"oper_act_id" => 7,
					"comment" => $row8['code_1C'],
					"date_log" => 'NOW()'));
			}
			// Сохраняем звонок в 1С
			$u_id = $_SESSION['c_id'];
			$dbc->element_create("calls",array(
				"oper_id" => ROOT_ID,
				"user_id" => $u_id,
				"call_lenght" => $_GET['call_lenght'],
				"res_call_id" => 5,
				"comment" => '',
				"date_next_call" => date("d.m.Y", strtotime($row['date_end'])),
				"date_call" => 'NOW()'));

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

			$row4 = $dbc->element_find('clients',$u_id);
			$params2["Call"]["Code1C"] = $row4['code_1C'];
			$params2["Call"]["ManagerCode"] = LOGIN_1C;
			$params2["Call"]["DateContact"] = date("Y-m-d\TH:i:s", (strtotime($row['date_end'])-604800));
			$params2["Call"]["Result"] = 5;
			$params2["Call"]["Comment"] = $row['dost_address'];
			$params2["Call"]["Duration"] = $_GET['call_lenght'];
			$params2["Call"]["Horosh"] = true;
			$result = $client2->SaveCall($params2);
		}
		else{
            // передача полиса в доставку из приемной
            $dbc->element_update('polises',$_SESSION['polis'],array(
                "status" => 3,
                "date_indost" => 'NOW()'));
            $dbc->element_create("cour_polis",array(
                "c_id" => ROOT_ID,
                "polis_id" => $_SESSION['polis'],
                "date" => 'NOW()'));
            ini_set("soap.wsdl_cache_enabled", "0" );
            $client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
                array(
                    'login' => 'ws',
                    'password' => '123456',
                    'trace' => true
                )
            );
            //$params["Code1C"] = LOGIN_1C;
            $rows = $dbc->dbselect(array(
                    "table"=>"cour_polis",
                    "select"=>"users.login_1C as cour_1C,
			        polises.bso_number as bso_number",
                    "joins"=>"LEFT OUTER JOIN users ON users.id = cour_polis.c_id
                    LEFT OUTER JOIN polises ON polises.id = cour_polis.polis_id",
                    "where"=>"cour_polis.c_id = ".ROOT_ID." AND cour_polis.polis_id = ".$_SESSION['polis'],
                    "limit"=>1
                )
            );
            $row = $rows[0];
            $params["polic_number"] = $row['bso_number'];
            $params["manager_code"] = $row['cour_1C'];
            $params["clear"] = 0;
            //print_r($params);
            $result = $client->ClearSetPolicCurier($params);
        }

		header("Location: /".getItemCHPU(2176, 'pages'));
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