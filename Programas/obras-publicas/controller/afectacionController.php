<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class afectacion extends globalController{
		private $id,$name,$code;

		public function getAfectaciones(){
			$this->query = 'SELECT idAfectacion, cCodigo AS cCodigo, cNombre FROM Afectacion WHERE idBaja IS NULL ORDER BY idAfectacion ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);

			// $result = $this->executeQuery();
			// $response = array();

			// while($row = $result->fetch()){
			// 	$response[] = array(
			// 		'Id' => $row['idAfectacion'],
			// 		'Code' => $row['cCodigo'],
			// 		'Name' => $row['cNombre'],
			// 	);
			// }

			// return $response;
		}

		public function getAfectacion($id){
			$this->query = 'SELECT idAfectacion, cCodigo, cNombre FROM Afectacion WHERE idAfectacion = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertAfectacion(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $_POST['codigo'];
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingAfectacion('CODE', $this->code)){
				return array('Status' => 'Existing Afectacion Code');
			}

			if($this->existingAfectacion('NAME', $this->name)){
				return array('Status' => 'Existing Afectacion Name');
			}

			$this->query = 'INSERT INTO Afectacion (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Afectacion' => (object)$this->getAfectacion($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateAfectacion(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $_POST['codigo'];
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingAfectacion('ID', $this->id)){
				return array('Status' => 'Unknown Afectacion');
			}

			$this->query = 'SELECT COUNT(idAfectacion) FROM Afectacion WHERE (cCodigo = :Code OR cNombre = :Name) AND idAfectacion != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Afectacion Code Or Name');
			}

			$this->query = 'UPDATE Afectacion SET cCodigo = :Code, cNombre = trim(:Name), idModificado = :User, dModificado = :Fecha WHERE idAfectacion = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Afectacion' => (object)$this->getAfectacion($this->id)) : array('Status' => 'Error');
		}

		public function deleteAfectacion(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingAfectacion('ID', $this->id)){
				return array('Status' => 'Unknown Afectacion');
			}

			$this->query = 'UPDATE Afectacion SET idBaja = :User, dBaja = :Fecha WHERE idAfectacion = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingAfectacion($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idAfectacion) FROM Afectacion WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idAfectacion) FROM Afectacion WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idAfectacion) FROM Afectacion WHERE idAfectacion = :search AND idBaja IS NULL';
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