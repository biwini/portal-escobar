<?php 

include_once  realpath(__DIR__ ).'/globalController.php';

class liquidacion extends globalController{
	private $id;
	private $idNomenclatura;
	private $razonSocial;
	private $zonificacion;
	private $tipoLiq;
	private $descuento;
	private $totalLiq;

	private $circ;
	private $seccion;
	private $fraccion;
	private $chacra;
	private $quinta;
	private $manzana;
	private $parcela;
	private $uf;
	private $partida;

	private $totalCg;
	private $totalMulta;

	private $Session;
	
	function __construct(){
		parent::__construct();

		$this->totalLiq = null;

		$this->Session = new session();

	}

	public function getLiquidacion($id = null){
		$this->query = 'SELECT idLiquidacion, cRazonSocial, cZonificacion, cTipoLiquidacion, nDescuento, nTotal, dFechaLiquidacion, idAlta FROM Liquidaciones';
		$this->data = [];

		$result = $this->executeQuery();
		$liquidaciones = array();

		while ($row = $result->fetch()) {
			$liquidaciones[] = array(
				'Id' => $row['idLiquidacion'],
				'RazonSocial' => $row['cRazonSocial'],
				'Zonificacion' => $row['cZonificacion'],
				'Type' => $row['cTipoLiquidacion'],
				'Discount' => $row['nDescuento'],
				'Total' => $row['nTotal'],
				'Date' => date_format(date_create($row['dFechaLiquidacion']),"Y-m-d"),
				'Creator' => $row['idAlta'],
			);
		}

		return $liquidaciones;
	}

	public function getFullDataLiquidacion($id){
		$this->query = 'SELECT idLiquidacion, idNomenclatura, cRazonSocial, cZonificacion, cTipoLiquidacion, nDescuento, nTotal, nSmMunicipal, dFechaLiquidacion, idAlta FROM Liquidaciones WHERE idLiquidacion = :Id';
		$this->data = [':Id' => intval($id)];

		$result = $this->executeQuery();
		$liquidacion = array();

		while ($row = $result->fetch()) {
			$liquidacion = array(
				'Id' => $row['idLiquidacion'],
				'Nomenclatura' => $this->getNomenclatura($row['idNomenclatura']),
				'RazonSocial' => $row['cRazonSocial'],
				'Zonificacion' => $row['cZonificacion'],
				'TipoLiquidacion' => $row['cTipoLiquidacion'],
				'Descuento' => $row['nDescuento'],
				'SmMunicipal' => $row['nSmMunicipal'],
				'ContratoColegio' => $this->getContratoColegio($row['idLiquidacion']),
				'Multas' => $this->getMultas($row['idLiquidacion']),
				'Total' => $row['nTotal'],
				'Fecha' => date_format(date_create($row['dFechaLiquidacion']),"Y-m-d"),
				'Creator' => $row['idAlta'],
			);
		}

		return $liquidacion;
	}

	public function addLiquidacion(){

		if(!$this->validateFields('insert')){
			return array('Status' => 'Incomplete Fields');
		}
		// return $_POST;
		$this->razonSocial = $this->cleanString($_POST['nombre']);
		$this->zonificacion = $this->cleanString($_POST['zonificacion']);
		$this->tipoLiq = $this->cleanString($_POST['tipo_liq']);
		$this->smMunicipal = intval($_POST['sm_municipal'], 10);
		$this->descuento = (isset($_POST['descuento'])) ? intval($_POST['descuento'], 10) : 0;

		$valid = false;

		switch ($this->tipoLiq) {
			case 'NORMAL':
			case 'ART126':
				if(isset($_POST['mt_m2'],$_POST['mt_cant'],$_POST['mt_porcentaje'],$_POST['cg_monto_name'],$_POST['cg_monto_value'], $_POST['cg_coef'],$_POST['cg_recargo'])){
					if($this->tipoLiq == 'ART126'){
						$valid = (isset($_POST['cg_artvs_destino'], $_POST['cg_artvs_m2'], $_POST['cg_artvs_coef'], $_POST['cg_artvs_ref'])) ? true : false;
					}else{
						$valid = true;
					}
					
				}
			break;
			case 'ART13':
				if(isset($_POST['cg_arttrc_des'], $_POST['cg_arttrc_cap'], $_POST['cg_arttrc_tipo'], $_POST['cg_arttrc_ref'], $_POST['cg_arttrc_coef'], $_POST['cg_arttrc_m2'], $_POST['cg_arttrc_destino'],)){
					$valid = true;
				}
			break;
			case 'CARTELES':
				if(isset($_POST['cg_monto_name'],$_POST['cg_monto_value'], $_POST['cg_coef'],$_POST['cg_recargo'])){
					$valid = true;
				}
			break;
			case 'ELECTROMECANICO':
				if(isset($_POST['elec_destino'],$_POST['elec_m2'], $_POST['elec_cap'])){
					$valid = true;
				}
			break;
		}

		if(!$valid){
			return array('Status' => 'Incomplete Fields',$valid,$this->tipoLiq,$_POST);
		}

		$this->query = 'INSERT INTO Liquidaciones (idNomenclatura, cRazonSocial, cZonificacion, cTipoLiquidacion, nSmMunicipal, nDescuento, nTotal, dFechaLiquidacion, idAlta)
			VALUES (:Nomenclatura, :RazonSocial, :Zonificacion, :TipoLiq, :SmMunicipal, :Descuento, :Total, :Fecha, :User)';
		$this->data = [
			':Nomenclatura' => $this->idNomenclatura,
			':RazonSocial' => $this->razonSocial,
			':Zonificacion' => $this->zonificacion,
			':TipoLiq' => $this->tipoLiq,
			':SmMunicipal' => $this->smMunicipal,
			':Descuento' => $this->descuento,
			':Total' => null,
			':Fecha' => $this->fecha,
			':User' => $this->Session->getUserId()
		];

		if(!$this->executeQuery()){
			return array('Status' => 'Error');
		}

		$this->id = $this->getLastInsertedId();

		$this->idNomenclatura = $this->addNomenclatura();

		if($this->idNomenclatura == 0){
			return array('Status' => 'Invalid Nomenclatura');
		}

		switch ($this->tipoLiq) {
			case 'NORMAL':
				$this->totalCg = $this->addContratoColegio($this->id);
				$this->totalMulta = $this->addMultas($this->id);

				$this->totalLiq = ($this->totalCg + $this->totalMulta) * ( (100 - $this->descuento) / 100);
			break;
			case 'ART13':
				$this->totalCg = $this->addContratoColegioArt13($this->id);

				$this->totalLiq = $this->totalCg;
			break;
			case 'ART126':
				$this->totalCg = $this->addContratoColegioArt126($this->id) + $this->addContratoColegio($this->id);
				$this->totalMulta = $this->addMultas($this->id);

				$this->totalLiq = $this->totalCg + $this->totalMulta;
			break;
			case 'CARTELES':
				$this->totalCg = $this->addContratoColegio($this->id);

				$this->totalLiq = $this->totalCg;
			break;
			case 'ELECTROMECANICO':
				$this->totalCg = $this->addLiqElectromecanico($this->id);

				$this->totalLiq = $this->totalCg;
			break;
		}

		// if($this->totalCg == 0 || $this->totalMulta == 0){
		// 	return array('Status' => 'Invalid Contrato Or Multa');
		// }

		$this->query = 'UPDATE Liquidaciones SET nTotal = :Total WHERE idLiquidacion = :Id';
		$this->data = [':Total' => $this->totalLiq, ':Id' => $this->id];

		return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
	}

	// private function addLiquidacion(){
	// 	$this->query = 'INSERT INTO Liquidaciones (idNomenclatura, cRazonSocial, cZonificacion, cTipoLiquidacion, nSmMunicipal, nDescuento, nTotal, dFechaLiquidacion, idAlta)
	// 		VALUES (:Nomenclatura, :RazonSocial, :Zonificacion, :TipoLiq, :SmMunicipal, :Descuento, :Total, :Fecha, :User)';
	// 	$this->data = [
	// 		':Nomenclatura' => $this->idNomenclatura,
	// 		':RazonSocial' => $this->razonSocial,
	// 		':Zonificacion' => $this->zonificacion,
	// 		':TipoLiq' => $this->tipoLiq,
	// 		':SmMunicipal' => $this->smMunicipal,
	// 		':Descuento' => $this->descuento,
	// 		':Total' => null,
	// 		':Fecha' => $this->fecha,
	// 		':User' => $this->Session->getUserId()
	// 	];

	// 	if(!$this->executeQuery()){
	// 		return 0;
	// 	}

	// 	return $this->getLastInsertedId();
	// }

	private function addNomenclatura(){
		$this->circ = $this->cleanString($_POST['circ']);
		$this->seccion = $this->cleanString($_POST['seccion']);
		$this->fraccion = $this->cleanString($_POST['fraccion']);
		$this->chacra = $this->cleanString($_POST['chacra']);
		$this->quinta = $this->cleanString($_POST['quinta']);
		$this->manzana = $this->cleanString($_POST['manzana']);
		$this->parcela = $this->cleanString($_POST['parcela']);
		$this->uf = $this->cleanString($_POST['uf']);
		$this->partida = $this->cleanString($_POST['partida']);

		$this->query = 'INSERT INTO nomenclatura (cCirc, cSeccion, cFraccion, cChacra, cQuinta, cManzana, cParcela, nUf, cPartida) VALUES 
			(:Circ, :Seccion, :Fraccion, :Chacra, :Quinta, :Manzana, :Parcela, :Uf, :Partida)';
		$this->data = [
			':Circ' => $this->circ,
			':Seccion' => $this->seccion,
			':Fraccion' => $this->fraccion,
			':Chacra' => $this->chacra,
			':Quinta' => $this->quinta,
			':Manzana' => $this->manzana,
			':Parcela' => $this->parcela,
			':Uf' => $this->uf,
			':Partida' => $this->partida
		];

		return ($this->executeQuery()) ? $this->getLastInsertedId() : 0;
	}

	private function addContratoColegio($idLiq){
		//cg = Contrato de colegio
		$cg = $this->array_zip_combine(
			['Type', 'Value', 'Coef', 'Recargo'],
			$_POST['cg_monto_name'],
			$_POST['cg_monto_value'], 
			$_POST['cg_coef'],
			$_POST['cg_recargo'],
		);

		$totalCG = 0;

		foreach ($cg as $key => $value) {
			$subTotal = $this->calculateTotalColegio($value);

			$this->query = 'INSERT INTO ContratoColegio (idLiquidacion,cTipo, nMonto, nCoef, nRecargo, nTotal) VALUES (:Id, :AmountType, :AmountValue, :Coef, :Recargo, :Total)';
			$this->data = [
				':Id' => $idLiq,
				':AmountType' => $value['Type'],
				':AmountValue' => $value['Value'],
				':Coef' => $value['Coef'],
				':Recargo' => $value['Recargo'],
				':Total' => $subTotal
			];

			if($this->executeQuery()){
				$totalCG += $subTotal;
			}
		}

		return $totalCG;
	}
	
	private function addContratoColegioArt13($idLiq){
		//cg = Contrato de colegio
		$cg = $this->array_zip_combine(
			['Destino', 'M2', 'Coef', 'Ref'],
			$_POST['cg_arttrc_destino'],
			$_POST['cg_arttrc_m2'],
			$_POST['cg_arttrc_coef'],
			$_POST['cg_arttrc_ref']
		);

		$totalCG = 0;

		foreach ($cg as $key => $value) {
			$subTotal = $this->calculateTotalColegioArt13($value);

			$this->query = 'INSERT INTO ContratoColegio (idLiquidacion, cTipo, nMtsCuadrados, nCoef, nReferencial, nTotal) VALUES (:Id, :Type, :M2, :Coef, :Ref, :Total)';
			$this->data = [
				':Id' => $idLiq,
				':Type' => $value['Destino'],
				':M2' => $value['M2'],
				':Coef' => $value['Coef'],
				':Ref' => $value['Ref'],
				':Total' => $subTotal
			];

			if($this->executeQuery()){
				$totalCG += $subTotal;
			}
		}
		$capIx = intVal($this->cleanString($_POST['cg_arttrc_cap']));

		$totalCG = $totalCG * ($capIx / 100);

		$this->query = 'INSERT INTO ContratoColegio (idLiquidacion, cTipo, cDestino, nCapIx, nTotal) VALUES (:Id, :Type, :Destino, :Cap, :Total)';
		$this->data = [
			':Id' => $idLiq,
			':Type' => 'ARTICULO 13',
			':Destino' => $this->cleanString($_POST['cg_arttrc_des']),
			':Cap' => $capIx,
			':Total' => $totalCG
		];

		return ($this->executeQuery()) ? $totalCG : 0;
	}

	private function addContratoColegioArt126($idLiq){
		//cg = Contrato de colegio
		$cg = $this->array_zip_combine(
			['Destino', 'M2', 'Coef', 'Ref'],
			$_POST['cg_artvs_destino'],
			$_POST['cg_artvs_m2'],
			$_POST['cg_artvs_coef'],
			$_POST['cg_artvs_ref']
		);

		$totalCG = 0;

		foreach ($cg as $key => $value) {
			$subTotal = $this->calculateTotalColegioArt13($value);

			$this->query = 'INSERT INTO ContratoColegio (idLiquidacion, cTipo, nMtsCuadrados, nCoef, nReferencial, nTotal) VALUES (:Id, :Type, :M2, :Coef, :Ref, :Total)';
			$this->data = [
				':Id' => $idLiq,
				':Type' => $value['Destino'],
				':M2' => $value['M2'],
				':Coef' => $value['Coef'],
				':Ref' => $value['Ref'],
				':Total' => $subTotal
			];

			if($this->executeQuery()){
				$totalCG += $subTotal;
			}
		}

		return $totalCG;
	}

	private function addLiqElectromecanico(){
		$liq = $this->array_zip_combine(
			['Destino', 'M2', 'Cap'],
			$_POST['elec_destino'],
			$_POST['elec_m2'], 
			$_POST['elec_cap'],
		);

		$total = 0;

		foreach ($multas as $key => $value) {
			$subTotal = $value['M2'] * $value['Cap'];

			$this->query = 'INSERT INTO ContratoColegio (idLiquidacion, cDestino, nMetrosCuadrados, nCapIx, nTotal) VALUES (:Id, :Destino, :M2, :Cap, :Total)';
			$this->data = [
				':Id' => $idLiq,
				':Destino' => $value['Destino'],
				':M2' => $value['M2'],
				':Cap' => $value['Cap'],
				':Total' => $subTotal
			];

			if($this->executeQuery()){
				$total += $subTotal;
			}
		}

		return $total;
	}

	private function addMultas($idLiq){
		$multas = $this->array_zip_combine(
			['Type', 'M2', 'Cant', 'Porcentaje'],
			array('fos', 'fot', 'retiros', 'densidad', 'dto 1281/14'),
			$_POST['mt_m2'],
			$_POST['mt_cant'], 
			$_POST['mt_porcentaje'],
		);

		$totalMulta = 0;

		foreach ($multas as $key => $value) {
			$subTotal = $this->calculateMulta($value);

			$this->query = 'INSERT INTO Multas (idLiquidacion, cTipoMulta, nMetrosCuadrados, nCant, nPorcentaje, nTotal) VALUES (:Id, :Type, :M2, :Cant, :Porcentaje, :Total)';
			$this->data = [
				':Id' => $idLiq,
				':Type' => $value['Type'],
				':M2' => $value['M2'],
				':Cant' => $value['Cant'],
				':Porcentaje' => $value['Porcentaje'],
				':Total' => $subTotal
			];

			if($this->executeQuery()){
				$totalMulta += $subTotal;
			}
		}

		return $totalMulta;
	}

	private function calculateMulta($values){
		$total = 0;
		$total += (($values['Cant'] * $this->smMunicipal) * $values['M2']) * ($values['Porcentaje'] / 100);

		return $total;
	}

	private function calculateTotalColegio($values){
		$total = 0;
		$total += (($values['Coef'] / 100) * $values['Value']) + (($values['Recargo'] / 100) * (($values['Coef'] / 100) * $values['Value']));

		return $total;
	}

	private function calculateTotalColegioArt13($values){
		$total = 0;

		$total += ($values['M2'] * $values['Coef']) * $values['Ref'];
	}

	// private function existingProvider($type, $search){
	// 	switch ($type) {
	// 		case 'ID':
	// 			$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE idProveedor = :Search AND idBaja IS NULL';
	// 		break;
	// 		case 'NAME':
	// 			$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE cProveedor = :Search AND idBaja IS NULL';
	// 		break;
	// 		case 'EMAIL':
	// 			$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE cEmail = :Search AND idBaja IS NULL';
	// 		break;
	// 		default:
	// 			$this->query = 'SELECT COUNT(idProveedor) FROM Proveedores WHERE idProveedor = :Search AND idBaja IS NULL';
	// 		break;
	// 	}

	// 	$this->data = [':Search' => $search];

	// 	return ($this->searchRecords() > 0) ? true : false;
	// }

	private function getContratoColegio($idLiq){
		$this->query = 'SELECT idContratoColegio, cTipo, cDestino, nMonto, nMtsCuadrados, nReferencial, nCoef, nRecargo, nCapIx, nTotal FROM ContratoColegio WHERE idLiquidacion = :Id';
		$this->data = [':Id' => $idLiq];

		$result = $this->executeQuery();
		$contratoColegio = array();

		while ($row = $result->fetch()) {
			$contratoColegio[] = array(
				'Id' => $row['idContratoColegio'],
				'Tipo' => $row['cTipo'],
				'Destino' => $row['cDestino'],
				'Monto' => $row['nMonto'],
				'M2' => $row['nMtsCuadrados'],
				'Referencial' => $row['nReferencial'],
				'Coef' => $row['nCoef'],
				'Recargo' => $row['nRecargo'],
				'CapIx' => $row['nCapIx'],
				'Total' => $row['nTotal'],
			);
		}

		return $contratoColegio;
	}

	private function getMultas($idLiq){
		$this->query = 'SELECT idMulta, cTipoMulta, nMetrosCuadrados, nCant, nPorcentaje, nTotal FROM Multas WHERE idLiquidacion = :Id';
		$this->data = [':Id' => $idLiq];

		$result = $this->executeQuery();
		$multas = array();

		while ($row = $result->fetch()) {
			$multas[] = array(
				'Id' => $row['idMulta'],
				'Tipo' => $row['cTipoMulta'],
				'M2' => $row['nMetrosCuadrados'],
				'Cant' => $row['nCant'],
				'Porcentaje' => $row['nPorcentaje'],
				'Total' => $row['nTotal'],
			);
		}

		return $multas;
	}

	private function getNomenclatura($id){
		$this->query = 'SELECT idNomenclatura, cManzana, cCirc, cQuinta, cSeccion, cFraccion, cChacra, cParcela, nUf, cPartida FROM nomenclatura WHERE idNomenclatura = :Id';
		$this->data = [':Id' => $id];

		$result = $this->executeQuery();
		$nomenclatura = array();

		while ($row = $result->fetch()) {
			$nomenclatura = array(
				'Id' => $row['idNomenclatura'],
				'Manzana' => $row['cManzana'],
				'Circ' => $row['cCirc'],
				'Quinta' => $row['cQuinta'],
				'Seccion' => $row['cSeccion'],
				'Fraccion' => $row['cFraccion'],
				'Chacra' => $row['cChacra'],
				'Parcela' => $row['cParcela'],
				'Uf' => $row['nUf'],
				'Partida' => $row['cPartida'],
			);
		}

		return $nomenclatura;
	}

	public function validLiquidacion($id){
		$this->query = 'SELECT COUNT(idLiquidacion) FROM Liquidaciones WHERE idLiquidacion = :Id';
		$this->data = [':Id' => $id];

		return ($this->searchRecords() > 0) ? true : false;
	}

	private function validateFields($call){
		$valid = false;
		switch ($call) {
			case 'insert':
				if(isset(
					$_POST['sm_municipal'],
					$_POST['tipo_liq'],
					$_POST['nombre'],
					$_POST['circ'],
					$_POST['seccion'],
					$_POST['fraccion'],
					$_POST['chacra'],
					$_POST['quinta'],
					$_POST['manzana'],
					$_POST['parcela'],
					$_POST['uf'],
					$_POST['partida'])
				){
					
					if(!$this->validateEmptyPost(array('cg_arttrc_tipo','mt_type','cg_monto_name','cg_monto_value','cg_coef','cg_recargo','mt_m2','mt_cant','mt_porcentaje','mt_sm','descuento','circ','seccion','fraccion','chacra','quinta','manzana','parcela','uf','partida'))){
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