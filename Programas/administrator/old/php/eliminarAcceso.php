<?php
	//solicito los datos de la coneccion.
	require('conexion.php');
	//Obtengo los valores enviados por Ajax.
	$id_Usuario = $_POST["usuario"];
	$id_Programa = $_POST["programa"];
	//Borro de la base de datos, la tabla Accesos los campos donde el '$id_Usuario' y el '$id_Programa' sean iguales a los datos recibidos por Ajax.
	sqlsrv_query($conn,"DELETE FROM acceso WHERE idUsuario='".$id_Usuario."' AND idPrograma='".$id_Programa."'");

	echo "Acceso Eliminado";

	sqlsrv_close($conn);

?>