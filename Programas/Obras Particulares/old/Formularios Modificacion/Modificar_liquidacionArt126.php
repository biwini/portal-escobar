<?php
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
?>
				<!DOCTYPE html>
				<html>
				<head>
					<title></title>
					<link href="../css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">

					<script language="javascript" src="../js/jquery-3.2.1.min.js"></script>
					<script language="javascript" src="../js/funciones.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="../js/funcionesPHP.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="../js/tableToExcel.js?v=<?php echo time(); ?>"></script>

					<link href="../css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link rel="stylesheet" href="../css/planilla.css?v=<?php echo time(); ?>">
					<?php if(!isset($_SESSION['TipoConsulta'])){?>
						<link href="../css/header.css" rel="stylesheet" type="text/css" media="print">
					<?php } ?>
					<script type="text/javascript">
						<?php
							if(isset($_SESSION['TipoConsulta'])){
							?>
								$(document).ready(function(){
									SoloVista()
								});
								
							<?php
							}
						?>
						$(document).ready(function(){
							calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')
							calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')
							calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')

							calcularSubTotalArt126();

							obtenerResultados('tblMultas','filaFOS','pagLiqArt126');
							obtenerResultados('tblMultas','filaFOT','pagLiqArt126');
							obtenerResultados('tblMultas','filaRetiros','pagLiqArt126');
							obtenerResultados('tblMultas','filaDencidad','pagLiqArt126');
							obtenerResultados('tblMultas','filaDTO','pagLiqArt126');
						});
					</script>
				</head>
				<body>
					<div class="container colorContainer nover">
						<div class="col-lg-12">
							<div class="table-responsive">
								<!--Encabezado-->
								<?php include("Modificar_header.php");?>
								<!--Fin Encabezdo-->
								<div class="table-responsive">
									<table id="tblMontoContratoColegio" border="1" class="tabla table text-center">
										<thead>
											<tr>
												<th colspan="8">CONTRATO DEL COLEGIO</th>
											</tr>
											<tr>
												<th>DESTINO</th>
												<th>(M²)</th>
												<th>COEF.%</th>
												<th>U.REFERENCIAL</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr id="filaCubierto">
												<td><STRONG>CUBIERTO</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" min="0" name="inpCubiertoM2" id="inpCubiertoM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][0];}?>">
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpCubiertoCoef" id="inpCubiertoCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][1];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpCubiertoURef" id="inpCubiertoURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][2];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblCubiertoTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][3];}?></label>
												</td>
											</tr>
											<tr id="filaSemiCub">
												<td><STRONG>SEMICUBIERTO</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" min="0" name="inpSemiCubM2" id="inpSemiCubM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][0];}?>">
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpSemiCubCoef" id="inpSemiCubCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][1];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpSemiCubURef" id="inpSemiCubURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][2];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblSemiCubTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][3];}?></label>
												</td>
											</tr>
											<tr id="filaPileta">
												<td><STRONG>PILETA</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" min="0" name="inpPiletaM2" id="inpPiletaM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][0];}?>">
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpPiletaCoef" id="inpPiletaCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][1];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpPiletaURef" id="inpPiletaURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][2];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblPiletaTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][3];}?></label>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-right" colspan="4"><STRONG class="lblTotal2">MONTO OBRA</STRONG></td>
												<td>
													<label><STRONG class="lblTotal2">$</STRONG></label>
													<STRONG><label id="lblTotalMontoObra" class="lblTotal2"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][0];}?></label></STRONG>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="table-responsive">
									<table id="tblContratoColegio" border="1" class="tabla table text-center">
										<thead>
											<tr>
												<th colspan="8">CONTRATO DEL COLEGIO</th>
											</tr>
											<tr>
												<th colspan="2">MONTO DE OBRA</th>
												<th>COEF.%</th>
												<th>RECARGO</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr id="filaDeclarar">
												<td><STRONG>A DECLARAR</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDeclararMonto"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][0];}?></label></td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpDeclararCoef" id="inpDeclararCoef" onchange="calcularSubTotalArt126()" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][1];}?>">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpDeclararRecargo" id="inpDeclararRecargo" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][2];}?>" onchange="calcularSubTotalArt126()">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDeclararTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][3];}?></label>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-right" colspan="4"><STRONG class="lblTotal2">SUBTOTAL(A)</STRONG></td>
												<td>
													<label><STRONG class="lblTotal2">$</STRONG></label>
													<STRONG><label id="lblSubTotalA" class="lblTotal2">0</label></STRONG>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="table-responsive">
									<table id="tblMultas" border="1" class="tabla table text-center">
										<thead>
											<tr>
												<th colspan="7">MULTAS</th>
											</tr>
											<tr>
												<th>MULTAS</th>
												<th>(m²)</th>
												<th>CANT</th>
												<th>S.M.MUNICIPAL</th>
												<th>PORCENTAJE</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr id="filaFOS">
												<td><STRONG>F.O.S</STRONG></td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][0];}?>" class="validanumericos" type="number" name="inpFOSM2" id="inpFOSm2" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][1];}?>" class="validanumericos" type="number" name="inpFOSCant" id="inpFOSCant" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][2];}?>" class="validanumericos" type="number" name="inpFOSSMMun" id="inpFOSSMMun" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][3];}?>" class="validanumericos" type="number" name="inpFOSPorcentaje" id="inpFOSPorcentaje" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblFOSTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][4];}?></label>
												</td>
											</tr>
											<tr id="filaFOT">
												<td><STRONG>F.O.T</STRONG></td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][0];}?>" class="validanumericos" type="number" name="inpFOTM2" id="inpFOTM2" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][1];}?>" class="validanumericos" type="number" name="inpFOTCant" id="inpFOTCant" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][2];}?>" class="validanumericos" type="number" name="inpFOTSMMun" id="inpFOTSMMun" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][3];}?>" class="validanumericos" type="number" name="inpFOTPorcentaje" id="inpFOTPorcentaje" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblFOTTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][4];}?></label>
												</td>
											</tr>
											<tr id="filaRetiros">
												<td><STRONG>RETIROS</STRONG></td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][0];}?>" class="validanumericos" type="number" name="inpRetirosM2" id="inpRetirosM2" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][1];}?>" class="validanumericos" type="number" name="inpRetirosCant" id="inpRetirosCant" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][2];}?>" class="validanumericos" type="number" name="inpRetirosSMMun" id="inpRetirosSMMun" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][3];}?>" class="validanumericos" type="number" name="inpRetirosPorcentaje" id="inpRetirosPorcentaje" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblRetirosTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][4];}?></label>
												</td>
											</tr>
											<tr id="filaDencidad">
												<td><STRONG>DENSIDAD</STRONG></td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][0];}?>" class="validanumericos" type="number" name="inpDencidadM2" id="inpDencidadM2" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][1];}?>" class="validanumericos" type="number" name="inpDencidadCant" id="inpDencidadCant" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][2];}?>" class="validanumericos" type="number" name="inpDencidadSMMun" id="inpDencidadSMMun" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][3];}?>" class="validanumericos" type="number" name="inpDencidadPorcentaje" id="inpDencidadPorcentaje" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDencidadTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][4];}?></label>
												</td>
											</tr>
											<tr id="filaDTO">
												<td><STRONG>DTO.1281/14</STRONG></td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][0];}?>" class="validanumericos" type="number" name="inpDtoM2" id="inpDtoM2" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][1];}?>" class="validanumericos" type="number" name="inpDtoCant" id="inpDtoCant" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][2];}?>" class="validanumericos" type="number" name="inpDtoSMMun" id="inpDtoSMMun" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
												</td>
												<td>
													<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][3];}?>" class="validanumericos" type="number" name="inpDtoPorcentaje" id="inpDtoPorcentaje" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDtoTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][4];}?></label>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-right" colspan="5"><STRONG class="lblTotal2">SUBTOTAL(B)</STRONG></td>
												<td>
													<label><STRONG class="lblTotal2">$</STRONG></label>
													<label id="lblSubTotalB" class="lblTotal2">0</label>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="table-responsive">
									<table border="1" class="tabla table text-center">
										<tbody>
											<tr>
												<td colspan="2"><STRONG>SUBTOTAL (A) + (B)</STRONG></td>
												<td rowspan="2"><STRONG><label class="lblTotal2">TOTAL A<br> ABONAR</label></STRONG></td>
												<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
												<td rowspan="2"><STRONG><label class="pull-left lblTotal">$</label></STRONG><label class="lblTotal" id="lblTotalAbonar">0</label>
											</tr>
											<tr>
												<td><STRONG class="pull-left">$</STRONG><label class="pull-right" id="lblSubTotalAB">0</label></td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="text-center">
									<h5> ART.126</h5>
									<h4 class="h4">ORDENANZA 5372/16</h4>
								</div>
							</td>
						</tr>
						<tr>
							<td>
								<div class="text-center">
									<label><STRONG>FIRMA DEL LIQUIDADOR</STRONG></label>
									<textarea rows="8" cols="60" name="textareaFirma" disabled="true" >En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.
Los Derechos de Construcción se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del Expediente.
La liquidación será ratificada previo a la aprobación.
									</textarea>
								</div>
								<div id="resultados"></div>
								<?php 
									if(!isset($_SESSION['TipoConsulta'])){?>
										<input type="button" class="btn btn-primary pull-right" name="bttnCargar" id="bttnCargar" value="Modificar Liquidacion" onclick="CargarLiquidacion_Normal_Mora_Art126('ModificarLiquidacionArt126')">
									<?php
									}
									else{?>
								
										<input type="button" class="btn btn-primary pull-left" name="bttnPDF" id="bttnPDF" value="Descargar PDF" onclick="imprimirPDF('FormModificacion')">
									<?php
									}
								?>
							</div>
						</div>
					</div>
				</body>
				</html>
<?php
			}
			else{
				header("location: ../../index.php");
			}
		}
		else{
			header("location: ../../index.php");
		}
	}
?>