<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 01.02.2017
 * Time: 9:42
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

// чистим текстовую $_GET[]
function SuperSaveGETStr($name) {
    //$name = preg_replace("/[^a-zA-ZА-Яа-я0-9_]/","",$name);
    $name = preg_replace("/[^a-zA-Z0-9_]/","",$name);
    return $name;
}

##############################################################################
if(isset($_POST['login'])){
    $login = SuperSaveGETStr($_POST['login']);
    if($login!=''){
        // проверка блокировки InfoBank
        $rows = $dbc->dbselect(array(
                "table"=>"block_info",
                "select"=>"id",
                "where"=>"login = '".$_POST['login']."'",
                "limit"=>"1"
            )
        );
        $numRows = $dbc->count;
        if ($numRows > 0) {
            echo 1;
        }
        else{
            echo 0;
        }
    }
    else{
        echo 2;
    }
}
else{
    echo 2;
}
