<?php
function getItemCHPU($id, $item_tab) {
	global $dbc;
	$resp = $dbc->element_find($item_tab,$id);
	return $resp['chpu'];
}
function getPageType($chpu) {
	global $dbc;
	$resp = $dbc->element_find_by_field('pages','chpu',$chpu);
	return $resp['type'];
}
function getPageTypeFromID($id) {
	global $dbc;
	$resp = $dbc->element_find('pages',$id);
	return $resp['type'];
}
function getBaseURL($chpuURL) {
	//echo $chpuURL."*<br>";
	global $dbc;
	$chpuURL = strtolower($chpuURL);
	$chpuURL = strip_tags($chpuURL);
	$chpuURL = trim($chpuURL);
	while (strpos($chpuURL,' ')!==false ){
		$chpuURL = str_replace(' ','',$chpuURL);
	};
	$chpuURL = preg_split("/\//", $chpuURL, -1, PREG_SPLIT_NO_EMPTY);
	//print_r($chpuURL);
	if(isset($chpuURL[2])){
		// параметр третьего уровня
		if(strpos($chpuURL[2],"page=")!=0){
			// это разбивка по страницам
			$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[1]);
			if ($dbc->count > 0) {
				$GLOBALS['_GET']['menu'] = (int)$resp['id'];
			}
			else{
				return false;
			}
			$page_num = str_replace("?page=","",$chpuURL[2]);
			$GLOBALS['_GET']['page'] = (int)$page_num;
		}
		else{
			// это страница либо art
			$param_type = getPageType($chpuURL[1]);
			if($param_type=='pages'){
				// Страница
				$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[2]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['menu'] = (int)$resp['id'];
				}
				else{
					return false;
				}
			}
			else{
				// item
				$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[1]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['menu'] = (int)$resp['id'];
				}
				else{
					return false;
				}
				
				$resp = $dbc->element_find_by_field($param_type,'chpu',$chpuURL[2]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['item'] = (int)$resp['id'];
				}
				else{
					return false;
				}
			}
		}
	}
	else if(isset($chpuURL[1])){
		// параметр второго уровня
		if(strpos($chpuURL[1],"page=")!=0){
			// это разбивка по страницам
			$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[0]);
			if ($dbc->count > 0) {
				$GLOBALS['_GET']['menu'] = (int)$resp['id'];
			}
			else{
				return false;
			}
			$page_num = str_replace("?page=","",$chpuURL[1]);
			$GLOBALS['_GET']['page'] = (int)$page_num;
		}
		elseif(strpos($chpuURL[1],"item")>0||strpos($chpuURL[1],"polis")>0||strpos($chpuURL[1],"count")>0||
			strpos($chpuURL[1],"call_lenght")>0||strpos($chpuURL[1],"polis_view")>0||strpos($chpuURL[1],"act")>0){
			$item = str_replace("?","",$chpuURL[1]);
			$item_arr = preg_split("/=/", $item, -1, PREG_SPLIT_NO_EMPTY);
			$GLOBALS['_GET'][$item_arr[0]] = (int)$item_arr[1];
			$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[0]);
			if ($dbc->count > 0) {
				$GLOBALS['_GET']['menu'] = (int)$resp['id'];
			}
			else{
				return false;
			}
		}
		else{
			// это страница либо art
			$param_type = getPageType($chpuURL[0]);
			if($param_type=='pages'){
				// Страница
				$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[1]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['menu'] = (int)$resp['id'];
				}
				else{
					return false;
				}
			}
			else{
				// item
				$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[0]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['menu'] = (int)$resp['id'];
				}
				else{
					return false;
				}
				
				$resp = $dbc->element_find_by_field($param_type,'chpu',$chpuURL[1]);
				if ($dbc->count > 0) {
					$GLOBALS['_GET']['item'] = (int)$resp['id'];
				}
				else{
					return false;
				}
			}
		}
	}
	else if(isset($chpuURL[0])){
		// параметр первого уровня
		$resp = $dbc->element_find_by_field('pages','chpu',$chpuURL[0]);
		if ($dbc->count > 0) {
			$GLOBALS['_GET']['menu'] = (int)$resp['id'];
		}
		else{
			return false;
		}
	}
	else{
		// нет параметров
		$resp = $dbc->element_find_by_field('pages','start',1);
		$GLOBALS['_GET']['menu'] = (int)$resp['id'];
	}
	
	return true;
}



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
				
				' ' => '_',
    ); 

    $output = str_replace( 
        array_keys($table), 
        array_values($table),$string 
    ); 

    return $output; 
}

// Проверяем есть ли в таблице ЧПУ и выводим
function setTransTitle($chpu, $table) {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>$table,
			"select"=>"id",
			"where"=>"chpu = '".$chpu."'",
			"limit"=>1
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		return $rows[0]['id'];
	}
	return 0;
}

// ЧПУ страниц
function setPageCHPU($title) {
	$trans1 = iconv("utf-8", "windows-1251", $title);
	$trans1 =  strtolower($trans1);
	$trans3 = str_replace('  ',' ',$trans1);
	$trans3 = strip_tags($trans3);
	$trans3 = iconv("windows-1251", "utf-8", $trans3);
	$trans3 = preg_replace("/[^a-zа-я0-9_ ]/iu", "", $trans3);
	$trans3 = trim($trans3);
	$trans2 = encodestring($trans3);
	$trans2 =  strtolower($trans2);
	$trans2 = str_replace('__','_',$trans2);
	
	if(setTransTitle($trans2, 'pages')==0){
		return $trans2;
	}
	else{
		$trans_bool = false;
		$trans_i = 2;
		while(!$trans_bool){
			$tmp_trans = $trans2.'_'.$trans_i;
			if(setTransTitle($tmp_trans, 'pages')==0){
				return $tmp_trans;
				$trans_bool = true;
			}
			$trans_i++;
		}
	}
}

// кодирует системный URL в ЧПУ
function getCodeBaseURL($baseURL, $addParam = '') {
	$chpuURL = '';
	$baseURL = str_replace('index.php?','',$baseURL);
	$baseURL = preg_split("/&/", $baseURL, -1, PREG_SPLIT_NO_EMPTY);
	$menu = 0;
	for($i=0;$i<sizeOf($baseURL);$i++){
		$sub_arr = explode("=", $baseURL[$i]);
		switch ($sub_arr[0]) {
			case 'menu':
				$chpuURL.= '/'.getItemCHPU($sub_arr[1], 'pages');
				$menu = (int)$sub_arr[1];
			break;
			case 'item':
				$param_type = getPageTypeFromID($menu);
				$item = (int)$sub_arr[1];
				$chpuURL.= '/'.getItemCHPU($sub_arr[1], $param_type);
				
			break;
			default:
				$chpuURL.= '/'.$baseURL[$i];
			break;
		}
	}
	if($addParam!=''){
		$chpuURL.= '/'.$addParam;
	}
	return $chpuURL;
}
?>