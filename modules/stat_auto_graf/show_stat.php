<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 22.08.2016
 * Time: 10:06
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
function getItemCHPU($id, $item_tab) {
    global $dbc;
    $resp = $dbc->element_find($item_tab,$id);
    return $resp['chpu'];
}

$div_width = 400;
$one_proc = 4;
//////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['date_start'])){
    $html = '';
    $sql = '';
    foreach ($_POST['ratings_val'] as $ratings){
        $html.= '<p><b>'.$ratings[3].' -> '.$ratings[4].'</b></p>';

        if($ratings[1]==0){
            $where = "rating1_id <> 0";
        }
        else{
            $where = "rating1_id = ".$ratings[1];
        }
        if($ratings[2]==0){
            $case = "rating2_id <> 0";
        }
        else{
            $case = "rating2_id=".$ratings[2];
        }

        $rows = $dbc->dbselect(array(
            "table"=>"calls_log",
            "select"=>"COUNT(id) as all1,
            SUM(CASE WHEN ".$case." THEN 1 ELSE 0 END) as res1,
            SUM(CASE WHEN res<>1 THEN 1 ELSE 0 END) as dozvon1",
            "where"=>"DATE_FORMAT(date_end,'%Y%m%d')>='".date("Ymd",strtotime($_POST['date_start']))."' AND 
            DATE_FORMAT(date_end,'%Y%m%d')<='".date("Ymd",strtotime($_POST['date_end']))."' AND
            oper_id = ".$_POST['oper_id']." AND
            ".$where
        ));
        $sql.= $dbc->outsql;
        $numRows = $dbc->count;
        if ($numRows > 0) {
            $row = $rows[0];
            if($row['res1']!=0){
                $curent_width = floor($row['res1']/($row['all1']/100))*$one_proc;
            }
            else{
                $curent_width = 0;
            }
            if($row['all1']!=0) {
                $proc_dozvon = floor($row['dozvon1'] / ($row['all1'] / 100));
            }
            else{
                $proc_dozvon = 0;
            }
            $html.= '<div class="rating_div" style="width: '.$div_width.'px;">
                    <div class="cur_r" style="width: '.$curent_width.'px;">'.$row['res1'].'</div>
                </div>
                <div class="rating_all" style="width: '.$div_width.'px;">Всего звонков: '.$row['all1'].'</div>
                <div class="rating_dozvon" style="width: '.$div_width.'px;">Дозвоны: '.$proc_dozvon.'% ('.$row['dozvon1'].')</div><p><hr align="left" width="'.$div_width.'px"></p>';
        }
        else{
            $html.= '<div class="rating_all" style="width: '.$div_width.'px;">Нет данных за период</div><p><hr align="left" width="'.$div_width.'px"></p>';
        }
    }






    $out_row['sql'] = $sql;
    $out_row['html'] = $html;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;