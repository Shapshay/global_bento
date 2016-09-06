<?php
# SETTINGS #############################################################################
$moduleName = "calls_stat";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "grid" => $prefix . "grid.tpl",
));

# MAIN #################################################################################
$tpl->parse("META_LINK", ".".$moduleName."grid");

ini_set("soap.wsdl_cache_enabled", "0" );
$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl",
array(
	'login' => 'ws',
	'password' => '123456',
	'trace' => true
)
);
//$params["Code1C"] = LOGIN_1C;
if(LOGIN_1C=='0515-0347-4'){
	$params["Code1C"] = '0715-0698-02';
}
else{
	$params["Code1C"] = LOGIN_1C;
}

$result = $client->GetMyCalls($params);
$array = objectToArray($result);
$u_arr = $array['return'];

$polises_table = '<table id="stat_table" class="display">
<thead>
	<tr>
		<th class="grey">Дата</th>
		<th class="grey">Клиент</th>
		<th class="grey">Телефоны</th>
		<th class="grey">Статистика</th>
		<th class="grey">Длительность</th>
		<th class="grey">Комментарий</th>
	</tr>
</thead>
<tbody>';
$out_row['result'] = 'OK';
$i = 0;
foreach($u_arr as $row2){
	foreach($row2 as $row){
		$polises_table.= '<tr>
					<td class="grey" align="left">'.date("H:i d-m-Y",strtotime($row['Date'])).'</td>
					<td class="grey" align="left">'.$row['Client'].'</td>
					<td class="grey" align="left">'.$row['Phones'].'</td>
					<td class="grey" align="left">'.$row['Statistics'].'</td>
					<td class="grey" align="left">'.$row['Duration'].'</td>
					<td class="grey" align="left">'.$row['Comments'].'</td>
					</tr>';
	}


}
$polises_table.= '</tbody></table>';


$tpl->assign("STAT_CALLS", $polises_table);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
