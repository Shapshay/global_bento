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
        "select"=>"users.name as ver,
                SUM(CASE WHEN ver_obrab=1 THEN 1 ELSE 0 END) as res1,
                COUNT(ver_log.id) as res2",
        "joins"=>"LEFT OUTER JOIN users ON ver_log.ver_id = users.id",
        "where"=>"DATE_FORMAT(ver_date,'%Y%m%d')>='".date("Ymd",strtotime($_POST['date_start']))."'
            AND DATE_FORMAT(ver_date,'%Y%m%d')<='".date("Ymd",strtotime($_POST['date_end']))."'",
        "group"=>"ver"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $html.= '<tr>
                    <td>'.$row['ver'].'</td>
                    <td>'.$row['res1'].'</td>
                    <td>'.$row['res2'].'</td>
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