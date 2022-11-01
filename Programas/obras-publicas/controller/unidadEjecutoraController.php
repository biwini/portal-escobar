<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class uEjecutora extends globalController{
		private $id,$name,$code;

		public function getUnidadesEjecutoras(){
			$this->query = 'SELECT idUnidadEjecutora, cCodigo AS cCodigo, cNombre FROM UnidadEjecutora WHERE idBaja IS NULL ORDER BY idUnidadEjecutora ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getUnidadEjecutora($id){
			$this->query = 'SELECT idUnidadEjecutora, cCodigo, cNombre FROM UnidadEjecutora WHERE idUnidadEjecutora = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertUnidadEjecutora(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingUnidadEjecutora('CODE', $this->code)){
				return array('Status' => 'Existing Unidad Ejecutora Code');
			}

			if($this->existingUnidadEjecutora('NAME', $this->name)){
				return array('Status' => 'Existing Unidad Ejecutora Name');
			}

			$this->query = 'INSERT INTO UnidadEjecutora (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getUnidadEjecutora($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateUnidadEjecutora(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingUnidadEjecutora('ID', $this->id)){
				return array('Status' => 'Unknown Unidad Ejecutora');
			}

			$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE (cCodigo = :Code OR cNombre = :Name) AND idUnidadEjecutora != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Unidad Ejecutora Code Or Name');
			}

			$this->query = 'UPDATE UnidadEjecutora SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idUnidadEjecutora = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getUnidadEjecutora($this->id)) : array('Status' => 'Error');
		}

		public function deleteUnidadEjecutora(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingUnidadEjecutora('ID', $this->id)){
				return array('Status' => 'Unknown Unidad Ejecutora');
			}

			$this->query = 'UPDATE UnidadEjecutora SET idBaja = :User, dBaja = :Fecha WHERE idUnidadEjecutora = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingUnidadEjecutora($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE idUnidadEjecutora = :search AND idBaja IS NULL';
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