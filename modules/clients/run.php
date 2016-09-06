<?php
# SETTINGS #############################################################################
$moduleName = "clients";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "call_target" => $prefix . "call_target.tpl",
));
# MAIN #################################################################################
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
}
// сохранение обработанных данных по клиенту
if(isset($_POST['edt_item'])){
	$dbc->element_update('clients',$c_id,array(
		"name" => addslashes($_POST['name']),
		"email" => $_POST['email'],
		"iin" => $_POST['iin'],
		"rnn" => $_POST['rnn'],
		"date_end" => date("Y-m-d H:i",strtotime($_POST['date_end']))));
	
	if(getClientPhoneID($c_id, $_POST['phone'])==0&&$_POST['phone']!=''){
		$dbc->element_create("phones", array(
			"client_id" => $c_id,
			"phone" => $_POST['phone']));
	}
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456',
		'trace' => true
		) 
	);
	$params["iin"] = $_POST['iin'];
	$params["rnn"] = $_POST['rnn'];
	$params["telnumber"] = $_POST['h_phone'];
	$result = $client->SearchClient($params); 
	$с_arr = objectToArray($result);
	if(isset($с_arr['return']['Client'][0])){
		$с_arr = $с_arr['return']['Client'][0];
	}
	else{
		$с_arr = $с_arr['return']['Client'];
	}
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params2["Client"]["Code1C"] = $_POST['code_1C'];
	$params2["Client"]["Name"] = $_POST['name'];
	$params2["Client"]["FIO"] = $_POST['name'];
	$params2["Client"]["IIN"] = $_POST['iin'];
	$params2["Client"]["RNN"] = $_POST['rnn'];
	$params2["Client"]["Email"] = $_POST['email'];
	$params2["Client"]["ManagerCode"] = LOGIN_1C;
	$params2["Client"]["ManagerName"] = $user_row['name'];
	$params2["Client"]["DateContact"] = date("Y-m-d H:i",strtotime($с_arr['DateContact']));
	$params2["Client"]["DateEndPolicy"] = date("Y-m-d",strtotime($_POST['date_end']));
	$params2["Client"]["Result"] = $с_arr['Result'];
	$params2["Client"]["Sourse"] = $с_arr['Sourse'];

	foreach($_POST['phone_comment'] as $key=>$v){
		$dbc->element_update('phones',$key,array(
			"comment" => addslashes($v)));
	}

	$rows = $dbc->dbselect(array(
		"table"=>"phones",
		"select"=>"phone, comment",
		"where"=>"client_id=".$c_id));
	$j = 0;
	foreach($rows as $row){
		$params2["Client"]["Telnumbers"][$j]['number']=$row['phone'];
		$params2["Client"]["Telnumbers"][$j]['comment']=$row['comment'];
		$j++;
	}
	$params2["Client"]["Error"] = $с_arr['Error'];
	$params2["Client"]["Comment"] = $с_arr['Comment'];
	$params2["Client"]["ActualDate"] = $с_arr['ActualDate'];
	$params2["Client"]["DateLastPolicy"] = $с_arr['DateLastPolicy'];
	$params2["Client"]["Rating"] = '0';
	$params2["Client"]["NadoOcenit"] = true;
	$result = $client2->SaveClient($params2);
	$url = getCodeBaseURL("index.php?menu=".$_GET['menu']);
	header("Location: ".$url);
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
	
	$result = $client2->SaveCall($params2);

	$dbc->element_create("oper_log", array(
		"oper_id" => ROOT_ID,
		"oper_act_type_id" => 1,
		"oper_act_id" => 1,
		"date_log" => 'NOW()',
		"comment" => addslashes($_POST['call_comment']).". Длительность: ".$call_lenght));
	
	$log = getOperCurentMaxLog(ROOT_ID);
	$dbc->element_update('calls_log',$log,array(
		"res" => $_POST['res_call_id'],
		"date_end" => 'NOW()'));
	header("Location: /".getItemCHPU(2176, 'pages'));
	exit;
}

$tpl->parse("META_LINK", ".".$moduleName."html");
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
	$row = $dbc->element_find('clients',$c_id);
	$tpl->assign("EDT_NAME", $row['name']);
	$tpl->assign("EDT_1C_CODE", $row['code_1C']);
	$tpl->assign("EDT_IIN", $row['iin']);
	$tpl->assign("EDT_RNN", $row['rnn']);
	$tpl->assign("EDT_EMAIL", $row['email']);
	$tpl->assign("EDT_COMMENT", $row['comment']);
	$tpl->assign("EDT_DATE_PREV_CALL", date("d-m-Y H:i",strtotime($row['date_prev_call'])));
	$tpl->assign("EDT_RES_PREV_CALL", $row['res_prev_call']);
	$tpl->assign("EDT_SOURCE", $row['source']);
	$tpl->assign("EDT_DATE_END", date("d-m-Y",strtotime($row['date_end'])));
	$tpl->assign("EDT_COMMENT", $row['comment']);
	if($row['ocenit']==1||in_array(8,$USER_ROLE)){
		$tpl->assign("OCEN_HIDE1", '');
		$tpl->assign("OCEN_HIDE2", '');
	}
	else{
		$tpl->assign("OCEN_HIDE1", '<!--');
		$tpl->assign("OCEN_HIDE2", '-->');
	}
	if(isset($row['rating'])){
		/*
		switch($row['rating']){
			case 0:
				$tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо уточнить только ФИО,ГОРОД,НАЛИЧИЕ АВТОМОБИЛЯ');
			break;
			case 1:
				$tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо уточнить только ФИО,ГОРОД,НАЛИЧИЕ АВТОМОБИЛЯ');
			break;
			case 2:
				$tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо применить скрипты Расчет налога, Перечень штрафов и ЦОАП, КОНЕЧНАЯ ЦЕЛЬ ТД');
			break;
			case 3:
				$tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>4 Вопроса, Застраховался у нас');
			break;
			case 4:
				$tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Застраховался у нас');
			break;
		}
		*/
		$tpl->assign("CALL_TARGET_RATING", $row['rating']);
		$tpl->assign("CALL_TARGET_TD", date("d-m-Y",strtotime($row['date_end'])));
		$tpl->assign("CALL_TARGET_PREV_DATE", date("d-m-Y H:i",strtotime($row['date_prev_call'])));
		$tpl->assign("CALL_TARGET_PREV_RES", $row['res_prev_call']);
		$tpl->parse("CALL_TARGET", ".".$moduleName."call_target");



		if(strtotime($row['date_lost'])==strtotime('1970-01-01 06:00:00')){
			$tpl->assign("EDT_DATE_LOST", 'Не страховался у нас');
		}
		else{
			$tpl->assign("EDT_DATE_LOST", date("d-m-Y H:i",strtotime($row['date_lost'])));
		}
		if($row['date_tochnaya']==1){
			$tpl->assign("EDT_DATE_TOCHNAYA", 'Да');
		}
		else{
			$tpl->assign("EDT_DATE_TOCHNAYA", 'Нет');
		}

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

		$tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y H:i",strtotime("+ 1 hour")));
		$c_id = $_SESSION['c_id'];
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
			$star_phones = substr_replace($row['phone'], '*****', 4, 3);
			$phones.=$star_phones.'<br>Комментарий: <input type="text" name="phone_comment['.$row['id'].']" value="'.$row['comment'].'"  class="pole_vvoda" style="padding-left:10px;"> <br>';
		}
		$tpl->assign("EDT_PHONES", $phones);
	}
	else{
		$tpl->assign("CALL_TARGET", '');
	}					
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