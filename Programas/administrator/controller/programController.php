<?php 
    require_once realpath(__DIR__ ).'/globalController.php';

	class program extends globalController{
		public $program = array();
		private $id;
		private $name;
		private $state;
		private $url;
		private $dependence;

		public function getProgram($id){
			$this->query = 'SELECT p.idPrograma,p.idDependencia,p.cNomPrograma,p.nEstado,p.cUrl,d.cNomDependencia,s.idSecretaria,s.cNomSecretaria FROM Programa AS p
				INNER JOIN Dependencia AS d ON p.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
				WHERE idPrograma = :Id ORDER BY p.idPrograma ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = array();
				while($row = $result->fetch()){
					$response = array(
						'Id' => $row['idPrograma'],
						'Program' => $row['cNomPrograma'],
						'Url' => $row['cUrl'],
						'State' => intval($row['nEstado']),
						'IdSecretary' => $row['idSecretaria'],
						'Secretary' => $row['cNomSecretaria'],
						'IdDependence' => $row['idDependencia'],
						'Dependence' => $row['cNomDependencia']
					);
				}

			return $response;
		}

		public function getPrograms(){
			$this->query = 'SELECT p.idPrograma,p.idDependencia,p.cNomPrograma,p.nEstado,p.cUrl,d.cNomDependencia,s.idSecretaria,s.cNomSecretaria FROM Programa AS p
				INNER JOIN Dependencia AS d ON p.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
				ORDER BY p.idPrograma ASC';
			$this->data = [];

			$result = $this->executeQuery();
			$response = array();
			if($result){
				while($row = $result->fetch()){
					$response[] =array(
						'Status' => 'Success',
						'Id' => $row['idPrograma'],
						'Program' => $row['cNomPrograma'],
						'Url' => $row['cUrl'],
						'State' => intval($row['nEstado']),
						'IdSecretary' => $row['idSecretaria'],
						'Secretary' => $row['cNomSecretaria'],
						'IdDependence' => $row['idDependencia'],
						'Dependence' => $row['cNomDependencia']
					);
				}
			}else{
				$response = array('Status' => 'Error');
			}
			// Descomentar esta linea si se desea que devuelva el array de manera automatica.
			return $response;
		}

		public function insertProgram(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->name = $this->cleanString($_POST['programa']);
			$this->url = (!empty(trim($_POST['url']))) ? $_POST['url'] : NULL;
			$this->dependence = intval($_POST['dependencia']);
			$this->state = $this->cleanString($_POST['estado']);

			if(!$this->existingDependence('ID', $this->dependence)){
				return array('Status' => 'Unknown Dependence');
			}

			$this->query = 'SELECT COUNT(idPrograma) FROM Programa WHERE UPPER(cNomPrograma) = UPPER(:Program) AND idDependencia != :Dependencia';
			$this->data = [':Program' => $this->name,':Dependencia' => $this->dependence];

			if($this->searchRecords() == 1){
				return array('Status' => 'Existing Program');
			}

			$this->state = intval($this->cleanString($_POST['estado']), 10);
			
			if($this->state < 0 || $this->state > 1){
				return array('Status' => 'Invalid State');
			}
			
			$this->query = 'INSERT INTO Programa (cNomPrograma,idDependencia,cUrl, nEstado, dFechaAlta, idAlta) VALUES (UPPER(:Program), :Dependencia, :Url, :State, :Fecha, :User)';
			$this->data = [
				':Program' => $this->name,
				':Dependencia' => $this->dependence,
				':Url' => $this->url,
				':State' => $this->state,
				':Fecha' => $this->fecha,
				':User' => $_SESSION['ID_USER']
			];
			
			return ($this->executeQuery()) ? array('Status' => 'Success', 'Programa' => $this->getProgram($this->getLastInsertedId())) : array('Status' => 'Error');
		}

		public function updateProgram(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->name = $this->cleanString($_POST['programa']);
			$this->url = (!empty(trim($_POST['url']))) ? $_POST['url'] : NULL;
			$this->dependence = intval($_POST['dependencia']);
			$this->state = intval($this->cleanString($_POST['estado']), 10);

			if(!$this->existingDependence('ID', $this->dependence)){
				return array('Status' => 'Unknown Dependence');
			}

			$this->query = 'UPDATE Programa SET cNomPrograma = UPPER(:Name), idDependencia = :Dependencia, cUrl = :Url, nEstado = :State, idModificado = :User, dFechaModificado = :Fecha WHERE idPrograma = :Id';
			$this->data = [
				':Name' => $this->name,
				':Dependencia' => $this->dependence,
				':Url' => $this->url,
				':State' => $this->state,
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Programa' => $this->getProgram($this->id)) : array('Status' => 'Error');
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['programa'], $_POST['dependencia'], $_POST['url'], $_POST['estado'])){
						if(!$this->validateEmptyPost(array('url', 'id'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id'], $_POST['programa'], $_POST['dependencia'], $_POST['url'], $_POST['estado'])){
						if(!$this->validateEmptyPost(array('url', 'estado')) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
			}

			return $valid;
		}
	}
?>