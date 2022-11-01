<?php

	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){

				require("conexion.php");

				$_SESSION['FILTRO_FECHA_DESDE'] = "-";
				$_SESSION['FILTRO_FECHA_HASTA'] = "-";
				unset($_SESSION['FILTROS']);

				$obtenerLiquidaciones = $conn->query("SELECT c.razon_social,tp.tipo,l.* FROM liquidacion AS l
					INNER JOIN cliente AS c ON l.id_cliente = c.id_cliente
					INNER JOIN tipo_liquidacion AS tp ON l.id_tipo_liquidacion = tp.id_tipo_liquidacion
					WHERE l.eliminado IS NULL ORDER BY l.id_liquidacion DESC");

				$registros = $conn->query("SELECT COUNT(id_auditoria) AS ID FROM auditoria ORDER BY ID");
				//Convierto el resultado a un valor numerico.
				// $cantidadRegistros = $registros -> num_rows;
				// $cantidadLiquidaciones = $obtenerLiquidaciones -> num_rows;

				$_SESSION['CANT_LIQUIDACIONES'] = 1;
				$_SESSION['REGISTROS_AUDITORIAS'] = 1;
				echo "<input type='text' name='inpCantAudit' id='inpCantAudit' value='".'1'."' style='display:none'>";

				while($row = $obtenerLiquidaciones->fetch()){
					echo "
						<tr id='tr' onclick='seleccionar(".$row['id_liquidacion'].")'>
							<td><input type='radio' value=".$row['id_liquidacion']." name='seleccion' id='chk".$row['id_liquidacion']."''></td>
							<td><label id='lblFecha".$row['id_liquidacion']."'>".$row['fecha_liquidacion']."</label></td>
							<td><label id='lblNombre".$row['id_liquidacion']."'>".$row['razon_social']."</label></td>
							<td><label id='lblZona".$row['id_liquidacion']."'>".$row['zonificacion']."</label></td>
							<td><label id='lblTipo".$row['id_liquidacion']."'>".$row['tipo']."</label></td>
							<td><label id='lblDescuento".$row['id_liquidacion']."'>".$row['descuento']."</label></td>
							<td><label id='lblTotal".$row['id_liquidacion']."'>".$row['total']."</label></td>
						</tr>";
				}
				$conn = NULL;
			}
			else{
				header("location: ../../../index.php");
			}
		}
		else{
			header("location: ../../../index.php");
		}
	}

?>