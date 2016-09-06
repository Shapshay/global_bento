<?php
# SETTINGS #############################################################################
$moduleName = "client";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и подготовка клиента
if(!isset($_GET['item'])&&!isset($_SESSION['1C'])&&!isset($_SESSION['c_id'])&&!in_array(5,$USER_ROLE)){
	// проверка блокировки InfoBank

	$url = 'http://192.168.1.227/inc/api.php';
	$postdata = 'u_lgn='.$_SESSION['lgn'];
	$result = post_content( $url, $postdata );
	$j_str = $result['content'];
	//echo $j_str.'<p>';
	$IBAnswer = json_decode($j_str);
	//var_dump($IBAnswer);
	if($IBAnswer->result=='OK'){
		//echo '<p>Блокировка получения клиента';
		$url = getCodeBaseURL("index.php?menu=2205");
		header("Location: ".$url);
		exit;
	}
	
	// конец проверки блокировки InfoBank
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
	$result = $client->GetClient($params);
	$array = objectToArray($result);
	$c_arr = $array['return'];
	$c_id = getClientID($c_arr['Code1C']);
	if(isset($c_arr['RNN'])){
		$rnn = $c_arr['RNN'];
	}
	else{
		$rnn = '';
	}
	if(isset($c_arr['Rating'])){
		$class = $c_arr['Rating'];
		$_SESSION['class'] = $class;
		//echo 'Y';
	}
	else{
		$class = '';
		unset($_SESSION['class']);
		//echo 'N';
	}
	//echo $c_arr['NadoOcenit']."*";
	if($c_arr['NadoOcenit']){
		$NadoOcenit = 1;
	}
	else{
		$NadoOcenit = 0;
	}
	if($c_id==0){
		$dbc->element_create("clients",array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['FIO']),
			"code_1C" => $c_arr['Code1C'],
			"iin" => $c_arr['IIN'],
			"rnn" => $rnn,
			"email" => $c_arr['Email'],
			"comment" => addslashes($c_arr['Comment']),
			"date_tochnaya" => $c_arr['ActualDate'],
			"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
			"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
			"res_prev_call" => $c_arr['Result'],
			"source" => $c_arr['Sourse'],
			"rating" => $c_arr['Rating'],
			"ocenit" => $NadoOcenit,
			"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))));

		$c_id = $dbc->ins_id;
	}
	else{
		$dbc->element_update('clients',$c_id,array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['FIO']),
			"code_1C" => $c_arr['Code1C'],
			"iin" => $c_arr['IIN'],
			"rnn" => $rnn,
			"email" => $c_arr['Email'],
			"comment" => addslashes($c_arr['Comment']),
			"date_tochnaya" => $c_arr['ActualDate'],
			"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
			"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
			"res_prev_call" => $c_arr['Result'],
			"source" => $c_arr['Sourse'],
			"rating" => $c_arr['Rating'],
			"ocenit" => $NadoOcenit,
			"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))));
	}
	$c_tel_arr = array();
	$j = 0;
	if(isset($c_arr['Telnumbers']['number'])){
		$number = $c_arr['Telnumbers'];
		$c_tel_arr[] = $number;
		if(getClientPhoneID($c_id, $number['number'])==0) {
			$dbc->element_create("phones", array(
				"client_id" => $c_id,
				"phone" => $number['number'],
				"comment" => $number['comment']));
			$j++;
		}
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

	ini_set("soap.wsdl_cache_enabled", "0" );
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
	$params["ManagerCode"] = LOGIN_1C;
	$params["Company"] = 3;
	$result = $client->GetPolicyNumber($params);
	$array = objectToArray($result);
	$polis_num = $array['return'];

	if($polis_num=='Нет прав страховать'){
		$_SESSION['bso'] = 0;
	}
	else{
		if($polis_num=='Это невероятно, но в принтерной нет  свободных полисов!!!'){
			$_SESSION['bso'] = 1;
		}
		else{
			$_SESSION['bso'] = $polis_num;
		}
	}
}
else{
	if(isset($_GET['item'])){
		$c_id = $_GET['item'];
		$_SESSION['1C'] = $c_id;
		$dbc->element_create("calls_log",array(
			"oper_id" => ROOT_ID,
			"date_start" => 'NOW()'));
	}
	else{
		if(isset($_SESSION['c_id'])){
			$c_id = $_SESSION['c_id'];
		}
	}
}

if(isset($c_id)){
	$_SESSION['c_id'] = $c_id;
	$tpl->assign("U_ID", $c_id);
}

if(isset($_SESSION['polis'])){
	$row9 = $dbc->element_find('polises',$_SESSION['polis']);
	if($row9['client_id']!=$c_id){
		unset($_SESSION['polis']);
	}
}
if(isset($c_id)){
	$row = $dbc->element_find('clients',$c_id);

	$tpl->assign("INFO_U_FIO", $row['fio']);
	$tpl->assign("INFO_U_NAME", $row['name']);
	$tpl->assign("INFO_U_IIN", $row['iin']);
	$tpl->assign("INFO_U_RNN", $row['rnn']);
	$tpl->assign("INFO_U_EMAIL", $row['email']);
	$tpl->assign("INFO_U_DATE_PREV_CALL", date("d-m-Y H:i",strtotime($row['date_prev_call'])));
	$tpl->assign("INFO_U_RES_PREV_CALL", $row['res_prev_call']);
	$tpl->assign("INFO_U_SOURCE", $row['source']);
	$tpl->assign("INFO_U_DATE_END", date("d-m-Y H:i",strtotime($row['date_end'])));
	$tpl->assign("INFO_U_COMMENT", $row['comment']);

	if(strtotime($row['date_lost'])==strtotime('1970-01-01 06:00:00')){
		$tpl->assign("INFO_U_DATE_LOST", 'Не страховался у нас');
	}
	else{
		$tpl->assign("INFO_U_DATE_LOST", date("d-m-Y H:i",strtotime($row['date_lost'])));
	}

	if($row['date_tochnaya']==1){
		$tpl->assign("INFO_U_DATE_TOCHNAYA", 'Да');
	}
	else{
		$tpl->assign("INFO_U_DATE_TOCHNAYA", 'Нет');
	}

	$tpl->assign("CLIENT_CODE_1C", $row['code_1C']);

	// cars
	ini_set("soap.wsdl_cache_enabled", "0" );
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
	$params["ClientCode1C"] = $row['code_1C'];

	$result = $client->GetClientInfo($params);
	$array = objectToArray($result);
	$c_arr = $array['return'];
	$LastPolicCars = '';
	if(isset($c_arr['LastPolicCars'])){
		if(is_array($c_arr['LastPolicCars'])){
			$c_arr['LastPolicCars'] = array_unique($c_arr['LastPolicCars']);
			$c = 1;
			foreach($c_arr['LastPolicCars'] as $v){
				if($c==1){
					$tpl->assign("INFO_U_CARS2", trim($v));
				}
				$LastPolicCars.= $v.'<br>';
				$c++;
			}
		}
		else{
			$tpl->assign("INFO_U_CARS2", trim($c_arr['LastPolicCars']));
			$LastPolicCars.= $c_arr['LastPolicCars'].'<br>';
		}
	}
	$tpl->assign("INFO_U_CARS", $LastPolicCars);


	// phones
	$rows = $dbc->dbselect(array(
			"table"=>"phones",
			"select"=>"*",
			"where"=>"client_id=".$c_id));
	$phones = '';
	$c =1;
	foreach($rows as $row){
		$star_phones = substr_replace($row['phone'], '*****', 4, 3);
		//echo $row['phone'];
		$phones.='<br> 
				<img src="images/bell1.png" class="img_call" align="absmiddle" style="margin:3px; cursor:pointer;">'.$star_phones.' ('.$row['comment'].')
				<input type="hidden" id="phone_call'.$c.'" value="'.$row['phone'].'" />';
		$c++;
	}
	$tpl->assign("INFO_U_PHONE", $phones);

	if(!in_array(5,$USER_ROLE)){
		ini_set("soap.wsdl_cache_enabled", "0" );
		$client3 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
			array(
			'login' => 'ws',
			'password' => '123456',
			'trace' => true
			)
		);
		$params3["ManagerCode"] = LOGIN_1C;
		$result3 = $client3->GetLimit($params3);
		$array3 = objectToArray($result3);

		$dbc->element_update('users',ROOT_ID,array(
			"l_limit" => $array3['return']));
		$tpl->assign("INFO_U_LITERS", $array3['return']);
	}
	else{
		$tpl->assign("INFO_U_LITERS", $user_row['l_limit']);
	}

	if(in_array(3,$USER_ROLE)||ROOT_ID==1){
		$tpl->assign("TECH_BTN", '<p><button type="button" class="btn_pero" onclick="javascript:window.location=\'/tehosmotr\';">Тех.осмотр</button></p>');
	}
	else{
		$tpl->assign("TECH_BTN", '');
	}

}
else{
	$tpl->assign("INFO_U_FIO", '');
	$tpl->assign("INFO_U_NAME", '');
	$tpl->assign("INFO_U_IIN", '');
	$tpl->assign("INFO_U_RNN", '');
	$tpl->assign("INFO_U_EMAIL", '');
	$tpl->assign("INFO_U_DATE_PREV_CALL", '');
	$tpl->assign("INFO_U_RES_PREV_CALL", '');
	$tpl->assign("INFO_U_SOURCE", '');
	$tpl->assign("INFO_U_DATE_END", '');
	$tpl->assign("INFO_U_COMMENT", '');
	$tpl->assign("INFO_U_DATE_LOST", '');
	$tpl->assign("INFO_U_DATE_TOCHNAYA", '');
	$tpl->assign("INFO_U_CARS", '');
	$tpl->assign("INFO_U_PHONE", '');

	$tpl->assign("INFO_U_LITERS", $user_row['l_limit']);

}



$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>