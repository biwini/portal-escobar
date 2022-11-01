<?php
// if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
//     if(!isset($_SESSION)){
//         require_once ('sessionController.php');
//         $Session = new session();
//     }
// }else{
//     header("location: ../../index.php");
// }
if(!isset($_SESSION)){
    require_once ('sessionController.php');
    $Session = new session();
}

    if(!isset($_SESSION['LOGUEADO'])){
        header("location: ../../index.php");
    }
    
    if($_SESSION['LOGUEADO'] && $_SESSION['FICHADAS']){
        if(isset($_REQUEST['page']) && isset($_REQUEST['action'])){
            $response = array('Status' => 'Invalid call', $_POST);
            switch ($_REQUEST['page']){
                case 'Reporte':
                    require ('reportController.php');
                    $report = new Report();
                    $secretary = new Secretary();
                    switch ($_REQUEST['action']) {
                        case 'getFichadasEmployee':
                            $response=$report->getFichadasEmployee();
                        break;
                        // case 'getHorariosEmployee':
                        //     $response=$report->getHorariosEmployee();
                        // break;
                        case 'getEmployee':
                            $response=$report->getEmployee();
                        break;
                        case 'getSecretary':
                            $response = $secretary->getAllSecretary();
                        break;
                        case 'getDependence':
                            $response=$secretary->getDependence();
                        break;
                        case 'searchEmployee':
                            $response=$report->searchEmployee();
                        break;
                        // case 'getHolidays':
                        //     $response=$report->getHolidays();
                        // break;
                        // case 'getLicencias':
                        //     $response=$report->getLicencias();
                        // break;
                    }
                break;
                case 'Empleados':
                    require ('employeeController.php');
                    $Empleado = new employee();

                    switch ($_REQUEST['action']) {
                        case 'getEmployee':
                            $response = $Empleado->getEmployee();
                        break;
                        case 'getEmployeesPaginated':
                            $response = $Empleado->getPersonas();
                        break;
                        case 'getInactiveEmployeesPaginated':
                            $response = $Empleado->getInactivePersonas();
                        break;
                        case 'getEmployeesSimple':
                            $response = $Empleado->getEmployeesSimple();
                        break;
                        case 'getEmployeesAmount':
                            $response = $Empleado->getEmployeesAmount();
                        break;
                        case 'getAllEmployeeTypes':
                            $response = $Empleado->getEmpoloyeesTypes();
                        break;
                        case 'insertEmployee':
                            $response = $Empleado->insertEmployee();
                        break;
                        case 'editEmployee':
                            $response = $Empleado->editEmployee();
                        break;
                        case 'deleteEmployee':
                            $response = $Empleado->deleteEmployee();
                        break;
                        case 'restoreEmployee':
                            $response = $Empleado->restoreEmployee();
                        break;
                    }
                break;
                case 'Horario':
                    require ('horarioController.php');
                    $Horario = new horario();

                    switch ($_REQUEST['action']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'todos':
                            $response = $Horario->getHorarios();
                        break;
                        case 'buscar':
                            $response = $Horario->getHorario();
                        break;
                        case 'insertar':
                            $response = $Horario->insertHorario();
                        break;
                        case 'actualizar':
                            $response = $Horario->updateHorario();
                        break;
                    }
                break;
                case 'Relojes':
                    require ('relojController.php');
                    $Reloj = new reloj();

                    switch ($_REQUEST['action']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'get':
                            $response = $Reloj->getRelojes();
                        break;
                        case 'send':
                            $response = $Reloj->insertReloj();
                        break;
                        case 'search':
                            $response = $Reloj->searchReloj();
                        break;
                        case 'getLastID':
                            $response = $Reloj->getRelojLastId();
                        break;
                    }
                break;
                case 'Licencias':
                    
                    require ('licenciaController.php');
                    $Licencia = new licencia();

                    switch ($_REQUEST['action']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'getLicencias':
                            $response = $Licencia->getLicencias();
                        break;
                        case 'getLicencia':
                            $response = $Licencia->getLicencia();
                        break;
                        case 'getHistory':
                            $response = $Licencia->getHistory();
                        break;
                        case 'send':
                            $response = $Licencia->insertLicense();
                        break;
                        case 'editLicencia':
                            $response = $Licencia->updateLicense();
                        break;
                        case 'eliminar':
                            $response = $Licencia->deleteLicense();
                        break;
                    }
                break;
                case 'Motivos':
                    require ('motivoController.php');
                    $Motivo = new motivo();

                    switch ($_POST['action']) {
                        case 'getMotivos':
                            $response = $Motivo->getMotivos();
                        break;
                        case 'add':
                            $response = $Motivo->insertMotivo();
                        break;
                        case 'update':
                            $response = $Motivo->updateMotivo();
                        break;
                        case 'delete':
                            $response = $Motivo->deleteMotivo();
                        break;
                    }
                break;
                case 'TiposEmpleado':
                    require ('tipoEmpleadoController.php');
                    $Tipo = new tipoEmpleado();

                    switch ($_REQUEST['action']) {
                        case 'getTiposEmpleado':
                            $response = $Tipo->getTiposEmpleado();
                        break;
                        case 'add':
                            $response = $Tipo->insertTipo();
                        break;
                        case 'update':
                            $response = $Tipo->updateTipo();
                        break;
                        case 'delete':
                            $response = $Tipo->deleteTipo();
                        break;
                    }
                break;

                case 'Fichadas':
                    require ('fichadaController.php');
                    $fichadaController = new FichadaController();

                    switch ($_REQUEST['action']) {
                        case 'add':
                            $response = $fichadaController->addFichada($_REQUEST['listEmployees'], $_REQUEST['datetime']);
                        break;

                    }
            }
            echo json_encode($response);
        }else{
            echo json_encode(array('Status' => 'Invalid call', $_POST));
        }
    }else{
        header("location: ../../index.php");
    }
