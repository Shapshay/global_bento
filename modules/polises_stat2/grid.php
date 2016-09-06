<?php
//error_reporting (E_ALL);
ini_set("display_errors", "0");
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
	

//$examp = $_REQUEST["q"]; //query number

//echo $_REQUEST["_search"]."R*<br>";

$_REQUEST['page'] = 1; // get the requested page
$_REQUEST['rows'] = 1000; // get how many rows we want to have into the grid
$_REQUEST['sidx'] = 1; // get index row - i.e. user click to sort
//$_REQUEST['sord']; // get the direction
$sidx =1;

$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
//if($totalrows) {
	$limit = 1000;
//}











ini_set("soap.wsdl_cache_enabled", "0" ); 
$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
	array( 
	'login' => 'ws', 
	'password' => '123456', 
	'trace' => true
	) 
);
//$params["Code1C"] = LOGIN_1C;
$params["Code1C"] = $_GET['Code1C'];
$params["Date"] = date("Y-m-d","-1 month");
$result = $client->GetMyPolicies($params); 
$array = objectToArray($result);
$u_arr = $array['return'];


$i = 0;
foreach($u_arr as $row2){
	if(isset($row2[$i]['PolicInfo'])){
		foreach($row2 as $row){
			$Oplachen = '0';
			$Prov = '0';
			$Printed = '0';
			if($row['Oplachen']){
				$Oplachen = '1';
			}
			if($row['Prov']){
				$Prov = '1';
			}
			if($row['Printed']){
				$Printed = '1';
			}
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row['BSO'],date("d-m-Y",strtotime($row['Date'])),$row['Client'],$row['Status'],$Oplachen,$Prov,$Printed,$row['Curier'],$row['Summa']);
			$i++;
		}
	}
	else{
		if(isset($row2[$i])){
			$row = $row2[$i];
			$Oplachen = '0';
			$Prov = '0';
			$Printed = '0';
			if($row['Oplachen']){
				$Oplachen = '1';
			}
			if($row['Prov']){
				$Prov = '1';
			}
			if($row['Printed']){
				$Printed = '1';
			}
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row['BSO'],date("d-m-Y",strtotime($row['Date'])),$row['Client'],$row['Status'],$Oplachen,$Prov,$Printed,$row['Curier'],$row['Summa']);
			$i++;
		}
	}
	
}
















$count = $i;

//echo '<p>'.$count.'<p>';
			
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
//echo '<p>'.$total_pages.'<p>';
$page = 1;
if ($page > $total_pages) $page=$total_pages;
if ($limit<0) $limit = 0;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if ($start<0) $start = 0;
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;

$i = 0;
foreach($u_arr as $row2){
	if(isset($row2[$i]['BSO'])){
		foreach($row2 as $row){
			$Oplachen = '0';
			$Prov = '0';
			$Printed = '0';
			if($row['Oplachen']){
				$Oplachen = '1';
			}
			if($row['Prov']){
				$Prov = '1';
			}
			if($row['Printed']){
				$Printed = '1';
			}
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row['BSO'],date("d-m-Y",strtotime($row['Date'])),$row['Client'],$row['Status'],$Oplachen,$Prov,$Printed,$row['Curier'],$row['Summa']);
			$i++;
		}
	}
	else{
		if(isset($row2['BSO'])){
			$row = $row2[$i];
			$Oplachen = '0';
			$Prov = '0';
			$Printed = '0';
			if($row['Oplachen']){
				$Oplachen = '1';
			}
			if($row['Prov']){
				$Prov = '1';
			}
			if($row['Printed']){
				$Printed = '1';
			}
			$responce->rows[$i]['id']=$i;
			$responce->rows[$i]['cell']=array($row['BSO'],date("-d-m-Y",strtotime($row['Date'])),$row['Client'],$row['Status'],$Oplachen,$Prov,$Printed,$row['Curier'],$row['Summa']);
			$i++;
		}
	}
	
}


echo json_encode($responce);
//echo '<p>'.$count;
//print_r($responce);

?>