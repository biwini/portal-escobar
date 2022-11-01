<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class proveedor extends globalController{
		private $id,$name,$code,$cuit;

		public function getProveedores(){
			$this->query = 'SELECT idProveedor, cCodigo, cNombre, nCuit FROM Proveedor WHERE idBaja IS NULL ORDER BY cNombre ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getProveedor($id){
			$this->query = 'SELECT idProveedor, cCodigo, cNombre, nCuit FROM Proveedor WHERE idProveedor = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertProveedor(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->code = $this->cleanString($_POST['codigo']);
            $this->name = $this->cleanString($_POST['nombre']);
            $this->cuit = $this->cleanString($_POST['cuit']);

			if($this->existingProveedor('CODE', $this->code)){
				return array('Status' => 'Existing Proveedor Code');
			}

			if($this->existingProveedor('CUIT', $this->cuit)){
				return array('Status' => 'Existing Proveedor Cuit');
			}

			$this->query = 'INSERT INTO Proveedor (cCodigo, cNombre, nCuit, idAlta, dAlta) VALUES (:Code, :Name, :Cuit, :User, :Fecha)';
			$this->data = [
				':Code' => $this->code,
                ':Name' => $this->name,
                ':Cuit' => $this->cuit,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getProveedor($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateProveedor(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->code = $this->cleanString($_POST['codigo']);
            $this->name = $this->cleanString($_POST['nombre']);
            $this->cuit = $this->cleanString($_POST['cuit']);

			if(!$this->existingProveedor('ID', $this->id)){
				return array('Status' => 'Unknown Proveedor');
			}

			$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE (cCodigo = :Code OR nCuit = :Cuit) AND idProveedor != :Id AND idBaja IS NULL';
			$this->data = [':Code' => $this->code, ':Cuit' => $this->cuit, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Proveedor Cuit Or Code');
			}

			$this->query = 'UPDATE Proveedor SET cCodigo = :Code, cNombre = :Name, nCuit = :Cuit, idModificacion = :User, dModificacion = :Fecha WHERE idProveedor = :Id';
			$this->data = [
				':Code' => $this->code,
                ':Name' => $this->name,
                ':Cuit' => $this->cuit,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getProveedor($this->id)) : array('Status' => 'Error');
		}

		public function deleteProveedor(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingProveedor('ID', $this->id)){
				return array('Status' => 'Unknown Proveedor');
			}

			$this->query = 'UPDATE Proveedor SET idBaja = :User, dBaja = :Fecha WHERE idProveedor = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingProveedor($type = 'ID', $search){
			switch ($type) {
				case 'CODE':
					$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE cCodigo = :search AND idBaja IS NULL';
				break;
				case 'CUIT':
					$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE nCuit = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE idProveedor = :search AND idBaja IS NULL';
				break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['codigo'], $_POST['nombre'], $_POST['cuit'])){
						if(!$this->validateEmptyPost(array('id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['codigo'], $_POST['nombre'], $_POST['cuit'], $_POST['id'])){
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