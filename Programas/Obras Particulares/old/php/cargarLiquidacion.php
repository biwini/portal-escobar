<?php
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
	require_once('../controller/globalController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				//Verifico que todos los campos necesarios hayan sido cargados.
				if(isset($_SESSION['FechaLiquidacion']) && isset($_SESSION['NombreCliente']) && isset($_SESSION['ARRAY_NOMENCLATURA']) && isset($_SESSION['ZONIFICACION'])){
					$Global = new globalController();

					// Obtengo los datos almacenados en Session.
					$fecha = $_SESSION['FechaLiquidacion'];
					$nombre = $_SESSION['NombreCliente'];

					$array_nomenclatura = $_SESSION['ARRAY_NOMENCLATURA'];

					$zona = $_SESSION['ZONIFICACION'];
				
					//Obtengo el id de la pagina de la cual quiero cargar la liquidacion.
					$id_pag = $_POST['idPag'];
					//variables para almacenar los id del ultimo registro ingresado de las tablas "cliente","nomenclatura".
					$id_cliente = 0;
					$id_nomenclatura = 0;
					//Defino la hora para realizar la auditoria.
					date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
					//Defino la fecha.
					$fecha =date('Y-m-d H:i:s', time());
					//Obtengo el id del usuario que realiza la carga de la liquidacion para la auditoria.
					$id_usuario = $_SESSION['ID_USER'];
					//Variable para guardar el string del sql para la auditoria.
					$informacion = "";
					if($id_pag == "liquidacion" || $id_pag == "liquidacionMoratoria" || $id_pag == "liquidacionArt126"){
						if($_POST['totalAbonar'] == 0){
							echo "<div class='center-block' style='background-color: red;font-size: 15px'><span class='label center-block label-danger center-block' style='font-size:15px;'>No se puede cargar una liquidacion vacia.</span></div>";
							return;
						}
					}
					else{
						if($_POST['total'] == 0){
							echo "<div class='center-block' style='background-color: red'><span class='label center-block label-danger center-block' style='font-size:15px;'>No se puede cargar una liquidacion vacia.</span></div>";
							return;
						}
					}

					//Inserto el cliente en la base de datos.
					if($fecha != "" && $nombre != "" && $zona != ""){
						require("conexion.php");

						$InsertCliente = "INSERT INTO cliente(razon_social) VALUES ('".$nombre."')";
						$conn->query($InsertCliente);
						//Obtengo el id del ultimo registro ingresado en la tabla "cliente" y se lo asigno a una variable.
						$SelectCliente = "SELECT TOP(1) id_cliente FROM cliente ORDER BY id_cliente DESC";
						$obtenerIdCliente = $conn->query($SelectCliente);
						while($id = $obtenerIdCliente->fetch()){
							$id_cliente = $id['id_cliente'];
						}
						//hago lo mismo con las tablas "liquidaciones", y "contrato Colegio", "multas","nomenclaturas","zonificacion".
			        	//Nomenclatura.
			        	$conn->query("INSERT INTO nomenclatura (manzana,circ,quinta,seccion,fraccion,chacra,parcela,uf,partida) VALUES ('".$array_nomenclatura[0]."','".$array_nomenclatura[1]."','".$array_nomenclatura[2]."','".$array_nomenclatura[3]."','".$array_nomenclatura[4]."','".$array_nomenclatura[5]."','".$array_nomenclatura[6]."','".$array_nomenclatura[7]."','".$array_nomenclatura[8]."')");
			        	
			        	$obtenerIdNomenclatura = $conn->query("SELECT TOP(1) id_nomenclatura FROM nomenclatura ORDER BY id_nomenclatura DESC");

			        	while($id = $obtenerIdNomenclatura->fetch()){
			        		$id_nomenclatura = $id['id_nomenclatura'];
			        	}

						if($id_pag == "liquidacionMoratoria" || $id_pag == "liquidacion" || $id_pag == "liquidacionArt126"){

							$totalCondonacionMulta = 0;
							$totalCondonacionContraCol = 0;
							$porcenCondonacion = 0;

							if($id_pag == "liquidacionMoratoria"){
								$totalCondonacionMulta = $_POST['totalCondonacionMulta'];
								$totalCondonacionContraCol = $_POST['totalCondonacionContraCol'];
								$porcenCondonacion = $_POST['porcentaje'];
							}
							// Obtengo los datos enviados desde el formulario.
							$arrayContrato = json_decode($_POST['arrayContrato']);
							$arrayMultas = json_decode($_POST['arrayMultas']);
							if($id_pag == "liquidacionMoratoria" || $id_pag == "liquidacion"){
								$descuento = $_POST['descuento'];
							}
							$totalAbonar = $_POST['totalAbonar'];
							$filaMonto = 1;
							$filaMulta = 1;
							//variable para almacenar el id del ultimo registro ingresado de la tabla "liquidaciones".
							$id_liquidacion = 0;

				        	//Liquidacion.
				        	switch ($id_pag) {
				        		case 'liquidacion':
				        			$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES (".$id_cliente.",".$id_nomenclatura.",'".$zona."',1,'".$descuento."','".$totalAbonar."','".$fecha."')");

				        			$informacion = "INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,1,$descuento,$totalAbonar,$fecha)";
				        		break;
				        		case 'liquidacionMoratoria':
				        			$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',2,'".$descuento."','".$totalAbonar."','".$fecha."')");

				        			$informacion = "INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,2,$descuento,$totalAbonar,$fecha)";
				        		break;
				        		case 'liquidacionArt126':
				        			$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',7,0,'".$totalAbonar."','".$fecha."')");

				        			$informacion = "INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,7,$descuento,$totalAbonar,$fecha)";
				        		break;
				        	}

							$obtenerIdLiquidacion = $conn->query("SELECT TOP(1) id_liquidacion FROM liquidacion ORDER BY id_liquidacion DESC");
							if($obtenerIdLiquidacion){
								while($id = $obtenerIdLiquidacion->fetch()){
					        		$id_liquidacion = $id['id_liquidacion'];
					        	}
					        	//Carga de la auditoria.
				        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES (".$id_usuario.",".$id_liquidacion.",'Carga de una Liquidacion','".$informacion."','".$fecha."')") or die(print_r( sqlsrv_errors(), true));
				        	}
				        	else{
				        		echo "Problemas en seleccionar la liquidacion cargada.";
				        	}
							//funcion para ingresar las filas de la tabla contrato de colegio.
							function insertarContrato($contrato,$tipoMonto,$id_liquidacion,$conn,$id_pag,$porcentaje){
								switch ($id_pag) {
									case 'liquidacion':
										//para ir identificando las filas de la tabla en la base de datos
										$TipoMonto = $contrato[4];
										
										//Obtengo los valores del array donde tengo las filas de la tabla.
										$Monto = $contrato[0];
										$Coef = $contrato[1];
										$Recargo = $contrato[2];
										$Total = $contrato[3];
										//Inserto los valores a la tabla "contra_colegio_tipo1".
										$conn->query("INSERT INTO contra_colegio_tipo_1 (id_liquidacion,tipo_monto,monto,coef,recargo,total) VALUES ('".$id_liquidacion."','".$TipoMonto."','".$Monto."','".$Coef."','".$Recargo."','".$Total."')");
									break;
									case 'liquidacionMoratoria':
										//para ir identificando las filas de la tabla en la base de datos
										switch ($tipoMonto) {
											case 1: $TipoMonto = "fila1";break;
											case 2: $TipoMonto = "fila2";break;
											case 3: $TipoMonto = "fila3";break;
											case 4: $TipoMonto = "fila4";break;
											case 5: $TipoMonto = "fila5";break;
										}
										$Monto = $contrato[0];
										$Coef = $contrato[1];
										$Recargo = $contrato[2];
										$Total = $contrato[3];
										$TotalCondonacion = $contrato[4];

										//Inserto los valores a la tabla "contra_colegio_tipo2".
										$conn->query("INSERT INTO contra_colegio_tipo_2 (id_liquidacion,fila,monto_obra,coef,recargo,total,porcen_condonacion,total_condonacion) VALUES ('".$id_liquidacion."','".$TipoMonto."','".$Monto."','".$Coef."','".$Recargo."','".$Total."','".$porcentaje."','".$TotalCondonacion."')");
									break;
									case 'liquidacionArt126':
										//para ir identificando las filas de la tabla en la base de datos
										switch ($tipoMonto) {
											case 1: $TipoMonto = "cubierto";break;
											case 2: $TipoMonto = "semi cubierto";break;
											case 3: $TipoMonto = "pileta";break;
											case 4: $TipoMonto = "declarar";break;
										}
										//Obtengo los valores del array donde tengo las filas de la tabla.
										$m2_monto = $contrato[0];
										$Coef = $contrato[1];
										$uRef_recargo = $contrato[2];
										$Total = $contrato[3];
										//Inserto los valores a la tabla "contra_colegio_tipo7".
										$conn->query("INSERT INTO contra_colegio_tipo_7 (id_liquidacion,destino,m2_monto,coef,ureferencial_recargo,total) VALUES ('".$id_liquidacion."','".$TipoMonto."','".$m2_monto."','".$Coef."','".$uRef_recargo."','".$Total."')");
									break;
								}
							}
							function insertarMulta($multas,$filaMulta,$id_liquidacion,$conn,$id_pag,$porcenCondonacion){
								//Carga de datos de la Liquidacion Normal.
								if($id_pag == "liquidacion" || $id_pag == "liquidacionArt126"){
									switch ($filaMulta) {
										case 1: $filaMulta = "fos";break;
										case 2: $filaMulta = "fot";break;
										case 3: $filaMulta = "retiros";break;
										case 4: $filaMulta = "densidad";break;
										case 5: $filaMulta = "dto.1281/14";break;
									}
									$m2 = $multas[0];
									$cant = $multas[1];
									$sMMunicipal = $multas[2];
									$porcentaje = $multas[3];
									$total = $multas[4];

									if($id_pag == "liquidacion"){
										$conn->query("INSERT INTO multa_tipo_1 (id_liquidacion,multa,m2,cant,smmunicipal,porcentaje,total) VALUES ('".$id_liquidacion."','".$filaMulta."','".$m2."','".$cant."','".$sMMunicipal."','".$porcentaje."','".$total."')");
									}
									else{
										$conn->query("INSERT INTO multa_tipo_7 (id_liquidacion,multa,m2,cant,smmunicipal,porcentaje,total) VALUES ('".$id_liquidacion."','".$filaMulta."','".$m2."','".$cant."','".$sMMunicipal."','".$porcentaje."','".$total."')");
									}
								}
								//Carga de datos de la Liquidacion Moratoria.
								else{
									switch ($filaMulta) {
										case 1: $filaMulta = "fos";break;
										case 2: $filaMulta = "fot";break;
										case 3: $filaMulta = "retiros";break;
										case 4: $filaMulta = "densidad";break;
										case 5: $filaMulta = "dto.1281/14";break;
									}
									$m2 = $multas[0];
									$cant = $multas[1];
									$sMMunicipal = $multas[2];
									$porcentaje = $multas[3];
									$total = $multas[4];
									$totalFilaCondonacion = $multas[5];

									$conn->query("INSERT INTO multa_tipo_2 (id_liquidacion,tipo,m2,cant,smmunicipal,porcentaje,total,porcen_condonacion,total_condonacion) VALUES ('".$id_liquidacion."','".$filaMulta."','".$m2."','".$cant."','".$sMMunicipal."','".$porcentaje."','".$total."','".$porcenCondonacion."','".$totalFilaCondonacion."')");
								}
							}
							// recorro el array donde tengo las filas de la tabla contrato de colegio y lo envio a la funcion "insertarRegistro" para su carga a la base de datos.
							foreach($arrayContrato as $contrato){
								switch ($id_pag) {
									case 'liquidacion':
										insertarContrato($contrato,$filaMonto,$id_liquidacion,$conn,$id_pag,$porcenCondonacion);
									break;
									case 'liquidacionMoratoria':
										insertarContrato($contrato,$filaMonto,$id_liquidacion,$conn,$id_pag,$porcenCondonacion);
									break;
									case 'liquidacionArt126':
										insertarContrato($contrato,$filaMonto,$id_liquidacion,$conn,$id_pag,$porcenCondonacion);
									break;

								}
								$filaMonto ++;
							}
							// recorro el array donde tengo las filas de la tabla multas y lo envio a la funcion "insertarRegistro" para su carga a la base de datos.
							foreach($arrayMultas as $multas){
								if($id_pag == "liquidacion" || $id_pag == "liquidacionArt126"){
									insertarMulta($multas,$filaMulta,$id_liquidacion,$conn,$id_pag,$porcenCondonacion);
								}
								else{
									insertarMulta($multas,$filaMulta,$id_liquidacion,$conn,$id_pag,$porcenCondonacion);
								}
								$filaMulta ++;
							}
							echo "<div class='center-block' style='background-color: green'><span class='label center-block label-success' style='font-size:15px;'>Liquidacion Cargada con Exito.</span></div>";
						}
						else{
							//Carga de datos de la liquidacion Incendio.
							if($id_pag == "liquidacionIncendio"){
								$destino = $_POST['destino'];
								$M2 = $_POST['M2'];
								$CapIX = $_POST['CapIX'];
								$total = $_POST['total'];
								$id_liquidacion = 0;

					        	//Liquidaciones
								$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',3,0,'".$total."','".$fecha."')") or die ("gato");

								$obtenerIdLiquidacion = $conn->query("SELECT * FROM liquidaciones ORDER BY id_liquidacion DESC LIMIT 1");
								if($obtenerIdLiquidacion){
									while($id = $obtenerIdLiquidacion->fetch()){
						        		$id_liquidacion = $id['id_liquidacion'];
						        	}
						        	//Carga de la auditoria.
					        		$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Carga de una Liquidacion','INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,3,$total,$fecha)','".$fecha."')") or die("Problemas con la carga de auditoria de la liquidacion1");

									$conn->query("INSERT INTO liq_incendio(id_liquidacion,destino,m2,cap_ix,total) VALUES ('".$id_liquidacion."','".$destino."','".$M2."','".$CapIX."','".$total."')");
								}
								else{
									echo "Problemas en seleccionar la liquidacion de incendio cargarda.";
								}
								echo "<div class='center-block' style='background-color: green'><span class='label center-block label-success' style='font-size:15px;'>Liquidacion Cargada con Exito.</span></div>";
							}
							//Carga de liquidacion electromecanico
							else{
								if($id_pag == "liquidacionElectromecanico"){
									$arrayElectromecanico = json_decode($_POST['arrayLiquidacionElectromecanico']);
									$total =  $_POST['total'];
									$filaLiquidacion = 1;
									$id_liquidacion = 0;

									//Liquidaciones
									$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',4,0,'".$total."','".$fecha."')");
									$obtenerIdLiquidacion = $conn->query("SELECT TOP(1) * FROM liquidacion ORDER BY id_liquidacion DESC");
									if($obtenerIdLiquidacion){
										while($id = $obtenerIdLiquidacion->fetch()){
							        		$id_liquidacion = $id['id_liquidacion'];
							        	}

							        	$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Carga de una Liquidacion','INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,electromecanico,0,$total,$fecha)','".$fecha."')") or die("Problemas con la carga de auditoria de la liquidacion5");
							        }
							        else{
							        	echo "Problemas en obtener la liquidacion Electromecanico cargada.";
							        }
						        	function cargarLiquidacionElectromecanico($electromecanico,$filaLiquidacion,$id_liquidacion,$conn){
						        		switch ($filaLiquidacion) {
						        			case '1': $filaLiquidacion = "hasta50";break;
						        			case '2': $filaLiquidacion = "exedente";break;
						        			case '3': $filaLiquidacion = "hasta25";break;
						        			case '4': $filaLiquidacion = "exedente";break;
						        		}
						        		$m2 = $electromecanico[0];
						        		$capIx = $electromecanico[1];
						        		$total = $electromecanico[2];

						        		$conn->query("INSERT INTO liq_electromecanico(id_liquidacion,destino,m2,cap_ix,total) VALUES ('".$id_liquidacion."','".$filaLiquidacion."','".$m2."','".$capIx."','".$total."')");
						        	}

									foreach($arrayElectromecanico as $electromecanico){
										cargarLiquidacionElectromecanico($electromecanico,$filaLiquidacion,$id_liquidacion,$conn);
										$filaLiquidacion ++;
									}
									echo "<div class='center-block' style='background-color: green'><span class='label center-block label-success' style='font-size:15px;'>Liquidacion Cargada con Exito.</span></div>";
								}
								else{
									//Carga de datos de Liquidacion Carteles-Antena.
									if($id_pag == "liquidacionCarteles"){
										$tipo =  $_POST['tipo'];
										$monto =  $_POST['monto'];
										$coef =  $_POST['coef'];
										$recargo =  $_POST['recargo'];
										$total =  $_POST['total'];
										$id_liquidacion = 0;

										//Liquidaciones
										$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',6,0,'".$total."','".$fecha."')");

										$obtenerIdLiquidacion = $conn->query("SELECT TOP(1) * FROM liquidacion ORDER BY id_liquidacion DESC");
										if($obtenerIdLiquidacion){
											while($id = $obtenerIdLiquidacion->fetch()){
								        		$id_liquidacion = $id['id_liquidacion'];
								        	}

								        	$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Carga de una Liquidacion','INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,6,0,$total,$fecha)','".$fecha."')") or die("Problemas con la carga de auditoria de la liquidacion1");
										}
							        	$conn->query("INSERT INTO liq_carteles(id_liquidacion,tipo,monto,coef,recargo,total) VALUES ('".$id_liquidacion."','".$tipo."','".$monto."','".$coef."','".$recargo."','".$total."')");

							        	echo "<div class='center-block' style='background-color: green'><span class='label center-block label-success' style='font-size:15px;'>Liquidacion Cargada con Exito.</span></div>";
									}
									//Liquidacion ART13.
									else{
										$arrayContrato = json_decode($_POST['arrayContrato']);
										$destino = $_POST['destino'];
										$capIx = $_POST['CapIx'];
										$monto = $_POST['DeclararMonto'];
										$total = $_POST['total'];
										$filaContrato = 1;
										$id_Contrato = 0;

										//Liquidaciones
										$conn->query("INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ('".$id_cliente."','".$id_nomenclatura."','".$zona."',8,0,'".$total."','".$fecha."')");

										$obtenerIdLiquidacion = $conn->query("SELECT TOP(1) * FROM liquidacion ORDER BY id_liquidacion DESC");
										if($obtenerIdLiquidacion){
											while($id = $obtenerIdLiquidacion->fetch()){
								        		$id_liquidacion = $id['id_liquidacion'];
								        	}

								        	$conn->query("INSERT INTO auditoria (id_usuario,id_liquidacion,tipo_auditoria,detalle_auditoria,fecha_auditoria) VALUES ('".$id_usuario."','".$id_liquidacion."','Carga de una Liquidacion','INSERT INTO liquidacion (id_cliente,id_nomenclatura,zonificacion,id_tipo_liquidacion,descuento,total,fecha_liquidacion) VALUES ($id_cliente,$id_nomenclatura,$zona,8,0,$total,$fecha)','".$fecha."')") or die("Problemas con la carga de auditoria de la liquidacion2");

								        }

							        	$conn->query("INSERT INTO contra_colegio_tipo_8_parte1 (id_liquidacion,tipo,destino,monto,capix,total) VALUES ('".$id_liquidacion."','articulo 13','".$destino."','".$monto."','".$capIx."','".$total."')");

							        	$obtenerIdContrato = $conn->query("SELECT TOP(1) * FROM contra_colegio_tipo_8_parte1 ORDER BY id_contrato_tipo_8 DESC");

										while($id = $obtenerIdContrato->fetch()){
							        		$id_Contrato = $id['id_contrato_tipo_8'];
							        	}
							        	//Liquidacion ART13
							     		function cargarContratoTipo8($Contrato,$filaContrato,$id_Contrato,$conn){
							        		switch ($filaContrato) {
							        			case '1': $filaContrato = "cubierto";break;
							        			case '2': $filaContrato = "semicubierto";break;
							        		}
											//Obtengo los valores del array donde tengo las filas de la tabla.
											$m2 = $Contrato[0];
											$Coef = $Contrato[1];
											$uRef = $Contrato[2];
											$Total = $Contrato[3];
											//Inserto los valores a la tabla "contra_colegio_tipo7".
											$conn->query("INSERT INTO contra_colegio_tipo_8_parte2 (id_contrato_parte1,destino,m2,coef,ureferencial,total) VALUES ('".$id_Contrato."','".$filaContrato."','".$m2."','".$Coef."','".$uRef."','".$Total."')");
							        	}

							        	foreach($arrayContrato as $Contrato){
											cargarContratoTipo8($Contrato,$filaContrato,$id_Contrato,$conn);
											$filaContrato ++;
										}
										echo "<div class='center-block' style='background-color: green'><span class='label center-block col-form-label-sm label-success' style='font-size:15px;'>Liquidacion Cargada con Exito.</span></div>";
									}
								}
							}
						}
						$conn = NULL;
					}
					else{
						echo "<div class='center-block' style='background-color: red;'><span class='label label-danger center-block' style='font-size:15px;'>Los campos de fecha, Nombre y Apellido, y zonificacion No pueden estar vacios.</span></div>";
						return;
					}
				}
				else{
					echo "<div class='center-block' style='background-color: red;'><span class='label label-danger center-block' style='font-size:15px;'>Los campos de fecha, Nombre y Apellido, y zonificacion son obligatorios.</span></div>";
				}
			}
			else{
				header("location: ../../../index.php");
			}
		}
		else{
			header("location: ../../../index.php");
		}
	}

?>