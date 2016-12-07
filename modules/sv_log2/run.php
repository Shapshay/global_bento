<?php
# SETTINGS #############################################################################

$moduleName = "sv_log2";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
		$moduleName . "html" => $prefix . "html.tpl",
		$moduleName . "stat_row" => $prefix . "stat_row.tpl",
        $moduleName . "item" => $prefix . "item.tpl",
));

# MAIN #################################################################################
$tpl->parse("META_LINK", ".".$moduleName."html");

if(!isset($_GET['item'])){
    // table
    $num_day = (date('w'));
    if($num_day==1){
        $minus_days = "-3 days";
    }
    else{
        $minus_days = "-1 days";
    }
    $rows = $dbc->dbselect(array(
            "table"=>"ver_log",
            "select"=>"ver_log.id as ver, 
		    calls_log.oper_id as oper_id, 
			users.name as oper, 
			ratings1.title as rating1, 
			ratings2.title as rating2, 
			ver_log.ver_comment as ver_comment,
			ver_log.control_log_id as control_log_id",
            "joins"=>"LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id 
		    LEFT OUTER JOIN users ON calls_log.oper_id = users.id 
			LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id 
			LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id 
			LEFT OUTER JOIN control_log ON ver_log.control_log_id = control_log.id",
            "where"=>"users.office_id = ".ROOT_OFFICE." AND 
			DATE_FORMAT(ver_log.ver_date,'%Y%m%d') < '".date("Ymd", strtotime($minus_days))."' AND 
			ver_log.ver_obrab = 1 AND
			ver_log.super_obrab = 0 AND
			control_log.control = 0",
            "order"=>"oper ASC"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $i = 1;
        $tmp = 0;
        foreach($rows as $row){
            $errs = '';
            if($row['oper_id']!=$tmp){
                $i = 1;
                $tmp = $row['oper_id'];
            }

            $rows2= $dbc->dbselect(array(
                    "table"=>"control_err_log",
                    "select"=>"errs.title as err",
                    "joins"=>"LEFT OUTER JOIN errs ON control_err_log.err_id = errs.id ",
                    "where"=>"control_log_id = ".$row['control_log_id']
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $j = 1;
                foreach($rows2 as $row2){
                    $errs.= $j.') '.$row2['err'].'<br>';
                    $j++;
                }
            }
            else{
                $errs = '---';
            }

            $tpl->assign("ITEM_ID", $row['ver']);
            $tpl->assign("SV_OPER", $row['oper']);
            $tpl->assign("SV_NUM", $i);
            $tpl->assign("SV_RATING", $row['rating1'].' -> '.$row['rating2']);
            $tpl->assign("SV_ERRS", $errs);
            $tpl->assign("SV_VER_COMMENT", $row['ver_comment']);


            $tpl->parse("STAT_ROWS", ".".$moduleName."stat_row");
            $i++;
        }
    }
    else{
        $tpl->assign("STAT_ROWS", '<tr><td colspan=9 align=center>Нет данных в этом периоде!</td></tr>');
    }

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

}
else{
    // item
    $rows2 = $dbc->dbselect(array(
            "table" => "ver_log",
            "select" => "ver_log.id as ver,
            ver_log.add_field_txt as add_field_txt,
            ver_log.add_field as add_field,
            ver_log.ver_comment as ver_comment,
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
            clients.id as c_id,
            ver_log.control_log_id as control_log_id",
            "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
            LEFT OUTER JOIN users ON calls_log.oper_id = users.id
            LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
            LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
            LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
            LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
            LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
            "where" => "ver_log.id = ".$_GET['item'],
            "limit" => 1
        )
    );
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
    $tpl->assign("VER_COMMENT", $row['ver_comment']);
    $tpl->assign("CALL_DATE", $row['date_start'].' - '.$row['date_end']);
    $tpl->assign("CLIENT_TD", date("d-m-Y",strtotime($row['td'])));
    if($row['add_field']==1){
        $tpl->assign("EDT_ADD_FIELD", 'Да');
    }
    else{
        $tpl->assign("EDT_ADD_FIELD", 'Нет');
    }


    $rows2= $dbc->dbselect(array(
            "table"=>"control_err_log",
            "select"=>"errs.title as err",
            "joins"=>"LEFT OUTER JOIN errs ON control_err_log.err_id = errs.id ",
            "where"=>"control_log_id = ".$row['control_log_id']
        )
    );
    $numRows = $dbc->count;
    $errs = '';
    if ($numRows > 0) {
        $j = 1;
        foreach($rows2 as $row2){
            $errs.= $j.') '.$row2['err'].'<br>';
            $j++;
        }
    }
    else{
        $errs = '---';
    }
    $tpl->assign("OPER_ERRS", $errs);

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

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."item");
}



?>
