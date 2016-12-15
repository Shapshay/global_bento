<?php
# SETTINGS #############################################################################

$moduleName = "auto_sv_btn";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "html" => $prefix . "html.tpl",
));

function getCountSvDay($office_id, $minus_day, $type){
    global $dbc;
    $res_arr = array();
    if($type==0){
        $znak = "=";
    }
    else{
        $znak = "<";
    }
    $rows = $dbc->dbselect(array(
            "table"=>"ver_log",
            "select"=>"SUM(CASE WHEN ver_log.super_obrab=1 THEN 1 ELSE 0 END) as ob_row,
                COUNT(ver_log.id) as all_row",
            "joins"=>"LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id 
                LEFT OUTER JOIN users ON calls_log.oper_id = users.id 
                LEFT OUTER JOIN control_log ON ver_log.control_log_id = control_log.id",
            "where"=>"users.office_id = ".$office_id." AND 
                DATE_FORMAT(ver_log.ver_date,'%Y%m%d') ".$znak." '".date("Ymd",strtotime($minus_day))."' AND 
                ver_log.ver_obrab = 1 AND
                control_log.control = 0"
        )
    );
    //echo $dbc->outsql."<p>";
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row = $rows[0];
        if($row['ob_row']!=null){
            $res_arr[0] = $row['ob_row'];
        }
        else{
            $res_arr[0] = 0;
        }

        $res_arr[1] = $row['all_row'];
    }
    else{
        $res_arr[0] = 0;
        $res_arr[1] = 0;
    }
    return $res_arr;
}
# MAIN #################################################################################

$tpl->parse("META_LINK", ".".$moduleName."html");

$num_day = (date('w'));
if($num_day==1){
    $minus_days = "-3 days";
}
else{
    $minus_days = "-1 days";
}
//$minus_days = "-0 days";
$btn1 = getCountSvDay(ROOT_OFFICE, $minus_days, 0);
$tpl->assign("AUTO1_COUNT", $btn1[0].' / '.$btn1[1]);

$btn2 = getCountSvDay(ROOT_OFFICE, $minus_days, 1);
$tpl->assign("AUTO2_COUNT", $btn2[0].' / '.$btn2[1]);

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
