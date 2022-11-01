<?php
	require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si esta definida la variable de '$_SESSION['Obras']', la cual se define si el Usuario tiene Acceso al Programa, esto se define en 'iniciarSession.php'. Si esta definida ejecuta el codigo php, sino vuelve a la pagina principal.
			if(isset($_SESSION["OBRAS_PARTICULARES"])){
				//Datos fehca y cliente
				$fecha=$_POST['fecha'];
				$nombre=$_POST['nombre'];
				//Nomenclatura
				$circ=$_POST['circ'];
				$seccion=$_POST['seccion'];
				$fraccion=$_POST['fraccion'];
				$chacra=$_POST['chacra'];
				$partida=$_POST['partida'];
				$quinta=$_POST['quinta'];
				$manzana=$_POST['manzana'];
				$parcela=$_POST['parcela'];
				$uf=$_POST['uf'];

				$array_nomenclatura = [$manzana,$circ,$quinta,$seccion,$fraccion,$chacra,$parcela,$uf,$partida];
				//Zonificacion.
				$rural=$_POST['rural'];
				$urbana=$_POST['urbana'];
				$residencial=$_POST['residencial'];
				$club=$_POST['club'];

				if ($rural == "X"){
					$zona = "RURAL";
				}
				if ($urbana == "X"){
					$zona = "URBANA";
				}
				if ($residencial == "X"){
					$zona = "RESIDENCIAL";
				}
				if ($club == "X"){
					$zona = "CLUB";
				}


				$_SESSION['FechaLiquidacion'] = $fecha;
				$_SESSION['NombreCliente'] = $nombre;

				$_SESSION['ARRAY_NOMENCLATURA'] = $array_nomenclatura;

				$_SESSION['ZONIFICACION'] = $zona;


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