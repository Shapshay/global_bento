<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;
// получение страницы через POST
function post_content ($url,$postdata) {
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_ENCODING, "");
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	curl_setopt($ch, CURLOPT_COOKIEJAR, "../../inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"../../coo.txt");

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

if(isset($_POST['id'])){
	$u_set=$dbc->element_find('users',$_POST['id']);
	$u_id = $u_set['login'];
	$dbc->element_delete('users',$_POST['id']);
	$sql = "DELETE FROM r_user_role WHERE user_id = ".$_POST['id'];
	$dbc->db_free_del($sql);
	// удаление из InfoBank
	$url = 'http://192.168.1.227/inc/api_user_del.php';
	$postdata = "u_id=".$u_id;
	$result = post_content( $url, $postdata );
	$out_row['result'] = 'OK';
}
else{
	$out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;
 ?>
