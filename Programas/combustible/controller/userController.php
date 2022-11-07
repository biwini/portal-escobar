<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }
	class usuario{
		private $conn;
		private $data;

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
		}

		private function searchRecords(){
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records->fetchColumn();
		}
		private function executeQuery(){
			$records = $this->conn->prepare($this->query);
			$records->execute($this->data);

			return $records;
		}

		public function getUserInfo($userId){
			$userId = intval($userId, 10);

			if(!$_SESSION['LOGUEADO']){
				return false;
			}

			$this->query = 'SELECT cNombre, cApellido FROM usuario WHERE idUsuario = :Id';
			$this->data = [':Id' => $userId];

			$result = $this->executeQuery();
			$User = '';
			while ($row = $result->fetch()){
	            $User = $row['cNombre'].' '.$row['cApellido'];
	        }

	        return $User;
		}
	}

?>