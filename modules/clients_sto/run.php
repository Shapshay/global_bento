<?php
	# SETTINGS #############################################################################
	$moduleName = "clients_tech";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
	));
	# MAIN #################################################################################
	if(isset($_SESSION['c_id'])){
		$c_id = $_SESSION['c_id'];
	}
	if(isset($_SESSION['tech_id'])){
		$tech_id = $_SESSION['tech_id'];
	}
	// сохранение обработанных данных по клиенту
	if(isset($_POST['edt_item'])){
		$dbc->element_update('clients',$c_id,array(
			"name" => addslashes($_POST['name'])));
		if(isset($_POST['dost'])){
			$dost = 1;
		}
		else{
			$dost = 0;
		}
		$dbc->element_update('tech',$tech_id,array(
			"gn" => $_POST['gn'],
			"pn" => $_POST['pn'],
			"mark" => $_POST['mark'],
			"model" => $_POST['model'],
			"car_year" => $_POST['car_year'],
			"dost" => $dost,
			"dost_adres" => $_POST['dost_adres'],
			"tech_date" => date("Y-m-d",strtotime($_POST['tech_date']))));

		ini_set("soap.wsdl_cache_enabled", "0" );
		
		$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		$params2["ClientTech"]["Code1C"] = $_POST['code_1C'];
		$params2["ClientTech"]["DateTech"] = date("Y-m-d",strtotime($_POST['tech_date']));
		$params2["ClientTech"]["ManagerCode"] = LOGIN_1C;
		$params2["ClientTech"]["Name"] = $_POST['name'];
		$params2["ClientTech"]["Gosnomer"] = $_POST['gn'];
		$params2["ClientTech"]["Nomertp"] = $_POST['pn'];
		$params2["ClientTech"]["Mark"] = $_POST['mark'];
		$params2["ClientTech"]["Model"] = $_POST['model'];
		$params2["ClientTech"]["Born"] = $_POST['car_year'];
		
		$params2["ClientTech"]["HavePolic"] = 0;
		$params2["ClientTech"]["HavePhotoCar"] = 0;
		$params2["ClientTech"]["HavePhotoTechPasport"] = 0;

		if(getClientPhoneID($c_id, $_POST['phone'])==0&&$_POST['phone']!=''){
			$dbc->element_create("phones", array(
				"client_id" => $c_id,
				"phone" => $_POST['phone']));
		}


		$rows = $dbc->dbselect(array(
			"table"=>"phones",
			"select"=>"phone, comment",
			"where"=>"client_id=".$c_id));
		$j = 0;
		foreach($rows as $row){
			$params2["ClientTech"]["Telnumbers"][$j]['number']=$row['phone'];
			$params2["ClientTech"]["Telnumbers"][$j]['comment']=$row['comment'];
			$j++;
		}

		$params2["ClientTech"]["Comment"] = '';
		$result = $client2->SaveClientTech($params2); 
		
		header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
		exit;
	}
	
	if(isset($_POST['res_call_id'])){
		if($_POST['call_lenght']>0){
			$call_lenght = $_POST['call_lenght'];
		}
		else{
			$call_lenght = 0;
		}
		$dbc->element_create("calls", array(
			"oper_id" => ROOT_ID,
			"client_id" => $c_id,
			"date_call" => 'NOW()',
			"call_lenght" => $call_lenght,
			"res_call_id" => $_POST['res_call_id'],
			"comment" => addslashes($_POST['call_comment']),
			"date_next_call" => date("Y-m-d H:i",strtotime($_POST['date_next_call']))));

		ini_set("soap.wsdl_cache_enabled", "0" ); 
		
		$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		if($_POST['call_lenght']>0){
			$call_lenght = $_POST['call_lenght'];
		}
		else{
			$call_lenght = 0;
		}
		$params2["Call"]["Code1C"] = $_POST['code_1C'];
		$params2["Call"]["ManagerCode"] = LOGIN_1C;
		$params2["Call"]["DateContact"] = date("Y-m-d\TH:i:s",strtotime($_POST['date_next_call']));
		$params2["Call"]["Result"] = $_POST['res_call_id'];
		$params2["Call"]["Comment"] = $_POST['call_comment'];
		$params2["Call"]["Duration"] = $call_lenght;
		if(isset($_POST['ocenit'])){
			if($_POST['ocenit']==1){
				$params2["Call"]["Horosh"] = true;
			}
			else{
				$params2["Call"]["Horosh"] = false;
			}
		}
		else{
			$params2["Call"]["Horosh"] = true;
		}

		$result = $client2->SaveCallTech($params2);

		$dbc->element_create("oper_log", array(
			"oper_id" => ROOT_ID,
			"oper_act_type_id" => 1,
			"oper_act_id" => 1,
			"date_log" => 'NOW()',
			"comment" => addslashes($_POST['call_comment']).". Длительность: ".$call_lenght));

		$log = getOperCurentMaxLog(ROOT_ID);
		$dbc->element_update('phones',$log,array(
			"res" => $_POST['res_call_id'],
			"date_end" => 'NOW()'));

		header("Location: /".getItemCHPU(2180, 'pages'));
		exit;
	}
	
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	if(isset($c_id)){
		$rows = $dbc->dbselect(array(
				"table"=>"clients, tech",
				"select"=>"clients.fio as fio,
				clients.name as name,
				clients.iin as iin,
				clients.rnn as rnn,
				clients.email as email,
				clients.comment as comment,
				clients.code_1C as code_1C,
				tech.tech_date as tech_date,
				tech.polis as polis,
				tech.gn as gn,
				tech.pn as pn,
				tech.mark as mark,
				tech.model as model,
				tech.car_year as car_year,
				tech.dost as dost,
				tech.dost_adres as dost_adres",
				"where"=>"clients.id = ".$c_id." AND  tech.id = ".$tech_id,
				"limit"=>1
			)
		);
		$row = $rows[0];

		$tpl->assign("CLIENT_CODE_1C", $row['code_1C']);
		$tpl->assign("U_FIO", $row['fio']);
		$tpl->assign("U_NAME", $row['name']);
		$tpl->assign("U_GN", $row['gn']);
		$tpl->assign("U_PN", $row['pn']);
		$tpl->assign("U_MARK", $row['mark']);
		$tpl->assign("U_MODEL", $row['model']);
		$tpl->assign("U_YEAR", $row['car_year']);
		$tpl->assign("EDT_COMMENT", nl2br($row['comment']));
		if($row['tech_date']=='0001-01-01'){
			$tpl->assign("U_DATE_PREV_TO", date("d-m-Y"));
			$tpl->assign("PREV_TO_COLOR", 'pole_vvoda2');
		}
		else{
			$tpl->assign("U_DATE_PREV_TO", date("d-m-Y",strtotime($row['tech_date'])));
			$tpl->assign("PREV_TO_COLOR", 'pole_vvoda');
		}
		
		$tpl->assign("DOST_CHECK", ' checked="checked"');
		$tpl->assign("EDT_DOST_ADDRESS", $row['dost_adres']);

		$rows = $dbc->dbselect(array(
				"table"=>"phones",
				"select"=>"*",
				"where"=>"client_id = ".$c_id
			)
		);
		$phones = '';
		$i=1;
		foreach($rows as $row){
			if($i==1){
				$tpl->assign("EDT_H_PHONES", $row['phone']);
			}
			$i++;
			$phones.=$row['phone'].'<br>Комментарий: <input type="text" name="phone_comment['.$row['id'].']" value="'.$row['comment'].'"  class="pole_vvoda" style="padding-left:10px;"> <br>';
		}
		$tpl->assign("EDT_PHONES", $phones);
		
		
		$res_calls='';
		$rows = $dbc->dbselect(array(
				"table"=>"tech_res",
				"select"=>"id, title"
			)
		);
		foreach($rows as $row){
			$res_calls.='<option value="'.$row['id'].'">'.$row['title'];
		}
		$tpl->assign("RES_CALLS_ROWS", $res_calls);
		
		$tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y H:i",strtotime("+ 1 hour")));
		
						
	}
	else{
		$tpl->assign("EDT_NAME", '');
		$tpl->assign("EDT_IIN", '');
		$tpl->assign("EDT_RNN", '');
		$tpl->assign("EDT_EMAIL", '');
		$tpl->assign("EDT_DATE_PREV_CALL", '');
		$tpl->assign("EDT_RES_PREV_CALL", '');
		$tpl->assign("EDT_SOURCE", '');
		$tpl->assign("EDT_DATE_END", '');
		$tpl->assign("EDT_COMMENT", '');
		$tpl->assign("EDT_DATE_LOST", '');
		$tpl->assign("EDT_DATE_TOCHNAYA", '');
		$tpl->assign("EDT_CARS", '');
		$tpl->assign("EDT_PHONES", '');
		$tpl->assign("CALL_TARGET", '');
		$tpl->assign("OCEN_HIDE1", '<!--');
		$tpl->assign("OCEN_HIDE2", '-->');
	}
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>