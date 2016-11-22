<?php
# SETTINGS #############################################################################
$moduleName = "auto_clients";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "call_target" => $prefix . "call_target.tpl",
));
# MAIN #################################################################################
if(isset($_SESSION['c_id'])){
	$c_id = $_SESSION['c_id'];
}

//print_r($_POST);
if(isset($_POST['code_1C'])){
    $is_car = 0;
    if(isset($_POST['car'])){
        $is_car = 1;
    }
    $is_dost = 0;
    if(isset($_POST['vp4_dost'])){
        $is_dost = 1;
    }
    $is_yur = 0;
    if(isset($_POST['vp4_yur'])){
        $is_yur = 1;
    }
    $is_ev = 0;
    if(isset($_POST['vp4_ev'])){
        $is_ev = 1;
    }
    $is_korgau = 0;
    if(isset($_POST['vp4_korgau'])){
        $is_korgau = 1;
    }

    $dbc->element_update('clients',$_POST['client_id'],array(
        "name" => addslashes($_POST['name']),
        "fio" => addslashes($_POST['name']),
        "iin" => $_POST['iin'],
        "email" => $_POST['email'],
        "city" => $_POST['city'],
        "is_car" => $is_car,
        "gn" => $_POST['gn'],
        "rating" => $_POST['rating'],
        "premium" => $_POST['premium'],
        "real_premium" => $_POST['real_premium'],
        "strach_id" => $_POST['strah'],
        "is_dost" => $is_dost,
        "is_yur" => $is_yur,
        "is_ev" => $is_ev,
        "is_korgau" => $is_korgau,
        "dop_iin1" => $_POST['dop_iin1'],
        "dop_iin2" => $_POST['dop_iin2'],
        "dop_iin3" => $_POST['dop_iin3'],
        "dop_iin4" => $_POST['dop_iin4'],
        "dop_iin5" => $_POST['dop_iin5'],
        "dop_gn1" => $_POST['dop_gn1'],
        "dop_gn2" => $_POST['dop_gn2'],
        "dop_gn3" => $_POST['dop_gn3'],
        "comment" => addslashes($_POST['call_comment']),
        "date_end" => date("Y-m-d",strtotime($_POST['date_end']))));

    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    // CLIENT
    $params["iin"] = $_POST['iin'];
    $params["rnn"] = '';
    $params["telnumber"] = '';
    $result = $client->SearchClient($params);
    $с_arr = objectToArray($result);
    if(isset($с_arr['return']['Client'][0])){
        $с_arr = $с_arr['return']['Client'][0];
    }
    else{
        $с_arr = $с_arr['return']['Client'];
    }

    ini_set("soap.wsdl_cache_enabled", "0" );
    $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params2["Client"]["Code1C"] = $_POST['code_1C'];
    $params2["Client"]["Name"] = $_POST['name'];
    $params2["Client"]["FIO"] = $_POST['name'];
    $params2["Client"]["IIN"] = $_POST['iin'];
    $params2["Client"]["RNN"] = $с_arr['RNN'];
    $params2["Client"]["Email"] = $_POST['email'];
    $params2["Client"]["ManagerCode"] = LOGIN_1C;
    $params2["Client"]["ManagerName"] = $user_row['name'];
    $params2["Client"]["DateContact"] = date("Y-m-d H:i",strtotime($с_arr['DateContact']));
    $params2["Client"]["DateEndPolicy"] = date("Y-m-d",strtotime($_POST['date_end']));
    $params2["Client"]["Result"] = $с_arr['Result'];
    $params2["Client"]["Sourse"] = $с_arr['Sourse'];

    $rows = $dbc->dbselect(array(
        "table"=>"phones",
        "select"=>"phone, comment",
        "where"=>"client_id=".$c_id));
    $j = 0;
    foreach($rows as $row){
        $params2["Client"]["Telnumbers"][$j]['number']=$row['phone'];
        $params2["Client"]["Telnumbers"][$j]['comment']=$row['comment'];
        $j++;
    }
    $params2["Client"]["Error"] = $с_arr['Error'];
    $params2["Client"]["Comment"] = $с_arr['Comment'];
    $params2["Client"]["ActualDate"] = $с_arr['ActualDate'];
    $params2["Client"]["DateLastPolicy"] = $с_arr['DateLastPolicy'];
    $params2["Client"]["Gosnomer"] = $_POST['gn'];


    $td_input = date("Ymd",strtotime($_POST['date_end']));
    $curent_date = date("Ymd");
    if($td_input<$curent_date){
        $rating_client = 2;
    }
    else{
        $rating_client = $_POST['rating'];
    }


    $params2["Client"]["Rating"] = $rating_client;
    $params2["Client"]["NadoOcenit"] = true;


    // CALL
    if($_POST['call_lenght']>0){
        $call_lenght = $_POST['call_lenght'];
    }
    else{
        $call_lenght = 0;
    }
    $date_next_call = date("Y-m-d 9:30", strtotime("+1 days"));
    $city = '';
    $comment = '';
    if($_POST['dozvon']==0||$_POST['auto_send']==1){
        // nedozvon
        $res_call_id = 1;
        $date_next_call = date("Y-m-d 9:30", strtotime("+1 days"));
        $comment = 'Недозвон';
    }
    elseif ($_POST['why_call_send']==1){
        // perezvon
        $res_call_id = 3;
        if($_POST['why_call_val']=="Отказ"){
            $date_next_call = "2020-10-10 9:30";
            $comment = $_POST['why_call_val'];
        }
        else{
            $date_next_call = date("Y-m-d H:i",strtotime($_POST['date_next_call']));
            $comment = $_POST['why_call_val'];
        }
    }
    elseif ($_POST['before_call_send']==1){
        // perezvon
        $res_call_id = 3;
        $date_next_call = date("Y-m-d H:i",strtotime($_POST['date_next_call']));
        $comment = 'Перезвон';
    }
    elseif ($_POST['err_call_send']==1){
        // error
        $res_call_id = 2;
        $date_next_call = date("Y-m-d 9:30", strtotime("+1 days"));
        $comment = $_POST['err_type'];
        if($_POST['err_type']=="Другой город"){
            $city = $_POST['citys'];
        }
        else{
            $city = '';
        }
    }

    if($_POST['auto_send']==1){
        $auto = 'Yes';
    }
    else{
        $auto = '';
    }
    
    $dbc->element_create("calls", array(
        "oper_id" => ROOT_ID,
        "client_id" => $c_id,
        "date_call" => 'NOW()',
        "call_lenght" => $call_lenght,
        "res_call_id" => $res_call_id,
        "comment" => $comment,
        "date_next_call" => $date_next_call));

    $params2["Call"]["Code1C"] = $_POST['code_1C'];
    $params2["Call"]["ManagerCode"] = LOGIN_1C;
    $params2["Call"]["DateContact"] = date("Y-m-d\TH:i:s",strtotime($date_next_call));
    $params2["Call"]["Result"] = $res_call_id;
    $params2["Call"]["Comment"] = $comment;
    $params2["Call"]["Duration"] = $call_lenght;
    $params2["Call"]["Horosh"] = true;
    $params2["Call"]["Auto"] = $auto;
    $params2["Call"]["City"] = $city;

    $result = $client2->SaveClientCall($params2);


    $dbc->element_create("oper_log", array(
        "oper_id" => ROOT_ID,
        "oper_act_type_id" => 1,
        "oper_act_id" => 1,
        "date_log" => 'NOW()',
        "comment" => addslashes($comment).". Длительность: ".$call_lenght));
    $log = getOperCurentMaxLog(ROOT_ID);
    $dbc->element_update('calls_log',$log,array(
        "res" => $res_call_id,
        "rating2_id" => $rating_client,
        "date_end" => 'NOW()'));
    $dbc->element_update('dozvon_log',$_SESSION['dozvon'],array(
        "res" => $res_call_id));


    /*if(ROOT_ID==2){
        echo $date_next_call."*<br>";
        print_r($params2["Call"]["DateContact"]);
    }
    else{*/
        header("Location: /".getItemCHPU(2232, 'pages'));
    //}

}

$tpl->parse("META_LINK", ".".$moduleName."html");

$row = $dbc->element_find('clients',$c_id);
//print_r($row);
$tpl->assign("EDT_CLIENT_ID", $row['id']);
$tpl->assign("EDT_NAME", $row['name']);
$tpl->assign("EDT_1C_CODE", $row['code_1C']);
$tpl->assign("EDT_IIN", $row['iin']);
$tpl->assign("EDT_RNN", $row['rnn']);
$tpl->assign("EDT_EMAIL", $row['email']);
$tpl->assign("EDT_COMMENT", $row['comment']);
$tpl->assign("EDT_DATE_PREV_CALL", date("d-m-Y H:i",strtotime($row['date_prev_call'])));
$tpl->assign("EDT_RES_PREV_CALL", $row['res_prev_call']);
$tpl->assign("EDT_SOURCE", $row['source']);
$tpl->assign("EDT_GN", $row['gn']);
$tpl->assign("EDT_PREMIUM", $row['premium']);
$tpl->assign("EDT_REAL_PREMIUM", $row['real_premium']);

for($i=1;$i<=5;$i++){
    $tpl->assign("EDT_DOP_IIN".$i, $row['dop_iin'.$i]);
}
for($i=1;$i<=3;$i++){
    $tpl->assign("EDT_DOP_GN".$i, $row['dop_gn'.$i]);
}

if(date("d-m-Y",strtotime($row['date_end']))=='01-01-0001'){
	$date_end = '01-01-2001';
}
else{
	$date_end = date("d-m-Y",strtotime($row['date_end']));
}
$tpl->assign("EDT_DATE_END", $date_end);
$tpl->assign("EDT_COMMENT", $row['comment']);
if($row['ocenit']==1||in_array(8,$USER_ROLE)){
	$tpl->assign("OCEN_HIDE1", '');
	$tpl->assign("OCEN_HIDE2", '');
}
else{
	$tpl->assign("OCEN_HIDE1", '<!--');
	$tpl->assign("OCEN_HIDE2", '-->');
}
if($row['is_car']==1){
    $tpl->assign("EDT_CAR", 'Да');
    $tpl->assign("EDT_CAR_CHECK", ' checked');
}
else{
    $tpl->assign("EDT_CAR", 'Нет');
    $tpl->assign("EDT_CAR_CHECK", '');
}
if($row['is_dost']==1){
    $tpl->assign("EDT_4VP_DOST", 'Да');
    $tpl->assign("EDT_4VP_DOST_CHECK1", ' checked');
    $tpl->assign("EDT_4VP_DOST_CHECK2", '');
}
else{
    $tpl->assign("EDT_4VP_DOST", 'Нет');
    $tpl->assign("EDT_4VP_DOST_CHECK1", '');
    $tpl->assign("EDT_4VP_DOST_CHECK2", ' checked');
}
if($row['is_yur']==1){
    $tpl->assign("EDT_4VP_YUR", 'Да');
    $tpl->assign("EDT_4VP_YUR_CHECK1", ' checked');
    $tpl->assign("EDT_4VP_YUR_CHECK2", '');
}
else{
    $tpl->assign("EDT_4VP_YUR", 'Нет');
    $tpl->assign("EDT_4VP_YUR_CHECK1", '');
    $tpl->assign("EDT_4VP_YUR_CHECK2", ' checked');
}
if($row['is_ev']==1){
    $tpl->assign("EDT_4VP_EV", 'Да');
    $tpl->assign("EDT_4VP_EV_CHECK1", ' checked');
    $tpl->assign("EDT_4VP_EV_CHECK2", '');
}
else{
    $tpl->assign("EDT_4VP_EV", 'Нет');
    $tpl->assign("EDT_4VP_EV_CHECK1", '');
    $tpl->assign("EDT_4VP_EV_CHECK2", ' checked');
}
if($row['is_korgau']==1){
    $tpl->assign("EDT_4VP_KORGAU", 'Да');
    $tpl->assign("EDT_4VP_KORGAU_CHECK1", ' checked');
    $tpl->assign("EDT_4VP_KORGAU_CHECK2", '');
}
else{
    $tpl->assign("EDT_4VP_KORGAU", 'Нет');
    $tpl->assign("EDT_4VP_KORGAU_CHECK1", '');
    $tpl->assign("EDT_4VP_KORGAU_CHECK2", ' checked');
}

/*
switch($row['rating']){
    case 0:
        $tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо уточнить только ФИО,ГОРОД,НАЛИЧИЕ АВТОМОБИЛЯ');
    break;
    case 1:
        $tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо уточнить только ФИО,ГОРОД,НАЛИЧИЕ АВТОМОБИЛЯ');
    break;
    case 2:
        $tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Необходимо применить скрипты Расчет налога, Перечень штрафов и ЦОАП, КОНЕЧНАЯ ЦЕЛЬ ТД');
    break;
    case 3:
        $tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>4 Вопроса, Застраховался у нас');
    break;
    case 4:
        $tpl->assign("CALL_TARGET", '<strong>Рейтинг клиента: '.$row['rating'].'</strong><br><strong>Цель звонка:</strong><br>Застраховался у нас');
    break;
}
*/
if($row['rating']==0){
    $tpl->assign("CALL_TARGET_RATING", 1);
}
else{
    $tpl->assign("CALL_TARGET_RATING", $row['rating']);
}

$tpl->assign("CALL_TARGET_TD", date("d-m-Y",strtotime($row['date_end'])));
$tpl->assign("CALL_TARGET_PREV_DATE", date("d-m-Y H:i",strtotime($row['date_prev_call'])));
$tpl->assign("CALL_TARGET_PREV_RES", $row['res_prev_call']);
$tpl->parse("CALL_TARGET", ".".$moduleName."call_target");



if(strtotime($row['date_lost'])==strtotime('1970-01-01 06:00:00')){
    $tpl->assign("EDT_DATE_LOST", 'Не страховался у нас');
}
else{
    $tpl->assign("EDT_DATE_LOST", date("d-m-Y H:i",strtotime($row['date_lost'])));
}
if($row['date_tochnaya']==1){
    $tpl->assign("EDT_DATE_TOCHNAYA", 'Да');
}
else{
    $tpl->assign("EDT_DATE_TOCHNAYA", 'Нет');
}

$citys='';
$city_title='';
$rows5 = $dbc->dbselect(array(
        "table"=>"city",
        "select"=>"id, title"
    )
);
foreach($rows5 as $row5){
    if($row5['id']==$row['city']){
        $citys.='<option value="'.$row5['id'].'" selected>'.$row5['title'];
        $city_title=$row5['title'];
    }
    else{
        $citys.='<option value="'.$row5['id'].'">'.$row5['title'];
    }

}
$tpl->assign("CITYS_ROWS", $citys);
$tpl->assign("EDT_CITY", $city_title);

$strachs='';
$strach_title='';
$rows5 = $dbc->dbselect(array(
        "table"=>"strach_company",
        "select"=>"id, title",
        "where"=>"view=0"
    )
);
foreach($rows5 as $row5){
    if($row5['id']==$row['strach_id']){
        $strachs.='<option value="'.$row5['id'].'" selected>'.$row5['title'];
        $strach_title=$row5['title'];
    }
    else{
        $strachs.='<option value="'.$row5['id'].'">'.$row5['title'];
    }

}
$tpl->assign("STRAHS_ROWS", $strachs);
$tpl->assign("EDT_4VP_STRAH", $strach_title);

$tpl->assign("EDT_DATE_NEXT_CALL", date("d-m-Y H:i",strtotime("+ 1 hour")));
$c_id = $_SESSION['c_id'];
$rows = $dbc->dbselect(array(
        "table"=>"phones",
        "select"=>"*",
        "where"=>"client_id = ".$c_id,
        "limit"=>10
    )
);
$phones = '';
$i=1;
foreach($rows as $row){
    if($i==1){
        $tpl->assign("EDT_H_PHONES", $row['phone']);
    }
    $i++;
    $star_phones = substr_replace($row['phone'], '*****', 4, 3);
    $phones.=$star_phones.'<br>Комментарий: <input type="text" name="phone_comment['.$row['id'].']" value="'.$row['comment'].'"  class="pole_vvoda" style="padding-left:10px;"> <br>';
}
$tpl->assign("EDT_PHONES", $phones);

if(!in_array(2,$USER_ROLE)){
    $tpl->assign("NO_PRODAZH_HIDE1", '<!--');
    $tpl->assign("NO_PRODAZH_HIDE2", '-->');
    $tpl->assign("PRODAZH_HIDE1", '');
    $tpl->assign("PRODAZH_HIDE2", '');
}
else{
    $tpl->assign("NO_PRODAZH_HIDE1", '');
    $tpl->assign("NO_PRODAZH_HIDE2", '');
    $tpl->assign("PRODAZH_HIDE1", '<!--');
    $tpl->assign("PRODAZH_HIDE2", '-->');
}


$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>