<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 09.12.2016
 * Time: 15:38
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
date_default_timezone_set ("Asia/Almaty");
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
        $xml_patch = '/var/www/html/adm/inc/config.xml';
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
$dbc = new BDFunc;

function getCountVerDay($auto_type, $ver_id){
    global $dbc;
    $res_arr = array();
    $rows = $dbc->dbselect(array(
            "table"=>"ver_log",
            "select"=>"SUM(CASE WHEN ver_obrab=1 THEN 1 ELSE 0 END) as res1,
                COUNT(id) as res2",
            "where"=>"DATE_FORMAT(ver_date,'%Y%m%d')='".date("Ymd")."' AND
                ver_id = ".$ver_id." AND 
                auto_type = ".$auto_type
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $row = $rows[0];
        $res_arr[0] = $row['res1'];
        $res_arr[1] = $row['res2'];
    }
    else{
        $res_arr[0] = 0;
        $res_arr[1] = 0;
    }
    return $res_arr;
}

// заголовок элемента
function getItemTitle($table, $item_id) {
    global $dbc;
    $row = $dbc->element_find($table,$item_id);
    $numRows = $dbc->count;
    if ($numRows > 0) {
        if ($row['title'] != '') return $row['title'];
        else return '';
    }
    return '';
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$add_field_txt = 'Дополнительное поле';

$num_day = (date('w'));

if($num_day==1){
    $minus_days = "-3 days";
}
else{
    $minus_days = "-1 days";
}

// verifikators
$u_rows = $dbc->dbselect(array(
        "table"=>"r_user_role",
        "select"=>"users.*",
        "joins"=>"LEFT JOIN users ON r_user_role.user_id = users.id",
        "where"=>"r_user_role.role_id = 14"
    )
);
$numRows = $dbc->count;
//echo $dbc->outsql;
if ($numRows > 0) {
    foreach ($u_rows as $u_row) {

        // Auto 1
        $auto1 = getCountVerDay(1, $u_row['id']);
        if ($auto1[1] == 0) {
            $rows = $dbc->dbselect(array(
                    "table" => "calls_log",
                    "select" => "opers.id as oper",
                    "joins" => "LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where" => "(
                (calls_log.rating1_id = 1 AND calls_log.rating2_id = 1) OR
                (calls_log.rating1_id = 2 AND calls_log.rating2_id = 2) OR
                (calls_log.rating1_id = 3 AND calls_log.rating2_id = 3) OR
                (calls_log.rating1_id = 4 AND calls_log.rating2_id = 4)
                ) AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '" . date("Ymd", strtotime($minus_days)) . "' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '" . date("Ymd", strtotime($minus_days)) . "' AND 
                opers.office_id = " . $u_row['office_id'],
                    "group" => "opers.name",
                    "order" => "opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto1_sql_arr = array();
                $i = 0;
                foreach ($rows as $row) {
                    $auto1_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                (
                (calls_log.rating1_id = 2 AND calls_log.rating2_id = 2) OR
                (calls_log.rating1_id = 3 AND calls_log.rating2_id = 3) OR
                (calls_log.rating1_id = 4 AND calls_log.rating2_id = 4)
                ) AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '" . date("Ymd", strtotime($minus_days)) . "' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '" . date("Ymd", strtotime($minus_days)) . "' AND
                calls_log.oper_id = " . $row['oper'] . "
                ORDER BY calls_log.date_end ASC
                LIMIT 5)";
                    $i++;
                }
                $auto1_sql = implode(" UNION ", $auto1_sql_arr);
                $result_rows = $dbc->db_free_query($auto1_sql);
                $numRows = $dbc->count;
                //echo $dbc->outsql;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row) {
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 1,
                            "ver_date"=>'NOW()'));
                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                            ));



                        $kol++;
                    }
                    $auto1 = array(0, $kol);
                } else {
                    $auto1 = array(0, 0);
                }
            } else {
                $auto1 = array(0, 0);
            }
        }
        
        echo $auto1[0].' / '.$auto1[1].'<br>';


        // Auto 2
        $auto2 = getCountVerDay(2, $u_row['id']);
        if($auto2[1]==0){
            $rows = $dbc->dbselect(array(
                    "table"=>"calls_log",
                    "select"=>"opers.id as oper",
                    "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where"=>"calls_log.res = 3 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                (
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1040", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1110", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1240", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1310", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1540", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1610", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1820", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1850", strtotime($minus_days))."'
                )
                ) AND
                opers.office_id = ".$u_row['office_id'],
                    "group"=>"opers.name",
                    "order"=>"opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto2_sql_arr = array();
                $i = 0;
                foreach ($rows as $row){
                    $auto2_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                calls_log.res = 3 AND
                (
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1040", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1110", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1240", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1310", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1540", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1610", strtotime($minus_days))."'
                )
                OR
                (
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') >= '".date("Ymd1820", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d%H%i') <= '".date("Ymd1850", strtotime($minus_days))."'
                )
                ) AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end ASC
                LIMIT 5)";
                    $i++;
                }
                $auto2_sql = implode(" UNION ", $auto2_sql_arr);
                $result_rows = $dbc->db_free_query($auto2_sql);
                $numRows = $dbc->count;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row){
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 2,
                            "ver_date"=>'NOW()'));
                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                        ));

                        $kol++;
                    }
                    $auto2 = array(0, $kol);
                }
                else{
                    $auto2 = array(0, 0);
                }
            }
            else{
                $auto2 = array(0, 0);
            }
        }

        echo $auto2[0].' / '.$auto2[1].'<br>';

        // Auto 3
        $auto3 = getCountVerDay(3, $u_row['id']);
        if($auto3[1]==0){
            $rows = $dbc->dbselect(array(
                    "table"=>"calls_log",
                    "select"=>"opers.id as oper",
                    "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where"=>"calls_log.rating1_id = 2 AND
                calls_log.rating2_id > 2 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".$u_row['office_id'],
                    "group"=>"opers.name",
                    "order"=>"opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto3_sql_arr = array();
                $i = 0;
                foreach ($rows as $row){
                    $auto3_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id = 2 AND
                calls_log.rating2_id > 2 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end ASC
                LIMIT 5) ";
                    $i++;
                }
                $auto3_sql = implode(" UNION ", $auto3_sql_arr);
                $result_rows = $dbc->db_free_query($auto3_sql);
                $numRows = $dbc->count;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row){
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 3 ,
                            "ver_date"=>'NOW()'));

                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                        ));

                        $kol++;
                    }
                    $auto3 = array(0, $kol);
                }
                else{
                    $auto3 = array(0, 0);
                }
            }
            else{
                $auto3 = array(0, 0);
            }
        }

        echo $auto3[0].' / '.$auto3[1].'<br>';

        // Auto 4
        $auto4 = getCountVerDay(4, $u_row['id']);
        if($auto4[1]==0){
            $rows = $dbc->dbselect(array(
                    "table"=>"calls_log",
                    "select"=>"opers.id as oper",
                    "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where"=>"calls_log.rating1_id = 3 AND
                calls_log.rating2_id > 3 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".$u_row['office_id'],
                    "group"=>"opers.name",
                    "order"=>"opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto4_sql_arr = array();
                $i = 0;
                foreach ($rows as $row){
                    $auto4_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE 
                calls_log.rating1_id = 3 AND
                calls_log.rating2_id > 3 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end ASC
                LIMIT 5)";
                    $i++;
                }
                $auto4_sql = implode(" UNION ", $auto4_sql_arr);
                $result_rows = $dbc->db_free_query($auto4_sql);
                $numRows = $dbc->count;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row){
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 4 ,
                            "ver_date"=>'NOW()'));

                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                        ));

                        $kol++;
                    }
                    $auto4 = array(0, $kol);
                }
                else{
                    $auto4 = array(0, 0);
                }
            }
            else{
                $auto4 = array(0, 0);
            }
        }

        echo $auto4[0].' / '.$auto4[1].'<br>';

        // Auto 5
        $auto5 = getCountVerDay(5, $u_row['id']);
        if($auto5[1]==0){
            $rows = $dbc->dbselect(array(
                    "table"=>"calls_log",
                    "select"=>"opers.id as oper",
                    "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where"=>"calls_log.rating1_id = 1 AND calls_log.rating2_id = 1 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".$u_row['office_id'],
                    "group"=>"opers.name",
                    "order"=>"opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto5_sql_arr = array();
                $i = 0;
                foreach ($rows as $row){
                    $auto5_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE calls_log.rating1_id = 1 AND calls_log.rating2_id = 1 AND
                calls_log.res = 3 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end ASC
                LIMIT 5)";
                    $i++;
                }
                $auto5_sql = implode(" UNION ", $auto5_sql_arr);
                $result_rows = $dbc->db_free_query($auto5_sql);
                $numRows = $dbc->count;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row){
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 5,
                            "ver_date"=>'NOW()'));

                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                        ));

                        $kol++;
                    }
                    $auto5 = array(0, $kol);
                }
                else{
                    $auto5 = array(0, 0);
                }
            }
            else{
                $auto5 = array(0, 0);
            }
        }

        echo $auto5[0].' / '.$auto5[1].'<br>';

        // Auto 6
        $auto6 = getCountVerDay(6, $u_row['id']);
        if($auto6[1]==0){
            $rows = $dbc->dbselect(array(
                    "table"=>"calls_log",
                    "select"=>"opers.id as oper",
                    "joins"=>"LEFT JOIN users as opers ON calls_log.oper_id = opers.id",
                    "where"=>"calls_log.res = 2 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND 
                opers.office_id = ".$u_row['office_id'],
                    "group"=>"opers.name",
                    "order"=>"opers.name"
                )
            );
            $numRows = $dbc->count;
            if ($numRows > 0) {
                $auto6_sql_arr = array();
                $i = 0;
                foreach ($rows as $row){
                    $auto6_sql_arr[$i] = "(SELECT
                calls_log.*,
                opers.name as oper
                FROM calls_log
                LEFT JOIN users as opers ON calls_log.oper_id = opers.id
                WHERE calls_log.res = 2 AND
                calls_log.rating1_id <> 0 AND calls_log.rating2_id <> 0 AND
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') >= '".date("Ymd", strtotime($minus_days))."' AND 
                DATE_FORMAT(calls_log.date_end,'%Y%m%d') <= '".date("Ymd", strtotime($minus_days))."' AND
                calls_log.oper_id = ".$row['oper']."
                ORDER BY calls_log.date_end ASC
                LIMIT 5)";
                    $i++;
                }
                $auto6_sql = implode(" UNION ", $auto6_sql_arr);
                $result_rows = $dbc->db_free_query($auto6_sql);
                $numRows = $dbc->count;
                if ($numRows > 0) {
                    $kol = 0;
                    foreach ($result_rows as $result_row){
                        $dbc->element_create("ver_log",array(
                            "calls_log_id" => $result_row['id'],
                            "ver_id" => $u_row['id'],
                            "add_field_txt" => $add_field_txt,
                            "auto_type" => 6,
                            "ver_date"=>'NOW()'));

                        $ver_log_id = $dbc->ins_id;
                        $rows2 = $dbc->dbselect(array(
                                "table" => "ver_log",
                                "select" => "ver_log.id as ver,
                                    ver_log.add_field_txt as add_field_txt,
                                    calls_log.oper_id as oper_id,
                                    users.name as oper,
                                    calls_log.date_start as date_start,
                                    calls_log.date_end as date_end,
                                    res_calls.id as res_id,
                                    res_calls.title as res,
                                    ratings1.title as rating1,
                                    ratings2.title as rating2,
                                    oper_calls.link as link,
                                    oper_calls.phone1 as phone,
                                    clients.date_end as td,
                                    clients.id as c_id",
                                "joins" => "LEFT OUTER JOIN calls_log ON ver_log.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN users ON calls_log.oper_id = users.id
                                    LEFT OUTER JOIN res_calls ON calls_log.res = res_calls.id
                                    LEFT OUTER JOIN ratings as ratings1 ON calls_log.rating1_id = ratings1.id
                                    LEFT OUTER JOIN ratings as ratings2 ON calls_log.rating2_id = ratings2.id
                                    LEFT OUTER JOIN oper_calls ON oper_calls.calls_log_id = calls_log.id
                                    LEFT OUTER JOIN clients ON calls_log.c_id = clients.id",
                                "where" => "ver_log.id = ".$ver_log_id,
                                "limit" => 1
                            )
                        );
                        $row2 = $rows2[0];
                        $row3 = $dbc->element_find('clients',$row2['c_id']);
                        $dbc->element_update('ver_log',$ver_log_id,array(
                            "oper_id" => $row2['oper_id'],
                            "oper" => $row2['oper'],
                            "call_date_start" => $row2['date_start'],
                            "call_date_end" => $row2['date_end'],
                            "res_id" => $row2['res_id'],
                            "res" => $row2['res'],
                            "rating1" => $row2['rating1'],
                            "rating2" => $row2['rating2'],
                            "link" => $row2['link'],
                            "phone" => $row2['phone'],
                            "td" => $row2['td'],
                            "c_id" => $row2['c_id'],
                            "c_name" => $row3['name'],
                            "c_iin" => $row3['iin'],
                            "c_email" => $row3['email'],
                            "c_comment" => $row3['comment'],
                            "c_premium" => $row3['premium'],
                            "c_real_premium" => $row3['real_premium'],
                            "c_gn" => $row3['gn'],
                            "c_dop_iin1" => $row3['dop_iin1'],
                            "c_dop_iin2" => $row3['dop_iin2'],
                            "c_dop_iin3" => $row3['dop_iin3'],
                            "c_dop_iin4" => $row3['dop_iin4'],
                            "c_dop_iin5" => $row3['dop_iin5'],
                            "c_dop_gn1" => $row3['dop_gn1'],
                            "c_dop_gn2" => $row3['dop_gn2'],
                            "c_dop_gn3" => $row3['dop_gn3'],
                            "c_is_car" => $row3['is_car'],
                            "c_is_dost" => $row3['is_dost'],
                            "c_is_yur" => $row3['is_yur'],
                            "c_is_ev" => $row3['is_ev'],
                            "c_is_korgau" => $row3['is_korgau'],
                            "c_city_id" => $row3['city'],
                            "c_city" => getItemTitle('city', $row3['city']),
                            "c_strach_company" => getItemTitle('strach_company', $row3['strach_id']),
                        ));
                        
                        $kol++;
                    }
                    $auto6 = array(0, $kol);
                }
                else{
                    $auto6 = array(0, 0);
                }
            }
            else{
                $auto6 = array(0, 0);
            }
        }

        echo $auto6[0].' / '.$auto6[1].'<br>';

    }
}