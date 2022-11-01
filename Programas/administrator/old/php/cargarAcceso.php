<?php
	require('conexion.php');

	$id_Usuario = $_POST["usuario"];
	$id_Programa = $_POST["programa"];
	//verifico si el Usuario ya tiene Acceso a ese Programa.
	$sql = sqlsrv_query($conn,"SELECT * FROM acceso WHERE idUsuario ='".$id_Usuario."' AND idPrograma ='".$id_Programa."'");
	// Si el Usuario no tiene Acceso al programa seleccionado se los otorga.
	if(sqlsrv_num_rows($sql) == 0){
		sqlsrv_query($conn,"INSERT INTO acceso(idUsuario,idPrograma) VALUES ('$id_Usuario','$id_Programa')");

		echo "Acceso Cargado";
	}
	else{
		echo "El Usuario ya tiene Acceso al Programa";
	}
	sqlsrv_close($conn);

?>