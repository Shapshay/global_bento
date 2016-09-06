<?php
# SETTINGS #############################################################################
$moduleName = "polises";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "gift_row" => $prefix . "gift_row.tpl",
		$moduleName . "add" => $prefix . "add.tpl",
));
# MAIN #################################################################################
if(isset($_GET['polis'])){
	$_SESSION['polis'] = $_GET['polis'];
	if(isset($_POST['edt_polis'])){
		if(isset($_POST['dost'])||!in_array(5,$USER_ROLE)){
			$dost = 1;
		}
		else{
			$dost = 0;
		}
		if($_POST['period_id']>0){
			$srok = 'm';
		}
		else{
			$srok = 'd';
		}
		if($srok == 'd'){
			$date = date("Y-m-d", strtotime($_POST['date_start']));
			$d = new DateTime($date);
			$num = $dbc->element_find('strach_periods',$_POST['period_id']);
			$kol = $num['kol'];
			$d->modify("+".$kol." days");
			$endDate = $d->format("Y-m-d");
		}
		else{
			$date = date("Y-m-d", strtotime($_POST['date_start']));
			$d = new DateTime($date);
			$num = $dbc->element_find('strach_periods',$_POST['period_id']);
			$kol = $num['kol'];
			$d->modify("+".$kol." months");
			//echo $d->format("Y-m-d");
			$d->modify("-1 days");
			$endDate = $d->format("Y-m-d");
		}

		$_SESSION['bso'] = $_POST['bso'];
		$dbc->element_update('polises',$_GET['polis'],array(
			"oper_id" => ROOT_ID,
			"client_id" => $c_id,
			"bso_number" => $_SESSION['bso'],
			"strach_comp_id" => $_POST['strach_comp_id'],
			"period_id" => $_POST['period_id'],
			"office_id" => $_POST['office_id'],
			"pay_type_id" => $_POST['pay_type_id'],
			"pay_id" => $_POST['pay_id'],
			"premium" => $_POST['premium'],
			"gn" => $_POST['gn'],
			"car_year" => $_POST['car_year'],
			"mark_id" => $_POST['mark_id'][1],
			"model_id" => $_POST['model_id'][1],
			"date_oform" => date("Y-m-d H:i", strtotime($_POST['date_oform'])),
			"date_start" => date("Y-m-d H:i", strtotime($_POST['date_start'])),
			"date_end" => date("Y-m-d H:i", strtotime($endDate)),
			"dost" => $dost,
			"recalc" => 1,
			"sms" => $_POST['sms'],
			"lng_sms" => $_POST['lng_sms'],
			"dost_address" => $_POST['dost_address'],
			"date_dost" => date("Y-m-d H:i", strtotime($_POST['date_dost']))));
		$_SESSION['strach_comp'] = $_POST['strach_comp_id'];
		$_SESSION['polis'] = $_GET['polis'];
		$dbc->element_update('polises',$_SESSION['polis'],array(
			"recalc" => 1));
		header("Location: /".getItemCHPU($_GET['menu'], 'pages')."/?polis=".$_GET['polis']);
		exit;
	}

	if(isset($_POST['add_gift'])){
		$dbc->element_create("gifts", array(
			"gift_type_id" => $_POST['gift_type_id'],
			"polis_id" => $_GET['polis']));
		$dbc->element_update('polises',$_SESSION['polis'],array(
			"recalc" => 1));
		header("Location: /".getItemCHPU($_GET['menu'], 'pages')."/?polis=".$_GET['polis']);
		exit;
	}

	if(isset($_POST['del_gift_id'])){
		$dbc->element_delete('gifts',$_POST['del_gift_id']);
		$dbc->element_update('polises',$_SESSION['polis'],array(
			"recalc" => 1));
		header("Location: /".getItemCHPU($_GET['menu'], 'pages')."/?polis=".$_GET['polis']);
		exit;
	}

	$tpl->parse("META_LINK", ".".$moduleName."html");

	$row = $dbc->element_find('polises',$_GET['polis']);

	if($row['lng_sms']==0){
		$tpl->assign("EDT_LNG_SMS1", ' selected="selected"');
		$tpl->assign("EDT_LNG_SMS2", '');
	}
	else{
		$tpl->assign("EDT_LNG_SMS1", '');
		$tpl->assign("EDT_LNG_SMS2", ' selected="selected"');
	}

	$tpl->assign("POLIS_ID", $row['id']);
	$tpl->assign("EDT_BSO_NUMBER", $row['bso_number']);
	$tpl->assign("EDT_PREMIUM", $row['premium']);
	$tpl->assign("EDT_GN", $row['gn']);
	if(!in_array(5,$USER_ROLE)){
		$tpl->assign("EDT_BSO_NUMBER", $row['bso_number']);
		$tpl->assign("EDT_BSO_NUMBER_EDIT", ' readonly="1"');
	}
	else{
		$tpl->assign("EDT_BSO_NUMBER", $row['bso_number']);
		$tpl->assign("EDT_BSO_NUMBER_EDIT", '');
	}

	$tpl->assign("EDT_CAR_YEAR", $row['car_year']);
	if($row['mark_id']!=0&&$row['model_id']!=0){
		$rows66 = $dbc->dbselect(array(
			"table"=>"salem_models",
			"select"=>"*",
			"where"=>"make_id = ".$row['mark_id']." AND model_id = ".$row['model_id'],
			"limit"=>1));
		$row66 = $rows66[0];
		$tpl->assign("EDT_MARK_ID", $row['mark_id']);
		$tpl->assign("EDT_MODEL_ID", $row['model_id']);
		$tpl->assign("EDT_MARK", $row66['make']);
		$tpl->assign("EDT_MODEL", $row66['model']);
		$tpl->assign("EDT_SHOW_MARK", '<script>$(document).ready(function() {$("#KaskoCarDiv").show();$("#ModelsDiv1").show();$("#btnsLitersDiv").show();});</script>');
	}
	else{
		$tpl->assign("EDT_MARK_ID", 0);
		$tpl->assign("EDT_MODEL_ID", 0);
		$tpl->assign("EDT_MARK", '');
		$tpl->assign("EDT_MODEL", '');
		$tpl->assign("EDT_SHOW_MARK", '');
	}
	$tpl->assign("EDT_STRACH_COMP_ID", $row['strach_comp_id']);
	$tpl->assign("EDT_PERIOD_ID", $row['period_id']);
	$tpl->assign("EDT_PAY_TYPE_ID", $row['pay_type_id']);
	$tpl->assign("EDT_PAY_ID", $row['pay_id']);
	$tpl->assign("EDT_SMS", substr($row['sms'],1));
	$tpl->assign("EDT_DATE_OFORM", date("d-m-Y",strtotime($row['date_oform'])));
	$tpl->assign("EDT_DATE_START", date("d-m-Y",strtotime($row['date_start'])));
	$tpl->assign("EDT_DATE_END", date("d-m-Y",strtotime($row['date_end'])));
	if($row['dost']==1||!in_array(5,$USER_ROLE)){
		$tpl->assign("DOST_CHECK", ' checked="checked"');
	}
	else{
		$tpl->assign("DOST_CHECK", '');
	}
	$tpl->assign("EDT_DOST_ADDRESS", $row['dost_address']);
	$tpl->assign("EDT_DATE_DOST", date("d-m-Y",strtotime($row['date_dost'])));

	$city_sel = '';
	if(ROOT_OFFICE==1){
		$rows3 = $dbc->dbselect(array(
			"table"=>"offices",
			"select"=>"*"));
		foreach($rows3 as $row3){
			if($row3['id']==$row['office_id']){
				$city_sel.= '<option value="'.$row3['id'].'" selected="1">'.$row3['title'].'</option>';
			}
			else{
				$city_sel.= '<option value="'.$row3['id'].'">'.$row3['title'].'</option>';
			}
		}
	}
	else{
		$rows3 = $dbc->dbselect(array(
			"table"=>"offices",
			"select"=>"*",
			"where"=>"id = ".ROOT_OFFICE,
			"limit"=>1));
		$row3 = $rows3[0];
		$city_sel.= '<option value="'.$row3['id'].'" selected="1">'.$row3['title'].'</option>';
	}
	$tpl->assign("OFFICE_SEL", $city_sel);

	$strach_periods = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"strach_periods",
		"select"=>"*"));
	foreach($rows2 as $row2){
		if($row2['id']==$row['period_id']){
			$strach_periods.= '<option value="'.$row2['id'].'" selected="selected">'.$row2['title'].'</option>';
		}
		else{
			$strach_periods.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
		}
	}
	$tpl->assign("EDT_STRACH_PERIOD", $strach_periods);

	$option_gifts = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"gift_types",
		"select"=>"*"));
	foreach($rows2 as $row2){
		$option_gifts.= '<option value="'.$row2['id'].'">'.$row2['title'].' ('.$row2['summa'].' тг)</option>';
	}
	$tpl->assign("OPTION_GIFTS_ROWS", $option_gifts);

	$rows3 = $dbc->dbselect(array(
			"table"=>"gifts",
			"select"=>"gifts.id AS id,
					gift_types.title AS  gift,
					gift_types.summa AS  summa",
			"joins"=>"LEFT OUTER JOIN gift_types ON gifts.gift_type_id = gift_types.id",
			"where"=>"gifts.polis_id = ".$_GET['polis']
		)
	);
	$numRows = $dbc->count;
	$gifts_sum = 0;
	if ($numRows > 0) {
		foreach($rows3 as $row3){
			$tpl->assign("GIFT_ID", $row3['id']);
			$tpl->assign("GIFT_TYPE", $row3['gift']);
			$tpl->assign("GIFT_SUMMA", $row3['summa']);
			$gifts_sum+= $row3['summa'];
			$tpl->parse("GIFTS_ROWS", ".".$moduleName."gift_row");
		}
	}
	else{
		$tpl->assign("GIFTS_ROWS", '');
	}
	$tpl->assign("GIFTS_ITOG", $gifts_sum);

	$pay_types = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"pay_types",
		"select"=>"*"));
	foreach($rows2 as $row2){
		if($row2['id']==$row['pay_type_id']){
			$pay_types.= '<option value="'.$row2['id'].'" selected="selected">'.$row2['title'].'</option>';
		}
		else{
			$pay_types.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
		}
	}
	$tpl->assign("EDT_PAY_TYPE", $pay_types);

	$pay = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"pays",
		"select"=>"*"));
	foreach($rows2 as $row2){
		if($row2['id']==$row['pay_id']){
			$pay.= '<option value="'.$row2['id'].'" selected="selected">'.$row2['title'].'</option>';
		}
		else{
			$pay.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
		}
	}
	$tpl->assign("EDT_PAY", $pay);

	$strach_company = '';
	$rows2 = $dbc->dbselect(array(
		"table"=>"strach_company",
		"select"=>"*",
		"where"=>"view = 1"));
	foreach($rows2 as $row2){
		if($row2['id']==$row['strach_comp_id']){
			$strach_company.= '<option value="'.$row2['id'].'" selected="selected">'.$row2['title'].'</option>';
		}
		else{
			$strach_company.= '<option value="'.$row2['id'].'">'.$row2['title'].'</option>';
		}
	}
	$tpl->assign("EDT_ALIAS", $strach_company);

	if(!in_array(5,$USER_ROLE)){
		$tpl->assign("POLIS_HIDE1", '');
		$tpl->assign("POLIS_HIDE2", '');
	}
	else{
		$tpl->assign("POLIS_HIDE1", '<!--');
		$tpl->assign("POLIS_HIDE2", '-->');
	}

	$liter_btns = '';
	$SQL = "SELECT 
			LEFT(make,1) as litera
			FROM salem_models
		GROUP BY litera";
	$rows3 = $dbc->db_free_query($SQL);
	foreach($rows3 as $row3){
		$liter_btns.= '<button type="button" onclick="choiseLiter(\''.$row3['litera'].'\');" class="btn_kasko">'.$row3['litera'].'</button> ';
	}
	$tpl->assign("LITERS_BTN",  $liter_btns);


	$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

}
else{
	if(isset($_POST['add_polis'])){
		if(isset($_POST['dost'])||!in_array(5,$USER_ROLE)){
			$dost = 1;
		}
		else{
			$dost = 0;
		}
		if($_POST['period_id']>0){
			$srok = 'm';
		}
		else{
			$srok = 'd';
		}
		if($srok == 'd'){
			$date = date("Y-m-d", strtotime($_POST['date_start']));
			$d = new DateTime($date);
			$num = $dbc->element_find('strach_periods',$_POST['period_id']);
			$kol = $num['kol'];
			$d->modify("+".$kol." days");
			$endDate = $d->format("Y-m-d");
		}
		else{
			$date = date("Y-m-d", strtotime($_POST['date_start']));
			$d = new DateTime($date);
			$num = $dbc->element_find('strach_periods',$_POST['period_id']);
			$kol = $num['kol'];
			$d->modify("+".$kol." months");
			$d->modify("-1 days");
			$endDate = $d->format("Y-m-d");
		}

		$_SESSION['bso'] = $_POST['bso'];

		$dbc->element_create("polises",array(
			"oper_id" => ROOT_ID,
			"client_id" => $c_id,
			"bso_number" => $_SESSION['bso'],
			"strach_comp_id" => $_POST['strach_comp_id'],
			"period_id" => $_POST['period_id'],
			"office_id" => $_POST['office_id'],
			"pay_type_id" => $_POST['pay_type_id'],
			"pay_id" => $_POST['pay_id'],
			"premium" => $_POST['premium'],
			"gn" => $_POST['gn'],
			"car_year" => $_POST['car_year'],
			"mark_id" => $_POST['mark_id'][1],
			"model_id" => $_POST['model_id'][1],
			"date_oform" => date("Y-m-d H:i", strtotime($_POST['date_oform'])),
			"date_start" => date("Y-m-d H:i", strtotime($_POST['date_start'])),
			"date_end" => date("Y-m-d H:i", strtotime($endDate)),
			"dost" => $dost,
			"recalc" => 1,
			"sms" => $_POST['sms'],
			"lng_sms" => $_POST['lng_sms'],
			"dost_address" => $_POST['dost_address'],
			"date_dost" => date("Y-m-d H:i", strtotime($_POST['date_dost']))));
		//echo $dbc->outsql.'+';
		$_SESSION['polis'] = $dbc->ins_id;
		//echo $_SESSION['polis'].'+';
		$_SESSION['strach_comp'] = $_POST['strach_comp_id'];
		$polis = $_SESSION['polis'];
		//echo $polis.'+';
		$dbc->element_create("oper_log",array(
			"oper_id" => ROOT_ID,
			"oper_act_type_id" => 4,
			"oper_act_id" => 6,
			"comment" => 'Дата начала: '.date("d-m-Y", strtotime($_POST['date_start']))." Дата окончания: ".date("d-m-Y", strtotime($_POST['date_end']))."'",
			"date_log" => 'NOW()'));
		header("Location: /".getItemCHPU($_GET['menu'], 'pages')."/?polis=".$polis);
		exit;
	}

	$tpl->parse("META_LINK", ".".$moduleName."html");

	$strach_periods = '';
	$rows = $dbc->dbselect(array(
		"table"=>"strach_periods",
		"select"=>"*"));
	foreach($rows as $row){
		if($row['id']==12){
			$strach_periods.= '<option value="'.$row['id'].'" selected="selected">'.$row['title'].'</option>';
		}
		else{
			$strach_periods.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
		}
	}
	$tpl->assign("ADD_STRACH_PERIOD", $strach_periods);

	$pay_types = '';
	$rows = $dbc->dbselect(array(
		"table"=>"pay_types",
		"select"=>"*"));
	foreach($rows as $row){
		$pay_types.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
	}
	$tpl->assign("ADD_PAY_TYPE", $pay_types);

	$pay = '';
	$rows = $dbc->dbselect(array(
		"table"=>"pays",
		"select"=>"*"));
	foreach($rows as $row){
		$pay.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
	}
	$tpl->assign("ADD_PAY", $pay);

	$tpl->assign("SS_DATE_NOW", date("d-m-Y"));

	$tpl->assign("ADD_DATE_START", date("d-m-Y", strtotime("+1 day")));
	$tpl->assign("ADD_DATE_END", date("d-m-Y", strtotime("+1 years")));

	$city_sel = '';
	if(ROOT_OFFICE==1){
		$rows3 = $dbc->dbselect(array(
			"table"=>"offices",
			"select"=>"*"));
		foreach($rows3 as $row3){
			if($row3['id']==ROOT_OFFICE){
				$city_sel.= '<option value="'.$row3['id'].'" selected="1">'.$row3['title'].'</option>';
			}
			else{
				$city_sel.= '<option value="'.$row3['id'].'">'.$row3['title'].'</option>';
			}
		}
	}
	else{
		$rows3 = $dbc->dbselect(array(
			"table"=>"offices",
			"select"=>"*",
			"where"=>"id = ".ROOT_OFFICE,
			"limit"=>1));
		$row3 = $rows3[0];
		$city_sel.= '<option value="'.$row3['id'].'" selected="1">'.$row3['title'].'</option>';
	}
	$tpl->assign("OFFICE_SEL", $city_sel);

	if(!in_array(5,$USER_ROLE)){
		$tpl->assign("DOST_CHECK", ' checked="checked"');
		$tpl->assign("ADD_BSO", $_SESSION['bso']);
		$tpl->assign("ADD_BSO_EDIT", ' readonly="1"');
	}
	else{
		$tpl->assign("DOST_CHECK", '');
		$tpl->assign("ADD_BSO", '');
		$tpl->assign("ADD_BSO_EDIT", '');
	}

	$liter_btns = '';
	$SQL = "SELECT 
			LEFT(make,1) as litera
			FROM salem_models
		GROUP BY litera";
	$rows3 = $dbc->db_free_query($SQL);
	foreach($rows3 as $row3){
		$liter_btns.= '<button type="button" onclick="choiseLiter(\''.$row3['litera'].'\');" class="btn_kasko">'.$row3['litera'].'</button> ';
	}
	$tpl->assign("LITERS_BTN",  $liter_btns);

	$tpl->parse(strtoupper($moduleName), ".".$moduleName."add");
}
	
?>