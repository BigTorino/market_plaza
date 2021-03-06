<?php

    //require_once '../response.class.php';

    class Connection {

        private $server;
        private $user;
        private $password;
        private $database;
        private $port;

        private $conecction;
        //private $_response;

        function __construct() {
            //$this->_response = new response;
            $dir = dirname(__FILE__);
            $jsonData = file_get_contents($dir . "/" . "config.json");
            $data = json_decode($jsonData, true);
            foreach($data as $key){
                $this->server = $key['server'];
                $this->user = $key['user'];
                $this->password = $key['password'];
                $this->database = $key['database'];
                $this->port = $key['port'];
            }
            try{
                $this->conecction = new PDO("mysql:host=$this->server;dbname=$this->database",$this->user,$this->password);
            } catch(PDOException $e){
                echo "Error: " . $e->getMessage();
                die();
            }
        }

        private function UTF8($array){
            array_walk_recursive($array,function(&$item,$key){
                if(!mb_detect_encoding($item, 'utf-8', true)){
                    $item = utf8_encode($item);
                }
            });
            return $array;
        }

        // retorna la data y las filas afectadas
        public function getData($query){
            $statement = $this->conecction->prepare($query);
            $statement->execute();
            return ["data" => $this->UTF8($statement->fetchAll()), "rows" => $statement->rowCount()];
        }

        // hace consultas
        public function queryData($sql){
            $statement = $this->conecction->prepare($sql);
            $statement->execute();
            return $statement->rowCount();
        }

        public function getQueryId($sql){
            $statement = $this->conecction->prepare($sql);
            $statement->execute();
            $rows = $statement->rowCount();
            if($rows > 1){
                return $this->conecction->lastInsertId();// posible error
            } else {
                return 0;
            }
        }

        public function encrypt($string){
            return md5($string);
        }

        // $get: son las columnas que quiero recibir ej: id,nombe,direccion
        // $tabla: la tabla que quiero ver
        // $con: son las condiciones (si $where es true) y el formato es: array["condicion" => "valor"]
        // $conOp: es el operador por cada condicion (si $where es true) y el 
        //         formato es: array["condicion" => "operador(=,<=,!=)"]
        // $where: es true o false depende de si queremos condicionar la busqeda
        public function selectFrom($get, $table, $where, $con = array(), $conOp = array()){
            $query = "SELECT " . $get . " FROM " . $table . " ";
            if($where){
                $query = $query . "WHERE";
                $keys = array_keys($con);
                foreach($con as $key => $value) { 
                    if ($key == end($keys)) {
                        $query = $query . " " . $key . " ". $conOp[$key] . " :" . $key;
                        $key = ":" . $key;
                    } else {
                        $query = $query . " " . $key . " ". $conOp[$key] . " :" . $key . " AND";
                        $key = ":" . $key;
                    }   
                }
            }
            $statement = $this->conecction->prepare($query);
            if($where){
                $statement->execute($con);
            }else{
                $statement->execute();
            }
            return ["data" => $this->UTF8($statement->fetchAll()), "rows" => $statement->rowCount()];
        }

        // $table = Nombre de la tabla a afectar
        // $cols = columnas espesificas a afectar
        // $values = valores a agregar en formato: ["userId" => "1"]
        public function insertInto($table, $cols = array(), $values = array()){
            $query = "INSERT INTO " . $table . " (";
            foreach ($cols as $value) {
                if($value == end($cols)){
                    $query = $query . $value . ") VALUES(";
                }else{
                    $query = $query . $value . ",";
                }
            }
            foreach ($values as $key => $value) {
                if($value == end($values)){
                    $query = $query . ":". $key . ")";
                }else{
                    $query = $query .":". $key . ",";
                }
            }
            $statement = $this->conecction->prepare($query);
            $statement->execute($values);
            return $statement->rowCount();
        }

    }

?>