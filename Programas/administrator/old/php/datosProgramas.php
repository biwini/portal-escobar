<?php

	require('conexion.php');

	$sql = sqlsrv_query($conn,"SELECT * FROM programa");

	echo "<option value='0'>Elija un Programa</option>";

	while ($row=sqlsrv_fetch_array($sql)) {
		echo "<option value=".$row['idPrograma'].">".$row['cNomPrograma']."</option>";
	}

	sqlsrv_close($conn);
?>