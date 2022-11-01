<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class proyecto extends globalController{
		private $id,$name,$code;

		public function getProyectos(){
			$this->query = 'SELECT idProyecto, cCodigo AS cCodigo, cNombre FROM Proyecto WHERE idBaja IS NULL ORDER BY idProyecto ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getProyecto($id){
			$this->query = 'SELECT idProyecto, cCodigo, cNombre FROM Proyecto WHERE idProyecto = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertProyecto(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingProyecto('CODE', $this->code)){
				return array('Status' => 'Existing Proyecto Code');
			}

			if($this->existingProyecto('NAME', $this->name)){
				return array('Status' => 'Existing Proyecto Name');
			}

			$this->query = 'INSERT INTO Proyecto (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getProyecto($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateProyecto(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingProyecto('ID', $this->id)){
				return array('Status' => 'Unknown Proyecto');
			}

			$this->query = 'SELECT COUNT(idProyecto) FROM Proyecto WHERE (cCodigo = :Code OR cNombre = :Name) AND idProyecto != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Proyecto Code Or Name');
			}

			$this->query = 'UPDATE Proyecto SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idProyecto = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getProyecto($this->id)) : array('Status' => 'Error');
		}

		public function deleteProyecto(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingProyecto('ID', $this->id)){
				return array('Status' => 'Unknown Proyecto');
			}

			$this->query = 'UPDATE Proyecto SET idBaja = :User, dBaja = :Fecha WHERE idProyecto = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingProyecto($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idProyecto) FROM Proyecto WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idProyecto) FROM Proyecto WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idProyecto) FROM Proyecto WHERE idProyecto = :search AND idBaja IS NULL';
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