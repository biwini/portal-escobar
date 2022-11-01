<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				require_once('controller/liquidacionController.php');
				$Liquidacion = new liquidacion();
?>
				<!DOCTYPE html>
				<html>
				<head>
					<title>Consultas</title>
					<link href="css/bootstrap.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link href="css/jquery-confirm.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link href="css/bootstrap-theme.min.css" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
					<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
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
							<div class="table-responsive">
								<div class="page-header text-center" style="width: 100%;display: table;">
									<div class="pull-left">
										<img class="img-fluid imgLogo pull-left" style="height: 175px;" src="imagenes/logo-escobar2.jpg" alt="logo">
									</div>
									<h1 class="text-center">MUNICIPALIDAD DE ESCOBAR</h1>
									<h5 class="text-center">SECRETARIO DE PLANIFICACION E INFRAESTRUCTURA<br>
									DIRECCION DE OBRAS PARTICULARES</h5>
								</div>
								<div class="table-responsive">
									<table class="table tablaFiltros">
										<tr>
											<td>Desde<input class="" type="date" name="inpFechaDesde" id="inpFechaDesde"></td>
											<td>Hasta<input type="date" name="inpFechaHasta" id="inpFechaHasta"></td>
											<td><input type="button" name="consultar" id="inpConsultar" value="Consultar" class="btn btn-success" onclick="filtrarConsulta()"></td>
											<td><input type="button" style="display: none;" name="consultar" id="inpConsultarAuditoria" value="Consultar" class="btn btn-success" onclick="ConsultasAuditorias('filtrarFecha')"></td>
										</tr>
									</table>
								</div>
								<div id="hola">
									<?php
										//require('php/conexion.php');
									
									?>
								</div>
								<div class="table-responsive divConsultas">
										<table name="tblConsulta" id="tblConsulta" class="table table-striped text-center" cellspacing="0" width="100%">
											<thead>
												<tr id="thLiquidaciones" >
													<th>Seleccionar</th>
													<th>Fecha</th>
													<th>Cliente</th>
													<th>Zonificacion</th>
													<th>Tipo Liquidacion</th>
													<th>Descuento</th>
													<th>Total</th>
												</tr>
	<!-- 											<tr id="thAuditorias" hidden="true">
													<th>Usuario</th>
													<th>Nro Liquidacion</th>
													<th>Accion realizada</th>
													<th>Informacion</th>
													<th>Fecha</th>
												</tr> -->
										   	</thead>
										   	<tbody id="consultas" ></tbody>
										</table>
									</div>
								</div>
								<div id="tbModificacion"></div>
								<table class="table tablaBotones">
									<tr class="">
										<td class="text-center"><input type="button" name="bttnEliminar" id="bttnEliminar" value="Eliminar" class="btn btn-danger pull-left" onclick="confirmarEliminacion()"></td>
										<td><input type="button" name="bttnToExcel" class="btn btn-info center-block" value="Imprimir en Excel" onclick="tableToExcel('consultas', 'Consulta')"></td>
										<td><input type="button" name="bttnToExcel" class="btn btn-warning center-block" value="Descargar PDF" onclick="descargarPDF()"></td>
										<td><input type="button" name="bttnVerConsulta" id="bttnVerConsulta" class="btn btn-success center-block" value="Ver Liquidacion" onclick="modificarRegistro('Ver')"></td>
										<td class="text-center"><input type="button" name="bttnModificar" id="bttnModificar" class="btn btn-primary pull-right" value="Modificar" onclick="modificarRegistro('Modificar')"></td>
									</tr>
								</table>
								<div id="resultado"></div>
							</div>
						</div>
					</div>
					<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
					<script language="javascript" src="js/jquery-confirm.min.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/funciones.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/funcionesPHP.js"></script>
					<script language="javascript" src="js/funcionesConsultas.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/tableToExcel.js?v=<?php echo time(); ?>"></script>
					<script language="javascript" src="js/jquery.dataTables.min.js"></script>
    				<script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    				<script type="text/javascript">
						$(document).ready(function(){
							displayTable();
						});
						ListLiquidaciones = <?php echo json_encode($Liquidacion->getLiquidacion()); ?>;
						function displayTable(){
							DataTable = $('#tblConsulta').DataTable({
			                    "data": ListLiquidaciones,
			                    "rowId": 'Id',
			                    "deferRender":true,
			                    "scrollX":true,
			                    "scrollCollapse":true,
			                    "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
			                    "iDisplayLength":10,
			                    "columns":[
			                        { "data": "Seleccionar",
			                        "render":function(data, type, full, meta){
			                               var controls = "<input type='radio' value=\""+full.Id+"\" name='seleccion' id=\"chk"+full.Id+"\">";
			                                return controls;
			                            }
			                        },
			                        { "data": "FechaLiquidacion"},
			                        { "data": "RazonSocial"},
			                        { "data": "Zonificacion"},
			                        { "data": "Tipo"},
			                        { "data": "Descuento"},
			                        { "data": "Total"}
			                    ],
			                });
						}
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