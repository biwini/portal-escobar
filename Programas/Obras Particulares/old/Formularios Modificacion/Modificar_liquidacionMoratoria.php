<?php
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				$pagMoratoria = true;
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
					<?php 
					}
					else{?>
						<link href="../css/header2.css" rel="stylesheet" type="text/css" media="print">
					<?php
					}
					?>
					<script type="text/javascript">
						$(document).ready(function(){
							obtenerResultados('tblContratoColegio','contraColFila1','pagLiqMonita')
							obtenerResultados('tblContratoColegio','contraColFila2','pagLiqMonita');
							obtenerResultados('tblContratoColegio','contraColFila3','pagLiqMonita');
							obtenerResultados('tblContratoColegio','contraColFila4','pagLiqMonita')
							obtenerResultados('tblContratoColegio','contraColFila5','pagLiqMonita')
							obtenerResultados('tblMultas','filaFOS','pagLiqMonita');
							obtenerResultados('tblMultas','filaFOT','pagLiqMonita');
							obtenerResultados('tblMultas','filaRetiros','pagLiqMonita');
							obtenerResultados('tblMultas','filaDencidad','pagLiqMonita');
							obtenerResultados('tblMultas','filaDTO','pagLiqMonita');
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
					<div class="container colorContainer nover">
						<div class="col-lg-12">
							<div class="table-responsive">
								<!--Encabezado-->
								<?php include("Modificar_header.php");?>
								<!--Fin Encabezdo-->
							</div>
							<div class="table-responsive">
								<table id="tblContratoColegio" border="1" class="tablaCondonacion table text-center pull-left tablaPrincipal">
									<thead>
										<tr>
											<th colspan="8">CONTRATO DEL COLEGIO</th>
										</tr>
										<tr>
											<th colspan="2">MONTO DE OBRA</th>
											<th>COEF.%</th>
											<th>RECARGO</th>
											<th style="min-width: 100px" colspan="2">TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<tr id="contraColFila1">
											<td><STRONG></STRONG></td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpMontoFila1" id="inpMontoFila1" onchange="obtenerResultados('tblContratoColegio','contraColFila1','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][0];}?>">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpCoefFila1" id="inpCoefFila1" onchange="obtenerResultados('tblContratoColegio','contraColFila1','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][1];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRecargoFila1" id="inpRecargoFila1" onchange="obtenerResultados('tblContratoColegio','contraColFila1','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][2];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblTotalFila1"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][3];}?></label>
											</td>
										</tr>
										<tr id="contraColFila2">
											<td><STRONG></STRONG></td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpMontoFila2" id="inpMontoFila2" onchange="obtenerResultados('tblContratoColegio','contraColFila2','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][0];}?>">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpCoefFila2" id="inpCoefFila2" onchange="obtenerResultados('tblContratoColegio','contraColFila2','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][1];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRecargoFila2" id="inpRecargoFila2" onchange="obtenerResultados('tblContratoColegio','contraColFila2','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][2];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblTotalFila2"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][3];}?></label>
											</td>
										</tr>
										<tr id="contraColFila3">
											<td><STRONG></STRONG></td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpMontoFila3" id="inpMontoFila3" onchange="obtenerResultados('tblContratoColegio','contraColFila3','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][0];}?>">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpCoefFila3" id="inpCoefFila3" onchange="obtenerResultados('tblContratoColegio','contraColFila3','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][1];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRecargoFila3" id="inpRecargoFila3" onchange="obtenerResultados('tblContratoColegio','contraColFila3','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][2];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblTotalFila3"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][3];}?></label>
											</td>
										</tr>
										<tr id="contraColFila4">
											<td><STRONG></STRONG></td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpMontoFila4" id="inpMontoFila4" onchange="obtenerResultados('tblContratoColegio','contraColFila4','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][0];}?>">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpCoefFila4" id="inpCoefFila4" onchange="obtenerResultados('tblContratoColegio','contraColFila4','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][1];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRecargoFila4" id="inpRecargoFila4" onchange="obtenerResultados('tblContratoColegio','contraColFila4','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][2];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblTotalFila4"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][3];}?></label>
											</td>
										</tr>
										<tr id="contraColFila5">
											<td><STRONG></STRONG></td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpMontoFila5" id="inpMontoFila5" onchange="obtenerResultados('tblContratoColegio','contraColFila5','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][0];}?>">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpCoefFila5" id="inpCoefFila5" onchange="obtenerResultados('tblContratoColegio','contraColFila5','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][1];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRecargoFila5" id="inpRecargoFila5" onchange="obtenerResultados('tblContratoColegio','contraColFila5','pagLiqMonita')" value="<?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][2];}?>">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblTotalFila5"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][3];}?></label>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="text-center" colspan="3"><STRONG>SUBTOTAL(A)</STRONG></td>
											<td><label><STRONG>REAL</STRONG></label>
											<td><label><STRONG>$</STRONG></label><STRONG><label id="lblSubTotalA">0</label></STRONG></td>
										</tr>
									</tfoot>
								</table>
								<table border="1" class="tablaCondonacion text-center pull-right table tabla-secundaria">
									<thead class="tablaDerechaTitulo">
										<tr>
											<th colspan="3" style="font-size: 11px;">CONDONACIÓN Ord. 5465/17</th>
										</tr>
										<tr>
											<th class="tablaDerecha">%</th>
											<th colspan="2">TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<STRONG><label id="porcenCon1"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][4];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalContraColFila1"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][5];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon2"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][4];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalContraColFila2"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][5];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon3"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][4];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalContraColFila3"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][5];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon4"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][4];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalContraColFila4"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][5];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon5"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][4];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalContraColFila5"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][5];}?></label></STRONG>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<STRONG>$</STRONG>
											</td>
											<td colspan="2">
												<STRONG><label id="condonacionContraCol">-</label></STRONG>
											</td>
										</tr>
									</tfoot>
								</table>
								<table id="tblMultas" border="1" class="tablaCondonacion table text-center pull-left tablaPrincipal" >
									<thead>
										<tr>
											<th colspan="7">MULTAS</th>
										</tr>
										<tr>
											<th>MULTAS</th>
											<th style="width: 150px;">(m²)</th>
											<th style="width: 150px;">CANT</th>
											<th style="width: 100px;">S.M.MUNICIPAL</th>
											<th style="width: 100px;">PORCENTAJE</th>
											<th colspan="2">TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<tr id="filaFOS">
											<td><STRONG>F.O.S</STRONG></td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOSM2" id="inpFOSm2" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][0];}?>" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOSCant" id="inpFOSCant" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][1];}?>" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqMonita')">
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpFOSSMMun" id="inpFOSSMMun" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][2];}?>" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOSPorcentaje" id="inpFOSPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][3];}?>" onchange="obtenerResultados('tblMultas','filaFOS','pagLiqMonita')">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblFOSTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][4];}?></label>
											</td>
										</tr>
										<tr id="filaFOT">
											<td><STRONG>F.O.T</STRONG></td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOTM2" id="inpFOTM2" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][0];}?>" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOTCant" id="inpFOTCant" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][1];}?>" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqMonita')">
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpFOTSMMun" id="inpFOTSMMun" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][2];}?>" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpFOTPorcentaje" id="inpFOTPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][3];}?>" onchange="obtenerResultados('tblMultas','filaFOT','pagLiqMonita')">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblFOTTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][1][4];}?></label>
											</td>
										</tr>
										<tr id="filaRetiros">
											<td><STRONG>RETIROS</STRONG></td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRetirosM2" id="inpRetirosM2" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][0];}?>" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRetirosCant" id="inpRetirosCant" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][1];}?>" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqMonita')">
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpRetirosSMMun" id="inpRetirosSMMun" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][2];}?>" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpRetirosPorcentaje" id="inpRetirosPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][3];}?>" onchange="obtenerResultados('tblMultas','filaRetiros','pagLiqMonita')">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblRetirosTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][2][4];}?></label>
											</td>
										</tr>
										<tr id="filaDencidad">
											<td><STRONG>DENSIDAD</STRONG></td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDencidadM2" id="inpDencidadM2" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][0];}?>" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDencidadCant" id="inpDencidadCant" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][1];}?>" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqMonita')">
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpDencidadSMMun" id="inpDencidadSMMun" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][2];}?>" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDencidadPorcentaje" id="inpDencidadPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][3];}?>" onchange="obtenerResultados('tblMultas','filaDencidad','pagLiqMonita')">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblDencidadTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][3][4];}?></label>
											</td>
										</tr>
										<tr id="filaDTO">
											<td><STRONG>DTO.1281/14</STRONG></td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDtoM2" id="inpDtoM2" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][0];}?>" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDtoCant" id="inpDtoCant" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][1];}?>" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqMonita')">
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<input class="validanumericos" type="text" min="0" name="inpDtoSMMun" id="inpDtoSMMun" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][2];}?>" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqMonita')">
											</td>
											<td>
												<input class="validanumericos" type="text" min="0" name="inpDtoPorcentaje" id="inpDtoPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][3];}?>" onchange="obtenerResultados('tblMultas','filaDTO','pagLiqMonita')">
												<label><STRONG>%</STRONG></label>
											</td>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblDtoTotal"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][4][4];}?></label>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td class="text-right" colspan="4"><STRONG>SUBTOTAL(B)</STRONG></td>
											<td><label><STRONG>REAL</STRONG></label>
											<td>
												<label><STRONG>$</STRONG></label>
												<label id="lblSubTotalB">0</label>
											</td>
										</tr>
									</tfoot>
								</table>
								<table border="1" class="tablaCondonacion text-center pull-right table tabla-secundaria">
									<thead class="tablaDerechaTitulo">
										<tr>
											<th colspan="3" style="font-size: 11px;">CONDONACIÓN Ord.5465/17</th>
										</tr>
										<tr>
											<th class="tablaDerecha">%</th>
											<th colspan="2">TOTAL</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<STRONG><label id="porcenCon6"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][5];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label></td>
											<td>
												<STRONG><label id="totalMultaFila1"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][6];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon7"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][5];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label>
											</td>
											<td>
												<STRONG><label id="totalMultaFila2"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][1][6];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon8"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][5];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label>
											</td>
											<td>
												<STRONG><label id="totalMultaFila3"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][2][6];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon9"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][5];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label>
											</td>
											<td>
												<STRONG><label id="totalMultaFila4"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][3][6];}?></label></STRONG>
											</td>
										</tr>
										<tr>
											<td>
												<STRONG><label id="porcenCon10"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][5];}?></label></STRONG>
											</td>
											<td><label><STRONG>$</STRONG></label>
											</td>
											<td>
												<STRONG><label id="totalMultaFila5"><?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][4][6];}?></label></STRONG>
											</td>
										</tr>
									</tbody>
									<tfoot>
										<tr>
											<td>
												<STRONG>$</STRONG>
											</td>
											<td colspan="2">
												<STRONG><label id="condonacionMultas">-</label></STRONG>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="table-responsive">
								<table border="1" class="tabla table text-center">
									<tbody>
										<tbody>
										<tr>
											<td colspan="2"><STRONG>SUBTOTAL (A) + (B)</STRONG></td>
											<td><STRONG>DESCUENTO</STRONG></td>
											<td rowspan="2"><STRONG class="lblTotal2">TOTAL A<br> ABONAR</STRONG></td>
											<td rowspan="2" class="tdContactado"><STRONG><label class="lblContactado">CONTACTADO</label></STRONG></td>
											<td rowspan="2"><STRONG class="pull-left lblTotal2">$</STRONG><label class="pull-right" id="lblTotalAbonar"><?php if(isset($_SESSION['TOTAL'])){ echo $_SESSION['TOTAL'];}?></label>
										</tr>
										<tr>
											<td><STRONG>$</STRONG></td>
											<td><label id="lblSubTotalAB"><?php if(isset($_SESSION['ARRAY_CONTRATO'])){ echo $_SESSION['ARRAY_CONTRATO'][0][3];}?></label></td>
											<td><input class="validanumericos" type="text" min="0" name="inpDescuento" id="inpDescuento" value="<?php if(isset($_SESSION['DESCUENTO'])){ echo $_SESSION['DESCUENTO'];}?>" onchange="obtenerResultados('','','pagLiqMonita')"><STRONG>%</STRONG></td>
										</tr>
									</tbody>
								</table>
							</div>
								<div class="table-responsive">
									<table class="tabla table text-center">
										<tr style="background-color: grey;">
											<td>
												<label class="pull-left"><STRONG>PERIODO DE APLICACION </STRONG></label>
											</td>
											<td>
												<input style="background-color: grey;" class="validanumericos" type="text" min="0" name="lblPorcentaje" id="lblPorcentaje" value="<?php if(isset($_SESSION['ARRAY_MULTAS'])){ echo $_SESSION['ARRAY_MULTAS'][0][5];}?>" onchange="actualizarPorcentajeCondonacion()">
												<STRONG><label>%</label></STRONG>
											</td>
											<td>
												<label class="pull-right"><STRONG> CONDONACIÓN según Ord. 5465/17</STRONG></label>
											</td>
										</tr>
									</table>
								</div>
								<div class="text-center">
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
								<br>
								<?php 
									if(!isset($_SESSION['TipoConsulta'])){?>
										<input type="button" class="btn btn-primary pull-right" name="bttnCargar" id="bttnCargar" value="Modificar Liquidacion" onclick="CargarLiquidacion_Normal_Mora_Art126('ModificarLiquidacionMoratoria')">
									<?php
									}
									else{?>
								
										<input type="button" class="btn btn-primary pull-left hidden" name="bttnPDF" id="bttnPDF" value="Descargar PDF" onclick="imprimirPDF('FormModificacion')" style="display: none;">
									<?php
									}
								?>
							</div>
						</div>
					</div>
					<script type="text/javascript">
						console.table(<?php echo json_encode( $_SESSION['ARRAY_MULTAS']); ?>);
					</script>
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