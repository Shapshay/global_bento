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

if(isset($_POST['oper_id'])){

    $html = '';
    $rows = $dbc->dbselect(array(
        "table"=>"post_control",
        "select"=>"DATE_FORMAT(post_control.date_obrabt,'%d-%m-%Y') as date_stat,
            users.name as oper,
            COUNT(post_control.id) as zvon,
            SUM(CASE WHEN post_control.email='' THEN 0 ELSE 1 END) as emails,
            ROUND(AVG(post_control.ocen), 2) as avg_ocen,
            SUM(CASE WHEN post_control.result=1 THEN 1 ELSE 0 END) as res1,
            SUM(CASE WHEN post_control.result=2 THEN 1 ELSE 0 END) as res2,
            SUM(CASE WHEN post_control.result=3 THEN 1 ELSE 0 END) as res3,
            SUM(CASE WHEN post_control.result=4 THEN 1 ELSE 0 END) as res4,
            SUM(CASE WHEN post_control.result=5 THEN 1 ELSE 0 END) as res5,
            SUM(post_control.send) as send",
        "joins"=>"LEFT OUTER JOIN users ON post_control.oper_id = users.id",
        "where"=>"post_control.oper_id = 2 AND 
            post_control.result <> 0
            AND DATE_FORMAT(post_control.date_obrabt,'%Y%m%d')>='".date("Ymd",strtotime($_POST['date_start']))."'
		    AND DATE_FORMAT(post_control.date_obrabt,'%Y%m%d')<='".date("Ymd",strtotime($_POST['date_end']))."'",
        "group"=>"date_stat",
        "limit"=>$_POST['limit']));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $html.= '<tr>
                    <td>'.$row['date_stat'].'</td>
                    <td>'.$row['oper'].'</td>
                    <td>'.$row['zvon'].'</td>
                    <td>'.$row['emails'].'</td>
                    <td>'.$row['avg_ocen'].'</td>
                    <td>'.$row['res1'].'</td>
                    <td>'.$row['res2'].'</td>
                    <td>'.$row['res3'].'</td>
                    <td>'.$row['res4'].'</td>
                    <td>'.$row['res5'].'</td>
                    <td>'.$row['send'].'</td>
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