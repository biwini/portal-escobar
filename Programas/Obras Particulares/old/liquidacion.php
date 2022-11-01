<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
?>
				<!DOCTYPE html>
				<html>
				<head>
					<title></title>
					<link href="css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link href="css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
					<link rel="stylesheet" type="text/css" href="css/planilla.css" media="print">
					<link href="css/header.css" rel="stylesheet" type="text/css" media="print">
				</head>
				<body>
					<div class="container colorContainer nover">
						<div class="col-lg-12">
							<div class="table-responsive">
								<header>
									<!--Encabezado-->
									<?php include("header.php");?>
									<!--Fin Encabezado-->
								</header>
								<form method="post" action="php/cargarLiquidacion.php">
									<div class="table-responsive">
										<input type="button" name="" value="Agregar Fila" onclick="AgregarFilaLiqNormal()">
										<input type="button" name="" value="Quitar fila" onclick="QuitarFilaLiqNormal()">
										<table id="tblContratoColegio" border="2" class="tabla2 text-center" style="margin-bottom: 5px !important;">
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
												<tr id="filaNuevo">
													<td class="tdInp" style="width: 14%;"><input type="text" class="inpContrato upper" name="inpFila1" id="inpFila1"></td>
													<td style="width: 24.3%;">
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpNuevoMonto" id="inpNuevoMonto" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="0" required="true">
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpNuevoCoef" id="inpNuevoCoef" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpNuevoRecargo" id="inpNuevoRecargo" onchange="obtenerResultados('tblContratoColegio','filaNuevo','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td style="width: 3%;">
														<label><STRONG>$</STRONG></label>
													</td>
													<td style="width: 27.1%;">
														<label id="lblNuevoTotal">0</label>
													</td>
												</tr>
												<tr id="filaDemoler">
													<td><input type="text" class="inpContrato upper" name="inpFila2" id="inpFila2"></td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpDemolerNuevo" id="inpDemolerNuevo" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="0">
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDemolerCoef" id="inpDemolerCoef" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDemolerRecargo" id="inpDemolerRecargo" onchange="obtenerResultados('tblContratoColegio','filaDemoler','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblDemolerTotal">0</label>
													</td>
												</tr>

												<tr id="filaDeclarar">
													<td><input type="text" class="inpContrato upper" name="inpFila3" id="inpFila3"></td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpDeclararNuevo" id="inpDeclararNuevo" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="0">
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDeclararCoef" id="inpDeclararCoef" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<input min="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDeclararRecargo" id="inpDeclararRecargo" onchange="obtenerResultados('tblContratoColegio','filaDeclarar','pagLiquidacion')" value="0">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblDeclararTotal">0</label>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<!-- <td class="text-right lblTotal2" colspan="4"><STRONG>SUBTOTAL(A)</STRONG></td>
													<td>
														<label><STRONG class="lblTotal2">$</STRONG></label>
													</td>
													<td>
														<STRONG><label id="lblSubTotalA" class="lblTotal2">0</label></STRONG>
													</td> -->
												</tr>
											</tfoot>
										</table>
										<table class="text-center pull-right tablaTotal" border="2" style="width: 46%; margin-bottom: 20px;">
											<tbody>
												<tr>
													<td class="lblTotal2" colspan="4"><STRONG>SUBTOTAL(A)</STRONG></td>
													<td style="width: 59.1%;">
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
													<th style="width: 14%;">MULTAS</th>
													<th>(m²)</th>
													<th style="width: 6%;">CANT</th>
													<th style="width: 15.9%;">S.M.MUNICIPAL</th>
													<th style="width: 15.9%;">PORCENTAJE</th>
													<th colspan="2">TOTAL</th>
												</tr>
											</thead>
											<tbody>
												<tr id="filaFOS">
													<td><STRONG>F.O.S</STRONG></td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpFOSM2" id="inpFOSm2" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
													</td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpFOSCant" id="inpFOSCant" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpFOSSMMun" id="inpFOSSMMun" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')" disabled>
													</td>
													<td>
														<input min="0" value="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpFOSPorcentaje" id="inpFOSPorcentaje" onchange="obtenerResultados('tblMultas','filaFOS','pagLiquidacion')">
														<label><STRONG>%</STRONG></label>
													</td>
													<td style="width: 3%;">
														<label><STRONG>$</STRONG></label>
													</td>
													<td style="width: 27.1%;">
														<label id="lblFOSTotal">0</label>
													</td>
												</tr>
												<tr id="filaFOT">
													<td><STRONG>F.O.T</STRONG></td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpFOTM2" id="inpFOTM2" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
													</td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpFOTCant" id="inpFOTCant" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpFOTSMMun" id="inpFOTSMMun" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')" disabled>
													</td>
													<td>
														<input min="0" value="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpFOTPorcentaje" id="inpFOTPorcentaje" onchange="obtenerResultados('tblMultas','filaFOT','pagLiquidacion')">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblFOTTotal">0</label>
													</td>
												</tr>
												<tr id="filaRetiros">
													<td><STRONG>RETIROS</STRONG></td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpRetirosM2" id="inpRetirosM2" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
													</td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpRetirosCant" id="inpRetirosCant" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpRetirosSMMun" id="inpRetirosSMMun" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')" disabled>
													</td>
													<td>
														<input min="0" value="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpRetirosPorcentaje" id="inpRetirosPorcentaje" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiquidacion')">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblRetirosTotal">0</label>
													</td>
												</tr>
												<tr id="filaDencidad">
													<td><STRONG>DENSIDAD</STRONG></td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpDencidadM2" id="inpDencidadM2" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
													</td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpDencidadCant" id="inpDencidadCant" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" class="validanumericos" type="number" name="inpDencidadSMMun" id="inpDencidadSMMun" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')" disabled>
													</td>
													<td>
														<input min="0" value="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDencidadPorcentaje" id="inpDencidadPorcentaje" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiquidacion')">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblDencidadTotal">0</label>
													</td>
												</tr>
												<tr id="filaDTO">
													<td><STRONG>DTO.1281/14</STRONG></td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpDtoM2" id="inpDtoM2" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
													</td>
													<td>
														<input min="0" value="0" class="validanumericos" type="number" name="inpDtoCant" id="inpDtoCant" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
														<input min="0" lass="validanumericos" type="number" name="inpDtoSMMun" id="inpDtoSMMun" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')" disabled>
													</td>
													<td>
														<input min="0" value="0" class="validanumericos porcentaje" min="0" max="100" type="number" name="inpDtoPorcentaje" id="inpDtoPorcentaje" onchange="obtenerResultados('tblMultas','filaDTO','pagLiquidacion')">
														<label><STRONG>%</STRONG></label>
													</td>
													<td>
														<label><STRONG>$</STRONG></label>
													</td>
													<td>
														<label id="lblDtoTotal">0</label>
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<!-- <td class="text-right lblTotal2" colspan="5"><STRONG>SUBTOTAL(B)</STRONG></td>
													<td>
														<label><STRONG class="lblTotal2">$</STRONG></label>
													</td>
													<td>
														<label id="lblSubTotalB" class="lblTotal2">0</label>
													</td> -->
												</tr>
											</tfoot>
										</table>
										<table class="text-center pull-right tablaTotal" border="2" style="width: 46%; margin-bottom: 20px;">
											<tbody>
												<tr>
													<td class="lblTotal2" colspan="5"><STRONG>SUBTOTAL(B)</STRONG></td>
													<td style="width: 59.1%;">
														<label class="pull-left" style="margin-left: 5px;"><STRONG class="lblTotal2">$</STRONG></label>
														<label id="lblSubTotalB" class="lblTotal2 pull-right">0</label>
													</td>
												</tr>
											</tbody>
										</table>
										<!-- <div class="">
											<label class="text-right lblTotal2"><STRONG>SUBTOTAL(B)</STRONG></label>
											<label><STRONG class="lblTotal2">$</STRONG></label>
											<label id="lblSubTotalB" class="lblTotal2">0</label>	
										</div> -->
									</div>
									<div class="table-responsive">
										<table border="2" class="tabla2 text-center tablaTotal">
											<tbody>
												<tr class="">
													<td colspan="2"><STRONG>SUBTOTAL (A) + (B)</STRONG></td>
													<td><STRONG>DESCUENTO</STRONG></td>
													<td rowspan="2"><STRONG><label class="lblTotal2">TOTAL A<br> ABONAR</label></STRONG></td>
													<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTADO</label></STRONG></td>	
													<td rowspan="2"><STRONG><label class="lblTotal pull-left">$</label></STRONG><label class="lblTotal" id="lblTotalAbonar">0</label>
												</tr>
												<tr>
													<td><STRONG>$</STRONG></td>
													<td><labprogel id="lblSubTotalAB"></label></td>
													<td><input min="0" value="10" type="number" name="inpDescuento" id="inpDescuento" onchange="obtenerResultados('','','pagLiquidacion')"><label>%</label></td>
												</tr>
											</tbody>
											<tfoot></tfoot>
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
													<td style="padding: 5rem"></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td><label class="pull-left">FIRMA DEL LIQUIDADOR (1)</label><label class="pull-right">FIRMA DEL LIQUIDADOR (2)</label></td>
												</tr>
											</tfoot>
										</table>
									</div>
									<div id="resultados" class="center-block"></div>
									<input type="button" name="cargar" id="bttnPDF" value="Cargar Liquidacion" class="btn btn-primary pull-right" onclick="CargarLiquidacion_Normal_Mora_Art126('liquidacion')">
								</form>
							</div>
						</div>
					</div>
				</body>
				</html>
				<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
				<script language="javascript" src="js/jquery.cookie.js?v=<?php echo time(); ?>"></script>
				<script language="javascript" src="js/funciones.js?v=<?php echo time(); ?>"></script>
				<script language="javascript" src="js/funcionesPHP.js?v=<?php echo time(); ?>"></script>
				<script language="javascript" src="js/tableToExcel.js?v=<?php echo time(); ?>"></script>
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