<?php
$moduleName = "check_fine";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "form" => $prefix . "form.tpl",
		$moduleName . "form2" => $prefix . "form2.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
	));

# MAIN ##################################################################################

if(isset($_POST['f_name'])){
	// отправка первой формы
	$tpl->assign("FINE_NAME", SuperSaveStr($_POST['f_name']));
	$tpl->assign("FINE_PHONE", SuperSaveStr($_POST['f_phone']));
	$tpl->assign("FINE_GN", SuperSaveStr($_POST['f_gn']));
	$tpl->assign("FINE_CKECK_DISPLAY", 'display: none;');
	$tpl->assign("FINE_CKECK_CONTENT", '');
	
	$city_sel = '';
	$rows3 = $dbc->dbselect(array(
		"table"=>"city",
		"select"=>"*"));
	foreach($rows3 as $row3){
		$city_sel.= '<option value="'.$row3['id'].'">'.$row3['title'].'</option>';
	}
	$tpl->assign("FINE_CITY_SEL", $city_sel);
	
	$tpl->parse("FINE_FORM", ".".$moduleName."form2");
}
else{
	if(isset($_POST['f_email'])){
		// отправка второй формы и вывод результата
		$f_name = SuperSaveStr($_POST['f_name2']);
		$f_phone = preg_replace('#^.{1}#i', '8', SuperSaveInt($_POST['f_phone2']));
		$f_gn = strtoupper(SuperSaveStr($_POST['f_gn2']));
		$f_pn = strtoupper(SuperSaveStr($_POST['f_pn']));
		$f_email = SuperSaveStr($_POST['f_email']);
		$f_city = getItemTitle('city', $_POST['city']);
		if($f_email==''){
			$f_email = 'Null';
		}
		
		
		//$fine = str_replace("h1","p",sendSoapFull($f_name, $f_phone, $f_gn, $f_pn, $f_email, $f_city));
		$fine = sendSoap($f_name, $f_phone, $f_gn, $f_pn, $f_email);
		//echo $f_name.'-'.$f_phone.'-'.$f_gn.'-'.$f_pn.'-'.$f_email;
		//echo '<p>'.$fine;
		
		if(setDateCounter(ROOT_ID, 2, $f_gn)){
			$counter_id = setOperDateCounter(ROOT_ID);
			if($counter_id==0){
				$dbc->element_create("oper_counter",array(
					"oper_id" => ROOT_ID,
					"shtraf" => 1,
					"date" => date("Y-m-d")));
			}
			else{
				$sql = "UPDATE oper_counter 
				SET shtraf = shtraf+1
				WHERE id = ".$counter_id;
				$dbc->element_free_update($sql);
			}
			$dbc->element_create("oper_counter_log",array(
				"oper_id" => ROOT_ID,
				"count_type" => 2,
				"gn" => $f_gn,
				"date" => date("Y-m-d H:i")));

			ini_set("soap.wsdl_cache_enabled", "0" ); 
			$client3 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
				array( 
				'login' => 'ws', 
				'password' => '123456', 
				'trace' => true
				) 
			);
			$params3['Hot']["ManagerCode"] = LOGIN_1C;
			$params3['Hot']["Operation"] = 'shtraf';
			$params3['Hot']["Email"] = '';
			$params3['Hot']["GosNomer"] = $f_gn;
			$result3 = $client3->PutHot($params3); 
		}
		
		
		
		
		$tpl->assign("FINE_CKECK_DISPLAY", '');
		$tpl->assign("FINE_CKECK_CONTENT", $fine);
	}
	else{
		// стандартное отображение
		$tpl->assign("FINE_CKECK_DISPLAY", 'display: none;');
		$tpl->assign("FINE_CKECK_CONTENT", '');
		$tpl->assign("AJAX_SCRIPT", '');
	}
	$tpl->parse("FINE_FORM", ".".$moduleName."form");
}

$tpl->parse("META_LINK", ".".$moduleName."html");
$tpl->parse(strtoupper($moduleName), $moduleName);
$tpl->clear("FINE_FORM");

?>