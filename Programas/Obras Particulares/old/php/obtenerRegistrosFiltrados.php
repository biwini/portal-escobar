<?php
	//Reanudo la Session ya existente.
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
					require('conexion.php');

					//Obtengo la cantidad de filas de la tabla de auditorias.
					$registros = $conn->query("SELECT id_auditoria FROM auditoria");
					//Convierto el resultado a un valor numerico.
					$cantidadRegistros = 1;

					if($_POST['tipoConsulta'] == "liquidaciones"){
						$string = "";
						if ($fechaDesde=$_POST['fechaDesde'] != "") {
							$fechaDesde=$_POST['fechaDesde'];
							$_SESSION['FILTRO_FECHA_DESDE'] = $fechaDesde;
							$string = $string." liquidacion.fecha_liquidacion >= '".$fechaDesde."' AND";
						}
						if ($fechaHasta=$_POST['fechaHasta'] != "") {
							$fechaHasta=$_POST['fechaHasta'];
							$_SESSION['FILTRO_FECHA_HASTA'] = $fechaHasta;
							$string = $string." liquidacion.fecha_liquidacion <= '".$fechaHasta."' AND";
						}
						$consulta = substr($string,0,-3)."";

						$_SESSION['FILTROS'] = $consulta;

						$obtenerRegistrosFiltrados = $conn->query("SELECT cliente.razon_social,tipo_liquidacion.tipo,liquidacion.*
						FROM liquidacion
						INNER JOIN cliente ON liquidacion.id_cliente = cliente.id_cliente
						INNER JOIN tipo_liquidacion ON liquidacion.id_tipo_liquidacion = tipo_liquidacion.id_tipo_liquidacion
						WHERE liquidacion.eliminado IS NULL AND ".$consulta."  ORDER BY liquidacion.id_liquidacion DESC") or die("Problemas en Realizar los filtros");

						$cantidadLiquidaciones = 1;

						$_SESSION['CANT_LIQUIDACIONES'] = $cantidadLiquidaciones;
						echo "<input type='text' name='inpCantAudit' id='inpCantAudit' value='".$cantidadRegistros."' style='display:none'>";

						while($row = $obtenerRegistrosFiltrados->fetch()){
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
					}
					else{
						//Obtengo la pagina actual en la que estoy.
						$pagina = $_POST['pagina'];
						if($pagina != 10 ){
							if($pagina > $cantidadRegistros){
								$antiguaPagina = $pagina - 10;
								$pagina = $cantidadRegistros;
							}
							else{
								$antiguaPagina = $pagina - 10;
							}
						}
						else{
							$antiguaPagina = 0;
							$pagina = 10;
						}

						$string = "";

						if ($fechaDesde=$_POST['fechaDesde'] != "") {
							$fechaDesde=$_POST['fechaDesde'];
							$string = $string." fecha_liquidacion >= '".$fechaDesde."' AND";
						}
						if ($fechaHasta=$_POST['fechaHasta'] != "") {
							$fechaHasta=$_POST['fechaHasta'];
							$string = $string." fecha_liquidacion <= '".$fechaHasta."' AND";
						}
						$filtroFecha = "";
						$QueryWHERE = "";
						if($string != ""){
							$filtroFecha = substr($string,0,-3);
							$QueryWHERE = "WHERE ".$filtroFecha."";
						}
						else{
							$filtroFecha = $string;
							$QueryWHERE = "WHERE ".$filtroFecha." id_auditoria <= ".$pagina." AND id_auditoria >= ".$antiguaPagina." ";
						}
						
						echo "<input type='text' name='inpCantAudit' id='inpCantAudit' value='".$cantidadRegistros."' style='display:none'>";

							$serverName = "127.0.0.1"; //serverName\instanceName
							$database = "PortalEscobar";
							$UID = 'SA';
							$PWD = 'Fu11@c3$*9739';
							//Inicio la conexion
							$conexion = new PDO("sqlsrv:server=$serverName;Database=$database",$UID,$PWD,
						        array(
						            //PDO::ATTR_PERSISTENT => true,
						            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
						        )
						    );

						$obtenerAuditorias = $conn->query("SELECT * FROM auditoria ".$QueryWHERE."") or die("Error al obtner las auditorias");

						while($row = $obtenerAuditorias->fetch()){

							$datosUsuario = $conexion->query("SELECT * FROM usuario WHERE idUsuario = ".$row['id_usuario']."")or die("Error");

							$nombreUsuario = "";

							while($dato = $datosUsuario->fetch()){
								$nombreUsuario = $dato['cNombre']." ".$dato['cApellido'];

							}
							echo "
								<tr id='tr' onclick='seleccionar(".$row['id_auditoria'].")'>			
									<td><label id='lblUsuario".$row['id_auditoria']."'>".$nombreUsuario."</label></td>
									<td><label id='lblLiquidacion".$row['id_auditoria']."'>".$row['id_liquidacion']."</label></td>
									<td><label id='lblAccion".$row['id_auditoria']."'>".$row['tipo_auditoria']."</label></td>
									<td><label id='lblInformacion".$row['id_auditoria']."'>".$row['detalle_auditoria']."</label></td>
									<td style='min-width: 100px;'><label id='lblFecha".$row['id_auditoria']."'>".$row['fecha_auditoria']."</label></td>
								</tr>";
						}
						$conexion = NULL;
					}
					$conn = NULL;
			}
			else{
				header("location: ../login.php");
			}
		}
		else{
			header("location: ../login.php");
		}
	}
?>