<?php
# SETTINGS #############################################################################
$moduleName = "client_tech";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и подготовка клиента
if(!isset($_GET['item'])&&!isset($_SESSION['1C'])&&!isset($_SESSION['c_id'])&&in_array(5,$USER_ROLE)){
	$dbc->element_create("calls_log",array(
		"oper_id" => ROOT_ID,
		"date_start" => 'NOW()'));
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["ManagerCode"] = LOGIN_1C;
	if(in_array(9,$USER_ROLE)||ROOT_ID==1){
		$params["test"] = true;
	}
	else{
		$params["test"] = false;
	}
	$result = $client->GetClientTech($params); 
	$array = objectToArray($result);
	$c_arr = $array['return'];
	
	$c_id = getClientID($c_arr['Code1C']);
	
	if($c_id==0){
		$dbc->element_create("clients",array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['FIO']),
			"code_1C" => $c_arr['Code1C'],
			"comment" => addslashes($c_arr['Comment'])));

		$c_id = $dbc->ins_id;
	}
	else{
		$dbc->element_update('clients',$c_id,array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['FIO']),
			"comment" => addslashes($c_arr['Comment'])));
	}
	$c_tel_arr = array();
	$j = 0;
	if(isset($c_arr['Telnumbers']['number'])){
		$number = $c_arr['Telnumbers'];
		$c_tel_arr[] = $number;
		if(getClientPhoneID($c_id, $number['number'])==0){
			$dbc->element_create("phones", array(
				"client_id" => $c_id,
				"phone" => $number['number'],
				"comment" => $number['comment']));
		}
		$j++;
	}
	else{
		$j=0;
		foreach($c_arr['Telnumbers'] as $numbers){
			$c_tel_arr[] = $numbers;
			if(getClientPhoneID($c_id, $numbers['number'])==0){
				$dbc->element_create("phones",array(
					"client_id" => $c_id,
					"phone" => $numbers['number'],
					"comment" => $numbers['comment']));
			}
			$j++;
		}
	}

	$dbc->element_create("tech",array(
		"oper_id" => ROOT_ID,
		"client_id" => $c_id,
		"tech_date" => date("Y-m-d",strtotime($c_arr['DateTech'])),
		"polis" => $c_arr['HavePolic'],
		"gn" => $c_arr['Gosnomer'],
		"pn" => $c_arr['Nomertp'],
		"mark" => $c_arr['Mark'],
		"model" => $c_arr['Model'],
		"car_year" => $c_arr['Born'],
		"car_photo" => $c_arr['HavePhotoCar'],
		"tp_photo" => $c_arr['HavePhotoTechPasport']));

	$tech_id = $dbc->ins_id;
	$_SESSION['tech_id'] = $tech_id;
}
else{
	if(isset($_GET['item'])&&!isset($_POST['edt_item'])){
		$c_id = $_GET['item'];
		$_SESSION['1C'] = $c_id;
		$dbc->element_create("calls_log",array(
			"oper_id" => ROOT_ID,
			"date_start" => 'NOW()'));
		$dbc->element_create("tech",array(
			"oper_id" => ROOT_ID,
			"client_id" => $c_id));
		$tech_id = $dbc->ins_id;
		
		$_SESSION['tech_id'] = $tech_id;
	}
	else{
		if(isset($_SESSION['c_id'])){
			$c_id = $_SESSION['c_id'];
		}
	}
}

if(isset($tech_id)){
	$_SESSION['tech_id'] = $tech_id;
	$tpl->assign("TECH_ID", $tech_id);
}
else{
	$dbc->element_create("tech",array(
		"oper_id" => ROOT_ID,
		"client_id" => $_SESSION['c_id']));

	$tech_id = $dbc->ins_id;
	$_SESSION['tech_id'] = $tech_id;
	$tpl->assign("TECH_ID", $tech_id);
}

if(isset($c_id)){
	$_SESSION['c_id'] = $c_id;
	$tpl->assign("U_ID", $c_id);
}



if(isset($c_id)){
	$rows = $dbc->dbselect(array(
			"table"=>"clients, tech",
			"select"=>"clients.fio as fio,
				clients.name as name,
				clients.iin as iin,
				clients.rnn as rnn,
				clients.email as email,
				clients.comment as comment,
				tech.tech_date as tech_date,
				tech.polis as polis,
				tech.gn as gn,
				tech.pn as pn,
				tech.mark as mark,
				tech.model as model,
				tech.car_year as car_year",
			"where"=>"clients.id = ".$c_id." AND  tech.id = ".$tech_id,
			"limit"=>1
		)
	);
	$row = $rows[0];
	
	$tpl->assign("INFO_U_FIO", $row['fio']);
	$tpl->assign("INFO_U_NAME", $row['name']);
	$tpl->assign("INFO_U_IIN", $row['iin']);
	$tpl->assign("INFO_U_RNN", $row['rnn']);
	$tpl->assign("INFO_U_EMAIL", $row['email']);
	$tpl->assign("INFO_U_GN", $row['gn']);
	$tpl->assign("INFO_U_PN", $row['pn']);
	$tpl->assign("INFO_U_MARK", $row['mark']);
	$tpl->assign("INFO_U_MODEL", $row['model']);
	$tpl->assign("INFO_U_YEAR", $row['car_year']);
	$tpl->assign("INFO_U_COMMENT", nl2br($row['comment']));
	if($row['tech_date']=='0001-01-01'){
		$tpl->assign("INFO_U_DATE_PREV_TO", '-');
	}
	else{
		$tpl->assign("INFO_U_DATE_PREV_TO", date("d-m-Y",strtotime($row['tech_date'])));
	}
	if(strtotime($row['polis'])==1){
		$tpl->assign("INFO_U_POLIS", 'Страховался у нас');
	}
	else{
		$tpl->assign("INFO_U_POLIS", 'Не страховался у нас');
	}
	
	
	// phones
	$rows = $dbc->dbselect(array(
		"table"=>"phones",
		"select"=>"*",
		"where"=>"client_id=".$c_id));
	$phones = '';
	$c =1;
	foreach($rows as $row){
		$phones.='<br>
				<img src="images/bell1.png" class="img_call" align="absmiddle" style="margin:3px; cursor:pointer;">'.$row['phone'].' ('.$row['comment'].')
				<input type="hidden" id="phone_call'.$c.'" value="'.$row['phone'].'" />';
		$c++;
	}
	$tpl->assign("INFO_U_PHONE", $phones);
	
	
	
}
else{
	$tpl->assign("INFO_U_FIO", '');
	$tpl->assign("INFO_U_NAME", '');
	$tpl->assign("INFO_U_IIN", '');
	$tpl->assign("INFO_U_RNN", '');
	$tpl->assign("INFO_U_EMAIL", '');
	$tpl->assign("INFO_U_DATE_PREV_TO", '');
	$tpl->assign("INFO_U_GN", '');
	$tpl->assign("INFO_U_PN", '');
	$tpl->assign("INFO_U_MARK", '');
	$tpl->assign("INFO_U_MODEL", '');
	$tpl->assign("INFO_U_YEAR", '');
	$tpl->assign("INFO_U_POLIS", '');
	$tpl->assign("INFO_U_PHONE", '');
	
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>