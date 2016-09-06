<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

########################################################################################################################

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





if(isset($_POST['iin'])) {
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://89.218.11.74/soap_server/wsdl");
	$result = $client->get_kbm($_POST['iin']); 
	//$result = $client->get_kbm('741017302590'); 
	$u_arr = objectToArray($result);
	if($u_arr['coef']==''){
		$out_row['result'] = 'Err';
	}
	else{
		$out_row['result'] = 'OK';
		$out_row['coef'] = $u_arr['coef'];
		$out_row['class_name'] = $u_arr['class_name'];
		$out_row['client_name'] = $u_arr['client_name'];
	}
	
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>
