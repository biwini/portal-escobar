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

		public function getSecretaries(){
			$this->query = 'SELECT s.idSecretaria,s.cNomSecretaria,s.nEstado,s.dFechaAlta FROM Secretaria AS s ORDER BY s.idSecretaria ASC';
			$this->data = [];

			$result = $this->executeQuery();
			$response = array();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFechaAlta']);

				$response[] = array(
					'Id' => $row['idSecretaria'],
				 	'Name' => $row['cNomSecretaria'], 
				 	'State' => $row['nEstado'],
				 	'Dependences' => $this->getSecretaryDependences($row['idSecretaria']),
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}

			return $response;
		}

		public function getSecretaryById($id){
			$this->query = 'SELECT s.idSecretaria,s.cNomSecretaria,s.nEstado,s.dFechaAlta FROM Secretaria AS s
				WHERE s.idSecretaria = :Id ORDER BY s.idSecretaria ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = array();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFechaAlta']);

				$response = array(
					'Id' => $row['idSecretaria'],
				 	'Name' => $row['cNomSecretaria'], 
				 	'State' => $row['nEstado'],
				 	'Dependences' => $this->getSecretaryDependences($row['idSecretaria']),
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}

			return $response;
		}

		public function addSecretary(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->Name = $this->cleanString($_POST['secretaria_name']);
			$this->Director = (isset($_POST['director_secretaria'])) ? $this->cleanString($_POST['director_secretaria']) : null;

			if($this->existSecretary('NAME', $this->Name)){
				return array('Status' => 'Existing Secretary');
			}

			$this->query = 'INSERT INTO Secretaria (cNomSecretaria, cDirector, dFechaAlta) VALUES (UPPER(:Secretaria), UPPER(:Director), :Fecha)';
			$this->data = [':Secretaria' =>  $this->Name, ':Director' => $this->Director, ':Fecha' => $this->fecha];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getSecretaryById($this->getLastInsertedId())) : array('Status' => 'Error');
		}

		public function updateSecretary(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->Id = $this->cleanString($_POST['id']);

			if(!$this->existSecretary('ID', $this->Id)){
				return array('Status' => 'Unknown Secretary');
			}

			$this->Name = $this->cleanString($_POST['secretaria_name']);
			// $this->Director = $this->cleanString($_POST['new_director']);
			$this->Director = null;

			$this->query = 'UPDATE Secretaria SET cNomSecretaria = UPPER(:Secretaria), cDirector = UPPER(:Director) WHERE idSecretaria = :Id';
			$this->data = [':Secretaria' => $this->Name, ':Director' => $this->Director, ':Id' => $this->Id];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getSecretaryById($this->Id)) : array('Status' => 'Error');
		}

		// public function changeSecretaryState(){
		// 	$this->secretary['Id'] = $this->cleanString($_POST['id']);
		// 	if(!empty($this->secretary['Id']) && $this->secretary['Id'] > 0){
		// 		if($this->existSecretary('ID', $this->secretary['Id'])){
		// 			$type = $this->cleanString($_POST['action']);
		// 			if($type == 'Deshabilitar'){
		// 				$this->query = 'UPDATE Secretaria SET nEstado = 0, dFechaBaja = :Fecha WHERE idSecretaria = :Id';
		// 				$this->data = [':Fecha' => $this->fecha, ':Id' => $this->secretary['Id']];
		// 			}else if($type == 'Habilitar'){
		// 				$this->query = 'UPDATE Secretaria SET nEstado = 1, dFechaBaja = :Fecha WHERE idSecretaria = :Id';
		// 				$this->data = [':Fecha' => NULL, ':Id' => $this->secretary['Id']];
		// 			}

		// 			if($this->executeQuery()){
		// 				return array('Status' => 'Success');
		// 			}else{
		// 				return array('Status' => 'Error');
		// 			}
		// 		}else{
		// 			return array('Status' => 'Unknown Dependence');
		// 		}
		// 	}else{
		// 		return array('Status' => 'Invalid call');
		// 	}
		// }

		private function getSecretaryDependences($secretary){
			$dependences = array();

			$this->query = 'SELECT d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
				INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
				WHERE d.idSecretaria = :Secretary ORDER BY d.idDependencia ASC';
			$this->data = [':Secretary' => $secretary];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$dependences[] = array(
					'IdSecretary' => $secretary,
					'IdDependence' => $row['idDependencia'],
				 	'Name' => $row['cNomDependencia'], 
				 	'IdLocation' => $row['idLocalidad'],
				 	'Location' => $row['cLocalidad'],
				 	'Address' => $row['cDireccion'],
				 	'State' => $row['nEstado']
				);
			}
			return $dependences;
		}

		// public function changeDependenceState(){
		// 	$this->dependence['Id'] = $this->cleanString($_POST['id']);
		// 	if(!empty($this->dependence['Id']) && $this->dependence['Id'] > 0){
		// 		if($this->existingDependence('ID', $this->dependence['Id'])){
		// 			$type = $this->cleanString($_POST['action']);
		// 			if($type == 'Deshabilitar'){
		// 				$this->query = 'UPDATE Dependencia SET nEstado = 0, dFechaBaja = :Fecha WHERE idDependencia = :Id';
		// 				$this->data = [':Fecha' => $this->fecha, ':Id' => $this->dependence['Id']];
		// 			}else if($type == 'Habilitar'){
		// 				$this->query = 'UPDATE Dependencia SET nEstado = 1, dFechaBaja = :Fecha WHERE idDependencia = :Id';
		// 				$this->data = [':Fecha' => NULL, ':Id' => $this->dependence['Id']];
		// 			}

		// 			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		// 		}else{
		// 			return array('Status' => 'Unknown Dependence', 'Hola' => $this->existingDependence($this->dependence['Id']));
		// 		}
		// 	}else{
		// 		return array('Status' => 'Invalid call');
		// 	}
		// }
		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'changeState':
            		if(isset($_POST['id']) && isset($_POST['action'])){
            			if(!$this->validateEmptyPost(array('id')) && (trim($_POST['action']) == 'Deshabilitar' || trim($_POST['action']) == 'Habilitar')){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['id']) || (isset($_POST['id']) && isset($_POST['secretaria_name']) && isset($_POST['dependencia_name']) && isset($_POST['ubicacion']) && isset($_POST['direccion']))){
            			if(!$this->validateEmptyPost(array('ubicacion','direccion','director_secretaria'))){
            				$valid = true;
            			}
            		}
            	break;
            	case 'insert':
            		if( isset($_POST['secretaria_name']) || (isset($_POST['secretaria']) && isset($_POST['dependencia']) && isset($_POST['ubicacion']) && isset($_POST['direccion']))){
            			if(!$this->validateEmptyPost(array('ubicacion','direccion','director_secretaria', 'id'))){
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