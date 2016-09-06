<?php
# SETTINGS #############################################################################
$moduleName = "clients_post";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
require_once('phpmailer/class.phpmailer.php');
include("phpmailer/class.smtp.php");
# MAIN #################################################################################
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
}
if(isset($_SESSION['post_id'])){
	$post_id = $_SESSION['post_id'];
}
// сохранение обработанных данных по клиенту
if(isset($_POST['ocen'])){
	$dbc->element_update('clients',$c_id,array(
		"email" => $_POST['email']));
    //print_r($_POST);
    $dbc->element_update('post_control',$post_id,array(
		"ocen" => $_POST['ocen'],
		"email" => $_POST['email'],
		"comment" => addslashes($_POST['call_comment']),
		"result" => $_POST['res_call_id'],
        "date_next_call" => date("Y-m-d H:i",strtotime($_POST['date_next_call'])),
		"date_obrabt" => 'NOW()'));
    //echo "<p>".$dbc->outsql;
	/*ini_set("soap.wsdl_cache_enabled", "0" );

	$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
		array(
		'login' => 'ws',
		'password' => '123456',
		'trace' => true
		)
	);
	$params2["CallPost"]["Code1C"] = $_POST['code_1C'];
	$params2["CallPost"]["ManagerCode"] = LOGIN_1C;
    $params2["CallPost"]["DateContact"] = date("Y-m-d",strtotime($_POST['date_next_call']));
	$params2["CallPost"]["Result"] = $_POST['res_call_id'];
	$params2["CallPost"]["Comment"] = addslashes($_POST['comment']);
	$params2["CallPost"]["Ocenka"] = $_POST['ocen'];
	$params2["CallPost"]["Email"] = $_POST['email'];

	$result = $client2->SaveCallPost($params2);*/

	header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
	exit;
}

if(isset($_POST['sendEmail2'])){
    $dbc->element_update('post_control',$post_id,array(
        "email" => $_POST['sendEmail2'],
        "send" => 1));
    $_mailFrom = "АВТО КЛУБ КАЗАХСТАНА";
    $_sendFrom = 'send@kazavtoclub.kz';				// E-mail отправителя
    $_mailSubject = 'Инструкция по действиям в случае ДТП';	// Тема письма
    $body = '<p>Уважаемый клиент</p>
        
        <p>Высылаем Вам во вложении Инструкцию по действиям в случае ДТП.</p>
        
        <p>С уважением,<br>
        Авто Клуб Казахстана<br>
        Контактные телефоны: <br>
        2626 (бесплатный)<br>
        8 (727) 328 66 60<br>
        8 (727) 317 28 09<br>
        8 (727) 328 97 82</p>';
    
    $attach = 'DTP.pdf';

    sendMailAttach($_POST['sendEmail2'], $_mailSubject, $body, $_mailFrom, $_sendFrom, $attach);

}

$tpl->parse("META_LINK", ".".$moduleName."html");

if(isset($c_id)){
	$rows = $dbc->dbselect(array(
			"table"=>"clients, post_control",
			"select"=>"clients.code_1C as code_1C,
			post_control.email as email,
			post_control.result as result,
			post_control.ocen as ocen,
			post_control.date_next_call as date_next_call,
			post_control.comment as comment",
			"where"=>"clients.id = ".$c_id." AND  post_control.id = ".$post_id,
			"limit"=>1
		)
	);
	$row = $rows[0];

	$tpl->assign("CLIENT_CODE_1C", $row['code_1C']);
	//$tpl->assign("EDT_COMMENT", nl2br($row['comment']));
	$tpl->assign("EDT_COMMENT", '');
    $tpl->assign("EDT_EMAIL", $row['email']);



    $ocen = '';
    for($i=1;$i<=5;$i++){
        if($i==$row['ocen']){
            $o_sel = ' selected';
        }
        else{
            $o_sel = '';
        }
        $ocen.= '<option value="'.$i.'"'.$o_sel.'>'.$i.'</option>';
    }
    $tpl->assign("O_SEL", $ocen);

	$res_calls='';
	$rows2 = $dbc->dbselect(array(
			"table"=>"res_calls_post",
			"select"=>"id, title"
		)
	);
	foreach($rows2 as $row2){
		if($row2['id']==$row['result']){
            $sel = ' selected';
        }
        else{
            $sel = '';
        }
        $res_calls.='<option value="'.$row2['id'].'"'.$sel.'>'.$row2['title'];
	}
	$tpl->assign("RES_CALLS_ROWS", $res_calls);
    if($row['date_next_call']=='0000-00-00 00:00:00'){
        $tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y 09:30",strtotime("+ 1 day")));
    }
    else{
        $tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y H:i",strtotime($row['date_next_call'])));
    }



}
else{
	$tpl->assign("EDT_NAME", '');
	$tpl->assign("EDT_EMAIL", '');
	$tpl->assign("EDT_COMMENT", '');
	
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>