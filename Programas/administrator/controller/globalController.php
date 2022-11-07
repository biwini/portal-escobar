<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

	class globalController{
		protected $conn;
        public $WHERE = '';
		public $query,$data,$fecha;

		function __construct(){
            date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
            $serverName = "127.0.0.1"; //serverName\instanceName
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

        protected function existingLocalidad($type, $search){
			switch ($type) {
				case 'ID': $this->query = 'SELECT COUNT(idLocalidad) FROM Localidad WHERE idLocalidad = :Search'; break;
				case 'NAME': $this->query = 'SELECT COUNT(idLocalidad) FROM Localidad WHERE cLocalidad = :Search'; break;
			}

			$this->data = [':Search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

        protected function existSecretary($type, $search){
            $type = mb_strtoupper($type, 'UTF-8');
            switch ($type) {
                case 'NAME': $this->query = 'SELECT COUNT(idSecretaria) FROM Secretaria WHERE UPPER(cNomSecretaria) = UPPER(:Search)'; break;
                case 'ID': $this->query = 'SELECT COUNT(idSecretaria) FROM Secretaria WHERE idSecretaria = :Search'; break;
                default: $this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search'; break;
            }

            $this->data = [':Search' => $search];

            return ($this->searchRecords() == 0) ? false : true;
        }

        protected function existingUser($type, $search){
            $type = mb_strtoupper($type, 'UTF-8');
            switch ($type) {
                case 'DNI': $this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE nDni = :Search'; break;
                case 'LEGAJO': $this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Search'; break;
                case 'ID': $this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search'; break;
                default: $this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search'; break;
            }
            $this->data = [':Search' => $search];

            return ($this->searchRecords() > 0) ? true : false;
        }

        // protected function existingDependence($id){
        //     $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE idDependencia = :Id';
        //     $this->data = [':Id' => intval($id, 10)];

        //     return ($this->searchRecords() == 0) ? false : true;
        // }

        protected function getUserIdByLegajo($legajo){
            $this->query = 'SELECT idUsuario FROM Usuario WHERE cNLegajo = :Legajo';
            $this->data = [':Legajo' => $legajo];

            return $this->executeQuery()->fetchColumn(0);
        }

        protected function existingDependence($type, $search){
            $type = mb_strtoupper($type, 'UTF-8');
            switch ($type) {
                case 'NAME': $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE cNomDependencia = :Search'; break;
                case 'ID': $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE idDependencia = :Search'; break;
                default: $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE idDependencia = :Search'; break;
            }
            $this->data = [':Search' => $search];

            return ($this->searchRecords() == 0) ? false : true;
        }

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