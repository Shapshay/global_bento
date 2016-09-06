<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################

// проверка сочетания Внутренего телефона/Времени звонка
function setVnutrCallTimeID($vnutr, $link) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"oper_calls",
			"select"=>"id",
			"where"=>"phone2 = '".$vnutr."' AND link = '".$link."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}
// возвращает размер удаленного файла
function getRemoteFileSize($url){
	$parse = parse_url($url);
	$host = $parse['host'];
	$fp = @fsockopen ($host, 80, $errno, $errstr, 20);
	if(!$fp){
		$ret = 0;
	}else{
		$host = $parse['host'];
		fputs($fp, "HEAD ".$url." HTTP/1.1\r\n");
		fputs($fp, "HOST: ".$host."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		$headers = "";
		while (!feof($fp)){
			$headers .= fgets ($fp, 128);
		}
		fclose ($fp);
		$headers = strtolower($headers);
		$array = preg_split("|[\s,]+|",$headers);
		$key = array_search('content-length:',$array);
		$ret = $array[$key+1];
	}
	if($array[1]==200) return $ret;
	else return -1*$array[1];
}

#$precision количество цифр после точки 1.23 MB
function FBytes($bytes, $precision = 2) {
	$units = array('B', 'KB', 'MB', 'GB', 'TB');
	$bytes = max($bytes, 0);
	$pow = floor(($bytes?log($bytes):0)/log(1024));
	$pow = min($pow, count($units)-1);
	$bytes /= pow(1024, $pow);
	return round($bytes, $precision).' '.$units[$pow];
}

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

// ID клиента по коду 1С
function getClientID($code_1C) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"clients",
			"select"=>"id",
			"where"=>"code_1C = '".$code_1C."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

// ID телефона клиента по номеру
function getUserPhoneID($u_id, $phone) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"phones",
			"select"=>"id",
			"where"=>"client_id = '".$u_id."' AND phone = '".$phone."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}

// ID клиента по коду 1С
function getOperCode1CId($code) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"id",
			"where"=>"login_1C = '".$code."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['id'];
	}
	else{
		return 0;
	}
}
 
######################################################################################################################

if(isset($_GET['ClientCode1C'])&&isset($_GET['OperCode1C'])){
	//echo 'OK3<br>';
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["iin"] = '';
	$params["rnn"] = '';
	$params["telnumber"] = '';
	$params["ClientCode1C"] = $_GET['ClientCode1C'];
	$result = $client->SearchClient($params); 
	$array = objectToArray($result);
	$i = 0;
	foreach($array['return'] as $clients){
		if(isset($clients[0]['Code1C'])){
			print_r($clients);
			echo '*<p>';
			foreach($clients as $client){
				//print_r($client);
				//echo '*<p>';
				$u_arr = $client;
				//print_r($u_arr);
				$u_id = getClientID($u_arr['Code1C']);
				if(isset($u_arr['RNN'])){
					$rnn = $u_arr['RNN'];
				}
				else{
					$rnn = '';
				}
				if($u_id==0){
					$dbc->element_create("clients", array(
						"oper_id" => getOperCode1CId($_GET['OperCode1C']),
						"name" => $u_arr['Name'],
						"code_1C" => $u_arr['Code1C'],
						"iin" => $u_arr['IIN'],
						"rnn" => $rnn,
						"email" => $u_arr['Email'],
						"comment" => $u_arr['Comment'],
						"date_tochnaya" => $u_arr['ActualDate'],
						"date_lost" => date("Y-m-d H:i",strtotime($u_arr['DateLastPolicy'])),
						"date_prev_call" => date("Y-m-d H:i",strtotime($u_arr['DateContact'])),
						"res_prev_call" => $u_arr['Result'],
						"source" => $u_arr['Sourse'],
						"date_end" => date("Y-m-d H:i",strtotime($u_arr['DateEndPolicy'])),
						"date_stek" => 'NOW()',
						"stek" => 1));
					$u_id = $dbc->ins_id;
				}
				else{
					$dbc->element_update('oper_calls',$u_id,array(
						"oper_id" => getOperCode1CId($_GET['OperCode1C']),
						"name" => $u_arr['Name'],
						"iin" => $u_arr['IIN'],
						"rnn" => $rnn,
						"email" => $u_arr['Email'],
						"comment" => $u_arr['Comment'],
						"date_tochnaya" => $u_arr['ActualDate'],
						"date_lost" => date("Y-m-d H:i",strtotime($u_arr['DateLastPolicy'])),
						"date_prev_call" => date("Y-m-d H:i",strtotime($u_arr['DateContact'])),
						"res_prev_call" => $u_arr['Result'],
						"source" => $u_arr['Sourse'],
						"date_end" => date("Y-m-d H:i",strtotime($u_arr['DateEndPolicy'])),
						"date_stek" => 'NOW()',
						"stek" => 1));
				}
				$u_tel_arr = array();
				foreach($u_arr['Telnumbers'] as $numbers){
					if(is_array($numbers)){
						foreach($numbers as $number){
							$u_tel_arr[] = $number;
							if(getUserPhoneID($u_id, $number)==0){
								$dbc->element_create("phones", array(
									"client_id" => $u_id,
									"phone" => "'".$number."'"));
							}
						}
					}
					else{
						$u_tel_arr[] = $numbers;
						if(getUserPhoneID($u_id, $numbers)==0){
							$dbc->element_create("phones", array(
								"client_id" => $u_id,
								"phone" => "'".$numbers."'"));
						}
					}
				}
				
				$i++;
			}
		}
		else{
			$u_arr = $clients;

			$u_id = getClientID($u_arr['Code1C']);
			
			if(isset($u_arr['RNN'])){
				$rnn = $u_arr['RNN'];
			}
			else{
				$rnn = '';
			}
			if($u_id==0){
				$dbc->element_create("clients", array(
					"oper_id" => getOperCode1CId($_GET['OperCode1C']),
					"name" => $u_arr['Name'],
					"code_1C" => $u_arr['Code1C'],
					"iin" => $u_arr['IIN'],
					"rnn" => $rnn,
					"email" => $u_arr['Email'],
					"comment" => $u_arr['Comment'],
					"date_tochnaya" => $u_arr['ActualDate'],
					"date_lost" => date("Y-m-d H:i",strtotime($u_arr['DateLastPolicy'])),
					"date_prev_call" => date("Y-m-d H:i",strtotime($u_arr['DateContact'])),
					"res_prev_call" => $u_arr['Result'],
					"source" => $u_arr['Sourse'],
					"date_end" => date("Y-m-d H:i",strtotime($u_arr['DateEndPolicy'])),
					"date_stek" => 'NOW()',
					"stek" => 1));
				$u_id = $dbc->ins_id;
			}
			else{
				$dbc->element_update('oper_calls',$u_id,array(
					"oper_id" => getOperCode1CId($_GET['OperCode1C']),
					"name" => $u_arr['Name'],
					"iin" => $u_arr['IIN'],
					"rnn" => $rnn,
					"email" => $u_arr['Email'],
					"comment" => $u_arr['Comment'],
					"date_tochnaya" => $u_arr['ActualDate'],
					"date_lost" => date("Y-m-d H:i",strtotime($u_arr['DateLastPolicy'])),
					"date_prev_call" => date("Y-m-d H:i",strtotime($u_arr['DateContact'])),
					"res_prev_call" => $u_arr['Result'],
					"source" => $u_arr['Sourse'],
					"date_end" => date("Y-m-d H:i",strtotime($u_arr['DateEndPolicy'])),
					"date_stek" => 'NOW()',
					"stek" => 1));
			}
			$u_tel_arr = array();
			foreach($u_arr['Telnumbers'] as $numbers){
				if(is_array($numbers)){
					foreach($numbers as $number){
						$u_tel_arr[] = $number;
						if(getUserPhoneID($u_id, $number)==0){
							$dbc->element_create("phones", array(
								"client_id" => $u_id,
								"phone" => "'".$number."'"));
						}
					}
				}
				else{
					$u_tel_arr[] = $numbers;
					if(getUserPhoneID($u_id, $numbers)==0){
						$dbc->element_create("phones", array(
							"client_id" => $u_id,
							"phone" => "'".$numbers."'"));
					}
				}
			}
			$i++;
		}
		
	}

	$dbc->element_fields_update("users","login_1C = '".$_GET['OperCode1C']."'", array(
		"date_stek" => 'NOW()',
		"stek" => 1,
		"code_1C" => $_GET['ClientCode1C']));

	echo 'OK';
}
else{
	echo 'Error';
}


?>
