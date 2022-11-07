<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

	class globalController{
        //Variables de la clase
		protected $conn;
		protected $query,$data;
        public $Admin;
        public $fecha;

		function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.

            $serverName = "127.0.0.1"; //serverName\instanceName
            $database = "Ticket_Combustible"; //nombre de la bbase de datos
            $UID = 'SA';    
            $PWD = 'Fu11@c3$*9739'; //Contraseña
			$this->conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,[
               PDO::ATTR_EMULATE_PREPARES => false,
            //    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]); //Establesco la conexion a la base de datos. Esto se podria mejorar...
            $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
			$this->data = array();
            $this->fecha = date('Y-m-d H:i:s');

            //Defino el permiso administrador segun su permiso en el sistema y segun su dependencia particular
            $this->Admin = ($_SESSION['TICKETS_DE_COMBUSTIBLE'] == 1 && $_SESSION['DEPENDENCIA'] == 265 || $_SESSION['ID_USER'] == 1) ? true : false; // Id de la dependencia de hacienda '265'
		}
        // //Todos los array que pasen por esta funcion deben de tener obligatoriamente los valores 'Id' y 'Name'
        // public function createOption($array,·){
        //     $result = '';
        //     foreach ($array as $key => $value) {
        //         $result .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
        //     }
        //     return $result;
        // }
		protected function searchRecords(){ //Funcion parra ejecutar la query SQL. devuelve el numero de filas afectadas por la consulta
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records->fetchColumn();
        }
        
		protected function executeQuery(){  //Funcion parra ejecutar la query. devuelve la respuesta del sql
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records;
		}

        protected function getLastInsertedId(){ //Funcion para obtener el ultimo id insertado
            return $this->conn->lastInsertId();
        }

    

        public function validateEmptyPost($exeption){   //Funcion para validar que los campos enviados por post no esten vacios. acepta exepciones en forma de array, Ej: array('telefono','observaciones')
            $incomplete = false;

            if(!isset($_POST)){
                return false;
            }
            
            foreach ($_POST as $key => $value) {
                if(!in_array($key, $exeption, true)){
                    if(empty(trim($value))){
                        $incomplete = true;
                        break; //sale del bucle foreach
                    }
                }
            }
            return $incomplete;
        }

        protected function is_valid_email($email){  //Funcion para validar el email
            $matches = null;
            return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
        }

        // protected function getUserDependence($user){ //Funcion obsoleta para obtener la dependencia del usuario
        //     $this->query = 'SELECT idDependencia FROM Usuario WHERE idUsuario = :User';
        //     $this->data = [':User' => $user];

        //     return $this->executeQuery()->fetchColumn(0);
        // }

        protected function validDate($date){ //Funcion para validar que la fecha este en un formato valido 'dia/mes/año' 
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
           	return mb_strtoupper($string, 'UTF-8'); //Devuelvo el string limpio y en mayusculas
        }
	}
?>