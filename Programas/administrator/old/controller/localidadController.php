<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class localidad extends globalController{
		public $localidad = array();

		public function getLocation(){
			$this->query = 'SELECT idLocalidad,cLocalidad FROM Localidad ORDER BY idLocalidad ASC';
			$this->data = [];
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->localidad[] =array(
					'Id' => $row['idLocalidad'],
					'Location' => $row['cLocalidad'],
				);
			}
			//DESCOMENTAR ESTA LINEA SI SE DESEA QUE DEVUELVA EL ARRAY CON LOS RESULTADOS
			//return $this->localidad;
		}
		public function insertLocation(){
			$this->localidad['Location'] = $this->cleanString($_POST['localidad']);
			$this->query = 'SELECT COUNT(idLocalidad) FROM Localidad WHERE cLocalidad = UPPER(:Location)';
			$this->data = [':Location' => $this->localidad['Location']];
			if($this->searchRecords() == 0){
				$this->query = 'INSERT INTO Localidad (cLocalidad) VALUES (UPPER(:Location))';

				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Existing Location Name');
			}
		}
		public function updateLocation(){
			$this->localidad['Id'] = $this->cleanString(intval($_POST['id'], 10));
			if($this->existingLocation($this->localidad['Id'])){
				$this->localidad['Location'] = $this->cleanString($_POST['modLocalidad']);
				$this->query = 'SELECT COUNT(idLocalidad) FROM Localidad WHERE cLocalidad = UPPER(:Location) AND idLocalidad != :Id';
				$this->data = [':Location' => $this->localidad['Location'], ':Id' => $this->localidad['Id']];

				if($this->searchRecords() == 0){
					$this->query = 'UPDATE Localidad SET cLocalidad = UPPER(:Location) WHERE idLocalidad = :Id';

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Existing Location Name');
				}
			}else{
				return array('Status' => 'Unknown Location');
			}
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['localidad'])){
						if(!$this->validateEmptyPost(array())){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['modLocalidad'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
				
				default:
					# code...
				break;
			}
			return $valid;
		}
	}
?>