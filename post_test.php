<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 22.09.2016
 * Time: 16:10
 */

// SOAP std в массив
function stdToArray($obj){
    $rc = (array)$obj;
    foreach($rc as $key => &$field){
        if(is_object($field))$field = $this->stdToArray($field);
    }
    return $rc;
}

///////////////////////////////////////////////////////////////////////////////////////////////

print_r($_POST);
echo "<p>";
$json = json_encode($_POST);
echo "<p>".$json;
$base = base64_encode($json);
echo "<p>".$base;

$base2 = base64_decode($base);
echo "<p>".$base2;
$json2 = json_decode($base2);
echo "<p>";
print_r($json2);
$arr = stdToArray($json2);
echo "<p>";
print_r($arr);