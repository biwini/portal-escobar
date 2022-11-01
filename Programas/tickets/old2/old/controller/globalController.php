<?php 
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $session = new session();
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

        protected function getUserIdByLegajo($legajo){
            $this->query = 'SELECT idUsuario FROM Usuario WHERE cNLegajo = :Legajo';
            $this->data = [':Legajo' => $legajo];

            return $this->executeQuery()->fetchColumn(0);
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
                case 'ID':$this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE idSubMotivo = :Search'; break;
                case 'NAME':$this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE cSubMotivo = :Search'; break;
                default: $this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE idSubMotivo = :Search'; break;
            }
            $this->data = [':Search' => $search];

            return ($this->searchRecords() != 0) ? true : false;
        }

        protected function existingEquipo($type, $search){
            $type = mb_strtoupper($type, 'UTF-8');
            switch ($type) {
                case 'ID': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search'; break;
                case 'INTERNO': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nInterno = :Search AND (nInterno != 0)'; break;
                case 'PATRIMONIO': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nPatrimonio = :Search AND (nPatrimonio != 0)'; break;
                default: $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search'; break;
            }
            $this->data = [':Search' => $search];

            return ($this->searchRecords() != 0) ? true : false;
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

		public function cleanString($string){
             
            $string = trim($string);
         
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
           	return mb_strtoupper($string, 'utf-8');
        }
	}
?>