<?php 

include_once  realpath(__DIR__ ).'/globalController.php';

class talonario extends globalController{
	private $Id;
	private $Oc, $Secretary, $ValidityDate, $ExpirationDate, $Dependence;

	private $Session;
	
	function __construct(){
		parent::__construct();

		$this->Session = new session();
	}

	public function getTalonarios(){

		$since = (isset($_POST['since'])) ? $_POST['since'] : '';
		$until = (isset($_POST['until'])) ? $_POST['until'] : '';

		if($since != ''){
			$this->query = 'SELECT t.idTalonario, t.nRemitoDesde, t.nRemitoHasta, t.dFechaExportado, t.idAlta, t.cDependencia, s.cSecretaria FROM Talonarios AS t
				INNER JOIN Secretarias AS s ON s.idSecretaria = t.idSecretaria
				WHERE :Since >= t.dFechaAlta AND :Until <= t.dFechaHasta';
			$this->data = [':Since' => $since, ':Until' => ($until == '') ? $this->fecha : $until,];
		}else{
			$this->query = 'SELECT t.idTalonario, t.nRemitoDesde, t.nRemitoHasta, t.dFechaExportado, t.idAlta, t.cDependencia, s.cSecretaria FROM Talonarios AS t
				INNER JOIN Secretarias AS s ON s.idSecretaria = t.idSecretaria';
			$this->data = [];
		}

		$result = $this->executeQuery();
		$talonario = array();
		while ($row = $result->fetch()){
            $talonario[] = array(
                'Remitos' => $row['nRemitoDesde'].' - '.$row['nRemitoHasta'],
                'Secretary' => $row['cSecretaria'],
                'Dependence' => $row['cDependencia'],
                'LastExportation' => $row['dFechaExportado'],
                'User' => $this->getUserInfo($row['idAlta']),
            );
        }

        return $talonario;
	}

	public function getRecorsToExport(){
		if(!$this->validateFields('export')){
			return array('Status' => 'Invalid Call', $_POST);
		}

		$this->MinExport = $this->getMinExport();
		$this->MaxExport = $this->getMaxExport();

		$this->ExportSince = intval($this->cleanString($_POST['desde']));
		$this->ExportUntil = intval($this->cleanString($_POST['hasta']));
		$this->Secretary = $this->cleanString($_POST['secretaria']);
		$this->Dependence = $this->cleanString($_POST['dependencia']);

		if( ($this->ExportSince < $this->MinExport && $this->ExportSince < $this->MaxExport) || ($this->ExportUntil > $this->MaxExport && $this->ExportUntil < $this->MinExport) ){
			return array('Status' => 'Records Out Of Limits', 'Min' => $this->MinExport, 'Max' => $this->MaxExport);
		}

		$this->query = 'SELECT r.nNumero, p.cProveedor, v.cPatente, oc.nOrdenCompra, r.nNafta, r.nNaftaPP, r.nGas, r.nGasPE, r.dFechaRemito, r.idAlta, r.dFechaAlta FROM Remitos AS r
			INNER JOIN Proveedores AS p ON r.idProveedor = p.idProveedor
			INNER JOIN Vehiculos AS v ON r.idVehiculo = v.idVehiculo
			INNER JOIN OrdenCompra AS oc ON r.idOc = oc.idOrdenCompra
			WHERE nNumero >= :Since AND nNumero <= :Until';
		$this->data = [':Since' => $this->ExportSince, ':Until' => $this->ExportUntil];

		$result = $this->executeQuery();
		$Remitos = array();
		while ($row = $result->fetch()){
            $Remitos[] = array(
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
                'CreationDate' => $row['dFechaAlta'],
            );
        }

        if(count($Remitos) > 0){
        	$this->saveTalonario($this->ExportSince, $this->ExportUntil);
        }

        return array(
        	'Status' => 'Success',
        	'ExportList' => $Remitos,
        	'Remitos' => $this->ExportSince.'-'.$this->ExportUntil,
        	'Secretary' => $this->Secretary,
        	'Dependence' => $this->Dependence,
        	'LastExportation' => $this->fecha,
        	'User' => $this->getUserInfo($this->Session->getUserId())
        );
	}

	public function getMinExport(){
		$this->query = 'SELECT MIN(nNumero) FROM Remitos';
		$this->data = [];

		return $this->executeQuery()->fetchColumn(0);
	}

	public function getMaxExport(){
		$this->query = 'SELECT MAX(nNumero) FROM Remitos';
		$this->data = [];

		return $this->executeQuery()->fetchColumn(0);
	}

	private function saveTalonario($since, $until){
		$this->query = 'INSERT INTO Talonarios (nRemitoDesde, nRemitoHasta, idSecretaria, cDependencia, dFechaExportado, idAlta, dFechaAlta) VALUES (:Since, :Until, :Secretary, :Dependence, :Fecha, :User, :FechaAlta) ';
		$this->data = [
			':Since' => $since,
			':Until' => $until,
			':Secretary' => $this->Secretary,
			':Dependence' => $this->Dependence,
			':Fecha' => $this->fecha,
			':User' => $this->Session->getUserId(),
			':FechaAlta' => $this->fecha
		];

		return ($this->executeQuery()) ? true : false;
	}


	private function getUserInfo($userId){
		require_once 'userController.php';

		$User = new usuario();

		return $User->getUserInfo($userId);
	}

	private function validateFields($call){
		$valid = false;

		switch ($call){
			case 'export':
				if(isset($_POST['desde'],$_POST['hasta'])){
					if(!$this->validateEmptyPost(array())){
						$valid = true;
					}
				}
			break;
		}
		return $valid;
	}
}

?>