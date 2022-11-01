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

		public function getProgram(){
			$this->query = 'SELECT p.idPrograma,p.idArea,p.cNomPrograma,p.nEstado,p.cUrl,a.cNomArea FROM Programa AS p
			INNER JOIN area AS a ON p.idArea = a.idArea
			ORDER BY idPrograma ASC';
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
						'IdArea' => $row['idArea'],
						'NameArea' => $row['cNomArea']
					);
				}
			}else{
				$this->program[] = array('Status' => 'Error');
			}
			//Descomentar esta linea si se desea que devuelva el array de manera automatica.
			//return $this->program;
		}
		public function insertProgram(){
			$this->name = $this->cleanString($_POST['program']);
			$this->url = $this->cleanString($_POST['programUrl']);
			if(!empty($this->name) && !empty($this->url)){
				$this->query = 'SELECT COUNT(idPrograma) FROM Programa WHERE UPPER(cNomPrograma) = UPPER(:Program)';
				$this->data = [':Program' => $this->name];

				if($this->searchRecords() == 0){
					$this->query = 'INSERT INTO Programa (cNomPrograma,idArea,cUrl,dFechaAlta) VALUES (UPPER(:Program), :Area, :Url, :Fecha)';
					$this->data = [':Program' => $this->name, ':Area' => intval($_POST['area']), ':Url' => $this->url, ':Fecha' => $this->fecha];
					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Existing Program');
				}
			}else{
				return array('Status' => 'Invalid Form');
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
			$this->id = $this->cleanString(trim($_POST['id']));
			$this->name = $this->cleanString(trim($_POST['program']));
			$this->url = trim($_POST['modProgramUrl']);

			if((!empty($this->id) && $this->id > 0) && (!empty($this->name))){
				$this->query = 'UPDATE Programa SET cNomPrograma = UPPER(:Name), idArea = :Area, cUrl = :Url WHERE idPrograma = :Id';
				$this->data = [':Name' => $this->name, ':Area' => intval($_POST['area']), ':Url' => $this->url, ':Id' => $this->id];

				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Invalid Form');
			}
		}
	}
?>