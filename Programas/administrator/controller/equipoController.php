<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class equipo extends globalController{
		private $Id;
		private $Type;
		private $Model;
		private $Brand;
		private $Dependence;

		private $NPatrimony;
		private $NInterno;

		private $User;

		public function getEquipos(){
			$this->query = 'SELECT u.cNLegajo, e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, e.cMotivoBaja, e.idBaja, e.dFechaBaja, d.cNomDependencia, d.idSecretaria, te.cTipo FROM Equipo AS e
				INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
				LEFT JOIN Usuario AS u ON e.idUsuario = u.idUsuario';
			$this->data = [];

			$result = $this->executeQuery();
			$equipos = array();

			while ($row = $result->fetch()){
				$equipos[] = array(
					'Id' => $row['idEquipo'],
					'IdType' => $row['idTipo'],
				 	'Type' => $row['cTipo'],
				 	'Brand' => $row['cMarca'], 
				 	'Model' => $row['cModelo'], 
				 	'Patrimony' => $row['nPatrimonio'], 
					'Intern' => $row['nInterno'],
					'User' => ($row['cNLegajo'] == null) ? 'SIN ASIGNAR' : $row['cNLegajo'],
					'IdSecretary' => $row['idSecretaria'],
					'Secretary' => $this->searchSecretary($row['idSecretaria']),
					'IdDependence' => $row['idDependencia'],
					'Dependence' => $row['cNomDependencia'],
					'State' => ($row['idBaja'] == null) ? 1 : 0,
					'DateBaja' => $row['dFechaBaja'],
					'MotivoBaja' => $row['cMotivoBaja']
				);
			}

			return $equipos;
		}

		public function getEquipoById($id){
			$this->query = 'SELECT u.cNLegajo, e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, e.cMotivoBaja, e.idBaja, e.dFechaBaja, d.cNomDependencia, d.idSecretaria, te.cTipo FROM Equipo AS e
				INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
				LEFT JOIN Usuario AS u ON e.idUsuario = u.idUsuario
				WHERE e.idEquipo = :Id';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$equipo = array();

			while ($row = $result->fetch()){
				$equipo = array(
					'Id' => $row['idEquipo'],
					'IdType' => $row['idTipo'],
				 	'Type' => $row['cTipo'],
				 	'Brand' => $row['cMarca'], 
				 	'Model' => $row['cModelo'], 
				 	'Patrimony' => $row['nPatrimonio'], 
					'Intern' => $row['nInterno'],
					'User' => ($row['cNLegajo'] == null) ? 'SIN ASIGNAR' : $row['cNLegajo'],
					'IdSecretary' => $row['idSecretaria'],
					'Secretary' => $this->searchSecretary($row['idSecretaria']),
					'IdDependence' => $row['idDependencia'],
					'Dependence' => $row['cNomDependencia'],
					'State' => ($row['idBaja'] == null) ? 1 : 0,
					'DateBaja' => $row['dFechaBaja'],
					'MotivoBaja' => $row['cMotivoBaja']
				);
			}

			return $equipo;
		}

		private function setValues(){
			$this->Type = intval($_POST['tipo_equipo'], 10);
			$this->Dependence = $_POST['dependencia'];
			$this->NPatrimony = intval($_POST['patrimonio']);
			$this->NInterno = intval($_POST['interno']);

			$this->Model = $this->cleanString($_POST['modelo']);
			$this->Brand = $this->cleanString($_POST['marca']);
			
			$this->User = ($_POST['usuario'] == 'SIN ASIGNAR') ? null : $this->cleanString($_POST['usuario']);
		}

		public function insertEquipo(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->setValues();

			if(!empty($this->User)){
				if(!$this->existingUser('LEGAJO', $this->User)){
					return array('Status' => 'Unknown User');
				}
			}
			
			if(!$this->validTypeEquipment($this->Type)){
				return array('Status' => 'Invalid Type');
			}

			if($this->existingEquipo('PATRIMONIO', $this->NPatrimony)){
				return array('Status' => 'Existing Patrimony');
			}

			if($this->existingEquipo('INTERNO', $this->NInterno)){
				return array('Status' => 'Existing Intern');
			}

			if($this->existingDependence('ID', $this->Dependence)){
				return array('Status' => 'Unknown Dependence Or Secretary');
			}

			$this->User = $this->getUserIdByLegajo($this->User);

			$this->query = 'INSERT INTO Equipo (idUsuario, idDependencia, idTipo, cMarca, cModelo, nPatrimonio, nInterno, idAlta, dFechaAlta) VALUES (:User, :Dependence, :Type, :Brand, :Model, :Patrimony, :Intern, :IdAlta, :Fecha)';
			$this->data = [
				':User' => (empty($this->User)) ? null : $this->User,
				':Dependence' => $this->Dependence,
				':Type' => $this->Type,
				':Brand' => $this->Brand,
				':Model' => $this->Model,
				':Patrimony' => $this->NPatrimony,
				':Intern' => $this->NInterno,
				':IdAlta' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getEquipoById($this->getLastInsertedId()))  : array('Status' => 'Error');
		}

		public function updateEquipo(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->Id = $this->cleanString($_POST['id']);

			if(!$this->existingEquipo('ID', $this->Id)){
				return array('Status' => 'Unknown Equipo');
			}

			$this->setValues();

			if(!empty($this->User)){
				if(!$this->existingUser('LEGAJO', $this->User)){
					return array('Status' => 'Unknown User');
				}
			}

			if(!$this->validTypeEquipment($this->Type)){
				return array('Status' => 'Invalid Type');
			}

			if($this->existingDependence('ID', $this->Dependence)){
				return array('Status' => 'Unknown Dependence Or Secretary');
			}
			
			$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE (nPatrimonio = :Patrimony OR nInterno = :Intern) AND idEquipo != :Id';
			$this->data = [':Patrimony' => $this->NPatrimony, ':Intern' => $this->NInterno, ':Id' => $this->Id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Existing Patrimony Or Intern');
			}

			$this->User = $this->getUserIdByLegajo($this->User);

			$this->query = 'UPDATE Equipo SET idUsuario = :User, idDependencia = :Dependence, idTipo = :Type, cMarca = :Brand, cModelo = :Model, nPatrimonio = :Patrimony, nInterno = :Intern, IdModificado = :IdModificado, dFechaModificado = :Fecha
				WHERE idEquipo = :Id';
			$this->data = [
				':User' => (empty($this->User)) ? null : $this->User,
				':Dependence' => $this->Dependence,
				':Type' => $this->Type,
				':Brand' => $this->Brand,
				':Model' => $this->Model,
				':Patrimony' => $this->NPatrimony,
				':Intern' => $this->NInterno,
				':IdModificado' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->Id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getEquipoById($this->Id)) : array('Status' => 'Error');

		}

		public function removeEquipo(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->Id = $this->cleanString($_POST['id']);

			if(!$this->existingEquipo('ID', $this->Id)){
				return array('Status' => 'Unknown Equipo');
			}

			$this->query = 'UPDATE Equipo SET cMotivoBaja = :Motivo, idBaja = :Baja, dFechaBaja = :Fecha WHERE idEquipo = :Id';
			$this->data = [
				':Motivo' => $this->cleanString($_POST['motivo_baja']),
				':Baja' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->Id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getEquipoById($this->Id)) : array('Status' => 'Error');
		}

		private function validTypeEquipment($type){
			$this->query = 'SELECT COUNT(idTipoEquipo) FROM TipoEquipo WHERE idTipoEquipo = :Id';
			$this->data = [':Id' => $type];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingEquipo($type, $search){
			$type = mb_strtoupper($type, 'UTF-8');
			switch ($type){
				case 'ID': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search'; break;
				case 'INERNO': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nInterno = :Search'; break;
				case 'PATRIMONIO': $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nPatrimonio = :Search'; break;
				default: $this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE idEquipo = :Search'; break;
			}
			$this->data = [':Search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'insert':
            		if(isset($_POST['tipo_equipo'], $_POST['dependencia'], $_POST['patrimonio'], $_POST['usuario'], $_POST['interno'], $_POST['modelo'], $_POST['marca'])){
            			if(!$this->validateEmptyPost(array('modelo', 'marca','usuario','id'))){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['tipo_equipo'], $_POST['dependencia'], $_POST['patrimonio'], $_POST['usuario'], $_POST['interno'], $_POST['modelo'], $_POST['marca'], $_POST['id'])){
            			if(!$this->validateEmptyPost(array('modelo', 'marca','usuario')) && intval(trim($_POST['id'])) != 0){
            				$valid = true;
            			}
            		}
				break;
				case 'delete':
            		if(isset($_POST['id'], $_POST['motivo_baja'])){
            			if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
            				$valid = true;
            			}
            		}
            	break;
            }
            return $valid;
        }
	}

// --------------------------- CLASE TIPOS DE EQUIPO -------------------------------------------
	class tipoEquipo extends globalController{
		private $Id;
		private $Type;
		public $ListTypes;

		function __construct(){

			parent::__construct();
		}

		public function getTypes(){

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