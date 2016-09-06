<?php
# SETTINGS #############################################################################
$moduleName = "ib_modul";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "main" => $prefix . "main.tpl",
));
# MAIN #################################################################################
$tpl->assign("META_LINK", '');
$url = 'http://192.168.1.227/inc/api.php';
$postdata = 'u_lgn='.$_SESSION['lgn'];
$result = post_content( $url, $postdata );
$j_str = $result['content'];
$IBAnswer = json_decode($j_str);

$ib_url = 'http://192.168.1.227/moi_zadachi/?comp='.$IBAnswer->id;
$tpl->assign("IB_URL", $ib_url);
$tpl->assign("IB_TITLE", $IBAnswer->c_title);


$tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
?>