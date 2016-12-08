<?php
# SETTINGS #############################################################################
$moduleName = "clients_sto";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
}
// сохранение обработанных данных по клиенту
if(isset($_POST['edt_item'])){
	$dbc->element_update('sto',$c_id,array(
        "name" => $_POST['name'],
		"gn" => $_POST['gn'],
		"pn" => $_POST['pn'],
        "phone2" => $_POST['phone2'],
		"mark" => $_POST['mark'],
		"model" => $_POST['model'],
		"born" => $_POST['born'],
		"date_to_end" => date("Y-m-d",strtotime($_POST['date_to_end']))));

	ini_set("soap.wsdl_cache_enabled", "0" );
	$client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
    $row = $dbc->element_find('sto',$c_id);
    $params2["Client"]["Name"] = $_POST['name'];
    $params2["Client"]["DateOfEnd"] = date("Y-m-d",strtotime($_POST['date_to_end']));
    $params2["Client"]["GosNomer"] = $_POST['gn'];
    $params2["Client"]["TechPassport"] = $_POST['pn'];
    $params2["Client"]["Mark"] = $_POST['mark'];
    $params2["Client"]["Model"] = $_POST['model'];
    $params2["Client"]["GodVypusk"] = $_POST['born'];
    $params2["Client"]["Telefon"] = $row['phone'];
    $params2["Client"]["Telefon1"] = $_POST['phone2'];
    $params2["Client"]["Email"] = $_POST['email'];
    $params2["Client"]["Comment"] = '';
    $params2["Client"]["Iin"] = $row['iin'];
    $params2["Client"]["Code1C"] = $_POST['code_1C'];
    //print_r($params2);
	$result = $client2->SaveClient1($params2);
    $array_save = objectToArray($result);
    $res_save_1c = $array_save['return'];
    //echo "<p>";
    //print_r($res_save_1c);
	header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
	exit;
}

if(isset($_POST['res_call_id'])){
	if($_POST['call_lenght']>0){
		$call_lenght = $_POST['call_lenght'];
	}
	else{
		$call_lenght = 0;
	}
	$dbc->element_create("calls", array(
		"oper_id" => ROOT_ID,
		"client_id" => $c_id,
		"date_call" => 'NOW()',
		"call_lenght" => $call_lenght,
		"res_call_id" => $_POST['res_call_id'],
		"comment" => addslashes($_POST['call_comment']),
		"date_next_call" => date("Y-m-d H:i",strtotime($_POST['date_next_call']))));

    switch ($_POST['res_call_id']){
        case 1:
            $dbc->element_update('sto',$c_id,array(
                "res_call_id" => $_POST['res_call_id'],
                "date_dog" => date("Y-m-d",strtotime($_POST['date_dog'])),
                "sto_tochka_id" => $_POST['sto'],
				"comment" => $_POST['call_comment2'],
                "date_call" => 'NOW()'));
            $row5 = $dbc->element_find('sto',$c_id);
            if(date("Ymd",strtotime($row5['date_dog']))==date("Ymd")){
                $sms_body = urlencode('Тех.осмотр со скидкой 20%.Адрес: Мирзояна 112/2,тел:87718488098.Автоклуб');
                $sms_url = "http://smsc.kz//sys/send.php?login=Tigay84@list.ru&psw=94120593&&phones=".$row5['phone']."&charset=utf-8&mes=".$sms_body;
                $result = get_web_page( $sms_url );
            }
            break;
        case 2:
            $dbc->element_update('sto',$c_id,array(
                "res_call_id" => $_POST['res_call_id'],
                "date_call" => 'NOW()'));
            break;
        case 3:
            $dbc->element_update('sto',$c_id,array(
                "res_call_id" => $_POST['res_call_id'],
                "date_call" => 'NOW()'));
            break;
        case 4:
            $dbc->element_update('sto',$c_id,array(
                "res_call_id" => $_POST['res_call_id'],
                "pozvon_res_id" => $_POST['perezvon_res_id'],
                "date_call" => 'NOW()'));
            break;
        case 5:
            $dbc->element_update('sto',$c_id,array(
                "res_call_id" => $_POST['res_call_id'],
                "err_res_id" => $_POST['err_res_id'],
                "date_call" => 'NOW()'));
            break;
    }




    ini_set("soap.wsdl_cache_enabled", "0" );

	$client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
	
    if($_POST['res_call_id']==5){
        $res_err = $_POST['err_res_id'];
    }
    else{
		if($_POST['res_call_id']==4){
			$res_err = $_POST['perezvon_res_id'];
		}
		else{
			$res_err = 0;
		}
    }
    $row = $dbc->element_find('sto_tochka',$_POST['sto']);
	$params2["Call"]["MenegerCode"] = LOGIN_1C;
	$params2["Call"]["ClientCode"] = $_POST['code_1C'];
	$params2["Call"]["Status"] = $_POST['res_call_id'];
	$params2["Call"]["DopStatus"] = $res_err;
	if($_POST['res_call_id']==1){
        $params2["Call"]["Comment"] = $_POST['call_comment2'];
		$params2["Call"]["DateContact"] = date('Y-m-d\TH:i:s',strtotime($_POST['date_dog']));
    }
    else{
        $params2["Call"]["Comment"] = $_POST['call_comment'];
		$params2["Call"]["DateContact"] = date('Y-m-d\TH:i:s',strtotime($_POST['date_next_call']));
    }


    $params2["Call"]["Sto"] = $row['code'];
    $params2["Call"]["DateDogovor"] = date('Y-m-d',strtotime($_POST['date_dog']));
    //print_r($params2);
	$result = $client2->SaveCall($params2);

	$dbc->element_create("oper_log", array(
		"oper_id" => ROOT_ID,
		"oper_act_type_id" => 1,
		"oper_act_id" => 1,
		"date_log" => 'NOW()',
		"comment" => addslashes($_POST['call_comment']).". Длительность: ".$call_lenght));

	$log = getOperCurentMaxLog(ROOT_ID);
	$dbc->element_update('calls_log',$log,array(
		"res" => $_POST['res_call_id'],
		"date_end" => 'NOW()'));

	header("Location: /".getItemCHPU(2218, 'pages'));
	exit;
}

$tpl->parse("META_LINK", ".".$moduleName."html");

if(isset($c_id)){
	$row = $dbc->element_find('sto',$c_id);

	$tpl->assign("CLIENT_CODE_1C", $row['code_1C']);
    $tpl->assign("CLIENT_ID", $row['id']);
	$tpl->assign("U_NAME", $row['name']);
	$tpl->assign("U_GN", $row['gn']);
	$tpl->assign("U_PN", $row['pn']);
	$tpl->assign("U_PHONE2", $row['phone2']);
	$tpl->assign("U_MARK", $row['mark']);
	$tpl->assign("U_MODEL", $row['model']);
	$tpl->assign("U_YEAR", $row['born']);
    $tpl->assign("U_EMAIL", $row['email']);
	$tpl->assign("EDT_COMMENT", nl2br($row['comment']));
	if($row['date_to_end']=='0001-01-01'){
		$tpl->assign("U_DATE_PREV_TO", date("d-m-Y"));
		$tpl->assign("PREV_TO_COLOR", 'pole_vvoda2');
	}
	else{
		$tpl->assign("U_DATE_PREV_TO", date("d-m-Y",strtotime($row['date_to_end'])));
		$tpl->assign("PREV_TO_COLOR", 'pole_vvoda');
	}


	$res_calls='';
	$rows = $dbc->dbselect(array(
			"table"=>"sto_res_call",
			"select"=>"id, title"
		)
	);
	foreach($rows as $row){
		$res_calls.='<option value="'.$row['id'].'">'.$row['title'];
	}
	$tpl->assign("RES_CALLS_ROWS", $res_calls);

    $err_res_calls='';
    $rows = $dbc->dbselect(array(
            "table"=>"sto_res_err",
            "select"=>"id, title"
        )
    );
    foreach($rows as $row){
        $err_res_calls.='<option value="'.$row['id'].'">'.$row['title'];
    }
    $tpl->assign("ERR_RES_CALLS_ROWS", $err_res_calls);

	$pere_res_calls='';
	$rows = $dbc->dbselect(array(
			"table"=>"sto_res_pozvon",
			"select"=>"id, title"
		)
	);
	foreach($rows as $row){
		$pere_res_calls.='<option value="'.$row['id'].'">'.$row['title'];
	}
	$tpl->assign("PREZVON_RES_CALLS_ROWS", $pere_res_calls);

    $sel_sto='';
    $rows = $dbc->dbselect(array(
            "table"=>"sto_tochka",
            "select"=>"id, title"
        )
    );
    foreach($rows as $row){
        $sel_sto.='<option value="'.$row['id'].'">'.$row['title'];
    }
    $tpl->assign("SEL_STO", $sel_sto);

	$tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y H:i",strtotime("+ 1 hour")));

    $tpl->assign("EDT_DATE_DOG", date("d-m-Y H:i"));


}
else{
	$tpl->assign("EDT_NAME", '');
	$tpl->assign("EDT_IIN", '');
	$tpl->assign("EDT_RNN", '');
	$tpl->assign("EDT_EMAIL", '');
	$tpl->assign("EDT_DATE_PREV_CALL", '');
	$tpl->assign("EDT_RES_PREV_CALL", '');
	$tpl->assign("EDT_SOURCE", '');
	$tpl->assign("EDT_DATE_END", '');
	$tpl->assign("U_PHONE2", '');
	$tpl->assign("EDT_COMMENT", '');
	$tpl->assign("EDT_DATE_LOST", '');
	$tpl->assign("EDT_DATE_TOCHNAYA", '');
	$tpl->assign("EDT_CARS", '');
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>