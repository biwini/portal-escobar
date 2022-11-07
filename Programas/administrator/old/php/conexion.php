<?php 
	//Creo la coneccion a la base de datos.
	$serverName = "127.0.0.1"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"PortalEscobar", "UID"=>"SA", "PWD"=>"Fu11@c3$*9739");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( !$conn ) {
	     echo "Conexión no se pudo establecer.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}
 ?>