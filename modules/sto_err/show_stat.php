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
            sto.date_dog as date_dog,
            sto.name as name,
            sto.gn as gn,
            users.name as oper",
        "joins"=>"LEFT OUTER JOIN sto_tochka ON sto.sto_tochka_id = sto_tochka.id
        LEFT OUTER JOIN users ON sto.sto_tochka_id = users.id",
        "where"=>"sto.visit = 0
            AND DATE_FORMAT(sto.date_dog,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."'"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $html.= '<tr>
                    <td>'.$row['sto_name'].'</td>
                    <td>'.$row['date_dog'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['gn'].'</td>
                    <td>'.$row['oper'].'</td>
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