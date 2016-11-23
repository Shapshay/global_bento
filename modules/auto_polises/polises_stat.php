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

//$_POST['LOGIN_1C'] = '0815-2515-02';

if(isset($_POST['LOGIN_1C'])){
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["Code1C"] = $_POST['LOGIN_1C'];
	//$params["Code1C"] = '0815-2515-02';
	$params["Date"] = date("Y-m-d");
	$result = $client->GetMyPolicies($params); 
	$array = objectToArray($result);
	$u_arr = $array['return'];
	$polises_table = '<table id="stat_table_polises">
	<thead>
		<tr>
			<th>БСО</th>
			<th>Дата</th>
			<th>Клиент</th>
			<th>Статус</th>
			<th>Оплачен</th>
			<th>Проведен</th>
			<th>Распечатан</th>
			<th>Курьер</th>
			<th>Сумма</th>
		</tr>
	</thead>
	<tbody>';
	$out_row['result'] = 'OK';
	$i = 0;
	foreach($u_arr as $row2){
		//echo '<p>';
		//print_r($row2);
		if(isset($row2[$i]['BSO'])){
			foreach($row2 as $row){
				//echo '<p>';
				//print_r($row);
				$Oplachen = '-';
				$Prov = '-';
				$Printed = '-';
				if($row['Oplachen']){
					$Oplachen = '<img src="images/gal_check.png" width="30" />';
				}
				if($row['Prov']){
					$Prov = '<img src="images/gal_check.png" width="30" />';
				}
				if($row['Printed']){
					$Printed = '<img src="images/gal_check.png" width="30" />';
				}
				$polises_table.= '<tr>
						<td align="left">'.$row['BSO'].'</td>
						<td class="grey" align="left">'.date("H:i:s d-m-Y",strtotime($row['Date'])).'</td>
						<td class="grey" align="left">'.$row['Client'].'</td>
						<td class="grey" align="left">'.$row['Status'].'</td>
						<td class="grey" align="left">'.$Oplachen.'</td>
						<td class="grey" align="left">'.$Prov.'</td>
						<td class="grey" align="left">'.$Printed.'</td>
						<td class="grey" align="left">'.$row['Curier'].'</td>
						<td class="grey" align="left">'.$row['Summa'].'</td>
						</tr>';
			}
		}
		else{
			if(isset($row2['BSO'])){
				//print_r($row2);
				$row = $row2[$i];
				$Oplachen = '-';
				$Prov = '-';
				$Printed = '-';
				if($row['Oplachen']){
					$Oplachen = '<img src="images/gal_check.png" width="30" />';
				}
				if($row['Prov']){
					$Prov = '<img src="images/gal_check.png" width="30" />';
				}
				if($row['Printed']){
					$Printed = '<img src="images/gal_check.png" width="30" />';
				}
				$polises_table.= '<tr>
						<td align="left">'.$row['BSO'].'</td>
						<td class="grey" align="left">'.date("H:i:s d-m-Y",strtotime($row['Date'])).'</td>
						<td class="grey" align="left">'.$row['Client'].'</td>
						<td class="grey" align="left">'.$row['Status'].'</td>
						<td class="grey" align="left">'.$Oplachen.'</td>
						<td class="grey" align="left">'.$Prov.'</td>
						<td class="grey" align="left">'.$Printed.'</td>
						<td class="grey" align="left">'.$row['Curier'].'</td>
						<td class="grey" align="left">'.$row['Summa'].'</td>
						</tr>';
			}
		}

	}
	$polises_table.= '</tbody></table>';
	$out_row['html'] = $polises_table;
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>
