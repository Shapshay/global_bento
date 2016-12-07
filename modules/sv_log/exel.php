<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 18.05.2016
 * Time: 10:50
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
require_once("../../inc/Xportxls.php");

######################################################################################################################

$rows = $dbc->dbselect(array(
        "table"=>"calls_log",
        "select"=>"users.name as oper, 
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td,
			SUM(CASE WHEN calls_log.res=5 THEN 1 ELSE 0 END) as zun,
			SUM(CASE WHEN calls_log.res=6 THEN 1 ELSE 0 END) as hz,
			SUM(CASE WHEN calls_log.res=3 THEN 1 ELSE 0 END) as pos,
			SUM(CASE WHEN calls_log.res=1 THEN 1 ELSE 0 END) as nd,
			SUM(CASE WHEN calls_log.res=2 THEN 1 ELSE 0 END) as err,
			SUM(CASE WHEN calls_log.res=7 THEN 1 ELSE 0 END) as 4vpd,
			SUM(CASE WHEN calls_log.res=8 THEN 1 ELSE 0 END) as 4vph",
        "joins"=>"LEFT OUTER JOIN users ON calls_log.oper_id = users.id",
        "where"=>"DATE_FORMAT(date_end, '%Y%m%d') = '".date("Ymd")."' AND users.name<>''",
        "group"=>"oper",
        "order"=>"oper"
    )
);
$numRows = $dbc->count;
$all_td = 0;
$all_zun = 0;
$all_hz = 0;
$all_pos = 0;
$all_nd = 0;
$all_err = 0;
$all_4vpd = 0;
$all_4vph = 0;
$global_call = 0;
if ($numRows > 0) {

    $array[0] = array(
        "oper"=>'Оператор',
        "td"=>'Точная дата',
        "zun"=>'Застраховался у нас',
        "hz"=>'Хочет застраховаться',
        "pos"=>'Позвонить',
        "nd"=>'Недозвонились',
        "err"=>'Ошибка',
        "4vpd"=>'4 вопроса (думает)',
        "4vph"=>'4 вопроса (хочет застраховатся)',
        "all_call"=>'Всего'
    );
    $i = 1;
    foreach($rows as $row){
        $all_call = $row['oper']+$row['td']+$row['zun']+$row['hz']+$row['pos']+$row['nd']+$row['err']+$row['4vpd']+$row['4vph'];
        $all_td+= $row['td'];
        $all_zun+= $row['zun'];
        $all_hz+= $row['hz'];
        $all_pos+= $row['pos'];
        $all_nd+= $row['nd'];
        $all_err+= $row['err'];
        $all_4vpd+= $row['4vpd'];
        $all_4vph+= $row['4vph'];
        $global_call+= $all_call;
        $array[$i] = array(
            "oper"=>$row['oper'],
            "td"=>$row['td'],
            "zun"=>$row['zun'],
            "hz"=>$row['hz'],
            "pos"=>$row['pos'],
            "nd"=>$row['nd'],
            "err"=>$row['err'],
            "4vpd"=>$row['4vpd'],
            "4vph"=>$row['4vph'],
            "all_call"=>$all_call
        );

        $i++;
    }
}

$array[$i] = array(
    "oper"=>'ИТОГО:',
    "td"=>$all_td,
    "zun"=>$all_zun,
    "hz"=>$all_hz,
    "pos"=>$all_pos,
    "nd"=>$all_nd,
    "err"=>$all_err,
    "4vpd"=>$all_4vpd,
    "4vph"=>$all_4vph,
    "all_call"=>$global_call
);
/*

$tpl->assign("ALL_TD", $all_td);
$tpl->assign("ALL_ZUN", $all_zun);
$tpl->assign("ALL_HZ",$all_hz);
$tpl->assign("ALL_POS", $all_pos);
$tpl->assign("ALL_ND", $all_nd);
$tpl->assign("ALL_ERR", $all_err);
$tpl->assign("ALL_4VPD", $all_4vpd);
$tpl->assign("ALL_4VPH",$all_4vph);
$tpl->assign("ALL_COUNT_CALL", $global_call);


$array[0] = array("id"=>"id","name"=>"Name","nation"=>"Country","ocupation"=>"ocupation");
$array[1] = array("id"=>"2","name"=>"Juan Perez","nation"=>"GT");
$array[2] = array("id"=>"3","name"=>"Pedro Partos","nation"=>"GT","aa"=>"12314");
$array[3] = array("id"=>"4","name"=>"Peter Parker","nation"=>"GT","aa"=>"12314");
*/

$obj= new Xportxls($array,true);
$obj->genString(true);
//print_r($array);