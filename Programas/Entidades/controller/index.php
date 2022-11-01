<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){

		}else{
			header("location: ../../index.php");
		}
	}else{
		header("location: ../../index.php");
	}
