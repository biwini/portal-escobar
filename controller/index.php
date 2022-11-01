<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
    require_once('sessionController.php');
    $Session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if(!$_SESSION['LOGUEADO']){
        	include ('loginController.php');

        	$Login = new login();
        	if($Login->validateFields('login')){
        		$response = $Login->loginUser();
        		echo json_encode($response);
        	}else{
        		echo json_encode(array('Status' => 'Unknown User'));
        	}
		}else{
            if(isset($_POST['pag']) && isset($_POST['tipo'])){
                switch ($_POST['pag']) {
                    case 'Registro':
                        include ('userController.php');
                        $User = new usuario();
                        if($User->validateFields('insert')){
                            echo json_encode($User->RegisterUser());
                        }else{
                            //echo 'hola';
                            echo json_encode(array('Status' => 'Invalid Call'));
                        }
                    break;
                    case 'check':
                        $result = ($Session->LimitTime()) ? array('Status' => 'Valid') : array('Status' => 'Expired');
                        echo json_encode($result);
                    break;
                    case 'restart':
                        $_SESSION['LIMIT_SESSION'] = date('Y-m-d H:i:s', time());
                        echo json_encode(array('Status' => 'Valid'));
                    break;
                    default:
                        # code...
                        break;
                }
                // if($_POST['pag'] == 'Registro' && $_POST['tipo'] == 'i'){
        
                // }
            }else{
                echo json_encode(array('Status' => 'Invalid Call'));
            }
		}
	}else{
		header("location: ../index.php");
	}
}else{
    header("location: ../index.php");
}
?>