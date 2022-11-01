<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    require_once('sessionController.php');
    $session = new session();
    
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
            if(isset($_POST['pag']) && isset($_POST['tipo'])){
                $response = array('Status' => 'Invalid call','ASD' => $_POST);
        		switch ($_POST['pag']){
        			case 'Motivos':
                        include ('motivoController.php');
                        $Motivo = new motivo();
                        switch ($_POST['tipo']) { 
                            case 'i':
                                if($Motivo->validateFields('insert')){
                                    $response =  $Motivo->insertMotivo();
                                }
                            break;
                            case 'g':
                                $Motivo->getMotivo();
                                $response =  $Motivo->listMotivo;
                            break;
                            // case 'd':
                            //     if($Motivo->validateFields('delete')){
                            //         $response =  $Motivo->changeMotivo();
                            //     }
                            // break;
                            case 'u':
                                if($Motivo->validateFields('update')){
                                    $response =  $Motivo->updateMotivo();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Equipo':
                        require 'equipoController.php';
                        $Equipo = new equipo();
                        switch ($_POST['tipo']) { 
                            case 'ue':
                                $response =  $Equipo->updateEquipo();
                            break;
                        }
                    break;
                    case 'SubMotivo':
                        include ('motivoController.php');
                        $Motivo = new motivo();
                        switch ($_POST['tipo']) { 
                            case 'i':
                                if($Motivo->validateFields('insert')){
                                    $response =  $Motivo->insertSubMotivo();
                                }
                            break;
                            case 'g':
                                $Motivo->getMotivo();
                                $response =  $Motivo->motivo;
                            break;
                            // case 'd':
                            //     if($Motivo->validateFields('delete')){
                            //         $response =  $Motivo->changeMotivo();
                            //     }
                            // break;
                            case 'u':
                                if($Motivo->validateFields('update')){
                                    $response =  $Motivo->updateMotivo();
                                }
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Ticket':
                    case 'Solicitudes':
                    case 'Mis Tickets':
                        require ('ticketController.php');
                        $Ticket = new ticket();
                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 't' = Setear Tecnico, 's' = Cambiar Estado Ticket
                            case 'i':
                                if($Ticket->validateFields('insert')){
                                    $response =  $Ticket->insertTicket();
                                }
                            break;
                            case 'g':
                                $Ticket->getTicket();
                                $response =  $Ticket->listTicket;
                            break;
                            // case 'd':
                            //     if($Ticket->validateFields('delete')){
                            //         $response =  $Ticket->changeMotivo();
                            //     }
                            // break;
                            case 't':
                                if($Ticket->validateFields('setTecnico') && $_SESSION['TICKETS'] == 1){
                                    $response = $Ticket->setTecnico();
                                }
                            break;
                            case 's': //Cambiar de estado
                                if($Ticket->validateFields('state_ticket')){
                                    $response = $Ticket->changeState();
                                }
                            break;
                            case 'pdt': //Pausar/despausar ticket
                                if($Ticket->validateFields('state_ticket')){
                                    $response = $Ticket->pauseTicket();
                                }
                            break;
                            case 'c': //Consulta de tickets
                                if($Ticket->validateFields('consult_ticket')){
                                    $response = $Ticket->getStateTicket();
                                }
                            break;
                            case 'p': //Agregar participantes
                                if($Ticket->validateFields('add_participant')){
                                    $response = $Ticket->addParticipant();
                                    // $response = explode(",", $_POST['participante']);;
                                }
                            break;
                            case 'dp': //ELIMINAR PARTICIPANTE
                                if($Ticket->validateFields('delete_participante')){
                                    $response = $Ticket->deleteParticipante();
                                }
                            break;
                            case 'ud': //ACTUALIZAR COMENTARIO INTERNO
                                if($Ticket->validateFields('add_internal_comment')){
                                    $response = $Ticket->addInternalComment();
                                }
                            break;
                            case 'cc': //Confirmar Cierre
                                if($Ticket->validateFields('confirm_closure')){
                                    $response = $Ticket->ConfirmClosure();
                                }
                            break;
                            case 'hu': //Historial de usuario
                                if(isset($_POST['id'])){
                                    $response = $Ticket->getUserHistory($_POST['id']);
                                }
                            break;
                            case 'he': //Historial de Equipo
                                if(isset($_POST['id'])){
                                    $response = $Ticket->getEquipoHistory($_POST['id']);
                                }
                            break;
                            // case 'ct': //Cancelar Ticket
                            //     if($Ticket->validateFields('cancel_ticket')){
                            //         $response = $Ticket->cancelTicket();
                            //     }
                            // break;
                            case 'ar': //Archivo
                                require_once 'archivoController.php';
                                $Archivo = new archivo();
                                
                                $response = $Archivo->chargeArchive();
                            break;
                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Informes':
                        include ('staticsController.php');
                        $Statics = new statics();

                        switch ($_POST['tipo']) { // 'a' = Actualizar Datos
                            case 'a':
                                if($Statics->validateFields('getStatics')){
                                    $response =  $Statics->getStatics();
                                }
                            break;

                            default:
                                $response = array('Status' => 'Invalid call');
                            break;
                        }
                    break;
                    case 'Reportes':
                        require ('staticsController.php');
                        $Statics = new statics();
                        
                        switch ($_POST['tipo']) {
                            case 'graficos':
                                $response = $Statics->getData();
                            break;
                            case 'excel':
                                $response = $Statics->getExcelData();
                            break;
                        }
                    break;
                    case 'Autocompletar':
                        require 'autocompleteController.php';
                        $Autocomplete = new autocomplete();

                        switch ($_POST['tipo']) { // 'a' = Actualizar Datos
                            case 'Usuarios':
                                $response = $Autocomplete->searchUser();
                            break;
                            case 'Patrimonio':
                                $response = $Autocomplete->searchEquipo('PATRIMONIO');
                            break;
                            case 'Interno':
                                $response = $Autocomplete->searchEquipo('INTERNO');
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
}else{
    header("location: ../../index.php");
}

?>