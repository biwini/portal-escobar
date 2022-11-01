<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class dependencia extends globalController{
        private $id;
        private $name;
        private $localidad;
        private $direccion;
        private $director;

        private $secretaria;

		public function getDependences(){
			$this->query = 'SELECT d.idSecretaria, s.cNomSecretaria, d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
                INNER JOIN secretaria AS s ON d.idSecretaria = s.idSecretaria
				INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
				ORDER BY d.idDependencia ASC';
			$this->data = [];

            $result = $this->executeQuery();
            $dependences = array();

			while ($row = $result->fetch()){
				$dependences[] = array(
                    'IdSecretary' => $row['idSecretaria'],
                    'Secretary' => $row['cNomSecretaria'],
					'Id' => $row['idDependencia'],
				 	'Name' => $row['cNomDependencia'], 
				 	'IdLocalidad' => $row['idLocalidad'],
				 	'Location' => $row['cLocalidad'],
				 	'Address' => $row['cDireccion'],
				 	'State' => $row['nEstado']
				);
			}
			return $dependences;
		}

		public function getDependenceById($id){
			$this->query = 'SELECT d.idSecretaria, s.cNomSecretaria, d.idDependencia,d.cNomDependencia,d.cDireccion,d.idLocalidad,l.cLocalidad,d.nEstado FROM Dependencia AS d
                INNER JOIN secretaria AS s ON d.idSecretaria = s.idSecretaria
                INNER JOIN localidad AS l ON d.idLocalidad = l.idLocalidad
                WHERE d.idDependencia = :Id ORDER BY d.idDependencia ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = array();

			while ($row = $result->fetch()){
				$response = array(
					'IdSecretary' => $row['idSecretaria'],
					'Secretary' => $row['cNomSecretaria'],
					'Id' => $row['idDependencia'],
				 	'Name' => $row['cNomDependencia'], 
				 	'IdLocation' => $row['idLocalidad'],
				 	'Location' => $row['cLocalidad'],
				 	'Address' => $row['cDireccion'],
				 	'State' => $row['nEstado']
				);
			}

			return $response;
		}

		public function addDepdendence(){
            if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
            }
            
			$this->name = $this->cleanString($_POST['dependencia_name']);
			$this->localidad = (!empty($_POST['localidad'])) ? $this->cleanString($_POST['localidad']) : NULL;
			$this->direccion = (!empty($_POST['direccion'])) ? $this->cleanString($_POST['direccion']) : NULL;
			$this->director = (isset($_POST['director']) && !empty($_POST['director'])) ? $this->cleanString($_POST['director']) : NULL;

			$this->secretaria = $this->cleanString($_POST['secretaria']);

			if(!$this->existSecretary('ID', $this->secretaria)){
				return array('Status' => 'Unknown Secretary');
			}

			if(!$this->existingLocalidad('ID',$this->localidad)){
				return array('Status' => 'Unknown Location');
            }
            
			if($this->existingDependence('NAME', $this->name)){
				return array('Status' => 'Existing Dependence');
			}

			$this->query = 'INSERT INTO Dependencia (cNomDependencia, cDireccion, idLocalidad, idSecretaria, dFechaAlta) VALUES (UPPER(:Dependencia), UPPER(:Direccion), :Localidad, UPPER(:Secretaria), :Fecha)';
			$this->data = [
				':Dependencia' =>  $this->name,
				':Direccion' =>  $this->direccion,
				':Localidad' => $this->localidad,
				':Secretaria' => $this->secretaria,
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getDependenceById($this->getLastInsertedId())) : array('Status' => 'Error');
		}

		public function updateDependence(){
            if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
            }

            $this->id = $this->cleanString($_POST['id']);
            $this->secretaria = $this->cleanString($_POST['secretaria']);

			if(!$this->existSecretary('ID', $this->secretaria)){
				return array('Status' => 'Unknown Secretary');
            }

			if(!$this->existingDependence('ID', $this->id)){
                return array('Status' => 'Unknown Dependence');
            }

            $this->name = $this->cleanString($_POST['dependencia_name']);
            $this->localidad = (!empty(trim($_POST['localidad']))) ? $this->cleanString($_POST['localidad']) : NULL;
            $this->direccion = (!empty(trim($_POST['direccion']))) ? $this->cleanString($_POST['direccion']) : NULL;
            $this->director = (!empty($_POST['director'])) ? $this->cleanString($_POST['director']) : NULL;

            $this->query = 'UPDATE Dependencia SET idSecretaria = :Secretaria, cNomDependencia = UPPER(:Dependencia), cDirector = UPPER(:Director), idLocalidad = UPPER(:Localidad), cDireccion = UPPER(:Direccion) WHERE idDependencia = :Id';
            $this->data = [
                ':Secretaria' => $this->secretaria,
                ':Dependencia' => $this->name,
                ':Director' => $this->director,
                ':Localidad' => $this->localidad,
                ':Direccion' => $this->direccion,
                ':Id' => $this->id
            ];

            return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getDependenceById($this->id)) : array('Status' => 'Error');
		}

		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'update':
            		if(isset($_POST['id'], $_POST['dependencia_name'], $_POST['localidad'], $_POST['direccion'], $_POST['secretaria'])){
            			if(!$this->validateEmptyPost(array('localidad','direccion','director_secretaria')) && intval(trim($_POST['id'])) != 0){
            				$valid = true;
            			}
            		}
            	break;
            	case 'insert':
            		if( isset($_POST['secretaria'], $_POST['dependencia_name'], $_POST['localidad'], $_POST['direccion'])){
            			if(!$this->validateEmptyPost(array('localidad','direccion','director_secretaria', 'id'))){
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