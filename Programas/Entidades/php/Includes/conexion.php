<?php 
	//Creo la coneccion a la base de datos.
	// $conn = mysqli_connect("localhost","root","","dbpruebaportal") or die ("Problemas en la coneccion");

	$serverName = "127.0.0.1"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"Entidad", "UID"=>"SA", "PWD"=>"Fu11@c3$*9739", "ReturnDatesAsStrings" => true);
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( !$conn ) {
	     echo "Conexión no se pudo establecer.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}
	header('Content-Type: text/html; charset=UTF-8');
 ?>