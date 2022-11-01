<?php
    require_once('sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO'] && isset($_SESSION['ADMINISTRATOR'])){
            if(isset($_POST['pag']) && isset($_POST['tipo'])){
                $response = null;
        		switch ($_POST['pag']){
        			case 'Program':
                        include ('programController.php');
                        $Program = new program();
                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Program->validateFields('insert')){
                                    $response =  $Program->insertProgram();
                                    // $response =  $Liquidacion->WHERE;
                                }else{
                                    $response =  array('Status' => 'Invalid call','ASD' => $_POST);
                                }
                            break;
                            case 'g':
                                if(!$Program->validateEmptyPost(array())){
                                    $Program->getProgram();
                                    $response =  $Program->program;
                                }else{
                                    $response =  array('Status' => 'Invalid call');
                                }
                            break;
                            case 'd':
                                if(!$Program->validateEmptyPost(array())){
                                    $response =  $Program->changeProgramState();
                                }else{
                                    $response =  array('Status' => 'Invalid call');
                                }
                            break;
                            case 'u':
                                if($Program->validateFields('update')){
                                    $response =  $Program->updateProgram();
                                }else{
                                    $response =  array('Status' => 'Invalid call');
                                }
                            break;
                            default:
                                $response =  array('Status' => 'Invalid call');
                            break;
                        }
        			break;
                    case 'Secretaria':
                        include ('secretaryController.php');
                        $Secretaria = new secretaria();
                        switch ($_POST['tipo']) { // 'i' = Insertar Area, 'g' = Obtener Area, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Secretaria->validateFields('insert')){
                                    $response = $Secretaria->insertSecretary();
                                    // $response = $Liquidacion->WHERE;
                                }else{
                                    var_dump($_POST);
                                }
                            break;
                            case 'g':
                                $Secretaria->getSecretary();
                                $response = $Secretaria->listSecretary;
                            break;
                            case 'd':
                                if($Secretaria->validateFields('changeState')){
                                    $response = $Secretaria->changeSecretaryState();
                                }
                            break;
                            case 'u':
                                if($Secretaria->validateFields('update')){
                                    $response = $Secretaria->updateSecretary();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Dependencia':
                        include ('secretaryController.php');
                        $Secretaria = new secretaria();
                        switch ($_POST['tipo']) { // 'i' = Insertar Area, 'g' = Obtener Area, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Secretaria->validateFields('insert')){
                                    $response = $Secretaria->insertDependence();
                                    // $response = $Liquidacion->WHERE;
                                }
                            break;
                            case 'd':
                                if($Secretaria->validateFields('changeState')){
                                    $response = $Secretaria->changeDependenceState();
                                }
                            break;
                            case 'u':
                                if($Secretaria->validateFields('update')){
                                    $response = $Secretaria->updateDependence();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'User':
                        include ('adminController.php');
                        $Admin = new admin();
                        switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Admin->validateFields('insert')){
                                    $response = $Admin->insertUser();
                                    // $response = $Liquidacion->WHERE;
                                }
                            break;
                            case 'g':
                                $Admin->getUser();
                                $response = $Admin->admin;
                            break;
                            // case 'd':
                            //     if(!$Admin->validateEmptyPost(array())){
                            //         $response = $Admin->changeAreaState();
                            //     }
                            // break;
                            case 'u':
                                if($Admin->validateFields('update')){
                                    $response = $Admin->updateUser();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Localidad':
                        include ('localidadController.php');
                        $Location = new localidad();
                        switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Location->validateFields('insert')){
                                    $response = $Location->insertLocation();
                                    // $response = $Liquidacion->WHERE;
                                }else{
                                    $response = array('Status' => 'Invalid call', 'ASD' => $_POST);                                
                                }
                            break;
                            case 'g':
                                $Location->getLocation();
                                $response = $Location->localidad;
                            break;
                            // case 'd':
                            //     if(!$Location->validateEmptyPost(array())){
                            //         $response = $Location->changeAreaState();
                            //     }
                            // break;
                            case 'u':
                                if($Location->validateFields('update')){
                                    $response = $Location->updateLocation();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
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
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Equipo':
                        include ('equipoController.php');
                        $Equipo = new equipo();
                        switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                            case 'i':
                                $response = $Equipo->validateInsert();
                            break;
                            case 'g':
                                $response = $Equipo->getEquipo();
                            break;
                            case 'u':
                                $response = $Equipo->validateUpdate();
                            break;
                            default:
                                $response = array('Status' => 'Invalid call2');
                            break;
                        }
                    break;
                    case 'Access':
                        include ('accessController.php');
                        $Access = new access();
                        switch ($_POST['tipo']) { // 'i' = Insertar user, 'g' = Obtener user, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Access->validateFields('insert')){
                                    $response = $Access->createAccess();
                                    // $response = $Liquidacion->WHERE;
                                }
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
                                if($Access->validateFields('update')){
                                    $response = $Access->updateAccess();
                                }
                            break;
                            case 'd':
                                if($Access->validateFields('delete')){
                                    $response = $Access->deleteAccess();
                                }
                            break;
                            case 'c':
                                if($Access->validateFields('changeState')){
                                    $response = $Access->changeStateAccess();
                                }else{
                                    var_dump($Access->validateFields('changeState'));
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Admin':
                        include 'ldapController.php';
                        switch ($_POST['tipo']) { 
                            case 's':
                                $Ldap = new ldap();

                                $response = $Ldap->ValidateUser($_POST['search']);
                            break;
                        }
                    break;
        			default:
        				$response = array('Status' => 'Invalid call');
        			break;
        		}
                if(!$response){  
                    echo json_encode(array('Status' => 'Invalid call'));
                }else{
                    echo json_encode($response);
                }
            }else{
                echo json_encode(array('Status' => 'Invalid call'));
            }
		}else{
			header("location: ../../index.php");
		}
	}else{
		header("location: ../../index.php");
	}
