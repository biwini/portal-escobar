<?php 
	//Creo la coneccion a la base de datos.
	// $conn = mysqli_connect("localhost","root","","obrasparticulares") or die ("Problemas en la coneccion");

	$serverName = "192.168.122.17"; //serverName\instanceName
	$database = "ObraParticular";
	$UID = 'SA';
	$PWD = 'Fu11@c3$*9739';
	//Inicio la conexion
	$conn = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,
        array(
            //PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        )
    );
	header('Content-Type: text/html; charset=UTF-8');
	
	// $connectionInfo = array( "Database"=>"ObraParticular", "UID"=>"SA", "PWD"=>"Fu11@c3$*9739", "ReturnDatesAsStrings" => true);
	// $conn = sqlsrv_connect( $serverName, $connectionInfo);

	// if( !$conn ) {
	//      echo "Conexión no se pudo establecer.<br />";
	//      die( print_r( sqlsrv_errors(), true));
	// }
 ?>