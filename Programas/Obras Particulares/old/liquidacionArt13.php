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
													<label><STRONG>$</STRONG></label><input class="validanumericos" type="number" min="0" name="inpCubiertoM2" id="inpCubiertoM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt13')" value="0">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCubiertoCoef" id="inpCubiertoCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt13')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCubiertoURef" id="inpCubiertoURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaCubierto','pagLiqArt13')" value="0">
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
													<input class="validanumericos" type="number" min="0" name="inpSemiCubM2" id="inpSemiCubM2" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt13')" value="0">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpSemiCubCoef" id="inpSemiCubCoef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt13')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpSemiCubURef" id="inpSemiCubURef" onchange="calcularMontoObra('tblMontoContratoColegio','filaSemiCub','pagLiqArt13')" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblSemiCubTotal">0</label>
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
									<table id="tblContratoColegio" border="1" class=" tabla2 text-center">
										<thead>
											<tr>
												<th colspan="7">CONTRATO DEL COLEGIO</th>
											</tr>
											<tr>
												<th>TIPO</th>
												<th>DESTINO</th>
												<th>MONTO DE OBRA</th>
												<th>CAP IX($/M²)</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr id="filaDeclarar">
												<td><STRONG>ARTICULO 13</STRONG></td>
												<td><input type="text" id="inpArt13Destino" class="upper"></td>
												<td><STRONG>$<label id="lblDeclararMonto">0</label></STRONG></td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCapXI" id="inpCapXI" onchange="calcularTotalArt13()" value="0">
													<label><STRONG>%</STRONG></label>
												</td>
												<td>
													<label><STRONG>$</STRONG></label>
													<label id="lblArt13Total">0</label>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="table-responsive">
									<table border="1" class="tabla2 text-center tablaTotal">
										<tbody>
											<tr class="trTotal">
												<td rowspan="2"><STRONG><label class="lblTotal">TOTAL A ABONAR</label></STRONG></td>
												<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
												<td rowspan="2"><STRONG><label class="lblTotal">$</label></STRONG></td>
												<td rowspan="2"><label class="lblTotal" id="lblTotalAbonar">0</label>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="text-center">
									<h5> ART.13</h5>
									<h4 class="h4">ORDENANZA 5372/16</h4>
								</div>
								<div class="text-center">
									<label><STRONG>FIRMA DEL LIQUIDADOR</STRONG></label>
									<textarea rows="8" cols="70" name="textareaFirma" disabled="true" >En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.
Los Derechos de Construcción se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del Expediente.
La liquidación será ratificada previo a la aprobación.
									</textarea>
								</div>
								<div id="resultados" class="center-block"></div>
								<input type="button" name="cargar" class="btn btn-primary pull-right" id="bttnCargar" value="Cargar Liquidacion" onclick="CargarLiquidacionArt13('liquidacionArt13')">
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