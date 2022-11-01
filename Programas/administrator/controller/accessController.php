<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class access extends globalController{
		public $access = array();
		public $auditoria = array();
		private $id;
		public $legajo,$programa,$permiso, $estado;

		public function getAccess(){
			$this->query = 'SELECT a.idAcceso,a.nPermiso,a.nEstado,u.idUsuario,u.cNombre,u.cApellido,u.cNLegajo,p.idPrograma,p.cNomPrograma FROM acceso AS a
				INNER JOIN usuario AS u ON a.idUsuario = u.idUsuario
				INNER JOIN programa AS p ON a.idPrograma = p.idPrograma';
			$this->data = [];

			$result = $this->executeQuery();
			$response =array();

			while($row = $result->fetch()){
				$response[] =array(
					'Id' => $row['idAcceso'],
					'IdUser' => $row['idUsuario'],
					'IdProgram' => $row['idPrograma'],
					'Permiso' => $row['nPermiso'],
					'UserName' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'Legajo' => $row['cNLegajo'],
					'ProgramName' => $row['cNomPrograma'],
					'State' => $row['nEstado']
				);
			}
			return $response;
		}

		public function getAccesById($id){
			$this->query = 'SELECT a.idAcceso,a.nPermiso,a.nEstado,u.idUsuario,u.cNombre,u.cApellido,u.cNLegajo,p.idPrograma,p.cNomPrograma FROM acceso AS a
				INNER JOIN usuario AS u ON a.idUsuario = u.idUsuario
				INNER JOIN programa AS p ON a.idPrograma = p.idPrograma
				WHERE a.idAcceso = :Id';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = array();

			while($row = $result->fetch()){
				$response = array(
					'Id' => $row['idAcceso'],
					'IdUser' => $row['idUsuario'],
					'IdProgram' => $row['idPrograma'],
					'Permiso' => $row['nPermiso'],
					'UserName' => $row['cNombre'],
					'Surname' => $row['cApellido'],
					'Legajo' => $row['cNLegajo'],
					'ProgramName' => $row['cNomPrograma'],
					'State' => $row['nEstado']
				);
			}
			return $response;
		}
		
		public function getAuditoria(){
			$this->query = 'SELECT TOP 1000 u.idUsuario,u.cNombre,u.cApellido,u.cNLegajo,a.idAuditoria,a.cAcceso,a.cIp,a.dFecha FROM auditoria AS a
				INNER JOIN usuario AS u ON a.idUsuario=u.idUsuario ORDER BY a.idAuditoria DESC';
			$this->data = [];
			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFecha']);
				$this->auditoria[] = array(
					'IdAuditoria' => $row['idAuditoria'],
					'IdUser' => $row['idUsuario'],
				 	'Name' => $row['cNombre'],
				 	'Surname' => $row['cApellido'],
				 	'Legajo' => $row['cNLegajo'],
				 	'Access' => $row['cAcceso'], 
				 	'Ip' => $row['cIp'], 
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}
		}

		public function createAccess(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields');
			}

			$this->legajo = $this->cleanString($_POST['usuario']);
			$this->programa = $this->cleanString($_POST['programa']);

			if($this->haveAccess($this->legajo, $this->programa)){
				return array('Status' => 'Existing Access');
			}

			$this->permiso = $this->cleanString($_POST['permiso']);
			$this->id = $this->getUserIdByLegajo($this->legajo);

			$this->query = 'INSERT INTO Acceso (idUsuario,idPrograma,nPermiso,idAlta,dFechaAlta) VALUES (:IdUser, :IdProgram, :Permiso, :IdAlta, :Fecha)';
			$this->data = [
				':IdUser' => $this->id,
				':IdProgram' => $this->programa,
				':Permiso' => $this->permiso,
				':IdAlta' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getAccesById($this->getLastInsertedId())) : array('Status' => 'Error');
		}

		public function updateAccess(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);
			$this->programa = $this->cleanString($_POST['programa']);
			$this->permiso = $this->cleanString($_POST['permiso']);
			$this->estado = $this->cleanString($_POST['estado']);

			if($this->haveAccess($this->legajo, $this->programa)){
				return array('Status' => 'Existing Access');
			}

			$this->query = 'UPDATE acceso SET idPrograma = :IdProgram, nPermiso = :Permiso, nEstado = :State WHERE idAcceso = :IdAcc';
			$this->data = [':IdProgram' => $this->programa, ':Permiso' => $this->permiso, ':State' => $this->estado, ':IdAcc' => $this->id];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Result' => $this->getAccesById($this->id)) : array('Status' => 'Error');
		}

		public function deleteAccess(){
			$this->id = $this->cleanString($_POST['id']);

			$this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idAcceso = :IdAcc';
			$this->data = [':IdAcc' => $this->id];
			//verifico si el Usuario ya tiene Acceso a ese Programa.
			if($this->searchRecords() == 0){
				return array('Status' => 'Unknown Access');
			}

			// $this->query = 'DELETE FROM acceso WHERE idAcceso = :IdAcc';
			// $this->data = [':IdAcc' => $this->id];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		// public function changeStateAccess(){
		// 	$this->id = $this->cleanString(trim($_POST['id']));

		// 	$this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idAcceso = :IdAcc';
		// 	$this->data = [':IdAcc' => $this->id];
		// 	//verifico si el Usuario ya tiene Acceso a ese Programa.
		// 	if($this->searchRecords() != 0){
		// 		$type = $this->cleanString(trim($_POST['action']));
		// 		if($type == 'Deshabilitar'){
		// 			$this->query = 'UPDATE acceso SET nEstado = 0 WHERE idAcceso = :IdAcc';
		// 			$this->data = [':IdAcc' => $this->id];
		// 		}else if($type == 'Habilitar'){
		// 			$this->query = 'UPDATE acceso SET nEstado = 1 WHERE idAcceso = :IdAcc';
		// 			$this->data = [':IdAcc' => $this->id];
		// 		}
		// 		if($this->executeQuery()){
		// 			return array('Status' => 'Success');
		// 		}else{
		// 			return array('Status' => 'Error');
		// 		}
		// 	}else{
		// 		return array('Status' => 'Unknown Access');
		// 	}
		// }
 
		private function haveAccess($legajo, $programa){	//verifico si el Usuario ya tiene Acceso a ese Programa.
			$userId = $this->getUserIdByLegajo($legajo);

			$this->query = 'SELECT COUNT(idUsuario) FROM Acceso WHERE idUsuario = :Id AND idPrograma = :Programa';
			$this->data = [':Id' => $userId, ':Programa' => $programa];

			return ($this->searchRecords() > 0) ?  true : false;
		}

		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'insert':
            		if(isset($_POST['usuario']) && isset($_POST['programa']) && isset($_POST['permiso'])){
            			if(!$this->validateEmptyPost(array('estado', 'id')) && (trim($_POST['permiso']) == 1 || trim($_POST['permiso']) == 2)){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['id']) && isset($_POST['programa']) && isset($_POST['permiso'])){
            			if(!$this->validateEmptyPost(array('estado')) && (trim($_POST['permiso']) == 1 || trim($_POST['permiso']) == 2)){
            				$valid = true;
            			}
            		}
            	break;
            	case 'delete':
            		if(isset($_POST['id'])){
            			if(!$this->validateEmptyPost(array())){
            				$valid = true;
            			}
            		}
            	break;
            	case 'changeState':
            		if(isset($_POST['id']) && isset($_POST['action'])){
            			if(!$this->validateEmptyPost(array()) && (trim($_POST['action']) == 'Deshabilitar' || trim($_POST['action']) == 'Habilitar')){
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