<?php
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

    class Main{
        protected $conn;
        protected $sql, $data;
        public $fecha;

        function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.

            $this->fecha = date('Y-m-d H:i:s');

            $this->sql = '';
            $this->data = array();
        }

        protected function conectar(){
            //Agregar los parametros
            // $this->con=new mysqli('localhost','root','usbw','efectores_ambulatorio');
            $serverName = ".\SQLEXPRESS"; # "192.168.122.17"; //serverName\instanceName
            $database = "Fichadas";
            $UID = 'SA';
            $PWD = '123'; # 'Fu11@c3$*9739';
                    $this->con = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
                PDO::ATTR_EMULATE_PREPARES => false,
                // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            // $this->con=new mysqli("","","","efectores_ambulatorio");
            // $this->con->set_charset("utf8");
        }

        protected function query(){
            $reg = $this->conn->prepare($this->sql);
            $reg->execute($this->data);

            return $reg;
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

        public function format_string($string){
            // $result=$this->con->real_escape_string($string); //mysql escape string no funciona en sql server
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

        protected function last_id(){
            // return $this->con->insert_id;
            return $this->conn->lastInsertId();
        }

        public function error_struct($error){
            header("HTTP/1.0 404 Not Found");
            header("Status: 404 Not Found");
            $this->con->query("ROLLBACK;");
            $this->con->close();
            exit($error);
        }

        public function close(){
            $this->con->query("COMMIT;");
            $this->con->close();
        }
    }
?>
