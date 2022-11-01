<?php 

include_once  realpath(__DIR__ ).'/globalController.php';

class vehiculo extends globalController{
	private $Id;
	private $Patente;
	private $Model;
	private $Combustible;
	private $Propietario;
	private $Secretaria;
	private $ActualPatente;
	
	private $Session;

	function __construct(){
		parent::__construct();

		$this->Session = new session();
	}

	public function getCars(){

		$search = (isset($_POST['search'])) ? mb_strtoupper($_POST['search'], 'utf-8') : '';

		if($search != ''){
			$this->query = 'SELECT idVehiculo, cPatente, cModelo, cCombustible, cPropietario, idSecretaria, idDependencia FROM Vehiculos AS v
				WHERE cPatente LIKE :Search AND idBaja IS NULL';
			$this->data = [':Search' => '%'.$search.'%'];
		}else{
			$this->query = 'SELECT idVehiculo, cPatente, cModelo, cCombustible, cPropietario, idSecretaria, idDependencia FROM Vehiculos AS v
				WHERE idBaja IS NULL';
			$this->data = [];
		}


		
		$result = $this->executeQuery();
		$carList = array();
		while ($row = $result->fetch()){
			if($this->Admin || $row['idDependencia'] == $_SESSION['DEPENDENCIA']){
	            $carList[] = array(
	                'Id' => $row['idVehiculo'],
	                'Patente' => $row['cPatente'],
					'Model' => $row['cModelo'],
					'Fuel' => $row['cCombustible'],
	                'FuelType' => $this->getFuelFullName($row['cCombustible']),
	                'Propietario' => $row['cPropietario'],
	                'Secretaria' => $this->searchSecretary($row['idSecretaria']),
	                'Dependencia' => $this->searchDependence($row['idSecretaria'], $row['idDependencia']),
	                'Suggestion' => $row['cPatente'].' | '.$this->searchSecretary($row['idSecretaria'])
	            );
	        }
        }

        return $carList;
	}

	public function addCar(){
		if(!$this->validateFields('insert')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Patente = strtoupper($this->cleanString($_POST['patente']));
		$this->Model = strtoupper($this->cleanString($_POST['modelo']));
		$this->Combustible = strtoupper($this->cleanString($_POST['combustible']));
		$this->Propietario = strtoupper($this->cleanString($_POST['propietario']));
		$this->Secretaria = ($this->Admin) ? strtoupper($this->cleanString($_POST['secretaria'])) : $_SESSION['SECRETARIA'];
		$this->Dependencia = ($this->Admin) ? strtoupper($this->cleanString($_POST['dependencia'])) : $_SESSION['DEPENDENCIA'];
		
		if($this->existingCar('PATENTE', $this->Patente)){
			return array('Status' => 'Existing Car');
		}

		if(!$this->validFuel($this->Combustible)){
			return array('Status' => 'Invalid Fuel');	
		}

		$this->query = 'INSERT INTO Vehiculos (cPatente, cModelo, cCombustible, cPropietario, idSecretaria, idDependencia, idAlta, dFechaAlta) VALUES (:Patente, :Model, :Combustible, :Propietario, :Sec, :Dep, :User, :Fecha) ';
		$this->data = [
			':Patente' => $this->Patente,
			':Model' => $this->Model,
			':Combustible' => $this->Combustible,
			':Propietario' => $this->Propietario,
			':Sec' => $this->Secretaria,
			':Dep' => $this->Dependencia,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha
		];

		return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
	}

	public function updateCar(){
		if(!$this->validateFields('update')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Id = intval($this->cleanString($_POST['id'], 10));
		$this->Patente = $this->cleanString($_POST['patente']);
		$this->Model = $this->cleanString($_POST['modelo']);
		$this->Combustible = $this->cleanString($_POST['combustible']);
		$this->Propietario = $this->cleanString($_POST['propietario']);
		$this->Secretaria = ($this->Admin) ? strtoupper($this->cleanString($_POST['secretaria'])) : $_SESSION['SECRETARIA'];
		$this->Dependencia = ($this->Admin) ? strtoupper($this->cleanString($_POST['dependencia'])) : $_SESSION['DEPENDENCIA'];
		
		if(!$this->existingCar('ID', $this->Id)){
			return array('Status' => 'Unknonw Car');
		}

		$this->query = 'SELECT COUNT(idVehiculo) FROM Vehiculos WHERE (cPatente = :Patente AND idVehiculo != :Id) AND idBaja IS NULL';
		$this->data = [':Patente' => $this->Patente, ':Id' => $this->Id];

		if($this->searchRecords() > 0){
			return array('Status' => 'Existing Car');
		}

		if(!$this->validFuel($this->Combustible)){
			return array('Status' => 'Invalid Fuel');	
		}

		$this->ActualPatente = $this->cleanString($this->getPatente($this->Id));

		if($this->ActualPatente != $this->Patente){
			// return array('Status' => 'Invalid Update');

			$this->query = 'SELECT COUNT(idVehiculo) FROM Remitos WHERE (idVehiculo = :Id)';
			$this->data = [':Id' => $this->Id];

			if($this->searchRecords() > 0){
				return array('Status' => 'Car In Use', $this->ActualPatente, $this->Patente);
			}
		}

		$this->query = 'UPDATE Vehiculos SET cPatente = :Patente, cModelo = :Model, cCombustible = :Combustible, cPropietario = :Propietario, idSecretaria = :Sec, idDependencia = :Dep, idModificado = :User, dFechaModificado = :Fecha WHERE idVehiculo = :Id';
		$this->data = [
			':Patente' => $this->Patente,
			':Model' => $this->Model,
			':Combustible' => $this->Combustible,
			':Propietario' => $this->Propietario,
			':Sec' => $this->Secretaria,
			':Dep' => $this->Dependencia,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha,
			':Id' => $this->Id,
		];

		return ($this->executeQuery()) ? array('Status' => 'Success', 'Patente' => $this->Patente,'Model' => $this->Model,'FuelType' => $this->Combustible,'Propietario' => $this->Propietario,'Secretaria' => $this->searchSecretary($this->Secretaria), 'Dependencia' => $this->searchDependence($this->Secretaria, $this->Dependencia)) : array('Status' => 'Error');
	}

	public function deleteCar(){
		if(!$this->validateFields('delete')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Id = intval($this->cleanString($_POST['id'], 10));

		if(!$this->existingCar('ID', $this->Id)){
			return array('Status' => 'Unknonw Car');
		}

		// $this->query = 'SELECT COUNT(idVehiculo) FROM Remitos WHERE (idVehiculo = :Id)';
		// $this->data = [':Id' => $this->Id];

		// if($this->searchRecords() > 0){
		// 	return array('Status' => 'Car In Use');
		// }

		$this->query = 'UPDATE Vehiculos SET idBaja = :User, dFechaBaja = :Fecha WHERE idVehiculo = :Id';
		$this->data = [':User' => $this->Session->getUserId(), ':Fecha' => $this->fecha, ':Id' => $this->Id];

		return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');

	}

	private function validFuel($fuelType){
		$valid = false;

		if($fuelType == 'NAFTA' || $fuelType == 'NAFTAPP' || $fuelType == 'GAS' || $fuelType == 'GASPE' || $fuelType == 'GASOIL'){
			$valid = true;	
		}

		return $valid;
	}

	private function existingCar($type, $search){
		switch ($type) {
			case 'ID':
				$this->query = 'SELECT COUNT(idVehiculo) FROM Vehiculos WHERE idVehiculo = :Search';
			break;
			case 'PATENTE':
				$this->query = 'SELECT COUNT(idVehiculo) FROM Vehiculos WHERE cPatente = :Search';
			break;
			default:
				$this->query = 'SELECT COUNT(idVehiculo) FROM Vehiculos WHERE idVehiculo = :Search';
			break;
		}

		$this->data = [':Search' => $search];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function getPatente($id){
		$this->query = 'SELECT cPatente FROM Vehiculos WHERE idVehiculo = :Id';
		$this->data = [':Id' => $id];

		return $this->executeQuery()->fetchColumn(0);
	}

	private function getFuelFullName($fuel){
		$result = '';

		switch ($fuel) {
			case 'NAFTAPP': $result = 'NAFTA - V POWER / PREMIUM'; break;
			case 'GASPE': $result = 'GAS - OIL - V POWER / EURO DIESEL'; break;
			
			default: $result = $fuel; break;
		}

		return $result;
	}

	private function validateFields($call){
		$valid = false;
		switch ($call) {
			case 'insert':
				if(isset($_POST['patente'],$_POST['propietario'],$_POST['modelo'],$_POST['combustible'],$_POST['secretaria'])){
					if(!$this->validateEmptyPost(array('id','modelo'))){
						$valid = true;
					}
				}
			break;
			case 'update':
				if(isset($_POST['id']) && isset($_POST['patente'],$_POST['propietario'],$_POST['modelo'],$_POST['combustible'],$_POST['secretaria'])){
					if(!$this->validateEmptyPost(array('modelo')) && intval(trim($_POST['id'])) != 0){
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