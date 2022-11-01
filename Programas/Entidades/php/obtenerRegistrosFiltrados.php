<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Solicito la conexion a la base de datos.
				require('Includes/conexion.php');
				//Declaro una variable para almacenar la consulta.
				$string = "";
				//Verifico que los campos recibidos no esten indefindos.

				if($_POST['expediente'] != "undefined"){
					//Lo agrego la variable el valor recibido.
					$expediente= $_POST['expediente']);
					//Lo agrego a una variable de session.
					$_SESSION['FILTRO_EXPEDIENTE'] = $expediente;
					//Añado al string la consulta a realizar.
					$string = $string." expediente LIKE '".$expediente."%'AND";
				}
				else{
					$_SESSION['FILTRO_EXPEDIENTE'] = "-";
				}

				if ($_POST['entidad'] != "undefined") {
					$entidad= $_POST['entidad']);

					$_SESSION['FILTRO_ENTIDAD'] = $entidad;

					$string = $string." entidad LIKE '".$entidad."%' AND";
				}

				else{
					$_SESSION['FILTRO_ENTIDAD'] = "-";
				}
				if ($_POST['responsable'] != "undefined") {
					$responsable= $_POST['responsable'];

					$_SESSION['FILTRO_RESPONSABLE'] = $responsable;

					$string = $string." responsable LIKE '".$responsable."%' AND";
				}
				else{
					$_SESSION['FILTRO_RESPONSABLE'] = "-";
				}

				if ($_POST['agente'] != "undefined") {
					$agente= $_POST['agente'];

					$_SESSION['FILTRO_AGENTE'] = $agente;

					$string = $string." agente LIKE '".$agente."%' AND";
				}
				else{
					$_SESSION['FILTRO_AGENTE'] = "-";
				}

				if ($_POST['novedad'] != "undefined") {
					$novedad= $_POST['novedad'];

					$_SESSION['FILTRO_NOVEDAD'] = $novedad;

					$string = $string." novedad LIKE '".$novedad."%' AND";
				}
				else{
					$_SESSION['FILTRO_NOVEDAD'] = "-";
				}

				if ($_POST['fechaDesde'] != "undefined") {
					$fechaDesde= $_POST['fechaDesde'];

					$_SESSION['FILTRO_FECHA_DESDE'] = $fechaDesde;

					$string = $string." fecha >= '".$fechaDesde."' AND";
				}
				else{
					$_SESSION['FILTRO_FECHA_DESDE'] = "-";
				}

				if ($_POST['fechaHasta'] != "undefined") {
					$fechaHasta= $_POST['fechaHasta'];

					$_SESSION['FILTRO_FECHA_HASTA'] = $fechaHasta;

					$string = $string." fecha <='".$fechaHasta."%' AND";
				}
				else{
					$_SESSION['FILTRO_FECHA_HASTA'] = "-";
				}
				//Le corto las 3 ultimas letras del string la cual seria el "AND".
				$consulta = $string;
				$_SESSION['FILTROS'] = $consulta;
				//Hago la consulta a la base de datos.
				$obtenerRegistrosFiltrados = sqlsrv_query($conn,"SELECT auditorias.*,entidades.*
				FROM entidades
				INNER JOIN auditorias ON entidades.id_entidad = auditorias.id_entidad
				WHERE ".$consulta." auditorias.eliminado = 0") or die("Error al obtener los registros");

				//Verifico que la consulta sea correcta.
				if($obtenerRegistrosFiltrados){
					//Recorro el registro obtenido y los muestro por pantalla.
					while($row = sqlsrv_fetch_array($obtenerRegistrosFiltrados)){
						echo "<tr onclick='seleccionar(".$row['id_entidad'].")'>
								<td class='seleccionar'><input type='radio' value=".$row['id_entidad']." name='seleccion' id='chk".$row['id_entidad']."''></td>
								<td>".$row['fecha']."</td>
								<td>".$row['expediente']."</td>
								<td>".$row['entidad']."</td>
								<td>".$row['referente']."</td>
								<td>".$row['responsable']."</td>
								<td>".$row['agente']."</td>
								<td>".$row['telefono']."</td>
								<td>".$row['e_mail']."</td>
								<td>".$row['novedad']."</td>
								<td>".$row['observaciones']."</td>
							  </tr>";
					}
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