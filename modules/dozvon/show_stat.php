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
        "table"=>"dozvon_log",
        "select"=>"COUNT(dozvon_log.id) as all_calls,
            SUM(CASE WHEN dozvon_log.dozvon=1 THEN 1 ELSE 0 END) as dozvon,
            users.name as oper",
        "joins"=>"LEFT OUTER JOIN users ON dozvon_log.oper_id = users.id",
        "where"=>"dozvon_log.res <> 0 AND
            users.office_id = ".$_POST['office_id']." AND
            DATE_FORMAT(dozvon_log.date_log,'%Y%m%d')='".date("Ymd",strtotime($_POST['date_start']))."'",
        "group"=>"dozvon_log.oper_id",
        "order"=>"users.name"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    $all_calls = 0;
    $all_dozv = 0;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $all_calls+=$row['all_calls'];
            $all_dozv+=$row['dozvon'];
            $html.= '<tr>
                    <td width="200">'.$row['oper'].'</td>
                    <td align="center">'.$row['dozvon'].'</td>
                    <td align="center">'.$row['all_calls'].'</td>
                    </tr>';
        }
    }




    $out_row['sql'] = $sql;
    $out_row['html'] = $html;
    $out_row['all_calls'] = $all_calls;
    $out_row['all_dozv'] = $all_dozv;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;