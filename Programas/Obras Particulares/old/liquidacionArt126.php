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
					<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
					<script language="javascript" src="js/funciones.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/funcionesPHP.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/tableToExcel.js?v=<?php echo time(); ?>"></script>
					<link href="css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
					<link href="css/header.css" rel="stylesheet" type="text/css" media="print">
				</head>
				<body>
					<div class="container colorContainer nover">
						<div class="col-lg-12">
							<div class="table-responsive">
								<!--Encabezado-->
								<?php include("header.php");?>
								<!--Fin Encabezado-->
								<div class="table-responsive">
									<table id="tblMontoContratoColegio" border="1" class="tabla2 text-center">
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
													<input class="validanumericos" type="number" min="0" name="inpCubiertoM2" id="inpCubiertoM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="0">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCubiertoCoef" id="inpCubiertoCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCubiertoURef" id="inpCubiertoURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblCubiertoTotal">0</label>
												</td>
											</tr>
											<tr id="filaSemiCub">
												<td><STRONG>SEMICUBIERTO</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" min="0" name="inpSemiCubM2" id="inpSemiCubM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="0">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpSemiCubCoef" id="inpSemiCubCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpSemiCubURef" id="inpSemiCubURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblSemiCubTotal">0</label>
												</td>
											</tr>
											<tr id="filaPileta">
												<td><STRONG>PILETA</STRONG></td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" min="0" name="inpPiletaM2" id="inpPiletaM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="0">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpPiletaCoef" id="inpPiletaCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpPiletaURef" id="inpPiletaURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaPileta','pagLiqArt126')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblPiletaTotal">0</label>
												</td>
											</tr>
										</tbody>
										<tfoot>
											<tr>
												<td class="text-right" colspan="4"><STRONG class="lblTotal2">MONTO OBRA</STRONG></td>
												<td>
													<label><STRONG class="lblTotal2">$</STRONG></label>
													<STRONG><label id="lblTotalMontoObra" class="lblTotal2">0</label></STRONG>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
								<div class="table-responsive">
									<table id="tblContratoColegio" border="1" class="tabla2 text-center">
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
													<label id="lblDeclararMonto">0</label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpDeclararCoef" id="inpDeclararCoef" onchange="calcularSubTotalArt126()" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpDeclararRecargo" id="inpDeclararRecargo" value="0" onchange="calcularSubTotalArt126()">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDeclararTotal">0</label>
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
									<table id="tblMultas" border="1" class="tabla2 text-center">
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
													<input class="validanumericos" type="number" value="0" min="0" name="inpFOSM2" id="inpFOSm2" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
												</td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpFOSCant" id="inpFOSCant" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" min="0" name="inpFOSSMMun" id="inpFOSSMMun" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')" disabled>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" value="0" min="0" name="inpFOSPorcentaje" id="inpFOSPorcentaje" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblFOSTotal">0</label>
												</td>
											</tr>
											<tr id="filaFOT">
												<td><STRONG>F.O.T</STRONG></td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpFOTM2" id="inpFOTM2" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
												</td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpFOTCant" id="inpFOTCant" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" min="0" name="inpFOTSMMun" id="inpFOTSMMun" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')" disabled>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" value="0" min="0" name="inpFOTPorcentaje" id="inpFOTPorcentaje" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblFOTTotal">0</label>
												</td>
											</tr>
											<tr id="filaRetiros">
												<td><STRONG>RETIROS</STRONG></td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpRetirosM2" id="inpRetirosM2" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
												</td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpRetirosCant" id="inpRetirosCant" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" min="0" name="inpRetirosSMMun" id="inpRetirosSMMun" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')" disabled>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" value="0" min="0" name="inpRetirosPorcentaje" id="inpRetirosPorcentaje" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblRetirosTotal">0</label>
												</td>
											</tr>
											<tr id="filaDencidad">
												<td><STRONG>DENSIDAD</STRONG></td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpDencidadM2" id="inpDencidadM2" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
												</td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpDencidadCant" id="inpDencidadCant" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" min="0" name="inpDencidadSMMun" id="inpDencidadSMMun" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')" disabled>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" value="0" min="0" name="inpDencidadPorcentaje" id="inpDencidadPorcentaje" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDencidadTotal">0</label>
												</td>
											</tr>
											<tr id="filaDTO">
												<td><STRONG>DTO.1281/14</STRONG></td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpDtoM2" id="inpDtoM2" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
												</td>
												<td>
													<input class="validanumericos" type="number" value="0" min="0" name="inpDtoCant" id="inpDtoCant" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<input class="validanumericos" type="number" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" min="0" name="inpDtoSMMun" id="inpDtoSMMun" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')" disabled>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" value="0" min="0" name="inpDtoPorcentaje" id="inpDtoPorcentaje" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqArt126')">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblDtoTotal">0</label>
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
									<table border="1" class="tabla2 text-center">
										<tbody>
											<tr>
												<td colspan="2"><STRONG>SUBTOTAL (A) + (B)</STRONG></td>
												<td rowspan="2"><STRONG><label class="lblTotal2">TOTAL A<br> ABONAR</label></STRONG></td>
												<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
												<td rowspan="2"><STRONG><label class="lblTotal2">$</label></STRONG></td>
												<td rowspan="2"><label class="lblTotal2" id="lblTotalAbonar">0</label>
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
								<input type="button"  class="btn btn-primary pull-right" id="bttnCargar" name="cargar" value="Cargar Liquidacion" onclick="CargarLiquidacion_Normal_Mora_Art126('liquidacionArt126')">
								<div id="resultados" class="center-block"></div>
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