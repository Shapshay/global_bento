<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 27.09.2016
 * Time: 14:31
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

// SOAP объект в массив
function objectToArray($d) {
    if (is_object($d)) {
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        return array_map(__FUNCTION__, $d);
    }
    else {
        return $d;
    }
}

// SOAP std в массив
function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key => &$field){
        if(is_object($field))$field = $this->stdToArray($field);
    }
    return $rc;
}

function setBlockID($login, $art) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"block_info",
            "select"=>"id",
            "where"=>"login = '".$login."' AND art_id = '".$art."'",
            "limit"=>"1"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row = $rows[0];
        return $row['id'];
    }
    else{
        return 0;
    }
}



##############################################################################

if(isset($_POST['block'])){
    $block = array();
    print_r($_POST);
    foreach ($_POST as $key=>$v){
        $block[$key] = $v;
    }
    unset($block['block']);
    $sql = "DELETE FROM block_info WHERE login = '".$block['login']."' AND art_id = '".$block['art_id']."'";
    $dbc->db_free_del($sql);
}
else{
    echo "NO";
}