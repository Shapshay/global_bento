<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

// получение страницы через POST
function post_content ($url,$postdata) {
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_ENCODING, "");
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if(isset($_POST['Ocenka'])){
    $dbc->element_create("control_log",array(
        "root_id" => $_POST['ROOT_ID'],
        "oper_id" => $_POST['oper_id'],
        "phone" => $_POST['phone'],
        "control" => $_POST['Ocenka'],
        "res" => $_POST['res_id'],
        "date" => 'NOW()'));
    $control_id = $dbc->ins_id;
    $oper_row = $dbc->element_find('users', $_POST['oper_id']);
    $control_row = $dbc->element_find('users', $_POST['ROOT_ID']);
    if(isset($_POST['send_err_arr'][0])) {
        foreach ($_POST['send_err_arr'] as $err) {
            $dbc->element_create("control_err_log", array(
                "root_id" => $_POST['ROOT_ID'],
                "oper_id" => $_POST['oper_id'],
                "err_id" => $err,
                "control_log_id" => $control_id,
                "date" => 'NOW()'));
        }
        $json = json_encode($_POST['send_err_arr']);
        $base = base64_encode($json);
        $url = 'http://kinfobank.kz/inc/api_user_err.php';
        $postdata = "u_id=".$oper_row['login'].
            "&control_id=".$control_row['login'].
            "&errs=".$base;
        $result = post_content( $url, $postdata );
    }

    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
echo json_encode($out_row);

?>
