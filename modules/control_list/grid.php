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

$result = mysql_query("SELECT COUNT(*) AS count FROM control_log");
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

$SQL = "SELECT 
				control_log.id AS id, 
				control_log.date AS date,
				croots.name AS control, 
				roots.name AS oper,
				control_log.phone AS phone,
				control_log.control AS control_res
			FROM control_log 
			LEFT OUTER JOIN roots ON control_log.oper_id = roots.id 
			LEFT OUTER JOIN roots as croots ON control_log.root_id = croots.id
			ORDER BY date DESC 
			LIMIT $start , $limit";
//echo $SQL;
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[id];
	if($row['control_res']==1){
		$control = "ХОРОШО";
	}
	else{
		$control = "ПЛОХО";
	}
	
	$responce->rows[$i]['cell']=array($row['id'],date("d-m-Y",strtotime($row['date'])),$row['control'],$row['oper'],$row['phone'],$control);
	$i++;
} 
echo json_encode($responce);

?>