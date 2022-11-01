<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class equipo extends globalController{
		private $Id;
		private $Type;
		private $Model;
		private $Brand;
		private $Dependence;

		private $NPatrimony;
		private $NInterno;

		private $User;

		private $ListEquipo;

		function __construct(){

			parent::__construct();
		}
		public function getEquipo(){
			return $this->setList();
		}
		public function validateInsert(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Call');
			}
			$this->setValues();

			if(!$this->validateValues()){
				return array('Status' => 'Invalid Call');
			}

			return $this->insertEquipo();
		}
		private function validateValues(){
			if(!$this->existingDependence($this->Dependence) || !$this->validTypeEquipment()){
				return false;
			}

			if($this->User != 0){
				if(!$this->existingUser('ID', $this->User)){
					return false;
				}
			}
			
			return true;
		}
		private function setValues(){
			$this->Type = intval($_POST['tipo_equipo'], 10);
			$this->Dependence = $_POST['equipo_dependencia'];
			$this->NPatrimony = $_POST['patrimonio'];
			$this->NInterno = $_POST['interno'];

			$this->Model = mb_strtoupper($_POST['modelo'], 'UTF-8');
			$this->Brand = mb_strtoupper($_POST['marca'], 'UTF-8');
			
			$this->User = ($_POST['usuario_asignado'] == 'SIN ASIGNAR') ? 0 : $_POST['usuario_asignado'];
		}
		public function validateUpdate(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Call');
			}

			$this->setValues();

			if(!$this->validateValues()){
				return array('Status' => 'Invalid Call');
			}

			return $this->updateEquipo();
		}
		private function updateEquipo(){
			if($this->existingEquipo('PATRIMONIO', $this->NPatrimony)){
				return array('Status' => 'Existing Patrimony');
			}
			if($this->existingEquipo('INTERNO', $this->NInterno)){
				return array('Status' => 'Existing Intern');
			}

			$this->query = 'UPDATE Equipo SET idUsuario = :User, idDependencia = :Dependence, idTipo = :Type, cMarca = :Brand, cModelo = :Model, cPatrimonio = :Patrimony, cInerno = :Intern, IdModificado = :IdModificado, dFechaModificado = :Fecha';
			$this->data = [
				':User' => $this->User,
				':Dependence' => $this->Dependence,
				':Type' => $this->Type,
				':Brand' => $this->Brand,
				':Model' => $this->Model,
				':Patrimony' => $this->NPatrimony,
				':Intern' => $this->NInterno,
				':IdModificado' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');

		}

		private function validTypeEquipment(){
			$this->query = 'SELECT COUNT(idTipoEquipo) FROM TipoEquipo WHERE idTipoEquipo = :Id';
			$this->data = [':Id' => $this->Type];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function setList(){
			$this->query = 'SELECT e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, d.cNomDependencia, d.idSecretaria, te.cTipo FROM Equipo AS e
				INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo';
			$this->data = [];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$this->ListEquipo[] = array(
					'User' => $this->getUserInfo($row['idUsuario']),
					'IdSecretary' => $row['idSecretaria'],
					'IdDependence' => $row['idDependencia'],
					'NameDependence' => $row['cNomDependencia'],
				 	'IdEquipo' => $row['idEquipo'],
				 	'Type' => $row['cTipo'],
				 	'Brand' => $row['cMarca'], 
				 	'Model' => $row['cModelo'], 
				 	'Patrimony' => $row['nPatrimonio'], 
				 	'Intern' => $row['nInterno']
				);
			}

			return $this->ListEquipo;
		}
		private function insertEquipo(){

			if($this->existingEquipo('PATRIMONIO', $this->NPatrimony)){
				return array('Status' => 'Existing Patrimony');
			}
			if($this->existingEquipo('INTERNO', $this->NInterno)){
				return array('Status' => 'Existing Intern');
			}

			$this->query = 'INSERT INTO Equipo (idUsuario, idDependencia, idTipo, cMarca, cModelo, nPatrimonio, nInterno, idAlta, dFechaAlta) VALUES (:User, :Dependence, :Type, :Brand, :Model, :Patrimony, :Intern, :IdAlta, :Fecha)';
			$this->data = [
				':User' => $this->User,
				':Dependence' => $this->Dependence,
				':Type' => $this->Type,
				':Brand' => $this->Brand,
				':Model' => $this->Model,
				':Patrimony' => $this->NPatrimony,
				':Intern' => $this->NInterno,
				':IdAlta' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingEquipo($type, $search){
			$type = mb_strtoupper($type, 'UTF-8');
			switch ($type){
				case 'ID':
					$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search';
					break;
				case 'INERNO':
					$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nInterno = :Search';
					break;
				case 'PATRIMONIO':
					$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nPatrimonio = :Search';
					break;
				default:
					$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search';
					break;
			}

			$this->data = [':Search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}
		private function getUserInfo($id){
			$this->query = 'SELECT idUsuario, cNombre, cApellido, cNLegajo, nTelefono, cEmail FROM Usuario WHERE idUsuario = :Id';
			$this->data = [':Id' => intval($id, 10)];

			$result = $this->executeQuery();

			$response = array();
			while ($row = $result->fetch()){
				$response = array(
					'Id' => $row['idUsuario'],
					'Name' => $row['cNombre'],
					'LastName' => $row['cApellido'],
					'Legajo' => $row['cNLegajo'],
					'Phone' => $row['nTelefono'],
					'Email' => $row['cEmail']
				);
			}
			return $response;
		}
		private function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'insert':
            		if(isset($_POST['tipo_equipo']) && isset($_POST['equipo_dependencia']) && isset($_POST['patrimonio']) && isset($_POST['usuario_asignado']) && isset($_POST['interno']) && isset($_POST['modelo']) && isset($_POST['marca'])){
            			if(!$this->validateEmptyPost(array('modelo', 'marca','usuario_asignado'))){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['tipo_equipo'], $_POST['equipo_dependencia'], $_POST['patrimonio'], $_POST['usuario_asignado'], $_POST['interno'], $_POST['modelo'], $_POST['marca'], $_POST['id'])){
            			if(!$this->validateEmptyPost(array('modelo', 'marca','usuario_asignado'))){
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

// --------------------------- CLASER TIPOS DE EQUIPO -------------------------------------------
	class tipoEquipo extends globalController{
		private $Id;
		private $Type;
		public $ListTypes;

		function __construct(){

			parent::__construct();
		}

		public function getList(){

			return $this->setList();
		}

		private function setList(){
			$this->query = 'SELECT idTipoEquipo, cTipo FROM TipoEquipo';
			$this->data = [];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$this->ListTypes[] = array(
					'Id' => $row['idTipoEquipo'],
					'Type' => $row['cTipo']
				);
			}

			return $this->ListTypes;
		}

	}

?>