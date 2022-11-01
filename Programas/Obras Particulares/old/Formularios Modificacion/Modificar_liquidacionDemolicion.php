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
												<th colspan="6"><STRONG>DERECHO DEMOLICION</STRONG></th>
											</tr>
											<tr>
												<th></th>
												<th>(M²)</th>
												<th>($ x M²)</th>
												<th colspan="2">TOTAL</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td rowspan="2"><STRONG>DEMOLICION</STRONG></td>
												<td><input class="validanumericos" type="number" min="0" name="inpM2" id="inpM2" value="<?php if(isset($_SESSION['ARRAY_DEMOLICION'])){ echo $_SESSION['ARRAY_DEMOLICION'][0];}?>" onchange="calcularLiquidacion('','pagDemolicion')"></td>
												<td>$<input class="validanumericos" type="number" min="0" name="inp$xm2" id="inp$xm2" value="<?php if(isset($_SESSION['ARRAY_DEMOLICION'])){ echo $_SESSION['ARRAY_DEMOLICION'][1];}?>" onchange="calcularLiquidacion('','pagDemolicion')"></td>
												<td><STRONG><label>$</label></STRONG></td>
												<td><label name="lblTotalDemolicion" id="lblTotalDemolicion"></label></td>
											</tr>
										</tbody>
									</table>
									
								</div>
								<div class="table-responsive">
									<table border="1" class="tabla table text-center tablaTotal">
										<tbody>
											<tr class="trTotal">
												<td rowspan="2"><STRONG>TOTAL A ABONAR</STRONG></td>
												<td><STRONG>DESCUENTO</STRONG><input type="number" min="0" name="inpDescuento" id="inpDescuento" value="<?php if(isset($_SESSION['ARRAY_DEMOLICION'])){ echo $_SESSION['ARRAY_DEMOLICION'][2];}?>" placeholder="">
												<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
												<td rowspan="2"><STRONG>$</STRONG></td>
												<td rowspan="2"><label id="lblTotalAbonar"></label></td>
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
				Los Derechos de Construcción se liquidaran en forma provisoria a la presentacion de los planos, debiendo realizarse su pago conjuntamente con la iniciación del Expediente.
				La liquidación será ratificada previo a la aprobación.
									</textarea>
								</div>
								<div id="resultados"></div>
								<?php 
									if(!isset($_SESSION['TipoConsulta'])){?>
										<input type="button" class="btn btn-primary pull-right" name="bttnCargar" id="bttnCargar" value="Modificar Liquidacion" onclick="cargarLiquidacionIncendio('ModificarLiquidacionDemolicion')">
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