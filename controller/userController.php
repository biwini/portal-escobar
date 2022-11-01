<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }
    include_once  realpath(__DIR__ ).'/globalController.php';
    include 'loginController.php';

	class usuario extends globalController{
		private $Id;
		private $Name;
		private $LastName;
		private $Legajo;
		private $Dni;
		private $BirthDate;
		private $Secretary;
		private $Dependence;
		private $CellPhone;
		private $Email;
		public $ListUsers;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->LastName = '';
			$this->Legajo = $_SESSION['LEGAJO'];
			$this->Dni = 0;
			$this->BirthDate = NULL;
			$this->Secretary = 0;
			$this->Dependence = 0;
			$this->CellPhone = 0;
			$this->Email = '';
			$this->ListUsers = array();
			
			parent::__construct();
		}

		public function RegisterUser(){
			$Ldap = new ldap();

			if($this->Legajo == NULL || !$_SESSION['UNREGISTRED'] || $_SESSION['TEMPORAL_PASSWORD'] == NULL || !$Ldap->validateUser($this->Legajo,$_SESSION['TEMPORAL_PASSWORD'])){
				$_SESSION['LOGUEADO'] = FALSE;
				$_SESSION['UNREGISTRED'] = FALSE;
				$_SESSION['TEMPORAL_PASSWORD'] = NULL;
				$_SESSION['LEGAJO'] = NULL;
				return array('Status' => 'Success'); // DEVUELVE SUCCESS PARA QUE ASI SE REFRESQUE LA PAGINA Y LO ENVIE AL LOGIN AUTOMATICAMENTE
			}
			if(!$this->setUserInfo() || !$this->existingDependence($this->Dependence)){
				return array('Status' => 'Invalid Register Form');
			}
			if($this->existingUser('LEGAJO', $this->Legajo)){
				return array('Status' => 'Existing User');
			}

			$this->query = 'INSERT INTO Usuario (idDependencia, cNombre, cApellido, cSexo, cNLegajo, nTelefono, cEmail, nEstado) VALUES (:Dependencia, :Nombre, :Apellido, :Sexo, :Legajo, :Telefono, :Email, :Estado)';
			$this->data = [
				':Dependencia' => intval($this->Dependence),
				':Nombre' => $this->Name,
				':Apellido' => $this->LastName,
				':Sexo' => NULL,
				':Legajo' => $this->Legajo,
				':Telefono' => $this->CellPhone,
				':Email' => $this->Email,
				':Estado' => 1,
			];
			// var_dump($this->data);
			if($this->executeQuery()){
				$_SESSION['AUTO_LOGIN'] = true;
				$Access = $this->setTicketAccess();
				$Login = new login();
				$Response = $Login->loginUser();
				return ($Response['Status'] == 'Success') ? array('Status' => 'Success','Access' => $Access,'ASD' => $Response) : array('Status' => 'Error','ASD' => $Response);
			}
			return array('Status' => 'Error');
		}
		protected function setUserInfo(){

			$this->Name = $_POST['alta_nombre'];
			$this->LastName = $_POST['alta_apellido'];
			$this->Secretary = $_POST['alta_secretaria'];
			$this->Dependence = $_POST['alta_dependencia'];
			$this->CellPhone = intval($_POST['alta_telefono'], 10);
			$this->Email = trim($_POST['alta_email']);

			return true;
		}
		protected function existingUser($type, $search){
			$type = mb_strtoupper($type, 'UTF-8');
			$this->data = [':Search' => $search];
			switch ($type) {
				case 'DNI':
					$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE nDni = :Search';
					break;
				case 'LEGAJO':
					$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Search';
					break;
				default:
					$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search';
					$search = intval($search, 10);
					break;
			}

			return ($this->searchRecords() > 0) ? $this->autoLogin() : false;
		}
		private function GetId($legajo){
			$this->query = 'SELECT idUsuario FROM Usuario WHERE cNLegajo = :Legajo';
			$this->data = [':Legajo' => $legajo];

			return $this->executeQuery()->fetchColumn(0);
		}
		private function setTicketAccess(){
			$this->Id = $this->GetId($this->Legajo);
			$this->query = 'INSERT INTO acceso (idUsuario,idPrograma,nPermiso,idAlta,dFechaAlta) VALUES (:IdUser, :IdProgram, :Permiso, :IdAlta, :Fecha)';
			$this->data = [
				':IdUser' => $this->Id,
				':IdProgram' => 18,
				':Permiso' => 2,
				':IdAlta' => NULL,
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? true : false;
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['alta_nombre']) && isset($_POST['alta_apellido']) && isset($_POST['alta_secretaria']) && isset($_POST['alta_dependencia']) && isset($_POST['alta_telefono']) && isset($_POST['alta_email'])){
						if(!$this->validateEmptyPost(array())){
							$valid = true;
						}
					}
					break;
			}
			return $valid;
		}
	}
?>