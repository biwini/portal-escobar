<?php
	require_once('controller/loginController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if(!$_SESSION['LOGUEADO']){

        	$response = array();

            if(isset($_POST['submit'])){
            	if(!isset($_POST['g-recaptcha-response'])){
					return false;
				}

				$captcha = $_POST["g-recaptcha-response"];
				$fields = array(
				        'secret'    =>  "6LdEsfcUAAAAAPDVNkeS8RV-tMUWrlrDzOp_yR_G",
				        'response'  =>  $captcha,
				        'remoteip'  =>  $_SERVER['REMOTE_ADDR']
				    );

			    $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");
				    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
				    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($fields));
				    $response = json_decode(curl_exec($ch));
				    curl_close($ch);
			
				$array = json_decode(json_encode($response),true);

				if($array['success'] == true){

	            	$Login = new login();

	            	$response = $Login->loginUser();

	            	if($response['Status'] == 'Success'){
	            		header('location: index');
	            	}
	            }else{
	            	$response = array('Status' => 'No se pudo validar el captcha');
	            }

            	// var_dump($response);
            }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Portal escobar</title>
	<meta name="theme-color" content="white"/>
   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="Portal Escobar">
	<meta name="theme-color" content="#fff"/>
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/login.css" rel="stylesheet">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet">
	<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
	<script SameSite="none" src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body style="background-color: lightgrey;">
	<div class="container">
		<div class="row text-center login-page">
			<div class="col-md-12 login-form" style="background-color: white;">
				<form method="POST" id="form-login" name="form-login">
					<div>
						<div>
							<img class="img" style=" height: 100px;" src="imagenes/logo-municipalidad-de-escobar.jpg" alt="">	
						</div>
						<div>
							<p class="col-md-12 login-form-font-header">Iniciar Sesion</p>
						</div>
					</div>
					<div class="row">
						
						<div class="col-md-12 login-from-row">
							<input class="form-control" type="text" autocomplete="current-username" name="usuario" id="usuario" placeholder="Legajo"  required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 login-from-row">
							<input class="form-control" type="password" autocomplete="current-password" name="contrasenia" id="contrasenia" placeholder="Contraseña" required>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="g-recaptcha" id="captcha" data-sitekey="6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI"></div>	
							<!-- 6LdEsfcUAAAAACgsRtFXefHmEJiBLPSVMpSj6r9J -->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12 login-from-row">
							<input class="btn btn-info form-control" type="submit" name="submit" id="iniciarSession" value="Iniciar">
						</div>
					</div>
					
				</form>
				<label id="mensaje" style="color: red;"><?php if(isset($_POST['submit'])){ echo ($response['Status'] == 'Success') ? '' : $response['Status'];} ?></label>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).on('submit','#form-login', function(e) {
		if(grecaptcha.getResponse() == ''){
			e.preventDefault();

			$("#mensaje").html('Por favor complete el captcha');
		}


	});
</script>
</html>
<?php
		}
		else{
			header("location: index");
		}
	}
?>


