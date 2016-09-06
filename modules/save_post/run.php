<?php
# SETTINGS #############################################################################
$moduleName = "save_post";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "main2" => $prefix . "main2.tpl",
		$moduleName . "html" => $prefix . "html.tpl",

));

# MAIN #################################################################################
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
}
if(isset($_SESSION['post_id'])){
	$post_id = $_SESSION['post_id'];
}
if(isset($_SESSION['post_id'])){
    //echo "Y";
	$rows = $dbc->dbselect(array(
			"table"=>"clients, post_control",
			"select"=>"clients.code_1C as code_1C,
			post_control.date_next_call as date_next_call,
			post_control.result as result,
			post_control.comment as comment,
			post_control.ocen as ocen,
			post_control.email as email",
			"where"=>"clients.id = ".$c_id." AND  post_control.id = ".$post_id,
			"limit"=>1
		)
	);
	$row = $rows[0];

	
	// Сохраняем в 1С
	$client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);

	$params2["CallPost"]["Code1C"] = $row['code_1C'];
	$params2["CallPost"]["ManagerCode"] = LOGIN_1C;
	$params2["CallPost"]["DateContact"] = date("Y-m-d\TH:i:s",strtotime($row['date_next_call']));
	$params2["CallPost"]["Result"] = $row['result'];
	$params2["CallPost"]["Comment"] = addslashes($row['comment']);
	$params2["CallPost"]["Ocenka"] = $row['ocen'];
	$params2["CallPost"]["Email"] = $row['email'];
    /*print_r($params2);
    echo "<p>";*/
    $result7 = $client7->SaveCallPost($params2);
	$array_save = objectToArray($result7);
	$res_save_1c = $array_save['return'];
    print_r($array_save);
	if($res_save_1c=='Success'){
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

		header("Location: /".getItemCHPU(2212, 'pages'));
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