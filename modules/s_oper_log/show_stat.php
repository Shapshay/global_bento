<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 26.05.2016
 * Time: 12:44
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

if(isset($_POST['oper_type'])){

    $sql_user = '';
    if($_POST['oper_id']>0){
        $sql_user = ' AND users.id = '.$_POST['oper_id'];
    }

    $sql_stat = '';
    if($_POST['stat_id']>0){
        $sql_stat = ' AND res_calls.id = '.$_POST['stat_id'];
    }

    $html = '';
    $rows = $dbc->dbselect(array(
        "table"=>"calls_log, oper_calls, users, res_calls",
        "select"=>"calls_log.id as id, 
				calls_log.date_start as date_start,
				calls_log.date_end as date_end,
				oper_calls.link as link,
				users.name as oper,
				calls_log.oper_id as oper_id,
				oper_calls.phone1 as phone,
				res_calls.title as res,
				res_calls.id as res_id",
        "where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
				calls_log.id = oper_calls.calls_log_id AND
				calls_log.oper_id = users.id AND 
				calls_log.res = res_calls.id".$sql_user.$sql_stat.
                " AND DATE_FORMAT(calls_log.date_end,'%Y%m%d')>='".date("Ymd",strtotime($_POST['date_start']))."'
				AND DATE_FORMAT(calls_log.date_end,'%Y%m%d')<='".date("Ymd",strtotime($_POST['date_end']))."'",
        "group"=>"calls_log.id",
        "order"=>"calls_log.date_start",
        "order_type"=>"DESC",
        "limit"=>$_POST['limit']));
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            if ($row['res'] == 'Точная дата') {
                $rows2 = $dbc->dbselect(array(
                    "table" => "phones",
                    "select" => "clients.date_end as date_end",
                    "joins" => "LEFT OUTER JOIN clients ON phones.client_id = clients.id",
                    "where" => "phone = '" . $row['phone'] . "'",
                    "limit" => 1));
                $row2 = $rows2[0];
                //echo $dbc->outsql."<br>";
                $td = date("d-m-Y", strtotime($row2['date_end']));
                //$td = $row2['date_end'];
            } else {
                $td = '-';
            }
            $view_log_url = '/' . getItemCHPU(2207, 'pages') . '/?act=log_view&contact=' . $row['id'];
            $audio_link = '<a href="javascript:PlayCall(\'' . $row['link'] . '\', \'' . $row['oper_id'] . '\', \'' . $row['phone'] . '\', \'' . $row['res'] . '\', \'' . $row['res_id'] . '\');">' . $row['link'] . '</a>';
            $html.= '<tr>
                    <td><a href="'.$view_log_url.'" title="Подробнее"><img src="images/edit_view.png" border="0"></a></td>
                    <td>'.$row['id'].'</td>
                    <td>'.$row['oper'].'</td>
                    <td>'.$row['date_start'].'</td>
                    <td>'.$row['date_end'].'</td>
                    <td>'.$row['res'].'</td>
                    <td>'.$row['phone'].'</td>
                    <td>'.$audio_link.'</td>
                    <td>'.$td.'</td>
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