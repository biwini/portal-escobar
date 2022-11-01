<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class area extends globalController{
		public $listArea = array();
		public $id,$area,$ubicacion;

		public function insertArea(){
			$this->area = $this->cleanString(trim($_POST['area']));
			$this->ubicacion = (!empty(trim($_POST['ubicacion']))) ? $this->cleanString(trim($_POST['ubicacion'])) : NULL;

			$this->query = "SELECT COUNT(idArea) FROM Area WHERE UPPER(cNomArea) = UPPER(:Area)";
			$this->data = [':Area' => $this->area];
			if($this->searchRecords() == 0){
				$this->query = 'INSERT INTO Area (cNomArea, cUbicacion, dFechaAlta) VALUES (UPPER(:Area), UPPER(:Ubicacion), :Fecha)';
				$this->data = [':Area' =>  $this->area,':Ubicacion' => $this->ubicacion, ':Fecha' => $this->fecha];
				if($this->executeQuery()){
					return array('Status' => 'Success');
				}else{
					return array('Status' => 'Error');
				}
			}else{
				return array('Status' => 'Existing Area');
			}
		}
		public function getArea(){
			$this->query = 'SELECT idArea,cNomArea,cUbicacion,nEstado,dFechaAlta FROM Area ORDER BY idArea ASC';
			$this->data = [];
			$result = $this->executeQuery();

			while ($row = $result->fetch()){
				$fecha = new DateTime($row['dFechaAlta']);
				$this->listArea[] = array(
					'IdArea' => $row['idArea'],
				 	'Name' => $row['cNomArea'], 
				 	'Ubicacion' => $row['cUbicacion'],
				 	'State' => $row['nEstado'],
				 	'Date' => $fecha->format('d-m-Y H:i:s')
				);
			}
		}
		public function updateArea(){
			$this->id = $this->cleanString(trim($_POST['id']));
			$this->area = $this->cleanString(trim($_POST['area']));
			$this->ubicacion = (!empty(trim($_POST['ubicacion']))) ? $this->cleanString(trim($_POST['ubicacion'])) : NULL;

			$this->query = "UPDATE Area SET cNomArea = UPPER(:Area), cUbicacion = UPPER(:Ubicacion) WHERE idArea = :Id";
			$this->data = [':Area' => $this->area, ':Ubicacion' => $this->ubicacion, ':Id' => $this->id];

			if($this->executeQuery()){
				return array('Status' => 'Success');
			}else{
				return array('Status' => 'Error');
			}
		}
		public function changeAreaState(){
			$this->id = $this->cleanString(trim($_POST['id']));
			if(!empty($this->id) && $this->id > 0){
				$this->query = 'SELECT COUNT(idArea) FROM Area WHERE idArea = :Id';
				$this->data = [':Id' => $this->id];

				if($this->searchRecords() != 0){
					$type = $this->cleanString($_POST['action']);
					if($type == 'Deshabilitar'){
						$this->query = 'UPDATE Area SET nEstado = 0, dFechaBaja = :Fecha WHERE idArea = :Id';
						$this->data = [':Fecha' => $this->fecha, ':Id' => $this->id];
					}else if($type == 'Habilitar'){
						$this->query = 'UPDATE Area SET nEstado = 1, dFechaBaja = :Fecha WHERE idArea = :Id';
						$this->data = [':Fecha' => NULL, ':Id' => $this->id];
					}

					if($this->executeQuery()){
						return array('Status' => 'Success');
					}else{
						return array('Status' => 'Error');
					}
				}else{
					return array('Status' => 'Unknown Area');
				}
			}else{
				return array('Status' => 'Invalid call');
			}
		}
		public function validateFields($call){
			$valid = false;
            switch ($call) {
            	case 'changeState':
            		if(isset($_POST['id']) && isset($_POST['action'])){
            			if(!$this->validateEmptyPost(array()) && (trim($_POST['action']) == 'Deshabilitar' || trim($_POST['action']) == 'Habilitar')){
            				$valid = true;
            			}
            		}
            	break;
            	case 'update':
            		if(isset($_POST['id']) && isset($_POST['area']) && isset($_POST['ubicacion'])){
            			if(!$this->validateEmptyPost(array('ubicacion'))){
            				$valid = true;
            			}
            		}
            	break;
            	case 'insert':
            		if(isset($_POST['area']) && isset($_POST['ubicacion'])){
            			if(!$this->validateEmptyPost(array('ubicacion'))){
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