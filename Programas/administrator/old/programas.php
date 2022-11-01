<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
				include ('controller/programController.php');

				$Program = new program();

				$Program->getProgram();

				$optionProgram = '';

	            foreach ($Program->program as $key => $value) {
	            	if($value['State'] == 1){
	                	$optionProgram .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
	                }
	            }
?>
				<!DOCTYPE html>
				<html>
				<head>
					<title>Admin</title>
					<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
					<link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
					<link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
				    <link rel="stylesheet" type="text/css" href="css/icons.css">
				    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
				    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
					<style type="text/css">
						body{
							width: 100%;
						    padding-right: 15px;
						    padding-left: 15px;
						    margin-right: auto;
						    margin-left: auto;
						}
					</style>
				</head>
				<body>
				    <?php 
				    require('header.php');
				    require('menu.php');
				    ?>
				    <main class="app-main">
				        <div class="container-fluid page">
				            <section>
				            <!-- CONTENIDO DE LA PAGINA -->
				            	<div class="message">
							        <span></span>
							    </div>
						    	<h3 class="text-center">PROGRAMAS</h3>
						    	<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_program">Cargar programa</button>
								<div class="col-md-12">
									<table class="table table-striped" name="t_program" id="t_program" width="100%">
										<thead>
											<th>Programa</th>
											<th>Area</th>
											<th>Url</th>
											<th>Estado</th>
											<th>Acciones</th>
										</thead>
										<tbody></tbody>
									</table>
									
									<div class="modal" id="modal_change_program">
						                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
						                    <div class="modal-content">
						                        <div class="modal-header">
						                        	<h3 class="modal-title">Modificar Nombre Programa</h3>
						                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
						                        </div>
						                        <div class="modal-body">
						                            <div class="form-group">
						                                <label for="new_name">Nombre:</label>
						                                <input type="text" id="new_name" name="new_name" placeholder="Nombre Programa" class="form-control" required>
						                            </div>
						                            <div class="form-group">
						                                <label for="modProgramUrl">Direccion Url:</label>
						                                <input type="text" id="modProgramUrl" name="modProgramUrl" placeholder="URL" class="form-control" required>
						                            </div>
						                            <div class="form-group">
						                                <label for="modProgramArea">Area: </label>
														<select class="form-control required" id="modProgramArea" name="modProgramArea" required="true">
															<option value="0">Seleccione un area</option>
															<?php echo $optionArea; ?>
														</select>
						                            </div>
						                        </div>
						                        <div class="modal-footer">
						                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
						                            <input type="button" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_program'>
						                        </div>
						                    </div>
						                </div>
						            </div>
								</div>
				            </section>
				        </div>
				    </main>
				    <section>
		            	<form name="charge_program" id="charge_program" method="POST">
							<div class="modal fade" id="modal_charge_program">
				                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
				                    <div class="modal-content">
				                        <div class="modal-header">
				                        	<h3 class="modal-title">Cargar Programa</h3>
				                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
				                        </div>
				                        <div class="modal-body">
				                            <div class="form-group">
				                                <label for="inpPrograma">Nombre:</label>
												<input type="text" class="form-control" name="inpPrograma" id="inpPrograma" placeholder="Programa" required>
				                            </div>
				                            <div class="form-group">
				                                <label for="programUrl">Direccion URL:</label>
												<input type="text" class="form-control" name="programUrl" id="programUrl" placeholder="URL">
				                            </div>
				                            <div class="form-group">
				                                <label for="programArea">Area: </label>
											<select class="form-control required" id="programArea" name="programArea" required="true">
												<option value="0">Seleccione un area</option>
												<?php echo $optionArea; ?>
											</select>
				                            </div>
				                        </div>
				                        <div class="modal-footer">
				                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
				                            <input type="button" class="btn btn-primary" style="float: right;" name="cargar_programa" id="cargar_programa" value="Cargar">
				                        </div>
				                    </div>
				                </div>
				            </div>
				        </form>
		            </section>
					
					<script language="javascript" src="js/jquery-3.2.1.min.js"></script>
					<script language="javascript" src="js/bootstrap.min.js"></script>
					<script language="javascript" src="js/jquery.dataTables.min.js"></script>
    				<script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    				<script language="javascript" src="js/sweetalert.min.js"></script>
					<script language="javascript" src="js/main.js"></script>
					<script language="javascript" src="js/program.js"></script>

					<script type="text/javascript">
						$(document).ready(function(){
							// setInterval(function(){
							// 	getUser();
							// },10000);
							ListProgram = <?php echo json_encode($Program->program); ?>;

							displayProgramTable();
						});
					</script>
				</body>
				</html>
<?php 
			}
			else{
				//si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
				header("location: ../../index.php");
			}
		}
		else{
			//si la Session no esta iniciada lo envio devuelta a la pagina principal.
			header("location: ../../index.php");
		}
	}
?>