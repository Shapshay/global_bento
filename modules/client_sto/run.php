<?php
# SETTINGS #############################################################################
$moduleName = "client_sto";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и подготовка клиента
if(!isset($_GET['item'])&&!isset($_SESSION['1C'])&&!isset($_SESSION['c_id'])){
	$dbc->element_create("calls_log",array(
		"oper_id" => ROOT_ID,
		"date_start" => 'NOW()'));
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["MenegerCode"] = LOGIN_1C;

	$result = $client->GetClient($params);
	$array = objectToArray($result);
	$c_arr = $array['return'];
	//print_r($c_arr);
	$c_id = getClientSTOID($c_arr['Code1C']);
	
	if($c_id==0){
        //echo "<br>INS=".$c_id;
		$dbc->element_create("sto",array(
			"code_1C" => $c_arr['Code1C'],
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
            "iin" => $c_arr['Iin'],
            "gn" => $c_arr['GosNomer'],
            "pn" => $c_arr['TechPassport'],
            "mark" => $c_arr['Mark'],
            "model" => $c_arr['Model'],
            "born" => $c_arr['GodVypusk'],
            "phone" => $c_arr['Telefon'],
			"phone2" => $c_arr['Telefon1'],
            "email" => $c_arr['Email'],
            "date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
            "comment" => addslashes($c_arr['Comment'])));
        //echo "<br>SQL=".$dbc->outsql;
		$c_id = $dbc->ins_id;
	}
	else{
        //echo "<br>UPD=".$c_id;
		$dbc->element_update('sto',$c_id,array(
            "code_1C" => $c_arr['Code1C'],
            "oper_id" => ROOT_ID,
            "name" => $c_arr['Name'],
            "iin" => $c_arr['Iin'],
            "gn" => $c_arr['GosNomer'],
            "pn" => $c_arr['TechPassport'],
            "mark" => $c_arr['Mark'],
            "model" => $c_arr['Model'],
            "born" => $c_arr['GodVypusk'],
            "phone" => $c_arr['Telefon'],
			"phone2" => $c_arr['Telefon1'],
            "email" => $c_arr['Email'],
            "date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
            "comment" => addslashes($c_arr['Comment'])));
	}
}
else{
	if(isset($_GET['item'])&&!isset($_POST['edt_item'])){
		$c_id = $_GET['item'];
		$_SESSION['1C'] = $c_id;
		$dbc->element_create("calls_log",array(
			"oper_id" => ROOT_ID,
			"date_start" => 'NOW()'));
	}
	else{
		if(isset($_SESSION['c_id'])){
			$c_id = $_SESSION['c_id'];
		}
	}
}

if(isset($c_id)){
	$_SESSION['c_id'] = $c_id;
	$tpl->assign("U_ID", $c_id);
}



if(isset($c_id)){
	//echo "<br>".$c_id;
    $row = $dbc->element_find('sto',$c_id);
	$tpl->assign("INFO_U_NAME", $row['name']);
	$tpl->assign("INFO_U_IIN", $row['iin']);
	$tpl->assign("INFO_U_EMAIL", $row['email']);
	$tpl->assign("INFO_U_GN", $row['gn']);
	$tpl->assign("INFO_U_PN", $row['pn']);
	$tpl->assign("INFO_U_MARK", $row['mark']);
	$tpl->assign("INFO_U_MODEL", $row['model']);
	$tpl->assign("INFO_U_YEAR", $row['born']);
	$tpl->assign("INFO_U_COMMENT", nl2br($row['comment']));
	if($row['date_to_end']=='0001-01-01'){
		$tpl->assign("INFO_U_DATE_PREV_TO", '-');
	}
	else{
		$tpl->assign("INFO_U_DATE_PREV_TO", date("d-m-Y",strtotime($row['date_to_end'])));
	}

	
	
	// phones
	$phones = '';
	$c =1;

    $phones.='<br>
            <img src="images/bell1.png" class="img_call" align="absmiddle" style="margin:3px; cursor:pointer;">'.$row['phone'].'
            <input type="hidden" id="phone_call'.$c.'" value="'.$row['phone'].'" />';
    $c++;
    if($row['phone2']!=''){
        $phones.='<br>
            <img src="images/bell1.png" class="img_call" align="absmiddle" style="margin:3px; cursor:pointer;">'.$row['phone2'].'
            <input type="hidden" id="phone_call'.$c.'" value="'.$row['phone2'].'" />';
    }


	$tpl->assign("INFO_U_PHONE", $phones);
	
	
	
}
else{
	$tpl->assign("INFO_U_NAME", '');
	$tpl->assign("INFO_U_IIN", '');
	$tpl->assign("INFO_U_EMAIL", '');
	$tpl->assign("INFO_U_DATE_PREV_TO", '');
	$tpl->assign("INFO_U_GN", '');
	$tpl->assign("INFO_U_PN", '');
	$tpl->assign("INFO_U_MARK", '');
	$tpl->assign("INFO_U_MODEL", '');
	$tpl->assign("INFO_U_YEAR", '');
	$tpl->assign("INFO_U_POLIS", '');
	$tpl->assign("INFO_U_PHONE", '');
	
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>