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
									<table border="1" class="tabla2 text-center">
										<thead>
											<tr>
												<th colspan="6"><STRONG>LIQUIDACION</STRONG></th>
											</tr>
											<tr>
												<th>TIPO</th>
												<th>MONTO OBRA</th>
												<th>COEF.%</th>
												<th>RECARGO</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<input type="text" name="inpTipo" id="inpTipo" class="upper">
												</td>
												<td>
													<label>$</label>
													<input type="number" name="inpMonto" min="0" id="inpMonto" value="0" onchange="calcularLiquidacion('','pagLiqCarteles')">
												</td>
												<td>
													<input class="validanumericos porcentaje" type="number" min="0" name="inpCoef" id="inpCoef" value="0" onchange="calcularLiquidacion('','pagLiqCarteles')">
													<label>%</label>
												</td>
												<td>
													$<input class="validanumericos" type="number" min="0" name="inpRecargo" id="inpRecargo" value="0" onchange="calcularLiquidacion('','pagLiqCarteles')">
												</td>
												<td>
													<STRONG><label>$</label></STRONG>
												</td>
												<td>
													<label name="lblTotalLiquidacion" id="lblTotalLiquidacion">0</label>
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
												<td rowspan="2"><STRONG class="pull-left lblTotal">$</STRONG><label class="pull-right lblTotal" id="lblTotalAbonar">0</label>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="text-center">
									<h3>PLANO SEGURIDAD CONTRA INCENDIO</h3>
									<h4 class="h4">ORDENANZA 5372/16</h4>
								</div>
								<div class="text-center">
									<label><STRONG>FIRMA DEL LIQUIDADOR</STRONG></label>
									<textarea rows="8" cols="60" name="textareaFirma" disabled="true">En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.
Los Derechos de Construcci??n se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciaci??n del Expediente.
La liquidaci??n ser?? ratificada previo a la aprobaci??n.
									</textarea>
								</div>
								<input type="button" class="btn btn-primary pull-right" id="bttnCargar" name="cargar" value="Cargar Liquidacion" onclick="cargarLiquidacionCarteles('liquidacionCarteles')">
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