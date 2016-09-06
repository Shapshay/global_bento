<?php
	include("../../inc/access.php");
   //читаем новые значения
    $id = $_POST['id'];
	// connect to the database
	$db = mysql_pconnect(DB_HOST, DB_LOGIN, DB_PASSWORD)
			or die("Connection Error: " . mysql_error());

	mysql_select_db(DB_NAME) or die("Error conecting to db.");
	$result = mysql_query('SET NAMES utf8;');
	
	if($_POST['oper']=='del'){
		$result = mysql_query("DELETE FROM roots WHERE id = ".$id);
	}
 
   ?>
