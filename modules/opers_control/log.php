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

$result = mysql_query("SELECT COUNT(*) AS count FROM oper_log WHERE oper_id = ".$_GET['r_id']);
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
		 oper_log.id AS id, 
		 oper_log.date_log AS date_log, 
		 oper_log.comment AS comment,
		 oper_act_type.title AS oper_act_type, 
		 oper_acts.title AS oper_acts
		FROM oper_log 
		LEFT OUTER JOIN oper_act_type ON oper_log.oper_act_type_id = oper_act_type.id
		LEFT OUTER JOIN oper_acts ON oper_log.oper_act_id = oper_acts.id
		WHERE oper_log.oper_id = ".$_GET['r_id']." 
		ORDER BY date_log DESC 
		LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[id];
	$responce->rows[$i]['cell']=array($row['id'],$row['date_log'],$row['oper_act_type'],$row['oper_acts'],$row['comment']);
	$i++;
} 
echo json_encode($responce);

?>