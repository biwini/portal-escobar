<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class secretaria extends globalController{

		private $Id;
		private $Name;
		private $Director;


		public $listDependence = array();
		public $listSecretary = Array();
		public $secretary = array();
		public $dependence = array();
		public $id,$dependencia,$ubicacion;

		public function insertSecretary(){
			$this->Name = $this->cleanString($_POST['secretaria']);
			$this->Director = (isset($_POST['director_secretaria'])) ? $this->cleanString($_POST['director_secretaria']) : null;

			if($this->existSecretary('NAME', $this->Name)){
				return array('Status' => 'Existing Secretary');
			}

			$this->query = 'INSERT INTO Secretaria (cNomSecretaria, cDirector, dFechaAlta) VALUES (UPPER(:Secretaria), UPPER(:Director), :Fecha)';
			$this->data = [':Secretaria' =>  $this->Name, ':Director' => $this->Director, ':Fecha' => $this->fecha];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function getSecretary(){
			$this->query = 'SELECT s.idSecretaria,s.cNomSecretaria,s.nEstado,s.dFechaAlta FROM Secretaria AS s ORDER BY s.idSecretaria ASC';
			$this->data = [];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFechaAlta']);

				$this->listSecretary[] = array(
					'IdSecretaria' => $row['idSecretaria'],
				 	'Name' => $row['cNomSecretaria'], 
				 	'State' => $row['nEstado'],
				 	'Dependences' => $this->getSecretaryDependence($row['idSecretaria']),
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}
		}
		public function updateSecretary(){
			$this->Id = $this->cleanString($_POST['id']);

			if(!$this->existSecretary('ID', $this->Id)){
				return array('Status' => 'Unknown Secretary');
			}

			$this->Name = $this->cleanString($_POST['new_secretaria']);
			$this->Director = $this->cleanString($_POST['new_director']);

			$this->query = 'UPDATE Secretaria SET cNomSecretaria = UPPER(:Secretaria), cDirector = UPPER(:Director) WHERE idSecretaria = :Id';
			$this->data = [':Secretaria' => $this->Name, ':Director' => $this->Director, ':Id' => $this->Id];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function changeSecretaryState(){
			$this->secretary['Id'] = $this->cleanString($_POST['id']);
			if(!empty($this->secretary['Id']) && $this->secretary['Id'] > 0){
				if($this->existSecretary('ID', $this->secretary['Id'])){
					$type = $this->cleanString($_POST['action']);
					if($type == 'Deshabilitar'){
						$this->query = 'UPDATE Secretaria SET nEstado = 0, dFechaBaja = :Fecha WHERE idSecretaria = :Id';
						$this->data = [':Fecha' => $this->fecha, ':Id' => $this->secretary['Id']];
					}else if($type == 'Habilitar'){
						$this->query = 'UPDATE Secretaria SET nEstado = 1, dFechaBaja = :Fecha WHERE idSecretaria = :Id';
						$this->data = [':Fecha' => NULL, ':Id' => $this->secretary['Id']];
					}

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Unknown Dependence');
				}
			}else{
				return array('Status' => 'Invalid call');
			}
		}

		public function insertDependence(){
			$this->dependence['Name'] = $this->cleanString($_POST['dependencia']);
			$this->dependence['Location'] = (!empty($_POST['ubicacion'])) ? $this->cleanString($_POST['ubicacion']) : NULL;
			$this->dependence['Address'] = (!empty($_POST['direccion'])) ? $this->cleanString($_POST['direccion']) : NULL;
			$this->dependence['Director'] = (isset($_POST['director_dependencia']) && !empty($_POST['director_dependencia'])) ? $this->cleanString($_POST['director_dependencia']) : NULL;
			$this->secretary['Id'] = $this->cleanString($_POST['dependence_secretary']);
			if(!$this->existSecretary('ID', $this->secretary['Id'])){
				return array('Status' => 'Unknown Secretary');
			}
			if($this->existingLocation($this->dependence['Location'])){
				$this->query = 'SELECT COUNT(idDependencia) FROM Dependencia WHERE cNomDependencia = UPPER(:Dependencia) AND idSecretaria = :Secretaria';
				$this->data = [':Dependencia' => $this->dependence['Name'], ':Secretaria' => $this->secretary['Id']];

				if($this->searchRecords() == 0){
					$this->query = 'INSERT INTO Dependencia (cNomDependencia, cDireccion, idLocalidad, idSecretaria, dFechaAlta) VALUES (UPPER(:Dependencia), UPPER(:Direccion), :Localidad, UPPER(:Secretaria), :Fecha)';
					$this->data = [
						':Dependencia' =>  $this->dependence['Name'],
						':Direccion' =>  $this->dependence['Address'],
						':Localidad' => $this->dependence['Location'],
						':Secretaria' => $this->secretary['Id'],
						':Fecha' => $this->fecha
					];

					return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
				}else{
					return array('Status' => 'Existing Dependence');
				}
			}else{
				return array('Status' => 'Unknown Location');
			}
		}

		private function getSecretaryDependence($secretary){
			$this->listDependence = array();
			$this->query = 'SELECT d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
				INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
				WHERE d.idSecretaria = :Secretary ORDER BY d.idDependencia ASC';
			$this->data = [':Secretary' => $secretary];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$this->listDependence[] = array(
					'IdSecretary' => $secretary,
					'IdDependence' => $row['idDependencia'],
				 	'Name' => $row['cNomDependencia'], 
				 	'IdLocation' => $row['idLocalidad'],
				 	'Location' => $row['cLocalidad'],
				 	'Address' => $row['cDireccion'],
				 	'State' => $row['nEstado']
				);
			}
			return $this->listDependence;
		}
		public function updateDependence(){
			$this->secretary['Id'] = $this->cleanString($_POST['new_dependence_secretary']);
			if($this->existSecretary('ID', $this->secretary['Id'])){
				$this->dependence['Id'] = $this->cleanString($_POST['id']);
				if($this->existingDependence($this->dependence['Id'])){

					$this->dependence['Name'] = $this->cleanString($_POST['new_dependencia']);
					$this->dependence['Location'] = (!empty(trim($_POST['new_ubicacion']))) ? $this->cleanString($_POST['new_ubicacion']) : NULL;
					$this->dependence['Address'] = (!empty(trim($_POST['new_direccion']))) ? $this->cleanString($_POST['new_direccion']) : NULL;
					$this->dependence['Director'] = (!empty($_POST['new_director'])) ? $this->cleanString($_POST['new_director']) : NULL;

					$this->query = 'UPDATE Dependencia SET idSecretaria = :Secretaria, cNomDependencia = UPPER(:Dependencia), cDirector = UPPER(:Director), idLocalidad = UPPER(:Ubicacion), cDireccion = UPPER(:Direccion) WHERE idDependencia = :Id';
					$this->data = [
						':Secretaria' => $this->secretary['Id'],
						':Dependencia' => $this->dependence['Name'],
						':Director' => $this->dependence['Director'],
						':Ubicacion' => $this->dependence['Location'],
						':Direccion' => $this->dependence['Address'],
						':Id' => $this->dependence['Id']
					];

					return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
				}else{
					return array('Status' => 'Unknown Dependence');
				}
			}else{
				return array('Status' => 'Unknown Secretary');
			}
		}
		public function changeDependenceState(){
			$this->dependence['Id'] = $this->cleanString($_POST['id']);
			if(!empty($this->dependence['Id']) && $this->dependence['Id'] > 0){
				if($this->existingDependence($this->dependence['Id'])){
					$type = $this->cleanString($_POST['action']);
					if($type == 'Deshabilitar'){
						$this->query = 'UPDATE Dependencia SET nEstado = 0, dFechaBaja = :Fecha WHERE idDependencia = :Id';
						$this->data = [':Fecha' => $this->fecha, ':Id' => $this->dependence['Id']];
					}else if($type == 'Habilitar'){
						$this->query = 'UPDATE Dependencia SET nEstado = 1, dFechaBaja = :Fecha WHERE idDependencia = :Id';
						$this->data = [':Fecha' => NULL, ':Id' => $this->dependence['Id']];
					}

					return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
				}else{
					return array('Status' => 'Unknown Dependence', 'Hola' => $this->existingDependence($this->dependence['Id']));
				}
			}else{
				return array('Status' => 'Invalid call');
			}
		}
		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'changeState':
            		if(isset($_POST['id']) && isset($_POST['action'])){
            			if(!$this->validateEmptyPost(array()) && (trim($_POST['action']) == 'Deshabilitar' || trim($_POST['action']) == 'Habilitar')){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['id']) || (isset($_POST['id']) && isset($_POST['new_dependence_secretary']) && isset($_POST['new_dependencia']) && isset($_POST['new_ubicacion']) && isset($_POST['new_direccion']))){
            			if(!$this->validateEmptyPost(array('new_ubicacion','new_direccion','director_secretaria'))){
            				$valid = true;
            			}
            		}
            	break;
            	case 'insert':
            		if( isset($_POST['secretaria']) || (isset($_POST['dependence_secretary']) && isset($_POST['dependencia']) && isset($_POST['ubicacion']) && isset($_POST['direccion']))){
            			if(!$this->validateEmptyPost(array('ubicacion','direccion','director_secretaria'))){
            				$valid = true;
            			}
            		}
            	break;
            	default:
            		$valid = false;
            	break;
            }
            return $valid;
        }
	}
?>