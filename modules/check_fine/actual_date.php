<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
define("DB_HOST", "localhost");
define("DB_NAME", "db_crm");
define("DB_LOGIN", "u_crm");
define("DB_PASSWORD", "123456");

date_default_timezone_set ("Asia/Almaty");

include('../../inc/XML2Array.php');

// ������������� ���������� � �����. � ������ ������ ��������� �����������.
function db_connect($db_host, $db_login, $db_password) {
	$conect_id = mysql_connect($db_host, $db_login, $db_password);
	if (!$conect_id) {
		exit();
	}
	else{
		return $conect_id;
	}
}

function db_select_db($db_name) {
	if (!mysql_select_db($db_name)) {
		echo "<br><b>Error in selecting database</b><br>";
		echo "<br><b>Error #: </b>" . db_errno() . "<br>";
		echo "<br><b>Error message: </b>" . db_error() . "<br>";
		exit;
	}
	$result = db_query('SET NAMES utf8;');
}

function db_query($query) {
	if (!$result = mysql_query($query)) {
		echo "<br><b>$query</b><br>";
		echo "<br><b>Error in executing query</b><br>";
		echo "<br><b>Error #: </b>" . db_errno() . "<br>";
		echo "<br><b>Error message: </b>" . db_error() . "<br>";
	} else {
		return $result;
	}
}

function db_table_count($table, $where) {
	if (!empty($where)) $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}"; 
		else $sql = "SELECT COUNT(*) FROM {$table}";
	if (!$result = mysql_query($sql)) {
		return -1;
	} else {
		$count = mysql_fetch_array($result);
		return $count[0];
	}
}

function db_errno() {
	return mysql_errno();
}

function db_error() {
	return mysql_error();
}

function db_num_rows($result) {
	return mysql_num_rows($result);
}

function db_fetch_array($result) {
	return mysql_fetch_array($result);
}

function db_insert_id() {
	return mysql_insert_id();
}


function db_get_data($sql, $field = '') {
	$result = db_query($sql);
	if (db_num_rows($result) > 0) {
		$row = db_fetch_array($result);
		if ($field == '')
			return $row;
		 else
			return $row[$field];
	}
	return 0;
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

error_reporting (E_ALL);
ini_set("display_errors", "1");
$link = db_connect(DB_HOST, DB_LOGIN, DB_PASSWORD);
db_select_db(DB_NAME);

######################################################################################################################
// чистим текст
function SuperSaveStr($name) {
	$name = strip_tags($name);
	$name = trim($name);
	$name = preg_replace("/[^\x20-\xFF]/","",@strval($name));
	return $name;
}
	

ini_set("soap.wsdl_cache_enabled", "0" ); 
$client = new SoapClient("https://".$_POST['CESNA_SERV'].".novelty.kz:28110/WebBridge/WebBridge?wsdl", 
		array( 
		'trace' => true
		) 
	);
//aName: auth, aPassword: comp_pass, aAlias: 'cesna'
$params["aName"] = 'autoclub.'.$_POST['SESSION_NUM'].'[TIGAI]';
$params["aPassword"] = $_POST['CESNA_PSW'];
$strach_comp = 'cesna';

$params["aAlias"] = $strach_comp;
$result = $client->authenticateUser($params); 
$array = objectToArray($result);
$ses_arr = $array['return'];
//print_r($ses_arr);
$_SESSION['C_SES'] = $ses_arr['sessionID'];

$params3["aSessionID"] = $_SESSION['C_SES'];
$params3["aRequest"]["Type"] = 'SearchVehicle';
$params3["aRequest"]["Version"] = '2';

$body = '';
$body.= '<VIN></VIN>';
$body.= '<REG_NUMBER>'.$_POST['REG_NUMBER'].'</REG_NUMBER>';
'<ENG_NUMBER></ENG_NUMBER>';

$params3["aRequest"]['Body'] = base64_encode('<BODY_TEXT>
	'.$body.'
	</BODY_TEXT>');
//echo $params3["aRequest"]['Body'];
$result = $client->getDataXml($params3); 
$array = objectToArray($result);
//print_r($array['return']);
$search_res = false;
if($array['return']['ResultCode']==0){
	$cars = XML2Array::createArray(base64_decode($array['return']['Body']));
	$search_res = true;
	$request_type = 2;
}
else{
	$params3["aRequest"]["Version"] = '1';
	$result = $client->getDataXml($params3); 
	$array = objectToArray($result);
	if($array['return']['ResultCode']==0){
		$cars = XML2Array::createArray(base64_decode($array['return']['Body']));
		$search_res = true;
		$request_type = 1;
	}
}




$i = 0;

foreach($cars['VEHICLES'] as $car){
	if($request_type != 1){
		$params3["aRequest"]["Type"] = 'LoadPolicyForRewrite';
		$params3["aRequest"]["Version"] = '1';
		
		$body = '<POLICY_ID></POLICY_ID><POLICY_NUMBER>'.$car['POLICY_NUMBER'].'</POLICY_NUMBER><OPERATION></OPERATION>';
		//echo "<p>".$body;
		$params3["aRequest"]['Body'] = base64_encode('<BODY_TEXT>
			'.$body.'</BODY_TEXT>');
		//echo $params3["aRequest"]['Body'];
		$result = $client->getDataXml($params3); 
		$array = objectToArray($result);
		//print_r($array['return']);
		$search_res = false;
		if($array['return']['ResultCode']==0){
			$polises = XML2Array::createArray(base64_decode($array['return']['Body']));
			//echo base64_decode($array['return']['Body']);
			$search_res = true;
			//echo "<p>";
			//print_r($polises);
			$polis = $polises['POLICY'];
			$v_den = ceil($polis['PREMIUM']/365);
			$v_den2 = ceil($polis['PREMIUM']/182);
			$dengi = ceil(($polis['PREMIUM'] - $polis['NOT_GAINED_PREMIUM'])/dateDifference(date("Y-m-d") , date("Y-m-d",strtotime($polis['POLICY_DATE']))));
			if($dengi<=$v_den){
				$ostat_day = ceil($polis['NOT_GAINED_PREMIUM']/$v_den);
			}
			else{
				$ostat_day = ceil($polis['NOT_GAINED_PREMIUM']/$v_den2);
			}
			
			if($ostat_day<0){
				$polis_date = date("d.m.Y",strtotime($ostat_day." days"));
			}
			else{
				$polis_date = date("d.m.Y",strtotime("+".$ostat_day." days"));
			}
			$sql = "UPDATE users SET 
					date_tochnaya = '1',
					date_end = '".date("Y-m-d H:i",strtotime($polis_date))."'
			WHERE id = ".$_POST['U_ID'];
			db_query($sql);
			
			
			ini_set("soap.wsdl_cache_enabled", "0" ); 
			
			$SQL7 = "SELECT * FROM users WHERE id = ".$_POST['U_ID'];
			$result7 = db_query($SQL7);
			$row7 = db_fetch_array($result7);
			
			$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
				array( 
				'login' => 'ws', 
				'password' => '123456', 
				'trace' => true
				) 
			);
			$params2["Client"]["Code1C"] = $row7['code_1C'];
			$params2["Client"]["Name"] = $row7['name'];
			$params2["Client"]["FIO"] = $row7['name'];
			$params2["Client"]["IIN"] = $row7['iin'];
			$params2["Client"]["RNN"] = $row7['rnn'];
			$params2["Client"]["Email"] = $row7['email'];
			$params2["Client"]["ManagerCode"] = $_POST['LOGIN_1C'];
			$params2["Client"]["ManagerName"] = getOperNameID($_POST['ROOT_ID']);
			$params2["Client"]["DateContact"] = date("Y-m-d H:i");
			$params2["Client"]["DateEndPolicy"] = date("Y-m-d",strtotime($polis_date));
			$params2["Client"]["Result"] = '';
			$params2["Client"]["Sourse"] = $row7['source'];
			
			
			$SQL = "SELECT phone, comment FROM phones WHERE user_id = ".$_POST['U_ID'];
			$result = db_query($SQL);
			$j = 0;
			while($row = db_fetch_array($result)){
				$params2["Client"]["Telnumbers"][$j]['number']=$row['phone'];
				$params2["Client"]["Telnumbers"][$j]['comment']=$row['comment'];
				$j++;
			}
			$params2["Client"]["Error"]["Error_val"] = false;
			$params2["Client"]["Error"]["Error_exp"] = 'Success';
			$params2["Client"]["Comment"] = '';
			$params2["Client"]["ActualDate"] = true;
			$params2["Client"]["DateLastPolicy"] = date("Y-m-d",strtotime($row7['date_lost']));
			$params2["Client"]["Rating"] = '0';
			//print_r($params2);
			$result = $client2->SaveClient($params2); 
		}
	}
}


//header("Content-Type: text/html;charset=utf-8");
//echo json_encode($out_row);
?>
