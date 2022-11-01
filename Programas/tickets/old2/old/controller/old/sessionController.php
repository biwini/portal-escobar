<?php
	//Compruebo si la session ya esta activa.
	if(!isset($_SESSION)){
		//Si no esta activa inicio una nueva session.
		session_start();
		//Verifico si estan definidas las variables de session requeridas.
		//Con tan solo verificar una es suficiente para saber que las demas no estan definidas.
		if(!isset($_SESSION['LOGUEADO'])){
			//Destruyo la sesion actual.
			session_destroy();
			session_unset();
			unset($_SESSION);
			//Funcion que inica una nueva sesion segura.
			SetSecureSession();
			//Funcion que establece las variables de sesion.
			SetSessionsVars();
		}
	}

	function SetSecureSession(){
		ini_set('session.cookie_httponly', 1);
		// **PREVENTING SESSION FIXATION**
		// Session ID cannot be passed through URLs
		ini_set('session.use_only_cookies', 1);

		// Uses a secure connection (HTTPS) if possible
		ini_set('session.cookie_secure', 1);

		session_start();
		$currentCookieParams = session_get_cookie_params(); 
		$sidvalue = session_id(); 
		setcookie( 'PHPSESSID',$sidvalue,0,$currentCookieParams['path'],$currentCookieParams['domain'],true);
	}

	function SetSessionsVars(){
		//Variable para verificar si el usuario esta logueado;
		$_SESSION['LOGUEADO'] = false;
		$_SESSION['CANT_PERMISOS'] = array();
		$_SESSION['ID_USER'] = null;
		$_SESSION['NOMBRE_USER'] = null;
		$_SESSION['APELLIDO_USER'] = null;
		$_SESSION['DNI_USER'] = null;
		$_SESSION['LEGAJO'] = null;
		$_SESSION['CART_CONTENT'] = array();
		$_SESSION['SEXO'] = null;
		$_SESSION['TELEFONO'] = null;
		$_SESSION['EMAIL'] = null;
		$_SESSION['DEPENDENCIA'] = null;
		$_SESSION['IP'] = null;
	}

?>