<?php
# SETTINGS #############################################################################
$moduleName = "polis_for_bso";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "grid" => $prefix . "grid.tpl",
		$moduleName . "p_view" => $prefix . "p_view.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
if(!isset($_GET['polis_view'])){
	// поиск полисов
    $search = false;
    if(isset($_POST['bso'])){
        $row = $dbc->element_find_by_field('polises','bso_number',$_POST['bso']);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            header("Location: /".getItemCHPU($_GET['menu'], 'pages')."/?polis_view=".$row['id']);
            exit;
        }
        else{
            $search = true;
        }
    }

    if($search){
        $tpl->assign("SEARCH_BSO", $_POST['bso']);
        $tpl->assign("SEARCH_RESULT", '<p><font color="red"><b>По данному БСО полиса ненайдено !</b></font></p>');
    }
    else{
        $tpl->assign("SEARCH_RESULT", '');
        $tpl->assign("SEARCH_BSO", '');
    }

	$tpl->parse("META_LINK", ".".$moduleName."grid");
	$tpl->parse(strtoupper($moduleName), ".".$moduleName);

}
else{

    if(isset($_POST['pc_err'])){
        $c_id = LOGIN_1C;
        $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
            array(
                'login' => 'ws',
                'password' => '123456',
                'trace' => true
            )
        );
        $params2["bso_number"] = $_POST['pc_err'];
        $params2["manager_code"] = $c_id;
        $result = $client2->PutPolicToTrash($params2);
        $array_save = objectToArray($result);
        $res_save_1c = $array_save['return'];
        //echo $res_save_1c;
        if($res_save_1c=='Успешно') {
            $sql = "UPDATE polises SET 
                  status=5,
                  date_err=NOW()
                  WHERE bso_number='".$_POST['pc_err']."'";
            $dbc->element_free_update($sql);
            /*$dbc->element_update('polises',$_POST['pc_err'],array(
                "status" => 5,
				"1C_comment" => $res_save_1c,
                "date_err" => 'NOW()'));*/
        }
        else{
            echo "<p>Ошибка сохранения в 1C !<br>".$res_save_1c;
        }
    }

	if(isset($_POST['printEv'])){
		// печать Талона эвакуатора
		echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?policeNum='.$_POST['policeNum'].'&pdf_type=evaq");</script>';
	}



	if(isset($_POST['printPolis'])){
		// печать Полиса
		$dbc->element_update('polises',$_GET['polis_view'],array(
			"status" => 2,
			"date_print" => 'NOW()'));
		header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
	}


	if(isset($_POST['printAdr'])){
		// печать Адресной листовки
		echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?policeNum='.$_POST['policeNum'].'&pdf_type=adres");</script>';
	}


	// информация о полисе
	$tpl->parse("META_LINK", ".".$moduleName."html");

	$rows = $dbc->dbselect(array(
		"table"=>"polises",
		"select"=>"polises.*, 
		strach_company.title AS strach_comp,
		strach_periods.title AS strach_period,
		pay_types.title AS pay_type,
		pays.title AS pay,
		users.name AS oper,
		clients.id AS client_id,
		clients.name AS fio",
		"joins"=>"LEFT OUTER JOIN strach_company ON polises.strach_comp_id = strach_company.id
		LEFT OUTER JOIN strach_periods ON polises.period_id = strach_periods.id
		LEFT OUTER JOIN pay_types ON polises.pay_type_id = pay_types.id
		LEFT OUTER JOIN pays ON polises.pay_id = pays.id
		LEFT OUTER JOIN users ON polises.oper_id = users.id 
		LEFT OUTER JOIN clients ON polises.client_id = clients.id",
		"where"=>"polises.id = ".$_GET['polis_view'],
		"limit"=>1));

	$row = $rows[0];

    $client2 = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $params2["PolicNumber"] = $row['bso_number'];
    $result = $client2->GetPolicInfo($params2);
    $array_info = objectToArray($result);
    //print_r($array_info);
    $res_status = $array_info['return']['PolicInfo']['Status'];
    $tpl->assign("VIEW_P_STATUS", $res_status);

	$tpl->assign("VIEW_P_OPER", $row['oper']);
	$tpl->assign("VIEW_P_CLIENT", $row['fio']);
	$tpl->assign("VIEW_P_DATE_OFORM", date("d-m-Y",strtotime($row['date_oform'])));
	$tpl->assign("VIEW_P_DATE_START", date("d-m-Y",strtotime($row['date_start'])));
	$tpl->assign("VIEW_P_DATE_END", date("d-m-Y",strtotime($row['date_end'])));
	$tpl->assign("VIEW_P_DATE_DOST", date("d-m-Y",strtotime($row['date_dost'])));
	$tpl->assign("VIEW_P_DOST_ADDRESS", $row['dost_address']);
	$tpl->assign("VIEW_P_POLIS_NUM", $row['bso_number']);
	$tpl->assign("VIEW_P_ALIAS", $row['strach_comp']);
	$tpl->assign("VIEW_P_STRACH_PERIOD", $row['strach_period']);
	$tpl->assign("VIEW_P_PAY_TYPE", $row['pay_type']);
	$tpl->assign("VIEW_P_PAY", $row['pay']);
	if($row['dost']==1){
		$tpl->assign("VIEW_P_DOST_CHECK", 'Да');
	}
	else{
		$tpl->assign("VIEW_P_DOST_CHECK", 'Нет');
	}
	if($row['rewrite']==1){
		$tpl->assign("VIEW_P_DOPLATA", '<p><strong>Доплата</strong><br>'.($row['premium']-$row['not_gained_premium']).' тг');

		$rows3 = $dbc->dbselect(array(
			"table"=>"gifts",
			"select"=>"gift_types.summa AS  summa",
			"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
			"where"=>"gifts.polis_id = ".$_GET['polis_view']));
		$gifts_sum = 0;
		foreach($rows3 as $row3){
			$gifts_sum+= $row3['summa'];
		}
		$sum = $row['premium'] - $row['not_gained_premium'] - $gifts_sum;
		$sum = ceil($sum/50) * 50;
		$tpl->assign("VIEW_P_SUMMA", number_format($sum, 0, ',', ' '));
	}
	else{
		$tpl->assign("VIEW_P_SUMMA", $row['summa']);
		$tpl->assign("VIEW_P_DOPLATA", '');
	}

	$row['premium'] = ceil($row['premium']/50) * 50;
	$tpl->assign("VIEW_P_PREMIUM", number_format($row['premium'], 0, ',', ' '));

	$rows3 = $dbc->dbselect(array(
		"table"=>"gifts",
		"select"=>"gifts.id AS id,
				gift_types.title AS  gift,
				gift_types.uchet AS  uchet,
				gift_types.summa AS  summa",
		"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
		"where"=>"gifts.polis_id = ".$_GET['polis_view']));
	$gifts_sum = 0;
	$gifts_rows = '';
	$numRows = $dbc->count;
	if ($numRows > 0) {
		foreach($rows3 as $row3){
			if($row3['uchet']==1){
				$gifts_sum+= $row3['summa'];
			}
			$gifts_rows.= $row3['gift'].' ('.$row3['summa'].' тг)<br>';
		}
	}
	else{
		$gifts_rows = '-';
	}

	$tpl->assign("VIEW_P_GIFTS", $gifts_rows);
	$tpl->assign("VIEW_P_GIFTS_NUM", $gifts_sum.' тг');


	$rows3 = $dbc->dbselect(array(
		"table"=>"phones",
		"select"=>"phone",
		"where"=>"client_id = ".$row['client_id']));
	$phones = '';
	foreach($rows3 as $row3){
		$phones.= $row3['phone'].'<br>';
	}
	$tpl->assign("VIEW_P_PHONES", $phones);

	$tpl->parse(strtoupper($moduleName), ".".$moduleName."p_view");


}



?>