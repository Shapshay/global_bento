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

// находит оператора по внутренему номеру
function getUserIDfromVnutr($vnut) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"id",
			"where"=>"phone = '".$vnut."'",
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

// находит последнюю запись в логе оператора
function getOperCurentMaxLog($oper_id){
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"calls_log",
			"select"=>"MAX(id) AS num",
			"where"=>"oper_id = ".$oper_id,
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['num'];
	}
	else{
		return 0;
	}
}
 
 // возвращает текущий рейтинг клиента
 function getUserRating($phone){
	 global $dbc;
	 $rows = $dbc->dbselect(array(
			 "table"=>"phones",
			 "select"=>"clients.rating AS num",
			 "joins"=>"LEFT OUTER JOIN clients ON phones.client_id = clients.id",
			 "where"=>"phone = '".$phone."'",
			 "order"=>"users.rating",
			 "order_type"=>"DESC",
			 "limit"=>"1"
		 )
	 );
	 $numRows = $dbc->count;
	 if ($numRows > 0) {
		 $row = $rows[0];
		 return $row['num'];
	 }
	 else{
		 return 0;
	 }
}

######################################################################################################################

if(isset($_GET['file_name'])){
	$phone = preg_split("/_/", $_GET['file_name'], -1, PREG_SPLIT_NO_EMPTY);
	//$audio_arr[]['call_date'] = $element->find("td", 2)->plaintext;
	$i = 0;
	$audio_arr[$i]['link'] = $_GET['file_name'];
	$audio_arr[$i]['call_date'] = date("Y-m-d H:i:s",strtotime($phone[0]));
	$audio_arr[$i]['size'] = getRemoteFileSize('http://192.168.0.200/freeswitch/'.$audio_arr[$i]['link']);
	$audio_arr[$i]['phone1'] = $phone[1];
	$audio_arr[$i]['phone2'] = str_replace('.wav','',$phone[2]);
	
	
	
	if(setVnutrCallTimeID($audio_arr[$i]['phone2'], $audio_arr[$i]['link'])==0){
		$oper_id = getUserIDfromVnutr($audio_arr[$i]['phone2']);
		$log = getOperCurentMaxLog($oper_id);
		$rating = getUserRating($audio_arr[$i]['phone1']);
		$dbc->element_create("oper_calls", array(
			"call_date" => $audio_arr[$i]['call_date'],
			"size" => FBytes($audio_arr[$i]['size']),
			"phone1" => $audio_arr[$i]['phone1'],
			"phone2" => $audio_arr[$i]['phone2'],
			"link" => $audio_arr[$i]['link'],
			"rating" => $rating,
			"get" => 1,
			"calls_log_id" => $log));

		$rows3 = $dbc->dbselect(array(
				"table"=>"oper_calls",
				"select"=>"id, link",
				"where"=>"phone2 = '".$audio_arr[$i]['phone2']."'",
				"order"=>"call_date",
				"order_type"=>"DESC",
				"limit"=>2
			)
		);
		$numRows = $dbc->count;
		if ($numRows > 0) {
			foreach($rows as $row){
				$file_size = FBytes(getRemoteFileSize('http://192.168.0.200/freeswitch/'.$row3['link']));
				$dbc->element_update('oper_calls',$row3['id'],array(
					"size" => $file_size));
			}
		}
	}
}


?>
