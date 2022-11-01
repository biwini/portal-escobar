<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class localidad extends globalController{
		public $localidad = array();

		public function getLocalidades(){
			$this->query = 'SELECT idLocalidad,cLocalidad FROM Localidad ORDER BY idLocalidad ASC';
			$this->data = [];
			
			$result = $this->executeQuery();
			$localidades = array();

			while($row = $result->fetch()){
				$localidades[] = array(
					'Id' => $row['idLocalidad'],
					'Name' => $row['cLocalidad'],
				);
			}
			//DESCOMENTAR ESTA LINEA SI SE DESEA QUE DEVUELVA EL ARRAY CON LOS RESULTADOS
			return $localidades;
		}

		private function getLocalidadById($id){
			$this->query = 'SELECT idLocalidad,cLocalidad FROM Localidad 
				WHERE idLocalidad = :Id';
			$this->data = [':Id' => $id];
			
			$result = $this->executeQuery();
			$localidad = array();

			while($row = $result->fetch()){
				$localidad = array(
					'Id' => $row['idLocalidad'],
					'Name' => $row['cLocalidad'],
				);
			}

			return $localidad;
		}

		public function addLocalidad(){
			$this->localidad = $this->cleanString($_POST['localidad']);

			if($this->existingLocalidad('NAME', $this->localidad)){
				return array('Status' => 'Existing Location Name');
			}

			$this->query = 'INSERT INTO Localidad (cLocalidad) VALUES (:Localidad)';
			$this->data = [':Localidad'];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getLocalidadById($this->getLastInsertedId())) : array('Status' => 'Error');
		}
		
		public function updateLocalidad(){
			$this->id = $this->cleanString(intval($_POST['id'], 10));

			if(!$this->existingLocalidad('ID', $this->id)){
				return array('Status' => 'Unknown Location');
			}

			$this->localidad = $this->cleanString($_POST['localidad']);

			$this->query = 'SELECT COUNT(idLocalidad) FROM Localidad WHERE cLocalidad = :Localidad AND idLocalidad != :Id';
			$this->data = [':Localidad' => $this->localidad, ':Id' => $this->id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Location Name');
			}

			$this->query = 'UPDATE Localidad SET cLocalidad = :Localidad WHERE idLocalidad = :Id';

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getLocalidadById($this->id)) : array('Status' => 'Error');
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['localidad'])){
						if(!$this->validateEmptyPost(array('id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['localidad'])){
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