<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 01.11.2016
 * Time: 16:36
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../inc/BDFunc.php");
$dbc = new BDFunc;

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
        'і' => 'i',
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
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['name'])){
    $sush = true;
    $i=1;
    $login = '123';
    while ($sush){
        $login = NewLogin($_POST['name'],$i);
        //echo "<br>".$login;
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

    $out_row['result'] = 'OK';
    $out_row['login'] = $login;
}
else{
    $out_row['result'] = 'Err';
}

header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;