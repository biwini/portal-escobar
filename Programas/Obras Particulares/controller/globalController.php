<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

	class globalController{
		protected $conn;
		protected $query,$data;
        protected $Admin;
        public $fecha;

		function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
            $serverName = "127.0.0.1"; //serverName\instanceName
            $database = "ObraParticular";
            $UID = 'SA';
            $PWD = 'Fu11@c3$*9739';
			$this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
               PDO::ATTR_EMULATE_PREPARES => false,
               PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            
			$this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
			$this->data = array();
            $this->fecha = date('Y-m-d H:i:s');

            // $this->Admin = ($_SESSION['TICKETS_DE_COMBUSTIBLE'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false; // Id de la dependencia de hacienda '265'
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

        protected function getLastInsertedId(){
            return $this->conn->lastInsertId();
        }

        public function array_zip_combine(array $keys, ...$arrs) {
			return array_map(function (...$values) use ($keys) {
			  return array_combine($keys, $values);
			}, ...$arrs);
		}

        public function validateEmptyPost($exeption){
            $incomplete = false;

            if(!isset($_POST)){
                return false;
            }
            
            foreach ($_POST as $key => $value) {
                if(!is_array($_POST[$key])){
                    if(!in_array($key, $exeption, true)){
                        if(empty(trim($value))){
                            $incomplete = true;
                            break; //sale del bucle foreach
                        }
                    }
                }
            }
            return $incomplete;
        }

        protected function is_valid_email($email){
            $matches = null;
            return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
        }

        protected function validDate($date){
            preg_match('/(\d{4})+(-)+(\d{2})+(-)+(\d{1,2})/', $date, $salida);
            if(count($salida)>=1){
                $salida = array_values(array_diff($salida,['-']));
                if(!in_array($salida[1],range(1900,2500))){ return false; }
                if(!in_array($salida[2],range(1,12))){ return false; }
                if(!in_array($salida[3],range(1,cal_days_in_month(CAL_GREGORIAN, $salida[2], $salida[3])))){ return false; }
                    
                return true;
            }else{
                return false;
            }
        }

        protected function searchSecretary($secretary){
            $s = '';

            foreach ($_SESSION['SECRETARIAS'] as $key => $value) {
                if($value['Id'] == $secretary){
                    $s = $value['Secretary'];
                    break;
                }
            }

            return $s;
        }

        public function searchDependence($secretary, $dependence){
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

            return$d;
        }

		public function cleanString($string){
             
            $string = trim($string);
         
            // $string = str_replace(
            //     array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            //     array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            //     $string
            // );
         
            // $string = str_replace(
            //     array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            //     array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            //     $string
            // );
         
            // $string = str_replace(
            //     array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            //     array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            //     $string
            // );
         
            // $string = str_replace(
            //     array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô','õ'),
            //     array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O','o'),
            //     $string
            // );
         
            // $string = str_replace(
            //     array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            //     array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            //     $string
            // );
         
            // $string = str_replace(
            //     array('ñ', 'Ñ', 'ç', 'Ç'),
            //     array('n', 'N', 'c', 'C',),
            //     $string
            // );
         
            $string = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                     "#", "@", "|", "!", "\"","`",
                     "·", "$", "%",
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