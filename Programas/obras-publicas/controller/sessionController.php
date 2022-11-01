<?php
	class session{
		protected $Logued;
		protected $Unregistred;
		private $ActualDate;
		private $StartTime;
		private $TimeBetween;
	
		function __construct(){
			$this->StartSession();
	
			$this->Logued = $_SESSION['LOGUEADO'];
			$this->Unregistred = $_SESSION['UNREGISTRED'];
	
			// if($_SESSION['UNREGISTRED'] && isset($_SESSION['LIMIT_SESSION'])) {
			// 	$this->LimitTime();
			// }
		}
	
		private function StartSession(){
			if(!isset($_SESSION)){//Si no esta activa inicio una nueva session.
				session_start();
				//Verifico si estan definidas las variables de session requeridas.
				if(!$this->validateSessionVars()){
					//Destruyo la sesion actual, de 3 formas distintas porque soy re pro.
					$this->DestroySession();
					//Funcion que inica una nueva sesion segura.
					$this->SetSecureSession();
					//Funcion que establece las variables globales de sesion.
					$this->SetSessionsVars();
				}
			}
		}
		private function DestroySession(){
			//Destruyo la sesion actual, de 3 formas distintas porque soy re pro.
			session_destroy();
			session_unset();
			unset($_SESSION);
		}
		public function LimitTime(){
			$this->ActualTime = new DateTime(date('Y-m-d H:i:s', time()));
			$this->StartTime = new DateTime($_SESSION['LIMIT_SESSION']);
	
			$this->TimeBetween = $this->StartTime->diff($this->ActualTime);
	 
			//comparamos el tiempo transcurrido en minutos "i"
			if($this->TimeBetween->i > 5) { //Si pasan 5 minutos de inactividad se destruye la session
				$this->DestroySession();
				$this->SetSecureSession();
				$this->SetSessionsVars();
	
				return false;
			   }
			   return true;
			   // else{ //sino, actualizo la fecha de la sesión 
			// 	$_SESSION['LIMIT_SESSION'] = date('Y-m-d H:i:s', time());
			   // }
		}
	
		private function SetSecureSession(){
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
		private function SetSessionsVars(){
			//Variable para verificar si el usuario esta logueado;
			$_SESSION['LOGUEADO'] = false;
			$_SESSION['CANT_PERMISOS'] = array();
			$_SESSION['ID_USER'] = null;
			$_SESSION['NOMBRE_USER'] = null;
			$_SESSION['APELLIDO_USER'] = null;
			$_SESSION['DNI_USER'] = null;
			$_SESSION['LEGAJO'] = null;
			// $_SESSION['CART_CONTENT'] = array();
			$_SESSION['SEXO'] = null;
			$_SESSION['TELEFONO'] = null;
			$_SESSION['EMAIL'] = '';
			$_SESSION['DEPENDENCIA'] = null;
			$_SESSION['IP'] = null;
	
			//Variables para la autenticacion LDAP
			$_SESSION['UNREGISTRED'] = FALSE;
			$_SESSION['TEMPORAL_PASSWORD'] = NULL;
		}
	
		public function isLogued(){
			if(!isset($_SESSION['LOGUEADO'])){
				return false;
			}

			return $this->Logued;
		}
	
		public function getUserId(){
			if(!$this->isLogued()){
				return false;
			}
			return $_SESSION['ID_USER'];
		}
		
	
		private function ValidateSessionVars(){
			// return (!isset($_SESSION['LOGUEADO']) || !isset($_SESSION['CANT_PERMISOS']) || !isset($_SESSION['ID_USER']) || !isset($_SESSION['LEGAJO']) || !isset($_SESSION['DNI_USER']) || !isset($_SESSION['EMAIL']) || !isset($_SESSION['DEPENDENCIA'])) ? false : true;
			return (isset($_SESSION['LOGUEADO'])) ? true : false;
		}
	}
?>