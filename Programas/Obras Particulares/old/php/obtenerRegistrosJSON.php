<?php
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				//Solicito la conexion.
				require("conexion.php");
				//Defino una variable para almacenar los filtros.
				$filtros = "";
				//Obtengo los filtros almacenados en session.
				if(isset($_SESSION['FILTROS'])){
					$filtros = "AND ".$_SESSION['FILTROS'];
				}
				
				//Obtengo los registros de las liquidaciones.
				$obtenerRegistros = $conn->query("SELECT cliente.razon_social,tipo_liquidacion.tipo,liquidacion.*
						FROM liquidacion
						INNER JOIN cliente ON liquidacion.id_cliente = cliente.id_cliente
						INNER JOIN tipo_liquidacion ON liquidacion.id_tipo_liquidacion = tipo_liquidacion.id_tipo_liquidacion
						WHERE liquidacion.eliminado IS NULL ".$filtros."") or die("Problemas al obtener los registros");
				//Verifico que la consulta haya sido exitosa.
				if($obtenerRegistros){
					$registros = array();
					//Recorro el registro y los agrego a un array.
					while($row = $obtenerRegistros->fetch()){	
						$fecha = $row['fecha_liquidacion'];
						$nombre = $row['razon_social'];
						$zona = $row['zonificacion'];
						$tipo = $row['tipo'];
						$descuento = $row['descuento'];
						$total = $row['total'];

						$registros[] = array('fecha' => $fecha,'nombre' => $nombre,'zona' => $zona,'tipo' => $tipo,'descuento' => $descuento,'total' => $total);
					}
					//Creamos el JSON.
					$json_string = json_encode($registros);
					echo $json_string;
				}
				//Cierro la conexion a la base de datos.
				$conn = NULL;
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