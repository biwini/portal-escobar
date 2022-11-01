<?php
    require_once('controller/sessionController.php');
    $Session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
        	if($_SESSION['UNREGISTRED']){
        		//Variable para calcular el tiempo limite por session de ser necesario
				$_SESSION['LIMIT_SESSION'] = date('Y-m-d H:i:s', time());

        		include 'controller/userController.php';
        		$User = new usuario();
        		$User->getSecretary();
                $optionSecretaria = '';
                $optionDependencia = '';

                foreach ($User->listSecretary as $key => $value) {
                    $optionSecretaria .= '<option value=\''.$value['IdSecretaria'].'\'>'.$value['Name'].'</option>';
                }
                
?>
			<!DOCTYPE html>
			<html>
			<head>
				<title>Portal Escobar - Registro</title>
				<meta name="theme-color" content="white"/>
			   	<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="Description" content="Portal Escobar">
				<meta name="theme-color" content="#fff"/>
			    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
				<link href="css/bootstrap.min.css" rel="stylesheet">
				<link href="css/login.css" rel="stylesheet">
				<link href="css/bootstrap-theme.min.css" rel="stylesheet">
				<style type="text/css">
					.login-page {
					  max-width: 660px !important;
					}
				</style>
			</head>
			<body style="background-color: lightgrey;">
				<div class="container">
					<div class="row text-center login-page">
						<div class="col-md-12 login-form" style="background-color: white;">
							<form method="POST" id="register_form" name="register_form" autocomplete="off">
								<div>
									<div>
										<img class="img" style=" height: 100px;" src="imagenes/logo-municipalidad-de-escobar.jpg" alt="">	
									</div>
									<div>
										<p class="col-md-12 login-form-font-header">FORMULARIO DE REGISTRO</p>
									</div>
								</div>
								<div class="col-md-12">									
									<div class="col-md-4 login-from-row">
										<label for="alta_nombre">Nombre*</label>
                                        <input type="text" class="form-control required" placeholder="Nombre" id="alta_nombre" name="alta_nombre" required>
									</div>
									<div class="col-md-4 login-from-row">
										<label for="alta_apellido">Apellido*</label>
	                                    <input type="text" class="form-control required" placeholder="Apellido" id="alta_apellido" name="alta_apellido" required>
									</div>
									<!-- <div class="col-md-4 login-from-row">
										<label for="alta_dni">Dni*</label>
                                        <input type="number" class="form-control required" placeholder="DNI" id="alta_dni" name="alta_dni" required>
									</div>		 -->
								</div>
								<div class="col-md-12">
									<div class="col-md-6 login-from-row">
										<label for="alta_email">Email Personal*</label>
                                        <input type="email" class="form-control required" placeholder="Email" id="alta_email" name="alta_email" required>
									</div>
									<div class="col-md-6 login-from-row">
										<label for="alta_telefono">Telefono*</label>
                                        <input type="number" class="form-control required only-number" placeholder="Telefono" id="alta_telefono" name="alta_telefono" required>
									</div>
								</div>
								<div class="col-md-12">
									<div class="col-md-6 login-from-row">
										<label for="alta_secretaria">Secretaria*</label>
                                        <select class="form-control secretaria required" id="alta_secretaria" name="alta_secretaria" required>
                                            <option value="0">Seleccione una secretaria</option>
                                            <?php echo $optionSecretaria; ?>
                                        </select>
									</div>
									<div class="col-md-6 login-from-row">
										<label for="alta_dependencia">Dependencia*</label>
                                        <select class="form-control dependencia required" id="alta_dependencia" name="alta_dependencia" required>
                                            <option value="0">Seleccione una dependencia</option>
                                        </select>
									</div>
								</div>
								<div class="row">
									<div class="col-md-6 login-from-row">
										<a href="functions/cerrarSession.php" class="col-md-6 btn btn-danger form-control col-md-6" title="Cancelar Registro">Cancelar</a>
									</div>
									<div class="col-md-6 login-from-row">
										<input class="btn btn-info form-control col-md-6" type="submit" name="registrar" id="registrar" value="Registrar">
									</div>
								</div>
								
							</form>
							<label id="mensaje"></label>
						</div>
					</div>
				</div>
				<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
				<script src="js/sweetalert.min.js"></script>
				<script type="text/javascript">
					var ListDependencia = [];
			        var ListSecretaria = <?php echo json_encode($User->listSecretary); ?>;
			        $.each(ListSecretaria, function(i,s){
			            ListDependencia.push(s.Dependences);
			        });
			        ListDependencia = ListDependencia[0];
					$('.secretaria').change(function(){
					    emptyDependence();
					    selected = $(this).val();
					    $.each(ListSecretaria, function(i,s){
					        if(s.IdSecretaria == selected){
					            $.each(s.Dependences, function(n,d){
					                $('.dependencia').append('<option value=\''+d.IdDependence+'\'>'+d.Name+'</option>');
					            });
					        }
					    });
					});
					function setDependences(secretaria){
					    $.each(ListSecretaria, function(i,s){
					        if(s.IdSecretaria == secretaria){
					            $.each(s.Dependences, function(n,d){
					                $('.dependencia').append('<option value=\''+d.IdDependence+'\'>'+d.Name+'</option>');
					            });
					        }
					    });
					}
					function emptyDependence(){
					    $('.dependencia').html('<option value=\'0\'>Seleccione una dependencia</option>')
					}
					function validate($array){
					  var valid = true;
					  $array.each(function() {
					    var attr = $(this).attr('disabled');
					    if (typeof attr === typeof undefined && attr === false) {
					      if(($.trim($(this).val()) == "" || $.trim($(this).val()) <= 0) ){
					        valid = false;
					      }
					    }
					  });
					  return valid;
					}
					
					$(document).on('submit', '#register_form', function(e){
					    e.preventDefault();

					    if(validate($(this).find('.required'))){
					        $.ajax({
					            type: "POST",
					            url: "controller/",
					            data: $(this).serialize()+"&pag=Registro"+"&tipo=i",
					            dataType: "html",
					        })
					        .fail(function(data){
					            console.log(data);
					            mensaje('fail','Error Peticion ajax');
					        })
					        .done(function(data){
					            response = JSON.parse(data);
					            console.table(response)
					            switch(response.Status){
					                case 'Success':
					                	location.reload();
					                break;
					                case 'Error':
					                    swal('Lo sentimos', 'No se pudo completar el registro, intente de nuevo mas tarde', 'error');
					                break;
					                case 'Invalid Call':
					                case 'Incomplete Form':
					                    swal('Formulario Incompleto', 'Por favor ingrese todos los datos solicitados en el formulario', 'warning');
					                break;
					            }
					        });
					    }
					});

					var count = 0;
					var show = false;
					$(function(){
					    setInterval(function() {
					        if(count <= 5){
					            isActive();
					        }else if(count >= 6){
						    	if(!show){
					                show = true;
					                swal({
					                  title: "Â¿Sigues ahi?",
					                  text: "",
					                  icon: "warning",
					                  showCancelButton: false,
					                  confirmButtonColor: '#3085d6',
					                  confirmButtonText: '¡Si!',
					                  dangerMode: true
					                })
					                .then((willDelete) => {
					                    if (willDelete) {
					                        count = 0;
					                        $.ajax({
					                            type: "POST",
					                            url: "controller/",
					                            data: 'pag=restart&tipo=s',
					                            dataType: "json",
					                        })
					                        .fail(function(data){
					                            console.log('Restart Failed')
					                            show = false;
					                        })
					                        .done(function(data){
					                            show = false;
					                            console.log('Restarted')
					                        });
					                    }
					                    show = false;
					                });
					            }else{
					                location.reload();
					            }
					        }
					    }, 60000);
					});
					function isActive(){
					    $.ajax({
					        type: "POST",
					        url: "controller/",
					        data: 'pag=check&tipo=s',
					        dataType: "json",
					    })
					    .fail(function(data){
					        console.log('CheckInactive Failed')
					    })
					    .done(function(data){
					        if(data.Status == 'Valid'){
					            count = count + 1;
					        }else{
					            window.location = "login";
					        }
					    });
					}
				</script>
			</body>
			</html>
<?php 
			}else{
				if(isset($_SESSION["PAGINA_PERMITIDA"])){
					$url =$_SESSION["PAGINA_PERMITIDA"];
					header("location:".$url."");
				}
			}
		}else{
			header("location: login.php");
		}
	}else{
		header("location: login.php");
	}
?>