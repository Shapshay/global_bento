<?php
# SETTINGS #############################################################################
$moduleName = "search_sto";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "result_row" => $prefix . "result_row.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "result" => $prefix . "result.tpl",
));

# MAIN ##################################################################################
$search = false;
if(isset($_POST['telnumber'])){
	ini_set("soap.wsdl_cache_enabled", "0" ); 
	$client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsakkto.1cws?wsdl",
		array( 
		'login' => 'ws', 
		'password' => '123456', //пароль 
		'trace' => true
		) 
	);
	$params["Number"] = $_POST['telnumber'];
	$result = $client->GetClientByPhone($params);
	$array = objectToArray($result);
	$search = true;

	$dbc->element_create("search_client", array(
		"oper_id" => ROOT_ID,
		"query" => "PHONE: ".$_POST['telnumber'],
		"date" => 'NOW()'
	));
}
$tpl->parse("META_LINK", ".".$moduleName."html");
if(!$search){
	$tpl->assign("SEARCH_PHONE", '');
	$tpl->assign("SEARCH_SHOW", '');
}
else{
	$tpl->assign("SEARCH_PHONE", $_POST['telnumber']);
	$i = 0;
    foreach($array['return'] as $clients){
		if(isset($clients[0]['Code1C'])){
			echo "Y1";
            foreach($clients as $client){
				$c_arr = $client;
				$c_id = getClientSTOID($c_arr['Code1C']);
				
				if($c_id==0){
					$dbc->element_create("sto",array(
						"code_1C" => $c_arr['Code1C'],
						"oper_id" => ROOT_ID,
						"name" => $c_arr['Name'],
						"iin" => $c_arr['Iin'],
						"gn" => $c_arr['GosNomer'],
						"pn" => $c_arr['TechPassport'],
						"mark" => $c_arr['Mark'],
						"model" => $c_arr['Model'],
						"born" => $c_arr['GodVypusk'],
						"phone" => $c_arr['Telefon'],
						"email" => $c_arr['Email'],
						"date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
						"comment" => addslashes($c_arr['Comment'])));

					$c_id = $dbc->ins_id;
				}
				else{
                    $dbc->element_update('sto',$c_id,array(
                        "code_1C" => $c_arr['Code1C'],
                        "oper_id" => ROOT_ID,
                        "name" => $c_arr['Name'],
                        "iin" => $c_arr['Iin'],
                        "gn" => $c_arr['GosNomer'],
                        "pn" => $c_arr['TechPassport'],
                        "mark" => $c_arr['Mark'],
                        "model" => $c_arr['Model'],
                        "born" => $c_arr['GodVypusk'],
                        "phone" => $c_arr['Telefon'],
                        "email" => $c_arr['Email'],
                        "date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
                        "comment" => addslashes($c_arr['Comment'])));
				}

				$tpl->assign("RESULT_NAME", $client['Name']);
				$tpl->assign("RESULT_IIN", $client['Iin']);
				$tpl->assign("RESULT_GOSNOMER", $client['GosNomer']);
				$tpl->assign("RESULT_PHONE", $c_arr['Telefon']);
				$tpl->assign("RESULT_URL", "/".getItemCHPU(2216, 'pages')."/?item=".$c_id);
				$tpl->parse("SEARCH_RESULTS", ".".$moduleName."result_row");
				$i++;
			}
		}
		else{
            //echo "Y2";
            $client = $array['return'];
			$c_arr = $client;
			$c_id = getClientSTOID($c_arr['Code1C']);
            //print_r($c_arr);
            if($c_id==0){
                $dbc->element_create("sto",array(
                    "code_1C" => $c_arr['Code1C'],
                    "oper_id" => ROOT_ID,
                    "name" => $c_arr['Name'],
                    "iin" => $c_arr['Iin'],
                    "gn" => $c_arr['GosNomer'],
                    "pn" => $c_arr['TechPassport'],
                    "mark" => $c_arr['Mark'],
                    "model" => $c_arr['Model'],
                    "born" => $c_arr['GodVypusk'],
                    "phone" => $c_arr['Telefon'],
                    "email" => $c_arr['Email'],
                    "date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
                    "comment" => addslashes($c_arr['Comment'])));

                $c_id = $dbc->ins_id;
            }
            else{
                $dbc->element_update('sto',$c_id,array(
                    "code_1C" => $c_arr['Code1C'],
                    "oper_id" => ROOT_ID,
                    "name" => $c_arr['Name'],
                    "iin" => $c_arr['Iin'],
                    "gn" => $c_arr['GosNomer'],
                    "pn" => $c_arr['TechPassport'],
                    "mark" => $c_arr['Mark'],
                    "model" => $c_arr['Model'],
                    "born" => $c_arr['GodVypusk'],
                    "phone" => $c_arr['Telefon'],
                    "email" => $c_arr['Email'],
                    "date_to_end" => date("Y-m-d",strtotime($c_arr['DateOfEnd'])),
                    "comment" => addslashes($c_arr['Comment'])));
            }




            $tpl->assign("RESULT_NAME", $client['Name']);
            $tpl->assign("RESULT_IIN", $client['Iin']);
            $tpl->assign("RESULT_GOSNOMER", $client['GosNomer']);
            $tpl->assign("RESULT_PHONE", $c_arr['Telefon']);
            $tpl->assign("RESULT_URL", "/".getItemCHPU(2216, 'pages')."/?item=".$c_id);
            $tpl->parse("SEARCH_RESULTS", ".".$moduleName."result_row");
            $i++;
            break;

		}
	}
	$tpl->assign("TOTAL_FOUND", $i);
	
	$tpl->parse("SEARCH_SHOW", ".".$moduleName."result");
}

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>