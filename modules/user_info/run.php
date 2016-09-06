<?php
# SETTINGS #############################################################################
$moduleName = "user_info";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и инфо клиента

ini_set("soap.wsdl_cache_enabled", "0" );
$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
	array(
	'login' => 'ws',
	'password' => '123456',
	'trace' => true
	)
);

$row = $dbc->element_find('clients',$c_id);

$params["ClientCode1C"] =$row['code_1C'];
//echo $params["ClientCode1C"];
$result = $client->GetClientInfo($params);
$array = objectToArray($result);
$u_arr = $array['return'];
//print_r($u_arr);
//echo '<p>';

$PolicDrivers = '';
if(is_array($u_arr['LastPolicDrivers'])){
	foreach($u_arr['LastPolicDrivers'] as $v){
		$PolicDrivers.= $v.'<br>';
	}
}
else{
	$PolicDrivers = $u_arr['LastPolicDrivers'];
}


$LastPolicCars = '';
if(is_array($u_arr['LastPolicCars'])){
	$u_arr['LastPolicCars'] = array_unique($u_arr['LastPolicCars']);
	foreach($u_arr['LastPolicCars'] as $v){
		$LastPolicCars.= $v.'<br>';
	}
}
else{
	$LastPolicCars.= $u_arr['LastPolicCars'].'<br>';
}


$tpl->assign("INFO_U2_NUMBER", $u_arr['LastPolicNumber']);
$tpl->assign("INFO_U2_DATE", $u_arr['LastPolicDate']);
$tpl->assign("INFO_U2_DRIVERS", $PolicDrivers);
$tpl->assign("INFO_U2_PREMIUM", $u_arr['LastPolicPremium']);
$tpl->assign("INFO_U2_SUM", $u_arr['LastPolicSumm']);
$tpl->assign("INFO_U2_CURIER", $u_arr['LastPolicCourier']);
$tpl->assign("INFO_U2_CARS", $LastPolicCars);


$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>