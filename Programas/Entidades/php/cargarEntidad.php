<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion a la base de datos.
				require('Includes/conexion.php');
				//Obtengo los valores del formulario de Carga.
				$expediente = $_POST['expediente'];
				$entidad = $_POST['entidad'];
				$referente = $_POST['referente'];
				$responsable = $_POST['responsable'];
				$agente = $_POST['atendido_por'];
				$telefono = $_POST['telefono'];
				$email = $_POST['email'];
				$novedad = $_POST['novedad'];
				$observaciones = $_POST['observaciones'];
				//Obtengo la hora.
				date_default_timezone_get();
				$hora = date("Y/n/j");

				//Query para verificar si el expediente ya existe en la base de datos.
				$sql = "SELECT cExpediente FROM Entidad WHERE cExpediente = '".$expediente."'";
				$params = array();
				$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
				$verificarExpediente = sqlsrv_query( $conn, $sql , $params, $options );
				//si el expediente no existe en la base de datos.
				if(sqlsrv_num_rows($verificarExpediente) == 0){
					//Query para cargar el registro.
					$cargarEntidad = sqlsrv_query($conn,"INSERT INTO entidad(cExpediente,cEntidad,cReferente,cResponsable,cAgente,nTelefono,cEmail,cNovedad,cObservaciones,dFecha) VALUES ('$expediente', '$entidad', '$referente', '$responsable', '$agente', '$telefono', '$email', '$novedad', '$observaciones', '$hora') ") or die("Error al cargar el registro.");
					//Mensaje de confirmacion
					echo "<span class='label label-success center-block' style='font-size:15px;'>Expediente cargado con exito.</span>";
					$id_entidad = 0;
					$obtenerIdEntidad = sqlsrv_query($conn,"SELECT TOP 1 * FROM entidad ORDER BY idEntidad DESC");
					var_dump(sqlsrv_fetch_array($obtenerIdEntidad));
			        if($obtenerIdEntidad){

						while($id = sqlsrv_fetch_array($obtenerIdEntidad)){
				        	$id_entidad = $id['idEntidad'];
				        	die("holaaaa");
				        }
				        echo $id_entidad;
						sqlsrv_query($conn,"INSERT INTO auditoria(idEntidad) VALUES ('".$id_entidad."')") or die ("holaaaa");
					}
				}
				else{
					echo "<span class='label label-danger center-block' style='font-size:15px;'>El expediente ya existe.</span>";
				}
				sqlsrv_close($conn);
			}
			else{
				header("location: ../../login.php");
			}
		}
		else{
			header("location: ../../login.php");
		}
	}
?>