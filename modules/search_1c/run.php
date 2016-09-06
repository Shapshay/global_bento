<?php
# SETTINGS #############################################################################
$moduleName = "search_1c";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "result_row" => $prefix . "result_row.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "result" => $prefix . "result.tpl",
));

# MAIN ##################################################################################
$search = false;
if(isset($_POST['iin'])||isset($_POST['rnn'])||isset($_POST['telnumber'])||isset($_POST['PolicNumber'])){
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', //пароль 
		'trace' => true
		) 
	);
	$params["iin"] = $_POST['iin'];
	$params["rnn"] = $_POST['rnn'];
	$params["telnumber"] = $_POST['telnumber'];
	$params["ClientCode1C"] = '';
	$params["PolicNumber"] = strtoupper($_POST['polis_num']);
	$result = $client->SearchClient($params); 
	$array = objectToArray($result);
	$search = true;

	$dbc->element_create("search_client", array(
		"oper_id" => ROOT_ID,
		"query" => "IIN: ".$_POST['iin']." | RNN: ".$_POST['rnn']." | PHONE: ".$_POST['telnumber'],
		"date" => 'NOW()'
	));
}
$tpl->parse("META_LINK", ".".$moduleName."html");
if(!$search){
	$tpl->assign("SEARCH_IIN", '');
	$tpl->assign("SEARCH_RNN", '');
	$tpl->assign("SEARCH_PHONE", '');
	$tpl->assign("SEARCH_POLIS_NUM", '');
	$tpl->assign("SEARCH_SHOW", '');
}
else{
	$tpl->assign("SEARCH_IIN", $_POST['iin']);
	$tpl->assign("SEARCH_RNN", $_POST['rnn']);
	$tpl->assign("SEARCH_PHONE", $_POST['telnumber']);
	$tpl->assign("SEARCH_POLIS_NUM", $_POST['polis_num']);
	$i = 0;
	foreach($array['return'] as $clients){
		if(isset($clients[0]['Code1C'])){
			foreach($clients as $client){
				$c_arr = $client;
				$c_id = getClientID($c_arr['Code1C']);
				
				$oper_id = getOperCode1CId($c_arr['ManagerCode']);
				
				ini_set("soap.wsdl_cache_enabled", "0" ); 
				$client3 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
					array( 
					'login' => 'ws', 
					'password' => '123456', 
					'trace' => true
					) 
				);
				$params3["ManagerCode"] = $c_arr['ManagerCode'];
				$result3 = $client3->GetLimit($params3); 
				$array3 = objectToArray($result3);

				$dbc->element_update('users',ROOT_ID,array(
					"l_limit" => $array3['return']));

				if(isset($c_arr['RNN'])){
					$rnn = $c_arr['RNN'];
				}
				else{
					$rnn = '';
				}
				if($c_id==0){
					$dbc->element_create("clients", array(
						"oper_id" => $oper_id,
						"name" => $c_arr['Name'],
						"code_1C" => $c_arr['Code1C'],
						"iin" => $c_arr['IIN'],
						"rnn" => $rnn,
						"email" => $c_arr['Email'],
						"comment" => $c_arr['Comment'],
						"date_tochnaya" => $c_arr['ActualDate'],
						"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
						"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
						"res_prev_call" => $c_arr['Result'],
						"source" => $c_arr['Sourse'],
						"rating" => $c_arr['Rating'],
						"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))
						));
					$c_id = $dbc->ins_id;
				}
				else{
					$dbc->element_update('clients',$c_id,array(
						"oper_id" => $oper_id,
						"name" => $c_arr['Name'],
						"code_1C" => $c_arr['Code1C'],
						"iin" => $c_arr['IIN'],
						"rnn" => $rnn,
						"email" => $c_arr['Email'],
						"comment" => $c_arr['Comment'],
						"date_tochnaya" => $c_arr['ActualDate'],
						"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
						"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
						"res_prev_call" => $c_arr['Result'],
						"source" => $c_arr['Sourse'],
						"rating" => $c_arr['Rating'],
						"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))
					));
				}
				$u_tel_arr = array();
				foreach($c_arr['Telnumbers'] as $numbers){
					if(is_array($numbers)){
						foreach($numbers as $number){
							$u_tel_arr[] = $number;
							if(getClientPhoneID($c_id, $number)==0){
								$dbc->element_create("phones",array(
									"client_id" => $c_id,
									"phone" => $number));
							}
						}
					}
					else{
						$u_tel_arr[] = $numbers;
						if(getClientPhoneID($c_id, $numbers)==0){
							$dbc->element_create("phones",array(
								"client_id" => $c_id,
								"phone" => $numbers));
						}
					}
				}

				$phone = '';
				foreach($c_arr['Telnumbers'] as $numbers){
					if(is_array($numbers)){
						foreach($numbers as $number){
							$u_tel_arr[] = $number;
							if(getClientPhoneID($c_id, $number)==0){
								$phone.= $number;
							}
						}
					}
					else{
						$u_tel_arr[] = $numbers;
						if(getClientPhoneID($c_id, $numbers)==0){
							$phone.= $numbers;
						}
					}
				}
				$tpl->assign("RESULT_NAME", $client['Name']);
				$tpl->assign("RESULT_IIN", $client['IIN']);
				$tpl->assign("RESULT_RNN", $client['RNN']);
				$tpl->assign("RESULT_OPER", $client['ManagerName']);
				$tpl->assign("RESULT_GOSNOMER", $client['Gosnomer']);
				$tpl->assign("RESULT_PHONE", $phone);
				if($oper_id==ROOT_ID||in_array(5,$USER_ROLE)||ROOT_ID==1){
					$tpl->assign("RESULT_URL", "/".getItemCHPU(1, 'pages')."/?item=".$c_id);
				}
				else{
					$tpl->assign("RESULT_URL", '#');
				}
				$tpl->parse("SEARCH_RESULTS", ".".$moduleName."result_row");
				$i++;
			}
		}
		else{

			$c_arr = $clients;
			$c_id = getClientID($c_arr['Code1C']);

			$oper_id = getOperCode1CId($c_arr['ManagerCode']);

			ini_set("soap.wsdl_cache_enabled", "0" );
			$client3 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
				array(
				'login' => 'ws',
				'password' => '123456',
				'trace' => true
				)
			);
			$params3["ManagerCode"] = $c_arr['ManagerCode'];
			$result3 = $client3->GetLimit($params3);
			$array3 = objectToArray($result3);

			$dbc->element_update('users',ROOT_ID,array(
				"l_limit" => $array3['return']));

			if(isset($c_arr['RNN'])){
				$rnn = $c_arr['RNN'];
			}
			else{
				$rnn = '';
			}

			if($c_id==0){
				$dbc->element_create("clients", array(
					"oper_id" => $oper_id,
					"name" => $c_arr['Name'],
					"code_1C" => $c_arr['Code1C'],
					"iin" => $c_arr['IIN'],
					"rnn" => $rnn,
					"email" => $c_arr['Email'],
					"comment" => $c_arr['Comment'],
					"date_tochnaya" => $c_arr['ActualDate'],
					"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
					"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
					"res_prev_call" => $c_arr['Result'],
					"source" => $c_arr['Sourse'],
					"rating" => $c_arr['Rating'],
					"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))
				));
				$c_id = $dbc->ins_id;
			}
			else{
				$dbc->element_update('clients',$c_id,array(
					"oper_id" => $oper_id,
					"name" => $c_arr['Name'],
					"code_1C" => $c_arr['Code1C'],
					"iin" => $c_arr['IIN'],
					"rnn" => $rnn,
					"email" => $c_arr['Email'],
					"comment" => $c_arr['Comment'],
					"date_tochnaya" => $c_arr['ActualDate'],
					"date_lost" => date("Y-m-d H:i",strtotime($c_arr['DateLastPolicy'])),
					"date_prev_call" => date("Y-m-d H:i",strtotime($c_arr['DateContact'])),
					"res_prev_call" => $c_arr['Result'],
					"source" => $c_arr['Sourse'],
					"rating" => $c_arr['Rating'],
					"date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy']))
				));
			}
			$u_tel_arr = array();
			foreach($c_arr['Telnumbers'] as $numbers){
				if(is_array($numbers)){
					foreach($numbers as $number){
						$u_tel_arr[] = $number;
						if(getClientPhoneID($c_id, $number)==0){
							$dbc->element_create("phones",array(
								"client_id" => $c_id,
								"phone" => $number));
						}
					}
				}
				else{
					$u_tel_arr[] = $numbers;
					if(getClientPhoneID($c_id, $numbers)==0){
						$dbc->element_create("phones",array(
							"client_id" => $c_id,
							"phone" => $numbers));
					}
				}
			}


			$phone = '';
			foreach($c_arr['Telnumbers'] as $numbers){
				if(is_array($numbers)){
					foreach($numbers as $number){
						$u_tel_arr[] = $number;
						if(getClientPhoneID($c_id, $number)==0){
							$phone.= $number;
						}
					}
				}
				else{
					$u_tel_arr[] = $numbers;
					if(getClientPhoneID($c_id, $numbers)==0){
						$phone.= $numbers;
					}
				}
			}
			$tpl->assign("RESULT_NAME", $clients['Name']);
			$tpl->assign("RESULT_IIN", $clients['IIN']);
			$tpl->assign("RESULT_RNN", $clients['RNN']);
			$tpl->assign("RESULT_OPER", $clients['ManagerName']);
			$tpl->assign("RESULT_PHONE", $phone);
			$tpl->assign("RESULT_GOSNOMER", $clients['Gosnomer']);
			$oper_id = getOperCode1CId($c_arr['ManagerCode']);
			//echo $c_arr['ManagerCode']."*".$oper_id."==".ROOT_ID;
			if($oper_id==ROOT_ID||in_array(5,$USER_ROLE)||ROOT_ID==1){
				$url = "/".getItemCHPU(1, 'pages')."/?item=".$c_id;
				$tpl->assign("RESULT_URL", $url);
			}
			else{
				$tpl->assign("RESULT_URL", '#');
			}
			$tpl->parse("SEARCH_RESULTS", ".".$moduleName."result_row");
			$i++;
		}
	}
	$tpl->assign("TOTAL_FOUND", $i);
	
	$tpl->parse("SEARCH_SHOW", ".".$moduleName."result");
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>