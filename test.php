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

/*
$url = 'http://192.168.1.88/inc/api.php';
$postdata = 'u_lgn=oper';
$result = post_content( $url, $postdata );
$j_str = $result['content'];

echo $j_str.'<p>';

$IBAnswer = json_decode($j_str);
var_dump($IBAnswer);

if($IBAnswer->result=='OK'){
    echo '<p>Блокировка получения клиента';
}*/
/*define("SECRET", 'IIib@v~X');
$rows = $dbc->dbselect(array(
    "table"=>"users_petr",
    "select"=>"*"));
foreach($rows as $row){
    if($row['id']>90){
        $rows2 = $dbc->dbselect(array(
            "table"=>"r_user_role_petr",
            "select"=>"*",
            "where"=>"user_id = ".$row['id']));
        echo "<p>";
        print_r($row);
        $dbc->element_create("users",array(
            "name" => $row['name'],
            "login" => $row['login'],
            "password" => md5('123456'.SECRET),
            "login_1C" => $row['login_1C'],
            "phone" => $row['phone'],
            "office_id" => 3,
            "prod" => $row['prod'],
            "page_id" => $row['page_id'],
            "reg_date"=>'NOW()'));
        $u_id = $dbc->ins_id;
        foreach($rows2 as $row2){
            echo "<br>";
            print_r($row2);
            $dbc->element_create("r_user_role",array(
                "user_id" => $u_id,
                "role_id" => $row2['role_id']));
        }
    }
}*/


/*$url = 'http://kinfobank.kz/inc/api.php';

for ($i=0;$i<=1000;$i++){
    echo "+++++ БЛОК ЗАПРОСОВ ".$i." +++++<p>";
    $rows = $dbc->dbselect(array(
        "table"=>"users",
        "select"=>"*"));
    foreach($rows as $row) {
        $postdata = 'u_lgn='.$row['login'];
        $result = post_content($url, $postdata);
        $j_str = $result['content'];
        echo $j_str . '<p>';
        $IBAnswer = json_decode($j_str);
        var_dump($IBAnswer);
        if ($IBAnswer->result == 'OK') {
            echo '<p>Блокировка получения клиента';
            $url = getCodeBaseURL("index.php?menu=2205");
            header("Location: ".$url);
            exit;
        }
        else{
            echo '<p>Нет блокировки';
        }
    }
}*/

/*$rows = $dbc->dbselect(array(
    "table"=>"users",
    "select"=>"*",
    "where"=>"office_id=3"));
foreach($rows as $row){
    if($row['phone']!=0){
        $new_phone = $row['phone']+300;
        echo $row['phone']." = ".$new_phone."<br>";
        //$dbc->element_update('users',$row['id'],array("phone" => $new_phone));
    }
}*/

/*$rows = $dbc->dbselect(array(
        "table"=>"cour_polis",
        "select"=>"*"
    )
);
$numRows = $dbc->count;
$i = 0;
$j = 0;
$x = 0;
if ($numRows > 0) {
    foreach ($rows as $row){
        switch ($row['stat_ok']){
            case 0:
                $dbc->element_update('polises',$row['polis_id'],array(
                    "status" => 3));
                $i++;
            break;
            case 1:
                $dbc->element_update('polises',$row['polis_id'],array(
                    "status" => 4));
                $j++;
            break;
            case 2:
                $dbc->element_update('polises',$row['polis_id'],array(
                    "status" => 5));
                $x++;
            break;
        }
        echo $i." = ".$j." = ".$x;
    }
}
else {
    echo 0;
}*/
/*$rows = $dbc->dbselect(array(
    "table"=>"polises",
    "select"=>"polises.*,
			strach_company.title AS strach_comp,
			clients.email AS email,
			clients.name AS name,
			clients.iin AS iin,
			users.name AS oper",
    "joins"=>"LEFT OUTER JOIN strach_company ON polises.strach_comp_id = strach_company.id
			LEFT OUTER JOIN strach_periods ON polises.period_id = strach_periods.id
			LEFT OUTER JOIN clients ON polises.client_id = clients.id
			LEFT OUTER JOIN users ON polises.oper_id = users.id",
    "where"=>"polises.id = 20754",
    "limit"=>1));
$row = $rows[0];
if($row['sms']!=''){
    ini_set("soap.wsdl_cache_enabled", "0" );
    $client = new SoapClient("http://akk.coap.kz/coap_server/wsdl",
        array(
            'login' => 'ws',
            'password' => '123456',
            'trace' => true
        )
    );
    $tel = '<telnumbers><telnumber><number>'.$row['sms'].'</number></telnumber></telnumbers>';
    //$email = '<emails><email><mail>'.$row['email'].'</mail></email></emails>';
    $email = '<emails><email><mail></mail></email></emails>';
    $polis = '<policies><policy><policy_number>'.$row['bso_number'].'</policy_number><policy_company>'.$row['strach_comp'].'</policy_company><policy_date>'.date("Y-m-d",strtotime($row['date_end'])).'</policy_date></policy></policies>';
    $auto = '<automobiles><automobile><gosnomer>'.$row['gn'].'</gosnomer><nomertp>'.$row['pn'].'</nomertp>'.$polis.'</automobile></automobiles>';
    $xml = '<client><name>'.$row['name'].'</name><iin>'.trim($row['iin']).'</iin><manager>'.$row['oper'].'</manager><date_end>'.date("Y-m-d",strtotime($row['date_end'])).'</date_end>'.$tel.$email.$auto.'</client>';

    $params["body"] = base64_encode($xml);
    echo $xml."<p>";
    echo $params["body"]."<p>";
    $result = $client->create_update1($params);
    $array = objectToArray($result);
    print_r($array);
    
}*/
// транслит
function encodestring($string){
    $table = array(
        'А' => 'A',
        'Б' => 'B',
        'В' => 'V',
        'Г' => 'G',
        'Д' => 'D',
        'Е' => 'E',
        'Ё' => 'YO',
        'Ж' => 'ZH',
        'З' => 'Z',
        'И' => 'I',
        'Й' => 'J',
        'К' => 'K',
        'Л' => 'L',
        'М' => 'M',
        'Н' => 'N',
        'О' => 'O',
        'П' => 'P',
        'Р' => 'R',
        'С' => 'S',
        'Т' => 'T',
        'У' => 'U',
        'Ф' => 'F',
        'Х' => 'H',
        'Ц' => 'C',
        'Ч' => 'CH',
        'Ш' => 'SH',
        'Щ' => 'CSH',
        'Ь' => '',
        'Ы' => 'Y',
        'Ъ' => '',
        'Э' => 'E',
        'Ю' => 'YU',
        'Я' => 'YA',

        'Ә' => 'E',
        'Ғ' => 'G',
        'Қ' => 'K',
        'Ң' => 'N',
        'Ө' => 'O',
        'Ұ' => 'U',
        'I' => 'I',

        'а' => 'a',
        'б' => 'b',
        'в' => 'v',
        'г' => 'g',
        'д' => 'd',
        'е' => 'e',
        'ё' => 'yo',
        'ж' => 'zh',
        'з' => 'z',
        'и' => 'i',
        'й' => 'j',
        'к' => 'k',
        'л' => 'l',
        'м' => 'm',
        'н' => 'n',
        'о' => 'o',
        'п' => 'p',
        'р' => 'r',
        'с' => 's',
        'т' => 't',
        'у' => 'u',
        'ф' => 'f',
        'х' => 'h',
        'ц' => 'c',
        'ч' => 'ch',
        'ш' => 'sh',
        'щ' => 'csh',
        'ь' => '',
        'ы' => 'y',
        'ъ' => '',
        'э' => 'e',
        'ю' => 'yu',
        'я' => 'ya',

        'ә' => 'e',
        'ғ' => 'g',
        'қ' => 'k',
        'ң' => 'n',
        'ө' => 'o',
        'ұ' => 'u',
        'i' => 'i',
        'h' => 'h',

        ' ' => '_',
    );

    $output = str_replace(
        array_keys($table),
        array_values($table),$string
    );

    return $output;
}

function NewLogin($name, $first_num){
    $name_arr = preg_split("/ /", $name, -1, PREG_SPLIT_NO_EMPTY);
    //print_r($name_arr);
    mb_internal_encoding("UTF-8");
    $first_liter = mb_substr($name_arr[1],0,$first_num);
    $second_liters = $name_arr[0];
    $login = strtolower(encodestring($first_liter.$second_liters));
    return $login;
}

$rows = $dbc->dbselect(array(
    "table"=>"users",
    "select"=>"*"));
foreach ($rows as $row){
    echo "<p>".$row['name'];
    $sush = true;
    $i=1;
    while ($sush){
        $login = NewLogin($row['name'],$i);
        echo "<br>".$login;
        $rows2 = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"*",
            "where"=>"login='".$login."'",
            "limit"=>1));
        if($dbc->count==0){
            $sush = false;
        }
        $i++;
    }
}
?>