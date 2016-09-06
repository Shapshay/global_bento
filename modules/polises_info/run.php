<?php
	# SETTINGS #############################################################################
	$moduleName = "polises_info";
	$prefix = "./modules/".$moduleName."/";
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "main" => $prefix . "main.tpl",
			$moduleName . "p_view" => $prefix . "p_view.tpl",
	));
	# MAIN #################################################################################
	$tpl->assign("META_LINK", '');
	
	if(isset($_POST['polis_num'])){
		ini_set("soap.wsdl_cache_enabled", "0" ); 
		$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
			array( 
			'login' => 'ws', 
			'password' => '123456', 
			'trace' => true
			) 
		);
		//$params["Code1C"] = LOGIN_1C;
		$params["PolicNumber"] = $_POST['polis_num'];
		$result = $client->GetPolicInfo($params); 
		$array = objectToArray($result);
		$u_arr = $array['return']['PolicInfo'];
		$tpl->assign("SEARCH_POLIS_NUM",  $_POST['polis_num']);
		
		if($u_arr['BSO']!=''){
			$Oplachen = '<img src="images/no.png" width="30" />';
			$Prov = '<img src="images/no.png" width="30" />';
			$Printed = '<img src="images/no.png" width="30" />';
			if($u_arr['Oplachen']){
				$Oplachen = '<img src="images/yes.png" width="30" />';
			}
			if($u_arr['Prov']){
				$Prov = '<img src="images/yes.png" width="30" />';
			}
			if($u_arr['Printed']){
				$Printed = '<img src="images/yes.png" width="30" />';
			}
			
			
			$tpl->assign("S_VIEW_P_BSO", $u_arr['BSO']);
			$tpl->assign("S_VIEW_P_CLIENT", $u_arr['Client']);
			$tpl->assign("S_VIEW_P_OPER", $u_arr['Manager']);
			$tpl->assign("S_VIEW_P_DATE", date("d-m-Y",strtotime($u_arr['Date'])));
			$tpl->assign("S_VIEW_P_STATUS", $u_arr['Status']);
			$tpl->assign("S_VIEW_P_OPLAT", $Oplachen);
			$tpl->assign("S_VIEW_P_PROV", $Prov);
			$tpl->assign("S_VIEW_P_PRINT", $Printed);
			$tpl->assign("S_VIEW_P_CURIER", $u_arr['Curier']);
			$tpl->assign("S_VIEW_P_SUM", $u_arr['Summa']);
			
			$tpl->parse("SEARCH_SHOW", ".".$moduleName."p_view");
		}
		else{
			$tpl->assign("SEARCH_SHOW", '<font color="#f00"><strong>Полис с данным номером ненайден !</strong></font>');
		}
	}
	else{
		$tpl->assign("SEARCH_POLIS_NUM", '');
		$tpl->assign("SEARCH_SHOW", '');
	}
	
	
	
	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	
?>