<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 14.09.2016
 * Time: 15:02
 */
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
date_default_timezone_set ("Asia/Almaty");

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
    "table"=>"sto",
    "select"=>"users.name as oper,
            sto.date_call as date_call,
            COUNT(sto.id) as call_count,
            SUM(CASE WHEN sto.res_call_id='1' THEN 1 ELSE 0 END) as status1,
            SUM(CASE WHEN sto.res_call_id='2' THEN 1 ELSE 0 END) as status2,
            SUM(CASE WHEN sto.res_call_id='3' THEN 1 ELSE 0 END) as status3,
            SUM(CASE WHEN sto.res_call_id='4' THEN 1 ELSE 0 END) as status4,
            SUM(CASE WHEN sto.res_call_id='5' THEN 1 ELSE 0 END) as status5,
            sto.summa as summa",
    "joins"=>"LEFT OUTER JOIN users ON sto.oper_id = users.id",
    "where"=>"DATE_FORMAT(sto.date_call,'%Y%m%d')='".date("Ymd")."'",
    "group"=>"sto.oper_id"));
//echo $dbc->outsql;
$STO_CALLS_ROWS = '<p><strong>Статистика звонков СТО</strong></p>
		<p>
		<table border=1>
		<thead>
        <tr>
            <th rowspan="2">Дата</th>
            <th rowspan="2">Менеджер</th>
            <th rowspan="2">Количество звонков</th>
            <th colspan="5">Статистика</th>
        </tr>
        <tr>
            <th>Отработан</th>
            <th>Недозвон</th>
            <th>Ошибка номера</th>
            <th>Перезвонить</th>
            <th>Отказ</th>
        </tr>
        </thead>
		<tbody>';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach($rows as $row){
        $STO_CALLS_ROWS.= '<tr>
                    <td>'.$row['date_call'].'</td>
                    <td>'.$row['oper'].'</td>
                    <td>'.$row['call_count'].'</td>
                    <td>'.$row['status1'].'</td>
                    <td>'.$row['status2'].'</td>
                    <td>'.$row['status3'].'</td>
                    <td>'.$row['status4'].'</td>
                    <td>'.$row['status5'].'</td>
                    </tr>';
    }
}
else{
    $STO_CALLS_ROWS.='<tr>
			<td colspan="4" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$STO_CALLS_ROWS.= '</tbody>
	</table><p></p>';

echo $STO_CALLS_ROWS;
#############################################################
$rows = $dbc->dbselect(array(
    "table"=>"sto",
    "select"=>"sto_tochka.title as sto_name,
            sto.date_visit as date_visit,
            sto.name as name,
            sto.gn as gn,
            sto.summa as summa",
    "joins"=>"LEFT OUTER JOIN sto_tochka ON sto.sto_tochka_id = sto_tochka.id",
    "where"=>"sto.visit = 1
            AND DATE_FORMAT(sto.date_visit,'%Y%m%d')='".date("Ymd")."'"));


$STO_OK_ROWS = '<p>&nbsp;</p>
	<p><strong>Список посетивших СТО</strong></p>
	<p><table border=1>
	<thead>
        <tr>
            <th>СТО</th>
            <th>Дата</th>
            <th>Клиент</th>
            <th>Машина</th>
            <th>Сумма</th>
        </tr>
        </thead>
	<tbody>';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach($rows as $row){
        $STO_OK_ROWS.= '<tr>
                    <td>'.$row['sto_name'].'</td>
                    <td>'.$row['date_visit'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['gn'].'</td>
                    <td>'.$row['summa'].'</td>
                    </tr>';
    }
}
else{
    $STO_OK_ROWS.='<tr>
			<td colspan="4" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$STO_OK_ROWS.= '</tbody>
	</table><p></p>';

echo $STO_OK_ROWS;
#############################################################

$rows = $dbc->dbselect(array(
    "table"=>"sto",
    "select"=>"sto_tochka.title as sto_name,
            sto.date_dog as date_dog,
            sto.name as name,
            sto.gn as gn,
            users.name as oper",
    "joins"=>"LEFT OUTER JOIN sto_tochka ON sto.sto_tochka_id = sto_tochka.id
        LEFT OUTER JOIN users ON sto.sto_tochka_id = users.id",
    "where"=>"sto.visit = 0
            AND DATE_FORMAT(sto.date_dog,'%Y%m%d')='".date("Ymd")."'"));



$STO_ERR_ROWS = '<p>&nbsp;</p>
    <p><strong>Таблица непосетивших СТО</strong></p>
	<p><table border=1>
	    <thead>
        <tr>
            <th>СТО</th>
            <th>Дата договоренности</th>
            <th>Клиент</th>
            <th>Машина</th>
            <th>Менеджер</th>
        </tr>
        </thead>
        <tbody id="table_rows">';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach($rows as $row){
        $STO_ERR_ROWS.= '<tr>
                    <td>'.$row['sto_name'].'</td>
                    <td>'.$row['date_dog'].'</td>
                    <td>'.$row['name'].'</td>
                    <td>'.$row['gn'].'</td>
                    <td>'.$row['oper'].'</td>
                    </tr>';
    }
}
else{
    $STO_ERR_ROWS.='<tr>
			<td colspan="8" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$STO_ERR_ROWS.= '</tbody>
    </table>
    </p></p>';

echo $STO_ERR_ROWS;

#############################################################

$rows = $dbc->dbselect(array(
    "table"=>"sto",
    "select"=>"DATE_FORMAT(sto.date_call,'%Y%m%d') as date_call,
            COUNT(sto.id) as call_count,
            SUM(CASE WHEN sto.res_call_id='1' THEN 1 ELSE 0 END) as status1,
            SUM(CASE WHEN sto.res_call_id='2' THEN 1 ELSE 0 END) as status2,
            SUM(CASE WHEN sto.res_call_id='3' THEN 1 ELSE 0 END) as status3,
            SUM(CASE WHEN sto.res_call_id='4' THEN 1 ELSE 0 END) as status4,
            SUM(CASE WHEN sto.res_call_id='5' THEN 1 ELSE 0 END) as status5",
    "where"=>"DATE_FORMAT(sto.date_call,'%Y%m%d')='".date("Ymd")."'",
    "group"=>"DATE_FORMAT(sto.date_call,'%Y%m%d')"));





$STO_ROWS = '<p>&nbsp;</p>
    <p><strong>Сводная таблица СТО</strong></p>
	<p><table border=1>
	    <thead>
        <tr>
            <th rowspan="2">Кол-во приехавших</th>
            <th rowspan="2">Кол-во неприехавших</th>
            <th rowspan="2">Количество звонков</th>
            <th colspan="5">Статистика</th>
        </tr>
        <tr>
            <th>Отработан</th>
            <th>Недозвон</th>
            <th>Ошибка номера</th>
            <th>Перезвонить</th>
            <th>Отказ</th>
        </tr>
        </thead>
        <tbody id="table_rows">';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach($rows as $row){

        $rows2 = $dbc->dbselect(array(
            "table"=>"sto",
            "select"=>"SUM(CASE WHEN sto.visit='1' THEN 1 ELSE 0 END) as ok",
            "where"=>"DATE_FORMAT(sto.date_visit,'%Y%m%d')='".date("Ymd")."'"));
        $row2 = $rows2[0];
        $rows3 = $dbc->dbselect(array(
            "table"=>"sto",
            "select"=>"SUM(CASE WHEN sto.visit='0' THEN 1 ELSE 0 END) as err",
            "where"=>"DATE_FORMAT(sto.date_dog,'%Y%m%d')='".date("Ymd")."'"));
        $row3 = $rows3[0];

        $STO_ROWS.='<tr>
                    <td>'.$row2['ok'].'</td>
                    <td>'.$row3['err'].'</td>
                    <td>'.$row['call_count'].'</td>
                    <td>'.$row['status1'].'</td>
                    <td>'.$row['status2'].'</td>
                    <td>'.$row['status3'].'</td>
                    <td>'.$row['status4'].'</td>
                    <td>'.$row['status5'].'</td>
                    </tr>';
    }
}
else{
    $STO_ROWS.='<tr>
			<td colspan="8" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$STO_ROWS.= '</tbody>
    </table>
    </p></p>';

echo $STO_ROWS;

#############################################################

$rows = $dbc->dbselect(array(
    "table"=>"costs",
    "select"=>"sto_tochka.title as sto_name,
            costs.date_cost as date_cost,
            costs.title as title,
            costs.summa as summa",
    "joins"=>"LEFT OUTER JOIN sto_tochka ON costs.sto_tochka_id = sto_tochka.id",
    "where"=>"DATE_FORMAT(costs.date_cost,'%Y%m%d')='".date("Ymd")."'"));





$STO_COST_ROWS = '<p>&nbsp;</p>
    <p><strong>Таблица расходов СТО</strong></p>
	<p><table border=1>
	    <thead>
        <tr>
            <th>СТО</th>
            <th>Дата</th>
            <th>Наименование</th>
            <th>Сумма</th>
        </tr>
        </thead>
        <tbody id="table_rows">';
$numRows = $dbc->count;
if ($numRows > 0) {
    foreach($rows as $row){

        $STO_COST_ROWS.= '<tr>
                    <td>'.$row['sto_name'].'</td>
                    <td>'.$row['date_cost'].'</td>
                    <td>'.$row['title'].'</td>
                    <td>'.$row['summa'].'</td>
                    </tr>';
    }
}
else{
    $STO_COST_ROWS.='<tr>
			<td colspan="8" align="center"><strong>Нет данных за этот период !</strong></td>
			</tr>';
}
$STO_COST_ROWS.= '</tbody>
    </table>
    </p></p>';

echo $STO_COST_ROWS;

#############################################################
$_sendTo = 'tigay84@list.ru';
$_sendFrom = 'send@kazavtoclub.kz';
$_mailSubject = 'Отчеты Bento СТО';
$_mailFrom = "Bento CRM";
$mail_body = $STO_CALLS_ROWS.$STO_OK_ROWS.$STO_ERR_ROWS.$STO_ROWS.$STO_COST_ROWS;
sendMail3('tigay84@list.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
sendMail3('skiv_80@list.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);

?>

</body>
</html>