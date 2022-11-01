<?php
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				require("conexion.php");

				$string = "";

				if($_POST['fechaDesde'] != ""){
					$fechaDesde = $_POST['fechaDesde'];
					$string = $string."fecha_liquidacion >= '".$fechaDesde."' AND";
				}
				if($_POST['fechaHasta'] != ""){
					$fechaHasta = $_POST['fechaHasta'];
					$string = $string." fecha_liquidacion <= '".$fechaHasta."' AND";
				}

				$consulta = substr($string,0,-3)."";

				$obtenerLiquidacionesFiltradas = $conn->query("SELECT cliente.nombre,tipo_liquidacion.tipo,liquidacion.*
					FROM liquidacion
					INNER JOIN cliente ON liquidacion.id_cliente = cliente.id_cliente
					INNER JOIN tipo_liquidacion ON liquidacion.id_tipo_liquidacion = tipo_liquidacion.id_tipo_liquidacion 
					WHERE ".$consulta."") or die ("ASIDOAFANSDK");

				while($row = $obtenerLiquidacionesFiltradas->fetch())){
					echo "<tr id='tr' onclick='seleccionar(".$row['id_liquidacion'].")'>
							<td><input type='radio' value=".$row['id_liquidacion']." name='seleccion' id='chk".$row['id_liquidacion']."''></td>
							<td><label id='lblFecha".$row['id_liquidacion']."'>".$row['fecha_liquidacion']."</label></td>
							<td><label id='lblNombre".$row['id_liquidacion']."'>".$row['nombre']."</label></td>
							<td><label id='lblZona".$row['id_liquidacion']."'>".$row['zonificacion']."</label></td>
							<td><label id='lblTipo".$row['id_liquidacion']."'>".$row['tipo']."</label></td>
							<td><label id='lblDescuento".$row['id_liquidacion']."'>".$row['descuento']."</label></td>
							<td><label id='lblTotal".$row['id_liquidacion']."'>".$row['total']."</label></td>
						</tr>";
				}
			}
			else{
				header("location: ../index.php");
			}
		}
		else{
			header("location: ../index.php");
		}
	}
?>