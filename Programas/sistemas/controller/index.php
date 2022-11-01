<?php
    require_once('sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
            if(isset($_POST['pag']) && isset($_POST['tipo'])){
                $response = null;
        		switch ($_POST['pag']){
        			case 'Program':
                        include ('programController.php');
                        $Program = new program();
                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if(!$Program->validateEmptyPost(array())){
                                    $response =  $Program->insertProgram();
                                    // $response =  $Liquidacion->WHERE;
                                }else{
                                    $response =  array('Status' => 'Invalid call');
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
                                if(!$Program->validateEmptyPost(array())){
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
                    case 'Area':
                        include ('areaController.php');
                        $Area = new area();
                        switch ($_POST['tipo']) { // 'i' = Insertar Area, 'g' = Obtener Area, 'd' = Eliminar Liqudiacion
                            case 'i':
                                if($Area->validateFields('insert')){
                                    $response = $Area->insertArea();
                                    // $response = $Liquidacion->WHERE;
                                }else{
                                    var_dump($_POST);
                                }
                            break;
                            case 'g':
                                $Area->getArea();
                                $response = $Area->listArea;
                            break;
                            case 'd':
                                if($Area->validateFields('changeState')){
                                    $response = $Area->changeAreaState();
                                }
                            break;
                            case 'u':
                                if($Area->validateFields('update')){
                                    $response = $Area->updateArea();
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
