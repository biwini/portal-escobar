<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class estado extends globalController{
		private $id,$name,$code;

		public function getEstados(){
			$this->query = 'SELECT idEstado, cCodigo AS cCodigo, cNombre FROM Estado WHERE idBaja IS NULL ORDER BY idEstado ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);

			// $result = $this->executeQuery();
			// $response = array();

			// while($row = $result->fetch()){
			// 	$response[] = array(
			// 		'Id' => $row['idEstado'],
			// 		'Code' => $row['cCodigo'],
			// 		'Name' => $row['cNombre'],
			// 	);
			// }

			// return $response;
		}

		public function getEstado($id){
			$this->query = 'SELECT idEstado, cCodigo, cNombre FROM Estado WHERE idEstado = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertEstado(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if($this->existingEstado('CODE', $this->code)){
				return array('Status' => 'Existing Estado Code');
			}

			if($this->existingEstado('NAME', $this->name)){
				return array('Status' => 'Existing Estado Name');
			}

			$this->query = 'INSERT INTO Estado (cCodigo, cNombre, idAlta, dAlta) VALUES (:Code, :Name, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getEstado($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateEstado(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
			$this->name = $this->cleanString($_POST['nombre']);

			if(!$this->existingEstado('ID', $this->id)){
				return array('Status' => 'Unknown Estado');
			}

			$this->query = 'SELECT COUNT(idEstado) FROM Estado WHERE (cCodigo = :Code OR cNombre = :Name) AND idEstado != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Name' => $this->name, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Estado Code Or Name');
			}

			$this->query = 'UPDATE Estado SET cCodigo = :Code, cNombre = :Name, idModificacion = :User, dModificacion = :Fecha WHERE idEstado = :Id';
			$this->data = [
				':Code' => $this->code,
				':Name' => $this->name,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getEstado($this->id)) : array('Status' => 'Error');
		}

		public function deleteEstado(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingEstado('ID', $this->id)){
				return array('Status' => 'Unknown TipoObra');
			}

			$this->query = 'UPDATE Estado SET idBaja = :User, dBaja = :Fecha WHERE idEstado = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingEstado($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idEstado) FROM Estado WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'NAME':
					$this->query = 'SELECT COUNT(idEstado) FROM Estado WHERE cNombre = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idEstado) FROM Estado WHERE idEstado = :search AND idBaja IS NULL';
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