<?php
# SETTINGS #############################################################################

$moduleName = "dostavka";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "graf" => $prefix . "graf.tpl",
		$moduleName . "dost1_row" => $prefix . "dost1_row.tpl",
		$moduleName . "dost2_row" => $prefix . "dost2_row.tpl",
));

# MAIN #################################################################################
// error
if(isset($_POST['polis_ot_cour'])&&($_POST['polis_ot_cour']==0)&&isset($_POST['PolCheck2'])){
	$pol_arr = '';
	foreach($_POST['PolCheck2'] as $v){
		$row2 = $dbc->element_find('polises',$v);
		if($row2['status']!=8){
			$dbc->element_create("alert_mobil_cour",array(
				"cour_id" => $_POST['c_id'],
				"bso" => $row2['bso_number'],
				"status" => $row2['status'],
				"date" => 'NOW()'));
		}

		$dbc->element_update('polises',$v,array(
			"status" => 9));
		$SQL = "UPDATE cour_polis SET
			stat_ok = 2
			WHERE 
			c_id = ".$_POST['c_id']." AND 
			polis_id = ".$v;
		$dbc->element_free_update($SQL);
		$pol_arr.= $v.',';


	}
	echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?pdf_type=c_p_err&c_id='.$_POST['c_id'].'&pol_arr='.$pol_arr.'");</script>';
}
//inkass
if(isset($_POST['polis_ot_cour'])&&($_POST['polis_ot_cour']==1)&&isset($_POST['PolCheck2'])){
	$pol_arr = '';
	foreach($_POST['PolCheck2'] as $v){

		ini_set("soap.wsdl_cache_enabled", "0" );
		$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
			array(
				'login' => 'ws',
				'password' => '123456',
				'trace' => true
			)
		);
		//$params["Code1C"] = LOGIN_1C;
		$rows = $dbc->dbselect(array(
				"table"=>"cour_polis",
				"select"=>"users.login_1C as cour_1C,
			        polises.bso_number as bso_number",
				"joins"=>"LEFT OUTER JOIN users ON users.id = cour_polis.c_id
                    LEFT OUTER JOIN polises ON polises.id = cour_polis.polis_id",
				"where"=>"cour_polis.c_id = ".$_POST['c_id']." AND cour_polis.polis_id = ".$v,
				"limit"=>1
			)
		);
		$row = $rows[0];
		$params["polic_numbers"]["PolicNumber"] = $row['bso_number'];
		$params["manager_code"] = $row['cour_1C'];
		$result = $client->PolicCash($params);
		$array_save = objectToArray($result);
		$res_save_1c = $array_save['return'];
		if($res_save_1c=='Успешно'){
		
		
		
		
		
		
		
		
		
			$row = $dbc->element_find('polises',$v);
			ini_set("soap.wsdl_cache_enabled", "0" );
			$client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
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
			$res_sum = $array_info['return']['PolicInfo']['Summa'];
			$dbc->element_update('polises',$v,array(
				"summa" => $res_sum));
			$row2 = $dbc->element_find('polises',$v);
			if($row2['status']!=7){
				$dbc->element_create("alert_mobil_cour",array(
					"cour_id" => $_POST['c_id'],
					"bso" => $row2['bso_number'],
					"status" => $row2['status'],
					"date" => 'NOW()'));
			}
	
	
			$dbc->element_update('polises',$v,array(
				"status" => 4,
				"date_kassa" => 'NOW()'));
			$SQL = "UPDATE cour_polis SET
				stat_ok = 1
				WHERE 
				c_id = ".$_POST['c_id']." AND 
				polis_id = ".$v;
			$dbc->element_free_update($SQL);
			$pol_arr.= $v.',';
		
		
		}
        else{
            print_r($res_save_1c);
            break;
        }
        

    }
	echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?pdf_type=c_p_in&c_id='.$_POST['c_id'].'&pol_arr='.$pol_arr.'");</script>';
}
// clear
if(isset($_POST['polis_ot_cour'])&&($_POST['polis_ot_cour']==2)&&isset($_POST['PolCheck2'])){
	$pol_arr = '';
	foreach($_POST['PolCheck2'] as $v){
        ini_set("soap.wsdl_cache_enabled", "0" );
        $client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
            array(
                'login' => 'ws',
                'password' => '123456',
                'trace' => true
            )
        );
        //$params["Code1C"] = LOGIN_1C;
        $rows = $dbc->dbselect(array(
                "table"=>"cour_polis",
                "select"=>"users.login_1C as cour_1C,
			        polises.bso_number as bso_number",
                "joins"=>"LEFT OUTER JOIN users ON users.id = cour_polis.c_id
                    LEFT OUTER JOIN polises ON polises.id = cour_polis.polis_id",
                "where"=>"cour_polis.c_id = ".$_POST['c_id']." AND cour_polis.polis_id = ".$v,
                "limit"=>1
            )
        );
        $row = $rows[0];
        $params["polic_number"] = $row['bso_number'];
        $params["manager_code"] = $row['cour_1C'];
        $params["clear"] = 1;
		//print_r($params);
        $result = $client->ClearSetPolicCurier($params);
		$dbc->element_update('polises',$v,array(
			"status" => 2,
			"date_indost" => '0000-00-00 00:00:00'));
		$SQL = "DELETE FROM cour_polis 
			WHERE 
			c_id = ".$_POST['c_id']." AND 
			polis_id = ".$v;
		$dbc->db_free_del($SQL);
		$pol_arr.= $v.',';
	}
	echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?pdf_type=c_p_clear&c_id='.$_POST['c_id'].'&pol_arr='.$pol_arr.'");</script>';
}

// верификация
if(isset($_POST['polis_ot_cour'])&&($_POST['polis_ot_cour']==3)&&isset($_POST['PolCheck2'])){
    $pol_arr = '';
    foreach($_POST['PolCheck2'] as $v){
        $dbc->element_create("polises_ver",array(
            "c_id" => $_POST['c_id'],
            "polis_id" => $v,
            "oper_id" => ROOT_ID,
            "date_ver" => 'NOW()'));
        $pol_arr.= $v.',';
    }
}

// in dost
if(isset($_POST['polis_to_cour'])&&isset($_POST['PolCheck'])){
	$pol_arr = '';
	foreach($_POST['PolCheck'] as $v){
        $row = $dbc->element_find('polises',$v);
        ini_set("soap.wsdl_cache_enabled", "0" );
        $client2 = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
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
        $res_sum = $array_info['return']['PolicInfo']['Summa'];
		$dbc->element_update('polises',$v,array(
			"status" => 3,
            "summa" => $res_sum,
			"date_indost" => 'NOW()'));
		$dbc->element_create("cour_polis",array(
			"c_id" => $_POST['c_id'],
			"polis_id" => $v,
			"date" => 'NOW()'));
        ini_set("soap.wsdl_cache_enabled", "0" );
        $client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
            array(
                'login' => 'ws',
                'password' => '123456',
                'trace' => true
            )
        );
        //$params["Code1C"] = LOGIN_1C;
        $rows = $dbc->dbselect(array(
                "table"=>"cour_polis",
                "select"=>"users.login_1C as cour_1C,
			        polises.bso_number as bso_number",
                "joins"=>"LEFT OUTER JOIN users ON users.id = cour_polis.c_id
                    LEFT OUTER JOIN polises ON polises.id = cour_polis.polis_id",
                "where"=>"cour_polis.c_id = ".$_POST['c_id']." AND cour_polis.polis_id = ".$v,
                "limit"=>1
            )
        );
        $row = $rows[0];
        $params["polic_number"] = $row['bso_number'];
        $params["manager_code"] = $row['cour_1C'];
        $params["clear"] = 0;
		//print_r($params);
        $result = $client->ClearSetPolicCurier($params);
		$pol_arr.= $v.',';
	}
	echo '<script type="text/javascript">window.open("http://'.$_SERVER['HTTP_HOST'].'/inc/ajax/pdf.php?pdf_type=c_p&c_id='.$_POST['c_id'].'&pol_arr='.$pol_arr.'");</script>';
}




$city_sel = '';
$rows3 = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"users.*,
			r_user_role.role_id",
		"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id",
		"where"=>"users.office_id = ".ROOT_OFFICE." AND (r_user_role.role_id = 7 OR r_user_role.role_id = 5)"
	)
);
foreach($rows3 as $row3){
	$city_sel.= '<option value="'.$row3['id'].'">'.$row3['name'].'</option>';
}
$tpl->assign("COURIER_SEL", $city_sel);

$rows3 = $dbc->dbselect(array(
		"table"=>"polises",
		"select"=>"polises.id as id, 
			polises.bso_number as bso_number,
			polises.dost_address as dost_address,
			polises.date_print as date_print,
			users.name as oper",
		"joins"=>"LEFT OUTER JOIN users ON polises.oper_id = users.id ",
		"where"=>"polises.status = 2 AND 
			polises.office_id = '".ROOT_OFFICE."'
			 AND polises.dost = 1
			 AND DATE_FORMAT(polises.date_write,'%Y%m%d')>20160821",
		"order"=>"polises.date_write"
	)
);
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows3 as $row3){
		$tpl->assign("DOST1_ID", $row3['id']);
		$tpl->assign("DOST1_POLIS_NUM", $row3['bso_number']);
		$tpl->assign("DOST1_OPER", $row3['oper']);
		$tpl->assign("DOST1_ADRES", $row3['dost_address']);
        $date_err = date("YmdHi",strtotime(date("YmdHi", strtotime($row3['date_print'])) . " + 3 day"));
        if($date_err<date("YmdHi")){
            $tpl->assign("DOST1_COLOR", ' style="color: red; font-weight: bold;"');
        }
        else{
            $tpl->assign("DOST1_COLOR", '');
        }
		
		$tpl->parse("DOST1_ROWS", ".".$moduleName."dost1_row");
	}
}
else{
	$tpl->assign("DOST1_ROWS", '');
}

$rows3 = $dbc->dbselect(array(
		"table"=>"cour_polis",
		"select"=>"polises.id as id, 
			polises.bso_number as bso_number,
			polises.dost_address as dost_address,
			polises.date_indost as date_indost,
			polises.summa as summa,
			users.name as oper,
			c_users.name as cour,
			polis_status.title as status,
			IFNULL(err_types.title,'---') as cour_err",
		"joins"=>"LEFT OUTER JOIN users AS c_users ON cour_polis.c_id = c_users.id 
			LEFT OUTER JOIN polises ON cour_polis.polis_id = polises.id
			LEFT OUTER JOIN users ON polises.oper_id = users.id
			LEFT OUTER JOIN polis_status ON polises.status = polis_status.id
			LEFT OUTER JOIN err_types ON polises.type_cour_err = err_types.id",
		"where"=>"(polises.status = 3 OR polises.status = 7 OR polises.status = 8) AND 
			polises.office_id = '".ROOT_OFFICE."'
			 AND polises.dost = 1
			 AND DATE_FORMAT(polises.date_write,'%Y%m%d')>20160821",
		"order"=>"polises.date_write"
	)
);
//echo $dbc->outsql;
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows3 as $row3){
		$tpl->assign("DOST2_ID", $row3['id']);
		$tpl->assign("DOST2_POLIS_NUM", $row3['bso_number']);
        $tpl->assign("DOST2_STATUS", $row3['status']);
        $tpl->assign("DOST2_CUR_ERR", $row3['cour_err']);
		$tpl->assign("DOST2_OPER", $row3['oper']);
        $tpl->assign("DOST2_SUM", $row3['summa']);
		$tpl->assign("DOST2_COUR", $row3['cour']);
		$tpl->assign("DOST2_ADRES", $row3['dost_address']);

        $date_err = date("YmdHi",strtotime(date("YmdHi", strtotime($row3['date_indost'])) . " + 3 day"));
        if($date_err<date("YmdHi")){
            $tpl->assign("DOST2_COLOR", ' style="color: red; font-weight: bold;"');
        }
        else{
            $tpl->assign("DOST2_COLOR", '');
        }
		
		$tpl->parse("DOST2_ROWS", ".".$moduleName."dost2_row");
	}
}
else{
	$tpl->assign("DOST2_ROWS", '');
}

$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
	
?>
