<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion a la base.
				require("includes/conexion.php");
				//Obtengo el id del registro a modificar.
				$id_registro = $_POST['id_registro'];
				//Busco el registro.
				$obtenerRegistros = sqlsrv_query($conn,"SELECT idEntidad,dFecha,cExpediente,cReferente,cResponsable,cAgente,nTelefono,cEmail,cNovedad,cObservacion FROM entidad WHERE idEntidad = '".$id_registro."'");
				//Verifico que la consulta sea correcta.
				if($obtenerRegistros){
					//Recorro el registro y los muestro por pantalla.
					while($row = sqlsrv_fetch_array($obtenerRegistros)){
						echo "<tr>
								<td style='display: none'><input type='text' id='modRegistro' value='".$row['idEntidad']."' style='display: none'></td>
							  </tr>
							  <tr>
							  	<td><label>Fecha</label></td>
								<td><input type='text' id='modfecha' value='".$row['dFecha']."'</td>
							  </tr>
							  <tr>
							  	<td><label>Expediente</label></td>
								<td><input type='text' id='modexpediente' value='".$row['cExpediente']."'</td>
							  </tr>
							  <tr>
								<td><label>Entidad</label></td>
								<td><input type='text' id='modentidad' value='".$row['cEntidad']."'</td>
							  </tr>
							  <tr>
								<td><label>Referente</label></td>
								<td><input type='text' id='modreferente' value='".$row['cReferente']."'</td>
							  </tr>
							  <tr>
								<td><label>Responsable</label></td>
								<td><input type='text' id='modresponsable' value='".$row['cResponsable']."'</td>
							  </tr>
							  <tr>
								<td><label>Agente</label></td>
								<td><input type='text' id='modagente' value='".$row['cAgente']."'</td>
							  </tr>
							  <tr>
								<td><label>Telefono</label></td>
								<td><input type='text' id='modtelefono' value='".$row['nTelefono']."'</td>
							  </tr>
							  <tr>
								<td><label>E-Mail</label></td>
								<td><input type='text' id='modemail' value='".$row['cEmail']."'</td>
							  </tr>
							  <tr>
								<td><label>Novedad</label></td>
								<td><input type='text' id='modnovedad' value='".$row['cNovedad']."'</td>
							  </tr>
							  <tr>
								<td><label>Observaciones</label></td>
								<td><textarea id='modobservaciones' rows='6' cols='60' value='".$row['cObservaciones']."'>".$row['cObservaciones']."</textarea></td>
							  </tr>";
					}
				}
				else{
					//En caso de error obteniendo el registro muestro un mensaje. 
					echo "<span class='label label-danger center-block' style='font-size:15px;>Error al obtener el registro para su modificacion.</span>";
				}
				//Cierro la conexion.
				sqlsrv_close($conn);
			}
			else{
				//Lo redirijo a la pagina principal.
				header("location: ../index.php");
			}
		}
		else{
			header("location: ../index.php");
		}
	}

?>