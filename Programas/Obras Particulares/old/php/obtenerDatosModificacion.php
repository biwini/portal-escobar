<?php

	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				require("conexion.php");
				//Variable para el acceso a el formulario de modificacion.
				$_SESSION['MODIFICACION'] = true;
				$_SESSION['ELIMINAR'] = true;
				//Variables de la liquidacion.
				$id_liquidacion = $_POST['id_registro'];
				$id_cliente = 0;
				$cliente = $_POST['cliente'];
				$fecha = $_POST['fecha'];
				$zona = $_POST['zona'];
				$id_tipo = 0;
				$total = $_POST['total'];
				$tipoConsulta = $_POST['boton'];
				//Variables de la tabla nomenclatura.
				$id_nomenclatura = 0;
				$array_nomenclatura = [];
				//Ingreso de las varibales de session globales.
				if($tipoConsulta == "Ver"){
					$_SESSION['TipoConsulta'] = $tipoConsulta;
				}else{
					$_SESSION['TipoConsulta'] = null;
				}
				$_SESSION['FechaLiquidacion'] = $fecha;
				$_SESSION['NombreCliente'] = $cliente;
				$_SESSION['ANTIGUO_NOMBRE_CLIENTE'] = $cliente;
				$_SESSION['ZONIFICACION'] = $zona;
				$_SESSION['ANTIGUA_ZONIFICACION'] = $zona;
				$_SESSION['TOTAL'] = $total;
				$_SESSION['ANTIGUO_TOTAL'] =$total;
				//Obtengo los datos de la liquidacion.
				$obtenerRegistro = $conn->query("SELECT * FROM liquidacion WHERE id_liquidacion = '".$id_liquidacion."'");
				while($id = $obtenerRegistro->fetch()){
					$id_nomenclatura = $id['id_nomenclatura'];
					$id_tipo = $id['id_tipo_liquidacion'];
					$id_cliente = $id['id_cliente'];
				}
				//Obtengo los datos de la nomenclatura.
				$obtenerDatosNomenclatura = $conn->query("SELECT * FROM nomenclatura WHERE id_nomenclatura = '".$id_nomenclatura."'");
				while($datos = $obtenerDatosNomenclatura->fetch()){
					$array_nomenclatura = [$datos['manzana'],$datos['circ'],$datos['quinta'],$datos['seccion'],$datos['fraccion'],$datos['chacra'],$datos['parcela'],$datos['uf'],$datos['partida']];
				}
				//Array de session para nomenclatura.
				$_SESSION['ARRAY_NOMENCLATURA'] = $array_nomenclatura;
				$_SESSION['ANTIGUO_ARRAY_NOMEN'] = $array_nomenclatura;

				function obtenerDatosLiquidacionNormal($id_liquidacion,$conn){
					$descuento = $_POST['descuento'];
					//Variable para contar las filas.
					$fila_contrato = 1;
					$fila_multas = 1;
					//Array para almacenar los datos de las filas de la tabla contrato colegio.
					$array_nuevo = [];
					$array_demoler = [];
					$array_declarar = [];
					$array_Fila_4 = [];
					$array_Fila_5 = [];
					$array_Fila_6 = [];
					$array_ContratoColegio = [];
					$array_id_fila_contrato = [];
					//Arrays para almacenar los datos de las filas de la tabla multas.
					$array_fos= [];
					$array_fot = [];
					$array_retiros = [];
					$array_dencidad = [];
					$array_dto = [];
					$array_multas = [];
					$array_id_fila_multa = [];
					//Obtencion de datos de la tabla contrato de colegio.
					$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_1 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosContrato->fetch()){

						switch ($fila_contrato) {
							case 1: 
								$array_nuevo = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
							case 2: 
								$array_demoler = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
							case 3: 
								$array_declarar = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
							case 4: 
								$array_Fila_4 = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
							case 5: 
								$array_Fila_5 = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
							case 6: 
								$array_Fila_6 = [$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['tipo_monto']];
								$array_id_fila_contrato[] = $datos['id_contrato_1'];
							break;
						}
						$fila_contrato++;
					}
					$array_ContratoColegio = [$array_nuevo,$array_demoler,$array_declarar,$array_Fila_4,$array_Fila_5,$array_Fila_6];
					//Obtencion de datos de la tabla multas.
					$datosMultas = $conn->query("SELECT * FROM multa_tipo_1 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosMultas->fetch()){
						switch ($fila_multas) {
							case 1: 
								$array_fos = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_1'];
							break;
							case 2: 
								$array_fot = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_1'];
							break;
							case 3: 
								$array_retiros = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_1'];
							break;
							case 4: 
								$array_dencidad = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_1'];
							break;
							case 5: 
								$array_dto = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_1'];
							break;
						}
						$fila_multas++;
					}
					$array_multas = [$array_fos,$array_fot,$array_retiros,$array_dencidad,$array_dto];
					//Carga de arrays a las variables de session.
					$_SESSION['ARRAY_CONTRATO'] = $array_ContratoColegio;
					$_SESSION['ARRAY_ID_CONTRATO'] = $array_id_fila_contrato;

					$_SESSION['ARRAY_MULTAS'] = $array_multas;
					$_SESSION['ARRAY_ID_MULTAS'] = $array_id_fila_multa;
					
					$_SESSION['DESCUENTO'] = $descuento;
					$_SESSION['ANTIGUO_DESCUENTO'] = $descuento;
				}
				//Funcion para Obtener los datos de la liquidacion moratoria.
				function obtenerDatosLiquidacionMoratoria($id_liquidacion,$conn){
					$descuento = $_POST['descuento'];
					//Variable para contar las filas.
					$fila_contrato = 1;
					$fila_multas = 1;
					//Array para almacenar los datos de las filas de la tabla contrato colegio.
					$array_fila_1 = [];
					$array_fila_2 = [];
					$array_fila_3 = [];
					$array_fila_4 = [];
					$array_fila_5 = [];
					$array_ContratoColegio = [];
					$array_id_fila_contrato = [];
					//Arrays para almacenar los datos de las filas de la tabla multas.
					$array_fos= [];
					$array_fot = [];
					$array_retiros = [];
					$array_dencidad = [];
					$array_dto = [];
					$array_multas = [];
					$array_id_fila_multa = [];
					//Obtencion de datos de la tabla contrato de colegio.
					$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_2 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosContrato->fetch()){
						switch ($fila_contrato) {
							case 1: 
								$array_fila_1 = [$datos['monto_obra'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_contrato[] = $datos['id_contrato_2'];
							break;
							case 2: 
								$array_fila_2 = [$datos['monto_obra'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_contrato[] = $datos['id_contrato_2'];
							break;
							case 3: 
								$array_fila_3 = [$datos['monto_obra'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_contrato[] = $datos['id_contrato_2'];
							break;
							case 4: 
								$array_fila_4 = [$datos['monto_obra'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_contrato[] = $datos['id_contrato_2'];
							break;
							case 5: 
								$array_fila_5 = [$datos['monto_obra'],$datos['coef'],$datos['recargo'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_contrato[] = $datos['id_contrato_2'];
							break;
						}
						$fila_contrato++;
					}
					$array_ContratoColegio = [$array_fila_1,$array_fila_2,$array_fila_3,$array_fila_4,$array_fila_5];
					//Obtencion de datos de la tabla multas.
					$datosMultas = $conn->query("SELECT * FROM multa_tipo_2 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosMultas->fetch()){
						switch ($fila_multas) {
							case 1: 
								$array_fos = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_2'];
							break;
							case 2: 
								$array_fot = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_2'];
							break;
							case 3: 
								$array_retiros = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_2'];
							break;
							case 4: 
								$array_dencidad = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_2'];
							break;
							case 5: 
								$array_dto = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total'],$datos['porcen_condonacion'],$datos['total_condonacion']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_2'];
							break;
						}
						$fila_multas++;
					}
					$array_multas = [$array_fos,$array_fot,$array_retiros,$array_dencidad,$array_dto];
					//Carga de arrays a las variables de session.
					$_SESSION['ARRAY_CONTRATO'] = $array_ContratoColegio;
					$_SESSION['ARRAY_ID_CONTRATO'] = $array_id_fila_contrato;

					$_SESSION['ARRAY_MULTAS'] = $array_multas;
					$_SESSION['ARRAY_ID_MULTAS'] = $array_id_fila_multa;

					$_SESSION['DESCUENTO'] = $descuento;
					$_SESSION['ANTIGUO_DESCUENTO'] = $descuento;
			}
			function obtenerDatosLiquidacionIncencio($id_liquidacion,$conn){
					$array_LiquidacionIncendio = [];
					$id_liq_incendio = 0;

					//Obtencion de datos de la tabla contrato de colegio.
					$datosIncendio = $conn->query("SELECT * FROM liq_incendio WHERE id_liquidacion = '".$id_liquidacion."'") or die ("asdkmpaosmdasdioansdas");
					while($datos = $datosIncendio->fetch()){
						$array_LiquidacionIncendio = [$datos['destino'],$datos['m2'],$datos['cap_ix'],$datos['total']];
						$id_liq_incendio = $datos['id_liq_incendio'];
					}
					$_SESSION['ANTIGUO_DESCUENTO'] = 0;
					$_SESSION['ARRAY_LIQ_INCENDIO'] = $array_LiquidacionIncendio;
					$_SESSION['ID_LIQ_INCENDIO'] = $id_liq_incendio;
				}
				function obtenerDatosLiquidacionElectromecanico($id_liquidacion,$conn){
					//Variable para contar las filas.
					$fila_liquidacion = 1;
					//Array para almacenar los datos de las filas de la tabla liquidacion.
					$hasta_50 = [];
					$exedente_50 = [];
					$hasta_25 = [];
					$exedente_25 = [];
					$array_id_fila_liquidacion = [];
					//Obtencion de datos de la tabla contrato de colegio.
					$datosContrato = $conn->query("SELECT * FROM liq_electromecanico WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosContrato->fetch()){
						switch ($fila_liquidacion) {
							case 1: 
								$hasta_50 = [$datos['m2'],$datos['cap_ix'],$datos['total']];
								$array_id_fila_liquidacion[] = $datos['id_liq_electromecanico'];
							break;
							case 2: 
								$exedente_50 = [$datos['m2'],$datos['cap_ix'],$datos['total']];
								$array_id_fila_liquidacion[] = $datos['id_liq_electromecanico'];
							break;
							case 3: 
								$hasta_25 = [$datos['m2'],$datos['cap_ix'],$datos['total']];
								$array_id_fila_liquidacion[] = $datos['id_liq_electromecanico'];
							break;
							case 4: 
								$exedente_25 = [$datos['m2'],$datos['cap_ix'],$datos['total']];
								$array_id_fila_liquidacion[] = $datos['id_liq_electromecanico'];
							break;
						}
						$fila_liquidacion++;
					}
					$array_LiquidacionElectromecanico = [$hasta_50,$exedente_50,$hasta_25,$exedente_25];

					$_SESSION['ARRAY_LIQ_ELECTRO'] = $array_LiquidacionElectromecanico;
					$_SESSION['ARRAY_ID_ELECTRO'] = $array_id_fila_liquidacion;
				}
				function obtenerDatosLiquidacionDemolicion($id_liquidacion,$conn){
					$descuento = $_POST['descuento'];
					$derecho_demolicion = [];
					$id_demolicion;

					$datosDemolicion = $conn->query("SELECT * FROM derecho_demolicion WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosDemolicion->fetch()){
						$derecho_demolicion = [$datos['m2'],$datos['($xm2)'],$datos['total']];
						$id_demolicion = $datos['id_derecho'];
					}

					$_SESSION['ARRAY_DEMOLICION'] = $derecho_demolicion;
					$_SESSION['ID_DEMOLICION'] = $id_demolicion;
					$_SESSION['DESCUENTO'] = $descuento;
					$_SESSION['ANTIGUO_DESCUENTO'] = $descuento;
				}
				function obtenerDatosLiquidacionCarteles($id_liquidacion,$conn){
					$array_liquidacion_carteles = [];
					$id_liq_carteles = 0;

					$datosCarteles = $conn->query("SELECT * FROM liq_carteles WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosCarteles->fetch()){
						$array_liquidacion_carteles = [$datos['tipo'],$datos['monto'],$datos['coef'],$datos['recargo'],$datos['total']];
						$id_liq_carteles = $datos['id_liq_carteles'];
					}
					$_SESSION['ARRAY_CARTELES'] = $array_liquidacion_carteles;
					$_SESSION['ID_CARTELES'] = $id_liq_carteles;
				}
				function obtenerDatosLiquidacionArt126($id_liquidacion,$conn){
					//Variable para contar las filas.
					$fila_contrato = 1;
					$fila_multas = 1;
					//Array para almacenar los datos de las filas de la tabla contrato colegio.
					$array_cubierto = [];
					$array_semicubierto = [];
					$array_pileta = [];
					$array_declarar = [];
					$array_ContratoColegio = [];
					$array_id_fila_contrato = [];
					//Arrays para almacenar los datos de las filas de la tabla multas.
					$array_fos= [];
					$array_fot = [];
					$array_retiros = [];
					$array_dencidad = [];
					$array_dto = [];
					$array_multas = [];
					$array_id_fila_multa = [];
					//Obtencion de datos de la tabla contrato de colegio.
					$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_7 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosContrato->fetch()){
						switch ($fila_contrato) {
							case 1: 
								$array_cubierto = [$datos['m2_monto'],$datos['coef'],$datos['ureferencial_recargo'],$datos['total']];
								$array_id_fila_contrato[] = $datos['id_contrato_tipo_7'];
							break;
							case 2: 
								$array_semicubierto = [$datos['m2_monto'],$datos['coef'],$datos['ureferencial_recargo'],$datos['total']];
								$array_id_fila_contrato[] = $datos['id_contrato_tipo_7'];
							break;
							case 3: 
								$array_pileta = [$datos['m2_monto'],$datos['coef'],$datos['ureferencial_recargo'],$datos['total']];
								$array_id_fila_contrato[] = $datos['id_contrato_tipo_7'];
							break;
							case 4: 
								$array_declarar = [$datos['m2_monto'],$datos['coef'],$datos['ureferencial_recargo'],$datos['total']];
								$array_id_fila_contrato[] = $datos['id_contrato_tipo_7'];
							break;
						}
						$fila_contrato++;
					}
					$array_ContratoColegio = [$array_cubierto,$array_semicubierto,$array_pileta,$array_declarar];
					//Obtencion de datos de la tabla multas.
					$datosMultas = $conn->query("SELECT * FROM multa_tipo_7 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosMultas->fetch()){
						switch ($fila_multas) {
							case 1: 
								$array_fos = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_7'];
							break;
							case 2: 
								$array_fot = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_7'];
							break;
							case 3: 
								$array_retiros = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_7'];
							break;
							case 4: 
								$array_dencidad = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_7'];
							break;
							case 5: 
								$array_dto = [$datos['m2'],$datos['cant'],$datos['smmunicipal'],$datos['porcentaje'],$datos['total']];
								$array_id_fila_multa[] = $datos['id_multa_tipo_7'];
							break;
						}
						$fila_multas++;
					}
					$array_multas = [$array_fos,$array_fot,$array_retiros,$array_dencidad,$array_dto];
					//Carga de arrays a las variables de session.
					$_SESSION['ARRAY_CONTRATO'] = $array_ContratoColegio;
					$_SESSION['ARRAY_ID_CONTRATO'] = $array_id_fila_contrato;

					$_SESSION['ARRAY_MULTAS'] = $array_multas;
					$_SESSION['ARRAY_ID_MULTAS'] = $array_id_fila_multa;

				}
				function obtenerDatosLiquidacionArt13($id_liquidacion,$conn){
					//Variable para contar las filas.
					$fila_contrato = 1;
					//Array para almacenar los datos de las filas de la tabla contrato colegio.
					$id_filaContrato1 = 0;
					$array_contrato_parte1 = [];
					$array_contrato_parte2_1 = [];
					$array_contrato_parte2_2 = [];
					$array_ContratoColegio = [];
					$array_id_fila_contrato2 = [];

					//Obtencion de datos de la tabla contrato de colegio.
					$datosContratoParte1 = $conn->query("SELECT * FROM contra_colegio_tipo_8_parte1 WHERE id_liquidacion = '".$id_liquidacion."'");
					while($datos = $datosContratoParte1->fetch()){
						$array_contrato_parte1 = [$datos['destino'],$datos['monto'],$datos['capix'],$datos['total']];
						$id_filaContrato1 = $datos['id_contrato_tipo_8'];
					}
					$datosContratoParte2 = $conn->query("SELECT * FROM contra_colegio_tipo_8_parte2 WHERE id_contrato_parte1 = '".$id_filaContrato1."'");
					while($datos = $datosContratoParte2->fetch()){
						switch ($fila_contrato) {
							case 1:
								$array_contrato_parte2_1 = [$datos['m2'],$datos['coef'],$datos['ureferencial'],$datos['total']];
							break;
							case 2:
								$array_contrato_parte2_2 = [$datos['m2'],$datos['coef'],$datos['ureferencial'],$datos['total']];
							break;
						}
						$array_id_fila_contrato2[] = $datos['id_contrato_tipo_8_parte2'];
						$fila_contrato++;
					}
					$array_ContratoColegio = [$array_contrato_parte1,$array_contrato_parte2_1,$array_contrato_parte2_2];

					//Carga de arrays a las variables de session.
					$_SESSION['ARRAY_CONTRATO'] = $array_ContratoColegio;
					$_SESSION['ID_FILA_CONTRATO1'] = $id_filaContrato1;
					$_SESSION['ARRAY_ID_CONTRATO2'] = $array_id_fila_contrato2;
				}

				$_SESSION["ID_LIQUIDACION"] = $id_liquidacion;
				$_SESSION["ID_NOMENCLATURA"] = $id_nomenclatura;
				$_SESSION["ID_CLIENTE"] = $id_cliente;
				switch ($id_tipo) {
					case 1: obtenerDatosLiquidacionNormal($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacion";break;
					case 2: obtenerDatosLiquidacionMoratoria($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionMoratoria";break;
					case 3: obtenerDatosLiquidacionIncencio($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionIncendio";break;
					case 4: obtenerDatosLiquidacionElectromecanico($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionElectromecanico";break;
					case 5: obtenerDatosLiquidacionDemolicion($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionDemolicion";break;
					case 6: obtenerDatosLiquidacionCarteles($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionCarteles";break;
					case 7: obtenerDatosLiquidacionArt126($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionArt126";break;
					case 8: obtenerDatosLiquidacionArt13($id_liquidacion,$conn); $_SESSION['ID_PAGINA'] = "ModificarLiquidacionArt13";break;
				}
			}
			else{
				header("location: ../login.php");
			}
		}
		else{
			header("location: ../login.php");
		}
	}

?>