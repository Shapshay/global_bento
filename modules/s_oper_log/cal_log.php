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

$result = mysql_query("SELECT COUNT(*) AS count FROM calls_log WHERE res <> 0");
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
		calls_log.id as id, 
		calls_log.date_start as date_start,
		calls_log.date_end as date_end,
		oper_calls.link as link,
		roots.name as oper,
		calls_log.oper_id as oper_id,
		oper_calls.phone1 as phone,
		res_calls.title as res,
		res_calls.id as res_id
		FROM calls_log, oper_calls, roots, res_calls
		WHERE 
		calls_log.date_end <> '0000-00-00 00:00:00' AND 
		calls_log.id = oper_calls.calls_log_id AND
		calls_log.oper_id = roots.id AND 
		calls_log.res = res_calls.id
		GROUP BY calls_log.id
		ORDER BY calls_log.date_start DESC
		LIMIT $start , $limit";
$result = mysql_query( $SQL ) or die("Couldn’t execute query.".mysql_error());
$responce->page = $page;
$responce->total = $total_pages;
$responce->records = $count;
$i=0;
while($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	if($row['res']=='Точная дата'){
		$sql2 = "SELECT
			users.date_end as date_end
			FROM phones
			LEFT OUTER JOIN users ON phones.user_id = users.id
			WHERE phone = '".$row['phone']."' LIMIT 1";
		$result2 = mysql_query($sql2);
		$row2 = mysql_fetch_array($result2,MYSQL_ASSOC);
		$td = date("d-m-Y",strtotime($row2['date_end']));
	}
	else{
		$td = '-';
	}
	
	
	$audio_link = '<a href="javascript:PlayCall(\''.$row['link'].'\', \''.$row['oper_id'].'\', \''.$row['phone'].'\', \''.$row['res'].'\', \''.$row['res_id'].'\');">'.$row['link'].'</a>';
	$responce->rows[$i]['id']=$row[id];
	$responce->rows[$i]['cell']=array("",$row['id'],$row['oper'],$row['date_start'],$row['date_end'],$row['res'],$row['phone'],$audio_link,$td);
	$i++;
} 
echo json_encode($responce);

?>