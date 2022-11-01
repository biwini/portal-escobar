<?php	
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				//Verifico si es una modificacion.
				if(isset($_SESSION["MODIFICACION"])){

					require("conexion.php");
					//Obtengo los valores de las variables de session.
					$id_liquidacion = $_SESSION['ID_LIQUIDACION'];
					$id_nomenclatura = $_SESSION['ID_NOMENCLATURA'];
					$id_cliente = $_SESSION['ID_CLIENTE'];

					$fecha = $_SESSION['FechaLiquidacion'];
					$nombre = $_SESSION['NombreCliente'];
					$array_nomenclatura = $_SESSION['ARRAY_NOMENCLATURA'];

					//Variable para almacenar los valores del total y el descuento.
					$totalAbonar = $_POST['totalAbonar'];
					$descuento = 0;
					// Recibo los datos que envia Ajax.
					$id_pag = $_POST['idPag'];

					switch ($id_pag) {
						case 'ModificarLiquidacion':

							$array_id_contrato = $_SESSION['ARRAY_ID_CONTRATO'];
							$array_id_multas = $_SESSION['ARRAY_ID_MULTAS'];						
							$arrayContrato = json_decode($_POST['arrayContrato']);
							$arrayMultas = json_decode($_POST['arrayMultas']);
							$descuento = $_POST['descuento'];
							$totalAbonar = $_POST['totalAbonar'];

							$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_1 WHERE id_liquidacion = '".$id_liquidacion."'");

							//Varible para almacenar el contador del array y el string de la informacion para la auditoria.
							$stringInformacion = "Contrato : ";
							$idContra = 0;

							while($datos = $datosContrato->fetch()){

								$conn->query("UPDATE contra_colegio_tipo_1 SET tipo_monto = '".$arrayContrato[$idContra][4]."', monto ='".$arrayContrato[$idContra][0]."',coef ='".$arrayContrato[$idContra][1]."',recargo ='".$arrayContrato[$idContra][2]."',total ='".$arrayContrato[$idContra][3]."' WHERE id_contrato_1 ='".$array_id_contrato[$idContra]."'");

								$stringInformacion = $stringInformacion."total ".$datos['tipo_monto']." = ".$datos['total']." => ".$arrayContrato[$idContra][3]." ";
								$idContra++;
							}

							$datosmultas = $conn->query("SELECT * FROM multa_tipo_1 WHERE id_liquidacion = '".$id_liquidacion."'");

							//Variable para almacenar el contador del array.
							$idMulta = 0;
							$stringInformacion = $stringInformacion." Multa : ";

							while($datos = $datosmultas->fetch()){
								$conn->query("UPDATE multa_tipo_1 SET m2 ='".$arrayMultas[$idMulta][0]."',cant ='".$arrayMultas[$idMulta][1]."',smmunicipal ='".$arrayMultas[$idMulta][2]."',porcentaje ='".$arrayMultas[$idMulta][3]."',total ='".$arrayMultas[$idMulta][4]."' WHERE id_multa_tipo_1 ='".$array_id_multas[$idMulta]."'") or die("PROBLEMAS EN EL UPDATE DE MULTAS LIQUIDACION NORMAL");

								$stringInformacion = $stringInformacion."total ".$datos['multa']." = ".$datos['total']." => ".$arrayMultas[$idMulta][4]." ";
								$idMulta++;
							}

							if($datosContrato && $datosmultas){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['ID_USER'];

				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$stringInformacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						case 'ModificarLiquidacionMoratoria':
							$totalCondonacionMulta = $_POST['totalCondonacionMulta'];
							$totalCondonacionContraCol = $_POST['totalCondonacionContraCol'];
							$porcenCondonacion = $_POST['porcentaje'];

							$array_id_contrato = $_SESSION['ARRAY_ID_CONTRATO'];
							$array_id_multas = $_SESSION['ARRAY_ID_MULTAS'];	

							$arrayContrato = json_decode($_POST['arrayContrato']);
							$arrayMultas = json_decode($_POST['arrayMultas']);

							$descuento = $_POST['descuento'];
							$totalAbonar = $_POST['totalAbonar'];

							//Variable para guardar la informacion a cambiar.
							$informacion = "Contrato : ";

							$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_2 WHERE id_liquidacion = '".$id_liquidacion."'");
							//Varible para almacenar el contador del array.
							$idContra = 0;
							while($datos = $datosContrato->fetch()){
								
								$conn->query("UPDATE contra_colegio_tipo_2 SET monto_obra ='".$arrayContrato[$idContra][0]."',coef ='".$arrayContrato[$idContra][1]."',recargo ='".$arrayContrato[$idContra][2]."',total ='".$arrayContrato[$idContra][3]."',porcen_condonacion ='".$porcenCondonacion."',total_condonacion ='".$arrayContrato[$idContra][4]."' WHERE id_contrato_2 ='".$array_id_contrato[$idContra]."'") or die ("error");

								$informacion = $informacion."total ".$datos['fila']." = ".$datos['total']." => ".$arrayContrato[$idContra][4];
								$idContra++;
							}
							$datosmultas = $conn->query("SELECT * FROM multa_tipo_2 WHERE id_liquidacion = '".$id_liquidacion."'");
							//Varia para almacenar el contador del array.
							$idMulta = 0;
							$informacion = $informacion." Multa : ";
							while($datos = $datosmultas->fetch()){

								$conn->query("UPDATE multa_tipo_2 SET m2 ='".$arrayMultas[$idMulta][0]."',cant ='".$arrayMultas[$idMulta][1]."',smmunicipal ='".$arrayMultas[$idMulta][2]."',porcentaje ='".$arrayMultas[$idMulta][3]."',total ='".$arrayMultas[$idMulta][4]."',porcen_condonacion ='".$porcenCondonacion."',total_condonacion ='".$arrayMultas[$idMulta][5]."' WHERE id_multa_tipo_2 ='".$array_id_multas[$idMulta]."'") or die("PROBLEMAS EN EL UPDATE DE MULTAS LIQUIDACION MORATORIA");

								$informacion = $informacion."total ".$datos['tipo']." = ".$datos['total']." => ".$arrayMultas[$idMulta][5];
								$idMulta++;
							}

							if($datosContrato && $datosmultas){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];
				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						case 'ModificarLiquidacionArt126':
							$array_id_contrato = $_SESSION['ARRAY_ID_CONTRATO'];
							$array_id_multas = $_SESSION['ARRAY_ID_MULTAS'];						
							$arrayContrato = json_decode($_POST['arrayContrato']);
							$arrayMultas = json_decode($_POST['arrayMultas']);
							$totalAbonar = $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "Contrato : ";
							$datosContrato = $conn->query("SELECT * FROM contra_colegio_tipo_7 WHERE id_liquidacion = '".$id_liquidacion."'");
							//Varible para almacenar el contador del array.
							$idContra = 0;
							while($datos = $datosContrato->fetch()){
								$conn->query("UPDATE contra_colegio_tipo_7 SET m2_monto ='".$arrayContrato[$idContra][0]."',coef ='".$arrayContrato[$idContra][1]."',ureferencial_recargo ='".$arrayContrato[$idContra][2]."',total ='".$arrayContrato[$idContra][3]."' WHERE id_contrato_tipo_7 ='".$array_id_contrato[$idContra]."'");

								$informacion = $informacion."total ".$datos['destino']." = ".$datos['total']." => ".$arrayContrato[$idContra][3];
								$idContra++;
							}
							$datosmultas = $conn->query("SELECT * FROM multa_tipo_7 WHERE id_liquidacion = '".$id_liquidacion."'") or die("PROBLEMAS EN EL SELECT DE MULTAS LIQUIDACION ART 126");;
							//Varia para almacenar el contador del array.
							$idMulta = 0;
							$stringInformacion = $stringInformacion." Multa : ";
							while($datos = $datosmultas->fetch()){
								$conn->query("UPDATE multa_tipo_7 SET m2 ='".$arrayMultas[$idMulta][0]."',cant ='".$arrayMultas[$idMulta][1]."',smmunicipal ='".$arrayMultas[$idMulta][2]."',porcentaje ='".$arrayMultas[$idMulta][3]."',total ='".$arrayMultas[$idMulta][4]."' WHERE id_multa_tipo_7 ='".$array_id_multas[$idMulta]."'") or die("PROBLEMAS EN EL UPDATE DE MULTAS LIQUIDACION ART 126");
								
								$informacion = $informacion." total ".$datos['multa']." = ".$datos['total']." => ".$arrayMultas[$idMulta][4];
								$idMulta++;
							}
							
							if($datosContrato && $datosmultas){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];

				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						//Liquidacion Electromecanico.
						case 'ModificarLiquidacionElectromecanico':
							$array_id_electro = $_SESSION['ARRAY_ID_ELECTRO'];					
							$arrayLiquidacionElectromecanico = json_decode($_POST['arrayLiquidacionElectromecanico']);
							$totalAbonar = $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "Contrato : ";

							$datosElectromecanico = $conn->query("SELECT * FROM liq_electromecanico WHERE id_liquidacion = '".$id_liquidacion."'");
							//Varible para almacenar el contador del array.
							$fila = 0;
							while($datos = $datosElectromecanico->fetch()){
								$conn->query("UPDATE liq_electromecanico SET m2 ='".$arrayLiquidacionElectromecanico[$fila][0]."',cap_ix ='".$arrayLiquidacionElectromecanico[$fila][1]."',total ='".$arrayLiquidacionElectromecanico[$fila][2]."' WHERE id_liq_electromecanico ='".$array_id_electro[$fila]."'");

								$informacion = $informacion."total ".$datos['destino']." = ".$datos['total']." => ".$arrayLiquidacionElectromecanico[$fila][2];

								$fila++;
							}
							
							if($datosElectromecanico){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];

				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						case 'ModificarLiquidacionCarteles':
							$id_carteles = $_SESSION['ID_CARTELES'];
							$tipo =  $_POST['tipo'];
							$monto =  $_POST['monto'];
							$coef =  $_POST['coef'];
							$recargo =  $_POST['recargo'];
							$totalAbonar =  $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "";

							$datosCarteles = $conn->query("SELECT * FROM liq_carteles WHERE id_liquidacion = '".$id_liquidacion."'");

							while($datos = $datosCarteles->fetch()){
								$conn->query("UPDATE liq_carteles SET tipo ='".$tipo."',monto ='".$monto."',coef ='".$coef."',recargo ='".$recargo."',total ='".$totalAbonar."' WHERE id_liq_carteles ='".$id_carteles."'");

								$informacion = $informacion."total ".$datos['tipo']." = ".$datos['total']." => ".$totalAbonar;
							}

							if($datosCarteles){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];

				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						case 'ModificarLiquidacionIncendio':
							$id_liq_incendio = $_SESSION['ID_LIQ_INCENDIO'];
							$destino = utf8_decode($_POST['destino']);
							$M2 = $_POST['M2'];
							$CapIX = $_POST['CapIX'];
							$totalAbonar = $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "";

							$datosIncendio = $conn->query("SELECT * FROM liq_incendio WHERE id_liquidacion = '".$id_liquidacion."'");
							
							while($datos = $datosIncendio->fetch()){
								$conn->query("UPDATE liq_incendio SET destino ='".$destino."',m2 ='".$M2."',cap_ix ='".$CapIX."',total ='".$totalAbonar."' WHERE id_liq_incendio ='".$id_liq_incendio."'");
								$informacion = $informacion."destino = ".$datos['destino']." => ".$destino." Total = ".$datos['total']." => ".$totalAbonar;
							}
							
							if($datosIncendio){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];
				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
						case 'ModificarLiquidacionDemolicion':
							/*$array_id_contrato = $_SESSION['ARRAY_ID_CONTRATO'];
							$array_id_multas = $_SESSION['ARRAY_ID_MULTAS'];						
							$arrayContrato = json_decode($_POST['arrayContrato']);
							$arrayMultas = json_decode($_POST['arrayMultas']);
							$descuento = $_POST['descuento'];
							$totalAbonar = $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "";

							//LIQUIDACION DEMOLICION TIENE PROBLEMAS EN EL CALCULO, HASTA QUE NO SE SOLUCIONE NO PUEDO CONTINUAR.
							$_SESSION['MODIFICACION_REALIZADA'] = true;
							if($datosContrato && $datosmultas){
				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];
				        		$conn->query("INSERT INTO auditorias (id_usuario,id_liquidacion,accion_realizada,informacion,fecha) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}*/
						break;
						case 'ModificarLiquidacionArt13':


							$id_contrato1 = $_SESSION['ID_FILA_CONTRATO1'];
							$array_id_contrato2 = $_SESSION['ARRAY_ID_CONTRATO2'];
							$arrayContrato = json_decode($_POST['arrayContrato']);
							$destino = $_POST['destino'];
							$monto = $_POST['DeclararMonto'];
							$capIx = $_POST['CapIx'];
							$totalAbonar = $_POST['totalAbonar'];
							//Variable para guardar la informacion a cambiar.
							$informacion = "Contrato1 : ";

							$datosContrato1 = $conn->query("SELECT * FROM contra_colegio_tipo_8_parte1 WHERE id_liquidacion = '".$id_liquidacion."'");
							//Varible para almacenar el contador del array.
							while($datos = $datosContrato1->fetch()){
								$conn->query("UPDATE contra_colegio_tipo_8_parte1 SET destino ='".$destino."',monto ='".$monto."',capix ='".$capIx."',total ='".$totalAbonar."' WHERE id_contrato_tipo_8 ='".$id_contrato1."'");
								$informacion = $informacion."total ".$datos['destino']." = ".$datos['total']." => ".$totalAbonar;
							}
							$datosContrato2 = $conn->query("SELECT * FROM contra_colegio_tipo_8_parte2 WHERE id_contrato_parte1 = '".$id_contrato1."'");
							//Varia para almacenar el contador del array.
							$idContra= 0;
							$stringInformacion = $stringInformacion." Contrato2 : ";
							while($datos = $datosContrato2->fetch()){
								$conn->query("UPDATE contra_colegio_tipo_8_parte2 SET m2 ='".$arrayContrato[$idContra][0]."',coef ='".$arrayContrato[$idContra][1]."',ureferencial ='".$arrayContrato[$idContra][2]."',total ='".$arrayContrato[$idContra][3]."' WHERE id_contrato_tipo_8_parte2 ='".$array_id_contrato2[$idContra]."'");
								$informacion = $informacion."total ".$datos['destino']." = ".$datos['total']." => ".$arrayContrato[$idContra][3];
								$idContra++;
							}
							
							if($datosContrato1 && $datosContrato2){
								$_SESSION['MODIFICACION_REALIZADA'] = true;

				        		date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
								$fecha =date('Y-m-d h:i:s', time());
								$id_usuario = $_SESSION['id_usuario'];
				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Modificacion de un Registro','".$informacion."','".$fecha."')") or die("Error en la carga de auditorias");
				        	}
						break;
					}
					if(isset($_SESSION['MODIFICACION_REALIZADA'])){

						$obtenerDatosAuditoria = $conn->query("SELECT TOP(1) id_auditoria,detalle_auditoria FROM auditoria ORDER BY id_auditoria DESC") or die("Error en obtener la auditoria cargada.");

						$modificacion = "";

			        	while($datosAuditoria = $obtenerDatosAuditoria->fetch()){
			        		$id_auditoria = $datosAuditoria['id_auditoria'];
			        		$infoAuditoria = $datosAuditoria['detalle_auditoria'];
			        	}

						$zona = $_SESSION['ZONIFICACION'];

						$modificacion = $infoAuditoria." ";

			        	$antiguoNombre = $_SESSION['ANTIGUO_NOMBRE_CLIENTE'];
						$antiguazona = $_SESSION['ANTIGUA_ZONIFICACION'];
						$antiguoDescuento = $_SESSION['ANTIGUO_DESCUENTO'];
						$antiguoTotal = $_SESSION['ANTIGUO_TOTAL'];

						$conn->query("UPDATE cliente SET razon_social = '".$nombre."' WHERE id_cliente = '".$id_cliente."'");

						$modificacion = $modificacion."Cliente : nombre =".$antiguoNombre." => ".$nombre." ";

						$conn->query("UPDATE nomenclatura SET manzana ='".$array_nomenclatura[0]."',circ ='".$array_nomenclatura[1]."',quinta ='".$array_nomenclatura[2]."',seccion ='".$array_nomenclatura[3]."',fraccion ='".$array_nomenclatura[4]."',chacra ='".$array_nomenclatura[5]."',parcela ='".$array_nomenclatura[6]."',uf ='".$array_nomenclatura[7]."',partida ='".$array_nomenclatura[8]."' WHERE id_nomenclatura ='".$id_nomenclatura."'");

						$conn->query("UPDATE liquidacion SET zonificacion ='".$zona."',descuento ='".$descuento."',total ='".$totalAbonar."' WHERE id_liquidacion = '".$id_liquidacion."'");

						$modificacion = $modificacion."Liquidacion : zonificacion = ".$antiguazona." => ".$zona. " descuento = ".$antiguoDescuento." => ".$descuento." total = ".$antiguoTotal." => ".$totalAbonar;

						$conn->query("UPDATE auditoria SET detalle_auditoria = '".$modificacion."' WHERE id_auditoria = '".$id_auditoria."'") or die ("PROBLEMAS EN EL UPDATE DE AUDITORIAS LINEA 333");

						$_SESSION['MODIFICACION_TERMINADA'] = true;

						$conn = NULL;
					}

					if(isset($_SESSION['MODIFICACION_REALIZADA']) && isset($_SESSION['MODIFICACION_TERMINADA'])){
						include("borrarVaribalesModificacion.php");
					}
				}
			}
		}
	}
?>