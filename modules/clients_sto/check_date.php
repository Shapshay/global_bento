<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 02.09.2016
 * Time: 9:37
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
// чистим текст
function SuperSaveStr($name) {
    $name = strip_tags($name);
    $name = trim($name);
    $name = preg_replace("/[^\x20-\xFF]/","",@strval($name));
    return $name;
}

// получение страницы через GET
function get_web_page( $url ){
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "inc/coo.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE,"inc/coo.txt");

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;

    return $header;
}

function post_content ($url,$postdata) {
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
//  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    curl_setopt($ch, CURLOPT_COOKIEJAR, "java/grab/cook/coo.txt");
    curl_setopt($ch, CURLOPT_COOKIEFILE,"java/grab/cook/coo.txt");

    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    return $header;
}

function setOperDateCounter($oper_id) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"oper_counter",
            "select"=>"id",
            "where"=>"oper_id = '".$oper_id."' AND DATE_FORMAT(date, '%Y%m%d') = ".date("Ymd"),
            "limit"=>"1"
        )
    );
    $row = $rows[0];
    return $row['id'];
}

function setDateCounter($oper_id, $count_type, $gn) {
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"oper_counter_log",
            "select"=>"COUNT(id) AS num",
            "where"=>"oper_id = '".$oper_id."' AND gn = '".$gn."' AND count_type = ".$count_type." AND NOW() < ADDDATE(date, INTERVAL 12 HOUR)",
            "limit"=>"1"
        )
    );
    $row = $rows[0];
    $row = $rows[0];
    return $row['num'];
}

//##################################################################################################

if(isset($_POST['gn'])){
    $gn = SuperSaveStr($_POST['gn']);

    $url = 'http://bentocrm.kz/test/php/egov2.php?vehicleNumber='.$gn;
    $result = get_web_page( $url );
    $html2 = $result['content'];

    $tech_date = date("d-m-Y", strtotime($html2));

    $dbc->element_update('sto',$_POST['CLIENT_ID'],array(
        "date_dog" => date("Y-m-d",strtotime($tech_date))));

    $out_row['result'] = 'OK';
    $out_row['tech_date'] = $tech_date;


}
else{
    $out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);