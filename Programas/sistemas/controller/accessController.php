<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class access extends globalController{
		public $access = array();
		public $auditoria = array();
		public $idUser,$idAcceso,$idProgram,$permiso;

		public function getAccess(){
			$this->query = 'SELECT a.idAcceso,a.nPermiso,a.nEstado,u.idUsuario,u.cNombre,u.cApellido,u.nDni,p.idPrograma,p.cNomPrograma FROM acceso AS a
								INNER JOIN usuario AS u ON a.idUsuario = u.idUsuario
								INNER JOIN programa AS p ON a.idPrograma = p.idPrograma';
			$this->data = [];
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->access[] =array(
					'IdAccess' => $row['idAcceso'],
					'IdUser' => $row['idUsuario'],
					'IdProgram' => $row['idPrograma'],
					'Permiso' => $row['nPermiso'],
					'UserName' => $row['cNombre'],
					'LastName' => $row['cApellido'],
					'UserDni' => $row['nDni'],
					'ProgramName' => $row['cNomPrograma'],
					'State' => $row['nEstado']
				);
			}
			//return $this->admin;
		}
		public function getAuditoria(){
			$this->query = 'SELECT TOP 1000 u.idUsuario,u.cNombre,u.cApellido,u.nDni,a.idAuditoria,a.cAcceso,a.cIp,a.dFecha FROM auditoria AS a
				INNER JOIN usuario AS u ON a.idUsuario=u.idUsuario ORDER BY a.idAuditoria DESC';
			$this->data = [];
			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFecha']);
				$this->auditoria[] = array(
					'IdAuditoria' => $row['idAuditoria'],
					'IdUser' => $row['idUsuario'],
				 	'Name' => $row['cNombre'],
				 	'LastName' => $row['cApellido'],
				 	'Dni' => $row['nDni'], 
				 	'Access' => $row['cAcceso'], 
				 	'Ip' => $row['cIp'], 
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}
		}

		public function createAccess(){
			$this->idUser = $this->cleanString(trim($_POST['user_access']));
			$this->idProgram = $this->cleanString(trim($_POST['program_access']));

			$this->query = 'SELECT COUNT(idUsuario) FROM Acceso WHERE idUsuario = :IdUser AND idPrograma = :IdProgram';
			$this->data = [':IdUser' => $this->idUser, ':IdProgram' => $this->idProgram];
			//verifico si el Usuario ya tiene Acceso a ese Programa.
			if($this->searchRecords() == 0){
				$this->permiso = $this->cleanString(trim($_POST['permissions_access']));
				$this->query = 'INSERT INTO acceso (idUsuario,idPrograma,nPermiso,idAlta,dFechaAlta) VALUES (:IdUser, :IdProgram, :Permiso, :IdAlta, :Fecha)';
				$this->data = [
					':IdUser' => $this->idUser,
					':IdProgram' => $this->idProgram,
					':Permiso' => $this->permiso,
					':IdAlta' => $_SESSION['ID_USER'],
					':Fecha' => $this->fecha
				];
				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Existing Access');
			}
		}
		public function updateAccess(){
			$this->idAcceso = $this->cleanString(trim($_POST['id']));
			$this->idProgram = $this->cleanString(trim($_POST['program_access']));

			$this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idAcceso = :IdAcc';
			$this->data = [':IdAcc' => $this->idAcceso];
			//verifico si el Usuario ya tiene Acceso a ese Programa.
			if($this->searchRecords() != 0){
				$this->permiso = $this->cleanString(trim($_POST['permissions_access']));
				$this->query = 'UPDATE acceso SET idPrograma = :IdProgram, nPermiso = :Permiso WHERE idAcceso = :IdAcc';
				$this->data = [':IdProgram' => $this->idProgram, ':Permiso' => $this->permiso, ':IdAcc' => $this->idAcceso];
				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Unknown Access');
			}
		}
		public function deleteAccess(){
			$this->idAcceso = $this->cleanString(trim($_POST['id']));

			$this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idAcceso = :IdAcc';
			$this->data = [':IdAcc' => $this->idAcceso];
			//verifico si el Usuario ya tiene Acceso a ese Programa.
			if($this->searchRecords() != 0){
				$this->query = 'DELETE FROM acceso WHERE idAcceso = :IdAcc';
				$this->data = [':IdAcc' => $this->idAcceso];
				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Unknown Access');
			}
		}
		public function changeStateAccess(){
			$this->idAcceso = $this->cleanString(trim($_POST['id']));

			$this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idAcceso = :IdAcc';
			$this->data = [':IdAcc' => $this->idAcceso];
			//verifico si el Usuario ya tiene Acceso a ese Programa.
			if($this->searchRecords() != 0){
				$type = $this->cleanString(trim($_POST['action']));
				if($type == 'Deshabilitar'){
					$this->query = 'UPDATE acceso SET nEstado = 0 WHERE idAcceso = :IdAcc';
					$this->data = [':IdAcc' => $this->idAcceso];
				}else if($type == 'Habilitar'){
					$this->query = 'UPDATE acceso SET nEstado = 1 WHERE idAcceso = :IdAcc';
					$this->data = [':IdAcc' => $this->idAcceso];
				}
				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Unknown Access');
			}
		}
		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'insert':
            		if(isset($_POST['user_access']) && isset($_POST['program_access']) && isset($_POST['permissions_access'])){
            			if(!$this->validateEmptyPost(array()) && (trim($_POST['permissions_access']) == 1 || trim($_POST['permissions_access']) == 2)){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['id']) && isset($_POST['program_access']) && isset($_POST['permissions_access'])){
            			if(!$this->validateEmptyPost(array()) && (trim($_POST['permissions_access']) == 1 || trim($_POST['permissions_access']) == 2)){
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