<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class jurisdiccion extends globalController{
		private $id,$name,$code;

		public function getJurisdicciones(){
			$this->query = 'SELECT idJurisdiccion, cCodigo AS cCodigo, cNombre FROM Jurisdiccion WHERE idBaja IS NULL ORDER BY idJurisdiccion ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getJurisdiccion($id){
			$this->query = 'SELECT idJurisdiccion, cCodigo, cNombre FROM Jurisdiccion WHERE idJurisdiccion = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertJurisdiccion(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingJurisdiccion('CODE', $this->code)){
				return array('Status' => 'Existing Jurisdiccion Code');
			}

			if($this->existingJurisdiccion('NAME', $this->name)){
				return array('Status' => 'Existing Jurisdiccion Name');
			}

			$this->query = 'INSERT INTO Jurisdiccion (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getJurisdiccion($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateJurisdiccion(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingJurisdiccion('ID', $this->id)){
				return array('Status' => 'Unknown Jurisdiccion');
			}

			$this->query = 'SELECT COUNT(idJurisdiccion) FROM Jurisdiccion WHERE (cCodigo = :Code OR cNombre = :Name) AND idJurisdiccion != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Jurisdiccion Code Or Name');
			}

			$this->query = 'UPDATE Jurisdiccion SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idJurisdiccion = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getJurisdiccion($this->id)) : array('Status' => 'Error');
		}

		public function deleteJurisdiccion(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingJurisdiccion('ID', $this->id)){
				return array('Status' => 'Unknown Jurisdiccion');
			}

			$this->query = 'UPDATE Jurisdiccion SET idBaja = :User, dBaja = :Fecha WHERE idJurisdiccion = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingJurisdiccion($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idJurisdiccion) FROM Jurisdiccion WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idJurisdiccion) FROM Jurisdiccion WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idJurisdiccion) FROM Jurisdiccion WHERE idJurisdiccion = :search AND idBaja IS NULL';
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