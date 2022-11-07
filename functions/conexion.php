<?php 
	//Creo la coneccion a la base de datos.
	// $conn = mysqli_connect("localhost","root","","dbpruebaportal") or die ("Problemas en la coneccion");
	$serverName = "127.0.0.1"; //serverName\instanceName
	$database = "PortalEscobar";
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
 ?>