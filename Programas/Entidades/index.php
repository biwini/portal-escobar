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
	<title>Entidad</title>
	<meta name="theme-color" content="white"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="Entidad">
	<meta name="theme-color" content="#fff"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link href="css/entidades.css?v=<?php echo time(); ?>" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
		
</head>
<body>
	<div id="divConteiner" class="container">
		<a href="../../Includes/cerrarSession.php">Cerrar Session</a>
		<div class="bttnConteiner">
			<input type="button" class="bttnRegistro" name="registro" id="bttnRegistro" value="Registro" onclick="mostrarDivs(1)">
			<input type="button" class="bttnConsultas" name="btnconsultas" id="bttnConsulta" value="Consultas" onclick="mostrarDivs(2)">
		</div>
		<div class="col-2" id="divRegistro" style="display: block">
			<div>
				<label class="lblDerecha" id="hora"></label>
				<label class="lblDerecha">Fecha : </label>
				<form class="form-group table-responsive">
					<table class="table">
						<tr>
							<td><label for="idExpediente"> Expediente</label></td>
							<td><input type="text" name="expediente" id="idExpediente" required="true" required="true"></td>
						</tr>

						<tr>
							<td><label for="idEntidad"> Entidad</label></td>
							<td><input type="text" name="entidad" id="idEntidad" required="true"></td>
						</tr>

						<tr>
							<td><label for="idReferente"> Referente</label></td>
							<td><input type="text" name="referente" id="idReferente" required="true"></td>
						</tr>

						<tr>
							<td><label for="idResponsable"> Responsable</label></td>
							<td><input type="text" name="responsable" id="idResponsable" required="true"></td>
						</tr>

						<tr>
							<td><label for="idAtendidoPor"> Atendido Por</label></td>
							<td><input type="text" name="antendidoPor" id="idAtendidoPor" required="true"></td>
						</tr>

						<tr>
							<td><label for="idTelefono"> Telefono</label></td>
							<td><input type="text" name="Telefono" id="idTelefono" required="true"></td>
						</tr>

						<tr>
							<td><label for="idEmail"> E-MAIL</label></td>
							<td><input type="text" name="Email" id="idEmail" required="true"></td>
						</tr>

						<tr>
							<td><label for="idNovedad"> Novedad</label></td>
							<td><input type="text" name="novedad" id="idNovedad" required="true"></td>
						</tr>

						<tr>
							<td><label for="idObservaciones"> Observaciones</label></td>
							<td><textarea name="observaciones" rows="6" cols="60" id="idObservaciones"></textarea></td>
						</tr>
					</table>
					<input type="button" class="boton btn btn-primary" name="aceptar" id="aceptar" value="Aceptar" onclick="cargarEntidad()">
					<div id="resultado" name="resultado"></div>
				</form>
			</div>
		</div>

		<div id="divConsultas" class="consultas" style="display: none">
			<div class="divTabla">
				<form class="formTablas">
					<table class="tabla tab-content">
						<tr>
							<td><input type="checkbox" id="chboxFechDesde" onclick="activarCampo(1)">
							<label for="idFilFechaDesde">Fecha Desde</label></td>
							<td><input type="date" name="fechaDesde" id="idFilFechaDesde" disabled="true">

							<input type="checkbox" id="chboxFechHasta" onclick="activarCampo(2)">
							<label for="idFilFechaHasta">Fecha Hasta</label>
							<input type="date" name="fechaHasta" id="idFilFechaHasta" disabled="true"></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="chboxEntidad" onclick="activarCampo(3)">
							<label for="idFilEntidad">Entidad</label></td>
							<td><input type="text" name="entidad" id="idFilEntidad" disabled="true"></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="chboxAgente" onclick="activarCampo(4)">
							<label for="idFilAgente">Agente</label></td>
							<td><input type="text" name="agente" id="idFilAgente" disabled="true"></td>
						</tr>
					</table>
					<table class="tabla tab-content">
						<tr>
							<td><input type="checkbox" id="chboxExpediente" onclick="activarCampo(5)">
							<label for="idFilExpediente"> Expediente</label></td>
							<td><input type="text" name="expediente" id="idFilExpediente" disabled="true"></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="chboxResponsable" onclick="activarCampo(6)">
							<label for="idFilResponsable"> Responsable</label></td>
							<td><input type="text" name="responsable" id="idFilResponsable" disabled="true"></td>
						</tr>
						<tr>
							<td><input type="checkbox" id="chboxNovedad" onclick="activarCampo(7)">
							<label for="idFilNovedad"> Novedad</label></td>
							<td><input type="text" name="novedad" id="idFilNovedad" disabled="true"></td>
						</tr>
					</table>
				</form>
			</div>
			<div class="divConsultas">
				<div>
					<input type="button" name="consultar" value="Consultar" class="btnConsulta btn-success" onclick="filtrarConsulta()">
				</div>
				<div class="table-responsive">
					<table border="1" class="table table2">
						<thead>
							<tr>
								<th>Seleccionar</th>
								<th>Fecha</th>
								<th>Expediente</th>
								<th>Entidad</th>
								<th>Referente</th>
								<th>Responsable</th>
								<th>Agente</th>
								<th>Telefono</th>
								<th style='width:68px;'>E-Mail</th>
								<th>Novedad</th>
								<th>Observaciones</th>
							</tr>
					  	</thead>
					  	<tbody id="consultas"></tbody>
					</table>
				</div>
				<div>
					<table class="table">
						<td><input type="button" name="bttnEliminar" value="Eliminar" class="btn btn-danger pull-left" onclick="eliminarRegistro()"></td>
						<td><input type="button" name="bttnToExcel" class="btn btn-info center" value="Imprimir en Excel" onclick="tableToExcel('consultas', 'Consulta')"></td>
						<td><input type="button" name="bttnPDF" class="btn btn-warning center" value="Imprimir en PDF" onclick="imprimirFormularioPDF()"></td>
						<td><input type="button" name="bttnModificar" class="btn btn-primary pull-right" value="Modificar" onclick="modificarRegistro()"></td>
					</table>
				</div>
			</div>
			<div id="mensaje"></div>
		</div>

		<div id="divModificacion" class="modificacion" style="display: none;">
			<table border="1" id="tbModificacion" class="table"></table>
			<input type="button" class="btn btn-primary pull-left" name="bttnCancelarModificacion" value="Cancelar Modificacion" onclick="mostrarDivs(2)">	
			<input type="button" class="btn btn-success pull-right" name="bttnConfirmarModificacion" value="Confirmar Modificacion" onclick="modificacionConfirmada()">
		</div>
	</div>
	<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
	<script language="javascript" src="js/main.js?v=<?php echo time(); ?>"></script>
	<script language="javascript" src="js/tableToExcel.js?v=<?php echo time(); ?>"></script>
	<script type="text/javascript">
		// Obtenemos la Hora local de la computadora
		$(document).ready(function(){
			$("#hora").queue(function(n) {
				$("#hora").html();
				$.ajax({
					type: "POST",
					url: "php/hora.php",
					dataType: "html",
					error: function(){
						alert("error petición ajax");
					},
					success: function(data){
						$("#hora").html(data);
						n();
					}
				});
			});
		});
	</script>
</body>
</html>
<?php 
			}
		}
		else{
			header("location: ../../index.php");
		}
	}
	else{
		header("location: ../../login.php");
	}
?>