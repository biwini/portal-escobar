<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $session = new session();
    }

	class globalController{
		protected $conn;
		public $query,$data,$fecha;

		function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
            $serverName = "192.168.122.17"; //serverName\instanceName
            $database = "ObraPublica";
            $UID = 'SA';
            $PWD = 'Fu11@c3$*9739';
			$this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
		       PDO::ATTR_EMULATE_PREPARES => false,
		       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		    ]);
			$this->data = array();
            $this->fecha = date('Y-m-d H:i:s', time());
		}

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
        
        protected function getLastInsertedId(){ //Funcion para obtener el ultimo id insertado
            return $this->conn->lastInsertId();
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
            
            }
            return $incomplete;
        }

        protected function is_valid_email($email){
            $matches = null;
            return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
        }

        // Las secretarias y dependencias se guardan en una variable de session
        protected function searchSecretary($secretary){ //Funcion que busca por el id de la secretaria y el devuelva el nombre de la secretaria
            $s = '';

            foreach ($_SESSION['SECRETARIAS'] as $key => $value) {
                if($value['Id'] == $secretary){
                    $s = $value['Secretary'];
                    break;
                }
            }

            return $s;
        }
        // Las secretarias y dependencias se guardan en una variable de session
        public function searchDependence($secretary, $dependence){ //Funcion que busca por el id de la secretaria y dependencia que devuelva el nombre de la dependencia
           $d = '';

            foreach ($_SESSION['SECRETARIAS'] as $key => $value) {
                if($value['Id'] == $secretary){
                    foreach ($value['Dependences'] as $k => $v) {
                        if($v['Id'] == $dependence){
                            $d = $v['Dependence'];

                            break;
                        }
                    }
                    break;
                }
            }

            return $d;
        }

        public function in_array_r($needle, $haystack, $strict = false) { //Funcion revursiva para buscar items dentro de un array asociativo. (Variable a buscar, Array)
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }
        
            return false;
        }

		public function cleanString($string){
            $string = trim($string);
         
            $string = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                     "#", "@", "|", "!", "\"","`",
                     "·", "$", "%", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "<code>", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".","="),
                '',
                $string
            );
           	return mb_strtoupper($string, 'utf-8');
        }
	}
?>