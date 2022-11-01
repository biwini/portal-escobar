<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion.
				require("includes/conexion.php");

				$_SESSION['FILTROS'] = "";
				$_SESSION['FILTRO_EXPEDIENTE'] = "-";
				$_SESSION['FILTRO_ENTIDAD'] = "-";
				$_SESSION['FILTRO_RESPONSABLE'] = "-";
				$_SESSION['FILTRO_AGENTE'] = "-";
				$_SESSION['FILTRO_NOVEDAD'] = "-";
				$_SESSION['FILTRO_FECHA_DESDE'] = "-";
				$_SESSION['FILTRO_FECHA_HASTA'] = "-";
				//Obtengo los registros de las entidades.
				$obtenerRegistros = sqlsrv_query($conn,"SELECT auditoria.*,entidad.*
				FROM entidad
				INNER JOIN auditoria ON entidad.idEntidad = auditoria.idEntidad
				WHERE auditoria.nEliminado IS NULL") or die("Error al obtener los registros");

				//Verifico que la consulta sea correcta.
				if($obtenerRegistros){
					var_dump(sqlsrv_fetch_array($obtenerRegistros, SQLSRV_FETCH_ASSOC));
					//Recorro el registro y los muestro por pantalla.
					while($row = sqlsrv_fetch_array($obtenerRegistros, SQLSRV_FETCH_ASSOC)){
						echo "<tr id='tr".$row['idEntidad']."' onclick='seleccionar(".$row['idEntidad'].")'>
								<td><input type='radio' value=".$row['idEntidad']." name='seleccion' id='chk".$row['idEntidad']."''></td>
								<td>".$row['dFecha']."</td>
								<td>".$row['cExpediente']."</td>
								<td>".$row['cEntidad']."</td>
								<td>".$row['cReferente']."</td>
								<td>".$row['cResponsable']."</td>
								<td>".$row['cAgente']."</td>
								<td>".$row['nTelefono']."</td>
								<td>".$row['cEmail']."</td>
								<td>".$row['cNovedad']."</td>
								<td>".$row['cObservaciones']."</td>
							 </tr>";
					}
				}
				else{
					die( print_r( sqlsrv_errors(), true));
				}
				//Cierro la conexion a la base de datos.
				sqlsrv_close($conn);
			}
			else{
				header("location: ../index.php");
			}
		}
		else{
			header("location: ../index.php");
		}
	}

?>