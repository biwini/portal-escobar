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
		private $so, $bits, $ram, $disc, $motherBoard, $procesador;
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

		public function getEquipo($id) {
			return $this->setList($id);
		}

		public function getProcesadores(){
			$this->query = 'SELECT p.idProcesador AS id, p.cModelo, t.cTipo FROM EquipoProcesador AS p
				INNER JOIN TipoProcesador AS t ON p.idTipoProcesador = t.idTipoProcesador';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getPlacasMadre(){
			$this->query = 'SELECT idPlacaMadre AS id, cModelo FROM EquipoPlacaMadre';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getDiscos($type = 'all'){
			if($type != 'all'){
				$this->query = 'SELECT idDisco AS id, nCapacidad FROM EquipoDisco WHERE idTipoDisco = :Type';
				$this->data = [':Type' => $type];
			}else{
				$this->query = 'SELECT idDisco AS id, nCapacidad FROM EquipoDisco';
				$this->data = [];
			}

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}
		public function getTipoDisco(){
			$this->query = 'SELECT idTipoDisco AS id, cTipo FROM TipoDisco';
			$this->data = [];

			$result = $this->executeQuery();
			$response = array();

			while($row = $result->fetch()){
				$response[] = array(
					'Id' => $row['id'],
					'Type' => $row['cTipo'],
					'Discs' => $this->getDiscos($row['id']),
				);
			}

			return $response;

			// return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function getSistemasOperativos(){
			$this->query = 'SELECT idSistemaOperativo AS id, cNombre FROM EquipoSistemaOperativo';
			$this->data = [];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
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
			$this->NInterno = intval($_POST['interno_ingreso']);
			$this->Type = intval($_POST['equipo_ingreso']);
			$this->Dependence = $dependencia;
			$this->NPatrimony = $_POST['patrimonio_ingreso'];
			$this->so = (isset($_POST['so_ingreso'])) ? $_POST['so_ingreso'] : NULL;
			$this->bits = (isset($_POST['bits_so_ingreso'])) ? $_POST['bits_so_ingreso'] : NULL;
			$this->ram = (isset($_POST['ram_ingreso'])) ? $_POST['ram_ingreso'] : NULL;
			$this->disc = (isset($_POST['cantidad_disco_ingreso'])) ? $_POST['cantidad_disco_ingreso'] : NULL;
			$this->motherBoard = (isset($_POST['mother_ingreso'])) ? $_POST['mother_ingreso'] : NULL;
			$this->procesador = (isset($_POST['procesador_ingreso'])) ?$_POST['procesador_ingreso'] : NULL;
			$this->Model = $this->cleanString($_POST['modelo_ingreso']);
			$this->Brand = $this->cleanString($_POST['marca_ingreso']);

			$existIntern = $this->existingEquipo('INTERNO', $this->NInterno);
			$existPat = $this->existingEquipo('PATRIMONIO', $this->NPatrimony);

			if($isUpdate){
				if (!$existIntern) {
					return NULL;
				}

				$this->query = 'SELECT COUNT(idEquipo) FROM Equipo WHERE nPatrimonio = :Pat OR nInterno = :Intern';
				$this->data = [':Pat' => $this->NPatrimony, ':Intern' => $this->NInterno];

				if ($this->searchRecords() <= 0) {
					return NULL;
				}

				$this->query = 'UPDATE Equipo SET 
					nPatrimonio = :Patrimony, 
					idDependencia = :Dependence, 
					idTipo = :Type, 
					cMarca = :Brand,
					cModelo = :Model,
					idPlacaMadre = :Mother,
					idProcesador = :Procesador,
					idSo = :So,
					nBitsSo = :Bits,
					nRam = :Ram,
					idDisco = :Disc,
					idModificado = :IdUser,
					dFechaModificado = :Fecha
					WHERE nInterno = :Intern';

				$this->data = [
					':Patrimony' => ($this->NPatrimony == '' && $this->NPatrimony == 0) ? NULL : $this->NPatrimony,
					':Dependence' => $this->Dependence,
					':Type' => $this->Type,
					':Brand' => $this->Brand,
					':Model' => $this->Model,
					':Mother' => $this->motherBoard,
					':Procesador' => $this->procesador,
					':So' => $this->so,
					':Bits' => $this->bits,
					':Ram' => $this->ram,
					':Disc' => $this->disc,
					':IdUser' => $_SESSION['ID_USER'],
					':Fecha' => $this->fecha,
					':Intern' => $this->NInterno
				];

				return ($this->executeQuery()) ? $this->getIdBy('INTERNO', $this->NInterno) : $this->getIdBy('INTERNO', $this->NInterno);
			}else{
				if(!($existIntern || $existPat)) {
					$this->query = 'INSERT INTO Equipo (idUsuario, idDependencia, idTipo, cMarca, cModelo, idPlacaMadre, idProcesador, idSo, nBitsSo, nRam, idDisco, nPatrimonio, nInterno, idAlta, dFechaAlta) 
						VALUES (:User, :Dependence, :Type, :Brand, :Model, :Mother, :Procesador, :So, :nBitsSo, :Ram, :Disc, :Patrimony, :Intern, :IdAlta, :Fecha)';
					$this->data = [
						':User' => NULL,
						':Dependence' => $this->Dependence,
						':Type' => $this->Type,
						':Brand' => $this->Brand,
						':Model' => $this->Model,
						':Mother' => $this->motherBoard,
						':Procesador' => $this->procesador,
						':So' => $this->so,
						':nBitsSo' => $this->bits,
						':Ram' => $this->ram,
						':Disc' => $this->disc,
						':Patrimony' => ($this->NPatrimony == '') ? NULL : $this->NPatrimony,
						':Intern' => $this->NInterno,
						':IdAlta' => $_SESSION['ID_USER'],
						':Fecha' => $this->fecha
					];	

					return ($this->executeQuery()) ? $this->getLastInsertedId() : NULL;
				} 
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
			
			$this->query = 'SELECT e.idEquipo, e.idUsuario, e.idDependencia, e.idTipo, e.idPlacaMadre, e.idProcesador, e.idDisco, e.idSo,
				e.nBitsSo, e.nRam, e.cMarca, e.cModelo, e.nPatrimonio, e.nInterno,
				d.cNomDependencia, d.idSecretaria,
				te.idTipoEquipo, te.cTipo,
				so.cNombre AS so,
				m.cModelo AS mother,
				p.cModelo AS procesador,
				ed.idTipoDisco, ed.nCapacidad FROM Equipo AS e
				INNER JOIN Dependencia AS d ON e.idDependencia = d.idDependencia
				INNER JOIN TipoEquipo AS te ON e.idTipo = te.idTipoEquipo
				LEFT JOIN EquipoPlacaMadre AS m ON e.idPlacaMadre = m.idPlacaMadre
				LEFT JOIN EquipoProcesador AS p ON e.idProcesador = p.idProcesador
				LEFT JOIN EquipoSistemaOperativo AS so ON e.idSo = so.idSistemaOperativo 
				LEFT JOIN EquipoDisco AS ed ON e.idDisco = ed.idDisco 
				'.$where;
			$this->data = ($id == 'all') ? [] : [':Id' => intval($id, 10)];

			$result = $this->executeQuery();
			
			while ($row = $result->fetch(PDO::FETCH_ASSOC)){
				$this->ListEquipo[] = array(
					'IdSecretary' => $row['idSecretaria'],
					'IdDependence' => $row['idDependencia'],
					'NameDependence' => $row['cNomDependencia'],
				 	'IdEquipo' => $row['idEquipo'],
				 	'Type' => $row['idTipoEquipo'],
					'TypeName' => $row['cTipo'],
					'IdSo' => $row['idSo'],
					'So' => $row['so'],
					'BitsSo' => $row['nBitsSo'],
					'IdMother' => $row['idPlacaMadre'],
					'Mother' => $row['mother'],
					'IdProcesador' => $row['idProcesador'],
					'Procesador' => $row['procesador'],
					'IdTypeDisc' => $row['idTipoDisco'],
					'DiscCapacity' => $row['nCapacidad'],
					'Ram' => $row['nRam'],
				 	'Brand' => $row['cMarca'],
				 	'Model' => $row['cModelo'],
				 	'Patrimony' => $row['nPatrimonio'],
					'Intern' => $row['nInterno'],
					'isComplete' => $this->isComplete($row)
				);
			}

			return $this->ListEquipo;
		} 

		public function isComplete($equipo){
			$complete = true;
			foreach($equipo as $key => $value) {
				if($key != 'cMarca' && $key != 'cModelo' && $key != 'nPatrimonio' && $key != 'idUsuario' && $key != 'cNomDependencia'){
					if(empty($value)){
						$complete = false;
						break;
					}
				}
			}

			return $complete;
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

		public function updateEquipo(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields', 'ASD' => $_POST);
			}

			$legajo = $this->cleanString($_POST['user']);
			
			$dependencia = $this->getUserDependence($this->getUserIdByLegajo($legajo));

			$this->NInterno = intval($_POST['interno_ingreso'], 10);
			$this->Type = intval($_POST['equipo_ingreso'], 10);
			$this->Dependence = $dependencia;
			$this->NPatrimony = intval($_POST['patrimonio_ingreso'], 10);
			$this->so = $this->cleanString($_POST['so_ingreso']);
			$this->bits = $this->cleanString($_POST['bits_so_ingreso']);
			$this->ram = $this->cleanString($_POST['ram_ingreso']);
			$this->disc = $this->cleanString($_POST['cantidad_disco_ingreso']);
			$this->motherBoard = $this->cleanString($_POST['mother_ingreso']);
			$this->procesador = $this->cleanString($_POST['procesador_ingreso']);
			$this->Model = $this->cleanString($_POST['modelo_ingreso']);
			$this->Brand = $this->cleanString($_POST['marca_ingreso']);

			$existIntern = $this->existingEquipo('INTERNO', $this->NInterno);

			if(!$existIntern){
				return array('Status' => 'Existing Intern', $_POST);
			}

			$this->query = 'UPDATE Equipo SET idDependencia = :Dependence, idTipo = :Type, cMarca = :Brand, cModelo = :Model, idPlacaMadre = :Mother, idProcesador = :Procesador,
				idSo = :So, nBitsSo = :Bits, nRam = :Ram, idDisco = :Disc, idModificado = :IdUser, dFechaModificado = :Fecha
				WHERE nInterno = :Intern';
			$this->data = [
				':Dependence' => $this->Dependence,
				':Type' => $this->Type,
				':Brand' => $this->Brand,
				':Model' => $this->Model,
				':Mother' => $this->motherBoard,
				':Procesador' => $this->procesador,
				':So' => $this->so,
				':Bits' => $this->bits,
				':Ram' => $this->ram,
				':Disc' => $this->disc,
				':IdUser' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Intern' => $this->NInterno
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Equipo' => $this->setList($this->getIdBy('INTERNO', $this->NInterno))) : array('Status' => 'Error');
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
            		if(isset($_POST['user'], $_POST['cantidad_disco_ingreso'], $_POST['bits_so_ingreso'], $_POST['equipo_ingreso'], $_POST['interno_ingreso'], $_POST['mother_ingreso'], $_POST['procesador_ingreso'], $_POST['ram_ingreso'], $_POST['so_ingreso'], $_POST['tipo_disco_ingreso'] )){
            			if(!$this->validateEmptyPost(array('marca_ingreso','patrimonio_ingreso','modelo_ingreso'))){
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
