<?php
	if(!isset($_SESSION)){
		require_once ('sessionController.php');
		$Session = new session();
    }

    error_reporting(0);
	// ini_set('display_errors', 1);
	// ini_set('display_startup_errors', 1);
	// error_reporting(E_ALL);
	
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
		    $this->UserLDAP = 'CN=Recibos Digitales,OU=Usuarios de Servicio,OU=Escobar,DC=escobar,DC=gov,DC=ar';        //NOMBRE DEL USUARIO LOGADO
		    $this->PassLDAP = 'R3c1b0$*723';  //CONTRASEÑA DEL USUARIO LOGADO 
		}

		private function GetConexionLDAP(){
			return ldap_connect($this->ServerLDAP,$this->PortLDAP); //CONECTAMOS CON SERVIDOR LDAP DESDE PHP
		}
		// "CN=Elian Benitez,CN=Users,DC=escobar,DC=gov,DC=ar"

	    //----------FORMA CORRECTA DE INGRESAR EL NOMBRE DE USUARIO------------
	    // L13848@escobar.gov.ar
	    //----------FINAL CORRECTA DE INGRESAR EL NOMBRE DE USUARIO-----------

		public function ValidateUser($User){
			$User = $this->cleanString($User);
			if(!isset($_SESSION['ADMINISTRATOR']) || $_SESSION['ADMINISTRATOR'] != 1){
				return array('Status' => 'Invalid Call');
			}

			$this->ConexionLDAP = $this->GetConexionLDAP();

			if(!$this->ConexionLDAP){
				return false;
			}

			ldap_set_option($this->ConexionLDAP, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_set_option($this->ConexionLDAP, LDAP_OPT_REFERRALS, 0);
			// ldap_set_option($this->ConexionLDAP, LDAP_OPT_SIZELIMIT, 1000); 

    		return $this->getUser($User);
		}

		private function getUser($User){
			ldap_bind($this->ConexionLDAP, $this->UserLDAP, $this->PassLDAP); 

			$sr = ldap_search($this->ConexionLDAP, "DC=escobar,DC=gov,DC=ar", "samaccountname=".$User);    //FILTRAMOS POR ObjectClass=group
            //El resultado de la búsqueda es $sr
            //El número de entradas devueltas es ldap_count_entries($ConexionLDAP, $sr);
            //Obteniendo entradas ...
			$info = ldap_get_entries($this->ConexionLDAP, $sr);

            return ($info['count'] != 0) ? array('Status' => 'Success', 'Name' => $info[0]['givenname'][0], 'Surname' => $info[0]['sn'][0], 'Legajo' => $User, 'Group' => $info[0]['memberof'][0]) : array('Status' => 'Unknown User', $_POST);
		}

		public function getUsers($objectClass = 'User'){
			if(!isset($_SESSION['ADMINISTRATOR']) || $_SESSION['ADMINISTRATOR'] != 1){
				return array('Status' => 'Invalid Call');
			}

			$entries = array('Status' => 'No Results');
			$response = array();

			//------------------------------------------------------------------------------
			// Connect to the LDAP server.
			//------------------------------------------------------------------------------
			$this->ConexionLDAP = $this->GetConexionLDAP();

			if (FALSE === $this->ConexionLDAP){
				die("<p>Failed to connect to the LDAP server: sad</p>");
			}

			ldap_set_option($this->ConexionLDAP, LDAP_OPT_PROTOCOL_VERSION, 3) or die('Unable to set LDAP protocol version');
			ldap_set_option($this->ConexionLDAP, LDAP_OPT_REFERRALS, 0); // We need this for doing an LDAP search.
			

			if (TRUE !== ldap_bind($this->ConexionLDAP, $this->UserLDAP, $this->PassLDAP)){
				return array('Status' => 'Imposible To Bind');
			}
			
			//------------------------------------------------------------------------------
			// Get a list of all Active Directory users. date('D M d, Y @ H:i:s', ($entries[$i][$col_name][0] / 10000000) - 11676009600); // See note below
			//------------------------------------------------------------------------------
			$ldap_base_dn = 'DC=escobar,DC=gov,DC=ar';
			$search_filter = "(&(objectclass=".$objectClass."))";
			$fields = ($objectClass == 'User') ? array('cn','distinguishedname','displayname','name','lastlogon','samaccountname') : array('cn','description', 'distinguishedname', 'whencreated', 'whenchanged','iscriticalsystemobject');
			// Usuarios campos utiles
			$result = ldap_search($this->ConexionLDAP, $ldap_base_dn, $search_filter, $fields, 0, 1000); 
			// Grupos campos utiles
			// $result = ldap_search($this->ConexionLDAP, $ldap_base_dn, $search_filter, array('cn','description', 'distinguishedname', 'whencreated', 'whenchanged','iscriticalsystemobject'), 0, 0);
			
			if (FALSE !== $result){
				$entries = ldap_get_entries($this->ConexionLDAP, $result);
			}

			for ($i = 0; $i < $entries['count']; $i++){
				if($objectClass == 'User'){
					$response[] = array(
						'Cn' => $entries[$i]['cn'][0],
						'DistName' => $entries[$i]['distinguishedname'][0],
						'DisplayName' => $entries[$i]['displayname'][0],
						'Name' => $entries[$i]['name'][0],
						'LastLogon' => date('Y-m-d H:i:s', ($entries[$i]['lastlogon'][0] / 10000000) - 11676009600),
						'Accountname' => $entries[$i]['samaccountname'][0]
					);
				}else{
					$response[] = array(
						'Cn' => $entries[$i]['cn'][0],
						'DistName' => $entries[$i]['distinguishedname'][0],
						'Description' => $entries[$i]['description'][0],
						'CreationDate' => date('Y-m-d H:i:s', ($entries[$i]['whencreated'][0] / 10000000) - 11676009600),
						'ChanginDate' => date('Y-m-d H:i:s', ($entries[$i]['whenchanged'][0] / 10000000) - 11676009600),
						'Critical' => $entries[$i]['iscriticalsystemobject'][0]
					);
				}
				
			}

			ldap_unbind($this->ConexionLDAP); // Clean up after ourselves.

			return $response;
		}

		public function cleanString($string){
             
			$string = trim($string);
			
            $string = str_replace(
                array("\\", "¨", "º", "~","°","¬",
                     "#", "@", "|", "!", "\"","`",
                     "·", "$", "%", "/",
                     "(", ")", "?", "'", "¡",
                     "¿", "[", "^", "<code>", "]",
                     "+", "}", "{", "¨", "´",
                     ">", "< ", ";", ",", ":",
                     ".","="),
                '',
                $string
            );
           	return mb_strtoupper($string, 'utf-8');
        }
	}
?>