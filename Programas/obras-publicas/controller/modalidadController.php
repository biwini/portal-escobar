<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class modalidad extends globalController{
		private $id,$name,$code;

		public function getModalidades(){
			$this->query = 'SELECT idModalidad, cCodigo AS cCodigo, cNombre FROM Modalidad WHERE idBaja IS NULL ORDER BY idModalidad ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);

			// $result = $this->executeQuery();
			// $response = array();

			// while($row = $result->fetch()){
			// 	$response[] = array(
			// 		'Id' => $row['idModalidad'],
			// 		'Code' => $row['cCodigo'],
			// 		'Name' => $row['cNombre'],
			// 	);
			// }

			// return $response;
		}

		public function getModalidad($id){
			$this->query = 'SELECT idModalidad, cCodigo, cNombre FROM Modalidad WHERE idModalidad = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertModalidad(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingModalidad('CODE', $this->code)){
				return array('Status' => 'Existing Modalidad Code');
			}

			if($this->existingModalidad('NAME', $this->name)){
				return array('Status' => 'Existing Modalidad Name');
			}

			$this->query = 'INSERT INTO Modalidad (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Modalidad' => (object)$this->getModalidad($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateModalidad(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingModalidad('ID', $this->id)){
				return array('Status' => 'Unknown Modalidad');
			}

			$this->query = 'SELECT COUNT(idModalidad) FROM Modalidad WHERE (cCodigo = :Code OR cNombre = :Name) AND idModalidad != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Modalidad Code Or Name');
			}

			$this->query = 'UPDATE Modalidad SET cCodigo = :Code, cNombre = trim(:Name), idModificacion = :User, dModificacion = :Fecha WHERE idModalidad = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Modalidad' => (object)$this->getModalidad($this->id)) : array('Status' => 'Error');
		}

		public function deleteModalidad(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingModalidad('ID', $this->id)){
				return array('Status' => 'Unknown Modalidad');
			}

			$this->query = 'UPDATE Modalidad SET idBaja = :User, dBaja = :Fecha WHERE idModalidad = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingModalidad($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idModalidad) FROM Modalidad WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idModalidad) FROM Modalidad WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idModalidad) FROM Modalidad WHERE idModalidad = :search AND idBaja IS NULL';
				break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['codigo'], $_POST['nombre'])){
						if(!$this->validateEmptyPost(array('id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['codigo'], $_POST['nombre'], $_POST['id'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
				case 'delete':
					if(isset($_POST['id'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
			}
			return $valid;
		}
	}
?>