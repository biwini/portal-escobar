<?php
    // if(!isset($_SESSION)){
    //     require_once ('sessionController.php');
    //     $Session = new session();
    // }

    class Main{
        protected $conn;
        protected $sql, $data;
        public $fecha;

        function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires');

            $serverName = ".\SQLEXPRESS"; # "192.168.122.17"; .\SQLEXPRESS
            $database = "Fichadas";
            $uid = "sa";
            $pwd = '123'; # 'Fu11@c3$*9739';

            $this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$uid,$pwd);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
            $this->data = array();
        }
        protected function query(){
            $records = $this->conn->prepare($this->sql);
            $records->execute($this->data);
            return $records;
        }

        public function array_result($result){
            $datos = array();
            $cont = 0;
            $info_campo = $result->fetch_fields();

            while($reg = $result->fetch_array()){
                foreach ($info_campo as $valor) {
                    $datos[$cont][$valor->name] = $reg[$valor->name];
                }
                
                $cont++;
            }

            return $datos;
        }

        protected function last_id(){
            return $this->conn->lastInsertId();
        }

        public function format_string($string){
            $result = trim($string);
                
            $result = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                        "#", "@", "|", "!", "\"","`",
                        "·", "$", "%",
                        "(", ")", "?", "'", "¡",
                        "¿", "[", "^", "<code>", "]",
                        "+", "}", "{", "¨", "´",
                        ">", "< ", ";", ",", ":",
                        ".","="),
                '',
                $result
            );

            return $result;
        }
    }
?>
