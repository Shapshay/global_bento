<?php
# SETTINGS #############################################################################

$moduleName = "stat_auto_graf";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "grid3" => $prefix . "grid3.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
));

# MAIN #################################################################################

$tpl->assign("TABLE_LOG_CALLS_ROWS", '');
$dateStart = date('d-m-Y');
$tpl->assign("EDT_DATE_START", $dateStart);

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

$oper_rows='';
$rows = $dbc->dbselect(array(
		"table"=>"users",
		"select"=>"users.*, GROUP_CONCAT(r_user_role.role_id) as role",
		"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id",
		"group"=>"users.id",
		"order"=>"users.name"
	)
);
foreach($rows as $row){
	$this_role = explode(",",$row['role']);
	if(in_array(1,$this_role)){
		$oper_rows.='<option value="'.$row['id'].'">'.$row['name'];
	}
}
$tpl->assign("OPERS_ROWS", $oper_rows);

$ratings = '';
$rows = $dbc->dbselect(array(
        "table"=>"ratings",
        "select"=>"*"
    )
);
foreach($rows as $row){
    $ratings.= '<option value="'.$row['id'].'">'.$row['title'].'</option>';
}

$ratings_block = '';
for ($i=1;$i<=6;$i++){
    $ratings_block.= '<div class="rating_block">
    '.$i.') <input type="checkbox" id="check_r" value="'.$i.'" class="check_r">
    <select id="rating'.$i.'_1">
        <option value="0">Невыбрано</option>'.$ratings.'
    </select>
    <select id="rating'.$i.'_2">
        <option value="0">Невыбрано</option>'.$ratings.'
    </select>
    </div>';
}

$tpl->assign("RATINGS_SEL", $ratings_block);

$tpl->parse("META_LINK", ".".$moduleName."grid");

$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");

?>
