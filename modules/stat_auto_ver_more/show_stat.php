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
            "select"=>"ver_log.*,
                vusers.name as ver_name,
                susers.name as s_name",
            "joins"=>"LEFT OUTER JOIN users ON ver_log.oper_id = users.id 
                LEFT OUTER JOIN users as vusers ON ver_log.ver_id = vusers.id
                LEFT OUTER JOIN users as susers ON ver_log.super_id = susers.id
                LEFT OUTER JOIN control_log ON ver_log.control_log_id = control_log.id",
            "where"=>"DATE_FORMAT(ver_log.ver_date,'%Y%m%d') = '".date("Ymd",strtotime($_POST['date_start']))."' AND 
                ver_log.ver_obrab = 1 AND
                control_log.control = 0",
            "order"=>"oper ASC"
        )
    );
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $i = 1;
        $tmp = 0;
        foreach($rows as $row){
            $errs = '';
            if($row['oper_id']!=$tmp){
                $i = 1;
                $tmp = $row['oper_id'];
            }

            $rows2= $dbc->dbselect(array(
                    "table"=>"control_err_log",
                    "select"=>"errs.title as err",
                    "joins"=>"LEFT OUTER JOIN errs ON control_err_log.err_id = errs.id ",
                    "where"=>"control_log_id = ".$row['control_log_id']
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $j = 1;
                foreach($rows2 as $row2){
                    $errs.= $j.') '.$row2['err'].'<br>';
                    $j++;
                }
            }
            else{
                $errs = '---';
            }

            if($row['super_id']!=0){
                $s_name = $row['s_name'];
                $s_com = $row['super_comment'];
            }
            else{
                $s_name = '---';
                $s_com = '---';
            }

            $html.= '<tr>
                <td>'.$row['ver_name'].'</td>
                <td>'.$row['oper'].'</td>
                <td>'.$i.'</td>
                <td>'.$row['rating1'].' -> '.$row['rating2'].'</td>
                <td>'.$errs.'</td>
                <td>'.$row['ver_comment'].'</td>
                <td>'.$s_name.'</td>
                <td>'.$s_com.'</td>
            </tr>';
            $i++;
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