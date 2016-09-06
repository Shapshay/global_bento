<?php
define("DB_HOST", "localhost");
define("DB_NAME", "v_6658_coap_db");
define("DB_LOGIN", "v_6658_db_user");
define("DB_PASSWORD", "mfI037r6");
	// Устанавливает соединение с базой. В случае ошибки выводится уведомление.

	function db_connect($db_host, $db_login, $db_password) {
		$conect_id = mysql_connect($db_host, $db_login, $db_password);
		if (!$conect_id) {
			exit();
		}
		else{
			return $conect_id;
		}
	}

	function db_select_db($db_name) {
		if (!mysql_select_db($db_name)) {
			echo "<br><b>Error in selecting database</b><br>";
			echo "<br><b>Error #: </b>" . db_errno() . "<br>";
			echo "<br><b>Error message: </b>" . db_error() . "<br>";
			exit;
		}
		$result = db_query('SET NAMES utf8;');
	}

	function db_query($query) {
		if (!$result = mysql_query($query)) {
			echo "<br><b>$query</b><br>";
			echo "<br><b>Error in executing query</b><br>";
			echo "<br><b>Error #: </b>" . db_errno() . "<br>";
			echo "<br><b>Error message: </b>" . db_error() . "<br>";
		} else {
			return $result;
		}
	}

	function db_num_rows($result) {
		return mysql_num_rows($result);
	}
	
	function db_fetch_array($result) {
		return mysql_fetch_array($result);
	}

	function db_insert_id() {
		return mysql_insert_id();
	}
	
	error_reporting (E_ALL);
	$link = db_connect(DB_HOST, DB_LOGIN, DB_PASSWORD);
	db_select_db(DB_NAME);

//**********************************************************************************************************


session_name('ROOT');
@session_start('ROOT');
function checkAuth() {
	//if (!session_is_registered('lgn') || !session_is_registered('psw')) return false;
	if (!$_SESSION['lgn'] || !$_SESSION['psw']) return false;
	
	$result = db_query("SELECT * FROM roots WHERE login = '".$_SESSION['lgn']."' AND password = MD5('".$_SESSION['psw']."') LIMIT 1");
	if (db_num_rows($result) > 0) {
		return true;
	}
	else{
		return false;
	}
}
//echo "Y";
if (!checkAuth()) {
	echo '<script>window.location = "index.php"; </script>';
	exit;
}


header('Content-Type: text/x-csv; charset=utf-8');
header("Content-Disposition: attachment;filename=".date("d-m-Y")."-export.xls");
header("Content-Transfer-Encoding: binary ");

$csv_output ='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta name="author" content="SKIV" />
<title>Excel</title>
</head>
<body>
<table>
<tr>
			<td align="center">№</td>
			<td align="center"><strong>ID юзера</strong></td>
			<td align="center"><strong>E-mail</strong></td>
			<td align="center"><strong>Имя</strong></td>
			<td align="center"><strong>Телефон</strong></td>
			<td align="center"><strong>Дата регистрации</strong></td>
</tr>
';
echo $_POST['date_start'].'<br>';
$result = db_query("SELECT * FROM users WHERE data_reg >= '".$_POST['date_start']."' ORDER BY data_reg DESC");
$i=1;
while ($row = db_fetch_array($result)) {
	$csv_output.='<tr>
		<td>'.$i.'</td>
		<td>'.$row['id'].'</td>
		<td>'.$row['email'].'</td>
		<td>'.$row['name'].'</td>
		<td>'.$row['phone'].'</td>
		<td>'.$row['data_reg'].'</td>
		</tr>';
$i++;
}

$csv_output .='</table></body></html>';
// И наконец выгрузка в EXCEL - что в скрипте как обычный вывод
echo $csv_output;

?>