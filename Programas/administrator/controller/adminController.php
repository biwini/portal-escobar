<?php 
    include_once  realpath(__DIR__ ).'/globalController.php';

	class admin extends globalController{
		public $admin = array();
		public $email,$legajo,$id;

		public function getUsers($search = null){
			if($search == null){
				$this->query = 'SELECT u.idUsuario, nActiveDirectory,u.cNombre,u.cApellido,u.cSexo,u.cNLegajo,u.nMonotributo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
					INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
					INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
					ORDER BY u.idUsuario ASC';
				$this->data = [];
			}else{
				$search = $this->cleanString($search);
				$this->query = 'SELECT LIMIT 10 u.idUsuario, nActiveDirectory,u.cNombre,u.cApellido,u.cSexo,u.cNLegajo,u.nMonotributo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
					INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
					INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
					WHERE CONCAT(u.cNombre, \' \', u.Apellido) LIKE :User OR u.cNLegajo LIKE :Legajo ORDER BY u.idUsuario ASC';
				$this->data = [':User' => '%'.$search.'%', ':Legajo' => '%'.$search.'%'];
			}
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->admin[] =array(
					'Id' => $row['idUsuario'],
					'Ad' => $row['nActiveDirectory'],
					'Name' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'FullName' => $row['cNombre'].' '.$row['cApellido'],
					'Gender' => $row['cSexo'],
					'Legajo' => trim($row['cNLegajo']),
					'Monotributo' => $row['nMonotributo'],
					'Cellphone' => $row['nTelefono'],
					'Email' => $row['cEmail'],
					'IdSecretaria' => $row['idSecretaria'],
					'Secretaria' => $row['cNomSecretaria'],
					'IdDependencia' => $row['idDependencia'],
					'Dependencia' => $row['cNomDependencia'],
					'Suggestion' => trim($row['cNLegajo']).' | '.$row['cNombre'].' '.$row['cApellido']
				);
			}

			return $this->admin;
		}

		public function getUser($id){
			$this->query = 'SELECT u.idUsuario, nActiveDirectory, u.cNombre,u.cApellido,u.cSexo,u.cNLegajo,u.nMonotributo,u.nTelefono,u.cEmail,s.idSecretaria,s.cNomSecretaria,d.idDependencia,d.cNomDependencia FROM Usuario AS u
				INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
				WHERE idUsuario = :Id ORDER BY u.idUsuario ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = array();

			while($row = $result->fetch()){
				$response = array(
					'Id' => $row['idUsuario'],
					'Ad' => $row['nActiveDirectory'],
					'Name' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'FullName' => $row['cNombre'].' '.$row['cApellido'],
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

			return $response;
		}

		public function insertUser(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields', $_POST);
			}

			$this->legajo = $this->cleanString($_POST['legajo']);
			$this->email = trim($_POST['email']);
			if(!$this->is_valid_email($this->email)){
				return array('Status' => 'Invalid Email');
			}

			if($this->existingUser('LEGAJO', $this->legajo)){
				return array('Status' => 'Existing User Legajo');
			}

			$this->query = 'INSERT INTO Usuario (idDependencia, nActiveDirectory, cNombre, cApellido, cSexo, cNLegajo, nMonotributo, nTelefono, cEmail, nEstado, cContrasenia) 
				VALUES (:Dependencia, :Ad, UPPER(:Nombre), UPPER(:Apellido), UPPER(:Sexo), UPPER(:Legajo), :Monotributo, :Telefono, :Email, :Estado, :Contrasenia)';
			$this->data = [
				':Dependencia' => $this->cleanString($_POST['dependencia']),
				':Ad' => $this->cleanString($_POST['ad']),
				':Nombre' => $this->cleanString($_POST['nombre']),
				':Apellido' => $this->cleanString($_POST['apellido']),
				':Sexo' => $this->cleanString($_POST['sexo']),
				':Legajo' => $this->legajo,
				':Monotributo' => null,
				':Telefono' => $this->cleanString($_POST['telefono']),
				':Email' => $this->email,
				':Estado' => 1,
				':Contrasenia' => NULL
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'User' => $this->getUser($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateUser(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->legajo = $this->cleanString($_POST['legajo']);

			if(!$this->existingUser('ID', $this->id)){
				return array('Status' => 'Unknown User');
			}

			$this->query = 'SELECT COUNT(idUsuario) FROM Usuario WHERE cNLegajo = :Legajo AND idUsuario != :Id';
			$this->data = [':Legajo' => $this->legajo, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing User Legajo');
			}

			$this->query = 'UPDATE Usuario SET idDependencia = :Dependencia, nActiveDirectory = :Ad, cNombre = UPPER(:Nombre), cApellido = UPPER(:Apellido), cSexo = :Sexo, cNLegajo = UPPER(:Legajo), nMonotributo = :Monotributo, nTelefono = :Telefono, cEmail = :Email WHERE idUsuario = :Id';
			$this->data = [
				':Dependencia' => $this->cleanString($_POST['dependencia']),
				':Ad' => $this->cleanString($_POST['ad']),
				':Nombre' => $this->cleanString($_POST['nombre']),
				':Apellido' => $this->cleanString($_POST['apellido']),
				':Sexo' => $this->cleanString($_POST['sexo']),
				':Legajo' => $this->legajo,
				':Monotributo' => null,
				':Telefono' => $this->cleanString($_POST['telefono']),
				':Email' => trim($_POST['email']),
				':Id' => $this->id
			];
				
			return ($this->executeQuery()) ? array('Status' => 'Success', 'User' => $this->getUser($this->id)) : array('Status' => 'Error');
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['dependencia']) && isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['sexo']) && isset($_POST['legajo']) && isset($_POST['telefono']) && isset($_POST['email'])){
						if(!$this->validateEmptyPost(array('monotribuo','ad','id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['dependencia']) && isset($_POST['nombre']) && isset($_POST['apellido']) && isset($_POST['sexo']) && isset($_POST['legajo']) && isset($_POST['telefono']) && isset($_POST['email'])){
						if(!$this->validateEmptyPost(array('consenia','monotributo','ad')) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
			}
			return $valid;
		}
	}
?>