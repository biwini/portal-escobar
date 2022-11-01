<?php

if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $session = new session();
    }

    $response = array('Status' => 'Invalid call', $_POST);

    if($session->isLogued() && isset($_SESSION['OBRAS_PUBLICAS'])){
        if(isset($_POST['pag']) && isset($_POST['tipo'])){
            switch ($_POST['pag']){
                case 'Obras':
                    require ('obraController.php');
                    $Obra = new obra();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Obra->insertObra();
                        break;
                        case 'u':
                            $response =  $Obra->updateObra();
                        break;
                        case 'ua':
                            $response =  $Obra->insertDatosAdicionales();
                        break;
                        case 'uc':
                            $response =  $Obra->insertCertificado();
                        break;
                        case 'uca':
                            $response =  $Obra->updateCertificados();
                        break;
                        case 'u':
                            $response = $Obra->UpdateObra();
                        break;
                        case 'd':
                            $response = $Obra->deleteObra();
                        break;
                    }
                break;
                case 'Modalidad':
                    require ('modalidadController.php');
                    $Modalidad = new modalidad();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Modalidad->insertModalidad();
                        break;
                        case 'u':
                            $response = $Modalidad->updateModalidad();
                        break;
                        case 'd':
                            $response = $Modalidad->deleteModalidad();
                        break;
                    }
                break;
                case 'Afectacion':
                    require ('afectacionController.php');
                    $Afectacion = new afectacion();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Afectacion->insertAfectacion();
                        break;
                        case 'u':
                            $response = $Afectacion->updateAfectacion();
                        break;
                        case 'd':
                            $response = $Afectacion->deleteAfectacion();
                        break;
                    }
                break;
                case 'Fuente':
                    require ('fuenteController.php');
                    $Fuente = new fuente();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Fuente->insertFuente();
                        break;
                        case 'u':
                            $response = $Fuente->updateFuente();
                        break;
                        case 'd':
                            $response = $Fuente->deleteFuente();
                        break;
                    }
                break;
                case 'TipoObra':
                    require ('tipoObraController.php');
                    $TipoObra = new tipoObra();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $TipoObra->insertTipoObra();
                        break;
                        case 'u':
                            $response = $TipoObra->updateTipoObra();
                        break;
                        case 'd':
                            $response = $TipoObra->deleteTipoObra();
                        break;
                    }
                break;
                case 'Estados':
                    require ('estadoController.php');
                    $Estado = new estado();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Estado->insertEstado();
                        break;
                        case 'u':
                            $response = $Estado->updateEstado();
                        break;
                        case 'd':
                            $response = $Estado->deleteEstado();
                        break;
                    }
                break;
                case 'Jurisdicciones':
                    require ('jurisdiccionController.php');
                    $Jurisdiccion = new jurisdiccion();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Jurisdiccion->insertJurisdiccion();
                        break;
                        case 'u':
                            $response = $Jurisdiccion->updateJurisdiccion();
                        break;
                        case 'd':
                            $response = $Jurisdiccion->deleteJurisdiccion();
                        break;
                    }
                break;
                case 'Proyectos':
                    require ('proyectoController.php');
                    $Proyecto = new proyecto();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Proyecto->insertProyecto();
                        break;
                        case 'u':
                            $response = $Proyecto->updateProyecto();
                        break;
                        case 'd':
                            $response = $Proyecto->deleteProyecto();
                        break;
                    }
                break;
                case 'UnidadEjecutora':
                    require ('unidadEjecutoraController.php');
                    $UnEjecutora = new uEjecutora();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $UnEjecutora->insertUnidadEjecutora();
                        break;
                        case 'u':
                            $response = $UnEjecutora->updateUnidadEjecutora();
                        break;
                        case 'd':
                            $response = $UnEjecutora->deleteUnidadEjecutora();
                        break;
                    }
                break;
                case 'Gasto':
                    require ('objetoGastoController.php');
                    $Gasto = new gasto();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Gasto->insertGasto();
                        break;
                        case 'u':
                            $response = $Gasto->updateGasto();
                        break;
                        case 'd':
                            $response = $Gasto->deleteGasto();
                        break;
                    }
                break;
                case 'Proveedores':
                    require ('proveedorController.php');
                    $Proveedor = new proveedor();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $Proveedor->insertProveedor();
                        break;
                        case 'u':
                            $response = $Proveedor->updateProveedor();
                        break;
                        case 'd':
                            $response = $Proveedor->deleteProveedor();
                        break;
                    }
                break;
                case 'OrdenPago':
                    require ('ordenPagoController.php');
                    $OP = new ordenPago();

                    switch ($_POST['tipo']) {
                        case 'r':
                            $response =  $OP->insertOrdenPago();
                        break;
                        case 'u':
                            $response = $OP->updateOrdenPago();
                        break;
                        case 'd':
                            $response = $OP->deleteOrdenPago();
                        break;
                    }
                break;
            }

            echo json_encode($response); 
        }else{
            echo json_encode($response);
        }
    }else{
        echo json_encode($response);
    }
}else{
    header("location: ../../index.php");
}

?>