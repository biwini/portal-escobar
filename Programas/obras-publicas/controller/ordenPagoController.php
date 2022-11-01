<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class ordenPago extends globalController{
		private $id,$idObra,$number, $date, $paidDate, $ocea;

		public function getOrdenesPago(){
			$this->query = 'SELECT idOP,idObra, nNro, dFecha, dPagado, cOCEA FROM OP WHERE idBaja IS NULL ORDER BY idOP ASC';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getOrdenById($id){
			$this->query = 'SELECT idOP,idObra, nNro, dFecha, dPagado, cOCEA FROM OP WHERE idBaja IS NULL AND idOP = :Id';
			$this->data = [':Id' => $id];

			return $this->executeQuery()->fetch(PDO::FETCH_ASSOC);
		}

		public function insertOrdenPago(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields', $_POST);
			}

			$this->idObra = $this->cleanString($_POST['obra']);
            $this->number = $this->cleanString($_POST['numero']);
            $this->date = $this->cleanString($_POST['fecha']);
            $this->paidDate = $this->cleanString($_POST['fecha_pago']);
            $this->ocea = $this->cleanString($_POST['ocea']);

			if($this->existingOrden('NUMBER', $this->number)){
				return array('Status' => 'Existing Order Number');
			}

			$this->query = 'INSERT INTO OP (idObra, nNro, dFecha, dPagado, cOCEA, idAlta, dAlta) VALUES (:Obra, :Number, :Date, :Pagado, :Ocea, :User, :Fecha)';
			$this->data = [
				':Obra' => $this->idObra,
                ':Number' => $this->number,
                ':Date' => $this->date,
                ':Pagado' => $this->paidDate,
                ':Ocea' => $this->ocea,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getOrdenById($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		public function updateOrdenPago(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->idObra = $this->cleanString($_POST['obra']);
            $this->number = $this->cleanString($_POST['numero']);
            $this->date = $this->cleanString($_POST['fecha']);
            $this->paidDate = $this->cleanString($_POST['fecha_pago']);
            $this->ocea = $this->cleanString($_POST['ocea']);

			if(!$this->existingOrden('ID', $this->id)){
				return array('Status' => 'Unknown Orden');
			}

			$this->query = 'SELECT COUNT(idOP) FROM OP WHERE nNro = :Number AND idOP != :Id AND idBaja IS NULL';
			$this->data = [':Number' => $this->number, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Order Number');
			}

			$this->query = 'UPDATE OP SET idObra = :Obra, nNro = :Number, dFecha = :Date, dPagado = :Pagado, cOCEA = :Ocea, idModificacion = :User, dModificacion = :Fecha WHERE idOP = :Id';
			$this->data = [
				':Obra' => $this->idObra,
                ':Number' => $this->number,
                ':Date' => $this->date,
                ':Pagado' => $this->paidDate,
                ':Ocea' => $this->ocea,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => (object)$this->getOrdenById($this->id)) : array('Status' => 'Error');
		}

		public function deleteOrdenPago(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields',$_POST);
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingOrden('ID', $this->id)){
				return array('Status' => 'Unknown Fuente');
			}

			$this->query = 'UPDATE OP SET idBaja = :User, dBaja = :Fecha WHERE idOP = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingOrden($type = 'ID', $search){
			switch ($type) {
				case 'NUMBER':
					$this->query = 'SELECT COUNT(idOP) FROM OP WHERE nNro = :search AND idBaja IS NULL';
				break;
				case 'ID':
					$this->query = 'SELECT COUNT(idOP) FROM OP WHERE idOP = :search AND idBaja IS NULL';
				break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['obra'], $_POST['numero'], $_POST['fecha'], $_POST['fecha_pago'], $_POST['ocea'])){
						if(!$this->validateEmptyPost(array('id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['obra'], $_POST['numero'], $_POST['fecha'], $_POST['fecha_pago'], $_POST['ocea'], $_POST['id'])){
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