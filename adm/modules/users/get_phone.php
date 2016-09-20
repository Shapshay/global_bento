<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 20.09.2016
 * Time: 9:54
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;

if(isset($_POST['office_id'])){

    $out_row['result'] = 'OK';
    $phone_start = 0;
    $phone = 0;
    switch ($_POST['office_id']){
        case 1:
            $phone_start = 100;
            break;
        case 2:
            $phone_start = 200;
            break;
        case 3:
            $phone_start = 100;
            break;
        default:
            $phone_start = 0;
            break;
    }
    
    for($i=($phone_start+2);$i<=($phone_start+99);$i++){
        $rows = $dbc->dbselect(array(
                "table"=>"users",
                "select"=>"id",
                "where"=>"office_id=".$_POST['office_id']." AND phone = '".$i."'"
            )
        );
        if($dbc->count == 0){
            $phone = $i;
            break;
        }
    }

    $out_row['phone'] = $phone;

}
else{
    $out_row['result'] = 'Err1';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;

?>