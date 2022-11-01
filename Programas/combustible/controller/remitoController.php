<?php 

include_once  realpath(__DIR__ ).'/globalController.php';
include_once  realpath(__DIR__ ).'/userController.php';
include_once 'mailController.php';
require_once 'pdfController.php';

class remito extends globalController{
	private $Id;
	private $Number;
	private $Provider;
	private $Car;
	private $Oc;
	private $LtsNafta;
	private $LtsNaftaPP; // PP = V POWER / PREMIUM
	private $LtsGas;
	private $LtsGasPE; // VpEd = V POWER / EURO DIESEL
	private $Date;

	
	function __construct(){
		parent::__construct();

		$this->Session = new session();
	}

	public function getRemitos($equal = null){

		$search = (isset($_POST['search'])) ? mb_strtoupper($_POST['search'], 'utf-8') : '';

		if($search != ''){
			$this->query = 'SELECT r.nNumero, p.cProveedor, v.cPatente, oc.idSecretaria, oc.idDependencia, oc.nOrdenCompra, oc.dFechaVencimiento, r.nNafta, r.nNaftaPP, r.nGas, r.nGasPE, r.dFechaRemito, r.idAlta, r.dFechaAlta FROM Remitos AS r
				INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
				INNER JOIN Vehiculos AS v ON r.idVehiculo = v.idVehiculo
				INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra
				WHERE r.nNumero LIKE :Search';
			$this->data = [':Search' => '%'.$search.'%'];
		}else{
			$this->query = 'SELECT r.nNumero, p.cProveedor, v.cPatente, oc.idSecretaria, oc.idDependencia, oc.nOrdenCompra, oc.dFechaVencimiento, r.nNafta, r.nNaftaPP, r.nGas, r.nGasPE, r.dFechaRemito, r.idAlta, r.dFechaAlta FROM Remitos AS r
				INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
				INNER JOIN Vehiculos AS v ON r.idVehiculo = v.idVehiculo
				INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra';
			$this->data = [];
		}

		$result = $this->executeQuery();
		$Remitos = array();
		while ($row = $result->fetch()){
			if($this->Admin || $row['idDependencia'] == $_SESSION['DEPENDENCIA']){

				$combustible = '';

				if($row['nNafta'] != 0){
					$combustible = 'NAFTA/ '.$row['nNafta'];
				}
				if($row['nNaftaPP'] != 0){
					$combustible = 'NAFTA POWER/ '.$row['nNaftaPP'];
				}
				if($row['nGas'] != 0){
					$combustible = 'GAS/ '.$row['nGas'];
				}
				if($row['nGasPE'] != 0){
					$combustible = 'GAS-OIL-DIESEL/ '.$row['nGasPE'];
				}

	            $Remitos[] = array(
	            	'Secretary' => $this->searchSecretary($row['idSecretaria']),
	                'Dependence' => $this->searchDependence($row['idSecretaria'], $row['idDependencia']),
	                'Number' => $row['nNumero'],
	                'Provider' => $row['cProveedor'],
	                'Car' => $row['cPatente'],
	                'Oc' => $row['nOrdenCompra'],
	                'OcState' => ($row['dFechaVencimiento'] < $this->fecha) ? 'EXPIRED' : 'CURRENT',
	                'Combustible' => $combustible,
	                'Date' => $row['dFechaRemito'],
	                'Creator' => $this->getUserInfo($row['idAlta']),
	                'CreationDate' => $row['dFechaAlta'],
	                'Suggestion' => $row['nNumero'].' | '. $row['cProveedor'].' | '. $row['cPatente']
	            );
	        }
        }

        return $Remitos;
	}

	public function getRemito($equal){
		$equal = $this->cleanString($equal);

		$this->query = 'SELECT r.nNumero, p.cProveedor, v.cPatente, oc.nOrdenCompra, oc.idDependencia, r.nNafta, r.nNaftaPP, r.nGas, r.nGasPE, r.dFechaRemito, r.idAlta, r.dFechaAlta FROM Remitos AS r
			INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
			INNER JOIN Vehiculos AS v ON r.idVehiculo = v.idVehiculo
			INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra
			WHERE r.nNumero = :Search';
		$this->data = [':Search' => $equal];

		$result = $this->executeQuery();
		$Remito = array();
		while ($row = $result->fetch()){
			if($this->Admin || $row['idDependencia'] == $_SESSION['DEPENDENCIA']){
	            $Remito = array(
	                'Number' => $row['nNumero'],
	                'Provider' => $row['cProveedor'],
	                'Car' => $row['cPatente'],
	                'Oc' => $row['nOrdenCompra'],
	                'LtsNafta' => $row['nNafta'],
	                'LtsNaftaPP' => $row['nNaftaPP'],
	                'LtsGas' => $row['nGas'],
	                'LtsGasPE' => $row['nGasPE'],
	                'Date' => $row['dFechaRemito'],
	                'Creator' => $this->getUserInfo($row['idAlta']),
	                'CreationDate' => $row['dFechaAlta']
	            );
	        }
        }

        return $Remito;
	}

	// public function getRemitosWithAvailableOrders(){
	// 	$this->query = 'SELECT r.nNumero, p.cProveedor, v.cPatente, oc.nOrdenCompra, r.nNafta, r.nNaftaPP, r.nGas, r.nGasPE, r.dFechaRemito, r.idAlta, r.dFechaAlta FROM Remitos AS r
	// 		INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor AND p.idBaja IS NULL
	// 		INNER JOIN Vehiculos AS v ON r.idVehiculo = v.idVehiculo AND v.idBaja IS NULL
	// 		INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra
	// 		WHERE :StartFecha >= oc.dFechaValidez AND :EndDate <= oc.dFechaVencimiento';
	// 	$this->data = [];

	// 	$result = $this->executeQuery();
	// 	$Remitos = array();
	// 	while ($row = $result->fetch()){
 //            $Remitos[] = array(
 //                'Number' => $row['nNumero'],
 //                'Provider' => $row['cProveedor'],
 //                'Car' => $row['cPatente'],
 //                'Oc' => $row['nOrdenCompra'],
 //                'LtsNafta' => $row['nNafta'],
 //                'LtsNaftaPP' => $row['nNaftaPP'],
 //                'LtsGas' => $row['nGas'],
 //                'LtsGasPE' => $row['nGasPE'],
 //                'Date' => $row['dFechaRemito'],
 //                'Creator' => $this->getUserInfo($row['idAlta']),
 //                'CreationDate' => $row['dFechaAlta'],
 //                'Suggestion' => $row['nNumero'].' | '. $row['cProveedor'].' | '. $row['cPatente']
 //            );
 //        }

 //        return $Remitos;
	// }

	public function addRemito(){
		if(!$this->validateFields('insert')){
			return array('Status' => 'Incomplete Fields',$_POST);
		}

		$this->Number = $this->getLastNumberRemito() + 1;
		$this->Provider = $this->cleanString($_POST['proveedor']);
		$this->Car = $this->cleanString($_POST['vehiculo']);
		$this->Oc = $this->cleanString($_POST['oc']);
		$this->LtsNafta = (isset($_POST['nafta'])) ?  $this->cleanString($_POST['nafta']) : 0;
		$this->LtsNaftaPP = (isset($_POST['naftapp'])) ?  $this->cleanString($_POST['naftapp']) : 0;
		$this->LtsGas = (isset($_POST['gas'])) ?  $this->cleanString($_POST['gas']) : 0;
		$this->LtsGasPE = (isset($_POST['gaspe'])) ?  $this->cleanString($_POST['gaspe']) : 0;
		$this->Date = $this->fecha;
		
		if($this->existingRemito('NUMBER', $this->Number)){
			return array('Status' => 'Existing Remito');
		}

		if(!$this->validOc($this->Oc)){
			return array('Status' => 'Invalid Oc For Use');
		}

		$Lts = $this->getSpentLts();

		if(!$this->haveFuelToSpent($this->Oc, $Lts, $this->Car)){
			return array('Status' => 'Not Fuel', 'Lts' => $Lts);
		}

		$this->query = 'INSERT INTO Remitos (nNumero, idProveedor, idVehiculo, idOc, nNafta, nNaftaPP, nGas, nGasPE, dFechaRemito, idAlta, dFechaAlta) VALUES (:Number, :Provider, :Car, :Oc, :LtsNafta, :LtsNaftaPP, :LtsGas, :LtsGasPE,:Date, :User, :Fecha) ';
		$this->data = [
			':Number' => $this->Number,
            ':Provider' => $this->Provider,
            ':Car' => $this->Car,
            ':Oc' => $this->Oc,
            ':LtsNafta' => $this->LtsNafta,
            ':LtsNaftaPP' => $this->LtsNaftaPP,
            ':LtsGas' => $this->LtsGas,
            ':LtsGasPE' => $this->LtsGasPE,
            ':Date' => $this->Date,
			':User' => $this->Session->getUserId(),
			':Fecha' => $this->fecha
		];

		return ($this->executeQuery()) ? array('Status' => 'Success', 'Fuel' => $this->updateFuel($this->Oc, $Lts, $this->Car)) : array('Status' => 'Error');
	}

	public function sendRemitosToProvider($reenvio = null){

		if($reenvio !== null){
			$reenvio = intval($this->cleanString($reenvio));
		}

		$this->query = 'SELECT COUNT(idRemito) FROM Remitos WHERE nMailEnviado IS NULL';
		$this->data = [];

		if($this->searchRecords() == 0 && $reenvio === null){
			return array('Status' => 'No Remito To Send');
		}
		//-----------------------EN SU MOMENTO SOLO SE PODRIA ENVIAR MAIL A LOS PROVEEDORES SOLO RELACIONADOS A LA DEPENDENCIA DEL USUARIO - ACTUALMENTE DESCOMENTADO POR SER INNECESARIO - ANALIZAR CON USUARIO PARA IMPLEMENTACION FUTURA ---------------------------

		// if($reenvio === null){
		// 	$this->query = 'SELECT r.idRemito,r.nNumero, p.idProveedor, p.cProveedor, p.cEmail, oc.idDependencia FROM Remitos AS r
		// 		INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
		// 		INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra 
		// 		WHERE r.nMailEnviado IS NULL AND oc.idDependencia = :Dependence';
		// 	$this->data = [':Dependence' => $_SESSION['DEPENDENCIA']];
		// }else{
		// 	$this->query = 'SELECT r.idRemito,r.nNumero, p.idProveedor, p.cProveedor, p.cEmail, oc.idDependencia FROM Remitos AS r
		// 		INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
		// 		INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra 
		// 		WHERE r.nNumero = :Remito AND doc.idDependencia = :Dependence';
		// 	$this->data = [':Remito' => $reenvio, ':Dependence' => $_SESSION['DEPENDENCIA']];
		// }
		// ----------------------------------------------------------------------------------------------------------------------------------------------
		if($reenvio === null){
			$this->query = 'SELECT r.idRemito,r.nNumero, p.idProveedor, p.cProveedor, p.cEmail, oc.idDependencia FROM Remitos AS r
				INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
				INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra 
				WHERE r.nMailEnviado IS NULL';
			$this->data = [];
		}else{
			$this->query = 'SELECT r.idRemito,r.nNumero, p.idProveedor, p.cProveedor, p.cEmail, oc.idDependencia FROM Remitos AS r
				INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
				INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra 
				WHERE r.nNumero = :Remito';
			$this->data = [':Remito' => $reenvio];
		}

		$result = $this->executeQuery();
		$Mail = new mail();

		$response = array();

		while ($row = $result->fetch()) {

			$dataPdf = $this->getRemito($row['nNumero']);

			if(count($dataPdf) != 0){
				$Pdf = new pdf(
					$dataPdf['Number'],
					$dataPdf['Provider'],
					$dataPdf['Car'],
					$dataPdf['Oc'],
					$dataPdf['LtsNafta'],
					$dataPdf['LtsNaftaPP'],
					$dataPdf['LtsGas'],
					$dataPdf['LtsGasPE'],
					$dataPdf['Date'],
					false,
					true
				);	//CREO EL PDF

				$P = $Pdf->GetPdf();
				
				$Mail->Address = $row['cEmail'];
				$Mail->Name = $row['cProveedor'];
				$Mail->Subject = 'Vale de combustible';
				$Mail->Body = 'Vale de combustible Nº '.$row['nNumero'];
				$Mail->NumRemito = $row['nNumero'];
				$Mail->Pdf = $P;

				$statusMail = $Mail->send();
				$save = false; 

				if($statusMail == 'Mail Send'){
					$save = $this->saveEmail($row['idRemito'], $row['idProveedor'], $reenvio);
				}

				$response[] = array('StatusEmail' => $statusMail, 'SentTo' => $row['cProveedor'], 'RemitoSent' => $row['nNumero'], 'Saved' => $save);
			}
			
		}

		return array('Status' => 'Success', 'Email' => $response);
	}

	public function validRemito($remito = null){
		if($remito === null){
			return false;
		}

		return $this->existingRemito('NUMBER', $remito);
	}

	public function getLastNumberRemito(){
		$this->query = 'SELECT TOP 1 nNumero FROM Remitos ORDER BY idRemito DESC';
		$this->data = [];

		return intval($this->executeQuery()->fetchColumn(0), 10);
	}

	private function haveFuelToSpent($Oc, $Lts, $Car){
		$this->query = 'SELECT nRestante FROM OrdenCompra WHERE idOrdenCompra = :Id';
		$this->data = [':Id' => $Oc];

		$OrderRemaining = $this->executeQuery()->fetchColumn(0);

		$result = $OrderRemaining - $Lts;

		if($result < 0){
			return false;
		}

		$this->query = 'SELECT nLtsRestantes FROM Vehiculo_x_Orden WHERE idOrdenCompra = :Id AND idVehiculo = :Car';
		$this->data = [':Id' => $Oc, ':Car' => $Car];

		$CarRemaining = $this->executeQuery()->fetchColumn(0);

		$result = $CarRemaining - $Lts;

		return ($result >= 0) ? true : false;
	}

	private function updateFuel($Oc, $Lts, $Car){
		$this->query = 'UPDATE OrdenCompra SET nRestante = (nRestante - :Lts) WHERE idOrdenCompra = :Id';
		$this->data = [':Lts' => $Lts, ':Id' => $Oc];

		$r = ($this->executeQuery()) ? true : false;

		$this->query = 'UPDATE Vehiculo_x_Orden SET nLtsRestantes = (nLtsRestantes - :Lts) WHERE idVehiculo = :Id';
		$this->data = [':Lts' => $Lts, ':Id' => $Car];

		return ($this->executeQuery()) ? true : false;
	}

	private function getSpentLts(){
		if($this->LtsGas != 0){
			return $this->LtsGas;
		}

		if($this->LtsNafta != 0){
			return $this->LtsNafta;
		}

		if($this->LtsGasPE != 0){
			return $this->LtsGasPE;
		}

		if($this->LtsNaftaPP != 0){
			return $this->LtsNaftaPP;
		}

		return 0;
	}

	private function validOc($Oc){
		if($this->Admin){
			$this->query = 'SELECT COUNT(idOrdenCompra) FROM OrdenCompra WHERE idOrdenCompra = :Id AND (:StartFecha >= dFechaValidez AND :EndDate <= dFechaVencimiento)';
			$this->data = [':Id' => $Oc, ':StartFecha' => $this->fecha, ':EndDate' => $this->fecha];
		}else{
			$this->query = 'SELECT COUNT(idOrdenCompra) FROM OrdenCompra WHERE idOrdenCompra = :Id AND (:StartFecha >= dFechaValidez AND :EndDate <= dFechaVencimiento) AND idDependencia = :Dependence';
			$this->data = [':Id' => $Oc, ':StartFecha' => $this->fecha, ':EndDate' => $this->fecha, ':Dependence' => $_SESSION['DEPENDENCIA']];
		}

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function saveEmail($IdRemito, $IdProveedor, $reenvio){
		if($reenvio === null){
			$this->query = 'UPDATE Remitos SET nMailEnviado = 1 WHERE idRemito = :Id';
			$this->data = [':Id' => $IdRemito];

			$this->executeQuery();

			$this->query = 'INSERT INTO MailsEnviados (idRemito, idProveedor, idEnvio, dFechaEnvio) VALUES (:Remito, :Provider, :User, :Fecha) ';
			$this->data = [':Remito' => $IdRemito, ':Provider' => $IdProveedor, ':User' => $this->Session->getUserId(), ':Fecha' => $this->fecha];
		}else{
			$this->query = 'UPDATE MailsEnviados SET idReenvio = :User, dFechaReenvio = :Fecha WHERE idRemito = :Remito AND idProveedor = :Provider';
			$this->data = [':User' =>  $this->Session->getUserId(), ':Fecha' => $this->fecha, ':Remito' => $IdRemito, ':Provider' => $IdProveedor];
		}

		return ($this->executeQuery()) ? true : false;
	}

	private function getUserInfo($userId){
		$User = new usuario();

		return $User->getUserInfo($userId);
	}

	private function existingRemito($type, $search){
		switch ($type) {
			case 'ID':
				$this->query = 'SELECT COUNT(idRemito) FROM Remitos WHERE idRemito = :Search';
			break;
			case 'NUMBER':
				$this->query = 'SELECT COUNT(idRemito) FROM Remitos WHERE nNumero = :Search';
			break;
			default:
				$this->query = 'SELECT COUNT(idRemito) FROM Remitos WHERE idRemito = :Search';
			break;
		}

		$this->data = [':Search' => $search];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function validateFields($call){
		$valid = false;
		switch ($call) {
			case 'insert':
				if(isset($_POST['proveedor'],$_POST['vehiculo'],$_POST['oc']) && (isset($_POST['nafta']) || isset($_POST['naftapp']) || isset($_POST['gas']) || isset($_POST['gaspe']))){
					if(!$this->validateEmptyPost(array('nafta','naftapp','gas','gaspe'))){
						$valid = true;
					}
				}
			break;
			case 'update':
				if(isset($_POST['id']) && isset($_POST['nombre'])){
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