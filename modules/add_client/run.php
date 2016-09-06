<?php
# SETTINGS #############################################################################
$moduleName = "add_client";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
$c_id = $_SESSION['c_id'];
// сохранение обработанных данных по клиенту
if(isset($_POST['edt_item'])){
	ini_set("soap.wsdl_cache_enabled", "0" );

	$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
	$params2["Client"]["Code1C"] = '';
	$params2["Client"]["Name"] = $_POST['name'];
	$params2["Client"]["FIO"] = $_POST['name'];
	$params2["Client"]["IIN"] = $_POST['iin'];
	$params2["Client"]["RNN"] = $_POST['rnn'];
	$params2["Client"]["Email"] = $_POST['email'];
	$params2["Client"]["ManagerCode"] = LOGIN_1C;
	$params2["Client"]["ManagerName"] = $user_row['name'];
	$params2["Client"]["DateContact"] = date("Y-m-d H:i");
	$params2["Client"]["DateEndPolicy"] = date("Y-m-d");
	$params2["Client"]["Result"] = '';
	$params2["Client"]["Sourse"] = '';
	$params2["Client"]["Telnumbers"][]['number']=$_POST['phone'];
	$params2["Client"]["Error"]["Error_val"] = false;
	$params2["Client"]["Error"]["Error_exp"] = '';
	$params2["Client"]["Comment"] = '';
	$params2["Client"]["ActualDate"] = '';
	$params2["Client"]["DateLastPolicy"] = date("Y-m-d");
	$params2["Client"]["NadoOcenit"] = true;
	
	$result = $client2->SaveClient($params2);
	$array = objectToArray($result);
	
	if($array['return']!='Есть клиент с таким телефоном'){
		$dbc->element_create("clients",array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($_POST['name']),
			"code_1C" => $array['return'],
			"iin" => $_POST['iin'],
			"rnn" => $_POST['rnn'],
			"email" => $_POST['email']));
		$c_id = $dbc->ins_id;

		if(getClientPhoneID($c_id, $_POST['phone'])==0&&$_POST['phone']!=''){
			$dbc->element_create("phones", array(
				"client_id" => $c_id,
				"phone" => $_POST['phone']));
		}

		header("Location: /".getItemCHPU(1, 'pages')."/?item=".$c_id);
		exit;

	}
	else{
		$tpl->assign("U_ADD_ERR", 'Есть клиент с таким телефоном!!!<br>Воспользуйтесь поиском');
	}
}
else{
	$tpl->assign("U_ADD_ERR", '');
}


$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>