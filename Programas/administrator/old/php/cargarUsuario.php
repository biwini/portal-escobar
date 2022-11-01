<?php
header('Content-Type: text/html; charset=UTF-8');
	require('conexion.php');

	$nombre_Usuario = $_POST['nombreUsuario'];
	$apellido_Usuario = $_POST['apellidoUsuario'];
	$dni_Usuario = $_POST['dniUsuario'];
	$contraseña = $_POST['inpContrasenia'];

	$hashContraseña = password_hash($contraseña, PASSWORD_DEFAULT);

	$sql = "SELECT idUsuario FROM usuario WHERE nDni ='".$dni_Usuario."'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$CargarUsuario = sqlsrv_query( $conn, $sql , $params, $options );

	if(sqlsrv_num_rows($CargarUsuario) == 0){
		sqlsrv_query($conn,"INSERT INTO usuario (cNombre,cApellido,nDni,cContrasenia) VALUES ('".$nombre_Usuario."','".$apellido_Usuario."','$dni_Usuario', '".$hashContraseña."')");
		echo "Usuario Cargado";
	}
	else{
		echo "El Usuario ya esta en la base de datos";
	}
	sqlsrv_close($conn);

?>