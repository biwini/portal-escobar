<?php

	require('conexion.php');
	//Selecciona todos los datos que contenga la tabla usuarios.
	$usuarios = sqlsrv_query($conn,"SELECT * FROM usuario");

	echo "<option value='0'>Elija un Usuario</option>";
	
	while ($row=sqlsrv_fetch_array($usuarios)) {
		echo "<option value=".$row['idUsuario'].">".$row['cNombre']."</option>";
	}

	sqlsrv_close($conn);

?>