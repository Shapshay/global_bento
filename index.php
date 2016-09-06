<?php
error_reporting (E_ALL);
ini_set("display_errors", "1");
date_default_timezone_set ("Asia/Almaty");
set_time_limit (600);
session_name('USER');
@session_start('USER');
if(isset($_GET['exit'])){
	$_SESSION = array();
	header('Location: https://'.$_SERVER['HTTP_HOST'].'/');
	exit();
}
require_once("inc/func.php");
require_once("inc/route.php");
require_once("adm/inc/core.php");
require_once("adm/inc/val.php");


// mod_rewrite
if (isset($_SERVER['REDIRECT_STATUS'])) {
	$url=urldecode($_SERVER['REQUEST_URI']);
	$url=ltrim($url,"/");
	//echo $url."*";
	if (!getBaseURL($url)||!isset($_GET['menu'])) { 
		header("http/1.0 404 Not found");
		header("Location: /404");
		exit;
	} else{
		header("http/1.0 200 Ok");
	}
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
define("GIFT_PROC", $main_set['gift_proc']);
define("META_MRP", SuperSaveInt($main_set['mrp']));
if(isset($_SESSION['lgn'])){
	$rows = $dbc->dbselect(array(
			"table"=>"users",
			"select"=>"users.*, 
			        GROUP_CONCAT(r_user_role.role_id) as role,
			        offices.fsw_ip as fsw_ip",
			"joins"=>"LEFT OUTER JOIN r_user_role ON users.id = r_user_role.user_id
					LEFT OUTER JOIN offices ON offices.id = users.office_id",
			"where"=>"login = '".$_SESSION['lgn']."' AND password = MD5('".$_SESSION['psw'].SECRET."')",
			"limit"=>1
		)
	);
	$user_row = $rows[0];
	define("ROOT_ID", $user_row['id']);
	define("LOGIN_1C", $user_row['login_1C']);
	define("ROOT_OFFICE", $user_row['office_id']);
	define("ROOT_PHONE", $user_row['phone']);
    define("FSW_IP", $user_row['fsw_ip']);
	$USER_ROLE = explode(",",$user_row['role']);
}
else{
	define("ROOT_ID", 0);
	define("ROOT_OFFICE", 0);
	define("ROOT_PHONE", 0);
	define("LOGIN_1C", 0);
    define("FSW_IP", '');
	$USER_ROLE = 4;
}
if (isset($_GET['menu'])){
	$page_arr = getPageMenuTpl($_GET['menu'],'s');
}
else{
	if(isset($_SESSION['lgn'])){
		$page_arr = getPageMenuTpl($user_row['page_id'], 's');
		$_GET['menu'] = $page_arr['id'];
	}
	else {
		$page_arr = getPageMenuTpl(0, 's');
		$_GET['menu'] = $page_arr['id'];
	}
}
if($_GET['menu']==212){
	header("http/1.0 404 Not found");
}

if(isRolePage($USER_ROLE,$_GET['menu'])==0){
	header("http/1.0 200 Ok");
	header("Location: /access_is_denied");
}

$page_id = $page_arr['id'];
$page_template = $page_arr['stemplate'];
$page_content = $page_arr['content'];
$page_title = $page_arr['title'];
define("PAGE_ID", $page_id);
define("PAGETEMPLATES_PATH", 'templates/'.$main_set['tpl_folder'].'/');
define("META_EMAIL", $main_set['email']);
if (!file_exists(PAGETEMPLATES_PATH.$page_template)) die('Error. Page template not found.');

header('Content-type: text/html; charset="utf-8"');
	
$tpl = new FastTemplate(".");
$tpl->define(array("page" => PAGETEMPLATES_PATH . $page_template));
$tpl->assign("CONTENT", $page_content);
$tpl->assign("PAGE_ID", $page_id);
$tpl->assign("BASE_URL", $_SERVER['HTTP_HOST']);
$tpl->assign("ROOT_OFFICE", ROOT_OFFICE);
$tpl->assign("ROOT_ID", ROOT_ID);
$tpl->assign("GIFT_PROC", GIFT_PROC);
$tpl->assign("LOGIN_1C", LOGIN_1C);
$tpl->assign("FSW_IP", FSW_IP);
//$tpl->assign("ROOT_NAME", ROOT_NAME);

$modules = array();
$template = file_get_contents(PAGETEMPLATES_PATH.$page_template);
$template = str_replace("{CONTENT}", $page_content, $template);

preg_match_all("/{([A-Z0-9_]*)}/e", $template, $modules);

foreach ($modules[0] as $i => $name) {
	if ($name != "{CONTENT}" && $name != "{LANG}") {
		$name = str_replace("{", '', $name);
		$name = str_replace("}", '', $name);
		if (is_file('./modules/'.strtolower($name)."/run.php")) {
			include_once('./modules/'.strtolower($name)."/run.php" );
		}
	}
}

if (isset($_GET['menu'])){
	if(getPageParentID($_GET['menu'])!=2){
		$tpl->assign("ACC_ID", getPageParentID($_GET['menu']));
	}
	else{
		$tpl->assign("ACC_ID", $_GET['menu']);
	}
}
else{
	$tpl->assign("ACC_ID", 1);
}

if (isset($_GET['ch'])){
	if (isset($_GET['ch2'])){
		$tpl->assign("PAGE_TITLE", getPageTitle($_GET['ch']).' - '.getPageTitle($_GET['ch2']).' - '.$page_title);
	}
	else{
		$tpl->assign("PAGE_TITLE", getPageTitle($_GET['ch']).' - '.$page_title);
	}
}
else{
	$tpl->assign("PAGE_TITLE", $page_title);
}

if(isset($_POST['edt_call'])){
	$tpl->assign("TIMER_RELOAD", '0');
}
else{
	if(isset($_GET['count'])){
		$tpl->assign("TIMER_RELOAD", '0');
		if((in_array(8,$USER_ROLE)||in_array(5,$USER_ROLE))){
			$tpl->assign("CALL_RELOAD", "parent.topFrame.location.href= 'sipphone/index2.html';");
		}
		else{
			$tpl->assign("CALL_RELOAD", "parent.topFrame.location.href= 'sipphone/call_light.html';");
			$dbc->element_create("oper_log", array(
				"oper_id" => ROOT_ID,
				"oper_act_type_id" => 5,
				"oper_act_id" => 9,
				"date_log" => 'NOW()'));
		}
	}
	else{
		$tpl->assign("TIMER_RELOAD", '1');
		$tpl->assign("CALL_RELOAD", '');
	}
}
if($USER_ROLE!=4&&in_array(8,$USER_ROLE)){
	$tpl->assign("MENU_HIDE1", '');
	$tpl->assign("MENU_HIDE2", '');
	$tpl->assign("SCRIPT_MODER1", '/*');
	$tpl->assign("SCRIPT_MODER2", '*/');
}
else{
	$tpl->assign("MENU_HIDE1", '<!--');
	$tpl->assign("MENU_HIDE2", ' -->');
	$tpl->assign("SCRIPT_MODER1", '');
	$tpl->assign("SCRIPT_MODER2", '');
}
//echo FSW_IP."*";
$tpl->assign("ROOT_PHONE", ROOT_PHONE);

$tpl->assign("PAGETEMPLATES_PATH", PAGETEMPLATES_PATH);

$tpl->parse("FINAL", "page");

$tpl->FINAL = parse_values($tpl->FINAL);
$tpl->FastPrint();

//$tpl->showDebugInfo();
?>