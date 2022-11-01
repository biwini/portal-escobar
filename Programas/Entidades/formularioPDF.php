<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
?>
				<!DOCTYPE html>
				<html>
				<head>
					<title></title>
					<link href="css/bootstrap.min.css" rel="stylesheet">
					<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
					<link href="css/bootstrap-theme.min.css" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
				</head>
				<body>
					<div class=" colorContainer">
						<div>
							<div class="nav">
								<div class="pull-left">
									<img class="img-fluid imgLogo" src="imagenes/logo-Escobar.png" alt="logo">
								</div>
								<div class="pull-left" >
									<h2 class="">MUNICIPALIDAD DE ESCOBAR</h2>
									<h2 class="">Provincia de Buenos Aires</h2>
								</div>
								<div class="pull-right colorContainer">
									<label id="fecha"></label>
								</div>
							</div>
							<div>
								<h4>Secretaria de Entidad - Escobar</h4>
								Consulta de Expedientes
								<form class="formTablas">
									<div>
										<table class="table tab-content ">
											<tr>
												<td>
													<p class="pull-left form-control-static" >Fecha Desde : </p>
													<label style="margin-left: 10px;" class="lblFiltro" id="lblFechaDesde">
														<?php 
															if(isset($_SESSION['FILTRO_FECHA_DESDE'])){ 
																echo $_SESSION['FILTRO_FECHA_DESDE']; 
															}
															else{ echo "-";
															} 
														?>
													</label>
												</td>
												<td>
													<p class="pull-left form-control-static" for="lblFechaHasta">Fecha Hasta : </p>
													<label class="lblFiltro" id="lblFechaHasta">
														<?php 
															if(isset($_SESSION['FILTRO_FECHA_HASTA'])){ 
																echo $_SESSION['FILTRO_FECHA_HASTA']; 
															}
															else{ echo "-";
															}  
														?>
													</label>
												</td>
												<td >
													<p class="pull-left form-control-static" for="lblExpediente">Expediente : </p>
													<label class="lblFiltro" id="lblExpediente">
														<?php 
															if(isset($_SESSION['FILTRO_EXPEDIENTE'])){ 
																echo $_SESSION['FILTRO_EXPEDIENTE']; 
															}
															else{ echo "-";
															}  
														?>
													</label>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<p class="pull-left form-control-static" for="lblEntidad">Entidad : </p>
													<label class="lblFiltro" id="lblEntidad">
														<?php 
															if(isset($_SESSION['FILTRO_ENTIDAD'])){ 
																echo $_SESSION['FILTRO_ENTIDAD']; 
															}
															else{ echo "-";
															}  
														?>
													</label>
												</td>
												<td >
													<p class="pull-left form-control-static"  for="lblResponsable">Responsable : </p>
													<label class="lblFiltro" id="lblResponsable">
														<?php 
															if(isset($_SESSION['FILTRO_RESPONSABLE'])){ 
																echo $_SESSION['FILTRO_RESPONSABLE']; 
															}
															else{ echo "-";
															}  
														?>
													</label>
												</td>
											</tr>
											<tr>
												<td colspan="2">
													<p class="pull-left form-control-static" for="lblAgente">Agente : </p>
													<label class="lblFiltro" id="lblAgente">
														<?php 
															if(isset($_SESSION['FILTRO_AGENTE'])){ 
																echo $_SESSION['FILTRO_AGENTE']; 
															}
															else{ echo "-";
															} 	 
														?>
													</label>
												</td>
												<td >
													<p class="pull-left form-control-static"  for="lblNovedad">Novedad : </p>
													<label class="lblFiltro" id="lblNovedad">
														<?php 
															if(isset($_SESSION['FILTRO_NOVEDAD'])){ 
																echo $_SESSION['FILTRO_NOVEDAD']; 
															}
															else{ echo "-";
															} 	 
														?>
													</label>
												</td>
											</tr>
										</table>
									</div>
								</form>
							</div>
							<div>
								<?php

								?>
							</div>
							<form method="post">
								<div class="">
									<table id="consultas" class="table table2">
										<thead>
											<tr>
												<th>Expediente</th>
												<th>Entidad</th>
												<th>Referente</th>
												<th>Responsable</th>
												<th>Agente</th>
												<th>Telefono</th>
												<th >E-Mail</th>
												<th>Novedad</th>
												<th>Observaciones</th>
												<th style='min-width:100px;'>Fecha</th>
											</tr>
										</thead>
										<tbody></tbody>
									</table>
								</div>
							</form>
						</div>
					</div>
				</body>
				</html>
				<script type="text/javascript">
					$("#fecha").queue(function(n) {
						$("#fecha").html();
						$.ajax({
							type: "POST",
							url: "php/hora.php",
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#fecha").html(data);
								n();
							}
						});
					});
					var url="php/generarRegistrosJSON.php";
						$("#consultas tbody").html("");
						$.getJSON(url,function(registros){
							$.each(registros, function(i,registro){
							var newRow =
							"<tr>"
							+"<td>"+registro.cExpediente+"</td>"
							+"<td>"+registro.cEntidad+"</td>"
							+"<td>"+registro.cReferente+"</td>"
							+"<td>"+registro.cResponsable+"</td>"
							+"<td>"+registro.cAgente+"</td>"
							+"<td>"+registro.nTelefono+"</td>"
							+"<td>"+registro.cEmail+"</td>"
							+"<td>"+registro.cNovedad+"</td>"
							+"<td>"+registro.cObservaciones+"</td>"
							+"<td>"+registro.dFecha+"</td>"
							+"</tr>";
							$(newRow).appendTo("#consultas tbody");
							});
							window.print();
							window.close();
						})
						.done(function(data){
							console.log(data)
						})
						.fail(function(data){
							console.log(data)
						});
				</script>
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