<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class admin extends globalController{
		public $admin = array();
		public $dni,$legajo,$id;

		public function getUser(){
			$this->query = 'SELECT u.idUsuario,u.cNombre,u.cApellido,u.nDni,u.cNLegajo,u.idArea,a.cNomArea FROM Usuario AS u
			INNER JOIN Area AS a ON u.idArea = a.idArea
			ORDER BY u.idUsuario ASC';
			$this->data = [];
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->admin[] =array('Id' => $row['idUsuario'], 'Name' => $row['cNombre'], 'LastName' => $row['cApellido'], 'Dni' => $row['nDni'], 'Legajo' => $row['cNLegajo'], 'IdArea' => $row['idArea'], 'Area' => $row['cNomArea']);
			}
			//return $this->admin;
		}
		public function insertUser(){
			$this->dni = $this->cleanString(trim($_POST['inpDni']));
			$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE nDni = :Dni';
			$this->data = [':Dni' => $this->dni];

			if($this->searchRecords() == 0){
				$this->legajo = $this->cleanString(trim($_POST['inpLegajo']));
				$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Legajo';
				$this->data = [':Legajo' => $this->legajo];
				if($this->searchRecords() == 0){
					$this->query = 'INSERT INTO Usuario (idArea, cNombre, cApellido, nDni, cNLegajo, cContrasenia) VALUES (:Area, UPPER(:Nombre), UPPER(:Apellido), :Dni, :Legajo, :Contrasenia)';
					$this->data = [
						':Area' => $this->cleanString(trim($_POST['selectArea'])),
						':Nombre' => $this->cleanString(trim($_POST['inpNombre'])),
						':Apellido' => $this->cleanString(trim($_POST['inpApellido'])),
						':Dni' => $this->dni,
						':Legajo' => $this->legajo,
						':Contrasenia' => password_hash($_POST['inpContrasenia'], PASSWORD_DEFAULT)
					];

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Existing User Legajo');
				}
			}else{
				return array('Status' => 'Existing User Dni');
			}
		}
		public function updateUser(){
			$this->id = $this->cleanString(trim($_POST['id']));
			$this->dni = $this->cleanString(trim($_POST['modDni']));
			$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE nDni = :Dni AND idUsuario != :Id';
			$this->data = [':Dni' => $this->dni, ':Id' => $this->id];

			if($this->searchRecords() == 0){
				$this->legajo = $this->cleanString(trim($_POST['modLegajo']));
				$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Legajo AND idUsuario != :Id';
				$this->data = [':Legajo' => $this->legajo, ':Id' => $this->id];
				if($this->searchRecords() == 0){
					if(!empty(trim($_POST['modContrasenia']))){
						$this->query = 'UPDATE Usuario SET idArea = :Area, cNombre = :Nombre, cApellido = :Apellido, nDni = :Dni, cNLegajo = :Legajo, cContrasenia = :Contra WHERE idUsuario = :Id';
						$this->data = [
							':Area' => $this->cleanString(trim($_POST['modArea'])),
							':Nombre' => $this->cleanString(trim($_POST['modNombre'])),
							':Apellido' => $this->cleanString(trim($_POST['modApellido'])),
							':Dni' => $this->dni,
							':Legajo' => $this->legajo,
							':Contra' => password_hash($_POST['modContrasenia'], PASSWORD_DEFAULT),
							':Id' => $this->id
						];
					}else{
						$this->query = 'UPDATE Usuario SET idArea = :Area, cNombre = :Nombre, cApellido = :Apellido, nDni = :Dni, cNLegajo = :Legajo WHERE idUsuario = :Id';
						$this->data = [
							':Area' => $this->cleanString(trim($_POST['modArea'])),
							':Nombre' => $this->cleanString(trim($_POST['modNombre'])),
							':Apellido' => $this->cleanString(trim($_POST['modApellido'])),
							':Dni' => $this->dni,
							':Legajo' => $this->legajo,
							':Id' => $this->id
						];
					}

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Existing User Legajo');
				}
			}else{
				return array('Status' => 'Existing User Dni');
			}
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['selectArea']) && isset($_POST['inpNombre']) && isset($_POST['inpApellido']) && isset($_POST['inpDni']) && isset($_POST['inpLegajo']) && isset($_POST['inpContrasenia'])){
						if(!$this->validateEmptyPost(array())){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['modArea']) && isset($_POST['modNombre']) && isset($_POST['modApellido']) && isset($_POST['modDni']) && isset($_POST['modLegajo']) && isset($_POST['modContrasenia'])){
						if(!$this->validateEmptyPost(array('modContrasenia')) && intval(trim($_POST['id'])) != 0){
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