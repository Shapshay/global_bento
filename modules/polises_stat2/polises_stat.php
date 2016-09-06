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
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["Code1C"] = $_POST['LOGIN_1C'];
	$params["Date"] = date("Y-m-d");
	$result = $client->GetMyPolicies($params); 
	$array = objectToArray($result);
	$u_arr = $array['return'];
	$polises_table = '<table class="PoLisesStatTable">
	<thead>
		<tr>
			<th>БСО</th>
			<th class="grey">Дата</th>
			<th class="grey">Клиент</th>
			<th class="grey">Статус</th>
			<th class="grey">Оплачен</th>
			<th class="grey">Проведен</th>
			<th class="grey">Распечатан</th>
			<th class="grey">Курьер</th>
			<th class="grey">Сумма</th>
		</tr>
	</thead>
	<tbody>';
	$out_row['result'] = 'OK';
	$Oplachen = '-';
	$Prov = '-';
	$Printed = '-';
	foreach($u_arr as $row){
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
					<td align="left">'.date("H:i:s d-m-Y",strtotime($row['Date'])).'</td>
					<td class="grey" align="left">'.$row['Client'].'</td>
					<td class="grey" align="left">'.$row['Status'].'</td>
					<td class="grey" align="left">'.$Oplachen.'</td>
					<td class="grey" align="left">'.$Prov.'</td>
					<td class="grey" align="left">'.$Printed.'</td>
					<td class="grey" align="left">'.$row['Curier'].'</td>
					<td class="grey" align="left">'.$row['Summa'].'</td>
					</tr>';
	}
	$polises_table.= '</tbody></table>';
	$out_row['polises'] = $polises_table;
}
else{
	$out_row['result'] = 'Err';
}


header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
