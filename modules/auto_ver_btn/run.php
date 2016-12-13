<?php
# SETTINGS #############################################################################

$moduleName = "auto_ver_btn";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "html" => $prefix . "html.tpl",
));

function getCountVerDay($auto_type, $ver_id){
    global $dbc;
    $res_arr = array();
    $rows = $dbc->dbselect(array(
            "table"=>"ver_log",
            "select"=>"SUM(CASE WHEN ver_obrab=1 THEN 1 ELSE 0 END) as res1,
                COUNT(id) as res2",
            "where"=>"DATE_FORMAT(ver_date,'%Y%m%d')='".date("Ymd")."' AND
                ver_id = ".$ver_id." AND 
                auto_type = ".$auto_type
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row = $rows[0];
        $res_arr[0] = $row['res1'];
        $res_arr[1] = $row['res2'];
    }
    else{
        $res_arr[0] = 0;
        $res_arr[1] = 0;
    }
    return $res_arr;
}

# MAIN #################################################################################
/*
$add_field_txt = 'Дополнительное поле';

$num_day = (date('w'));

if($num_day==1){
    $minus_days = "-3 days";
}
else{
    $minus_days = "-1 days";
}*/

// Auto 1
$auto1 = getCountVerDay(1, ROOT_ID);
/*if($auto1[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"(
                (calls_log.rating1_id = 1 AND calls_log.rating2_id = 1) OR
                (calls_log.rating1_id = 2 AND calls_log.rating2_id = 2) OR
                (calls_log.rating1_id = 3 AND calls_log.rating2_id = 3) OR
                (calls_log.rating1_id = 4 AND calls_log.rating2_id = 4)
                ) AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto1_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto1_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                (
                (calls_log.rating1_id = 2 AND calls_log.rating2_id = 2) OR
                (calls_log.rating1_id = 3 AND calls_log.rating2_id = 3) OR
                (calls_log.rating1_id = 4 AND calls_log.rating2_id = 4)
                ) AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto1_sql = implode(" UNION ", $auto1_sql_arr);
        $result_rows = $dbc->db_free_query($auto1_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 1,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto1 = array(0, $kol);
        }
        else{
            $auto1 = array(0, 0);
        }
    }
    else{
        $auto1 = array(0, 0);
    }
}*/
$tpl->assign("AUTO1_COUNT", $auto1[0].' / '.$auto1[1]);


// Auto 2
$auto2 = getCountVerDay(2, ROOT_ID);
/*if($auto2[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"calls_log.res = 3 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                (
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1040", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1110", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1240", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1310", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1540", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1610", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1820", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1850", strtotime($minus_days))."'
                )
                ) AND
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto2_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto2_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                calls_log.res = 3 AND
                (
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1040", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1110", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1240", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1310", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1540", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1610", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1820", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1850", strtotime($minus_days))."'
                )
                ) AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto2_sql = implode(" UNION ", $auto2_sql_arr);
        $result_rows = $dbc->db_free_query($auto2_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 2,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto2 = array(0, $kol);
        }
        else{
            $auto2 = array(0, 0);
        }
    }
    else{
        $auto2 = array(0, 0);
    }
}*/
$tpl->assign("AUTO2_COUNT", $auto2[0].' / '.$auto2[1]);



// Auto 3
$auto3 = getCountVerDay(3, ROOT_ID);
/*if($auto3[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"calls_log.rating1_id = 2 AND
                calls_log.rating2_id > 2 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto3_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto3_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id = 2 AND
                calls_log.rating2_id > 2 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto3_sql = implode(" UNION ", $auto3_sql_arr);
        $result_rows = $dbc->db_free_query($auto3_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 3 ,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto3 = array(0, $kol);
        }
        else{
            $auto3 = array(0, 0);
        }
    }
    else{
        $auto3 = array(0, 0);
    }
}*/
$tpl->assign("AUTO3_COUNT", $auto3[0].' / '.$auto3[1]);


// Auto 4
$auto4 = getCountVerDay(4, ROOT_ID);
/*if($auto4[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"calls_log.rating1_id = 3 AND
                calls_log.rating2_id > 3 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto4_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto4_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id = 3 AND
                calls_log.rating2_id > 3 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto4_sql = implode(" UNION ", $auto4_sql_arr);
        $result_rows = $dbc->db_free_query($auto4_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 4 ,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto4 = array(0, $kol);
        }
        else{
            $auto4 = array(0, 0);
        }
    }
    else{
        $auto4 = array(0, 0);
    }
}*/
$tpl->assign("AUTO4_COUNT", $auto4[0].' / '.$auto4[1]);


// Auto 5
$auto5 = getCountVerDay(5, ROOT_ID);
/*if($auto5[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"calls_log.rating1_id = 1 AND calls_log.rating2_id = 1 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto5_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto5_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE calls_log.rating1_id = 1 AND calls_log.rating2_id = 1 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto5_sql = implode(" UNION ", $auto5_sql_arr);
        $result_rows = $dbc->db_free_query($auto5_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 5,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto5 = array(0, $kol);
        }
        else{
            $auto5 = array(0, 0);
        }
    }
    else{
        $auto5 = array(0, 0);
    }
}*/
$tpl->assign("AUTO5_COUNT", $auto5[0].' / '.$auto5[1]);


// Auto 6
$auto6 = getCountVerDay(6, ROOT_ID);
/*if($auto6[1]==0){
    $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"opers.id as oper",
            "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
            "where"=>"calls_log.res = 2 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".ROOT_OFFICE,
            "group"=>"opers.name",
            "order"=>"opers.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $auto6_sql_arr = array();
        $i = 0;
        foreach ($rows as $row){
            $auto6_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE calls_log.res = 2 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end DESC
                LIMIT 2)";
            $i++;
        }
        $auto6_sql = implode(" UNION ", $auto6_sql_arr);
        $result_rows = $dbc->db_free_query($auto6_sql);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $kol = 0;
            foreach ($result_rows as $result_row){
                $dbc->element_create("ver_log",array(
                    "calls_log_id" => $result_row['id'],
                    "ver_id" => ROOT_ID,
                    "add_field_txt" => $add_field_txt,
                    "auto_type" => 6,
                    "ver_date"=>'NOW()'));
                $kol++;
            }
            $auto6 = array(0, $kol);
        }
        else{
            $auto6 = array(0, 0);
        }
    }
    else{
        $auto6 = array(0, 0);
    }
}*/
$tpl->assign("AUTO6_COUNT", $auto6[0].' / '.$auto6[1]);


$tpl->parse("META_LINK", ".".$moduleName."html");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
