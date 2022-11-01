<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

    class globalController{
        protected $conn;
        public $WHERE = '';
        protected $query,$data;
        public $fecha;

        function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
            $serverName = "192.168.122.17"; //serverName\instanceName
            $database = "PortalEscobar";
            $UID = 'SA';
            $PWD = 'Fu11@c3$*9739';
            $this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
               PDO::ATTR_EMULATE_PREPARES => false,
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            $this->data = array();
            $this->fecha = date('Y-m-d H:i:s', time());
        }
        // OBSOLETO
        // //Todos los array que pasen por esta funcion deben de tener obligatoriamente los valores 'Id' y 'Name'
        // public function createOption($array,·){
        //     $result = '';
        //     foreach ($array as $key => $value) {
        //         $result .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
        //     }
        //     return $result;
        // }
        protected function searchRecords(){
            $records = $this->conn->prepare($this->query);
            $records->execute($this->data);

            return $records->fetchColumn();
        }
        protected function executeQuery(){
            $records = $this->conn->prepare($this->query);
            $records->execute($this->data);

            return $records;
        }
        public function validateEmptyPost($exeption){
            $incomplete = false;
            if(!isset($_POST)){
                return false;
            }
            foreach ($_POST as $key => $value) {
                $UpperKey = mb_strtoupper($key, 'UTF-8');
                if(!in_array($key, $exeption, true)){
                    if(empty(trim($value))){
                        $incomplete = true;
                        break; //sale del bucle foreach
                    }
                }
                
                if(strpos($UpperKey, 'EMAIL') !== false || strpos($UpperKey, 'MAIL') !== false){
                    if(!$this->is_valid_email($value)){ $incomplete = true; break; }
                }else if(strpos($UpperKey, 'CONTRASENIA') === false && strpos($UpperKey, 'PASS') === false && strpos($UpperKey, 'PASSWORD') === false){
                    $_POST[$key] = $this->cleanString($value);
                }
            }
            return $incomplete;
        }
        public function getUserIpAddress(){
            foreach ( [ 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR' ] as $key ) {
                // Comprobamos si existe la clave solicitada en el array de la variable $_SERVER 
                if ( array_key_exists( $key, $_SERVER ) ) {
                    // Eliminamos los espacios blancos del inicio y final para cada clave que existe en la variable $_SERVER 
                    foreach ( array_map( 'trim', explode( ',', $_SERVER[ $key ] ) ) as $ip ) {
                        // Filtramos* la variable y retorna el primero que pase el filtro
                        if ( filter_var( $ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE ) !== false ) {
                            return $ip;
                        }
                    }
                }
            }
            return '?'; // Retornamos '?' si no hay ninguna IP o no pase el filtro
        }
        public function getLocalIp(){ 
            $exec = exec("hostname"); //El "hostname" es un comando valido para windows como para linux.
            $hostname = trim($exec); //Remueve los espacios de antes y despues.
            $ip = gethostbyname($hostname);

            return $ip;
        }

        public function getSecretaries(){
            $this->query = 'SELECT idSecretaria, cNomSecretaria FROM Secretaria ORDER BY idSecretaria ASC';
            $this->data = [];

            $result = $this->executeQuery();
            $secretaryList = array();

            while ($row = $result->fetch()){
                $secretaryList[] = array(
                    'Id' => $row['idSecretaria'],
                    'Secretary' => $row['cNomSecretaria'],
                    'Dependences' => $this->getSecretaryependences($row['idSecretaria'])
                );
            }

            return $secretaryList;
        }

        private function getSecretaryependences($secretary){
            $this->query = 'SELECT idDependencia, cNomDependencia FROM Dependencia WHERE idSecretaria = :Id AND cNomDependencia != \'OTRO\' ORDER BY idDependencia ASC';
            $this->data = [':Id' => $secretary];

            $result = $this->executeQuery();
            $dependenceList = array();
            while ($row = $result->fetch()){
                $dependenceList[] = array(
                    'Id' => $row['idDependencia'],
                    'Dependence' => $row['cNomDependencia']
                );
            }

            return $dependenceList;
        }

        protected function existingDependence($id){
            $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE idDependencia = :Id';
            $this->data = [':Id' => $id];

            return ($this->searchRecords() > 0) ? true : false;
        }

        public function is_valid_email($email){
            $matches = null;
            return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
        }
        
        public function cleanString($string){
             
            $string = trim($string);
         
            $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
                array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
                $string
            );
         
            $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
                array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
                $string
            );
         
            $string = str_replace(
                array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
                array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
                $string
            );
         
            $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','õ'),
                array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','o'),
                $string
            );
         
            $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
                array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
                $string
            );
         
            $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'),
                array('n', 'N', 'c', 'C',),
                $string
            );
         
            $string = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                     "#", "@", "|", "!", "\"","`",
                     "·", "$", "%", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "<code>", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".","="),
                ' ',
                $string
            );
            return mb_strtoupper($string, 'UTF-8');
        }
    }
?>