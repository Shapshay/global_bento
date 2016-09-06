<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
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



if(isset($_POST['LOGIN_1C'])){
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client7 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params7['TechOsmotr']["Code1C"] = $_POST['U_1C'];
	$params7['TechOsmotr']["ManagerCode"] = $_POST['LOGIN_1C'];
	$params7['TechOsmotr']["DateTech"] = date("Y-m-d");
	$params7['TechOsmotr']["Gosnomer"] = $_POST['gn'];
	$params7['TechOsmotr']["Dostavka"] = 1;
	$params7['TechOsmotr']["Address"] = $_POST['dost_adr'];
	
	$result7 = $client7->SaveTech($params7); 
	$array = objectToArray($result7);
	//$u_arr = $array['return']['Info'];
	$out_row['result'] = 'OK';
	$out_row['str'] = $array['return'];
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
