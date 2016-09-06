<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

// SOAP
function objectToArray($d) {
if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}
function stdToArray($obj){
  $rc = (array)$obj;
  foreach($rc as $key => &$field){
	if(is_object($field))$field = $this->stdToArray($field);
  }
  return $rc;
}

$dbc->element_create("control_log",array(
	"root_id" => $_POST['ROOT_ID'],
	"oper_id" => $_POST['oper_id'],
	"phone" => $_POST['phone'],
	"control" => $_POST['Ocenka'],
	"res" => $_POST['res_id'],
	"date" => 'NOW()'));








ini_set("soap.wsdl_cache_enabled", "0" ); 
$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
	array( 
	'login' => 'ws', 
	'password' => '123456', //пароль 
	'trace' => true
	) 
);
$params["iin"] = '';
$params["rnn"] ='';
$params["telnumber"] = $_POST['phone'];
$params["ClientCode1C"] = '';
$params["PolicNumber"] = '';
$result = $client->SearchClient($params); 
$u_arr = objectToArray($result);
if(isset($u_arr['return']['Client'][0])){
	$u_arr = $u_arr['return']['Client'][0];
}
else{
	$u_arr = $u_arr['return']['Client'];
}

//print_r($u_arr);
ini_set("soap.wsdl_cache_enabled", "0" ); 

$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
	array( 
	'login' => 'ws', 
	'password' => '123456', 
	'trace' => true
	) 
);
$params2["Client"]["Code1C"] = $u_arr['Code1C'];
$params2["Client"]["Name"] = '';
$params2["Client"]["FIO"] = '';
$params2["Client"]["IIN"] = '';
$params2["Client"]["RNN"] = '';
$params2["Client"]["Email"] = '';
$params2["Client"]["ManagerCode"] = $_POST['LOGIN_1C'];
$params2["Client"]["ManagerName"] = '';
$params2["Client"]["DateContact"] =date('Y-m-d',strtotime(date("Y-m-d",strtotime($_POST['date_end'])) . " - 7 day"));
$params2["Client"]["DateEndPolicy"] = date("Y-m-d",strtotime($_POST['date_end']));
$params2["Client"]["Result"] = $u_arr['Result'];
$params2["Client"]["Sourse"] = $u_arr['Sourse'];
$params2["Client"]["Telnumbers"][0]['number']='';
$params2["Client"]["Telnumbers"][0]['comment']='';
$params2["Client"]["Error"] = $u_arr['Error'];
$params2["Client"]["Comment"] = '';
$params2["Client"]["ActualDate"] = $u_arr['ActualDate'];
$params2["Client"]["DateLastPolicy"] = $u_arr['DateLastPolicy'];
$params2["Client"]["Rating"] = '0';
$params2["Client"]["NadoOcenit"] = FALSE;

//print_r($params2);
$result = $client2->SaveClient($params2); 











$out_row['result'] = 'OK';
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
