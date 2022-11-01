<?php
if(isset($_REQUEST['page']) && isset($_REQUEST['action'])){
    
    $response = array('Status' => 'Invalid call');
    switch ($_REQUEST['page']){
        case 'Reporte':
            require ('reportController.php');
            $report = new Report();
            $secretary = new Secretary();
            switch ($_REQUEST['action']) {
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
            }
        break;
    }
    echo json_encode($response);
}
else{
    echo json_encode(array('Status' => 'Invalid call'));
}
?>