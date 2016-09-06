<?php
	# SETTINGS #############################################################################
	$moduleName = "car_cost";
	$prefix = "./modules/".$moduleName."/";
	
	$tpl->define(array(
			$moduleName => $prefix . $moduleName.".tpl",
			$moduleName . "result" => $prefix . "result.tpl",
			$moduleName . "html" => $prefix . "html.tpl",
	));
	# MAIN #################################################################################
	$tpl->parse("META_LINK", ".".$moduleName."html");
	
	if(isset($_POST['Calculate'])){
		//print_r($_POST);
		$url = 'http://melchior.kz/api/getSI?hash='. md5(date("d.m.Y")).'&make_id='.$_POST['mark_id'][1].'&model_id='.$_POST['model_id'][1].'&year='.$_POST['year'];
		//echo '<p>'.$url;
		$result = get_web_page( $url );
		$html2 = $result['content']; 
		//echo '<p>'.$html2;
		
		$obj=json_decode($html2);
		
		$tpl->assign("R_MARK",  $_POST['mark'][1]);
		$tpl->assign("R_MODEL",  $_POST['model'][1]);
		$tpl->assign("R_YEAR",  $_POST['year']);
		$tpl->assign("R_COST",  number_format ($obj->summ , 0 , "." ,  " " ));
		
		$tpl->parse("COST_RESULT", ".".$moduleName."result");
	}
	else{
		$tpl->assign("COST_RESULT",  '');
	}
	
	$year_btns = '';
	for($i = date("Y"); $i > (date('Y')-17); $i--){
		$year_btns.= '<button type="button" onclick="choiseYear('.$i.');">'.$i.'</button> ';
	}
	$tpl->assign("YEARS_BTN",  $year_btns);
	
	$liter_btns = '';
	$SQL = "SELECT 
			LEFT(make,1) as litera
			FROM salem_models
		GROUP BY litera";
	$result3 = mysql_query($SQL);
	while($row3 = mysql_fetch_array($result3)){
		$liter_btns.= '<button type="button" onclick="choiseLiter(\''.$row3['litera'].'\');">'.$row3['litera'].'</button> ';
	}
	$tpl->assign("LITERS_BTN",  $liter_btns);
	
	$tpl->assign("NOW_DATE",  md5(date("d.m.Y")));
	
	
	
	$tpl->parse(strtoupper($moduleName), $moduleName);
?>