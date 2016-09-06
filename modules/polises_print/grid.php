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

$result = mysql_query("SELECT COUNT(*) AS count FROM polises WHERE kasko = ''");
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
if(isset($_GET['oper'])){
	//$add_sql = 'AND polises.oper_id = '.$_GET['oper'];
	$add_sql = '';
}
else{
	$add_sql = '';
}
$SQL = "SELECT 
				polises.id AS id, 
				polises.bso_number AS bso_number, 
				polises.date_write AS date_oform, 
				polises.date_start AS date_start, 
				polises.date_end AS date_end, 
				roots.name AS oper
			FROM polises 
			LEFT OUTER JOIN roots ON polises.oper_id = roots.id 
			WHERE polises.status = '1' AND polises.office_id = ".$_GET['office_id']." AND DATE_FORMAT(polises.date_write,'%Y-%m-%d') = '".date('Y-m-d')."'
			ORDER BY date_write ASC 
			LIMIT $start , $limit";
//echo $SQL;
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$responce->rows[$i]['id']=$row[id];
	
	$responce->rows[$i]['cell']=array("",$row['id'],$row['bso_number'],date("d-m-Y H:i",strtotime($row['date_oform'])),date("d-m-Y",strtotime($row['date_start'])),date("d-m-Y",strtotime($row['date_end'])),$row['oper']);
	$i++;
} 
echo json_encode($responce);

?>