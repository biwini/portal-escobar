<?php 

include_once  realpath(__DIR__ ).'/globalController.php';

class proveedor extends globalController{
	private $Id;
	private $Name;
	private $Email;

	private $Session;
	
	function __construct(){
		parent::__construct();

		$this->Session = new session();

	}

	public function getProviders(){

		$search = (isset($_POST['search'])) ? mb_strtoupper($_POST['search'], 'utf-8') : '';

		if($search != ''){
			$this->query = 'SELECT idProveedor, cProveedor, cEmail FROM Proveedores WHERE cProveedor LIKE :Search AND idBaja IS NULL';
			$this->data = [':Search' => '%'.$search.'%'];
		}else{
			$this->query = 'SELECT idProveedor, cProveedor, cEmail FROM Proveedores WHERE idBaja IS NULL';
			$this->data = [];
		}
		

		$result = $this->executeQuery();
		$providerList = array();
		while ($row = $result->fetch()){
            $providerList[] = array(
                'Id' => $row['idProveedor'],
                'Proveedor' => $row['cProveedor'],
                'Email' => $row['cEmail'],
                'Suggestion' => $row['cProveedor'].' | '.$row['cEmail']
            );
        }

        return $providerList;
	}

	public function addProvider(){
		if(!$this->validateFields('insert')){
			return array('Status' => 'Incomplete Fields');
		}

		if(!$this->Admin){
			return array('Status' => 'Invalid Permissions');
		}

		$this->Name = $this->cleanString($_POST['proveedor']);
		$this->Email = $_POST['email'];
		
		if($this->existingProvider('NAME', $this->Name)){
			return array('Status' => 'Existing Provider');
		}

		if($this->existingProvider('EMAIL', $this->Email)){
			return array('Status' => 'Email In Use');
		}

		if(!$this->is_valid_email($this->Email)){
			return array('Status' => 'Invalid Email');
		}

		$this->query = 'INSERT INTO Proveedores (cProveedor, cEmail, idAlta, dFechaAlta) VALUES (:Name, :Email, :User, :Fecha) ';
		$this->data = [
			':Name' => $this->Name,
			':Email' => $this->Email,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha
		];

		return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
	}

	public function updateProvider(){
		if(!$this->validateFields('update')){
			return array('Status' => 'Incomplete Fields');
		}

		if(!$this->Admin){
			return array('Status' => 'Invalid Permissions');
		}

		$this->Id = intval($this->cleanString($_POST['id'], 10));
		$this->Name = $this->cleanString($_POST['proveedor']);
		$this->Email = $_POST['email'];

		if(!$this->is_valid_email($this->Email)){
			return array('Status' => 'Invalid Email');
		}
		
		if(!$this->existingProvider('ID', $this->Id)){
			return array('Status' => 'Unknonw Provider');
		}

		$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE (cProveedor = :Name OR cEmail = :Email) AND idProveedor != :Id AND idBaja IS NULL';
		$this->data = [':Name' => $this->Name, ':Email' => $this->Email, ':Id' => $this->Id];

		if($this->searchRecords() > 0){
			return array('Status' => 'Existing Provider Or Email');
		}

		$this->query = 'UPDATE Proveedores SET cProveedor = :Name, cEmail = :Email, idModificado = :User, dFechaModificado = :Fecha';
		$this->data = [
			':Name' => $this->Name,
			':Email' => $this->Email,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha
		];

		return ($this->executeQuery()) ? array('Status' => 'Success', 'Proveedor' => $this->Name, 'Email' => $this->Email) : array('Status' => 'Error');
	}

	public function deleteProvider(){
		if(!$this->validateFields('delete')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Id = intval($this->cleanString($_POST['id'], 10));

		if(!$this->existingProvider('ID', $this->Id)){
			return array('Status' => 'Unknonw Provider');
		}

		$this->query = 'UPDATE Proveedores SET idBaja = :User, dFechaBaja = :Fecha';
		$this->data = [':User' => $this->Session->getUserId(), ':Fecha' => $this->fecha];

		return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');

	}

	private function existingProvider($type, $search){
		switch ($type) {
			case 'ID':
				$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE idProveedor = :Search AND idBaja IS NULL';
			break;
			case 'NAME':
				$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE cProveedor = :Search AND idBaja IS NULL';
			break;
			case 'EMAIL':
				$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE cEmail = :Search AND idBaja IS NULL';
			break;
			default:
				$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE idProveedor = :Search AND idBaja IS NULL';
			break;
		}

		$this->data = [':Search' => $search];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function validateFields($call){
		$valid = false;
		switch ($call) {
			case 'insert':
				if(isset($_POST['proveedor'], $_POST['email'])){
					if(!$this->validateEmptyPost(array('id'))){
						$valid = true;
					}
				}
			break;
			case 'update':
				if(isset($_POST['id']) && isset($_POST['proveedor'], $_POST['email'])){
					if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
						$valid = true;
					}
				}
			break;
			case 'delete':
				if(isset($_POST['id'])){
					if(!$this->validateEmptyPost(array()) && intval(trim($_POST['id'])) != 0){
						$valid = true;
					}
				}
			break;
		}
		return $valid;
	}
}

?>