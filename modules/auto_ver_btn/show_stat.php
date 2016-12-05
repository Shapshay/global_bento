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
        "select"=>"users.name as oper,
            sto.date_call as date_call,
            COUNT(sto.id) as call_count,
            SUM(CASE WHEN sto.res_call_id='1' THEN 1 ELSE 0 END) as status1,
            SUM(CASE WHEN sto.res_call_id='2' THEN 1 ELSE 0 END) as status2,
            SUM(CASE WHEN sto.res_call_id='3' THEN 1 ELSE 0 END) as status3,
            SUM(CASE WHEN sto.res_call_id='4' THEN 1 ELSE 0 END) as status4,
            SUM(CASE WHEN sto.res_call_id='5' THEN 1 ELSE 0 END) as status5,
            sto.summa as summa",
        "joins"=>"LEFT OUTER JOIN users ON sto.oper_id = users.id",
        "where"=>"DATE_FORMAT(sto.date_call,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."'",
        "group"=>"sto.oper_id"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $html.= '<tr>
                    <td>'.$row['date_call'].'</td>
                    <td>'.$row['oper'].'</td>
                    <td>'.$row['call_count'].'</td>
                    <td>'.$row['status1'].'</td>
                    <td>'.$row['status2'].'</td>
                    <td>'.$row['status3'].'</td>
                    <td>'.$row['status4'].'</td>
                    <td>'.$row['status5'].'</td>
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