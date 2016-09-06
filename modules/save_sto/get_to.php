<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
date_default_timezone_set ("Asia/Almaty");

function get_web_page( $url ){
	$uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
	
	$ch = curl_init( $url );
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_HEADER, 0);        
	curl_setopt($ch, CURLOPT_ENCODING, "");  
	curl_setopt($ch, CURLOPT_USERAGENT, $uagent); 
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
	curl_setopt($ch, CURLOPT_TIMEOUT, 120);       
	curl_setopt($ch, CURLOPT_MAXREDIRS, 10);     
	curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
	curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");
	
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




if(isset($_POST['gn'])){
	$url = 'http://81.176.228.119/capcha/get_to.php?gn='.$_POST['gn'];
	$result = get_web_page($url);
	$json = json_decode($result['content']);
	if($json->result=='OK'){
		$out_row['result'] = 'OK';
		$json_str = '<b>'.$json->gn.'</b><br>Модель: '.$json->model.'<br>Дата прохождения: '.date("d-m-Y",strtotime($json->start)).'<br>Дата окончания: '.date("d-m-Y",strtotime($json->end));
		$out_row['str'] = $json_str;
	}
	else{
		$out_row['result'] = 'Err';
	}
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
