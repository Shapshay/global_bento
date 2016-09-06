<?php
	# SETTINGS #############################################################################
	$moduleName = "epolis";
	$prefix = "./modules/".$moduleName."/";
	
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "ch_row" => $prefix . "ch_row.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
	));
	# MAIN #################################################################################
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	if(isset($_POST['Calculate'])){
		//print_r($_POST);
		$BasePremium = 1.9;
		$MRP = 2121;
		
		// coef cars
		$CarsCoefArr = array();
		for($i=1;$i<=sizeof($_POST['tf_region']);$i++){
			$CarsCoefArr[$i] = $_POST['tf_region'][$i]*$_POST['tf_age'][$i]*$_POST['tf_type'][$i];
			if(!isset($_POST['isBigCity'][$i])){
				$CarsCoefArr[$i]*=0.8;	
			}
		}
		$CarsCoef = max($CarsCoefArr);
		
		// coef clients
		$clientsStr = '';
		$ClientsCoefArr = array();
		for($i=1;$i<=sizeof($_POST['person_type']);$i++){
			$ClientsCoefArr[$i] = $_POST['age_experience'][$i]*$_POST['client_class'][$i];
			$clientsStr.= $_POST['client_name'][$i]." : ".$_POST['class_name'][$i]."<br>";
			if(isset($_POST['isDiscount'][$i])){
				$ClientsCoefArr[$i]/=2;	
			}
		}
		$ClientCoef = max($ClientsCoefArr);
		
		$Premium = $MRP*$BasePremium*$CarsCoef*$ClientCoef;
		
		//coef days
		$days = date('L')?366:365;
		$dayPrem = $Premium/$days;
		//echo "<p>".$dayPrem;
		//$datetime1 = date_create('2009-10-11');
		//$datetime2 = date_create('2009-10-13');
		$datetime1 = date_create($_POST['date_start']);
		$datetime2 = date_create($_POST['date_end']);
		$interval = date_diff($datetime1, $datetime2);
		//echo "<p>".$interval->format('%R%a дней');
		//echo "<p>".ceil($dayPrem*$interval->format('%a')+($dayPrem*2));
		
		$itogPrem = ceil($dayPrem*$interval->format('%a')+($dayPrem*2));
		$itogPrem = ceil($itogPrem/50) * 50;
		
		$tpl->assign("CLIENTS_LIST",  $clientsStr);
		$tpl->assign("ITOG_PREM",  '<b>Страховая премия: '.$itogPrem.'</b><p>Расчитать еще один полис:<br>');
	}
	else{
		$tpl->assign("ITOG_PREM",  '');
		$tpl->assign("CLIENTS_LIST",  '');
	}
	
	$tpl->assign("NOW_DATE",  date("d-m-Y", strtotime("+1 day")));
	$tpl->assign("END_DATE",  date("d-m-Y", strtotime("+1 year")));
	
	
	$tpl->parse(strtoupper($moduleName), $moduleName);
?>