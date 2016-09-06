<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("adm/inc/BDFunc.php");
$dbc = new BDFunc;
require_once("adm/inc/RFunc.php");
$rfq = new RFunc;
date_default_timezone_set ("Asia/Almaty");
/*
function get_web_page( $url ){
    $uagent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    $ch = curl_init( $url );
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);   // ���������� ���-��������
    curl_setopt($ch, CURLOPT_HEADER, 0);           // �� ���������� ���������
    //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);   // ��������� �� ����������
    curl_setopt($ch, CURLOPT_ENCODING, "");        // ������������ ��� ���������
    curl_setopt($ch, CURLOPT_USERAGENT, $uagent);  // useragent
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120); // ������� ����������
    curl_setopt($ch, CURLOPT_TIMEOUT, 120);        // ������� ������
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);       // ��������������� ����� 10-��� ���������
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
$url = 'http://melchior.kz/api/getSI?hash='. md5(date("d.m.Y")).'&make_id=40&model_id=4&year=2004';
echo $url."<br>";
$result = get_web_page( $url );
$html2 = $result['content'];
$obj=json_decode($html2);
$stoim = $obj->summ;
echo $stoim."<br>";
*/
/*
$user_arr = array();
$row = 1;
$handle = fopen("roots.csv", "r");
$i=0;
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
    $num = count($data);
    //echo "<p> $num полей в строке $row: <br /></p>\n";
    $row++;
    for ($c=0; $c < $num; $c++) {
        switch ($c){
            case '1': $user_arr[$i]['reg_date'] = $data[$c];
                break;
            case '2': $user_arr[$i]['name'] = $data[$c];
                break;
            case '3': $user_arr[$i]['login'] = $data[$c];
                break;
            case '5': $user_arr[$i]['login_1C'] = $data[$c];
                break;
            case '10': $user_arr[$i]['phone'] = $data[$c];
                break;
            case '11': $user_arr[$i]['page_id'] = $data[$c];
                break;
            case '13': $user_arr[$i]['av'] = $data[$c];
                break;
            case '18': $user_arr[$i]['prod'] = $data[$c];
                break;
        }
        //echo $data[$c] . "<br />\n";
    }
    $i++;
}
fclose($handle);
//echo "<p>&nbsp;</p>\n";
//var_dump($user_arr);
//echo "<p>&nbsp;</p>\n";
define("SECRET", 'IIib@v~X');
for ($i=0;$i<sizeof($user_arr);$i++){
    echo "<p>";
    print_r($user_arr[$i]);
    switch ($user_arr[$i]['page_id']){
        case '204': $page_id = 2192;
            break;
        case '207': $page_id = 2200;
            break;
        default: {
            $page_id = 1;
        }
            break;
    }
    $dbc->element_create("users",array(
        "name" => $user_arr[$i]['name'],
        "login" => $user_arr[$i]['login'],
        "password" => md5('123456'.SECRET),
        "login_1C" => $user_arr[$i]['login_1C'],
        "phone" => $user_arr[$i]['phone'],
        "office_id" => 1,
        "prod" => $user_arr[$i]['prod'],
        "page_id" => $page_id,
        "av" => $user_arr[$i]['av']));
    $u_id = $dbc->ins_id;
    echo $dbc->outsql;
    switch ($user_arr[$i]['page_id']){
        case '204': {
            $rfq->set_role_user(8,$u_id);
            break;
        }
        case '207': {
            $rfq->set_role_user(6,$u_id);
            break;
        }
        default: {
            if($user_arr[$i]['prod']==1){
                $rfq->set_role_user(2,$u_id);
            }
            else{
                $rfq->set_role_user(1,$u_id);
            }
            break;
        }
    }
}
*/
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

$url = 'http://192.168.1.88/inc/api.php';
$postdata = 'u_lgn=oper';
$result = post_content( $url, $postdata );
$j_str = $result['content'];

echo $j_str.'<p>';

$IBAnswer = json_decode($j_str);
var_dump($IBAnswer);

if($IBAnswer->result=='OK'){
    echo '<p>Блокировка получения клиента';
}

?>