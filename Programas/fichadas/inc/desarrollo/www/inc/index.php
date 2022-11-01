<?php
if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest'){
    if(!isset($_SESSION)){
        require_once ('sessionController.php');
        $Session = new session();
    }
}else{
    header("location: ../../index.php");
}

    if(!isset($_SESSION['LOGUEADO'])){
        header("location: ../../index.php");
    }

    if($_SESSION['LOGUEADO'] && $_SESSION['FICHADAS']){
        if(isset($_REQUEST['pag']) && isset($_REQUEST['tipo'])){
            $response = array('Status' => 'Invalid call');

            switch ($_REQUEST['pag']){
                case 'especialistas':
                    include ('especialistaController.php');
                    $Especialista = new especialista();

                    switch ($_REQUEST['action']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'get':
                            $response = $Especialista->getEspecialistas();
                        break;
                    }
                break;
                case 'horario':
                    require ('horarioController.php');
                    $Horario = new horario();

                    switch ($_REQUEST['action']) { // 'i' = Insertar Programa, 'g' = Obtener Programa, 'd' = Eliminar Liqudiacion
                        case 'get':
                            $response = $Horario->getHorario();
                        break;
                        case 'search':
                            $response = $Horario->searchHorario();
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

?>