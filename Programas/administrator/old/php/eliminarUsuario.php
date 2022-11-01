<?php
	//solicito los datos de la coneccion.
	require('conexion.php');
	//Obtengo los valores enviados por Ajax.
	$id_Usuario = $_POST["usuario"];
	// Borro primero el id del usuario que esta en la tabla Accesos por motivos de que saldria error ya que estaria haciendo referencia a un dato no existente, por lo tanto no me permite borrarlo.
	sqlsrv_query($conn,"DELETE FROM acceso WHERE idUsuario='".$id_Usuario."'");

	sqlsrv_query($conn,"DELETE FROM usuario WHERE idUsuario='".$id_Usuario."'");
	
	echo "Usuario Eliminado";
	sqlsrv_close($conn);
?>