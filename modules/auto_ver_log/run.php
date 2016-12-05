<?php
# SETTINGS #############################################################################

$moduleName = "auto_ver_log";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "html" => $prefix . "html.tpl",
    $moduleName . "err_title" => $prefix . "err_title.tpl",
    $moduleName . "err_row" => $prefix . "err_row.tpl",
));

# MAIN #################################################################################

//Verification errors
$rows = $dbc->dbselect(array(
		"table"=>"errs",
		"select"=>"*",
		"where"=>"parent_id = 0"
	)
);
$num = 1;
$err_arr = '';
foreach($rows as $row) {
	$tpl->assign("ERR_PARENT_TITLE", $row['title']);
	$rows2 = $dbc->dbselect(array(
        "table" => "errs",
        "select" => "*",
        "where" => "parent_id = " . $row['id']
    )
);
//echo "\n";
$col = 1;
foreach ($rows2 as $row2) {
    $tpl->assign("ERR_ID", $row2['id']);
    $tpl->assign("ERR_TITLE", $row2['title']);
    //echo "<br>".$col;
    if($col == 3){
        $tpl->assign("ERR_TR", '</tr><tr>');
        $col = 0;
    }
    else{
        $tpl->assign("ERR_TR", '');
    }
    $err_arr.= $row2['id'].',';
    $tpl->parse("ERR_ROWS", ".".$moduleName."err_row");
    $col++;

}
if($num<8){
    $tpl->parse("ERR_SELS", ".".$moduleName."err_title");
}
else{
    $tpl->parse("ERR_SELS2", ".".$moduleName."err_title");
}
$num++;
$tpl->clear("ERR_ROWS");
}
$tpl->assign("ERR_ARR", substr($err_arr, 0, -1));

// Выдача звонка на прослушку

$rows2 = $dbc->dbselect(array(
        "table" => "ver_log",
        "select" => "ver_log.id as ver,
            ver_log.add_field_txt as add_field_txt,
            calls_log.oper_id as oper_id,
            users.name as oper,
            calls_log.date_start as date_start,
            calls_log.date_end as date_end,
            res_calls.id as res_id,
            res_calls.title as res,
            ratings1.title as rating1,
            ratings2.title as rating2,
            oper_calls.link as link,
            oper_calls.phone1 as phone,
            clients.date_end as td,
            clients.id as c_id",
        "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
            LEFT OUTER JOIN users ON calls_log.oper_id = users.id
            LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
            LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
            LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
            LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
            LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
        "where" => "ver_log.ver_id = ".ROOT_ID." AND
            DATE_FORMAT(ver_log.ver_date,'%Y%m%d') = '".date("Ymd")."' AND
            ver_log.auto_type = ".$_GET['act']." AND 
            ver_log.ver_obrab = 0",
        "order"=>"ver_log.id",
        "order_type"=>"ASC",
        "limit" => 1
    )
);
//echo $dbc->outsql;
$row = $rows2[0];
$tpl->assign("OPER_NAME", $row['oper']);
$tpl->assign("CALL_RATING", $row['rating1'].' -> '.$row['rating2']);
$tpl->assign("OPER_ID", $row['oper_id']);
$tpl->assign("RES", $row['res']);
$tpl->assign("RES_ID", $row['res_id']);
$tpl->assign("CALL_PHONE", $row['phone']);
$tpl->assign("ADD_FIELD_TXT", $row['add_field_txt']);
$tpl->assign("VER_ID", $row['ver']);
$tpl->assign("AUDIO_LINK", $row['link']);
$tpl->assign("CALL_DATE", $row['date_start'].' - '.$row['date_end']);
$tpl->assign("AUTO_TYPE", $_GET['act']);
$tpl->assign("CLIENT_TD", date("d-m-Y",strtotime($row['td'])));



$row2 = $dbc->element_find('clients',$row['c_id']);
$tpl->assign("EDT_NAME", $row2['name']);
$tpl->assign("EDT_IIN", $row2['iin']);
$tpl->assign("EDT_EMAIL", $row2['email']);
$tpl->assign("EDT_COMMENT", $row2['comment']);
$tpl->assign("EDT_PREMIUM", $row2['premium']);
$tpl->assign("EDT_REAL_PREMIUM", $row2['real_premium']);
$tpl->assign("EDT_GN", $row2['gn']);
for($i=1;$i<=5;$i++){
    $tpl->assign("EDT_DOP_IIN".$i, $row2['dop_iin'.$i]);
}
for($i=1;$i<=3;$i++){
    $tpl->assign("EDT_DOP_GN".$i, $row2['dop_gn'.$i]);
}
$tpl->assign("EDT_COMMENT", $row2['comment']);
if($row2['is_car']==1){
    $tpl->assign("EDT_CAR", 'Да');
}
else{
    $tpl->assign("EDT_CAR", 'Нет');
}
if($row2['is_dost']==1){
    $tpl->assign("EDT_4VP_DOST", 'Да');
}
else{
    $tpl->assign("EDT_4VP_DOST", 'Нет');
}
if($row2['is_yur']==1){
    $tpl->assign("EDT_4VP_YUR", 'Да');
}
else{
    $tpl->assign("EDT_4VP_YUR", 'Нет');
}
if($row2['is_ev']==1){
    $tpl->assign("EDT_4VP_EV", 'Да');
}
else{
    $tpl->assign("EDT_4VP_EV", 'Нет');
}
if($row2['is_korgau']==1){
    $tpl->assign("EDT_4VP_KORGAU", 'Да');
}
else{
    $tpl->assign("EDT_4VP_KORGAU", 'Нет');
}
$tpl->assign("EDT_CITY", getItemTitle('city', $row2['city']));
$tpl->assign("EDT_4VP_STRAH", getItemTitle('strach_company', $row2['strach_id']));




$dbc->element_update('ver_log',$row['ver'],array(
    "ver_comment" => '',
    "ver_date_start" => 'NOW()'));


$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
