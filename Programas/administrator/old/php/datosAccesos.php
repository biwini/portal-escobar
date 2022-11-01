
<?php
	require('conexion.php');

	$accesos=sqlsrv_query($conn,"SELECT usuario.*,programa.*
		FROM acceso
		INNER JOIN usuario ON acceso.idUsuario=usuario.idUsuario
		INNER JOIN programa ON acceso.idPrograma=programa.idPrograma");

	echo "<table border=1><tr><td>Usuario</td><td>Programa</td><td>Dar de baja</td></tr>";

	while ($reg = sqlsrv_fetch_array($accesos)){
		echo "<tr><td><input type='hidden' value='".$reg['idUsuario']."' id='usuario'>".$reg['cNombre']."</td><td><input type='hidden' value='".$reg['idPrograma']."' id='programa'>". $reg['cNomPrograma']."</td><td><input type='button' id='eliminarAcceso' value='Eliminar' onclick='eliminarAcceso()'></td></tr>";
	}

	sqlsrv_close($conn);
?>
</body>
</html>
