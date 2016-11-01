<?php
# SETTINGS #############################################################################

$moduleName = "dozvon";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
    $moduleName . "view" => $prefix . "view.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));

# MAIN #################################################################################
if(!isset($_GET['view'])){
    $tpl->assign("TABLE_LOG_CALLS_ROWS", '');
    $dateStart = date('d-m-Y 09:00');
    $dateEnd = date('d-m-Y H:i');
    $tpl->assign("EDT_DATE_START", $dateStart);
    $tpl->assign("EDT_DATE_END", $dateEnd);

    $offices='';
    $office = ROOT_OFFICE;
    $rows = $dbc->dbselect(array(
            "table"=>"offices",
            "select"=>"id, title"
        )
    );
    foreach($rows as $row){
        if($row['id']==ROOT_OFFICE){
            $sel_of = ' selected="selected"';
        }
        else{
            $sel_of = '';
        }
        $offices.='<option value="'.$row['id'].'"'.$sel_of.'>'.$row['title'];
    }
    $tpl->assign("OFFICES_ROWS", $offices);




    $tpl->parse("META_LINK", ".".$moduleName."grid");

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else{
    //print_r($_GET);

    $rows = $dbc->dbselect(array(
        "table"=>"dozvon_log",
        "select"=>"dozvon_log.date_log as date_log,
            clients.name as client,
            res_calls.title as res,
            (CASE WHEN dozvon_log.dozvon=1 THEN '+' ELSE '-' END) as dozvon",
        "joins"=>"LEFT OUTER JOIN users ON dozvon_log.oper_id = users.id
            LEFT OUTER JOIN clients ON dozvon_log.client_id = clients.id
            LEFT OUTER JOIN res_calls ON dozvon_log.res = res_calls.id ",
        "where"=>"dozvon_log.res <> 0 AND
            users.id = ".$_GET['view']." AND
            DATE_FORMAT(dozvon_log.date_log,'%Y%m%d%H%i')>='".$_GET['start']."' AND 
            DATE_FORMAT(dozvon_log.date_log,'%Y%m%d%H%i')<='".$_GET['end']."'",
        "order"=>"dozvon_log.date_log ASC"));
    $html = '';
    $all_calls = 0;
    $all_dozv = 0;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $all_calls++;
            if($row['dozvon']=='+') $all_dozv++;
            $html.= '<tr>
                    <td align="center">'.date("d-m-Y H:i", strtotime($row['date_log'])).'</td>
                    <td width="200">'.$row['client'].'</td>
                    <td align="center">'.$row['res'].'</td>
                    <td align="center">'.$row['dozvon'].'</td>
                    </tr>';
        }
    }

    $tpl->assign("OPER_NAME", getUserName($_GET['view']));
    $tpl->assign("ITOG_STAT", $all_calls);
    $tpl->assign("ITOG_DOZVON", $all_dozv);
    $tpl->assign("ITOG_PROC", number_format($all_dozv/($all_calls/100), 2, ',', ' ').'%');
    $tpl->assign("TABLE_LOG_CALLS_ROWS", $html);


    $tpl->parse("META_LINK", ".".$moduleName."grid");

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."view");
}


?>
