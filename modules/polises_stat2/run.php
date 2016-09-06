<?php
	# SETTINGS #############################################################################
	ini_set("display_errors", "0");
	$moduleName = "polises_stat2";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "grid" => $prefix . "grid.tpl",
	));
	
	# MAIN #################################################################################
	$tpl->parse("META_LINK", ".".$moduleName."grid");
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	//$params["Code1C"] = LOGIN_1C;
	if(LOGIN_1C=='0515-0347-4'){
		$params["Code1C"] = '0715-0698-02';
	}
	else{
		$params["Code1C"] = LOGIN_1C;
	}
	$params["Date"] = date("Y-m-d","-1 month");
	$result = $client->GetMyPolicies($params); 
	$array = objectToArray($result);
	$u_arr = $array['return'];
	
	$polises_table = '<table id="stat_table" class="display">
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
	//echo '<p>';
	//print_r($u_arr);
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
	
	//echo $polises_table;
	
	$tpl->assign("STAT_POLISES", $polises_table);
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	
?>