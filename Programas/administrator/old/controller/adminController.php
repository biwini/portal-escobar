<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class admin extends globalController{
		public $admin = array();
		public $dni,$legajo,$id;
		protected $active = 1;

		public function getUser(){
			$this->query = 'SELECT u.idUsuario,u.cNombre,u.cApellido,u.cSexo,u.cNLegajo,u.nMonotributo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
			INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
			INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
			ORDER BY u.idUsuario ASC';
			$this->data = [];
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->admin[] =array(
					'Id' => $row['idUsuario'],
					'Name' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'Gender' => $row['cSexo'],
					'Legajo' => trim($row['cNLegajo']),
					'Monotributo' => $row['nMonotributo'],
					'Cellphone' => $row['nTelefono'],
					'Email' => $row['cEmail'],
					'IdSecretaria' => $row['idSecretaria'],
					'Secretaria' => $row['cNomSecretaria'],
					'IdDependencia' => $row['idDependencia'],
					'Dependencia' => $row['cNomDependencia'],
					'Suggestion' => $row['cNombre'].' '.$row['cApellido'].' | '.trim($row['cNLegajo'])
				);
			}
			//return $this->admin;
		}
		public function insertUser(){
			$this->legajo = $this->cleanString($_POST['inpLegajo']);

			if(!isset($_POST['inpMonotributo'])){
				if($this->existingUser('LEGAJO', $this->legajo)){
					return array('Status' => 'Existing User Legajo');
				}
			}

			$this->query = 'INSERT INTO Usuario (idDependencia, cNombre, cApellido, cSexo, cNLegajo, nMonotributo, nTelefono, cEmail, nEstado, cContrasenia) VALUES (:Dependencia, UPPER(:Nombre), UPPER(:Apellido), UPPER(:Sexo), UPPER(:Legajo), :Monotributo, :Telefono, :Email, :Estado, :Contrasenia)';
			$this->data = [
				':Dependencia' => $this->cleanString($_POST['selectDependencia']),
				':Nombre' => $this->cleanString($_POST['inpNombre']),
				':Apellido' => $this->cleanString($_POST['inpApellido']),
				':Sexo' => $this->cleanString($_POST['inpSexo']),
				':Legajo' => $this->legajo,
				':Monotributo' => (isset($_POST['inpMonotributo'])) ? $this->cleanString($_POST['inpMonotributo']) : null,
				':Telefono' => $this->cleanString($_POST['inpTelefono']),
				':Email' => trim($_POST['inpEmail']),
				':Estado' => $this->active,
				':Contrasenia' => NULL
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function updateUser(){
			$this->id = $this->cleanString($_POST['id']);
			$this->legajo = $this->cleanString($_POST['modLegajo']);

			if(!$this->existingUser('ID', $this->id)){
				return array('Status' => 'Unknown User');
			}

			// if(!isset($_POST['modMonotributo'])){
			// 	$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Legajo AND idUsuario != :Id';
			// 	$this->data = [':Legajo' => $this->legajo, ':Id' => $this->id];

			// 	if($this->searchRecords() != 0){
			// 		return array('Status' => 'Existing User Legajo');
			// 	}
			// }

			$this->query = 'UPDATE Usuario SET idDependencia = :Dependencia, cNombre = UPPER(:Nombre), cApellido = UPPER(:Apellido), cSexo = :Sexo, cNLegajo = UPPER(:Legajo), nMonotributo = :Monotributo, nTelefono = :Telefono, cEmail = :Email WHERE idUsuario = :Id';
			$this->data = [
				':Dependencia' => $this->cleanString($_POST['modDependencia']),
				':Nombre' => $this->cleanString($_POST['modNombre']),
				':Apellido' => $this->cleanString($_POST['modApellido']),
				':Sexo' => $this->cleanString($_POST['modSexo']),
				':Legajo' => $this->legajo,
				':Monotributo' => (isset($_POST['modMonotributo'])) ? $this->cleanString($_POST['modMonotributo']) : null,
				':Telefono' => $this->cleanString($_POST['modTelefono']),
				':Email' => trim($_POST['modEmail']),
				':Id' => $this->id
			];
				
			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['selectDependencia']) && isset($_POST['inpNombre']) && isset($_POST['inpApellido']) && isset($_POST['inpSexo']) && isset($_POST['inpLegajo']) && isset($_POST['inpTelefono']) && isset($_POST['inpEmail'])){
						if(!$this->validateEmptyPost(array('inpMonotributo'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['modDependencia']) && isset($_POST['modNombre']) && isset($_POST['modApellido']) && isset($_POST['modSexo']) && isset($_POST['modLegajo']) && isset($_POST['modTelefono']) && isset($_POST['modEmail'])){
						if(!$this->validateEmptyPost(array('modContrasenia','modMonotributo')) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
			}
			return $valid;
		}
	}
?>