<?php
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				if(isset($_SESSION['MODIFICACION'])){
					if($_SESSION['MODIFICACION'] == true){
						// var_dump($_SESSION);
				?>
						<!DOCTYPE html>
						<html>
						<head>
							<title></title>
							<link href="../css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
							<link href="../css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
							<link rel="stylesheet" href="../css/planilla.css?v=<?php echo time(); ?>">
							<script language="javascript" src="../js/jquery-3.2.1.min.js"></script>

							<?php if(!isset($_SESSION['TipoConsulta'])){?>

								<link href="../css/header.css" rel="stylesheet" type="text/css" media="print">
							<?php } ?>
							<script type="text/javascript">
								//funcion para Calcular los totales.
								$(document).ready(function(){
									obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')
									obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion');
									obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion');
									<?php if(count($_SESSION['ARRAY_CONTRATO'][3]) != 0){ obtenerResultados('tblContratoColegio','fila4','pagLiquidacion'); } ?>
									<?php if(count($_SESSION['ARRAY_CONTRATO'][4]) != 0){ obtenerResultados('tblContratoColegio','fila5','pagLiquidacion'); } ?>
									<?php if(count($_SESSION['ARRAY_CONTRATO'][5]) != 0){ obtenerResultados('tblContratoColegio','fila6','pagLiquidacion'); } ?>
									obtenerResultados('tblMultas','filaFOS','pagLiquidacion');
									obtenerResultados('tblMultas','filaFOT','pagLiquidacion');
									obtenerResultados('tblMultas','filaRetiros','pagLiquidacion');
									obtenerResultados('tblMultas','filaDencidad','pagLiquidacion');
									obtenerResultados('tblMultas','filaDTO','pagLiquidacion');

									<?php if(isset($_SESSION['TipoConsulta'])){?>
										SoloVista();
									<?php
										}
									?>
								});
							</script>
						</head>
						<?php if(isset($_SESSION['TipoConsulta'])){
							echo "<body onLoad=\"window.print(); window.close();\">";
						}else{
							echo "<body>";
						}
						?>
							<div class="contenido nover">
								<div class="col-lg-12 " style="margin-top: 13rem;">
									<div class="table-responsive">
										<!--Encabezado-->
										<?php include("Modificar_header.php");?>
										<!--Fin Encabezdo-->
										<form method="post" action="php/cargarLiquidacion.php">
											<div class="table-responsive">
												<table id="tblContratoColegio" border="2" class="tabla2 colegio text-center" style="margin-bottom: 5px !important;">
													<thead>
														<tr>
															<th colspan="8">CONTRATO DEL COLEGIO</th>
														</tr>
														<tr>
															<th colspan="2">MONTO DE OBRA</th>
															<th>COEF.%</th>
															<th>RECARGO</th>
															<th colspan="2" style="width: 32% !important;border-right: solid;border-right-width: 2px;">TOTAL</th>
														</tr>
													</thead>
													<tbody>
														<tr id="filaNuevo">
															<td class="tdInp" style="width: 15%;"><input type="text" class="inpContrato upper" name="inpFila1" id="inpFila1" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][4];}?>"></td>
															<td style="width: 15%;">
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpNuevoMonto" id="inpNuevoMonto" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][0];}?>">
															</td>
															<td style="width: 15%;">
																<input min="0" class="validanumericos porcentaje" type="text" name="inpNuevoCoef" id="inpNuevoCoef" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][1];}?>">
																<label>%</label>
															</td>
															<td style="width: 15%;">
																<input min="0" class="validanumericos porcentaje" type="text" name="inpNuevoRecargo" id="inpNuevoRecargo" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][2];}?>">
																<label>%</label>
															</td>
															<td style="width: 4.8% !important;">
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblNuevoTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][3];}?></label>
															</td>
														</tr>
														<tr id="filaDemoler">
															<td class="tdInp"><input type="text" class="inpContrato upper" name="inpFila2" id="inpFila2" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][4];}?>"></td>
															<td>
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpDemolerNuevo" id="inpDemolerNuevo" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][0];}?>"></td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpDemolerCoef" id="inpDemolerCoef" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][1];}?>">
																<label>%</label>
															</td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpDemolerRecargo" id="inpDemolerRecargo" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][2];}?>">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblDemolerTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][3];}?></label>
															</td>
														</tr>
														<tr id="filaDeclarar">
															<td class="tdInp"><input type="text" class="inpContrato upper" name="inpFila3" id="inpFila3" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][4];}?>"></td>
															<td>
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpDeclararNuevo" id="inpDeclararNuevo" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][0];}?>"></td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpDeclararCoef" id="inpDeclararCoef" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][1];}?>">
																<label>%</label>
															</td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpDeclararRecargo" id="inpDeclararRecargo" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][2];}?>">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblDeclararTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][3];}?></label>
															</td>
														</tr>
														<?php
															if(count($_SESSION['ARRAY_CONTRATO'][3]) != 0){
														?>
														<tr id="4">
															<td class="tdInp"><input type="text" class="inpContrato upper" name="inpFila4" id="inpFila4" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][4];}?>"></td>
															<td>
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpFila4Monto" id="inpFila4Monto" onchange="obtenerResultados('tblContratoColegio','fila4','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][0];}?>"></td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila4Coef" id="inpFila4Coef" onchange="obtenerResultados('tblContratoColegio','fila4','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][1];}?>">
																<label>%</label>
															</td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila4Recargo" id="inpFila4Recargo" onchange="obtenerResultados('tblContratoColegio','fila4','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][2];}?>">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblFila4Total"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][3];}?></label>
															</td>
														</tr>
														<?php
															}
															if(count($_SESSION['ARRAY_CONTRATO'][4]) != 0){
														?>
														<tr id="5">
															<td class="tdInp"><input type="text" class="inpContrato upper" name="inpFila5" id="inpFila5" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][4];}?>"></td>
															<td>
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpFila5Monto" id="inpFila5Monto" onchange="obtenerResultados('tblContratoColegio','fila5','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][0];}?>"></td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila5Coef" id="inpFila5Coef" onchange="obtenerResultados('tblContratoColegio','fila5','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][1];}?>">
																<label>%</label>
															</td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila5Recargo" id="inpFila5Recargo" onchange="obtenerResultados('tblContratoColegio','fila5','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][2];}?>">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblFila5Total"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][3];}?></label>
															</td>
														</tr>
														<?php
															}
															if(count($_SESSION['ARRAY_CONTRATO'][5]) != 0){
														?>
														<tr id="6">
															<td class="tdInp"><input type="text" class="inpContrato upper" name="inpFila5" id="inpFila6" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][5][4];}?>"></td>
															<td>
																<label>$</label>
																<input min="0" class="validanumericos" type="text" name="inpFila5Monto" id="inpFila6Monto" onchange="obtenerResultados('tblContratoColegio','fila6','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][5][0];}?>"></td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila6Coef" id="inpFila6Coef" onchange="obtenerResultados('tblContratoColegio','fila6','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][5][1];}?>">
																<label>%</label>
															</td>
															<td>
																<input min="0" class="validanumericos porcentaje" type="text" name="inpFila6Recargo" id="inpFila6Recargo" onchange="obtenerResultados('tblContratoColegio','fila6','pagLiquidacion')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][5][2];}?>">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td style="border-right: solid;border-right-width: 2px;">
																<label id="lblFila6Total"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][5][3];}?></label>
															</td>
														</tr>
														<?php
														}
														?>
													</tbody>
													<tfoot>
														<tr>
															
														</tr>
													</tfoot>
												</table>
												<table class="text-center pull-right tablaTotal" border="2" style="width: 51.4%; margin-bottom: 20px;">
													<tbody>
														<tr>
															<td class="lblTotal2" colspan="4">SUBTOTAL(A)</td>
															<td style="width: 58.2%;">
																<label class="pull-left" style="margin-left: 5px;"><STRONG class="lblTotal2">$</STRONG></label>
																<STRONG class="pull-right"><label id="lblSubTotalA" class="lblTotal2">0</label></STRONG>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="table-responsive">
												<table id="tblMultas" border="2" class="tabla2 text-center" style="margin-bottom: 5px !important;">
													<thead>
														<tr>
															<th colspan="7">MULTAS</th>
														</tr>
														<tr>
															<th style="width: 15%;">MULTAS</th>
															<th style="width: 10%;">(m²)</th>
															<th style="width: 5.1%;">CANT</th>
															<th style="width: 15%;">S.M.MUNICIPAL</th>
															<th style="width: 14.9%;">PORCENTAJE</th>
															<th colspan="2" style="width: 32.3%;">TOTAL</th>
														</tr>
													</thead>
													<tbody>
														<tr id="filaFOS">
															<td>F.O.S</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][0];}?>" class="validanumericos" type="text" name="inpFOSM2" id="inpFOSm2" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][1];}?>" class="validanumericos" type="text" name="inpFOSCant" id="inpFOSCant" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
															</td>
															<td>
																<label>$</label>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][2];}?>" class="validanumericos" type="text" name="inpFOSSMMun" id="inpFOSSMMun" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')" disabled>
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][3];}?>" class="validanumericos porcentaje" type="text" name="inpFOSPorcentaje" id="inpFOSPorcentaje" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
																<label>%</label>
															</td>
															<td style="width: 4.8%;">
																<label>$</label>
															</td>
															<td>
																<label id="lblFOSTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][4];}?></label>
															</td>
														</tr>
														<tr id="filaFOT">
															<td>F.O.T</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][0];}?>" class="validanumericos" type="text" name="inpFOTM2" id="inpFOTM2" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][1];}?>" class="validanumericos" type="text" name="inpFOTCant" id="inpFOTCant" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
															</td>
															<td>
																<label>$</label>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][2];}?>" class="validanumericos" type="text" name="inpFOTSMMun" id="inpFOTSMMun" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')" disabled>
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][3];}?>" class="validanumericos porcentaje" type="text" name="inpFOTPorcentaje" id="inpFOTPorcentaje" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td>
																<label id="lblFOTTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][4];}?></label>
															</td>
														</tr>
														<tr id="filaRetiros">
															<td>RETIROS</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][0];}?>" class="validanumericos" type="text" name="inpRetirosM2" id="inpRetirosM2" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][1];}?>" class="validanumericos" type="text" name="inpRetirosCant" id="inpRetirosCant" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
															</td>
															<td>
																<label>$</label>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][2];}?>" class="validanumericos" type="text" name="inpRetirosSMMun" id="inpRetirosSMMun" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')" disabled>
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][3];}?>" class="validanumericos porcentaje" type="text" name="inpRetirosPorcentaje" id="inpRetirosPorcentaje" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td>
																<label id="lblRetirosTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][4];}?></label>
															</td>
														</tr>
														<tr id="filaDencidad">
															<td>DENSIDAD</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][0];}?>" class="validanumericos" type="text" name="inpDencidadM2" id="inpDencidadM2" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][1];}?>" class="validanumericos" type="text" name="inpDencidadCant" id="inpDencidadCant" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
															</td>
															<td>
																<label>$</label>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][2];}?>" class="validanumericos" type="text" name="inpDencidadSMMun" id="inpDencidadSMMun" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')" disabled>
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][3];}?>" class="validanumericos porcentaje" type="text" name="inpDencidadPorcentaje" id="inpDencidadPorcentaje" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td>
																<label id="lblDencidadTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][4];}?></label>
															</td>
														</tr>
														<tr id="filaDTO">
															<td>DTO.1281/14</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][0];}?>" class="validanumericos" type="text" name="inpDtoM2" id="inpDtoM2" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][1];}?>" class="validanumericos" type="text" name="inpDtoCant" id="inpDtoCant" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
															</td>
															<td>
																<label>$</label>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][2];}?>" class="validanumericos" type="text" name="inpDtoSMMun" id="inpDtoSMMun" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')" disabled>
															</td>
															<td>
																<input min="0" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][3];}?>" class="validanumericos porcentaje" type="text" name="inpDtoPorcentaje" id="inpDtoPorcentaje" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
																<label>%</label>
															</td>
															<td>
																<label>$</label>
															</td>
															<td>
																<label id="lblDtoTotal"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][4];}?></label>
															</td>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															
														</tr>
													</tfoot>
												</table>
												<table class="text-center pull-right tablaTotal" border="2" style="width: 51.4%; margin-bottom: 20px;">
													<tbody>
														<tr>
															<td class="lblTotal2" colspan="5">SUBTOTAL(B)</td>
															<td style="width: 58.3%;">
																<label class="pull-left" style="margin-left: 5px;"><STRONG class="lblTotal2">$</STRONG></label>
																<label id="lblSubTotalB" class="lblTotal2 pull-right">0</label>
															</td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="table-responsive">
												<table border="2" class="tabla2 tablaTotal text-center">
													<tbody>
														<tr>
															<td colspan="2" style="width: 26.3%;">SUBTOTAL (A) + (B)</td>
															<td style="width: 13%;">DESCUENTO</td>
															<td rowspan="2" style="width: 13%;"><label class="lblTotal2">TOTAL A<br> ABONAR</label></td>
															<td rowspan="2" class="tdContactado" style="width: 2% !important;"><label class="lblContactado">CONTADO</label></td>
															<td rowspan="2" style="width: 23.8%"><label class="pull-left lblTotal" style="margin-left: 5px;">$</label><label class="lblTotal pull-right" id="lblTotalAbonar"><?php if(isset($_SESSION['TOTAL'])){ echo $_SESSION['TOTAL'];}?></label>
														</tr>
														<tr>
															<td>$</td>
															<td><label id="lblSubTotalAB"></label></td>
															<td><input class="validanumericos porcentaje" min="0" value="<?php if(isset($_SESSION['DESCUENTO'])){ echo $_SESSION['DESCUENTO'];}?>" type="text" name="inpDescuento" id="inpDescuento" onchange="obtenerResultados('','','pagLiquidacion')"><span>%</span></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="table-responsive">
												<table class="tabla2 text-center" border="2">
													<thead>
														<th>2019</th>
													</thead>
													<tbody>
														<tr>
															<td>En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.</td>
														</tr>
														<tr>
															<td>Los Derechos de Construccion se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del Expediente.</td>
														</tr>
														<tr>
															<td>La liquidación será ratificada previo a la aprobación.</td>
														</tr>
														<tr>
															<td style="padding: 5rem; height: 170px"></td>
														</tr>
													</tbody>
													<tfoot>
														<tr>
															<td><label class="pull-left">FIRMA DEL LIQUIDADOR (1)</label><label class="pull-right">FIRMA DEL LIQUIDADOR (2)</label></td>
														</tr>
													</tfoot>
												</table>
											</div>
											<div id="resultados"></div>
											<?php 
												if(!isset($_SESSION['TipoConsulta'])){?>
													<input type="button" class="btn btn-primary pull-right" name="bttnCargar" id="bttnCargar" value="Modificar Liquidacion" onclick="CargarLiquidacion_Normal_Mora_Art126('ModificarLiquidacion')">
												<?php
												}
												else{?>
											
													<input type="button" class="btn btn-primary pull-left hidden" name="bttnPDF" id="bttnPDF" value="Descargar PDF" onclick="imprimirPDF('FormModificacion')">
												<?php
												}
											?>
										</form>
									</div>
								</div>
							</div>
						</body>
						</html>
						<script language="javascript" src="../js/jquery-3.2.1.min.js"></script>
						<script language="javascript" src="../js/funciones.js?v=<?php echo time(); ?>"></script>
						<script language="javascript" src="../js/funcionesPHP.js?v=<?php echo time(); ?>"></script>
						<script language="javascript" src="../js/tableToExcel.js?v=<?php echo time(); ?>"></script>
<?php
					}
				}
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