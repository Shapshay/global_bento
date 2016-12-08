<?php
# SETTINGS #############################################################################
$moduleName = "add_sto_client";
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
	$client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
		array(
			'login' => 'ws',
			'password' => '123456',
			'trace' => true
		)
	);
	$params2["Client"]["Name"] = $_POST['name'];
	$params2["Client"]["DateOfEnd"] = date("Y-m-d",strtotime($_POST['date_to_end']));
	$params2["Client"]["GosNomer"] = $_POST['gn'];
	$params2["Client"]["TechPassport"] = $_POST['pn'];
	$params2["Client"]["Mark"] = $_POST['mark'];
	$params2["Client"]["Model"] = $_POST['model'];
	$params2["Client"]["GodVypusk"] = $_POST['born'];
	$params2["Client"]["Telefon"] = $_POST['phone'];
	$params2["Client"]["Telefon1"] = $_POST['phone2'];
	$params2["Client"]["Email"] = $_POST['email'];
	$params2["Client"]["Comment"] = '';
	$params2["Client"]["Iin"] = '';
	$params2["Client"]["Code1C"] = '';
	//print_r($params2);
	$result = $client2->SaveClient1($params2);
	$array = objectToArray($result);
    //print_r($array);
	if($array['return']!='Есть клиент с таким телефоном'){
		$dbc->element_create("sto",array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($_POST['name']),
			"code_1C" => $array['return'],
            "gn" => $_POST['gn'],
            "pn" => $_POST['pn'],
            "phone" => $_POST['phone'],
            "phone2" => $_POST['phone2'],
            "mark" => $_POST['mark'],
            "model" => $_POST['model'],
            "born" => $_POST['born'],
            "date_to_end" => date("Y-m-d",strtotime($_POST['date_to_end'])),
            "email" => $_POST['email']));
		$c_id = $dbc->ins_id;
		$_SESSION['c_id'] = $c_id;

		

		header("Location: /sto/?item=".$c_id);
		exit;

	}
	else{
		$tpl->assign("U_ADD_ERR", 'Есть клиент с таким телефоном!!!<br>Воспользуйтесь поиском');
	}
}
else{
	$tpl->assign("U_ADD_ERR", '');
}

$tpl->assign("CURENT_DATE", date("d-m-Y"));
$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>