<?php
# SETTINGS #############################################################################

$moduleName = "sv_log";

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
    //$minus_days = "-0 days";
    /*$rows = $dbc->dbselect(array(
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
			DATE_FORMAT(ver_log.ver_date,'%Y%m%d') = '".date("Ymd", strtotime($minus_days))."' AND 
			ver_log.ver_obrab = 1 AND
			ver_log.super_obrab = 0 AND
			control_log.control = 0",
            "order"=>"oper ASC"
        )
    );*/
    $rows = $dbc->dbselect(array(
            "table"=>"ver_log",
            "select"=>"ver_log.*",
            "joins"=>"LEFT OUTER JOIN users ON ver_log.oper_id = users.id 
			LEFT OUTER JOIN control_log ON ver_log.control_log_id = control_log.id",
            "where"=>"users.office_id = ".ROOT_OFFICE." AND 
			DATE_FORMAT(ver_log.ver_date,'%Y%m%d') = '".date("Ymd", strtotime($minus_days))."' AND 
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

            $tpl->assign("ITEM_ID", $row['id']);
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
            "select" => "*",
            "where" => "id = ".$_GET['item'],
            "limit" => 1
        )
    );
    $row = $rows2[0];
    $tpl->assign("VER_COMMENT", $row['ver_comment']);
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

    $tpl->assign("OPER_NAME", $row['oper']);
    $tpl->assign("CALL_RATING", $row['rating1'].' -> '.$row['rating2']);
    $tpl->assign("OPER_ID", $row['oper_id']);
    $tpl->assign("RES", $row['res']);
    $tpl->assign("RES_ID", $row['res_id']);
    $tpl->assign("CALL_PHONE", $row['phone']);
    $tpl->assign("ADD_FIELD_TXT", $row['add_field_txt']);
    $tpl->assign("VER_ID", $row['id']);
    $tpl->assign("AUDIO_LINK", $row['link']);
    $tpl->assign("CALL_DATE", $row['call_date_start'].' - '.$row['call_date_end']);
    $tpl->assign("AUTO_TYPE", $row['auto_type']);
    $tpl->assign("CLIENT_TD", date("d-m-Y",strtotime($row['td'])));
    $tpl->assign("EDT_NAME", $row['c_name']);
    $tpl->assign("EDT_IIN", $row['c_iin']);
    $tpl->assign("EDT_EMAIL", $row['c_email']);
    $tpl->assign("EDT_COMMENT", $row['c_comment']);
    $tpl->assign("EDT_PREMIUM", $row['c_premium']);
    $tpl->assign("EDT_REAL_PREMIUM", $row['c_real_premium']);
    $tpl->assign("EDT_GN", $row['c_gn']);
    for($i=1;$i<=5;$i++){
        $tpl->assign("EDT_DOP_IIN".$i, $row['c_dop_iin'.$i]);
    }
    for($i=1;$i<=3;$i++){
        $tpl->assign("EDT_DOP_GN".$i, $row['c_dop_gn'.$i]);
    }
    if($row['c_is_car']==1){
        $tpl->assign("EDT_CAR", 'Да');
    }
    else{
        $tpl->assign("EDT_CAR", 'Нет');
    }
    if($row['c_is_dost']==1){
        $tpl->assign("EDT_4VP_DOST", 'Да');
    }
    else{
        $tpl->assign("EDT_4VP_DOST", 'Нет');
    }
    if($row['c_is_yur']==1){
        $tpl->assign("EDT_4VP_YUR", 'Да');
    }
    else{
        $tpl->assign("EDT_4VP_YUR", 'Нет');
    }
    if($row['c_is_ev']==1){
        $tpl->assign("EDT_4VP_EV", 'Да');
    }
    else{
        $tpl->assign("EDT_4VP_EV", 'Нет');
    }
    if($row['c_is_korgau']==1){
        $tpl->assign("EDT_4VP_KORGAU", 'Да');
    }
    else{
        $tpl->assign("EDT_4VP_KORGAU", 'Нет');
    }
    $tpl->assign("EDT_CITY", $row['c_city']);
    $tpl->assign("EDT_4VP_STRAH", $row['c_strach_company']);

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."item");
}



?>
