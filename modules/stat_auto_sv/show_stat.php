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

//////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['date_start'])){

    $html = '';
    $rows = $dbc->dbselect(array(
        "table"=>"ver_log",
        "select"=>"users.name as sv,
                SUM(CASE WHEN (super_obrab=1 AND DATE_FORMAT(ver_date,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."') THEN 1 ELSE 0 END) as res1,
                SUM(CASE WHEN (super_obrab=0 AND ver_obrab=1 AND DATE_FORMAT(ver_date,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."') THEN 1 ELSE 0 END) res2,
                SUM(CASE WHEN super_obrab=1 THEN 1 ELSE 0 END) as res3,
                SUM(CASE WHEN (super_obrab=0 AND ver_obrab=1) THEN 1 ELSE 0 END) res4",
        "joins"=>"LEFT JOIN users ON ver_log.super_id = users.id",
        "group"=>"sv"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            if($row['sv']!=null){
                $html.= '<tr>
                    <td>'.$row['sv'].'</td>
                    <td>'.$row['res1'].'</td>
                    <td>'.$row['res2'].'</td>
                    <td>'.$row['res3'].'</td>
                    <td>'.$row['res4'].'</td>
                    </tr>';
            }

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