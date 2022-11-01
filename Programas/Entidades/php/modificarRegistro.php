<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion a la base de datos.
				require('Includes/conexion.php');
				//Obtengo los valores del formulario de modificacion.
				$id_entidad=$_POST['id_entidad'];
				$expediente = $_POST['expediente'];
				$entidad = $_POST['entidad'];
				$referente = $_POST['referente'];
				$responsable = $_POST['responsable'];
				$agente = $_POST['agente'];
				$telefono = $_POST['telefono'];
				$email = $_POST['email'];
				$novedad = $_POST['novedad'];
				$observaciones = $_POST['observaciones'];

				date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
				$fecha =date('Y-m-d H:i:s', time());
				//Query para Modificar el registro.
				$modificarRegistro = sqlsrv_query($conn,"UPDATE entidad SET cExpediente='".$expediente."',cEntidad='".$entidad."',cReferente='".$referente."',cResponsable='".$responsable."',cAgente='".$agente."',nTelefono='".$telefono."',cEmail='".$email."', cNovedad='".$novedad."',cObservaciones='".$observaciones."' WHERE idEntidad=".$id_entidad."") or die("Error al modificar el registro");

				sqlsrv_query($conn,"UPDATE auditoria SET nModificado=1,dFecha_modificado='".$fecha."'");
				//Verifico que la Modificacion haya sido un exito.
				if($modificarRegistro){
					//Mensaje de que el registro fue Modificado con exito.
					echo "<span class='label label-success center-block' style='font-size:15px;'>Registro Modificado.</span>";
				}
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
?>