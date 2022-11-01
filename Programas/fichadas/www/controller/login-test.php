<?php 

    require 'globalController2.php';

    class login extends globalController{
        public function logins(){
        
            $this->query = 'SELECT u.idUsuario, u.idDependencia, u.cNombre, u.cApellido, u.cSexo, u.cNLegajo, u.nTelefono, u.cEmail, u.cContrasenia, d.idSecretaria FROM Usuario AS u 
                INNER JOIN Dependencia AS d ON u.idDependencia = d.idDependencia
                WHERE u.cNLegajo = 40649445';
            $result = $this->executeQuery();
            if($result){	//Verifico si la consulta fue correcta
                while ($row = $result->fetch()) {	//Recorro los datos obtenidos
                    $ip = $this->getUserIpAddress();	//Obtengo la ip del usuario que intenta loguearse.
                    //Añado los datos obtenidos a un array.
                    $this->user = array(
                        'Id' => $row['idUsuario'],
                        'IdDependence' => $row['idDependencia'],
                        'IdSecretary' => $row['idSecretaria'],
                        'HashPass' => $row['cContrasenia'],
                        'Name' => $row['cNombre'],
                        'LastName' => $row['cApellido'],
                        'Gender' => $row['cSexo'],
                        'Legajo' => $row['cNLegajo'],
                        'Cellphone' => $row['cSexo'],
                        'Email' => $row['cSexo'],
                        'Ip' => ($ip != '?') ? $ip : $this->getLocalIp()
                    );	
                }
        
                // $this->query = 'SELECT COUNT(idAcceso) FROM Acceso WHERE idUsuario = :Id';		//Busco los programas a los que tiene acceso el usuario
                $this->data = [':Id' => $this->user['Id']];

                // if($this->searchRecords() != 0){	//Si tiene accesos
                    $this->query = 'SELECT a.idPrograma,a.nPermiso,p.cNomPrograma,p.idDependencia,p.cUrl FROM acceso AS a
                    INNER JOIN Programa AS p ON a.idPrograma = p.idPrograma WHERE idUsuario = :Id';	//Obtengo los acceso del usuario

                    $result = $this->executeQuery();
                    while ($row = $result->fetch()){	//Recorro los resultados.
                        $this->program[] = array('Id' => $row['idPrograma'],'Name' => $row['cNomPrograma'],'Url' => $row['cUrl'],'IdDependencia' => $row['idDependencia']); //Añado los programas a un array.
                        //----------------------- ESTAS VARIABLES SON FUNDAMENTALES PARA TODOS LOS PROGRAMAS DEL "SISTEMA DE PORTAL ESCOBAR".
                        $sesion = str_replace(' ','_',$row['cNomPrograma']);	// REEMPLAZO LOS ESPACIOS POR GUIONES BAJOS 

                        //CONVIERTO EL NOMBRE DEL PROGRAMA EN EL NOMBRE DE LA SESSION QUE UTILIZO EN LOS DISTINTOS SISTEMAS Y LE AGREGO EL NIVEL DE ACCESO
                        $_SESSION[$sesion] = $row['nPermiso'];  //($row['nPermiso'] == 1) ? 'ADMINISTRADOR' : 'USUARIO'; // ES DE TOTAL IMPORTANCIA QUE EL NOMBRE DE LA SESSION EN EL SISTEMA SEA IDENTICO AL NOMBRE DEL PROGRAMA
                        $this->cantPermisos[] = $row['cNomPrograma'];	//
                        $this->url = $row['cUrl'];
                        //--------------------------------------------------- FIN VARIABLEs FUNDAMENTAL---------------------------------------------------------
                    }

                    $_SESSION['CANT_PERMISOS'] = $this->cantPermisos;	//Añado los premisos del usuario a una variable de sesion
                    $_SESSION['ID_USER'] = $this->user['Id'];	//Añado el 'id' del usuario a una variable de session
                    $_SESSION['NOMBRE_USER'] = $this->user['Name'];	// Añado el nombre del usuario a una variable de session
                    $_SESSION['APELLIDO_USER'] = $this->user['LastName'];	// Añado el apellido del usuario a una variable de session
                    $_SESSION['LEGAJO'] = $this->user['Legajo'];	// Añado el Legajo del usuario  a una variable de session
                    $_SESSION['SEXO'] = $this->user['Gender'];
                    $_SESSION['TELEFONO'] = $this->user['Cellphone'];
                    $_SESSION['EMAIL'] = $this->user['Email'];
                    $_SESSION['SECRETARIA'] = $this->user['IdSecretary'];
                    $_SESSION['DEPENDENCIA'] = $this->user['IdDependence'];
                    $_SESSION['IP'] = $this->user['Ip'];
                    $_SESSION['LOGUEADO'] = true;	// Establezco la variable de session 'LOGUEADO' con un estado de true

                    $Accesos = "";	// Variable creada para almacenar un string con los accesos del usuario para razones de auditoria
                    foreach ($_SESSION["CANT_PERMISOS"] as $Programas ){
                        $Accesos = $Programas.",".$Accesos;	// Añado los accesos al string.
                    }
                    
                    $this->query = 'INSERT INTO Auditoria (idUsuario,cAcceso,cIp,dFecha) VALUES (:Id, :Acc, :Ip, :Fecha)';
                    $this->data = [':Id' => $this->user['Id'], ':Acc' => $Accesos, ':Ip' => $this->user['Ip'], ':Fecha' => $this->fecha];
                    $this->executeQuery();	// Inserto una auditoria del inicio de session.

                    if(count($this->cantPermisos) != 1){	//si tengo mas de un permiso
                        foreach ($this->program as $key => $programa) {	//recorro los programas a los que tengo acceso
                            if(in_array($programa['Name'], $_SESSION["CANT_PERMISOS"], true)){	//Compruebo que de verdad tenga acceso al programa.
                                $this->pages[] = $programa['Url'];	//Establesco las url del programa
                            }
                        }
                    }

                    $_SESSION['PAGINAS_PERMITIDAS'] = array($this->url);
                    $_SESSION["PAGINA_PERMITIDA"] = $this->url;

                    $_SESSION['SECRETARIAS'] = $this->getSecretaries();

                    if (count($this->pages) > 1) {
                        $_SESSION['PAGINAS_PERMITIDAS'] = $this->pages;
                    }

                    return array('Status' => 'Success');	//Devuelvo un array con el estado de succes
                // }else{
                // 	return array('Status' => 'No Access');
            // }
            }
        }
    }

    $hola = new login();

    $hola->logins();
?>