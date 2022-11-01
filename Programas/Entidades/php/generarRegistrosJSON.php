<?php
	//Reanudo la Session ya existente.
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion.
				require("includes/conexion.php");
				//Obtengo los registros de las entidades.
				$filtros = "";
				if(isset($_SESSION['FILTROS'])){
					$filtros = $_SESSION['FILTROS'];
				}

				$obtenerRegistros = sqlsrv_query($conn,"SELECT auditoria.*,entidad.*
				FROM entidad
				INNER JOIN auditoria ON entidad.idEntidad = auditoria.idEntidad
				WHERE ".$filtros." auditoria.nEliminado IS NULL ") or die("Error al obtener los registros");
				//Verifico que la consulta sea correcta.
				if($obtenerRegistros){
					$registros = array();
					//Recorro el registro y los muestro por pantalla.
					while($row = sqlsrv_fetch_array($obtenerRegistros)){	
						$fecha = $row['dFecha'];
						$expediente = $row['cExpediente'];
						$entidad = $row['cEntidad'];
						$referente = $row['cReferente'];
						$responsable = $row['cResponsable'];
						$agente = $row['cAgente'];
						$telefono = $row['nTelefono'];
						$e_mail = $row['cEmail'];
						$novedad = $row['cNovedad'];
						$observaciones = $row['cObservaciones'];

						$registros[] = array('dFecha' => $fecha,'cExpediente' => $expediente,'cEntidad' => $entidad,'cReferente' => $referente,'cResponsable' => $responsable,'cAgente' => $agente,'nTelefono' => $telefono,'cEmail' => $e_mail,'cNovedad' => $novedad,'cObservaciones' => $observaciones,);
					}
					//Creamos el JSON.
					$json_string = json_encode($registros);
					echo $json_string;
				}
				//Cierro la conexion a la base de datos.
				sqlsrv_close($conn);
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