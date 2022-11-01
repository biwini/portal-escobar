<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class compartida extends globalController{
		private $Id;
		private $Name;
		private $Dependence;
		public $ListCompartidas;

		function __construct(){
			$this->Id = 0;
			$this->Name = '';
			$this->ListCompartidas = array();
			
			parent::__construct();
		}

		public function getCompartida(){
			$this->query = 'SELECT c.idCompartida,c.idDependencia,c.cCompartida,d.cNomDependencia,s.idSecretaria,s.cNomSecretaria FROM Compartida AS c
				INNER JOIN Dependencia AS d ON c.idDependencia = d.idDependencia
				INNER JOIN Secretaria AS s ON d.idSecretaria = s.idSecretaria ORDER BY idCompartida ASC';
			$this->data = [];
			$result = $this->executeQuery();

			while($row = $result->fetch()){
				$this->ListCompartidas[] = array(
					'Id' => $row['idCompartida'],
					'IdSecretary' => $row['idSecretaria'],
					'Secretary' => $row['cNomSecretaria'],
					'IdDependence' => $row['idDependencia'],
					'Dependence' => $row['cNomDependencia'],
					'Name' => $row['cCompartida']
				);
			}
			//DESCOMENTAR ESTA LINEA SI SE DESEA QUE DEVUELVA EL ARRAY CON LOS RESULTADOS
			return $this->ListCompartidas;
		}

		private function findEcual($id,$val){
			$this->query = 'SELECT COUNT(idCompartida) FROM Compartida WHERE cCompartida = UPPER(:Compartida) AND idCompartida != :Id';
			$this->data = [':Compartida' => $val, ':Id' => $id];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingCompartida($type, $value){
			switch ($type) {
				case 'Name':
					$this->query = 'SELECT COUNT(cCompartida) FROM Compartida WHERE cCompartida = UPPER(:Val)';
					break;
				case 'Id':
					$this->query = 'SELECT COUNT(idCompartida) FROM Compartida WHERE idCompartida = UPPER(:Val)';
					break;
				default:
					$this->query = 'SELECT COUNT(idCompartida) FROM Compartida WHERE idCompartida = UPPER(:Val)';
					break;
			}
			$this->data = [':Val' => $value];

			return ($this->searchRecords() > 0) ? true : false;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['compartida']) && isset($_POST['compartidaDependencia'])){
						if(!$this->validateEmptyPost(array())){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['new_compartidaDependencia']) && isset($_POST['new_compartida'])){
						if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
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