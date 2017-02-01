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
    $errs = array();
    $html = '';
    $rows = $dbc->dbselect(array(
            "table"=>"control_err_log",
            "select"=>"users.id as oper_id,
                users.`name` as oper,
                control_err_log.err_id as err_id,
                COUNT(err_id) as err_count",
            "joins"=>"LEFT OUTER JOIN users ON control_err_log.oper_id = users.id",
            "where"=>"DATE_FORMAT(control_err_log.date,'%Y%m%d') >= '".date("Ymd",strtotime($_POST['date_start']))."' AND 
                DATE_FORMAT(control_err_log.date,'%Y%m%d') <= '".date("Ymd",strtotime($_POST['date_end']))."' AND
                users.`name` IS NOT NULL AND users.office_id = ".$_POST['office_id'],
            "group"=>"oper, err_id"
        )
    );
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        // формируем массив ошибок
        foreach($rows as $row){
            if(!in_array($row['err_id'], $errs)){
                array_push($errs, $row['err_id']);
            }
        }
        sort($errs);

        // создаем шапку
        if(sizeof($errs)>1){
            $head_title = join(' OR id=', $errs);
        }
        else{
            $head_title = $errs[0];
        }
        $head_title = join(' OR id=', $errs);
        $rows2 = $dbc->dbselect(array(
            "table"=>"errs",
            "select"=>"title",
            "where"=>"(id=".$head_title.")",
            "order"=>"id ASC"
        ));
        $titled = array();
        foreach($rows2 as $row2){
            array_push($titled, $row2['title']);
        }
        $head_th = join('</th><th>', $titled);
        //$sql = $dbc->outsql;
        $html = '<thead>
            <tr>
                <th>Оператор</th>
                <th>'.$head_th.'</th>
            </tr>
            </thead>
            <tbody id="table_rows">';

        // выводим информацию по ошибкам
        $tmp_oper_id = 0;
        $tmp_errs_arr = array();
        $tmp_i = 1;
        foreach($rows as $row){
            if($tmp_oper_id==0){
                $tmp_oper_id = $row['oper_id'];
            }
            if($tmp_oper_id!=$row['oper_id']){
                // следующий оператор. формируем строку предыдущего
                $html.='<tr>';
                $html.='<td>'.$tmp_oper_name.'</td>';
                foreach ($errs as $err){ // проходим по списку всех ошибок
                    $setIn = 0;
                    foreach ($tmp_errs_arr as $tmp_err){ // проходим по списку ошибок оператора
                        if($err==$tmp_err[0]){
                            $setIn = $tmp_err[1];
                        }
                    }
                    $html.='<td>'.$setIn.'</td>';
                }
                $html.='</tr>';
                $tmp_oper_id = $row['oper_id'];
                $tmp_errs_arr = array();
            }

            // собираем ошибки текущего оператора
            array_push($tmp_errs_arr, array($row['err_id'], $row['err_count']));
            $tmp_oper_name = $row['oper'];
        }

        $html.='<tr>';
        $html.='<td>'.$tmp_oper_name.'</td>';
        foreach ($errs as $err){ // проходим по списку всех ошибок
            $setIn = 0;
            foreach ($tmp_errs_arr as $tmp_err){ // проходим по списку ошибок оператора
                if($err==$tmp_err[0]){
                    $setIn = $tmp_err[1];
                }
            }
            $html.='<td>'.$setIn.'</td>';
        }
        $html.='</tr>';

    }
    else{
        // список пуст
        $html.='<thead>
            <tr>
                <th>Оператор</th>
                <th>Ошибки</th>
            </tr>
            </thead><tbody>';
    }

    $html.='</tbody>';


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