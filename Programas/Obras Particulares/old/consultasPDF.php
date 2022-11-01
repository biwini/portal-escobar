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
					<link href="css/bootstrap-theme.min.css?v=<?php echo time(); ?>" rel="stylesheet">
					<link rel="stylesheet" href="css/planilla.css?v=<?php echo time(); ?>">
				</head>
				<body>
					<div class=" ContainerPDF">
						<div>
							<div class="nav">
								<div class="pull-left">
									<img class="img-fluid imgLogo" src="imagenes/logo-Escobar.png" alt="logo">
								</div>
								<div class="pull-left" >
									<h2 class="">MUNICIPALIDAD DE ESCOBAR</h2>
									<h2 class="">Provincia de Buenos Aires</h2>
								</div>
								<div class="pull-right ContainerPDF">
									<label id="fecha"></label>
								</div>
							</div>
							<div>
								<h4>Secretaria de Obras Particulares - Escobar</h4>
								Consulta de Expedientes
								<form class="formTablas">
									<div>
										<table class="table tab-content text-center">
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
								<div class="table-responsive">
									<table id="consultas" class="table text-center">
										<thead>
											<tr>
												<th>Cliente</th>
												<th>Zonificacion</th>
												<th>Tipo Liquidacion</th>
												<th>Descuento</th>
												<th>Total</th>
												<th>Fecha</th>
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
					var url="controller/";
					$.ajax({
						type: "POST",
						url: "controller/",
						data: "pag=Consultas"+"&tipo=g",
						dataType: "html",
					})
					.fail(function(data){
						alert("error petición ajax");
					})
					.done(function(data){
						registros = JSON.parse(data);
						$("#consultas tbody").html("");
						$.each(registros, function(i,registro){
							var newRow =
							"<tr>"
							+"<td>"+registro.RazonSocial+"</td>"
							+"<td>"+registro.Zonificacion+"</td>"
							+"<td>"+registro.Tipo+"</td>"
							+"<td>"+registro.Descuento+"</td>"
							+"<td>"+registro.Total+"</td>"
							+"<td>"+registro.FechaLiquidacion+"</td>"
							+"</tr>";
							$(newRow).appendTo("#consultas tbody");
						});
						window.print();
						window.close();
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