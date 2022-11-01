<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class equipo extends globalController{
		public $Id;
		private $Type;
		private $Model;
		private $Brand;
		private $so, $ram, $typeDisc, $capacity;
		private $Dependence;

		private $NPatrimony;
		private $NInterno;
		private $User;

		private $ListEquipo;
		private $ListIntern;

		function __construct(){
			$this->Patrimony = array();
			
			parent::__construct();
		}
		public function getEquipo($id){
			return $this->setList($id);
		}

		public function getListPatrimonio(){
			$this->query = 'SELECT e.nPatrimonio, te.cTipo FROM Equipo AS e
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo ORDER BY e.idEquipo DESC';
			$this->data = [];

			$result = $this->executeQuery();

			while ($row = $result->fetch()) {
				$this->Patrimony[] = array('Id' => $row['nPatrimonio'], 'Suggestion' => $row['nPatrimonio'].' | '.$row['cTipo']);
			}
			return $this->Patrimony;
		}
		public function getListIntern(){
			$this->query = 'SELECT e.nInterno, te.cTipo FROM Equipo AS e
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo ORDER BY e.idEquipo DESC';
			$this->data = [];

			$result = $this->executeQuery();

			while ($row = $result->fetch()) {
				$this->ListIntern[] = array('Id' => $row['nInterno'], 'Suggestion' => $row['nInterno'].' | '.$row['cTipo']);
			}
			return $this->ListIntern;
		}
//---------------------------- ESTA FUNCION ESTA DISEÑA PARA UTILIZARSE AL SER LLAMADA DESDE TICKET CONTROLLER, NO MODIFICAR -------------------------------------------------
		public function addEquipoFromTicket($dependencia, $isUpdate){
			$this->NInterno = intval($_POST['interno_ingreso'], 10);
			$this->Type = intval($_POST['equipo_ingreso'], 10);
			$this->Dependence = $dependencia;
			$this->NPatrimony = $_POST['patrimonio_ingreso'];
			$this->so = $_POST['so_ingreso'];
			$this->ram = $_POST['ram_ingreso'];
			$this->typeDisc = $_POST['tipo_disco_ingreso'];
			$this->capacity = $_POST['cantidad_disco_ingreso'];

			$this->Model = $this->cleanString($_POST['modelo_ingreso']);
			$this->Brand = $this->cleanString($_POST['marca_ingreso']);

			if($isUpdate){
				$this->query = 'UPDATE Equipo SET idDependencia = :Dependence, idTipo = :Type, cMarca = :Brand, cModelo = :Model, cSo = :So, nRam = :Ram, cTipoDisco = :TypeDisc, nCapacidadDisco = :Capacity, idModificado = :IdUser, dFechaModificado = :Fecha WHERE nInterno = :Intern';
				$this->data = [
					':Dependence' => $this->Dependence,
					':Type' => $this->Type,
					':Brand' => $this->Brand,
					':Model' => $this->Model,
					':So' => $this->so,
					':Ram' => $this->ram,
					':TypeDisc' => $this->typeDisc,
					':Capacity' => $this->capacity,
					':IdUser' => $_SESSION['ID_USER'],
					':Fecha' => $this->fecha,
					':Intern' => $this->NInterno
				];

				return ($this->executeQuery()) ? $this->getIdBy('INTERNO', $this->NInterno) : $this->getIdBy('INTERNO', $this->NInterno);
			}else{
				$this->query = 'INSERT INTO Equipo (idUsuario, idDependencia, idTipo, cMarca, cModelo, cSo, nRam, cTipoDisco, nCapacidadDisco, nPatrimonio, nInterno, idAlta, dFechaAlta) VALUES (:User, :Dependence, :Type, :Brand, :Model, :So, :Ram, :TypeDisc, :Capacity, :Patrimony, :Intern, :IdAlta, :Fecha)';
				$this->data = [
					':User' => NULL,
					':Dependence' => $this->Dependence,
					':Type' => $this->Type,
					':Brand' => $this->Brand,
					':Model' => $this->Model,
					':So' => $this->so,
					':Ram' => $this->ram,
					':TypeDisc' => $this->typeDisc,
					':Capacity' => $this->capacity,
					':Patrimony' => ($this->NPatrimony == '') ? NULL : $this->NPatrimony,
					':Intern' => $this->NInterno,
					':IdAlta' => $_SESSION['ID_USER'],
					':Fecha' => $this->fecha
				];

				return ($this->executeQuery()) ? $this->getLastInsertedId() : NULL;
			}
			
			return NULL;
		}
//---------------------------- FINAL DE FUNCION -------------------------------------------------------------------------
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
		public function getIdBy($Type, $Search){
			$Type = mb_strtoupper($Type, 'UTF-8');
			switch ($Type) {
				case 'INTERNO': $this->query = 'SELECT TOP 1 idEquipo FROM Equipo WHERE nInterno = :Search'; break;
				case 'PATRIMONIO': $this->query = 'SELECT TOP 1 idEquipo FROM Equipo WHERE nPatrimonio = :Search'; break;
				default: $this->query = 'SELECT TOP 1 idEquipo FROM Equipo WHERE nInterno = :Search'; break;
			}
			$this->data = [':Search' => $Search];

			return $this->executeQuery()->fetchColumn(0);
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

		private function validTypeEquipment(){
			$this->query = 'SELECT COUNT(idTipoEquipo) FROM TipoEquipo WHERE idTipoEquipo = :Id';
			$this->data = [':Id' => $this->Type];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function setList($id){
			$where = ($id == 'all') ? '' : 'WHERE e.idEquipo = :Id';
			$this->query = 'SELECT e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno, d.cNomDependencia, d.idSecretaria, te.idTipoEquipo, te.cTipo FROM Equipo AS e
				INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo '.$where;
			$this->data = ($id == 'all') ? [] : [':Id' => intval($id, 10)];

			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$this->ListEquipo[] = array(
					'IdSecretary' => $row['idSecretaria'],
					'IdDependence' => $row['idDependencia'],
					'NameDependence' => $row['cNomDependencia'],
				 	'IdEquipo' => $row['idEquipo'],
				 	'Type' => $row['idTipoEquipo'],
				 	'TypeName' => $row['cTipo'],
				 	'Brand' => $row['cMarca'], 
				 	'Model' => $row['cModelo'], 
				 	'Patrimony' => $row['nPatrimonio'], 
				 	'Intern' => $row['nInterno'],
				);
			}

			return $this->ListEquipo;
		} 
		public function insertEquipo(){

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

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getEquipo($this->getLastInsertedId())) : array('Status' => 'Error');
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
		private $ListTypes;

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