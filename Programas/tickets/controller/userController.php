<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class usuario extends globalController{
		public $Name;
		public $Surname;
		public $Legajo;
		public $Email;

		private $Id;
		private $Dni;
		private $BirthDate;
		private $Secretary;
		private $Dependence;
		private $CellPhone;

		public $ListUsers;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->Surname = '';
			$this->Legajo = '';
			$this->Dni = 0;
			$this->BirthDate = '';
			$this->Secretary = 0;
			$this->Dependence = 0;
			$this->CellPhone = 0;
			$this->Email = '';
			$this->ListUsers = array();
			
			parent::__construct();
		}

		public function InsertInactiveUser(){
			if(!$this->setUserInfo() || !$this->existingDependence($this->Dependence)){
				return array('Status' => 'Invalid User Form');
			}
			if($this->existingUser('DNI', $this->Dni) || $this->existingUser('LEGAJO', $this->Legajo)){
				return array('Status' => 'Existing User');
			}

			$this->query = 'INSERT INTO Usuario (idDependencia, cNombre, cApellido, nDni, cSexo, cNLegajo, nTelefono, cEmail, nEstado, cContrasenia) VALUES (:Dependencia, :Nombre, :Apellido, :Dni, :Sexo, :Legajo, :Telefono, :Email, :Estado, :Contrasenia)';
			$this->data = [
				':Dependencia' => $this->Dependence,
				':Nombre' => $this->Name,
				':Apellido' => $this->Surname,
				':Dni' => $this->Dni,
				':Sexo' => NULL,
				':Legajo' => $this->Legajo,
				':Telefono' => $this->CellPhone,
				':Email' => $this->Email,
				':Estado' => 0,
				':Contrasenia' => NULL
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		protected function setUserInfo(){
			if(!$this->formatPost()){
				return false;
			}

			$this->Name = $_POST['alta_nombre'];
			$this->Surname = $_POST['alta_apellido'];
			$this->Legajo = $_POST['alta_legajo'];
			$this->Dni = intval($_POST['alta_dni'], 10);
			$this->BirthDate = $_POST['alta_nacimiento'];
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
				case 'ID':
					$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search';
					break;
				default:
					$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE idUsuario = :Search';
					$search = intval($search, 10);
					break;
			}

			return ($this->searchRecords() > 0) ? true : false;
		}
		public function setUserById($id){
			if(!$this->existingUser('ID', $id)){
				return false;
			}

			$this->query = 'SELECT cNombre, cApellido, cNLegajo, cEmail FROM Usuario WHERE idUsuario = :Id ORDER BY idUsuario DESC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();

			while ($row = $result->fetch()) {
				$this->Name = $row['cNombre'];
				$this->Surname = $row['cApellido'];
				$this->Email = $row['cEmail'];
			}

			return ($this->Email != '') ? true : false;
		}
		public function getUserId($Legajo){
			if(!$this->existingUser('LEGAJO', $Legajo)){
				return 0;
			}
			$this->query = 'SELECT idUsuario FROM Usuario WHERE cNLegajo = :Legajo';
			$this->data = [':Legajo' => $Legajo];

			return $this->executeQuery()->fetchColumn(0);
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['alta_nombre']) && isset($_POST['alta_apellido']) && isset($_POST['alta_legajo']) && isset($_POST['alta_dni']) && isset($_POST['alta_nacimiento']) && isset($_POST['alta_secretaria']) && isset($_POST['alta_dependencia']) && isset($_POST['alta_telefono']) && isset($_POST['alta_email'])){
						if(!$this->validateEmptyPost(array('obs','alta_compartida','alta_telefono'))){
							$valid = true;
						}
					}
					break;
				
				default:
					# code...
					break;
			}
			return $valid;
		}
	}
?>