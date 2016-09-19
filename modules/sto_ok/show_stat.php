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
        "table"=>"sto",
        "select"=>"sto_tochka.title as sto_name,
            sto.date_visit as date_visit,
            sto.name as name,
            sto.gn as gn,
            sto.summa as summa",
        "joins"=>"LEFT OUTER JOIN sto_tochka ON sto.sto_tochka_id = sto_tochka.id",
        "where"=>"sto.visit = 1
            AND DATE_FORMAT(sto.date_visit,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."'"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $html.= '<tr>
                    <td>'.$row['sto_name'].'</td>
                    <td>'.$row['date_visit'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['gn'].'</td>
                    <td>'.$row['summa'].'</td>
                    </tr>';
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