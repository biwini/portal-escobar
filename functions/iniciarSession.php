<?php
    require_once('../controller/sessionController.php');
    if(!$_SESSION['LOGUEADO']){
		require('conexion.php');
		require('filtrarCaracteres.php');
		//solicita los datos ingresados.
		$Dni_Usuario = filtrar_caracteres($_POST["usuario"]);
		$Contraseña = $_POST["contraseña"];
		//busca si el usuario existe en la base de datos.
		$FoundRows = $conn->prepare('SELECT COUNT(nDni) FROM Usuario WHERE nDni = :dni');
		$FoundRows->execute([':dni' => $Dni_Usuario]);

		if($FoundRows->fetchColumn() != 0){
			$SearchUser = $conn->prepare("SELECT idUsuario,cNombre,cApellido,nDni,cNLegajo,cContrasenia FROM Usuario WHERE nDni = :dni ");
			$SearchUser->execute([':dni' => $Dni_Usuario]);
			if($SearchUser){	//si el usuario existe selecciona el 'ID' del usuario ingresado. sino muestra un Mensaje.
				while ($usuario = $SearchUser->fetch()) {
					$id_Usuario = $usuario['idUsuario'];
					$hashContraseña = $usuario['cContrasenia'];
					$nombre_Usuario = $usuario['cNombre'];
					$apellido = $usuario['cApellido'];
					$dni = $usuario['nDni'];
					$legajo = $usuario['cNLegajo'];
				}
				if($Contraseña == password_verify($Contraseña, $hashContraseña)){
					//busca los permisos a los que tiene Acceso el Usuario, si no encuentra nada muestra un mensaje.
					$FoundAccess = $conn->prepare('SELECT COUNT(idAcceso) FROM Acceso WHERE idUsuario = :id');
					$FoundAccess->execute([':id' => $id_Usuario]);
					//si el usuario tiene Acceso a algunos de los Programas.
					if ($FoundAccess->fetchColumn() != 0){
						$searchAccess = $conn->prepare('SELECT idPrograma FROM acceso WHERE idUsuario = :id');
						$searchAccess->execute([':id' => $id_Usuario]);
						//Si '$_SESSION' esta definido.
						if (isset($_SESSION)) {
							//Defino una variable para contar la cantidad de permisos que tiene el usuario.
							$cantPermisos = array();
							$url = "";
							//Selecciona los 'ID' de los Programas y los asigna a una variable de $_SESSION dependiendo de los Programas a los que tenga Acceso.
							//Esto podria funcionar de la misma manera con un Switch.
							while ($programa = $searchAccess->fetch()) {
								$id_Programa = $programa['idPrograma'];
								switch ($id_Programa) {
									case 1:
										$_SESSION["OBRAS"] = $id_Programa;
										$cantPermisos[] = "Obras Particulares";
										$url = "../Programas/Obras Particulares/index.php";
									break;
									case 2:
										$_SESSION["JUZGADO"] = $id_Programa;
										$cantPermisos[] = "Juzgado";
									break;
									case 3:
										$_SESSION["CAMARAS"] = $id_Programa;
										$cantPermisos[] = "Camaras";
									break;
									case 4:
										$_SESSION["SEMAFOROS"] = $id_Programa;
										$cantPermisos[] = "Semaforos";
									break;
									case 5:
										$_SESSION["SECRETARIA"] = $id_Programa;
										$cantPermisos[] = "Secretaria";
									break;
									case 6:
										$_SESSION["CALL_OF_DUTY"] = $id_Programa;
										$cantPermisos[] = "Call_Of_Duty";
									break;
									case 7:
										$_SESSION["ENTIDADES"] = $id_Programa;
										$cantPermisos[] = "Entidades";
										$url = "../Programas/Entidades/index.php";
									break;
									case 8:
										$_SESSION["ADMINISTRADOR"] = $id_Programa;
										$cantPermisos[] = "Administrador";
										$url = "../Programas/administrator/";
									break;	
								}
							}
							$_SESSION['CANT_PERMISOS'] = $cantPermisos;
							$_SESSION['ID_USER'] = $id_Usuario;
							$_SESSION['NOMBRE_USER'] = $nombre_Usuario;
							$_SESSION['APELLIDO_USER'] = $apellido;
							$_SESSION['DNI_USER'] = $dni;
							$_SESSION['LEGAJO'] = $legajo;
							$_SESSION['LOGUEADO'] = true;
						}
						//Obtengo la ip de la computadora.
						function getLocalIp(){ 
							return gethostbyname(trim(`hostname`)); 
						}

						$exec = exec("hostname"); //El "hostname" es un comando valido para windows como para linux.
						$hostname = trim($exec); //Remueve los espacios de antes y despues.
						$ip = gethostbyname($hostname);

						$Accesos = "";
						//Recorro el los permisos a los que tiene el usuario y los agrego a un string para cargarlos a la auditoria.
						foreach ($_SESSION["CANT_PERMISOS"] as $Programas ){
							$Accesos = $Programas.",".$Accesos;
						}

						date_default_timezone_set('America/Argentina/Buenos_Aires'); //Configuro un nuevo timezone.
						$fecha =date('Y-m-d H:i:s', time()); //Establesco la fecha.
						//Inserto en la tabla auditorias el id del usuario, los accesos a los que tiene, su ip y la fecha en que ingreso.
						$conn->query("INSERT INTO auditoria(idUsuario,cAcceso,cIp,dFecha) VALUES('".$id_Usuario."','".$Accesos."','".$ip."','".$fecha."')");
						//Si tengo mas de un permiso los recorro y los agrego a un array.
						if (count($cantPermisos) != 1) {
							$paginas = array();
							foreach ($_SESSION["CANT_PERMISOS"] as $Programas )
							{
								switch ($Programas) {
									case "Obras Particulares":$paginas[] = "Programas/Obras Particulares/index.php"; break;
									case "Entidades":$paginas[] = "Programas/Entidades/index.php";break;
									case "Administrador":$paginas[] = "Programas/administrator/"; break;
									default : $paginas[] = "";break;
								}
							}
						}
						//Si solo tengo un permiso lo agrego a una variable de session y lo redirecciono a la pagina correspondiente.
						else{
							$_SESSION["PAGINA_PERMITIDA"] = $url;
							header("location:".$url."");
						}
						$_SESSION["PAGINA_PERMITIDA"] = $url;
						//Verifico que la variable "$paginas" sea mayor de 1, en caso de ser asi agrega "$paginas" a una variable de session y lo redirecciona a la pagina donde tendra todos los enlases de sus programas.
						if (count($paginas) > 1) {
							$_SESSION['PAGINAS_PERMITIDAS'] = $paginas;
							header("location:../index.php");
						}
					}
					else{
						echo "El usuario : ".$nombre_Usuario." no tiene Permisos a ningun Programa";
					}
				}
				else{
					echo "Contraseña incorrecta";
				}
			}
		}
		else{
			echo "El usuario no existe en la base de datos";
		}
	}
?> 