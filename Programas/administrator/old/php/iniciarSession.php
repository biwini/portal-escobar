<?php

	require('conexion.php');
	//solicita los datos ingresados.
	$nombre_Usuario = $_REQUEST["txtUsuario"];
	$Dni = $_REQUEST["txtDni"];
	//busca si el usuario existe en la base de datos.
	$sql = "SELECT id_usuario FROM usuarios WHERE dni='".$Dni."'";
	$params = array();
	$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
	$buscar_Usuario = sqlsrv_query( $conn, $sql , $params, $options );
	//si el usuario existe selecciona el 'ID' del usuario ingresado. sino muestra un Mensaje.
	if(mysqli_num_rows($buscar_Usuario) != 0){
		while ($usuario=mysqli_fetch_array($buscar_Usuario)) {
			$id_Usuario = $usuario['id_usuario'];
		}
		//busca los permisos a los que tiene Acceso el Usuario, si no encuentra nada muestra un mensaje.
		$sql = "SELECT id_programa FROM accesos WHERE id_usuario = '".$id_Usuario."'";
		$params = array();
		$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
		$buscar_Permiso = sqlsrv_query( $conn, $sql , $params, $options );
		//si el usuario tiene Acceso a algunos de los Programas.
		if (mysqli_num_rows($buscar_Permiso) != 0){
			//Si '$_SESSION' no esta definido.
			if (!isset($_SESSION)) {
				// Inicia una Session.
				session_start();
				//Selecciona los 'ID' de los Programas y los asigna a una variable de $_SESSION dependiendo de los Programas a los que tenga Acceso.
				//Esto podria funcionar de la misma manera con un Switch.
				while ($programa=mysqli_fetch_array($buscar_Permiso)) {
					$id_Programa = $programa['id_programa'];
					if ($id_Programa == 1){
						$_SESSION["Metegol"] = $id_Programa;
					}
					else{
						if ($id_Programa == 2){
							$_SESSION["Juzgado"] = $id_Programa;
							header("location: ../pagina2.php");
						}
						else{
							if ($id_Programa == 3){
								$_SESSION["Camaras"] = $id_Programa;
							}
							else{
								if ($id_Programa == 4){
									$_SESSION["Semaforos"] = $id_Programa;
								}
								else{
									if ($id_Programa == 5){
										$_SESSION["Secretaria"] = $id_Programa;
									}
									else{
										if ($id_Programa == 6){
											$_SESSION["Call_Of_Duty"] = $id_Programa;
										}
										else{
											if ($id_Programa == 7){
												$_SESSION["GtaV"] = $id_Programa;
											}
											else{
												if ($id_Programa == 8){
													$_SESSION["Administrador"] = $id_Programa;
													header("location: ../pagina1.php");
												}
											}
										}
									}
								}
							}
						}
					}
				}
				$_SESSION['id_usuario'] = $id_Usuario;
				$_SESSION['nombre_Usuario'] = $nombre_Usuario;
				$_SESSION['estado'] = "Logueado";
			}
		}
		else{
			echo "El Usuario : ".$nombre_Usuario." no tiene Permisos a ningun Programa";
			echo "<a href='../index.php'>Volver</a>";
		}
	}
	else{
		echo "El Usuario : ".$nombre_Usuario." no se encuentra en la Base de Datos";
		echo "<a href='../index.php'>Volver</a>";
	}
?> 