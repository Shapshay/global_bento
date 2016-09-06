<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 05.09.2016
 * Time: 10:31
 */
//echo md5('2016-09-05');
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

// возвращает текущий рейтинг клиента
function getUser($code1C){
    global $dbc;
    $rows = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"id",
            "where"=>"login_1C = '".$code1C."'",
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

$main_set=$dbc->dbselect(array(
        "table"=>"site_setings",
        "select"=>"site_setings.*, tpl_groups.tpl_folder AS tpl_folder",
        "joins"=>"LEFT OUTER JOIN tpl_groups ON site_setings.tpl_group_id = tpl_groups.id",
        "limit"=>1
    )
);
$main_set = $main_set[0];
define("SECRET", $main_set['secret']);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST['date'])){
    $xml = '<?xml version="1.0" encoding="UTF-8"?>'.base64_decode(str_replace(" ","+",urldecode($_GET['date'])));
    $xml = str_replace("﻿","",str_replace("Data","mytag",$xml));
    echo $xml;
    $xml = simplexml_load_string(trim($xml), 'SimpleXMLElement', LIBXML_NOCDATA | LIBXML_NOBLANKS);
    if(md5(date('Y-m-d'))==$xml->token){
        if(getUser($xml->code1c)==0){
            $row = $dbc->element_find_by_field('offices','code1c',$xml->codeoffice);
            $office_id = $row['id'];

            $phone = 0;
            for($i=100;$i<=199;$i++){
                $row2 = $dbc->element_find_by_field('users','phone',$i);
                if($dbc->count=0){
                    $phone = $i;
                    break;
                }
            }

            if($phone!=0){
                switch ($xml->role){
                    case 1:
                        $prod = 0;
                        $page_id = 1;
                        break;
                    case 2:
                        $prod = 1;
                        $page_id = 1;
                        break;
                    case 3:
                        $prod = 0;
                        $page_id = 2178;
                        break;
                    case 4:
                        $prod = 0;
                        $page_id = 2173;
                        break;
                    case 5:
                        $prod = 1;
                        $page_id = 1;
                        break;
                    case 6:
                        $prod = 0;
                        $page_id = 2200;
                        break;
                    case 7:
                        $prod = 0;
                        $page_id = 2198;
                        break;
                    case 8:
                        $prod = 0;
                        $page_id = 2207;
                        break;
                    case 9:
                        $prod = 0;
                        $page_id = 2207;
                        break;
                    case 10:
                        $prod = 0;
                        $page_id = 2210;
                        break;
                    case 11:
                        $prod = 2216;
                        $page_id = 1;
                        break;
                    default:
                        $prod = 0;
                        $page_id = 1;
                        break;
                }
                $dbc->element_create("users",array(
                    "name" => $xml->name,
                    "login" => $xml->login,
                    "password" => md5($xml->password.SECRET),
                    "login_1C" => $xml->code1c,
                    "phone" => $phone,
                    "office_id" => $office_id,
                    "prod" => $prod,
                    "page_id" => $page_id));
                $u_id = $dbc->ins_id;

                $dbc->element_create("r_user_role",array(
                    "user_id" => $u_id,
                    "role_id" => $xml->role));


                // добавление в InfoBank
                $url = 'http://192.168.1.227/inc/api_user.php';
                $postdata = "name=".$_POST['name'].
                    "&login=".$_POST['login'].
                    "&password=".$_POST['password'].
                    "&login_1C=".$_POST['login_1C'].
                    "&phone=".$_POST['phone'].
                    "&office_id=".$_POST['office_id'].
                    "&prod=".$prod;
                $result = post_content( $url, $postdata );

                $out_row['result'] = 'OK';
            }
            else{
                $out_row['result'] = 'Нет свободных номеров';
            }
        }
        else{
            $out_row['result'] = 'Пользователь существует';
        }
    }
    else{
        $out_row['result'] = 'Ошибка сервера';
    }
}
else{
    $out_row['result'] = 'Ошибка сервера';
}



header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;