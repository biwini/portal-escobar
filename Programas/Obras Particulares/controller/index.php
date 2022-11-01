<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }

    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO'] && isset($_SESSION['TICKETS_DE_COMBUSTIBLE'])){
            if(isset($_POST['pag']) && isset($_POST['tipo'])){
                $response = array('Status' => 'Invalid call','ASD' => $_POST);
        		switch ($_POST['pag']){
                    case 'Liquidacion': 
                        include ('liquidacionController.php');
                        $Liquidacion = new liquidacion();

                        switch ($_POST['tipo']) { 
                            case 'normal':
                                $response = $Liquidacion->addLiquidacion();
                            break;
                        }
                    break;
        			case 'Vehículos':
                        include ('vehiculoController.php');
                        $Vehiculo = new vehiculo();

                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'r':
                                $response = $Vehiculo->addCar();
                            break;
                            case 'u':
                                $response = $Vehiculo->updateCar();
                            break;
                            case 'd':
                                $response =  $Vehiculo->deleteCar();  
                            break;
                        }
        			break;
                    case 'Proveedores':
                        include ('proveedorController.php');
                        $Proveedor = new proveedor();

                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'r':
                                $response = $Proveedor->addProvider();
                            break;
                            case 'u':
                                $response = $Proveedor->updateProvider();
                            break;
                            case 'd':
                                $response =  $Proveedor->deleteProvider();  
                            break;
                        }
                    break;
                    case 'Remitos':
                        include ('remitoController.php');
                        $Remito = new remito();

                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'r':
                                $response = $Remito->addRemito();
                            break;
                            case 'send':
                                $response = $Remito->sendRemitosToProvider();
                            break;
                            // case 'u':
                            //     $response = $Remito->updateProvider();
                            // break;
                            // case 'd':
                            //     $response =  $Remito->deleteProvider();  
                            // break;
                        }
                    break;
                    case 'Exportar Remitos':
                        include ('talonarioController.php');
                        $Talonario = new talonario();

                        switch ($_POST['tipo']) { // 'e' Exportar registros
                            case 'e':
                                $response = $Talonario->getRecorsToExport();
                            break;

                        }
                    break;
                    case 'Ordenes de compra':
                        include ('ordenCompraController.php');
                        $Orden = new ordencompra();

                        switch ($_POST['tipo']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                            case 'r':
                                $response = $Orden->addOrder();
                            break;
                            case 'u':
                                $response = $Orden->updateOrder();
                            break;
                            // case 'd':
                            //     $response =  $Remito->deleteProvider();  
                            // break;
                        }
                    break;
        			default:
        				$response = array('Status' => 'Invalid call');
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