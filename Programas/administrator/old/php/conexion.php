<?php 
	//Creo la coneccion a la base de datos.
	$serverName = "192.168.122.17"; //serverName\instanceName
	$connectionInfo = array( "Database"=>"PortalEscobar", "UID"=>"SA", "PWD"=>"Fu11@c3$*9739");
	$conn = sqlsrv_connect( $serverName, $connectionInfo);

	if( !$conn ) {
	     echo "Conexión no se pudo establecer.<br />";
	     die( print_r( sqlsrv_errors(), true));
	}
 ?>