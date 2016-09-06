<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 17.08.2016
 * Time: 11:58
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

function PolisCourName($p_id) {
    global $dbc;
    $row = $dbc->element_find_by_field('cour_polis','polis_id',$p_id);
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row2 = $dbc->element_find('users',$row['c_id']);
        $numRows = $dbc->count;
        if ($numRows > 0) {
            return $row2['name'];
        }
        else{
            return 0;
        }
    }
    else{
        return 0;
    }
}

//*** MAIN ****************************************************************************/
$SEND = false;
$PRINT_TABLE = "";
$INDOST_TABLE = "";
$COUR_TABLE = "";


// print
$rows = $dbc->dbselect(array(
        "table"=>"polises",
        "select"=>"polises.bso_number as bso_number,
            polises.date_write as date_write,
            polises.date_start as date_start",
        "where"=>"polises.status = 1 AND
            DATE_ADD(polises.date_write, INTERVAL 30 MINUTE) < NOW() AND
            polises.office_id = 1 AND 
            DATE_FORMAT(polises.date_write,'%Y%m%d')>20160821"
    )
);

$numRows = $dbc->count;
if($numRows){
    $SEND = true;
    $PRINT_TABLE = '<p><strong>Не распечатано более получаса</strong></p>
		<p>
		<table border=1>
		<thead>
		<tr>
		<th width="400"><b>БСО</b></th>
		<th width="100"><b>Дата выписки</b></th>
		<th width="100"><b>Дата начала действия</b></th>
		</tr>
		</thead>
		<tbody>';
    foreach($rows as $row){
        $PRINT_TABLE.='<tr>
			<td>'.$row['bso_number'].'</td>
			<td>'.$row['date_write'].'</td>
			<td>'.date("d-m-Y",strtotime($row['date_start'])).'</td>
			</tr>';
    }
    $PRINT_TABLE.='</tbody>
	    </table><p></p>';
}

// indost
$rows = $dbc->dbselect(array(
        "table"=>"polises",
        "select"=>"polises.bso_number as bso_number,
            polises.date_print as date_print,
            polises.date_dost as date_dost,
            polises.date_start as date_start",
        "where"=>"polises.status = 2 AND
            DATE_ADD(polises.date_print, INTERVAL 3 DAY) < NOW() AND
            polises.office_id = 1 AND 
            DATE_FORMAT(polises.date_write,'%Y%m%d')>20160821"
    )
);
$numRows = $dbc->count;
if($numRows){
    $SEND = true;
    $INDOST_TABLE = '<p><strong>Не назначен курьер более 3х суток</strong></p>
		<p>
		<table border=1>
		<thead>
		<tr>
		<th width="400"><b>БСО</b></th>
		<th width="100"><b>Дата распечатки</b></th>
		<th width="100"><b>Дата доставки</b></th>
		<th width="100"><b>Дата начала действия</b></th>
		</tr>
		</thead>
		<tbody>';
    foreach($rows as $row){
        $INDOST_TABLE.='<tr>
			<td>'.$row['bso_number'].'</td>
			<td>'.$row['date_print'].'</td>
			<td>'.date("d-m-Y",strtotime($row['date_dost'])).'</td>
			<td>'.date("d-m-Y",strtotime($row['date_start'])).'</td>
			</tr>';
    }
    $INDOST_TABLE.='</tbody>
	    </table><p></p>';
}

// cour
$rows = $dbc->dbselect(array(
        "table"=>"polises",
        "select"=>"polises.id as p_id,
            polises.bso_number as bso_number,
            polises.date_indost as date_indost,
            polises.date_dost as date_dost,
            polises.date_start as date_start",
        "where"=>"polises.status = 3 AND
            DATE_ADD(polises.date_indost, INTERVAL 3 DAY) < NOW() AND
            polises.office_id = 1 AND 
            DATE_FORMAT(polises.date_write,'%Y%m%d')>20160821"
    )
);
$numRows = $dbc->count;
if($numRows){
    $SEND = true;
    $COUR_TABLE = '<p><strong>Полис у курьера более 3х суток</strong></p>
		<p>
		<table border=1>
		<thead>
		<tr>
		<th width="400"><b>БСО</b></th>
		<th width="400"><b>Курьер</b></th>
		<th width="100"><b>Дата выдачи курьеру</b></th>
		<th width="100"><b>Дата доставки</b></th>
		<th width="100"><b>Дата начала действия</b></th>
		</tr>
		</thead>
		<tbody>';
    foreach($rows as $row){
        $COUR_TABLE.='<tr>
			<td>'.$row['bso_number'].'</td>
			<td>'.PolisCourName($row['p_id']).'</td>
			<td>'.$row['date_indost'].'</td>
			<td>'.date("d-m-Y",strtotime($row['date_dost'])).'</td>
			<td>'.date("d-m-Y",strtotime($row['date_start'])).'</td>
			</tr>';
    }
    $COUR_TABLE.='</tbody>
	    </table><p></p>';
}


#############################################################
if($SEND) {
    $_sendTo = 'tigay84@list.ru';
    $_sendFrom = 'send@kazavtoclub.kz';
    $_mailSubject = 'Красные полисы Bento';
    $_mailFrom = "Bento CRM";
    $mail_body = $PRINT_TABLE . $INDOST_TABLE . $COUR_TABLE;
    sendMail3('tigay84@list.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    sendMail3('mtyrlybekova@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    sendMail3('hr@kazavtoclub.kz', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    //sendMail3('skiv_80@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    sendMail3('e.kharitonova777@gmail.com', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    sendMail3('aida_89__@mail.ru', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    //$test = sendMail3('skiv.weber@gmail.com', $_mailSubject, $mail_body, $_mailFrom, $_sendFrom);
    //echo "<p>ОК = " . $test . "</p>";
}
else{
    echo "<p>Просрочек нет</p>";
}
?>

</body>
</html>