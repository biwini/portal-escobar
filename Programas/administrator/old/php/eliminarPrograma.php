<?php
	//solicito los datos de la coneccion.
	require("conexion.php");
	//Obtengo los valores enviados por Ajax.
	$id_Programa = $_POST["programa"];
	// Borro primero el id del programa que esta en la tabla Accesos por motivos de que saldria error ya que estaria haciendo referencia a un dato no existente, por lo tanto no me permite borrarlo. 
	sqlsrv_query($conn,"DELETE FROM acceso WHERE idPrograma='".$id_Programa."'");

	sqlsrv_query($conn,"DELETE FROM programa WHERE idPrograma='".$id_Programa."'");

	echo "programa Eliminado";
	sqlsrv_close($conn);

?>