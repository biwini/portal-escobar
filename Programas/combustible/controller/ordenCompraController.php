<?php 

include_once  realpath(__DIR__ ).'/globalController.php';

class ordencompra extends globalController{
	private $Id;
	private $Oc, $Secretary, $Dependence, $Fuel, $ValidityDate, $ExpirationDate, $Cars;

	private $Session;
	
	function __construct(){
		parent::__construct();

		$this->Session = new session();
	}

	public function getOrders(){

		$search = (isset($_POST['search'])) ? mb_strtoupper($_POST['search'], 'utf-8') : '';

		if($search != ''){
			$this->query = 'SELECT idOrdenCompra, nOrdenCompra, idSecretaria, idDependencia, nCombustible, nRestante, dFechaValidez, dFechaVencimiento, idAlta, dFechaAlta FROM OrdenCompra AS o
				WHERE nOrdenCompra LIKE :Search AND idBaja IS NULL';
			$this->data = [':Search' => '%'.$search.'%'];
		}else{
			$this->query = 'SELECT idOrdenCompra, nOrdenCompra, idSecretaria, idDependencia, nCombustible, nRestante, dFechaValidez, dFechaVencimiento, idAlta, dFechaAlta FROM OrdenCompra AS o
				WHERE idBaja IS NULL';
			$this->data = [];
		}

		$result = $this->executeQuery();
		$OrderList = array();
		while ($row = $result->fetch()){
			if($this->Admin || $row['idDependencia'] == $_SESSION['DEPENDENCIA']){
	            $OrderList[] = array(
	                'Id' => $row['idOrdenCompra'],
	                'Oc' => $row['nOrdenCompra'],
	                'Secretary' => $this->searchSecretary($row['idSecretaria']),
	                'Dependence' => $this->searchDependence($row['idSecretaria'], $row['idDependencia']),
	                'Fuel' => $row['nCombustible'],
					'RemainingFuel' => $row['nRestante'],
					'Cars' => $this->getCarsInOrder($row['idOrdenCompra']),
	                'ValidityDate' => $row['dFechaValidez'],
	                'State' => ( ($row['dFechaValidez'] >= $this->fecha || $this->fecha >= $row['dFechaValidez']) && $this->fecha <= $row['dFechaVencimiento']) ? 'CURRENT' : 'EXPIRED',
	                'ExpirationDate' => $row['dFechaVencimiento'],
	                'User' => $this->getUserInfo($row['idAlta']),
	                'DateCreation' => $row['dFechaAlta'],
	                'Suggestion' => $row['nOrdenCompra'].' | '. $this->searchSecretary($row['idSecretaria'])
	            );
	        }
        }

        return $OrderList;
	}

	// public function getOrder($id = 0){
	// 	$id = (int) $id;

	// 	if($id == 0){
	// 		return array();
	// 	}

	// 	$this->query = 'SELECT idOrdenCompra, nOrdenCompra, idSecretaria, dFechaValidez, dFechaVencimiento, idAlta FROM OrdenCompra WHERE idBaja IS NULL';
	// 	$this->data = [':Id' => $id];
		

	// 	$result = $this->executeQuery();
	// 	$OrderList = array();
	// 	while ($row = $result->fetch()){
 //            $OrderList[] = array(
 //                'Id' => $row['idOrdenCompra'],
 //                'Oc' => $row['nOrdenCompra'],
 //                'Secretary' => $row['idSecretaria'],
 //                'ValidityDate' => $row['dFechaValidez'],
 //                'ExpirationDate' => $row['dFechaVencimiento'],
 //                'User' => $this->getUserInfo($row['idAlta']),
 //            );
 //        }

 //        return $OrderList;
	// }

	public function getOrdersAvailableForUse(){
		$this->query = 'SELECT o.idOrdenCompra, o.nOrdenCompra, o.idSecretaria, o.idDependencia, o.nRestante, o.dFechaValidez, o.dFechaVencimiento, o.idAlta FROM OrdenCompra AS o
				WHERE :StartFecha >= dFechaValidez AND :EndDate <= dFechaVencimiento';
		$this->data = [':StartFecha' => $this->fecha, ':EndDate' => $this->fecha];

		$result = $this->executeQuery();
		$OrderList = array();
		while ($row = $result->fetch()){
			if($this->Admin || $row['idDependencia'] == $_SESSION['DEPENDENCIA']){
	            $OrderList[] = array(
	                'Id' => $row['idOrdenCompra'],
	                'Oc' => $row['nOrdenCompra'],
	                'Secretary' => $this->searchSecretary($row['idSecretaria']),
					'Dependence' => $this->searchDependence($row['idSecretaria'], $row['idDependencia']),
					'Cars' => $this->getCarsInOrder($row['idOrdenCompra']),
	                'RemainingFuel' => $row['nRestante']
	            );
	        }
        }

        return $OrderList;
	}

	public function addOrder(){
		if(!$this->validateFields('insert')){
			return array('Status' => 'Incomplete Fields');
		}

		if(!$this->Admin){
			return array('Status' => 'No Access');
		}

		// $this->Oc = intval($this->getLastOrderNumer(), 10);
		$this->Oc = $this->cleanString($_POST['oc']);
		$this->Fuel = intval($this->cleanString($_POST['combustible']), 10);
		$this->ValidityDate = $this->cleanString($_POST['validez']);
		$this->ExpirationDate = $this->cleanString($_POST['vencimiento']);

		$this->Secretary = ($this->Admin) ? strtoupper($this->cleanString($_POST['secretaria'])) : $_SESSION['SECRETARIA'];
		$this->Dependence = ($this->Admin) ? intval($this->cleanString($_POST['dependencia']), 10) : $_SESSION['DEPENDENCIA'];
		
		if($this->existingOrder('OC', $this->Oc)){
			return array('Status' => 'Existing Order');
		}

		if(empty($this->Fuel)){
			return array('Status' => 'Invalid Fuel');
		}

		if($this->Dependence == 0){
			return array('Status' => 'Unknown Dependence', 'ASd' => $this->Dependence, '123' => $_POST);
		}

		if($this->Dependence == 'Existing Dependence'){
			return array('Status' => 'Existing Dependence');
		}

		$this->Cars = $this->getSelectedCars();

		if(count($this->Cars) == 0){
			return array('Status' => 'Invalid Cars');
		}

		if(!$this->validFuelToSpent()){
			return array('Status' => 'Invalid Fuel');
		}

		// return array('status' => 'Success', 'asd' => $this->Cars);

		$this->query = 'INSERT INTO OrdenCompra (nOrdenCompra, idSecretaria, idDependencia, nCombustible, nRestante, dFechaValidez, dFechaVencimiento, idAlta, dFechaAlta) VALUES (:Oc, :Secretary, :Dependence, :Fuel, :Remaining, :ValidityDate, :ExpirationDate, :User, :Fecha) ';
		$this->data = [
			':Oc' => $this->Oc,
			':Secretary' => $this->Secretary,
			':Dependence' => $this->Dependence,
			':Fuel' => $this->Fuel,
			':Remaining' => $this->Fuel,
			':ValidityDate' => $this->ValidityDate,
			':ExpirationDate' => $this->ExpirationDate,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha
		];

		return ($this->executeQuery()) ? array('Status' => 'Success', 'CarSaved' => $this->addCarsToOrder($this->getLastInsertedId())) : array('Status' => 'Error');
	}

	public function updateOrder(){
		if(!$this->validateFields('update')){
			return array('Status' => 'Incomplete Fields');
		}

		$this->Id = intval($this->cleanString($_POST['id'], 10));
		$this->Oc = intval($this->getOrderOcById($this->Id), 10);
		$this->Fuel = intval($this->cleanString($_POST['combustible']), 10);
		$this->ValidityDate = $this->cleanString($_POST['validez']);
		$this->ExpirationDate = $this->cleanString($_POST['vencimiento']);

		$this->Secretary = ($this->Admin) ? strtoupper($this->cleanString($_POST['secretaria'])) : $_SESSION['SECRETARIA'];
		$this->Dependence = ($this->Admin) ? strtoupper($this->cleanString($_POST['dependencia'])) : $_SESSION['DEPENDENCIA'];
		
		if(!$this->existingOrder('ID', $this->Id)){
			return array('Status' => 'Unknonw Order');
		}

		$BeginDate = $this->getOrderValidityDate($this->Id);

		if($this->fecha >= $BeginDate || $this->ValidityDate < $this->fecha){
			return array('Status' => 'Order In Use', $this->ValidityDate, $this->getOrderValidityDate($this->Id));
		}

		$user = $this->Session->getUserId();
		$this->Cars = $this->getSelectedCars();

		if(count($this->Cars) == 0){
			return array('Status' => 'Invalid Cars');
		}

		if(!$this->validFuelToSpent()){
			return array('Status' => 'Invalid Fuel');
		}

		$this->query = 'UPDATE OrdenCompra SET idSecretaria = :Secretary, idDependencia = :Dependence, nCombustible = :Fuel, nRestante = :Remaining, dFechaValidez = :ValidityDate, dFechaVencimiento = :ExpirationDate, idModificado = :User, dFechaModificado = :Fecha WHERE idOrdenCompra = :Id';
		$this->data = [
			':Secretary' => $this->Secretary,
			':Dependence' => $this->Dependence,
			':Fuel' => $this->Fuel,
			':Remaining' => $this->Fuel,
			':ValidityDate' => $this->ValidityDate,
			':ExpirationDate' => $this->ExpirationDate,
			':User' => $user,
			':Fecha' => $this->fecha,
			':Id' => $this->Id
		];

		return ($this->executeQuery()) ? 
			array('Status' => 'Success',
				'CarSaved' => $this->addCarsToOrder($this->Id),
				'Oc' => $this->Oc,
				'Secretary' => $this->searchSecretary($this->Secretary),
				'Dependence' => $this->searchDependence($this->Secretary, $this->Dependence),
				'Fuel' => $this->Fuel,
				'RemainingFuel' => $this->Fuel,
				'ValidityDate' => $this->ValidityDate,
				'ExpirationDate' => $this->ExpirationDate,
				'User' => $user
			) 
			: array('Status' => 'Error');
	}

	// public function deleteOrder(){
	// 	if(!$this->validateFields('delete')){
	// 		return array('Status' => 'Incomplete Fields');
	// 	}

	// 	$this->Id = intval($this->cleanString($_POST['id'], 10));

	// 	if(!$this->existingOrder('ID', $this->Id)){
	// 		return array('Status' => 'Unknonw Order');
	// 	}

	// 	$this->Oc = intval($this->getOrderOcById($this->Id), 10);

	// 	if($this->orderInUse($this->Oc)){
	// 		return array('Status' => 'Order In Use');
	// 	}

	// 	$this->query = 'DELETE FROM OrdenCompra WHERE idOrdenCompra = :Id';
	// 	$this->data = [':Id' = $this->Id];

	// 	return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
	// }

	private function getSelectedCars(){
		$cars = array();

		if(!empty($_POST['vehiculo'])){
			foreach ($_POST['vehiculo'] as $key => $value) {
				if(!isset($_POST['lts_'.$value])){
					$cars = array();
					break;
				}

				if($_POST['lts_'.$value] < 1){
					$cars = array();
					break;
				}

				$cars[] = array('Id' => intval($value), 'Lts' => intval($_POST['lts_'.$value]));
			}
		}

		return $cars;
	}

	// private function addCarsToOrder($orderId){
	// 	foreach ($this->Cars as $key => $car) {
	// 		$this->query = 'INSERT INTO Vehiculo_x_Orden (idOrdenCompra, idVehiculo, nLtsDisponibles, nLtsRestantes) VALUES (:Order, :Car, :Lts, :LtsRemaining)';
	// 		$this->data = [
	// 			':Order' => $orderId,
	// 			':Car' => $car['Id'],
	// 			':Lts' => $car['Lts'],
	// 			':LtsRemaining' => $car['Lts'],
	// 		];

	// 		$this->executeQuery();
	// 	}

	// 	return true;
	// }

	private function addCarsToOrder($orderId){
		$carsInOrder = $this->getCarsInOrder($orderId);
		if(count($carsInOrder) > 0){
			foreach ($carsInOrder as $key => $value) {
				if(!$this->in_array_r($value['Id'], $this->Cars)){
					$remove = $this->removeCarFromOrder($orderId, $value['Id']);
				}
			}
		}

		foreach ($this->Cars as $key => $car) {

			if(!$this->existingCarInOrder($orderId, $car['Id'])){
				$this->query = 'INSERT INTO Vehiculo_x_Orden (idOrdenCompra, idVehiculo, nLtsDisponibles, nLtsRestantes) VALUES (:Order, :Car, :Lts, :LtsRemaining)';
				$this->data = [
					':Order' => $orderId,
					':Car' => $car['Id'],
					':Lts' => $car['Lts'],
					':LtsRemaining' => $car['Lts'],
				];
			}else{
				$this->query = 'UPDATE Vehiculo_x_Orden SET nLtsDisponibles = :Lts, nLtsRestantes = :LtsRemaining WHERE idVehiculo = :IdCar AND idOrdenCompra = :IdOrder';
				$this->data = [
					':Lts' => $car['Lts'],
					':LtsRemaining' => $car['Lts'],
					':IdCar' => $car['Id'],
					':IdOrder' => $orderId,
				];
			}

			$this->executeQuery();
		}

		return true;
	}

	private function getCarsInOrder($orderId){
		$this->query = 'SELECT vo.idVehiculo, vo.nltsDisponibles, vo.nltsRestantes, v.cPatente, v.cModelo FROM Vehiculo_x_Orden AS vo
			INNER JOIN Vehiculos AS v ON vo.idVehiculo = v.idVehiculo
			WHERE idOrdenCompra = :Id';
		$this->data = [':Id' => $orderId];

		$result = $this->executeQuery();
		$carsInOrder = array();
		while ($row = $result->fetch()){
			$carsInOrder[] = array(
				'Id' => $row['idVehiculo'],
				'Patente' => $row['cPatente'],
				'Model' => $row['cModelo'],
				'MaxLts' => $row['nltsDisponibles'],
				'RemainingFuel' => $row['nltsRestantes']
			);
        }

        return $carsInOrder;
	}

	private function existingCarInOrder($orderId, $carId){
		$this->query = 'SELECT COUNT(idVehiculoxOrden) FROM Vehiculo_x_Orden WHERE idOrdenCompra = :IdOrden AND idVehiculo = :IdCar';
		$this->data = [':IdOrden' => $orderId, ':IdCar' => $carId];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function removeCarFromOrder($orderId, $carId){
		$this->query = 'DELETE FROM Vehiculo_x_Orden WHERE idOrdenCompra = :IdOrden AND idVehiculo = :IdCar';
		$this->data = [':IdOrden' => $orderId, ':IdCar' => $carId];

		return ($this->executeQuery()) ? true : false;
	}

	private function validFuelToSpent(){
		$total = $this->Fuel;

		$remaining = $total;

		foreach ($this->Cars as $key => $car) {
			$remaining = $total - $car['Lts'];
		}

		return ($remaining >= 0) ? true : false;
	}

	private function getDependenceId(){
		if($_POST['dependencia'] == 'OTRO'){
			$dependencia = $this->cleanString($_POST['new_dependencia']);

			if(empty($dependencia)){
				return 0;
			}

			$this->query = 'SELECT COUNT(idDependencia) FROM Dependencias WHERE idSecretaria = :Secretary AND cDependencia = UPPER(:Dependencia) ';
			$this->data = [':Secretary' => $this->Secretary, ':Dependencia' => $dependencia];

			if($this->searchRecords() > 0){
				$this->query = 'SELECT idDependencia FROM Dependencias WHERE cDependencia = UPPER(:Dependencia) ';

				return $this->executeQuery()->fetchColumn(0);
			}

			$this->query = 'INSERT INTO Dependencias (idSecretaria, cDependencia, idAlta, dFechaAlta) VALUES (:Secretary, UPPER(:Dependence), :User, :Fecha) ';
			$this->data = [':Secretary' => $this->Secretary, ':Dependence' => $dependencia, ':User' => $this->Session->getUserId(), ':Fecha' => $this->fecha];
			// var_dump($this->data);
			return ($this->executeQuery()) ? $this->getLastInsertedId() : 0;
		}

		$dependencia = intval($this->cleanString($_POST['dependencia']), 10);

		if(empty($dependencia)){
			return 0;
		}

		return ($this->existingDependence($dependencia)) ? $dependencia : 0;
	}

	// private function existingDependence($id){
	// 	$this->query = 'SELECT COUNT(idDependencia) FROM Dependencias WHERE idDependencia = :Id';
	// 	$this->data = [':Id' => $id];

	// 	return ($this->searchRecords() > 0) ? true : false;
	// }

	private function existingOrder($type, $search){
		switch ($type) {
			case 'ID':
				$this->query = 'SELECT COUNT(idOrdenCompra) FROM OrdenCompra WHERE idOrdenCompra = :Search AND idBaja IS NULL';
			break;
			case 'OC':
				$this->query = 'SELECT COUNT(idOrdenCompra) FROM OrdenCompra WHERE nOrdenCompra = :Search AND idBaja IS NULL';
			break;
			default:
				$this->query = 'SELECT COUNT(idOrdenCompra) FROM OrdenCompra WHERE idOrdenCompra = :Search AND idBaja IS NULL';
			break;
		}

		$this->data = [':Search' => $search];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function getUserInfo($userId){
		require_once 'userController.php';

		$User = new usuario();

		return $User->getUserInfo($userId);
	}

	private function orderInUse($Oc){
		$this->query = 'SELECT COUNT(nOc) FROM Remitos WHERE nOc = :Oc';
		$this->data = [':Oc' => $Oc];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function getOrderOcById($Id){
		$this->query = 'SELECT nOrdenCompra FROM OrdenCompra WHERE idOrdenCompra = :Id';
		$this->data = [':Id' => $Id];

		return $this->executeQuery()->fetchColumn(0);
	}

	private function getOrderValidityDate($Id){
		$this->query = 'SELECT dFechaValidez FROM OrdenCompra WHERE idOrdenCompra = :Id';
		$this->data = [':Id' => $Id];

		return $this->executeQuery()->fetchColumn(0);
	}

	private function getLastOrderNumer(){
		$this->query = 'SELECT TOP 1 nOrderCompra FROM OrdenCompra ORDER BY idOrdenCompra DESC';
		$this->data = [];

		return $this->executeQuery()->fetchColumn(0);
	}

	private function validateFields($call){
		$valid = false;

		switch ($call){
			case 'insert':
				if(isset($_POST['oc'],$_POST['secretaria'],$_POST['validez'],$_POST['vencimiento'])){
					if(!$this->validateEmptyPost(array('id', 'vehiculo'))){
						$valid = true;
					}
				}
			break;
			case 'update':
				if(isset($_POST['id']) && isset($_POST['secretaria'],$_POST['validez'],$_POST['vencimiento'])){
					if(!$this->validateEmptyPost(array('vehiculo')) && intval(trim($_POST['id'])) != 0){
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