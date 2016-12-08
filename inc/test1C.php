<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 07.12.2016
 * Time: 11:53
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set ("Asia/Almaty");
require_once('/var/www/html/phpmailer/class.phpmailer.php');
include("/var/www/html/phpmailer/class.smtp.php");

// Отправляем письмо
function sendMail3($mail_to, $subject, $body, $sender_name = "", $sender_mail = "") {
    $mail = new PHPMailer();
    $mail->IsSMTP(); // telling the class to use SMTP
    $mail->Host       = "185.98.6.157"; // SMTP server
    $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->Host       = "185.98.6.157"; // sets the SMTP server
    $mail->Port       = 25;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "send@kazavtoclub.kz"; // SMTP account username
    $mail->Password   = "Av3toc7lu5d";        // SMTP account password

    $mail->SetFrom($sender_mail, $sender_name);

    $mail->AddReplyTo($sender_mail,$sender_name);

    $mail->Subject    = $subject;

    $mail->AltBody    = "12345"; // optional, comment out and test

    $body             = $body;

    $mail->MsgHTML($body);

    $mail->AddAddress($mail_to, 'Subscriber');

    if(!$mail->Send()) {
        $sql = "Mailer Error: " . $mail->ErrorInfo;
    } else {
        $sql = "Message sent!";
    }

    if($sql==''){
        $sql = "TEST";
    }
    return $sql;
}

// SOAP
function objectToArray($d) {
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    }
    else {
        // Return array
        return $d;
    }
}
function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key => &$field){
        if(is_object($field))$field = $this->stdToArray($field);
    }
    return $rc;
}
//*** ****************************************************************************/
$start_time = date("YmdHis");
ini_set("soap.wsdl_cache_enabled", "0" );
$client = new SoapClient("http://akk.coap.kz:55544/akk/ws/wsphp.1cws?wsdl",
    array(
        'login' => 'ws',
        'password' => '123456',
        'trace' => true
    )
);
$result = $client->TestTiming();
$array = objectToArray($result);
//print_r($array);
$err = false;
$mail_body = '';

if($array['return']=='Успешно'){
    $end_time = date("YmdHis");
    $period = $end_time-$start_time;
    echo $end_time-$start_time;
    if($period>9){
        $err = true;
        $mail_body = 'Интервал запроса 10 и более секунд.';
    }
}
else{
    $err = true;
    $mail_body = 'Ошибка 1С.';
}

if($err){
    $_sendFrom = 'send@kazavtoclub.kz';
    $_mailSubject = 'Проблемы 1С -> Bento';
    $_mailFrom = "Bento CRM";
    sendMail3('tigayn@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
}
echo '<br>'.$mail_body;
