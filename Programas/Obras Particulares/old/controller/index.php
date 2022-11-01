<?php
    require_once('sessionController.php');
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
        if(isset($_SESSION['LOGUEADO'])){
            if($_SESSION['LOGUEADO']){
            	if(isset($_SESSION["OBRAS_PARTICULARES"])){
                    if(isset($_POST['pag']) && isset($_POST['tipo'])){
                        include ('liquidacionController.php');
                        $Liquidacion = new liquidacion();
                        $Response = array('Status' => 'Invalid call');
                		switch ($_POST['pag']){
                			case 'Consultas':
                                switch ($_POST['tipo']) { // 'gf' = obterner liquidacion con filtros, 'g' = Obtener liquidacion, 'd' = Eliminar Liqudiacion
                                    case 'gf':
                                    case 'g':
                                        $Response = $Liquidacion->getLiquidacion();
                                        // echo $Liquidacion->WHERE;
                                    break;
                                    case 'd':
                                        $Response = $Liquidacion->deleteLiquidacion();
                                    break;
                                    default:
                                        $Response = array('Status' => 'Invalid call');
                                    break;
                                }
                			break;
                			default:
                				echo json_encode($Response);
                			break;
                		}
                        echo json_encode($Response);
                    }else{
                        echo json_encode(array('Status' => 'Invalid call'));
                    }
            	}else{
            		header("location: ../../../index.php");
            	}
    		}else{
    			header("location: ../../../index.php");
    		}
    	}else{
    		header("location: ../../../index.php");
    	}
    }else{
        header("location: ../../../index.php");
    }
?>
