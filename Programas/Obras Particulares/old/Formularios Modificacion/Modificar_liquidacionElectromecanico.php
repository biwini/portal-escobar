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
							calcularLiquidacion('filaHasta50','pagLiqElec');
							calcularLiquidacion('filaExedenteH50','pagLiqElec');
							calcularLiquidacion('filaHasta25','pagLiqElec');
							calcularLiquidacion('filaExedenteH25','pagLiqElec');
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
									<table border="1" class="tabla table text-center">
										<thead>
											<tr>
												<th colspan="6"><STRONG>LIQUIDACION</STRONG></th>
											</tr>
											<tr>
												<th>TIPO</th>
												<th>DESTINO</th>
												<th>(M²)</th>
												<th>CAP IX ($/M²)</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr id="filaHasta50">
												<td rowspan="4" class="centrarTexto" style="padding-top: 50px;">ELECTROMECANICO<br><STRONG>INDUSTRIA</STRONG></td>
												<td>HASTA 50 HP</td>
												<td>
													<input class="validanumericos" type="number" class="validanumericos" name="inpHasta50M2" id="inpHasta50M2" min="0" pattern="^[0-9]+" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][0][0];}?>" onchange="calcularLiquidacion('filaHasta50','pagLiqElec')">
												</td>
												<td>
													$<input class="validanumericos" type="number" min="0" name="inpHasta50CapIX" id="inpHasta50CapIX" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][0][1];}?>" onchange="calcularLiquidacion('filaHasta50','pagLiqElec')">
												</td>
												<td><STRONG><label>$</label></STRONG></td>
												<td>
													<label name="lblHasta50Total" id="lblHasta50Total"><?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][0][2];}?></label>
												</td>
											</tr>
											<tr id="filaExedenteH50">
												<td>EXEDENTE</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpExedente50M2" id="inpExedente50M2" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][1][0];}?>" onchange="calcularLiquidacion('filaExedenteH50','pagLiqElec')">
												</td>
												<td>
													$<input class="validanumericos" type="number" min="0" name="inpExedente50CapIX" id="inpExedente50CapIX" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][1][1];}?>" onchange="calcularLiquidacion('filaExedenteH50','pagLiqElec')">
												</td>
												<td><STRONG><label>$</label></STRONG></td>
												<td>
													<label name="lblExedente50Total" id="lblExedente50Total"><?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][1][2];}?></label>
												</td>
											</tr>
											<tr id="filaHasta25">
												<td>HASTA 25 HP</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpHasta25M2" id="inpHasta25M2" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][2][0];}?>" onchange="calcularLiquidacion('filaHasta25','pagLiqElec')">
												</td>
												<td>
													$<input class="validanumericos" type="number" min="0" name="inpHasta25CapIX" id="inpHasta25CapIX" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][2][1];}?>" onchange="calcularLiquidacion('filaHasta25','pagLiqElec')">
												</td>
												<td><STRONG><label>$</label></STRONG></td>
												<td>
													<label name="lblHasta25Total" id="lblHasta25Total"><?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][2][2];}?></label>
												</td>
											</tr>
											<tr id="filaExedenteH25">
												<td>EXEDENTE</td>
												<td>
													<input class="validanumericos" type="number" min="0" name="inpExedente25M2" id="inpExedente25M2" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][3][0];}?>" onchange="calcularLiquidacion('filaExedenteH25','pagLiqElec')">
												</td>
												<td>
													$<input class="validanumericos" type="number" min="0" name="inpExedente25CapIX" id="inpExedente25CapIX" value="<?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][3][1];}?>" onchange="calcularLiquidacion('filaExedenteH25','pagLiqElec')">
												</td>
												<td><STRONG><label>$</label></STRONG></td>
												<td>
													<label name="lblExedente25Total" id="lblExedente25Total"><?php if(isset($_SESSION['ARRAY_LIQ_ELECTRO'])){ echo $_SESSION['ARRAY_LIQ_ELECTRO'][3][2];}?></label>
												</td>
											</tr>
										</tbody>
									</table>
									
								</div>
								<div class="table-responsive">
									<table border="1" class="tabla table text-center">
										<tbody>
											<tr class="trTotal">
												<td rowspan="2"><STRONG><label class="lblTotal">TOTAL A ABONAR</label></STRONG></td>
												<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
												<td rowspan="2"><STRONG class="pull-left lblTotal">$</STRONG><label class="lblTotal" id="lblTotalAbonar">0</label>
											</tr>
										</tbody>
									</table>
								</div>
								<div class="text-center">
									<h3>PLANO ELECTROMECANICO</h3>
									<h4 class="h4">ORDENANZA 5372/16</h4>
								</div>
								<div class="text-center">
									<label><STRONG>FIRMA DEL LIQUIDADOR</STRONG></label>
									<textarea rows="8" cols="60" name="textareaFirma" disabled="true">En el computo metrico de las edificaciones quedaran incluidos los espesores de muro, los aleros, galerias y las respectivas construcciones complementarias.
Los Derechos de Construcción se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del Expediente.
La liquidación será ratificada previo a la aprobación.
									</textarea>
								</div>
								<div id="resultados"></div>
								<?php 
									if(!isset($_SESSION['TipoConsulta'])){?>
										<input type="button" class="btn btn-primary pull-right" name="bttnCargar" id="bttnCargar" value="Modificar Liquidacion" onclick="cargarLiquidacionElectromecanico('ModificarLiquidacionElectromecanico')">
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