<?php
	require('conexion.php');

	$nombre_Programa = $_POST["nombrePrograma"];
	//verifico si el programa ya esta cargado en la Base de Datos.
	$sql = "SELECT * FROM programa WHERE cNomPrograma ='".$nombre_Programa."'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$programas = sqlsrv_query( $conn, $sql , $params, $options );
	// Si el programa no esta, carga el programa ingresado.
	if(sqlsrv_num_rows($programas) == 0){
		sqlsrv_query($conn,"INSERT INTO programa(cNomPrograma) VALUES ('$nombre_Programa')");

		echo "Programa Cargado";
	}
	else{
		echo "El Programa ya esta en la base de datos";
	}
	sqlsrv_close($conn);
?>