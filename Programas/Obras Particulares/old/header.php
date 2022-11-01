<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				require("php/borrarVaribalesModificacion.php");
				if(isset($pagMoratoria)){
					if($pagMoratoria !=true){
						$pagMoratoria = false;
					}
				}
				else{
					$pagMoratoria = false;
				}
?>
				<div>
					<header id="header">
			    		<div class="menu">
					  		<ul>
				      			<li class="list-inline-item"><a class="btn btn-info" href="index.php" title="">Cabezera</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacion.php" title="">Normal</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionArt13.php" title="">Art. 13</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionArt126.php" title="">Art. 126</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionCarteles-Antena.php" title="">Carteles</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionDemolicion.php" title="">Demolicion</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionElectromecanico.php" title="">Electromecanico</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionIncendio.php" title="">Incendio</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="liquidacionMoratoria.php" title="">Moratoria</a></li>
								<li class="list-inline-item"><a class="btn btn-info" href="consultas.php" title="">Consultas</a></li>
								<?php if(isset($_SESSION["PAGINAS_PERMITIDAS"])){ ?>
									<li class="list-inline-item"><a class="btn btn-info" href="../../index.php" title="">Volver Al menu</a></li>
								<?php }?>
								<li class="list-inline-item"><a class="btn btn-info" href="../../functions/cerrarSession.php">Cerrar Session</a></li>
				    		</ul>
				    	</div>
					</header>
				</div>
				<div class="table-responsive">
					<div class="table-responsive">
						<table class="tabla" border="2" width="100%">
							<thead>
								<tr>
									<th style="width: 32.6%;border-width: 2px;"><img class="img-fluid" style="width: 190px;height: 113px;" src="imagenes/logo-escobar2.jpg" alt="logo"></th>
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
										<td colspan="" class="tablaTotal" style="background-color: lightgray;"><p class="h2 label-info text-center info" style="font-size: 24px;">LIQUIDACIÓN DE DERECHOS DE CONSTRUCCIÓN (CAP. IX)</p></td>
										<td colspan="" class="tablaTotal" style="background-color: lightgray;"><p class="h3 label-info text-center info" style="font-size: 16px;">RÉGIMEN ESPECIAL DE REGULARIZACIÓN DE OBRAS PARTICULARES</p></td>
									<?php
									}
									?>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table class="tabla2 client-name text-center" border="2">
							<tbody>
								<tr id="bttnCambiarValores">
									<td colspan="2">
										<input type="button" class="btn btn-warning" name="" value="Cambiar valores" onclick="cambiarValor('')"><br>
										<div id="mensajeCabezera"></div>
									</td>
								</tr>
								<tr>
									<td class="tdFecha" style="width: 32.6%;">FECHA</td>
									<td><input class="input" style="text-align: center;" type="date" name="inpFecha" id="inpFecha" disabled="true" value="<?php if(isset($_SESSION['FechaLiquidacion'])){ echo $_SESSION['FechaLiquidacion'];}?>"></td>
								</tr>
								<tr>
									<td class="tdNombre">NOMBRE Y APELLIDO </td>
									<td><input type="text" style="text-align: center;" class="txtHeader nombre upper" disabled="true" name="inpNombre" id="inpNombre" disabled="true" value="<?php if(isset($_SESSION['NombreCliente'])){ echo $_SESSION['NombreCliente'];}?>">
								</tr>
							</tbody>
						</table>	
					</div>
					<div class="table-responsive">
						<table border="2" class="tabla2 text-center">
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
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpCirc" id="inpCirc" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][1];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpSeccion" id="inpSeccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][3];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpFraccion" id="inpFraccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][4];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpChacra" id="inpChacra" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][5];}?>">
									</td>
									<td rowspan="3">
										<input type="text" class="txtHeader upper form-control" disabled="true" id="inpPartida" name="inpPartida" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][8];}?>" style="height: 100px;width: 100%">
									</td>
								</tr>
								<tr>
									<td><strong>QUINTA</strong></td>
									<td><strong>MANZANA</strong></td>
									<td><strong>Parcela</strong></td>
									<td><strong>UF</strong></td>
								</tr>
								<tr>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpQuinta" id="inpQuinta" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][2];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpManzana" id="inpManzana" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][0];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpParcela" id="inpParcela" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][6];}?>">
									</td>
									<td>
										<input type="text" class="txtHeader upper" disabled="true" name="inpUF" id="inpUF" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][7];}?>">
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table border="2" class="tabla2 text-center" >
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
									<td>
										<input type="text" onchange="setearZona('rural')" class="txtHeader" disabled="true" name="inpRuralComp" id="inpRuralComp" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'rural'){ echo 'X';}}?>">
									</td>
									<td>
										<input type="text" onchange="setearZona('urbana')" class="txtHeader" disabled="true" name="inpUrbana" id="inpUrbana" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'urbana'){ echo 'X';}}?>">
									</td>
									<td>
										<input type="text" onchange="setearZona('extraUrb')" class="txtHeader" disabled="true" name="inpResExtraUrb" id="inpResExtraUrb"  value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'residencial'){ echo 'X';}}?>">
									</td>
									<td>
										<input type="text" onchange="setearZona('club')" class="txtHeader" disabled="true" name="inpClubCampo" id="inpClubCampo" value="<?php if(isset($_SESSION['ZONIFICACION'])){ if($_SESSION['ZONIFICACION'] == 'club'){ echo 'X';}}?>">
									</td>
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