<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class program extends globalController{
		public $program = array();
		private $name;
		private $url;
		private $id;
		private $idDependence;

		public function getProgram(){
			$this->query = 'SELECT p.idPrograma,p.idDependencia,p.cNomPrograma,p.nEstado,p.cUrl,d.cNomDependencia,s.idSecretaria,s.cNomSecretaria FROM Programa AS p
			INNER JOIN Dependencia AS d ON p.idDependencia = d.idDependencia
			INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria
			ORDER BY p.idPrograma ASC';
			$this->data = [];
			$result = $this->executeQuery();

			if($result){
				while($row = $result->fetch()){
					$this->program[] =array(
						'Status' => 'Success',
						'Id' => $row['idPrograma'],
						'Name' => $row['cNomPrograma'],
						'Url' => $row['cUrl'],
						'State' => $row['nEstado'],
						'IdSecretaria' => $row['idSecretaria'],
						'Secretaria' => $row['cNomSecretaria'],
						'IdDependencia' => $row['idDependencia'],
						'Dependencia' => $row['cNomDependencia']
					);
				}
			}else{
				$this->program[] = array('Status' => 'Error');
			}
			//Descomentar esta linea si se desea que devuelva el array de manera automatica.
			//return $this->program;
		}
		public function insertProgram(){
			$this->name = $this->cleanString($_POST['inpPrograma']);
			$this->url = (!empty(trim($_POST['programUrl']))) ? $_POST['programUrl'] : NULL;
			$this->idDependence = intval($_POST['programDependencia']);
			if($this->existingDependence($this->idDependence)){
				$this->query = 'SELECT COUNT(idPrograma) FROM Programa WHERE UPPER(cNomPrograma) = UPPER(:Program) AND idDependencia != :Dependencia';
				$this->data = [':Program' => $this->name,':Dependencia' => $this->idDependence];

				if($this->searchRecords() == 0){
					$this->query = 'INSERT INTO Programa (cNomPrograma,idDependencia,cUrl,dFechaAlta) VALUES (UPPER(:Program), :Dependencia, :Url, :Fecha)';
					$this->data = [
						':Program' => $this->name,
						':Dependencia' => $this->idDependence,
						':Url' => $this->url,
						':Fecha' => $this->fecha
					];
					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Existing Program');
				}
			}else{
				return array('Status' => 'Unknown Dependence');
			}
		}
		public function changeProgramState(){
			$this->id = $this->cleanString($_POST['program']);
			if(!empty($this->id) && $this->id > 0){
				$type = $this->cleanString($_POST['action']);
				if($type == 'Deshabilitar'){
					$this->query = 'UPDATE Programa SET nEstado = 0, dFechaBaja = :Fecha WHERE idPrograma = :Id';
					$this->data = [':Id' => $this->id, ':Fecha' => $this->fecha];
				}else if($type == 'Habilitar'){
					$this->query = 'UPDATE Programa SET nEstado = 1, dFechaBaja = :Fecha WHERE idPrograma = :Id';
					$this->data = [':Id' => $this->id, ':Fecha' => NULL];
				}

				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Invalid Form');
			}
		}
		public function updateProgram(){
			$this->id = $this->cleanString($_POST['id']);
			$this->name = $this->cleanString($_POST['modProgram']);
			$this->url = (!empty(trim($_POST['modProgramUrl']))) ? $_POST['modProgramUrl'] : NULL;
			$this->idDependence = intval($_POST['modProgramDependencia']);
			if($this->existingDependence($this->idDependence)){
				if($this->id > 0){
					$this->query = 'UPDATE Programa SET cNomPrograma = UPPER(:Name), idDependencia = :Dependencia, cUrl = :Url WHERE idPrograma = :Id';
					$this->data = [':Name' => $this->name, ':Dependencia' => $this->idDependence, ':Url' => $this->url, ':Id' => $this->id];

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Invalid Form');
				}
			}else{
				return array('Status' => 'Invalid Form');
			}
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['inpPrograma']) && isset($_POST['programUrl']) && isset($_POST['programDependencia'])){
						if(!$this->validateEmptyPost(array('programUrl'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['modProgram']) && isset($_POST['modProgramUrl']) && isset($_POST['modProgramDependencia'])){
						if(!$this->validateEmptyPost(array('modProgramUrl')) && intval(trim($_POST['id'])) != 0){
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