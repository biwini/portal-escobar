<?php
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				if(isset($pagMoratoria)){
					if($pagMoratoria !=true){
						$pagMoratoria = false;
					}
				}
				else{
					$pagMoratoria = false;
				}
?>
				<div class="table-responsive">
					<div class="table-responsive">
						<table class="tabla" border="2" width="100%">
							<thead>
								<tr>
									<th style="width: 32.6%;border-width: 2px;"><img class="img-fluid" style="width: 190px;height: 113px;" src="../imagenes/logo-escobar2.jpg" alt="logo"></th>
									<th>
										<p class="text-center" style="font-size: 36px;">MUNICIPALIDAD DE ESCOBAR</p>
										<span class="text-center" style="font-size: 16px;font-weight: normal;">SECRETARIO DE PLANIFICACION E INFRAESTRUCTURA<br>DIRECCION DE OBRAS PARTICULARES</span>
									</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<?php
									if (!$pagMoratoria) {?>
										<td colspan="2" class="tablaTotal" style="background-color: lightgray;"><p class="h2 text-center info" style="background-color: lightgrey; font-size: 24px;">LIQUIDACION DE DERECHOS DE CONSTRUCCION (CAP. IX)</p></td>
									<?php
									}
									else {?>
										<td colspan="2" class="tablaTotal" style="background-color: lightgray;"><p class="h2 label-info text-center info" style="font-size: 24px;">LIQUIDACIÓN DE DERECHOS DE CONSTRUCCIÓN (CAP. IX)</p></td>
										<td colspan="2" class="tablaTotal" style="background-color: lightgray;"><p class="h3 label-info text-center info" style="font-size: 16px;">RÉGIMEN ESPECIAL DE REGULARIZACIÓN DE OBRAS PARTICULARES</p></td>
									<?php
									}
									?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table class="tabla2 text-center" border="1">
							<tbody>
								<tr id="bttnCambiarValores">
									<?php if(!isset($_SESSION['TipoConsulta'])){?>
									<td colspan="2">
										<input type="button" class="btn btn-warning" name="" value="Cambiar valores" onclick="cambiarValor('headerModificacion')"><br>
										<div id="mensajeCabezera"></div>
									</td>
									<?php } ?>
								</tr>
								<tr>
									<td class="" style="width: 32.6%;">FECHA</td>
									<td><input class="input" style="text-align: center;" type="date" name="inpFecha" id="inpFecha" disabled="true" value="<?php if(isset($_SESSION['FechaLiquidacion'])){ echo $_SESSION['FechaLiquidacion'];}?>"></td>
								</tr>
								<tr>
									<td class="" style="height: 80px;">NOMBRE Y APELLIDO </td>
									<td><input type="text" style="text-align: center;font-weight: bold;" class="txtHeader nombre upper" disabled="true" name="inpNombre" id="inpNombre" disabled="true" value="<?php if(isset($_SESSION['NombreCliente'])){ echo $_SESSION['NombreCliente'];}?>">
								</tr>
							</tbody>
						</table>	
					</div>
					<div class="table-responsive">
						<table border="2" class="tabla2 nomenclatura text-center">
							<thead>
								<tr>
									<th colspan="5">NOMENCLATURA CATASTRAL</th>
								</tr>
								<tr>
									<th>CIRC.</th>
									<th>SECCIÓN</th>
									<th>FRACCIÓN</th>
									<th>CHACRA</th>
									<th style="width: 32% !important;">PARTIDA</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpCirc" id="inpCirc" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][1];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpSeccion" id="inpSeccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][3];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpFraccion" id="inpFraccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][4];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpChacra" id="inpChacra" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][5];}?>"></td>
									<td rowspan="3"><input type="text" class="txtHeader upper" id="inpPartida" disabled="true" name="inpPartida" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][8];}?>" style="height: 100px;width: 100%;font-size: 26px;font-weight: bold;"></td>
								</tr>
								<tr>
									<td><strong>QUINTA</strong></td>
									<td><strong>MANZANA</strong></td>
									<td><strong>Parcela</strong></td>
									<td><strong>UF</strong></td>
								</tr>
								<tr>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpQuinta" id="inpQuinta" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][2];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpManzana" id="inpManzana" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][0];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpParcela" id="inpParcela" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][6];}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader upper" disabled="true" name="inpUF" id="inpUF" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][7];}?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table border="2" class="tabla2 zonificacion text-center" >
							<thead>
								<tr>
									<th colspan="5" class="text-center">ZONIFICACION</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><STRONG>RURAL/COMPLEM SEMIURB-IND</STRONG></td>
									<td><STRONG>URBANA</STRONG></td>
									<td><STRONG>RESIDENCIAL EXTRAURBANA</STRONG></td>
									<td><STRONG>CLUB DE CAMPO</STRONG></td>
									<td rowspan="2" style="width: 32% !important;"><STRONG>DE4</STRONG></td>
								</tr>
								<tr>
									<td><input style="font-weight: bold;" type="text" class="txtHeader" disabled="true" name="inpRuralComp" id="inpRuralComp" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'RURAL'){ echo 'X';}}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader" disabled="true" name="inpUrbana" id="inpUrbana" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'URBANA'){ echo 'X';}}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader" disabled="true" name="inpResExtraUrb" id="inpResExtraUrb" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'RESIDENCIAL'){ echo 'X';}}?>"></td>
									<td><input style="font-weight: bold;" type="text" class="txtHeader" disabled="true" name="inpClubCampo" id="inpClubCampo" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'CLUB'){ echo 'X';}}?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
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