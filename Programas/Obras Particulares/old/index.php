<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){

				require("php/conexion.php");
				$valor_sm_municipal = 0;
				//Busco el valor del S.M.Municipal.
				$sql = "SELECT valor_sm_municipal FROM sm_municipal";
				$stmt = $conn->query($sql);
				if($stmt) {
				    while( $row = $stmt->fetch()) {
					    $valor_sm_municipal = $row['valor_sm_municipal'];
					}
				}
				$conn = NULL;
				// $buscar_sm_municipal = mysqli_query($conn,"SELECT valor_sm_municipal FROM sm_municipal");
				// //Obtengo el valor del S.M.Municipal.
				// while($obtener_sm_municipal = mysqli_fetch_array($buscar_sm_municipal)){
				// 	$valor_sm_municipal = $obtener_sm_municipal['valor_sm_municipal'];
				// }
				//Lo agrego a una variable de session.
				$_SESSION['VALOR_SM_MUNICIPAL'] = $valor_sm_municipal;
				//Cierro la conexion a la base de datos.
				// mysqli_close($conn);

				//Verifico si el boton "cargar" fue oprimido.
				if(isset($_REQUEST['inpCargar'])){
					$_SESSION["FechaLiquidacion"] = $_REQUEST['inpFecha'];
					$_SESSION["NombreCliente"] = $_REQUEST['inpNombre'];
					
					$circ = $_REQUEST['inpCirc'];
					$seccion = $_REQUEST['inpSeccion'];
					$fraccion = $_REQUEST['inpFraccion'];
					$chacra = $_REQUEST['inpChacra'];
					$partida = $_REQUEST['inpPartida'];
					$quinta = $_REQUEST['inpQuinta'];
					$manzana = $_REQUEST['inpManzana'];
					$parcela = $_REQUEST['inpParcela'];
					$uf = $_REQUEST['inpUF'];

					$sm_municipal = $_REQUEST['inpValor_SMMunicipal'];

					if ($sm_municipal != $_SESSION['VALOR_SM_MUNICIPAL']) {

						require("php/conexion.php");

						$conn->query("UPDATE sm_municipal SET valor_sm_municipal = '".$sm_municipal."'");

						$_SESSION['VALOR_SM_MUNICIPAL'] = $sm_municipal;

						$conn = NULL;

					}

					$_SESSION["ZONIFICACION"] = $_REQUEST['radioZona'];

					$array_nomenclatura = [$manzana,$circ,$quinta,$seccion,$fraccion,$chacra,$parcela,$uf,$partida];

					$_SESSION['ARRAY_NOMENCLATURA'] = $array_nomenclatura;

					header("location: liquidacion.php");
					
				}
				else{

				}
?>			
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<meta charset="utf-8">
	<link href="css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
	<link rel="icon" href="https://www.escobar.gov.ar/wp-content/uploads/2018/04/cropped-Logo-1-32x32.png" sizes="32x32">
	<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script language="javascript" src="js/funciones.js?v=<?php echo time(); ?>"></script>
	<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
</head>
<body>
	<div class="container colorContainer">
		<div class="col-lg-12">
			<header id="header">
		  		<div class="menu">
			  		<ul class="">
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
			<div class="page-header text-center" style="width: 100%;display: table;">
				<div class="pull-left">
					<img class="img-fluid imgLogo pull-left" style="height: 175px;" src="imagenes/logo-escobar2.jpg" alt="logo">
				</div>
				<h1 class="text-center">MUNICIPALIDAD DE ESCOBAR</h1>
				<h5 class="text-center">SECRETARIO DE PLANIFICACION E INFRAESTRUCTURA<br>
				DIRECCION DE OBRAS PARTICULARES</h5>
			</div>
				<form method="post" id="form1">
					<div class="table-responsive">
						<table class="tabla table text-center" border="1">
							<tbody>
								<tr>
									<td>FECHA</td>
									<td><input class="input" type="date" name="inpFecha" id="inpFecha" value="<?php if(isset($_SESSION['FechaLiquidacion'])){ echo $_SESSION['FechaLiquidacion'];}?>" required="true"></td>
								</tr>
								<tr>
									<td>NOMBRE Y APELLIDO </td>
									<td><input type="text" class="upper" name="inpNombre" id="inpNombre" value="<?php if(isset($_SESSION['NombreCliente'])){ echo $_SESSION['NombreCliente'];}?>" required="true">
										<span></span>
								</tr>
							</tbody>
						</table>	
					</div>
					<div class="table-responsive">
						<table border="1" class="tabla table text-center">
							<thead>
								<tr>
									<th colspan="5">NOMENCLATURA CATASTRAL</th>
								</tr>
								<tr>
									<th>CIRC.</th>
									<th>SECCIÓN</th>
									<th>FRACCIÓN</th>
									<th>CHACRA</th>
									<th>PARTIDA</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input type="text" class="upper" name="inpCirc" id="inpCirc" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][1];}?>"></td>
									<td><input type="text" class="upper" name="inpSeccion" id="inpSeccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][3];}?>"></td>
									<td><input type="text" class="upper" name="inpFraccion" id="inpFraccion" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][4];}?>"></td>
									<td><input type="text" class="upper" name="inpChacra" id="inpChacra" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][5];}?>"></td>
									<td rowspan="3"><input type="text" class="upper" id="inpPartida" name="inpPartida" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][8];}?>"></td>
								</tr>
								<tr>
									<td><strong>QUINTA</strong></td>
									<td><strong>MANZANA</strong></td>
									<td><strong>Parcela</strong></td>
									<td><strong>UF</strong></td>
								</tr>
								<tr>
									<td><input type="text" class="upper" name="inpQuinta" id="inpQuinta" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][2];}?>"></td>
									<td><input type="text" class="upper" name="inpManzana" id="inpManzana" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][0];}?>"></td>
									<td><input type="text" class="upper" name="inpParcela" id="inpParcela" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][6];}?>"></td>
									<td><input type="text" class="upper" name="inpUF" id="inpUF" value="<?php if(isset($_SESSION['ARRAY_NOMENCLATURA'])){ echo $_SESSION['ARRAY_NOMENCLATURA'][7];}?>"></td>
								</tr>
							</tbody>
						</table>
					</div>
				<div class="table-responsive">
					<table border="1" class="tabla table text-center" >
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
								<td rowspan="2"><STRONG>DE4</STRONG></td>
							</tr>
							<tr>
								<?php 
									if(isset($_SESSION['ZONIFICACION'])){
										switch ($_SESSION['ZONIFICACION']) {
											case 'rural':
												$rural = "<td><input type='radio' name='radioZona' id='radioZona' value='rural' checked='' required='true'></td>";
												$urbana = "<td><input type='radio' name='radioZona' id='radioZona' value='urbana' required='true'></td>";
												$residencial = "<td><input type='radio' name='radioZona' id='radioZona' value='residencial' required='true'></td>";
												$club = "<td><input type='radio' name='radioZona' id='radioZona' value='club' required='true'></td>";
												echo $rural.$urbana.$residencial.$club;
											break;
											case 'urbana':
												$rural = "<td><input type='radio' name='radioZona' id='radioZona' value='rural' required='true'></td>";
												$urbana = "<td><input type='radio' name='radioZona' id='radioZona' value='urbana' checked='' required='true'></td>";
												$residencial = "<td><input type='radio' name='radioZona' id='radioZona' value='residencial' required='true'></td>";
												$club = "<td><input type='radio' name='radioZona' id='radioZona' value='club' required='true'></td>";
												echo $rural.$urbana.$residencial.$club;
											break;
											case 'residencial':
												$rural = "<td><input type='radio' name='radioZona' id='radioZona' value='rural' required='true'></td>";
												$urbana = "<td><input type='radio' name='radioZona' id='radioZona' value='urbana' required='true'></td>";
												$residencial = "<td><input type='radio' name='radioZona' id='radioZona' value='residencial' checked='' required='true'></td>";
												$club = "<td><input type='radio' name='radioZona' id='radioZona' value='club' required='true'></td>";
												echo $rural.$urbana.$residencial.$club;
											break;
											case 'club':
												$rural = "<td><input type='radio' name='radioZona' id='radioZona' value='rural' required='true'></td>";
												$urbana = "<td><input type='radio' name='radioZona' id='radioZona' value='urbana' required='true'></td>";
												$residencial = "<td><input type='radio' name='radioZona' id='radioZona' value='residencial' required='true'></td>";
												$club = "<td><input type='radio' name='radioZona' id='radioZona' value='club' checked='' required='true'></td>";
												echo $rural.$urbana.$residencial.$club;
											break;
										}
									}
									else{
										$rural = "<td><input type='radio' name='radioZona' id='radioZona' value='rural' required='true'></td>";
										$urbana = "<td><input type='radio' name='radioZona' id='radioZona' value='urbana' required='true'></td>";
										$residencial = "<td><input type='radio' name='radioZona' id='radioZona' value='residencial' required='true'></td>";
										$club = "<td><input type='radio' name='radioZona' id='radioZona' value='club' required='true'></td>";
										echo $rural.$urbana.$residencial.$club;
									}
								?>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="table-responsive">
					<table class="tabla table text-center" border="1">
						<tbody>
							<tr>
								<td>S.M.MUNICIPAL</td>
								<td><input class="" type="number" name="inpValor_SMMunicipal" id="inpValor_SMMunicipal" value="<?php if(isset($_SESSION['VALOR_SM_MUNICIPAL'])){ echo $_SESSION['VALOR_SM_MUNICIPAL'];}?>" required="true"></td>
							</tr>
						</tbody>
					</table>	
				</div>
				<input type="submit" class="pull-right btn btn-primary" name="inpCargar" id="inpCargar" value="Cargar">
				<span></span>
			</form>
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