<?php 
    require_once  realpath(__DIR__ ).'/globalController.php';

	class obra extends globalController{
		private $id, $name, $pagado, $aPagar, $expediente, $anioExp, $importeEst, $importeDef;
		private $imputado, $observaciones, $unidadEjecutora, $proveedor, $proyecto;
		private $certificado;

		public function getObras(){
			$this->query = 'SELECT idObra, cNombre, nExpte,nExpteAnio, nEstimado, nDefinitivo, cImputado, nPagado, nSaldoAPagar,
                cObs, idUnidadEjecutora, idProveedor, idProyecto, dAlta FROM Obra WHERE idBaja IS NULL ORDER BY idObra ASC';
			$this->data = [];

			$result = $this->executeQuery();
			$response = $this->getResponse($result);

			return $response;
		}

		public function getObra($id){
			$this->query = 'SELECT idObra, cNombre, nExpte,nExpteAnio, nEstimado, nDefinitivo, cImputado, nPagado, nSaldoAPagar,
			cObs, idUnidadEjecutora, idProveedor, idProyecto, dAlta FROM Obra WHERE idObra = :Id AND idBaja IS NULL ORDER BY idObra ASC';
			$this->data = [':Id' => $id];

			$result = $this->executeQuery();
			$response = $this->getResponse($result);

			return $response[0];
		}

		private function getResponse($obra){
			$response = array();

			while ($row = $obra->fetch()) {
				$response [] = array(
					'id' => $row['idObra'],
					'nombre' => $row['cNombre'],
					'proyecto' => $row['idProyecto'],
					'expediente' => $row['nExpte'],
					'anioExpediente' => $row['nExpteAnio'],
					'idUnidadEjecutora' => $row['idUnidadEjecutora'],
					'idProveedor' => $row['idProveedor'],
					'importeEstimado' => (float) $row['nEstimado'],
					'importeDefinitivo' => (float) $row['nDefinitivo'],
					'imputado' => $row['cImputado'],
					'pagado' => (float) $row['nPagado'],
					'aPagar' => (float) $row['nSaldoAPagar'],
					'observaciones' => $row['cObs'],
					'fechaCreado' => $row['dAlta'],
					'registrosCompromiso' => $this->getRegistrosCompromiso($row['idObra']),
					'ordenesPago' => $this->getOrdenesPago($row['idObra']),
					'imputaciones' => $this->getImputaciones($row['idObra']),
					'datosAdicionales' => $this->getDatosAdicionales($row['idObra']),
					'certificados' => $this->getCertificados($row['idObra'])
				);
			}

			return $response; 
		}

		private function getRegistrosCompromiso($idObra){
			$this->query = 'SELECT idRC AS id, nNro AS numero, dFecha AS fecha, nImporte AS importe, idBaja AS eliminado FROM Rc WHERE idObra = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $idObra];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		private function getOrdenesPago($idObra){
			$this->query = 'SELECT idOP AS id, nNro AS numero, dFecha AS fecha, nImporte AS importe, dPagado AS pagado, cOCEA AS ocea, nOceaNumero AS numeroOcea, dOceaFecha AS fechaOcea, idBaja AS eliminado FROM OP WHERE idObra = :id AND idBaja IS NULL ORDER BY idOP ASC';
			$this->data = [':id' => $idObra];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		private function getImputaciones($idObra){
			$this->query = 'SELECT idImputacion AS id, idFuente AS fuente, idJurisdiccion AS jurisdiccion, idObjetoGasto AS gasto, nCP1 AS categoria1, nCP2 AS categoria2, nCP3 AS categoria3, cAfectacion AS afectacion, idBaja AS eliminado FROM Imputacion WHERE idObra = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $idObra];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		private function getDatosAdicionales($idObra){
			$this->query = 'SELECT idModalidad, nModalidad,nModalidadAnio, idEstado, idTipoObra, cPlazo, nPlazo, dApertura, dCircuito, dLlamado, nLlamado, nLlamadoAnio,
				dPropuesta, nPropuestaFolio, cPropuestaHora, dAdjudicacion, nAdjudicacion, nAdjudicacionAnio, dContrato, nContrato, dInicioObra, dFinObra, dRecepcion,
				p.cNombre, p.cCodigo
				FROM Obra AS o
				LEFT JOIN Proyecto AS p ON o.idProyecto = p.idProyecto
				WHERE o.idObra = :Id AND o.idBaja IS NULL';
			$this->data = [':Id' => $idObra];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC)[0];
		}

		private function getCertificados($idObra){
			$this->query = 'SELECT idCertificado, cCertificado, cPeriodo, nImporte, cAmpliacion, dContrato, nAmpliacion, nProrroga,
				dProrroga, nProrroga2, dProrroga2, dTerminacion, dTerminacion2, nCertificado, nGarantia, dDevolucion,
				cRetencion, nRetencion, cPeriodo, cObs, cPlazoProrroga1, cPlazoProrroga2, cPlazoGarantia FROM Certificado WHERE idObra = :Id AND idBaja IS NULL';
			$this->data = [':Id' => $idObra];

			return $this->executeQuery()->fetchAll(PDO::FETCH_ASSOC);
		}

		public function insertObra(){
			if(!$this->validateFields('insert')){
				return array('Status' => 'Invalid Fields', $_POST);
			}

			$this->name = $this->cleanString($_POST['obra']);
			$this->pagado = (int) 0; //$this->cleanString($_POST['pagado']);
			$this->aPagar = (int) 0; //$this->cleanString($_POST['a_pagar']);
			$this->expediente = $this->cleanString($_POST['expediente']);
			$this->anioExp = $this->cleanString($_POST['anio_expediente']);
			$this->importeEst = $this->cleanString(substr($_POST['importe_est'], 0, strpos($_POST['importe_est'], ',')));
			$this->importeDef = $this->cleanString(substr($_POST['importe_def'], 0, strpos($_POST['importe_def'], ',')));
			$this->imputado = $this->cleanString($_POST['imputado']);
			$this->observaciones = $this->cleanString($_POST['observaciones']);
			$this->unidadEjecutora = $this->cleanString($_POST['u_ejecutora']);
			$this->proveedor = $this->cleanString($_POST['proveedor']);
			$this->proyecto = (!empty($_POST['proyecto'])) ? $this->cleanString($_POST['proyecto']) : NULL;

			$this->importeEst = (strstr($_POST['importe_est'], ',') == '00') ? $this->importeEst : $this->importeEst.str_replace(',','.',strstr($_POST['importe_est'], ','));
			$this->importeDef = (strstr($_POST['importe_def'], ',') == '00') ? $this->importeDef : $this->importeDef.str_replace(',','.',strstr($_POST['importe_def'], ','));

			if(!$this->existingProveedor('ID', $this->proveedor)){
				return array('Status' => 'Unknown Proveedor');
			}

			if(!$this->existingUnidadEjecutora('ID', $this->unidadEjecutora)){
				return array('Status' => 'Unknown UnidadEjecutora');
			}

			if($this->existingObra('NAME', $this->name)){
				return array('Status' => 'Existing Obra Name');
			}

			$this->query = 'INSERT INTO Obra (cNombre, nExpte, nExpteAnio, nEstimado, nDefinitivo, cImputado, nPagado, cObs, idUnidadEjecutora, nSaldoAPagar, idProveedor, idProyecto, idAlta, dAlta) VALUES 
				(:name, :expte, :anioExp, :impEstimado, :impDefinitivo, :imputado, :pagado, :obs, :unidadEjecutora, :aPagar, :proveedor, :proyecto, :user, :fecha)';
			$this->data = [
				':name' => $this->name,
                ':expte' => $this->expediente,
				':anioExp' => $this->anioExp,
				':impEstimado' => (float) $this->importeEst,
				':impDefinitivo' => (float) $this->importeDef,
				':imputado' => $this->imputado,
				':pagado' => $this->pagado,
				':obs' => $this->observaciones,
				':unidadEjecutora' => $this->unidadEjecutora,
				':aPagar' => $this->aPagar,
				':proveedor' => $this->proveedor,
				':proyecto' => $this->proyecto,
				':user' => $_SESSION['ID_USER'],
				':fecha' => $this->fecha,
			];
			
			$result = $this->executeQuery();

			if(!$result){
				return array('Status' => 'Error');
			}

			$this->id = $this->getLastInsertedId();

			$compromisos = $this->addCompromisos($this->id);
			$ordenesPago = $this->addOrdenesPago($this->id);
			$imputaciones = $this->addImputaciones($this->id);

			$success = true;

			return array(
				'Status' => 'Success', 
				'Response' => (object)$this->getObra($this->id), 
				'compromisos' => $compromisos, 
				'ordenesPago' => $ordenesPago, 
				'imputaciones' => $imputaciones
			);
		}

		public function insertDatosAdicionales(){
			$this->id = (int) $_POST['id'];
			
			if(!$this->existingObra('ID', $this->id)){
				return array('Status' => 'Unknown Obra');
			}

			$this->query = 'UPDATE Obra SET idModalidad = :idModalidad, nModalidad  = :nModalidad, nModalidadAnio = :nModalidadAnio,
				idEstado = :idEstado, idTipoObra = :idTipoObra, cPlazo = :cPlazo, nPlazo  = :nPlazo, dApertura = :dApertura,
				dCircuito = :dCircuito, dLlamado = :dLlamado, nLlamado = :nLlamado, nLlamadoAnio = :nLlamadoAnio, dPropuesta = :dPropuesta, 
				nPropuestaFolio = :nPropuestaFolio, cPropuestaHora = :cPropuestaHora, dAdjudicacion = :dAdjudicacion,
				nAdjudicacion = :nAdjudicacion, nAdjudicacionAnio = :nAdjudicacionAnio, dContrato  = :dContrato, nContrato = :nContrato, 
				dInicioObra = :dInicioObra, dFinObra = :dFinObra, dRecepcion = :dRecepcion
				WHERE idObra = :idObra';

			$this->data = [
				':idModalidad' => ($_POST['modalidad_ad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['modalidad_ad'],
				':nModalidad' => $_POST['modnumero_ad'],
				':nModalidadAnio' => ($_POST['anio_modalidad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['anio_modalidad'],
				':idEstado' => ($_POST['estado_ad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['estado_ad'],
				':idTipoObra' => ($_POST['tipoobra_ad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['tipoobra_ad'],
				':cPlazo' =>  $_POST['tipoplazo_ad'],
				':nPlazo' =>  $_POST['plazo_ad'],
				':dApertura' => (empty($_POST['apertura_ad'])) ? NULL : $_POST['apertura_ad'],
				':dCircuito' => (empty($_POST['inicio_ad'])) ? NULL : $_POST['inicio_ad'],
				':dLlamado' => (empty($_POST['decllamado_ad'])) ? NULL : $_POST['decllamado_ad'],
				':nLlamado' => $_POST['nllamado_ad'],
				':nLlamadoAnio' => ($_POST['aniollamado_ad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['aniollamado_ad'],
				':dPropuesta' => (empty($_POST['aperturapr_ad'])) ? NULL : $_POST['aperturapr_ad'],
				':nPropuestaFolio' => $_POST['folio_ad'],
				':cPropuestaHora' => $_POST['hora_ad'],
				':dAdjudicacion' => (empty($_POST['dAdjudicacion'])) ? NULL : $_POST['dAdjudicacion'],
				':nAdjudicacion' => $_POST['nAdjudicacion_ad'],
				':nAdjudicacionAnio' => ($_POST['anioAdjudicacion_ad'] == 'SIN_DEFINIR') ? NULL : (int) $_POST['anioAdjudicacion_ad'],
				':dContrato' => (empty($_POST['contrato_ad'])) ? NULL : $_POST['contrato_ad'],
				':nContrato' => $_POST['cfolio_ad'],
				':dInicioObra' => (empty($_POST['obinicio_ad'])) ? NULL : $_POST['obinicio_ad'],
				':dFinObra' => (empty($_POST['fin_ad'])) ? NULL : $_POST['fin_ad'],
				':dRecepcion' => (empty($_POST['recepcion_ad'])) ? NULL : $_POST['recepcion_ad'],
				':idObra' => $_POST['id']
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => $this->getDatosAdicionales($this->id)) : array('Status' => 'Error');
		}

		public function insertCertificado(){
			$this->id = (int) $_POST['id'];
			$this->certificado = $_POST['certificado'];
			
			if(!$this->existingObra('ID', $this->id)){
				return array('Status' => 'Unknown Obra');
			}

			if($this->existingCertificado('NUMBER', $this->id, $this->certificado)){
				return array('Status' => 'Existing Certificado');
			}

			$this->query = 'INSERT INTO Certificado (idObra, cCertificado, cPeriodo, nImporte, cAmpliacion, dContrato, nAmpliacion, cPlazoProrroga1, nProrroga,
				dProrroga, cPlazoProrroga2, nProrroga2, dProrroga2, dTerminacion, dTerminacion2, nCertificado, cPlazoGarantia, nGarantia, dDevolucion,
				cRetencion, nRetencion, cObs) VALUES (:idObra, :cCertificado, :cPeriodo, :nImporte, :cAmpliacion, :dContrato, :nAmpliacion, :cPlazoProrroga1, :nProrroga,
				:dProrroga, :cPlazoProrroga2, :nProrroga2, :dProrroga2, :dTerminacion, :dTerminacion2, :nCertificado, :cPlazoGarantia, :nGarantia, :dDevolucion,
				:cRetencion, :nRetencion, :cObs)';

			$impCert = $this->cleanString(substr($_POST['importe_cert'], 0, strpos($_POST['importe_cert'], ',')));
			$impCert = (strstr($_POST['importe_cert'], ',') == '00') ? $impCert : $impCert.str_replace(',','.',strstr($_POST['importe_cert'], ','));

			$impCert2 = $this->cleanString(substr($_POST['importe2_cert'], 0, strpos($_POST['importe2_cert'], ',')));
			$impCert2 = (strstr($_POST['importe2_cert'], ',') == '00') ? $impCert2 : $impCert2.str_replace(',','.',strstr($_POST['importe2_cert'], ','));

			$certObra = $this->cleanString(substr($_POST['certobra_cert'], 0, strpos($_POST['certobra_cert'], ',')));
			$certObra = (strstr($_POST['certobra_cert'], ',') == '00') ? $certObra : $certObra.str_replace(',','.',strstr($_POST['certobra_cert'], ','));

			$this->data = [
				':idObra' => $_POST['id'],
				':cCertificado' => $_POST['nro_cert'],
				':cPeriodo' => $_POST['periodo_cert'],
				':nImporte' => (float) $impCert,
				':cAmpliacion' => $_POST['ampliacion_cert'],
				':dContrato' => (empty($_POST['dcontrato_cert'])) ? NULL : $_POST['dcontrato_cert'],
				':nAmpliacion' => (float) $impCert2,
				':cPlazoProrroga1' => $_POST['tipoplazo_cert'],
				':nProrroga' => $_POST['plazo1_cert'],
				':dProrroga' => (empty($_POST['prorroga1_cert'])) ? NULL : $_POST['prorroga1_cert'],
				':cPlazoProrroga2' => $_POST['tipoplazo2_cert'],
				':nProrroga2' => $_POST['plazo2_cert'],
				':dProrroga2' => (empty($_POST['prorroga2_cert'])) ? NULL : $_POST['prorroga2_cert'],
				':dTerminacion' => (empty($_POST['terminacion1_cert'])) ? NULL : $_POST['terminacion1_cert'],
				':dTerminacion2' => (empty($_POST['terminacion2_cert'])) ? NULL : $_POST['terminacion2_cert'],
				':cPlazoGarantia' => $_POST['tipoplazo3_cert'],
				':nCertificado' => (float) $certObra,
				':nGarantia' => $_POST['plazogarantia_cert'],
				':dDevolucion' => (empty($_POST['devolucion_cert'])) ? NULL : $_POST['devolucion_cert'],
				':cRetencion' => $_POST['retencion_cert'],
				':nRetencion' => $_POST['imretencion_cert'],
				':cObs' => $_POST['observaciones_cert']
			];

			// var_dump($this->data);

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => $this->getCertificados($this->id)) : array('Status' => 'Error');
		}

		public function updateCertificados(){
			$this->id = (int) $_POST['id'];
			$this->certificado = $_POST['certificado'];
			
			if(!$this->existingObra('ID', $this->id)){
				return array('Status' => 'Unknown Obra');
			}

			if(!$this->existingCertificado('ID', $this->id, $this->certificado)){
				return array('Status' => 'Unkown Certificado');
			}

			$this->query = 'UPDATE Certificado SET cCertificado = :cCertificado, cPeriodo = :cPeriodo, nImporte = :nImporte, cAmpliacion = :cAmpliacion, 
				dContrato = :dContrato, nAmpliacion = :nAmpliacion, cPlazoProrroga1 = :cPlazoProrroga1, nProrroga = :nProrroga, dProrroga = :dProrroga,
				cPlazoProrroga2 = :cPlazoProrroga2, nProrroga2 = :nProrroga2, dProrroga2 = :dProrroga2, dTerminacion = :dTerminacion, dTerminacion2 = :dTerminacion2,
				nCertificado = :nCertificado, cPlazoGarantia = :cPlazoGarantia, nGarantia = :nGarantia, dDevolucion = :dDevolucion,
				cRetencion = :cRetencion, nRetencion = :nRetencion, cObs = :cObs
				WHERE idCertificado = :certificado';

			$impCert = $this->cleanString(substr($_POST['importe_cert'], 0, strpos($_POST['importe_cert'], ',')));
			$impCert = (strstr($_POST['importe_cert'], ',') == '00') ? $impCert : $impCert.str_replace(',','.',strstr($_POST['importe2_cert'], ','));

			$impCert2 = $this->cleanString(substr($_POST['importe2_cert'], 0, strpos($_POST['importe2_cert'], ',')));
			$impCert2 = (strstr($_POST['importe2_cert'], ',') == '00') ? $impCert2 : $impCert2.str_replace(',','.',strstr($_POST['importe2_cert'], ','));

			$certObra = $this->cleanString(substr($_POST['certobra_cert'], 0, strpos($_POST['certobra_cert'], ',')));
			$certObra = (strstr($_POST['certobra_cert'], ',') == '00') ? $certObra : $certObra.str_replace(',','.',strstr($_POST['certobra_cert'], ','));

			$this->data = [
				':cCertificado' => $_POST['nro_cert'],
				':cPeriodo' => $_POST['periodo_cert'],
				':nImporte' => (float) $impCert,
				':cAmpliacion' => $_POST['ampliacion_cert'],
				':dContrato' => (empty($_POST['dcontrato_cert'])) ? NULL : $_POST['dcontrato_cert'],
				':nAmpliacion' => (float) $impCert2,
				':cPlazoProrroga1' => $_POST['tipoplazo1_cert'],
				':nProrroga' => $_POST['plazo1_cert'],
				':dProrroga' => (empty($_POST['prorroga1_cert'])) ? NULL : $_POST['prorroga1_cert'],
				':cPlazoProrroga2' => $_POST['tipoplazo2_cert'],
				':nProrroga2' => $_POST['plazo2_cert'],
				':dProrroga2' => (empty($_POST['prorroga2_cert'])) ? NULL : $_POST['prorroga2_cert'],
				':dTerminacion' => (empty($_POST['terminacion1_cert'])) ? NULL : $_POST['terminacion1_cert'],
				':dTerminacion2' => (empty($_POST['terminacion2_cert'])) ? NULL : $_POST['terminacion2_cert'],
				':nCertificado' => (float) $certObra,
				':cPlazoGarantia' => $_POST['tipoplazo3_cert'],
				':nGarantia' => $_POST['plazogarantia_cert'],
				':dDevolucion' => (empty($_POST['devolucion_cert'])) ? NULL : $_POST['devolucion_cert'],
				':cRetencion' => $_POST['retencion_cert'],
				':nRetencion' => $_POST['imretencion_cert'],
				':cObs' => $_POST['observaciones_cert'],
				':certificado' => $this->certificado
			];

			return ($this->executeQuery()) ? array('Status' => 'Success', 'Response' => $this->getCertificados($this->id)) : array('Status' => 'Error');
		}
		
		private function addCompromisos($idObra){
			$compromisos = json_decode($_POST['compromisos'], true);
			$response = array();

			foreach ($compromisos as $key => $value) {

				if($value['id'] != null){
					if($value['eliminado'] == null){
						$this->query = 'UPDATE RC SET nNro = :nro, dFecha = :fecha, nImporte = :importe, idModificacion = :user, dModificacion = :fechaAlta
							WHERE idRC = :id';
						$this->data = [
							':nro' => $value['numero'],
							':fecha' => $value['fecha'],
							':importe' => $value['importe'],
							':user' => $_SESSION['ID_USER'],
							':fechaAlta' => $this->fecha,
							':id' => $value['id']
						];
					}else{
						$this->query = 'UPDATE RC SET idBaja = :user, dBaja = :fecha WHERE idRC = :id';
						$this->data = [
							':user' => $_SESSION['ID_USER'],
							':fecha' => $this->fecha,
							':id' => $value['id']
						];
					}
				}else{
					$this->query = 'INSERT INTO RC (idObra,nNro,dFecha,nImporte,idAlta,dAlta) VALUES (:idObra, :nro, :fecha, :importe, :user, :fechaAlta)';
					$this->data = [
						':idObra' => $idObra,
						':nro' => $value['numero'],
						':fecha' => $value['fecha'],
						':importe' => $value['importe'],
						':user' => $_SESSION['ID_USER'],
						':fechaAlta' => $this->fecha
					];
	
				}
				// var_dump($this->data);
				$response[] = ($this->executeQuery()) ? array('status' => 'success', 'numero' => $value['numero']) : array('status' => 'error', 'numero' => $value['numero']);
			}

			return $response;
		}

		private function addOrdenesPago($idObra){
			$ordenesPago = json_decode($_POST['ordenesPago'], true);
			$response = array();
			
			foreach ($ordenesPago as $key => $value) {
				if($value['id'] != null){
					if($value['eliminado'] == null){
						$this->query = 'UPDATE OP SET nNro = :nro, nImporte = :importe, dFecha = :fecha, dPagado = :pagado,
							cOCEA = :ocea, nOceaNumero = :oceaNumero, dOceaFecha = :oceaFecha, idModificacion = :user, dModificacion = :fechaModificado
							WHERE idObra = :idObra AND idOP = :id';
						$this->data = [
							':nro' => $value['numero'],
							':importe' => $value['importe'],
							':fecha' => $value['fecha'],
							':pagado' => ($value['pagado'] == null) ? null : $value ['pagado'],
							':ocea' => $value['ocea'],
							':oceaNumero' => ($value['numeroOcea'] == null) ? null : $value ['numeroOcea'],
							':oceaFecha' => ($value['fechaOcea'] == null) ? null : $value ['fechaOcea'],
							':user' => $_SESSION['ID_USER'],
							':fechaModificado' => $this->fecha,
							':idObra' => $idObra,
							':id' => $value['id']
						];
					}else{
						$this->query = 'UPDATE OP SET idBaja = :user, dBaja = :fecha WHERE idOP = :id';
						$this->data = [
							':user' => $_SESSION['ID_USER'],
							':fecha' => $this->fecha,
							':id' => $value['id']
						];
					}
				}else{
					$this->query = 'INSERT INTO OP (idObra, nNro, nImporte, dFecha, dPagado, cOCEA, nOceaNumero, dOceaFecha, idAlta, dAlta) 
						VALUES (:idObra, :nro, :importe, :fecha, :pagado, :ocea, :oceaNumero, :oceaFecha, :user, :fechaAlta)';
					$this->data = [
						':idObra' => $idObra,
						':nro' => $value['numero'],
						':importe' => $value['importe'],
						':fecha' => $value['fecha'],
						':pagado' => ($value['pagado'] == null) ? null : $value ['pagado'],
						':ocea' => $value['ocea'],
						':oceaNumero' => ($value['numeroOcea'] == null) ? null : $value ['numeroOcea'],
						':oceaFecha' => ($value['fechaOcea'] == null) ? null : $value ['fechaOcea'],
						':user' => $_SESSION['ID_USER'],
						':fechaAlta' => $this->fecha
					];
				}

				$response[] = ($this->executeQuery()) ? array('status' => 'success', 'numero' => $value['numero']) : array('status' => 'error', 'numero' => $value['numero']);
			}

			return $response;
		}

		private function addImputaciones($idObra){
			$imputaciones = json_decode($_POST['imputaciones'], true);
			$response = array();

			foreach ($imputaciones as $key => $value) {
				if($value['id'] != null){
					if($value['eliminado'] == null){
						$this->query = 'UPDATE Imputacion SET idFuente = :fuente, idJurisdiccion = :juris, idObjetoGasto = :gasto, 
							nCP1 = :cp1, nCP2 = :cp2, nCP3 = :cp3, cAfectacion = :afectacion, idModificacion = :user, dModificacion = :fecha
							WHERE idImputacion = :id';
						$this->data = [
							':fuente' => $value['fuente'],
							':juris' => $value['jurisdiccion'],
							':gasto' => $value['gasto'],
							':cp1' => $value['categoria1'],
							':cp2' => $value['categoria2'],
							':cp3' => $value['categoria3'],
							':afectacion' => $value['afectacion'],
							':user' => $_SESSION['ID_USER'],
							':fecha' => $this->fecha,
							':id' => $value['id']
						];
					}else{
						$this->query = 'UPDATE Imputacion SET idBaja = :user, dBaja = :fecha WHERE idImputacion = :id';
						$this->data = [
							':user' => $_SESSION['ID_USER'],
							':fecha' => $this->fecha,
							':id' => $value['id']
						];
					}
				}else{
					$this->query = 'INSERT INTO Imputacion (idObra, idFuente, idJurisdiccion, idObjetoGasto, nCP1, nCP2, nCP3, cAfectacion, idAlta, dAlta) 
						VALUES (:idObra, :fuente, :juris, :gasto, :cp1, :cp2, :cp3, :afectacion, :user, :fechaAlta)';
					$this->data = [
						':idObra' => $idObra,
						':fuente' => $value['fuente'],
						':juris' => $value['jurisdiccion'],
						':gasto' => $value['gasto'],
						':cp1' => $value['categoria1'],
						':cp2' => $value['categoria2'],
						':cp3' => $value['categoria3'],
						':afectacion' => $value['afectacion'],
						':user' => $_SESSION['ID_USER'],
						':fechaAlta' => $this->fecha
					];
				}

				$response[] = ($this->executeQuery()) ? array('status' => 'success', 'afectacion' => $value['afectacion']) : array('status' => 'error', 'afectacion' => $value['afectacion']);
			}

			return $response;
		}

		public function updateObra(){
			if(!$this->validateFields('update')){
				return array('Status' => 'Invalid Fields', $_POST);
			}

			$this->id = (int) $_POST['id'];

			if(!$this->existingObra('ID', $this->id)){
				return array('Status' => 'Unknown Obra');
			}

			$this->name = $this->cleanString($_POST['obra']);
			$this->pagado = (int) 0; //$this->cleanString($_POST['pagado']);
			$this->aPagar = (int) 0; //$this->cleanString($_POST['a_pagar']);
			$this->expediente = $this->cleanString($_POST['expediente']);
			$this->anioExp = $this->cleanString($_POST['anio_expediente']);
			$this->importeEst = $this->cleanString(substr($_POST['importe_est'], 0, strpos($_POST['importe_est'], ',')));
			$this->importeDef = $this->cleanString(substr($_POST['importe_def'], 0, strpos($_POST['importe_def'], ',')));
			$this->imputado = $this->cleanString($_POST['imputado']);
			$this->observaciones = $this->cleanString($_POST['observaciones']);
			$this->unidadEjecutora = $this->cleanString($_POST['u_ejecutora']);
			$this->proveedor = $this->cleanString($_POST['proveedor']);
			$this->proyecto = (!empty($_POST['proyecto'])) ? $this->cleanString($_POST['proyecto']) : NULL;

			$this->importeEst = (strstr($_POST['importe_est'], ',') == '00') ? $this->importeEst : $this->importeEst.str_replace(',','.',strstr($_POST['importe_est'], ','));
			$this->importeDef = (strstr($_POST['importe_def'], ',') == '00') ? $this->importeDef : $this->importeDef.str_replace(',','.',strstr($_POST['importe_def'], ','));
			
			// return [$this->importeDef, $this->importeEst];

			if(!$this->existingProveedor('ID', $this->proveedor)){
				return array('Status' => 'Unknown Proveedor');
			}

			if(!$this->existingUnidadEjecutora('ID', $this->unidadEjecutora)){
				return array('Status' => 'Unknown UnidadEjecutora');
			}

			$this->query = 'UPDATE Obra SET cNombre = :name, nExpte = :expte, nExpteAnio = :anioExp, nEstimado = :impEstimado, 
				nDefinitivo = :impDefinitivo, cImputado = :imputado, nPagado = :pagado, cObs = :obs, idUnidadEjecutora = :unidadEjecutora,
				nSaldoAPagar = :aPagar, idProveedor = :proveedor, idProyecto = :proyecto, idAlta = :user, dAlta = :fecha
				WHERE idObra = :id';
			$this->data = [
				':name' => $this->name,
                ':expte' => $this->expediente,
				':anioExp' => $this->anioExp,
				':impEstimado' => $this->importeEst,
				':impDefinitivo' => $this->importeDef,
				':imputado' => $this->imputado,
				':pagado' => $this->pagado,
				':obs' => $this->observaciones,
				':unidadEjecutora' => $this->unidadEjecutora,
				':aPagar' => $this->aPagar,
				':proveedor' => $this->proveedor,
				':proyecto' => $this->proyecto,
				':user' => $_SESSION['ID_USER'],
				':fecha' => $this->fecha,
				':id' => $this->id
			];
			
			$result = $this->executeQuery();

			if(!$result){
				return array('Status' => 'Error');
			}

			$compromisos = $this->addCompromisos($this->id);
			$ordenesPago = $this->addOrdenesPago($this->id);
			$imputaciones = $this->addImputaciones($this->id);

			$success = true;

			return array(
				'Status' => 'Success', 
				'Response' => (object)$this->getObra($this->id), 
				'compromisos' => $compromisos, 
				'ordenesPago' => $ordenesPago, 
				'imputaciones' => $imputaciones,
				'hola' => $this->importeEst
			);
		}

		public function deleteObra(){
			if(!$this->validateFields('delete')){
				return array('Status' => 'Invalid Fields');
			}

			$this->id = $this->cleanString($_POST['id']);

			if(!$this->existingObra('ID', $this->id)){
				return array('Status' => 'Unknown Proveedor');
			}

			$this->query = 'UPDATE Obra SET idBaja = :User, dBaja = :Fecha WHERE idObra = :Id';
			$this->data = [
				':User' => $_SESSION['ID_USER'],
				':Fecha' => $this->fecha,
				':Id' => $this->id
			];

			return ($this->executeQuery()) ? array('Status' => 'Success') : array('Status' => 'Error');
		}

		private function existingUnidadEjecutora($type = 'ID', $search){
			switch ($type) {
				case 'CODE':$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE cCodigo = :search AND idBaja IS NULL';break;
				case 'NAME':$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE cNombre = :search AND idBaja IS NULL';break;
				case 'ID':$this->query = 'SELECT COUNT(idUnidadEjecutora) FROM UnidadEjecutora WHERE idUnidadEjecutora = :search AND idBaja IS NULL';break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingProveedor($type = 'ID', $search){
			switch ($type) {
				case 'CODE':$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE cCodigo = :search AND idBaja IS NULL';break;
				case 'CUIT':$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE nCuit = :search AND idBaja IS NULL';break;
				case 'ID':$this->query = 'SELECT COUNT(idProveedor) FROM Proveedor WHERE idProveedor = :search AND idBaja IS NULL';break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingObra($type = 'ID', $search){
			switch ($type) {
				case 'NAME':$this->query = 'SELECT COUNT(idObra) FROM Obra WHERE cNombre = :search AND idBaja IS NULL';break;
				case 'ID':$this->query = 'SELECT COUNT(idObra) FROM Obra WHERE idObra = :search AND idBaja IS NULL';break;
			}

			$this->data = [':search' => $search];

			return ($this->searchRecords() > 0) ? true : false;
		}

		private function existingCertificado($type = 'NUMBER', $search, $search2){
			switch ($type) {
				case 'NUMBER':$this->query = 'SELECT COUNT(idCertificado) FROM Certificado WHERE idObra = :search AND cCertificado = :search2 AND idBaja IS NULL';break;
				case 'ID':$this->query = 'SELECT COUNT(idCertificado) FROM Certificado WHERE idObra = :search AND idCertificado = :search2 AND idBaja IS NULL';break;
			}

			$this->data = [':search' => $search, ':search2' => $search2];

			return ($this->searchRecords() > 0) ? true : false;
		}

		public function validateFields($call){
			$valid = false;
			switch ($call) {
				case 'insert':
					if(isset($_POST['anio_expediente'],$_POST['expediente'],$_POST['importe_def'],$_POST['importe_est'],$_POST['imputado'],$_POST['obra'],$_POST['observaciones'],$_POST['proveedor'],$_POST['u_ejecutora'])){
						if(!$this->validateEmptyPost(array('id','observaciones','proyecto'))){
							$valid = true;
						}
					}
				break;
				case 'update':
					if(isset($_POST['anio_expediente'],$_POST['expediente'],$_POST['importe_def'],$_POST['importe_est'],$_POST['imputado'],$_POST['obra'],$_POST['proveedor'],$_POST['u_ejecutora'], $_POST['id'])){
						if(!$this->validateEmptyPost(array('observaciones','proyecto')) && intval(trim($_POST['id'])) != 0){
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