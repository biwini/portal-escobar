<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class gasto extends globalController{
		private $id,$name,$code;

		public function getObjetosGasto(){
			$this->query = 'SELECT idObjetoGasto, cCodigo AS cCodigo, cNombre FROM ObjetoGasto WHERE idBaja IS NULL ORDER BY idObjetoGasto ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getObjetoGasto($id){
			$this->query = 'SELECT idObjetoGasto, cCodigo, cNombre FROM ObjetoGasto WHERE idObjetoGasto = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertGasto(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingObjetoGasto('CODE', $this->code)){
				return array('Status' => 'Existing Gasto Code');
			}

			if($this->existingObjetoGasto('NAME', $this->name)){
				return array('Status' => 'Existing Gasto Name');
			}

			$this->query = 'INSERT INTO ObjetoGasto (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getObjetoGasto($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateGasto(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingObjetoGasto('ID', $this->id)){
				return array('Status' => 'Unknown Gasto');
			}

			$this->query = 'SELECT COUNT(idObjetoGasto) FROM ObjetoGasto WHERE (cCodigo = :Code OR cNombre = :Name) AND idObjetoGasto != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing ObjetoGasto Code Or Name');
			}

			$this->query = 'UPDATE ObjetoGasto SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idObjetoGasto = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getObjetoGasto($this->id)) : array('Status' => 'Error');
		}

		public function deleteGasto(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingObjetoGasto('ID', $this->id)){
				return array('Status' => 'Unknown Gasto');
			}

			$this->query = 'UPDATE ObjetoGasto SET idBaja = :User, dBaja = :Fecha WHERE idObjetoGasto = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingObjetoGasto($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idObjetoGasto) FROM ObjetoGasto WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idObjetoGasto) FROM ObjetoGasto WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idObjetoGasto) FROM ObjetoGasto WHERE idObjetoGasto = :search AND idBaja IS NULL';
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