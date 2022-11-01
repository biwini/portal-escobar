<?php
    require_once('../controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ENTIDADES"])){
				//Array para almacenar los dias de la semana.
				$dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
				//Array para almacenar los meses del año.
				$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
				// el "w" es un numero que representa un dia de la semana. "d" es el dia del mes. "n" mes actual en digitos del 1 al 12. "Y" Año actual con 4 digitos.
				echo $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;			
			}
		}
		else{
			header("location: ../login.php");
		}
	}
	else{
		header("location: ../login.php");
	}
?>