<?php
	require('conexion.php');

	$sql = sqlsrv_query($conn,"SELECT * FROM programa");

	while ($row=sqlsrv_fetch_array($sql)) {
		echo "<input type='checkbox' value=".$row['idPrograma'].">".$row['cNombre']."";
	}
	sqlsrv_close($conn);
?>