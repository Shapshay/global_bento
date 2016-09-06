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

$result = mysql_query("SELECT COUNT(*) AS count FROM oper_calls WHERE calls_log_id = ".$_GET['contact']);
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
		 id, 
		 call_date, 
		 phone1,
		 size, 
		 link
		FROM oper_calls 
		WHERE calls_log_id = ".$_GET['contact']." 
		ORDER BY call_date DESC 
		LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	$audio_link = '<a href="javascript:PlayCall(\''.$row['link'].'\');">'.$row['link'].'</a>';
	$responce->rows[$i]['id']=$row[id];
	$responce->rows[$i]['cell']=array($row['id'],$row['call_date'],$row['phone1'],$row['size'],$audio_link);
	$i++;
} 
echo json_encode($responce);

?>