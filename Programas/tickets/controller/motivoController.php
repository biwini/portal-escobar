<?php 
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
    }
    include_once  realpath(__DIR__ ).'/globalController.php';

	class motivo extends globalController{
		private $Id;
		private $Name;

		private $IdSubMotivo;
		private $NameSubMotivo;
		private $EstimatedTime;

		public $listMotivo = array();
		public $listSubMotivo = array();


		function __construct(){
			$this->Id = 0;
			$this->Name = NULL;
			$this->listMotivo = array();

			$this->IdSubMotivo = 0;
			$this->NameSubMotivo = NULL;
			$this->EstimatedTime = 0;

			parent::__construct();
		}

		public function getMotivo(){
			$this->query = 'SELECT idMotivo,cMotivo FROM Motivo ORDER BY idMotivo ASC';
			$this->data = [];
			$result = $this->executeQuery();

			return ($result) ? $this->setMotivo($result) : array('Status' => 'Error');
		}

		private function setMotivo($data){
			while($row = $data->fetch()){
				$this->listMotivo[] =array(
					'Status' => 'Success',
					'Id' => $row['idMotivo'],
					'Motivo' => $row['cMotivo'],
					'SubMotivo' => $this->getSubMotivo($row['idMotivo'])
				);
			}
			return $this->listMotivo;
		}
		public function insertMotivo(){
			$this->Name= $this->cleanString($_POST['motivo']);

			$this->query = 'SELECT COUNT(idMotivo) FROM Motivo WHERE cMotivo = :Motivo';
        	$this->data = [':Motivo' => $this->Name];

			if($this->searchRecords() != 0){
				return array('Status' => 'Existing Motivo');
			}

			$this->query = 'INSERT INTO Motivo (cMotivo) VALUES (UPPER(:Motivo))';
			$this->data = [':Motivo' => $this->Name];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		public function insertSubMotivo(){
			$this->Id = $this->cleanString($_POST['slct_motivo']);
			
			if(!$this->existingMotivo($this->Id)){
				return array('Status' => 'Unknown Motivo');
			}

			$this->NameSubMotivo = $this->cleanString($_POST['sub_motivo']);
			$this->EstimatedTime = $this->cleanString($_POST['tiempo_estimado']);

			$this->query = 'SELECT COUNT(idSubMotivo) FROM SubMotivo WHERE cSubMotivo = :SubMotivo AND idMotivo = :Motivo';
        	$this->data = [':SubMotivo' => $this->NameSubMotivo,':Motivo' => $this->Id];

        	if($this->searchRecords() != 0){
				return array('Status' => 'Existing SubMotivo');
			}

			$this->query = 'INSERT INTO SubMotivo (idMotivo,cSubMotivo,nTiempoEstimado) VALUES (:Id,UPPER(:SubMotivo), :EstimatedTime)';
			$this->data = [':Id' => $this->Id,':SubMotivo' => $this->NameSubMotivo, ':EstimatedTime' => $this->EstimatedTime];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}
		public function getSubMotivo($id){
			$this->listSubMotivo = array();
			$this->query = 'SELECT idSubMotivo,cSubMotivo,nTiempoEstimado FROM SubMotivo WHERE idMotivo = :Id ORDER BY cSubMotivo ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			while($row = $result->fetch()){
				$this->listSubMotivo[] =array(
					'IdMotivo' => $id,
					'Id' => $row['idSubMotivo'],
					'SubMotivo' => $row['cSubMotivo'],
					'EstimatedTime' => $row['nTiempoEstimado']
				);
			}
			return $this->listSubMotivo;
		}
		private function validateUpdate($arraySubMotivos){
			$valid = true;
			foreach ($arraySubMotivos as $key => $value) {
				if(!$this->existingSubMotivo('Id', $value['Id'])){
					$valid = false;
					break;
				}
			}
			return $valid;
		}
		public function updateMotivo(){
			// $_POST['submotivo'] = json_decode($_POST['submotivo']);
			$this->Id = $_POST['id'];
			$this->Name = $_POST['mod_motivo'];

			if(!$this->existingMotivo($this->Id)){
				return array('Status' => 'Unknown Motivo');
			}

			$this->query = 'UPDATE Motivo SET cMotivo = :Motivo WHERE idMotivo = :Id';
			$this->data = [':Motivo' => $this->Name, ':Id' => $this->Id];

			$response = ($this->executeQuery()) ? true : false;

			if(isset($_POST['submotivo']) && $response){
				if(!$this->validateUpdate($_POST['submotivo'])){
					return array('Status' => 'Unknown Sub Motivo');
				}

				$response = array('Status' => 'Error');

				foreach ($_POST['submotivo'] as $key => $value) {
					$this->query = 'UPDATE SubMotivo SET cSubMotivo = :SubMotivo, nTiempoEstimado = :Tiempo WHERE idSubMotivo = :Id';
					$this->data = [':SubMotivo' => $this->cleanString($value['Name']), ':Tiempo' => $this->cleanString($value['Time']), ':Id' => $this->cleanString($value['Id'])];

					$response = ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
				}
			}else{
				$response = ($response) ? array('Status' => 'Success') : array('Status' => 'Error');
			}

			return $response;
		}
		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['motivo']) || (isset($_POST['slct_motivo']) && isset($_POST['sub_motivo']))){
						if(!$this->validateEmptyPost(array())){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['id']) && isset($_POST['mod_motivo'])){
						if(!$this->validateEmptyPost(array('submotivo')) && intval(trim($_POST['id'])) != 0){
							$valid = true;
						}
					}
				break;
			}
			return $valid;
		}
	}
?>