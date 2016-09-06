<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
class BDfunc
{


	public $status;
	public $count;
	public $outsql;
	public $ins_id;

	private $conn;
	private $operation = 'SELECT';
	private $table;
	private $select = '*';
	private $joins;
	private $order;
	private $order_type;
	private $limit;
	private $offset;
	private $group;
	private $having;
	private $where;



	//constructor, create connection
	public function __construct()
	{
		$xml_patch = '/var/www/html/perch/adm/inc/config.xml';
		$xml = simplexml_load_file($xml_patch);
		$server_name=trim($xml->bd_config->server_name);
		$user_name=trim($xml->bd_config->user_name);
		$password=trim($xml->bd_config->password);
		$bd_name=trim($xml->bd_config->bd_name);
		$conn = new mysqli($server_name, $user_name, $password, $bd_name);
		$this->status="";
		// Oh no! A connect_errno exists so the connection attempt failed!
		if ($conn->connect_errno) {
			// The connection failed. What do you want to do?

			// Let's try this:
			$this->status= "Sorry, this website is experiencing problems.";

			// Something you should not do on a public site, but this example will show you
			// anyways, is print out MySQL error related information -- you might log this
			$this->status.= "Error: Failed to make a MySQL connection, here is why: \n";
			$this->status.= "Errno: " . $conn->connect_errno . "\n";
			$this->status.= "Error: " . $conn->connect_error . "\n";

			// You might want to show them something nice, but we will simply exit
			exit;
		}

		$this->conn=$conn;

		if (!$conn->set_charset("utf8")) {
			$this->status.= sprintf("Error loading character set utf8: %s\n", $conn->error);
			exit();
		} else {
			$this->status.=sprintf("Current character set: %s\n", $conn->character_set_name());
		}


	}

	//destroy connection
	public function destroy() {
		$this->conn->close();
		$this->status="";
	}

	//create data base
	public function bd_create($bd_name)
	{
		$conn=$this->conn;
		$this->status="";
		// Create database
		$sql = "CREATE DATABASE ".$bd_name;
		if ($conn->query($sql)) {
			$this->status="Database created successfully";
		} else {
			$this->status=sprintf("Error creating database - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//create table
	public function table_create($tab_name,$tab_columns)
	{
		$conn=$this->conn;
		$this->status="";
		$columns = " (id INT(11) NOT NULL AUTO_INCREMENT";
		if (is_array($tab_columns)) {
			foreach ($tab_columns as $key => $value) {
				$columns.=", ".$key." ".$value;
			}
		}
		$columns.=", PRIMARY KEY (id))";
		$sql="CREATE TABLE ".$tab_name.$columns;
		//echo $sql;
		if ($conn->query($sql)) {
			$this->status= "Table created successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	public function table_create_uniq_index($tab_name,$ind_name,$field1="",$field2="")
	{
		$conn=$this->conn;
		$this->status="";
		$columns = "";
		if ($field1=="") {
			$field1=$ind_name;
		}
		if ($field2=="") {
			$columns.=$field1;
		} else {
			$columns.=$field1.",".$field2;
		}
		$sql="ALTER TABLE ".$tab_name." ADD UNIQUE ".$ind_name." (".$columns.");";
		//echo $sql;
		if ($conn->query($sql)) {
			$this->status= "Index created successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}


	//delete table
	public function table_delete($tab_name)
	{
		$conn=$this->conn;
		$this->status="";
		$sql="DROP TABLE IF EXISTS ".$tab_name;
		if ($conn->query($sql)) {
			$this->status= "Table droped successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//create element of table
	public function element_create($tab_name,$columns)
	{
		$conn=$this->conn;
		$this->status="";
		$fields = "(";
		$values = "(";
		$first = true;
		if (is_array($columns)) {
			foreach ($columns as $key => $value) {
				if ($first) {
					$fields.=	$key;
					if($value!='NOW()'){
						$values.= "'".$value."'";
					}
					else{
						$values.= $value;
					}
					$first = false;
				} else {
					$fields.=	", ".$key;

					if($value!='NOW()'){
						$values.= ", '".$value."'";
					}
					else{
						$values.= ", ".$value;
					}
				}
			}
		}
		$fields .= ")";
		$values .= ")";
		$sql="INSERT INTO ".$tab_name." ".$fields." VALUES ".$values;
		$this->outsql=$sql;
		if ($conn->query($sql)) {
			$this->status = "Element created successfully";
			$row_cnt = $conn->insert_id;
			$columns["id"]=$row_cnt;
			$this->ins_id = $row_cnt;
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//updates element of the table
	public function element_update($tab_name,$id,$columns)
	{
		$conn=$this->conn;
		$this->status="";
		$conditions="";
		$first=true;
		if (is_array($columns)) {
			foreach ($columns as $key => $value) {
				if ($first) {
					$conditions.= $key."='".$value."'";
					$first = false;
				} else {
					if($value!='NOW()'){
						$conditions.= ", ".$key."='".$value."'";
					}
					else{
						$conditions.= ", ".$key."=".$value;
					}
				}
			}
		}
		$sql="UPDATE ".$tab_name." SET ".$conditions." WHERE id=".$id;
		if ($conn->query($sql)) {
			$this->status="Element updated successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//updates element of the table for fields
	public function element_fields_update($tab_name,$where,$columns)
	{
		$conn=$this->conn;
		$this->status="";
		$conditions="";
		$first=true;
		if (is_array($columns)) {
			foreach ($columns as $key => $value) {
				if ($first) {
					$conditions.= $key."='".$value."'";
					$first = false;
				} else {
					if($value!='NOW()'){
						$conditions.= ", ".$key."='".$value."'";
					}
					else{
						$conditions.= ", ".$key."=".$value;
					}
				}
			}
		}
		$sql="UPDATE ".$tab_name." SET ".$conditions.$where;
		if ($conn->query($sql)) {
			$this->status="Element updated successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//updates element of the table for fields
	public function element_free_update($sql)
	{
		$conn=$this->conn;
		$this->status="";
		if ($conn->query($sql)) {
			$this->status="Element updated successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//find element by ID
	public function element_find($tab_name,$id)
	{
		$conn=$this->conn;
		$this->status="";
		$sql="SELECT * FROM ".$tab_name." WHERE id=".$id." LIMIT 1";
		$this->outsql=$sql;
		if ($result = $conn->query($sql)) {
			if ($result->num_rows===0) {
				$this->status="Result is empty";
				$this->count=0;
			} else {
				$this->status = "Element finded successfully";
				$element = $result->fetch_assoc();
				$this->count=1;
				return $element;
			}
		}	else {
			$this->count=0;
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}

	}

	//find element by field value
	public function element_find_by_field($tab_name,$field_name,$value)
	{
		$conn=$this->conn;
		$this->status="";
		if (is_string($value)) {
			$value="'".$value."'";
		}
		$sql="SELECT * FROM ".$tab_name." WHERE ".$field_name."=".$value." LIMIT 1";
		$this->outsql=$sql;
		if ($result = $conn->query($sql)) {
			if ($result->num_rows===0) {
				$this->status="Result is empty";
				$this->count=0;
			} else {
				$this->status= "Element finded successfully";
				$element = $result->fetch_assoc();
				$this->count=1;
				return $element;
			}
		}	else {
			$this->count=0;
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}

	}

	//delete element from the table
	public function element_delete($tab_name,$id)
	{
		$conn=$this->conn;
		$this->status="";
		$sql="DELETE FROM ".$tab_name." WHERE id=".$id;
		if ($conn->query($sql)) {
			$this->status="Element deleted successfully";
		}	else {
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//performs request to the base
	public function dbselect($parameters) {

		if (isset($parameters["table"])) {
			$this->table=$parameters["table"];
		}
		if (isset($parameters["select"])) {
			$this->select($parameters["select"]);
		} else {
			$this->select("*");
		}
		if (isset($parameters["where"])) {
			$this->where($parameters["where"]);
		}
		if (isset($parameters["order"])) {
			$this->order($parameters["order"]);
		}
		if (isset($parameters["order_type"])) {
			$this->order_type($parameters["order_type"]);
		}
		if (isset($parameters["limit"])) {
			$this->limit($parameters["limit"]);
		}
		if (isset($parameters["offset"])) {
			$this->offset($parameters["offset"]);
		}
		if (isset($parameters["joins"])) {
			$this->joins($parameters["joins"]);
		}
		if (isset($parameters["having"])) {
			$this->having($parameters["having"]);
		}
		if (isset($parameters["group"])) {
			$this->group($parameters["group"]);
		}
		//return $this->build_select();
		$sql = $this->build_select();
		//echo $sql;
		$this->status="";
		$this->outsql=$sql;
		$conn=$this->conn;
		if ($result = $conn->query($sql)) {
			if ($result->num_rows===0) {
				$this->status= "Result is empty";
				$this->count=0;
			} else {
				$this->status = "Elements finded successfully";
				$elements = array();
				while ($actor = $result->fetch_assoc()) {
					$elements[]=$actor;
					//print_r($actor);
				}
				$this->count=$result->num_rows;
				return $elements;
			}
		}	else {
			$this->count=0;
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	private function where($where)
	{
		$this->where = is_array($where) ? implode(",",$where) : $where;
		return $this;
	}

	private function order($order)
	{
		$this->order = is_array($order) ? implode(",",$order) : $order;
		return $this;
	}

	private function order_type($order_type)
	{
		$this->order_type = $order_type;
		return $this;
	}

	private function group($group)
	{
		$this->group = $group;
		return $this;
	}

	private function having($having)
	{
		$this->having = $having;
		return $this;
	}

	private function limit($limit)
	{
		$this->limit = intval($limit);
		return $this;
	}

	private function offset($offset)
	{
		$this->offset = intval($offset);
		return $this;
	}

	private function select($select)
	{
		$this->operation = 'SELECT';
		$this->select = is_array($select) ? implode(",",$select) : $select;
		return $this;
	}

	private function joins($joins)
	{
		$this->joins = $joins;
		return $this;
	}

	private function build_select()
	{
		$sql = "SELECT $this->select FROM $this->table";

		if ($this->joins)
			$sql .= ' ' . $this->joins;

		if ($this->where)
			$sql .= " WHERE $this->where";

		if ($this->group)
			$sql .= " GROUP BY $this->group";

		if ($this->having)
			$sql .= " HAVING $this->having";

		if ($this->order)
			$sql .= " ORDER BY $this->order";

		if ($this->order_type)
			$sql .= " $this->order_type";

		if ($this->limit || $this->offset)
			$sql = $this->limit_exp($sql,$this->offset,$this->limit);

		$this->table = '';
		$this->select = '';
		$this->joins = '';
		$this->order = '';
		$this->order_type = '';
		$this->limit = '';
		$this->offset = '';
		$this->group = '';
		$this->having = '';
		$this->where = '';

		return $sql;
	}

	private function limit_exp($sql, $offset, $limit)
	{
		$offset = is_null($offset) ? '' : intval($offset) . ',';
		$limit = intval($limit);
		return "$sql LIMIT {$offset}$limit";
	}

	//free request to the base
	public function db_free_query($sql) {
		$this->status="";
		$this->outsql=$sql;
		$conn=$this->conn;
		if ($result = $conn->query($sql)) {
			if ($result->num_rows===0) {
				$this->status= "Result is empty";
				$this->count=0;
			} else {
				$this->status = "Elements finded successfully";
				$elements = array();
				while ($actor = $result->fetch_array()) {
					$elements[]=$actor;
					//print_r($actor);
				}
				$this->count=$result->num_rows;
				return $elements;
			}
		}	else {
			$this->count=0;
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

	//free request to the base
	public function db_free_del($sql) {
		$this->status="";
		$this->outsql=$sql;
		$conn=$this->conn;
		if ($result = $conn->query($sql)) {
			return 0;
		}	else {
			$this->count=0;
			$this->status=sprintf("Error - SQLSTATE %s.\n", $conn->sqlstate);
		}
	}

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Отчет <?php echo date("d-m-Y"); ?></title>
</head>
<body>
<?php
error_reporting (E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set ("Asia/Almaty");
require_once('/var/www/html/perch/phpmailer/class.phpmailer.php');
include("/var/www/html/perch/phpmailer/class.smtp.php");
$dbc = new BDFunc;
//date_default_timezone_set ("Asia/Almaty");
//*********FUNCTIONS*********************************************************/
function PolisCount() {
	global $dbc;
	$rows = $dbc->dbselect(array(
			"table"=>"polises",
			"select"=>"COUNT(id) AS num",
			"where"=>"polises.dost <> ''  AND DATE_FORMAT(date_oform, '%Y%m%d') = '".date("Ymd")."'",
			"limit"=>"1"
		)
	);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$row = $rows[0];
		return $row['num'];
	}
	else{
		return 0;
	}
}
// Отправляем письмо
function sendMail3($mail_to, $subject, $body, $sender_name = "", $sender_mail = "") {
	$mail = new PHPMailer();
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->Host       = "185.98.6.157"; // SMTP server
	$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
											   // 1 = errors and messages
											   // 2 = messages only
	$mail->SMTPAuth   = true;                  // enable SMTP authentication
	$mail->Host       = "185.98.6.157"; // sets the SMTP server
	$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
	$mail->Username   = "send@kazavtoclub.kz"; // SMTP account username
	$mail->Password   = "Av3toc7lu5d";        // SMTP account password
	
	$mail->SetFrom($sender_mail, $sender_name);
	
	$mail->AddReplyTo($sender_mail,$sender_name);
	
	$mail->Subject    = $subject;

	$mail->AltBody    = "12345"; // optional, comment out and test
	
	$body             = $body;
	
	$mail->MsgHTML($body);
	
	$mail->AddAddress($mail_to, 'Subscriber');
	
	if(!$mail->Send()) {
		$sql = "Mailer Error: " . $mail->ErrorInfo;
	} else {
		$sql = "Message sent!";
	}
	
	if($sql==''){
		$sql = "TEST";
	}
	return $sql;
}

// SOAP
function objectToArray($d) {
if (is_object($d)) {
		// Gets the properties of the given object
		// with get_object_vars function
		$d = get_object_vars($d);
	}
	if (is_array($d)) {
		/*
		* Return array converted to object
		* Using __FUNCTION__ (Magic constant)
		* for recursive call
		*/
		return array_map(__FUNCTION__, $d);
	}
	else {
		// Return array
		return $d;
	}
}
function stdToArray($obj){
  $rc = (array)$obj;
  foreach($rc as $key => &$field){
	if(is_object($field))$field = $this->stdToArray($field);
  }
  return $rc;
}
//*** ****************************************************************************/
$rows = $dbc->dbselect(array(
		"table"=>"calls_log, users, res_calls",
		"select"=>"COUNT(DISTINCT users.name) as oper,
			COUNT(calls_log.id) as cal,
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td,
			(COUNT(calls_log.id)/COUNT(DISTINCT users.name)) as sred",
		"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
			calls_log.oper_id = users.id AND 
			calls_log.res = res_calls.id AND 
			DATE_FORMAT(calls_log.date_start, '%Y%m%d') = '".date("Ymd")."'",
		"limit"=>"1"
	)
);
$row = $rows[0];

$rows2 = $dbc->dbselect(array(
		"table"=>"calls_log, users, res_calls",
		"select"=>"COUNT(DISTINCT users.name) as oper,
			COUNT(calls_log.id) as cal,
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td,
			(COUNT(calls_log.id)/COUNT(DISTINCT users.name)) as sred",
		"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
			calls_log.oper_id = users.id AND 
			users.prod = 1 AND 
			calls_log.res = res_calls.id AND 
			DATE_FORMAT(calls_log.date_start, '%Y%m%d') = '".date("Ymd")."'",
		"limit"=>"1"
	)
);
$row2 = $rows2[0];

$rows3 = $dbc->dbselect(array(
		"table"=>"calls_log, users, res_calls",
		"select"=>"COUNT(DISTINCT users.name) as oper,
			COUNT(calls_log.id) as cal,
			SUM(CASE WHEN calls_log.res=4 THEN 1 ELSE 0 END) as td,
			(COUNT(calls_log.id)/COUNT(DISTINCT users.name)) as sred",
		"where"=>"calls_log.date_end <> '0000-00-00 00:00:00' AND 
			calls_log.oper_id = users.id AND 
			users.prod = 0 AND 
			calls_log.res = res_calls.id AND 
			DATE_FORMAT(calls_log.date_start, '%Y%m%d') = '".date("Ymd")."'",
		"limit"=>"1"
	)
);
$row3 = $rows3[0];

$MAIN_TABLE = '<p><strong>Основная таблица</strong></p>
		<p>
		<table border=1>
		<thead>
		<tr>
		<th width="200"></th>
		<th width="200"><b>Кол-во менеджеров</b></th>
		<th width="100"><b>Кол-во звонков</b></th>
		<th width="100"><b>Кол-во ТД</b></th>
		<th width="100"><b>Кол-во страховок</b></th>
		<th width="100"><b>Звонков на менеджера</b></th>
		</tr>
		</thead>
		<tbody>
		<tr>
		<td><b>Продажники</b></td>
		<td>'.$row2['oper'].'</td>
		<td>'.$row2['cal'].'</td>
		<td>'.$row2['td'].'</td>
		<td>'.PolisCount().'</td>
		<td>'.$row2['sred'].'</td>
		</tr>
		<tr>
		<td><b>Младшие менеджеры</b></td>
		<td>'.$row3['oper'].'</td>
		<td>'.$row3['cal'].'</td>
		<td>'.$row3['td'].'</td>
		<td>0</td>
		<td>'.$row3['sred'].'</td>
		</tr>
		<tr>
		<td><b>Итого</b></td>
		<td>'.$row['oper'].'</td>
		<td>'.$row['cal'].'</td>
		<td>'.$row['td'].'</td>
		<td>'.PolisCount().'</td>
		<td>'.$row['sred'].'</td>
		</tr>
		</tbody>
		</table><p></p>';
//echo "SELECT COUNT(id) AS num FROM polises WHERE polises.local <> ''  AND DATE_FORMAT(date_oform, '%Y%m%d') = '".date("Y-m-d")."'";
echo $MAIN_TABLE;

//*** ****************************************************************************/
ini_set("soap.wsdl_cache_enabled", "0" ); 
$client = new SoapClient("http://192.168.0.220/akk/ws/wsphp.1cws?wsdl", 
	array( 
	'login' => 'ws', 
	'password' => '123456', 
	'trace' => true
	) 
);
$result = $client->BackLock(); 
$array = objectToArray($result);
$back_arr = str_replace('<table>','<table border=1 cellpadding=10>',$array['return']);
$BACK_TABLE = '<p><strong>Баклог</strong></p>
		<p>'.$back_arr.'</p>';
echo $BACK_TABLE;
//*** ****************************************************************************/
$rows = $dbc->dbselect(array(
		"table"=>"control_log",
		"select"=>"croots.name AS control, 
			COUNT(control) as amount,
			sum(control) as good,
			COUNT(control)-sum(control) as bad",
		"joins"=>"LEFT OUTER JOIN users as croots ON control_log.root_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d")."'",
		"group"=>"croots.name"
	)
);
$all_count = 0;
$all_good = 0;
$all_bad = 0;
$SUPERVISER_ROWS = '<p><strong>Прослушивание звонков</strong></p>
		<p>
		<table border=1>
		<thead>
		<tr>
		<th width="400"><b>Проверяющий</b></th>
		<th width="100"><b>Проверенно</b></th>
		<th width="100"><b>Хороших</b></th>
		<th width="100"><b>Плохих</b></th>
		</tr>
		</thead>
		<tbody>';
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$all_count+= $row['amount'];
		$all_good+= $row['good'];
		$all_bad+= $row['bad'];
		
		$SUPERVISER_ROWS.='<tr>
			<td>'.$row['control'].'</td>
			<td>'.$row['amount'].'</td>
			<td>'.$row['good'].'</td>
			<td>'.$row['bad'].'</td>
			</tr>';
	}
}
else{
	$SUPERVISER_ROWS.='<tr>
			<td colspan="4" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$SUPERVISER_ROWS.= '</tbody>
	<tfoot>
	<tr>
	<th style="padding-left:5px;">Итого:</th>
	<th align="center"><b>'.$all_count.'</b></th>
	<th><b>'.$all_good.'</b></th>
	<th><b>'.$all_bad.'</b></th>
	</tr>
	</tfoot>
	</table><p></p>';
	
echo $SUPERVISER_ROWS;
#############################################################
$rows = $dbc->dbselect(array(
		"table"=>"pryanik",
		"select"=>"croots.name AS oper, 
			COUNT(date_start) as do_min,
			SUM(CASE WHEN post_timer_start='1970-01-01 06:00:00' THEN 0 ELSE 1 END) as posle_min,
			SUM(CASE WHEN obrab='1' THEN 1 ELSE 0 END) as count_obrab",
		"joins"=>"LEFT OUTER JOIN users as croots ON pryanik.oper_id = croots.id",
		"where"=>"DATE_FORMAT(date,'%Y-%m-%d')='".date("Y-m-d")."'",
		"group"=>"croots.name"
	)
);
$all_pryan_count = 0;
$all_posle_min = 0;
$all_count_obrab = 0;

$PRAYNIK_ROWS = '<p>&nbsp;</p>
	<p><strong>Таблица пряников</strong></p>
	<p><table border=1>
	<thead>
	<tr>
	<th width="400"><b>Оператор</b></th>
	<th width="100"><b>До звонка > минуты</b></th>
	<th width="100"><b>После звонка > минуты</b></th>
	<th width="100"><b>Пряников</b></th>
	</tr>
	</thead>
	<tbody>';
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
		$all_pryan_count+= $row['do_min'];
		$all_posle_min+= $row['posle_min'];
		$all_count_obrab+= $row['count_obrab'];
		$PRAYNIK_ROWS.='<tr>
			<td>'.$row['oper'].'</td>
			<td>'.$row['do_min'].'</td>
			<td>'.$row['posle_min'].'</td>
			<td>'.$row['count_obrab'].'</td>
			</tr>';
	}
}
else{
	$PRAYNIK_ROWS.='<tr>
			<td colspan="4" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$PRAYNIK_ROWS.= '</tbody>
	<tfoot>
	<tr>
	<th style="padding-left:5px;">Итого:</th>
	<th align="center">'.$all_pryan_count.'</th>
	<th>'.$all_posle_min.'</th>
	<th>'.$all_count_obrab.'</th>
	</tr>
	</tfoot>
	</table><p></p>';	
	
echo $PRAYNIK_ROWS;	
#############################################################

$rows = $dbc->dbselect(array(
    "table"=>"post_control",
    "select"=>"COUNT(post_control.id) as zvon,
            SUM(CASE WHEN post_control.email='' THEN 0 ELSE 1 END) as emails,
            ROUND(AVG(post_control.ocen), 2) as avg_ocen,
            SUM(CASE WHEN post_control.result=1 THEN 1 ELSE 0 END) as res1,
            SUM(CASE WHEN post_control.result=2 THEN 1 ELSE 0 END) as res2,
            SUM(CASE WHEN post_control.result=3 THEN 1 ELSE 0 END) as res3,
            SUM(CASE WHEN post_control.result=4 THEN 1 ELSE 0 END) as res4,
            SUM(CASE WHEN post_control.result=5 THEN 1 ELSE 0 END) as res5",
    "where"=>"post_control.result <> 0
            AND DATE_FORMAT(post_control.date_obrab,'%Y%m%d')='".date("Ymd")."'"));



$POST_ROWS = '<p>&nbsp;</p>
    <p><strong>Таблица POST-контроля</strong></p>
	<p><table border=1>
	    <thead>
        <tr>
            <th rowspan="2">Количество звонков в день</th>
            <th rowspan="2">Количество email</th>
            <th rowspan="2">Средний бал по качеству</th>
            <th colspan="5">Статистика</th>
        </tr>
        <tr>
            <th>Перезвонить</th>
            <th>Не дозвон</th>
            <th>Неверный номер</th>
            <th>Агент</th>
            <th>Отработан</th>
        </tr>
        </thead>
        <tbody id="table_rows">';
$numRows = $dbc->count;
if ($numRows > 0) {
	foreach($rows as $row){
        $POST_ROWS.='<tr>
                    <td>'.$row['zvon'].'</td>
                    <td>'.$row['emails'].'</td>
                    <td>'.$row['avg_ocen'].'</td>
                    <td>'.$row['res1'].'</td>
                    <td>'.$row['res2'].'</td>
                    <td>'.$row['res3'].'</td>
                    <td>'.$row['res4'].'</td>
                    <td>'.$row['res5'].'</td>
                    </tr>';
	}
}
else{
    $POST_ROWS.='<tr>
			<td colspan="8" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$POST_ROWS.= '</tbody>
    </table>
    </p></p>';

echo $POST_ROWS;

#############################################################
$_sendTo = 'tigay84@list.ru';
$_sendFrom = 'send@kazavtoclub.kz';
$_mailSubject = 'Отчеты Bento';
$_mailFrom = "Bento CRM";
$mail_body = $MAIN_TABLE.$BACK_TABLE.$SUPERVISER_ROWS.$PRAYNIK_ROWS.$POST_ROWS;
sendMail3('tigay84@list.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('mtyrlybekova@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('hr@kazavtoclub.kz', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('skiv_80@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('e.kharitonova777@gmail.com', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('aida_89__@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
$test = sendMail3('skiv.weber@gmail.com', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
echo "<p>ОК = ".$test."</p>"
?>

</body>
</html>