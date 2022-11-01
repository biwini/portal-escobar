<?php
    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
				include ('controller/adminController.php');
				include ('controller/programController.php');
				include ('controller/accessController.php');
				include ('controller/areaController.php');
				$Admin = new admin();
				$Program = new program();
				$Access = new access();
				$Area = new area();

				$Admin->getUser();
				$Program->getProgram();
				$Access->getAccess();
				$Access->getAuditoria();
				$Area->getArea();

				$optionUser = '';
				$optionProgram = '';
				$optionArea = '';
				$tableAccess = '';
				$tableAuditoria = '';

				foreach ($Area->listArea as $key => $value) {
					$optionArea .= '<option value=\''.$value['IdArea'].'\'>'.$value['Name'].'</option>';
				}
				
				foreach ($Admin->admin as $key => $value) {
	                	$optionUser .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].' | '.$value['Dni'].'</option>';
	                
	            }
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
							    <h3 class="text-center">ACCESOS</h3>
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
									<div class="modal" id="modal_change_access">
						                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
						                    <div class="modal-content">
						                        <div class="modal-header">
						                        	<h3 class="modal-title">Cargar datos de acceso</h3>
						                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
						                        </div>
						                        <div class="modal-body">
						                            <div class="form-group">
														<label for="mod_program_access">Programa</label>
														<select name="mod_program_access" id="mod_program_access" class="form-control required">
															<option value="0">Seleccione un Programa</option>
														 	<?php echo $optionProgram; ?> 
														</select>
													</div>
													<div class="form-group">
														<label>Al programa : </label>
														<select name="program_access" id="program_access" class="form-control">
															<option value="0">Seleccione un Programa</option>
														 	<?php echo $optionProgram; ?> 
														</select>
													</div>
													<div class="form-group">
														<label>Con Permisos de: </label>
														<select name="permissions_access" id="permissions_access" class="form-control">
														 	<option value="1">ADMINISTRADOR</option>
														 	<option value="2" selected>USUARIO</option>
														</select>
													</div>
						                        </div>
						                        <div class="modal-footer">
						                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
						                        	<input type="button" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_access'>
						                        </div>
						                    </div>
						                </div>
						            </div>
						        </div>
				            </section>
				        </div>
				    </main>
				    <section>
		            	<form id="form_access" name="form_access">
							<div class="modal fade" id="modal_charge_program">
				                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
				                    <div class="modal-content">
				                        <div class="modal-header">
				                        	<h3 class="modal-title">Cargar Programa</h3>
				                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
				                        </div>
				                        <div class="modal-body">
				                            <div class="form-group">
				                                <label>Definir accesos a : </label>
												<select class="form-control required" name="user_access" id="user_access">
													<option value="0">Seleccione un Usuario</option>
													<?php echo $optionUser; ?>
												</select>
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
				                            <input type="submit" class="btn btn-primary" name="cargarAcceso" id="cargarAcceso" value="Cargar">
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
							ListArea = <?php echo json_encode($Area->listArea); ?>;
							ListUser = <?php echo json_encode($Admin->admin); ?>;
							ListAccess = <?php echo json_encode($Access->access); ?>;
							ListAuditoria = <?php echo json_encode($Access->auditoria); ?>;
							displayAccessTable();
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