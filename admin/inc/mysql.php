<?php
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("America/Asuncion");

define("DB_BASE", "gestor_proyectos");
define("DB_USER", "root");
define("DB_PSW", "");

class DataBase extends MySQLi{
    private $link;
    private $result;
    private $sql;
    private $lastError;
    public $resultSize;
    private static $connection;
    private static $sqlQueries;
    private static $totalQueries;
    
    const DB_NAME = DB_BASE;
    const DB_USER = DB_USER;
    const DB_PSW = DB_PSW;
    const DB_HOST = 'localhost';

    public function log_db_errors($error, $query)
    {
        $message = date('Y-m-d H:i:s')."\r\n";
        $message .= 'Query: '. htmlentities($query)."\r\n";
        $message .= 'Error: ' . $error."\r\n";
        $message .= "---------------------------------------------------"."\r\n";
    }

    public static function conectar(){
        if (is_null(self::$connection)) {
            self::$connection = new DataBase();
            self::$connection->set_charset("utf8");
        }
        return self::$connection;
    }

    public function __construct(){
        $this->link = parent::__construct(self::DB_HOST, self::DB_USER, self::DB_PSW, self::DB_NAME);
        if($this->connect_errno == 0){
            self::$totalQueries = 0;
            self::$sqlQueries = array();
        }
        else {
            $this->log_db_errors($this->connect_error, "Error en la conexion");
            echo 'Error en la conexion: '.$this->connect_error;
        }
    }

    // Escape the string get ready to insert or update
    public function clearText($sql) {
        $sql = trim($sql);
        return mysqli::real_escape_string($sql);
    }

    private function execute(){
        if(!($this->result = $this->query($this->sql))){
            $this->lastError = $this->error;
            $this->log_db_errors($this->lastError, $this->sql);
            return false;
        }
        self::$sqlQueries[] = $this->sql;
        self::$totalQueries++;
        $this->resultSize = $this->result->num_rows;
        return true;
    }

    public function alter(){
        if(!($this->result = $this->query($this->sql))){
            $this->lastError = $this->error;
            $this->log_db_errors($this->lastError, $this->sql);
            return false;
        }
        self::$sqlQueries[] = $this->sql;
        return true;
    }

    public function loadObjectList(){
        if (!$this->execute()){
            return null;
        }
        $resultSet = array();
        while ($objectRow = $this->result->fetch_object()){
            $resultSet[] = $objectRow;
        }
      //  $this->result->close();
        return $resultSet;
    }

    public function loadObject(){
        if ($this->execute()){
            if ($object = $this->result->fetch_object()){
              //  $this->result->close();
                return $object;
            }
            else return null;
        }
        else return false;
    }

    public function setQuery($sql){
        if(empty($sql)){
            return false;
        }
        $this->sql = $sql;
        return true;
    }

    public function getTotalQueries(){
        return self::$totalQueries;
    }

    public function getSQLQueries(){
        return self::$sqlQueries;
    }

    public function getError(){
        return $this->lastError;
    }

    public function getLastID(){
        return $this->insert_id;
    }

    function __destruct(){
       $this->close();
    }

    public function insert($table,$data){
        $db = DataBase::conectar();
        if(!empty($data) && is_array($data)){
            $columns = '';
            $values  = '';
            $i = 0;

            if(!array_key_exists('created', $data)){
                $data['created'] = date("Y-m-d H:i:s");
            }

            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $columns .= $pre.$key;
                $values  .= $pre."'".$val."'";
                $i++;
            }
            $query = "INSERT INTO ".$table." (".$columns.") VALUES (".$values.")";
            $insert = $db->query($query);
            // print_r($insert);
            return $insert?$db->insert_id:false;
        }else{
            return false;
        }
    }

    /*
     * Actualizar datos en la base de datos
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        $db = DataBase::conectar();
        if(!empty($data) && is_array($data)){
            $colvalSet = '';
            $whereSql = '';
            $i = 0;

            foreach($data as $key=>$val){
                $pre = ($i > 0)?', ':'';
                $colvalSet .= $pre.$key."='".$val."'";
                $i++;
            }
            if(!empty($conditions)&& is_array($conditions)){
                $whereSql .= ' WHERE ';
                $i = 0;
                foreach($conditions as $key => $value){
                    $pre = ($i > 0)?' AND ':'';
                    $whereSql .= $pre.$key." = '".$value."'";
                    $i++;
                }
            }
            $query = " UPDATE ".$table." SET ".$colvalSet.$whereSql;
            $update = $db->query($query);
            return $update?$this->db->affected_rows:false;
        }else{
            return false;
        }
    }
}
?>
