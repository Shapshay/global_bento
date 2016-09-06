<?php
# SETTINGS #############################################################################
$moduleName = "client_post";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и подготовка клиента
if(!isset($_GET['item'])&&!isset($_SESSION['1C'])&&!isset($_SESSION['c_id'])){
	//echo "Y<p>";
    $dbc->element_create("calls_log",array(
		"oper_id" => ROOT_ID,
		"date_start" => 'NOW()'));
	
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
		array( 
		'login' => 'ws', 
		'password' => '123456', 
		'trace' => true
		) 
	);
	$params["ManagerCode"] = LOGIN_1C;
	/*if(in_array(9,$USER_ROLE)||ROOT_ID==1){
		$params["test"] = true;
	}
	else{
		$params["test"] = false;
	}*/
	$result = $client->GetClientPost($params);
	$array = objectToArray($result);
	$c_arr = $array['return'];
    //print_r($c_arr);
	
	$c_id = getClientID($c_arr['Code1C']);
	
	if($c_id==0){
        //echo "<p>Y1";
		$dbc->element_create("clients",array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['Name']),
			"code_1C" => $c_arr['Code1C'],
			"iin" => $c_arr['IIN'],
			"comment" => addslashes($c_arr['Comment'])));

		$c_id = $dbc->ins_id;

        $dbc->element_create("post_control",array(
            "oper_id" => ROOT_ID,
            "c_id" => $c_id,
            "name" => addslashes($c_arr['Name']),
            "code_1C" => $c_arr['Code1C'],
            "iin" => $c_arr['IIN'],
            "bso" => $c_arr['Dogovor'],
            "obj" => addslashes($c_arr['Object']),
            "date_oform" => date("Y-m-d H:i",strtotime($c_arr['DatePolicy'])),
            "date_start" => date("Y-m-d H:i",strtotime($c_arr['DateBeginPolicy'])),
            "date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy'])),
            "comment" => addslashes($c_arr['Comment'])));
        //echo "<p>".$dbc->outsql;
        $post_id = $dbc->ins_id;
	}
	else{
		$dbc->element_update('clients',$c_id,array(
			"oper_id" => ROOT_ID,
			"name" => addslashes($c_arr['Name']),
			"fio" => addslashes($c_arr['Name']),
            "iin" => $c_arr['IIN'],
			"comment" => addslashes($c_arr['Comment'])));

        $post_id = getPostClientID($c_arr['Code1C']);
        $dbc->element_update('post_control',$post_id,array(
            "oper_id" => ROOT_ID,
            "c_id" => $c_id,
            "name" => addslashes($c_arr['Name']),
            "code_1C" => $c_arr['Code1C'],
            "iin" => $c_arr['IIN'],
            "bso" => $c_arr['Dogovor'],
            "obj" => addslashes($c_arr['Object']),
            "date_oform" => date("Y-m-d H:i",strtotime($c_arr['DatePolicy'])),
            "date_start" => date("Y-m-d H:i",strtotime($c_arr['DateBeginPolicy'])),
            "date_end" => date("Y-m-d H:i",strtotime($c_arr['DateEndPolicy'])),
            "comment" => addslashes($c_arr['Comment'])));
	}
	$number = $c_arr['Telnumber'];
    if(getClientPhoneID($c_id, $number)==0){
        $dbc->element_create("phones", array(
            "client_id" => $c_id,
            "phone" => $number));
    }
	$_SESSION['post_id'] = $post_id;
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

if(isset($post_id)){
	$_SESSION['post_id'] = $post_id;
	$tpl->assign("POST_ID", $post_id);
}
else{
    $post_id = $_SESSION['post_id'];
	$tpl->assign("POST_ID", $post_id);
}

if(isset($c_id)){
	$_SESSION['c_id'] = $c_id;
	$tpl->assign("U_ID", $c_id);
}



if(isset($c_id)){
	$rows = $dbc->dbselect(array(
			"table"=>"clients, post_control",
			"select"=>"clients.name as name,
				clients.iin as iin,
				clients.email as email,
				clients.comment as comment,
				post_control.date_oform as date_oform,
				post_control.date_start as date_start,
				post_control.date_end as date_end,
				post_control.obj as obj",
			"where"=>"clients.id = ".$c_id." AND  post_control.id = ".$post_id,
			"limit"=>1
		)
	);
	$row = $rows[0];
	
	$tpl->assign("INFO_U_NAME", $row['name']);
	$tpl->assign("INFO_U_IIN", $row['iin']);
	$tpl->assign("INFO_U_OBJ", $row['obj']);
	$tpl->assign("INFO_U_EMAIL", $row['email']);
    $tpl->assign("INFO_U_DATE_OFORM", date("d-m-Y",strtotime($row['date_oform'])));
    $tpl->assign("INFO_U_DATE_START", date("d-m-Y",strtotime($row['date_start'])));
    $tpl->assign("INFO_U_DATE_END", date("d-m-Y",strtotime($row['date_end'])));

    $tpl->assign("INFO_U_COMMENT", nl2br($row['comment']));

	// phones
	$rows = $dbc->dbselect(array(
		"table"=>"phones",
		"select"=>"*",
		"where"=>"client_id=".$c_id));
	$phones = '';
	$c =1;
	foreach($rows as $row){
		$phones.='<br>
				<img src="images/bell1.png" class="img_call" align="absmiddle" style="margin:3px; cursor:pointer;">'.$row['phone'].' ('.$row['comment'].')
				<input type="hidden" id="phone_call'.$c.'" value="'.$row['phone'].'" />';
		$c++;
	}
	$tpl->assign("INFO_U_PHONE", $phones);
	
	
	
}
else{
	$tpl->assign("INFO_U_NAME", '');
	$tpl->assign("INFO_U_IIN", '');
	$tpl->assign("INFO_U_EMAIL", '');
	$tpl->assign("INFO_U_OBJ", '');
	$tpl->assign("INFO_U_DATE_OFORM", '');
	$tpl->assign("INFO_U_DATE_START", '');
	$tpl->assign("INFO_U_DATE_END", '');
	$tpl->assign("INFO_U_COMMENT", '');
	$tpl->assign("INFO_U_PHONE", '');
	
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>