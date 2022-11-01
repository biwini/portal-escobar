<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }

	class globalController{
		protected $conn;
        public $listSecretary = array();
        public $WHERE = '';
        public $user = array();
        public $tecnico = array();
		protected $query,$data;
        public $fecha;
        public $isTecnicUser;

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
            $this->fecha = date('Y-m-d H:i:s');
            $this->isTecnicUser = ($_SESSION['DEPENDENCIA'] == 2 && $_SESSION['TICKETS'] == 1) ? true : false;
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

        protected function getLastInsertedId(){
            return $this->conn->lastInsertId();
        }

        public function getTecnico(){
            $this->query = 'SELECT u.idUsuario,u.cNombre,u.cApellido FROM Usuario AS u 
                WHERE u.idDependencia = 2 ORDER BY u.idUsuario ASC';
            $this->data = [];
            $result = $this->executeQuery();

            while($row = $result->fetch()){
                $this->tecnico[] =array(
                    'Id' => $row['idUsuario'],
                    'Name' => $row['cNombre'],
                    'LastName' => $row['cApellido'],
                );
            }
            return $this->tecnico;
        }
        public function getUsers(){
            $this->query = 'SELECT u.idUsuario,u.cNombre,u.cApellido,u.nDni,u.cSexo,u.cNLegajo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
                INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
                INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
                ORDER BY u.idUsuario ASC';
            $this->data = [];
            
            $result = $this->executeQuery();

            while($row = $result->fetch()){
                $this->user[] =array(
                    'Id' => $row['idUsuario'],
                    'Name' => $row['cNombre'],
                    'LastName' => $row['cApellido'],
                    'Dni' => $row['nDni'],
                    'Gender' => $row['cSexo'],
                    'Legajo' => trim($row['cNLegajo']),
                    'Cellphone' => $row['nTelefono'],
                    'Email' => $row['cEmail'],
                    'IdSecretaria' => $row['idSecretaria'],
                    'Secretaria' => $row['cNomSecretaria'],
                    'IdDependencia' => $row['idDependencia'],
                    'Dependencia' => $row['cNomDependencia'],
                    'Suggestion' => trim($row['cNLegajo'].' | '.$row['cNombre'].' '.$row['cApellido'])
                );
            }
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
                }else if(!is_array($_POST[$key])) {
                    $_POST[$key] = $value;
                }
            }
            return $incomplete;
        }
        protected function formatPost(){
            if(!isset($_POST)){
                return false;
            }
            foreach ($_POST as $key => $value) {
                $UpperKey = mb_strtoupper($key, 'UTF-8');
                if(strpos($UpperKey, 'EMAIL') !== false || strpos($UpperKey, 'MAIL') !== false){
                    if(!$this->is_valid_email($value)){ return false; }
                }else{
                    $_POST[$key] = $this->cleanString($value);
                }
            }
            return true;
        }
        protected function is_valid_email($email){
            $matches = null;
            return (1 === preg_match('/[^@\s]+@[^@\s]+\.[^@\s]+/', $email, $matches));
        }

        public function getSecretary(){
            $where = ($_SESSION['TICKETS'] != 1) ? 'WHERE s.idSecretaria = '.$this->getUserSecretary($_SESSION['DEPENDENCIA']) : '';

            $this->query = 'SELECT s.idSecretaria,s.cNomSecretaria,s.nEstado,s.dFechaAlta FROM Secretaria AS s '.$where.' ORDER BY s.idSecretaria ASC';
            $this->data = [];

            $result = $this->executeQuery();

            while ($row = $result->fetch()){
                // $fecha = new DateTime($row['dFechaAlta']);
                $this->listSecretary[] = array(
                    'IdSecretaria' => $row['idSecretaria'],
                    'Name' => $row['cNomSecretaria'], 
                    'State' => $row['nEstado'],
                    'Dependences' => $this->getSecretaryDependence($row['idSecretaria']),
                    // 'Date' => $fecha->format('d-m-Y H:i:s')
                );
            }
        }
        private function getSecretaryDependence($secretary){
            $where = ($_SESSION['TICKETS'] != 1) ? 'AND d.idDependencia = '.$_SESSION['DEPENDENCIA'] : '';

            $this->query = 'SELECT d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
                INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
                WHERE d.idSecretaria = :Secretary '.$where.' ORDER BY d.idDependencia ASC';
            $this->data = [':Secretary' => $secretary];

            $result = $this->executeQuery();
            $listDependence = array();
            while ($row = $result->fetch()){
                $listDependence[] = array(
                    'IdSecretary' => $secretary,
                    'IdDependence' => $row['idDependencia'],
                    'Name' => $row['cNomDependencia'], 
                    'IdLocation' => $row['idLocalidad'],
                    'Location' => $row['cLocalidad'],
                    'Address' => $row['cDireccion'],
                    'State' => $row['nEstado']
                );
            }
            return $listDependence;
        }
        protected function getUserSecretary($userDependence){
            $this->query = 'SELECT idSecretaria FROM Dependencia WHERE idDependencia = :Dependence';
            $this->data = [':Dependence' => $userDependence];

            return $this->executeQuery()->fetchColumn(0);
        }
        protected function getUserDependence($user){
            $this->query = 'SELECT idDependencia FROM Usuario WHERE idUsuario = :User';
            $this->data = [':User' => $user];

            return $this->executeQuery()->fetchColumn(0);
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
        protected function existingDependence($id){
            $this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE idDependencia = :Id';
            $this->data = [':Id' => $id];

            return ($this->searchRecords() > 0) ? true : false;
        }
        protected function existingMotivo($id){
            $this->query = 'SELECT COUNT(idMotivo) FROM Motivo WHERE idMotivo = :Motivo';
            $this->data = [':Motivo' => $id];

            return ($this->searchRecords() != 0) ? true : false;
        }
        protected function existingSubMotivo($type, $search){
            $type = mb_strtoupper($type, 'UTF-8');
            switch ($type) {
                case 'ID':
                    $this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE idSubMotivo = :Search';
                    break;
                case 'NAME':
                    $this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE cSubMotivo = :Search';
                    break;
                default:
                    $this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE idSubMotivo = :Search';
                break;
            }
            $this->data = [':Search' => $search];

            return ($this->searchRecords() != 0) ? true : false;
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