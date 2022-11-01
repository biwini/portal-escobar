<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class fuente extends globalController{
		private $id,$name,$code;

		public function getFuentes(){
			$this->query = 'SELECT idFuente, cCodigo, cNombre FROM Fuente WHERE idBaja IS NULL ORDER BY idFuente ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getFuenteById($id){
			$this->query = 'SELECT idFuente, cCodigo, cNombre FROM Fuente WHERE idBaja IS NULL WHERE idFuente = :Id';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertFuente(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingFuente('CODE', $this->code)){
				return array('Status' => 'Existing Fuente Code');
			}

			if($this->existingFuente('NAME', $this->name)){
				return array('Status' => 'Existing Fuente Name');
			}

			$this->query = 'INSERT INTO Fuente (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getFuenteById($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateFuente(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingFuente('ID', $this->id)){
				return array('Status' => 'Unknown Fuente');
			}

			$this->query = 'SELECT COUNT(idFuente) FROM Fuente WHERE (cCodigo = :Code OR cNombre = :Name) AND idFuente != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Fuente Code Or Name');
			}

			$this->query = 'UPDATE Fuente SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idFuente = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getFuenteById($this->id)) : array('Status' => 'Error');
		}

		public function deleteFuente(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields',$_POST);
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingFuente('ID', $this->id)){
				return array('Status' => 'Unknown Fuente');
			}

			if($this->isInObra($this->id)){
				return array('Status' => 'Have Obra');
			}

			$this->query = 'UPDATE Fuente SET idBaja = :User, dBaja = :Fecha WHERE idFuente = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function isInObra($id){
			$this->query = 'SELECT COUNT(idImputacion) FROM Imputacion WHERE idFuente = :fuente';
			$this->data = [':fuente' => $id];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingFuente($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idFuente) FROM Fuente WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idFuente) FROM Fuente WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idFuente) FROM Fuente WHERE idFuente = :search AND idBaja IS NULL';
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