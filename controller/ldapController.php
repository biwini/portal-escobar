<?php
	if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }
    // error_reporting(0);
	class ldap{
		private $ServerLDAP;
		private $PortLDAP;
		private $DomainLDAP;
		private $UserLDAP;
		private $PassLDAP;
		private $ConexionLDAP;

	    function __construct(){
			$this->ServerLDAP = 'escobar-dc01.escobar.gov.ar'; //IP DEL SERVIDOR
		    $this->PortLDAP = '389'; //PUERTO DE CONEXIÓN
		    // $this->DomainLDAP = 'DC=escobar,DC=gov,DC=ar';//NOMBRE DEL DOMINIO
		    $this->DomainLDAP = '@escobar.gov.ar';//NOMBRE DEL DOMINIO
		    $this->UserLDAP = '';        //NOMBRE DEL USUARIO LOGADO
		    $this->PassLDAP = '';  //CONTRASEÑA DEL USUARIO LOGADO 
		}

		private function GetConexionLDAP(){
			return ldap_connect($this->ServerLDAP,$this->PortLDAP); //CONECTAMOS CON SERVIDOR LDAP DESDE PHP
		}
		// "CN=Elian Benitez,CN=Users,DC=escobar,DC=gov,DC=ar"

	    //----------FORMA CORRECTA DE INGRESAR EL NOMBRE DE USUARIO------------
	    // L13848@escobar.gov.ar
	    //----------FINAL CORRECTA DE INGRESAR EL NOMBRE DE USUARIO-----------

		public function ValidateUser($User,$Pass){
			$this->ConexionLDAP = $this->GetConexionLDAP();

			if(!$this->ConexionLDAP){
				return false;
			}

			ldap_set_option($this->ConexionLDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
    		ldap_set_option($this->ConexionLDAP, LDAP_OPT_REFERRALS, 0);

			$this->UserLDAP = $User.$this->DomainLDAP;
			$this->PassLDAP = $Pass;

			return @ldap_bind($this->ConexionLDAP, $this->UserLDAP, $this->PassLDAP); 
		}
	}
// Base DN: DC=escobar,DC=gov,DC=ar
// Usuario: CN=Recibos Digitales,OU=elian benitez,OU=Escobar,DC=escobar,DC=gov,DC=ar
// Contraseña: R3c1b0$*723

// escobar-dc01.escobar.gov.ar
// escobar-dc02.escobar.gov.ar
	// $Ldap = new ldap();
	// $hola = $Ldap->ValidateUser('asdasd','asdasdasd');
	// var_dump($hola);
?>