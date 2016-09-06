<?php
# SETTINGS #############################################################################
$moduleName = "polis";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "main2" => $prefix . "main2.tpl",
		$moduleName . "main3" => $prefix . "main3.tpl",
		$moduleName . "main4" => $prefix . "main4.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
));
# MAIN #################################################################################
// запрос и подготовка полиса
if(isset($_GET['rew'])){
	$_SESSION['polis'] = $_GET['polis'];
}

if(isset($_SESSION['polis'])){
	$polis_id = $_SESSION['polis'];
	$rows = $dbc->dbselect(array(
		"table"=>"polises",
		"select"=>"polises.*, 
			strach_company.title AS strach_comp,
			strach_periods.title AS strach_period,
			pay_types.title AS pay_type,
			pays.title AS pay",
		"joins"=>"LEFT OUTER JOIN strach_company ON polises.strach_comp_id = strach_company.id
			LEFT OUTER JOIN strach_periods ON polises.period_id = strach_periods.id
			LEFT OUTER JOIN pay_types ON polises.pay_type_id = pay_types.id
			LEFT OUTER JOIN pays ON polises.pay_id = pays.id",
		"where"=>"polises.id = ".$polis_id,
		"limit"=>1));
	$row = $rows[0];
	$tpl->assign("INFO_P_DATE_OFORM", date("d-m-Y H:i",strtotime($row['date_oform'])));
	$tpl->assign("INFO_P_DATE_START", date("d-m-Y H:i",strtotime($row['date_start'])));
	$tpl->assign("INFO_P_DATE_END", date("d-m-Y H:i",strtotime($row['date_end'])));
	$tpl->assign("INFO_P_DATE_DOST", date("d-m-Y H:i",strtotime($row['date_dost'])));
	$tpl->assign("INFO_P_DOST_ADDRESS", $row['dost_address']);
	$tpl->assign("INFO_P_ALIAS", $row['strach_comp']);
	$tpl->assign("INFO_P_STRACH_PERIOD", $row['strach_period']);
	$tpl->assign("INFO_P_PAY_TYPE", $row['pay_type']);
	$tpl->assign("INFO_P_PAY", $row['pay']);
	$tpl->assign("POLIS_ID", $polis_id);
	if($row['dost']==1){
		$tpl->assign("INFO_P_DOST_CHECK", 'Да');
	}
	else{
		$tpl->assign("INFO_P_DOST_CHECK", 'Нет');
	}

	if($row['rewrite']==1){
		$tpl->assign("INFO_P_DOPLATA", '<p><strong>Доплата</strong><br>'.($row['premium']-$row['not_gained_premium']).' тг');

		$rows = $dbc->dbselect(array(
			"table"=>"gifts",
			"select"=>"gift_types.summa AS  summa,
					gift_types.uchet AS  uchet",
			"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
			"where"=>"gifts.polis_id = ".$_SESSION['polis']));
		$numRows = $dbc->count;
		$gifts_sum = 0;
		if ($numRows > 0) {
			foreach ($rows as $row3) {
				if ($row3['uchet'] == 1) {
					$gifts_sum += $row3['summa'];
				}
			}
		}
		$sum = $row['premium'] - $row['not_gained_premium'] - $gifts_sum;
		$sum = ceil($sum/50) * 50;
		$dbc->element_update('polises',$_SESSION['polis'],array(
			"summa" => $sum));
		$tpl->assign("INFO_P_SUMMA", number_format($sum, 0, ',', ' '));
	}
	else{
		$rows = $dbc->dbselect(array(
			"table"=>"gifts",
			"select"=>"gift_types.summa AS  summa,
					gift_types.uchet AS  uchet",
			"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
			"where"=>"gifts.polis_id = ".$_SESSION['polis']));
		$numRows = $dbc->count;
		$gifts_sum = 0;
		if ($numRows > 0) {
			foreach ($rows as $row3) {
				if ($row3['uchet'] == 1) {
					$gifts_sum += $row3['summa'];
				}
			}
		}
		$sum = $row['premium'] - $gifts_sum;
		$sum = ceil($sum/50) * 50;
		$dbc->element_update('polises',$_SESSION['polis'],array(
			"summa" => $sum));
		$tpl->assign("INFO_P_SUMMA", number_format($sum, 0, ',', ' '));
		$tpl->assign("INFO_P_DOPLATA", '');
	}

	$row['premium'] = ceil($row['premium']/50) * 50;
	$tpl->assign("INFO_P_PREMIUM", number_format($row['premium'], 0, ',', ' '));
	$tpl->assign("INFO_P_PREMIUM2", $row['premium']);

	$tpl->assign("INFO_P_HIDE1", '');
	$tpl->assign("INFO_P_HIDE2", '');

	$rows = $dbc->dbselect(array(
		"table"=>"gifts",
		"select"=>"gift_types.title AS  gift",
		"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
		"where"=>"gifts.polis_id = ".$_SESSION['polis']));
	$numRows = $dbc->count;
	$gifts_titles = '';
	if ($numRows > 0) {
		foreach ($rows as $row3) {
			$gifts_titles.= $row3['gift'].'<br>';
		}
		$tpl->assign("INFO_P_GIFTS_ROWS", '<p><strong>Подарки</strong><br>'.$gifts_titles);
	}
	else{
		$tpl->assign("INFO_P_GIFTS_ROWS", '');
	}

	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
	if(!in_array(5,$USER_ROLE)){
		if($_SESSION['bso'] == 0){
			$tpl->parse(strtoupper($moduleName), ".".$moduleName."main3");
		}
		else{
			if($_SESSION['bso'] == 1){
				$tpl->parse(strtoupper($moduleName), ".".$moduleName."main4");
			}
			else{
				$tpl->parse(strtoupper($moduleName), ".".$moduleName."main2");
			}
		}
	}
	else{
		$tpl->parse(strtoupper($moduleName), ".".$moduleName."main2");
	}
}
?>