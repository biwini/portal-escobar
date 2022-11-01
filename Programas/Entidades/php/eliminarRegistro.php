<?php
	//Reanudo la Session ya existente.
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion a la base de datos.
				require("Includes/conexion.php");
				//Obtengo el id del registro para su eleminacion.
				$id_registro = $_POST['id_registro'];

				date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
				$fecha =date('Y-m-d h:i:s', time());
				//Query para eliminar el registro.
				$EliminarRegistro = sqlsrv_query($conn,"UPDATE auditoria SET nEliminado=1,dFecha_eliminado='".$fecha."' WHERE idEntidad = '".$id_registro."'") or die("Error al eliminar el registro.");
				//Verifico que la eliminacion haya sido un exito.
				if($EliminarRegistro){
					//Muestro un mensaje.
					echo "<span class='label label-danger center-block' style='font-size:15px;>Registro Eliminado.</span>";
				}
				//Cierro la conexion a la base.
				sqlsrv_close($conn);
			}
			else{
			header("location: ../login.php");
			}
		}
		else{
			header("location: ../login.php");
		}
	}
?>