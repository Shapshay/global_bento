<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
// чистим текст
function SuperSaveStr($name) {
	$name = strip_tags($name);
	$name = trim($name);
	$name = preg_replace("/[^\x20-\xFF]/","",@strval($name));
	return $name;
}
	
function post_content ($url,$postdata) {
  $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

  $ch = curl_init( $url );
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_HEADER, 0);
//  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_ENCODING, "");
  curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
  curl_setopt($ch, CURLOPT_TIMEOUT, 120);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
  curl_setopt($ch, CURLOPT_COOKIEJAR, "java/grab/cook/coo.txt");
  curl_setopt($ch, CURLOPT_COOKIEFILE,"java/grab/cook/coo.txt");

  $content = curl_exec( $ch );
  $err     = curl_errno( $ch );
  $errmsg  = curl_error( $ch );
  $header  = curl_getinfo( $ch );
  curl_close( $ch );

  $header['errno']   = $err;
  $header['errmsg']  = $errmsg;
  $header['content'] = $content;
  return $header;
}

function setOperDateCounter($oper_id) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"oper_counter",
			"select"=>"id",
			"where"=>"oper_id = '".$oper_id."' AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
			"limit"=>"1"
		)
	);
	$row = $rows[0];
	return $row['id'];
}

function setDateCounter($oper_id, $count_type, $gn) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"oper_counter_log",
			"select"=>"COUNT(id) AS num",
			"where"=>"oper_id = '".$oper_id."' AND gn = '".$gn."' AND count_type = ".$count_type." AND NOW() < ADDDATE(date, INTERVAL 12 HOUR)",
			"limit"=>"1"
		)
	);
	$row = $rows[0];
	$row = $rows[0];
	return $row['num'];
}

//##################################################################################################

if(isset($_POST['tab_f_email'])){
	$f_name = SuperSaveStr($_POST['tab_f_name']);
	$f_email = SuperSaveStr($_POST['tab_f_email']);

	if(setDateCounter($_POST['ROOT_ID'], 3, $f_email)==0){
		$url = 'http://coap.kz/shtrafy';
		$postdata = 'tab_f_name='.$f_name.'&tab_f_email='.$f_email;
		$result = post_content( $url, $postdata );
		$html2 = $result['content'];

		$out_row['result'] = 'OK';

		$counter_id = setOperDateCounter($_POST['ROOT_ID']);
		if($counter_id==0){
			$dbc->element_create("oper_counter", array(
				"oper_id" => $_POST['ROOT_ID'],
				"email" => 1,
				"date" => date("Y-m-d")));
		}
		else{
			$dbc->element_update('phones',$key,array(
				"comment" => addslashes($v)));
			$sql = "UPDATE oper_counter SET 
									email = email + 1
						WHERE id = ".$counter_id;
			$dbc->element_free_update($sql);
		}
		$dbc->element_create("oper_counter_log", array(
			"oper_id" => $_POST['ROOT_ID'],
			"count_type" => 3,
			"gn" => $f_email,
			"date" => date("Y-m-d H:i:s")));

		ini_set("soap.wsdl_cache_enabled", "0" );
		$client3 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
			array(
			'login' => 'ws',
			'password' => '123456',
			'trace' => true
			)
		);
		$params3['Hot']["ManagerCode"] = $_POST['LOGIN_1C'];
		$params3['Hot']["Operation"] = 'email';
		$params3['Hot']["Email"] = $f_email;
		$params3['Hot']["GosNomer"] = '';
		$result3 = $client3->PutHot($params3);
	}
	else{
		$out_row['result'] = 'Err2';
	}
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);
?>
