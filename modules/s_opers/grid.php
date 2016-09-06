<?php
include("../../inc/access.php");

$examp = $_REQUEST["q"]; //query number

//echo $_REQUEST["_search"]."R*<br>";

$_REQUEST['page']; // get the requested page
$_REQUEST['rows']; // get how many rows we want to have into the grid
$_REQUEST['sidx']; // get index row - i.e. user click to sort
$_REQUEST['sord']; // get the direction
if(!$sidx) $sidx =1;

$totalrows = isset($_REQUEST['totalrows']) ? $_REQUEST['totalrows']: false;
if($totalrows) {
	$limit = $totalrows;
}

// connect to the database
$db = mysql_pconnect(DB_HOST, DB_LOGIN, DB_PASSWORD)
or die("Connection Error: " . mysql_error());

mysql_select_db(DB_NAME) or die("Error conecting to db.");
$result = mysql_query('SET NAMES utf8;');

$result = mysql_query("SELECT COUNT(*) AS count FROM roots WHERE rights <> 0");
$row = mysql_fetch_array($result,MYSQL_ASSOC);
$count = $row['count'];
			
if( $count >0 ) {
	$total_pages = ceil($count/$limit);
} else {
	$total_pages = 0;
}
if ($page > $total_pages) $page=$total_pages;
if ($limit<0) $limit = 0;
$start = $limit*$page - $limit; // do not put $limit*($page - 1)
if ($start<0) $start = 0;
$where= "";
//if($where!=''){
//	$where.= "ch_id = '22'";
//}
//else{
	//$where.= $where." AND ch_id = ".$_GET['ch'];
//}

$SQL = "SELECT 
		roots.id AS id,
		roots.reg_date AS reg_date, 
		roots.session_num AS session_num,
		roots.phone AS phone, 
		roots.login AS login, 
		roots.name AS name
	FROM roots 
	WHERE roots.rights <> 0
	ORDER BY reg_date DESC";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[id];
	$responce->rows[$i]['cell']=array("",$row[id],$row[reg_date],$row[login],$row[name],$row[session_num],$row[phone]);
	$i++;
} 
echo json_encode($responce);

?>