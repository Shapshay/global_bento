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
    $start = date("YmdHi",strtotime($_POST['date_start']));
    $end = date("YmdHi",strtotime($_POST['date_end']));
    $html = '';
    $rows = $dbc->dbselect(array(
        "table"=>"polises",
        "select"=>"polises.*,
		users.name AS oper,
		polis_status.title AS stat",
        "joins"=>"LEFT OUTER JOIN users ON polises.oper_id = users.id
            LEFT OUTER JOIN polis_status ON polises.status = polis_status.id",
        "where"=>"polises.office_id = ".$_POST['office_id']." AND 
            DATE_FORMAT(polises.date_write,'%Y%m%d%H%i')>='".$start."' AND 
            DATE_FORMAT(polises.date_write,'%Y%m%d%H%i')<='".$end."'",
        "order"=>"polises.date_write ASC"));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    $all_sum = 0;


    if ($numRows > 0) {
        foreach ($rows as $row) {
            $all_sum+= $row['summa'];
            $html.= '<tr>
                    <td>'.date("d-m-Y", strtotime($row['date_write'])).'</td>
                    <td>'.$row['oper'].'</td>
                    <td>'.$row['bso_number'].'</td>
                    <td>'.$row['summa'].'</td>
                    <td>'.$row['stat'].'</td>
                    </tr>';
        }
    }



    $out_row['all_sum'] = number_format($all_sum, 0, '', ' ');
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