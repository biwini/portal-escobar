<?php

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }
    if($_SESSION['LOGUEADO'] && isset($_SESSION['ADMINISTRATOR'])){
        if(isset($_POST['pag']) && isset($_POST['tipo'])){
            $response = array('Status' => 'Invalid call', $_POST);
            switch ($_POST['pag']){
                case 'Programas':
                    include ('programController.php');
                    $Program = new program();
                    switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'i':
                            $response =  $Program->insertProgram();
                        break;
                        case 'g':
                            if(!$Program->validateEmptyPost(array())){
                                $Program->getProgram();
                                $response =  $Program->program;
                            }else{
                                $response =  array('Status' => 'Invalid call');
                            }
                        break;
                        case 'u':
                            $response = $Program->updateProgram();
                        break;

                    }
                break;
                case 'Secretarias':
                    require ('secretaryController.php');
                    $Secretaria = new secretaria();
                    switch ($_POST['tipo']) { // 'i' = Insertar Area, 'g' = Obtener Area, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Secretaria->addSecretary();
                        break;
                        case 'g':
                            $Secretaria->getSecretary();
                            $response = $Secretaria->listSecretary;
                        break;
                        // case 'd':
                        //     if($Secretaria->validateFields('changeState')){
                        //         $response = $Secretaria->changeSecretaryState();
                        //     }
                        // break;
                        case 'u':
                            $response = $Secretaria->updateSecretary();
                        break;
                    }
                break;
                case 'Dependencias':
                    include ('dependenceController.php');
                    $Dependencia = new dependencia();
                    switch ($_POST['tipo']) { // 'i' = Insertar Area, 'g' = Obtener Area, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Dependencia->addDepdendence();
                        break;
                        // case 'd':
                        //     if($Dependencia->validateFields('changeState')){
                                // $response = $Dependencia->changeDependenceState();
                        //     }
                        // break;
                        case 'u':
                            $response = $Dependencia->updateDependence();
                        break;
                    }
                break;
                case 'Usuarios':
                    include ('adminController.php');
                    $Admin = new admin();
                    switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Admin->insertUser();
                        break;
                        case 'g':
                            $response = $Admin->getUser();
                        break;
                        case 'u':
                            $response = $Admin->updateUser();
                        break;
                        case 's':
                            $response = $Admin->getUsers();
                        break;
                    }
                break;
                case 'Localidades':
                    include ('localidadController.php');
                    $Location = new localidad();
                    switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Location->addLocalidad();
                        break;
                        case 'g':
                            $response = $Location->getLocalidades();
                        break;
                        // case 'd':
                        //     if(!$Location->validateEmptyPost(array())){
                        //         $response = $Location->changeAreaState();
                        //     }
                        // break;
                        case 'u':
                            $response = $Location->updateLocalidad();
                        break;
                    }
                break;
                case 'Compartida':
                    include ('compartidaController.php');
                    $Compartida = new compartida();
                    switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                        case 'i':
                            if($Compartida->validateFields('insert')){
                                $response = $Compartida->insertCompartida();
                                // $response = $Liquidacion->WHERE;
                            }else{
                                $response = array('Status' => 'Invalid call', 'ASD' => $_POST);                                
                            }
                        break;
                        case 'g':
                            $response = $Compartida->getCompartida();
                        break;
                        // case 'd':
                        //     if(!$Compartida->validateEmptyPost(array())){
                        //         $response = $Compartida->changeAreaState();
                        //     }
                        // break;
                        case 'u':
                            if($Compartida->validateFields('update')){
                                $response = $Compartida->updateCompartida();
                            }
                        break;
                    }
                break;
                case 'Equipos':
                    include ('equipoController.php');
                    $Equipo = new equipo();
                    switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Equipo->insertEquipo();
                        break;
                        case 'g':
                            $response = $Equipo->getEquipos();
                        break;
                        case 'u':
                            $response = $Equipo->updateEquipo();
                        break;
                        case 'd':
                            $response = $Equipo->removeEquipo();
                        break;
                    }
                break;
                case 'Accesos':
                    include ('accessController.php');
                    $Access = new access();
                    switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                        case 'r':
                            $response = $Access->createAccess();
                        break;
                        case 'g':
                            $Access->getAccess();
                            $response = $Access->access;
                        break;
                        // case 'd':
                        //     if(!$Access->validateEmptyPost(array())){
                        //         $response = $Access->changeAreaState();
                        //     }
                        // break;
                        case 'u':
                            $response = $Access->updateAccess();
                        break;
                        case 'd':
                            $response = $Access->deleteAccess();
                        break;
                        case 'c':
                            $response = $Access->changeStateAccess();
                        break;
                    }
                break;
                case 'ActiveDirectory':
                    require 'ldapController.php';
                    $Ldap = new ldap();
                    switch ($_POST['tipo']) { 
                        case 's':
                            $response = $Ldap->ValidateUser($_POST['search']);
                        break;
                    }
                break;
            }

            echo json_encode($response);
            
        }else{
            echo json_encode(array('Status' => 'Invalid call'));
        }
    }else{
        header("location: ../../index.php");
    }
}else{
    header("location: ../../index.php");
}

?>