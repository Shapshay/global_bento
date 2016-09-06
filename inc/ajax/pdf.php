<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

######################################################################################################################
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

include("../mpdf60/mpdf.php");

$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10); /*задаем формат, отступы и.т.д.*/
//$mpdf->charset_in = 'cp1251'; /*не забываем про русский*/

//$stylesheet = file_get_contents('style.css'); /*подключаем css*/
//$mpdf->WriteHTML($stylesheet, 1);
//echo urldecode($_GET['html']);
$mpdf->list_indent_first_level = 0; 

switch($_GET['pdf_type']){
	case 'adres':
		ini_set("soap.wsdl_cache_enabled", "0" ); 
		$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		$params["PolicNumber"] = $_GET['policeNum'];
		$result = $client->PrintAddrList($params); 
		$array = objectToArray($result);
		$html = $array['return'];
		$html = str_replace('<TABLE>','<TABLE border=1 width=400  cellspacing=0 cellpadding=5>',$html);
	break;
	
	case 'evaq':
		ini_set("soap.wsdl_cache_enabled", "0" ); 
		$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		$params["PolicNumber"] = $_GET['policeNum'];
		$result = $client->PrintEvac($params); 
		$array = objectToArray($result);
		$html = $array['return'];
	break;
	
	case 'c_p':
		date_default_timezone_set ("Asia/Almaty");
		$row = $dbc->element_find('users',$_GET['c_id']);
		$pol_arr = preg_split("/,/", $_GET['pol_arr'], -1, PREG_SPLIT_NO_EMPTY);
		$pol_rows = '';
		foreach($pol_arr as $v){
			$row2 = $dbc->element_find('polises',$v);
			$pol_rows.= '<tr><td>'.$row2['bso_number'].'</td></tr>';
		}
		$html = '<h1 align="center">Расписка</h1>
<p>Я, '.$row['name'].', получил следующие полисы в доставку:<br>
<table border=1  cellspacing=0 cellpadding=10>
<tr><th><b>Номер полиса</b></th></tr>
'.$pol_rows.'
</table></p>
<p>Обязуюсь сдать денежные средства согласно указанных страховых премий</p>
<p>'.date("d-m-Y H:i").'</p> 
<p>'.$row['name'].'</p> 
<p>Подпись______________________</p>';
	break;
	
	case 'c_p_in':
		date_default_timezone_set ("Asia/Almaty");
		$row = $dbc->element_find('users',$_GET['c_id']);
		$pol_arr = preg_split("/,/", $_GET['pol_arr'], -1, PREG_SPLIT_NO_EMPTY);
		$pol_rows = '';
		$all_sum = 0;
		foreach($pol_arr as $v){
			$row2 = $dbc->element_find('polises',$v);
			$pol_rows.= '<tr>
		 			<td>'.$row2['bso_number'].'</td>
		 			<td>'.$row2['summa'].' тг</td>
		 		</tr>';
		 	$all_sum+= $row2['summa'];
		}
		$html = '<h1 align="center">Расписка</h1>
<p>Я, '.$row['name'].', сдал следующию сумму за полисы на инкасацию:<br>
<table border=1  cellspacing=0 cellpadding=5>
<thead>
<tr>
<td><b>Номер полиса</b></td>
<td><b>Сумма</b></td>
</tr>
</thead>
<tbody>
'.$pol_rows.'
</tbody>
<tfoot>
<tr>
<td><b>Итого:</b></td>
<td><b>'.$all_sum.' тг</b></td>
</tr>
</tfoot>
</table></p>
<p>'.date("d-m-Y H:i").'</p> 
<p>'.$row['name'].'</p> 
<p>Подпись______________________</p>';
	break;
	
	case 'c_p_clear':
		date_default_timezone_set ("Asia/Almaty");
		$row = $dbc->element_find('users',$_GET['c_id']);
		$pol_arr = preg_split("/,/", $_GET['pol_arr'], -1, PREG_SPLIT_NO_EMPTY);
		$pol_rows = '';
		foreach($pol_arr as $v){
			$row2 = $dbc->element_find('polises',$v);
			$pol_rows.= '<tr>
		 			<td>'.$row2['bso_number'].'</td>
		 			<td>&nbsp;</td>
		 		</tr>';
		}
		$html = '<h1 align="center">Расписка</h1>
<p>Я, '.$row['name'].', сдал следующие недоставленные полисы:<br>
<table border=1  cellspacing=0 cellpadding=5>
<thead>
<tr>
<td><b>Номер полиса</b></td>
<td width="400"><b>Причина</b></td>
</tr>
</thead>
<tbody>
'.$pol_rows.'
</tbody>
</table></p>
<p>'.date("d-m-Y H:i").'</p> 
<p>'.$row['name'].'</p> 
<p>Подпись______________________</p>';
	break;

	case 'c_p_in':
		date_default_timezone_set ("Asia/Almaty");
		$row = $dbc->element_find('users',$_GET['c_id']);
		$pol_arr = preg_split("/,/", $_GET['pol_arr'], -1, PREG_SPLIT_NO_EMPTY);
		$pol_rows = '';
		$all_sum = 0;
		foreach($pol_arr as $v){
			$row2 = $dbc->element_find('polises',$v);
			$pol_rows.= '<tr>
		 			<td>'.$row2['bso_number'].'</td>
		 			<td>'.$row2['summa'].' тг</td>
		 		</tr>';
			$all_sum+= $row2['summa'];
		}
		$html = '<h1 align="center">Расписка</h1>
<p>Я, '.$row['name'].', сдал следующию сумму за полисы на инкасацию:<br>
<table border=1  cellspacing=0 cellpadding=5>
<thead>
<tr>
<td><b>Номер полиса</b></td>
<td><b>Сумма</b></td>
</tr>
</thead>
<tbody>
'.$pol_rows.'
</tbody>
<tfoot>
<tr>
<td><b>Итого:</b></td>
<td><b>'.$all_sum.' тг</b></td>
</tr>
</tfoot>
</table></p>
<p>'.date("d-m-Y H:i").'</p> 
<p>'.$row['name'].'</p> 
<p>Подпись______________________</p>';
		break;
	
}


$mpdf->WriteHTML($html, 2); /*формируем pdf*/
//$mpdf->WriteHTML(urldecode($_GET['html']), 2); /*формируем pdf*/
$mpdf->Output('mpdf.pdf', 'I');
?>