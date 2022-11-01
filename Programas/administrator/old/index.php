<?php

// echo '<h1>EN MANTENIMIENTO</h1>';
// exit();

    require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
				include ('controller/adminController.php');
				include ('controller/programController.php');
				include ('controller/accessController.php');
				include ('controller/secretaryController.php');
				include ('controller/localidadController.php');
				include ('controller/compartidaController.php');
				include ('controller/equipoController.php');

				$Admin = new admin();
				$Program = new program();
				$Access = new access();
				$Secretaria = new secretaria();
				$Localidad = new localidad();
				$Compartida = new compartida();
				$Equipo = new equipo();
				$TipoEquipo = new tipoEquipo();

				$Admin->getUser();
				$Program->getProgram();
				$Access->getAccess();
				$Access->getAuditoria();
				$Secretaria->getSecretary();
				$Localidad->getLocation();
				$Compartida->getCompartida();
				$TipoEquipo->getList();

				$optionUser = '';
				$optionProgram = '';
				$optionSecretaria = '';
				$optionLocation = '';
				$tableAccess = '';
				$tableAuditoria = '';
				$TypeEquipmentOption = '';

				foreach ($Localidad->localidad as $key => $value) {
					$optionLocation .= '<option value=\''.$value['Id'].'\'>'.$value['Location'].'</option>';
				}

				foreach ($Secretaria->listSecretary as $key => $value) {
					$optionSecretaria .= '<option data-tokens=\''.$value["Name"].','.$value["IdSecretaria"].'\' value=\''.$value['IdSecretaria'].'\'>'.$value['Name'].'</option>';
				}
				
				foreach ($Admin->admin as $key => $value) {
	                $optionUser .= '<option data-tokens=\''.$value["Name"].','.$value["Legajo"].'\' value=\''.$value['Id'].'\'>'.$value['Name'].' | '.$value['Legajo'].'</option>';
	                
	            }
	            foreach ($Program->program as $key => $value) {
	            	if($value['State'] == 1){
	                	$optionProgram .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
	                }
	            }
	            foreach ($TipoEquipo->ListTypes as $key => $value) {
					$TypeEquipmentOption .= '<option value=\''.$value['Id'].'\'>'.$value['Type'].'</option>';
				}

?>
				<!DOCTYPE html>
				<html>
				<head>
					<title>Admin</title>
					<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
					<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
					<!-- <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>"> -->
					<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"/>
					<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet"/>
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
				            	<div class="card">
                    				<div class="row">
				            <!-- CONTENIDO DE LA PAGINA -->
						            	<div class="message">
									        <span></span>
									    </div>
									    <div style="height: 70px;width: 100%">
									    	<div style="text-align: right; width: 50%;float: right;"  class="pull-right">
									    		<a style="text-align: right;" href="../../functions/cerrarSession.php">Cerrar Session</a>
									    	</div>
									    	<div style="text-align: left; width: 50%;float: left;"  class="pull-left">
									    		<a style="text-align: left;" href="../../index.php">Volver al menu</a>
									    	</div>

									    </div>
									    <div class="containter">
									    	<section id="section_user">
												<div class="row display-content" id="div_usuarios" style="display: block;">
													<div class="col-md-12">
														<h3 class="text-center">USUARIOS</h3>
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_user">Nuevo usuario</button>
														<h4>Lista Usuarios</h4>
														<div class="col-md-12">
															<table class="table table-striped" name="t_user" id="t_user" width="100%">
																<thead>
																	<th>Nombre</th>
																	<th>Apellido</th>
																	<!-- <th>DNI</th> -->
																	<th>Legajo</th>
																	<th>Sexo</th>
																	<th>Telefono</th>
																	<th>Email</th>
																	<th>Secretaria</th>
																	<th>Dependencia</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="form_user" id="form_user" autocomplete="off">
															<div class="modal" id="modal_charge_user">
												                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Nuevo Usuario</h3>
												                            <button class="close pull-right" data-dismiss="modal" id="closemodal1" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                        	<div class="form-group">
												                                <label for="search_user">buscar usuario: </label>
																				<input type="text" class="form-control" id="inp_search_user" placeholder="Nº Legajo">
																				<input type="button" class="btn btn-primary pull-right" id="buscar_usuario" value="Buscar">
												                            </div>
												                            <div class="form-group">
												                                <label for="inpNombre">Nombre: </label>
																				<input type="text" class="form-control required" name="inpNombre" id="inpNombre" placeholder="Nombre" required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="inpApellido">Apellido: </label>
																				<input type="text" class="form-control required" name="inpApellido" id="inpApellido" placeholder="Apellido" required="true">
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="inpDni">DNI: </label>
																				<input type="number" class="form-control required" name="inpDni" id="inpDni" placeholder="DNI" required="true">
												                            </div> -->
												                            <div class="form-group">
												                                <label for="inpSexo">Sexo: </label>
																				<select class="form-control" id="inpSexo" name="inpSexo">
																					<option value="M">MASCULINO</option>
																					<option value="F">FEMENINO</option>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="inpLegajo">Nº Legajo/Dni: </label>
																				<input type="text" class="form-control required" name="inpLegajo" id="inpLegajo" placeholder="Nº Legajo" required="true">
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="inpMonotributo">MONOTRIBUTO: </label>
																				<input type="checkbox" class="monotributo" name="inpMonotributo" id="inpMonotributo" value="1">
												                            </div> -->
												                            <div class="form-group">
												                                <label for="inpTelefono">Telefono: </label>
																				<input type="number" class="form-control only-number" name="inpTelefono" id="inpTelefono" placeholder="Telefono" required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="inpEmail">E-mail: </label>
																				<input type="email" class="form-control required" name="inpEmail" id="inpEmail" placeholder="E-mail" required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="selectSecretaria">Secretaria: </label>
																				<select class="form-control secretaria selectpicker" id="selectSecretaria" name="selectSecretaria" data-live-search="true" required>
												                                	<option data-tokens="" value="0">SELECCIONE UNA SECRETARIA</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="selectDependencia">Dependencia: </label>
																				<select class="form-control dependencia selectpicker" id="selectDependencia" name="selectDependencia" data-live-search="true" required="true">
																					<option data-tokens="" value="0">SELECCIONE UNA DEPENDENCIA</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="inpContrasenia">Contraseña: </label>
																				<input type="password" class="form-control required" name="inpContrasenia" id="inpContrasenia" placeholder="Contraseña" autocomplete="current-password" required="true">
												                            </div> -->
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="Cargar" id="cargar_usuario" value="Cargar">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
														<form name="form_change_user" id="form_change_user" autocomplete="off">
															<div class="modal" id="modal_change_usuario">
												                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Modificar datos Usuario</h3>
												                            <button class="close pull-right" data-dismiss="modal" id="closemodal2" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label for="modNombre">NOMBRE:</label>
												                                <input type="text" id="modNombre" name="modNombre" placeholder="Nombre Usuario" class="form-control required" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="modApellido">APELLIDO:</label>
												                                <input type="text" id="modApellido" name="modApellido" placeholder="Apellido Usuario" class="form-control required" required>
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="modDni">DNI:</label>
												                                <input type="number" id="modDni" name="modDni" placeholder="Dni Usuario" class="form-control required" required>
												                            </div> -->
												                            <div class="form-group">
												                                <label for="modSexo">Sexo: </label>
																				<select class="form-control" id="modSexo" name="modSexo">
																					<option value="M">MASCULINO</option>
																					<option value="F">FEMENINO</option>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="modLegajo">Nº Legajo/Dni: </label>
																				<input type="text" class="form-control required" name="modLegajo" id="modLegajo" placeholder="Nº Legajo" required="true">
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="modMonotributo">MONOTRIBUTO: </label>
																				<input type="checkbox" class="monotributo" name="modMonotributo" id="modMonotributo" value="1">
												                            </div> -->
												                            <div class="form-group">
												                                <label for="modTelefono">Telefono: </label>
																				<input type="number" class="form-control only-number" name="modTelefono" id="modTelefono" placeholder="Telefono" required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="modEmail">E-mail: </label>
																				<input type="email" class="form-control required" name="modEmail" id="modEmail" placeholder="E-mail" required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="modSecretaria">Secretaria: </label>
																				<select class="form-control secretaria selectpicker" id="modSecretaria" name="modSecretaria" data-live-search="true" required>
												                                	<option data-tokens="" value="0">SELECCIONE UNA SECRETARIA</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="modDependencia">Dependencia: </label>
																				<select class="form-control dependencia selectpicker" id="modDependencia" name="modDependencia" data-live-search="true" required="true">
																					<option data-tokens="" value="0">SELECCIONE UNA DEPENDENCIA</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                            <!-- <div class="form-group">
												                                <label for="modContrasenia">Contraseña:</label>
												                                <input type="password" id="modContrasenia" name="modContrasenia" placeholder="Contraseña Usuario" autocomplete="current-password" class="form-control">
												                            </div> -->
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_user'>
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
													</div>
												</div>
											</section>
											<section id="section_programas">
												<div class="row display-content" id="div_programas" style="display: none">
													<div class="col-md-12">
														<h3 class="text-center">PROGRAMAS</h3>
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_program">Cargar programa</button>
														<h4>Lista Programas</h4>
														<div class="col-md-12">
															<table class="table table-striped" name="t_program" id="t_program" width="100%">
																<thead>
																	<th>Programa</th>
																	<th>Secretaria</th>
																	<th>Dependencia</th>
																	<th>Url</th>
																	<th>Estado</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="change_program" id="change_program" method="POST">
															<div class="modal" id="modal_change_program">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Modificar Nombre Programa</h3>
												                            <button class="close pull-right" data-dismiss="modal" id="closemodal3" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label for="modProgram">Nombre:</label>
												                                <input type="text" id="modProgram" name="modProgram" placeholder="Nombre Programa" class="form-control" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="modProgramUrl">Direccion Url:</label>
												                                <input type="text" id="modProgramUrl" name="modProgramUrl" placeholder="URL" class="form-control">
												                            </div>
												                            <div class="form-group">
												                                <label for="modProgramSecretaria">Secretaria: </label>
																				<select class="form-control secretaria required" id="modProgramSecretaria" name="modprogramSecretaria" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="modProgramDependencia">Dependencia: </label>
																				<select class="form-control dependencia required" id="modProgramDependencia" name="modProgramDependencia" required="true">
																					<option value="0">Seleccione una dependencia</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='submit_change_program'>
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
											            <form name="charge_program" id="charge_program" method="POST">
															<div class="modal fade" id="modal_charge_program">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar Programa</h3>
												                            <button class="close pull-right" data-dismiss="modal" id="closemodal4" aria-hidden="true">&times;</button>    
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
												                                <label for="programSecretaria">Secretaria: </label>
																				<select class="form-control secretaria required" id="programSecretaria" name="programSecretaria" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="programDependencia">Dependencia: </label>
																				<select class="form-control dependencia required" id="programDependencia" name="programDependencia" required="true">
																					<option value="0">Seleccione una dependencia</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="cargar_programa" id="cargar_programa" value="Cargar">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
													</div>
												</div>
											</section>
											<section>
												<div class="row display-content" id="div_accesos" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_access">Cargar Acceso</button>
														<h3 class="text-center">ACCESOS</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista Accesos</h4>
															<table class="table table-striped" name="t_access" id="t_access" width="100%">
																<thead>
																	<th>Usuario</th>
																	<th>Programa</th>
																	<th>Estado</th>
																	<th>Permisos</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form id="form_access" name="form_access">
															<div class="modal fade" id="modal_charge_access">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar Acceso</h3>
												                            <button class="close pull-right" data-dismiss="modal" id="closemodal5" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label label="user_access">Definir accesos para : </label>
																				<select class="form-control required" name="user_access" id="user_access">
																					<option value="0">Seleccione un Usuario</option>
																			 		<?php echo $optionUser; ?> 
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label>Definir accesos a : </label>
																				<select class="form-control required" name="program_access" id="program_access">
																					<option value="0">Seleccione un Programa</option>
																			 		<?php echo $optionProgram; ?> 
																				</select>
												                            </div>
												                            <div class="form-group">
																				<label for="permissions_access">Permisos</label>
																				<select name="permissions_access" id="permissions_access" class="form-control required">
																					<option value="0">Seleccione el permiso</option>
																				 	<option value="1">ADMINISTRADOR</option>
																				 	<option value="2">USUARIO</option>
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
														<div class="modal" id="modal_change_access">
											                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
											                    <div class="modal-content">
											                        <div class="modal-header">
											                        	<h3 class="modal-title">Modificar datos de acceso</h3>
											                            <button class="close pull-right" data-dismiss="modal" id="closemodal6" aria-hidden="true">&times;</button>    
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
																			<label for="mod_permissions_access">Permisos</label>
																			<select name="mod_permissions_access" id="mod_permissions_access" class="form-control required">
																				<option value="0">Seleccione el permiso</option>
																			 	<option value="1">ADMINISTRADOR</option>
																			 	<option value="2">USUARIO</option>
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
												</div>
											</section>
											<section>
												<div class="row display-content" id="div_localidades" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_location">Cargar Localidad</button>
														<h3 class="text-center">LOCALIDADES</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista Localidades</h4>
															<table class="table table-striped" name="t_location" id="t_location" width="100%">
																<thead>
																	<th>Localidad</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form id="form_localidad" name="form_localidad">
															<div class="modal fade" id="modal_localidad">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar Localidad</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label>Localidad:</label>
																				<input type="text" class="form-control required" name="localidad" id="localidad" placeholder="Localidad" required>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" name="cargarLocalidad" id="cargarLocalidad" value="Cargar">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
												        <form id="form_mod_localidad" name="form_mod_localidad">
															<div class="modal" id="modal_change_localidad">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Modificar Localidad</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label>Localidad: </label>
																				<input type="text" class="form-control required" name="modLocalidad" id="modLocalidad" placeholder="Localidad" required>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_localidad'>
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
													</div>
												</div>
											</section>
											<section id="auditorias">
												<div id="resultado"></div>
												<div class="row display-content" id="div_auditorias" style="display: none">
													<div class="col-md-12">
														<h3 class="text-center">AUDITORIAS</h3>
														<div class="col-md-12">
															<table name="t_auditoria" id="t_auditoria" class="table table-striped" width="100%">
																<thead>
																	<th>Usuario</th>
																	<th>Legajo</th>
																	<th>Accesos</th>
																	<th>IP Computadora</th>
																	<th>Fecha</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
													</div>
												</div>
												<div id="resultado"></div>
											</section>
											<section>
												<div class="row display-content" id="div_dependencias" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_dependencia">Cargar dependencia</button>
														<h3 class="text-center">DEPENDENCIAS</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista de dependencias</h4>
															<table class="table table-striped" name="t_dependencia" id="t_dependencia" width="100%">
																<thead>
																	<th>Dependencia</th>
																	<th>Secretaria</th>
																	<th>Localidad</th>
																	<th>Direccion</th>
																	<th>Estado</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="form_dependencia" id="form_dependencia">
															<div class="modal" id="modal_charge_dependencia">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar dependencia</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                        	<div class="form-group">
												                                <label for="dependence_secretary">Secretaria:</label>
																				<select class="form-control secretaria required" id="dependence_secretary" name="dependence_secretary" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label>Dependencia:</label>
																				<input type="text" class="form-control" name="dependencia" id="dependencia" required="true">
												                            </div>
												                            <div class="form-group">
												                            	<label for="ubicacion">Localidad:</label>
												                            	<select id="ubicacion" name="ubicacion" class="form-control required localidad" required>
												                            		<option value="0">Seleccione una localidad</option>
												                                	<?php 
													                                	echo $optionLocation;
													                                ?>
												                                </select>
												                            </div>
												                            <div class="form-group">
												                            	<label for="direccion">Direccion:</label>
												                                <input type="text" class="form-control" name="direccion" id="direccion">
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="submit_dependencia" id="submit_dependencia" value="Registrar Dependencia">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
												        <form name="form_change_dependencia" id="form_change_dependencia">
															<div class="modal" id="modal_change_dependencia">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Modificar Dependencia</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                        	<div class="form-group">
												                                <label for="new_dependence_secretary">Secretaria:</label>
																				<select class="form-control secretaria required" id="new_dependence_secretary" name="new_dependence_secretary" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="new_dependencia">Dependencia:</label>
												                                <input type="text" id="new_dependencia" name="new_dependencia" placeholder="Nombre Area" class="form-control" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="new_ubicacion">Localidad:</label>
												                                <select id="new_ubicacion" name="new_ubicacion" class="form-control required localidad" required>
												                                	<option value="0">Seleccione una localidad</option>
												                                	<?php 
													                                	echo $optionLocation;
													                                ?>
												                                </select>
												                            </div>
												                            <div class="form-group">
												                                <label for="new_direccion">Direccion:</label>
												                                <input type="text" id="new_direccion" name="new_direccion" placeholder="Ubicacion" class="form-control">
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_dependencia'>
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
													</div>
												</div>
											</section>
											<section>
												<div class="row display-content" id="div_secretarias" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_secretaria">Cargar secretaria</button>
														<h3 class="text-center">SECRETARIA</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista de secretarias</h4>
															<table class="table table-striped" name="t_secretaria" id="t_secretaria" width="100%">
																<thead>
																	<th>Secretaria</th>
																	<th>Dependencias</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="form_secretaria" id="form_secretaria">
															<div class="modal" id="modal_charge_secretaria">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar secretaria</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label>Secretaria:</label>
																				<input type="text" class="form-control" name="secretaria" id="secretaria" placeholder="Secretaria"  required="true">
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="submit_secretaria" id="submit_secretaria" value="Registrar Secretaria">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
												        <form name="form_change_secretaria" id="form_change_secretaria">
														<div class="modal" id="modal_change_secretaria">
											                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
											                    <div class="modal-content">
											                        <div class="modal-header">
											                        	<h3 class="modal-title">Modificar Secretaria</h3>
											                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
											                        </div>
											                        <div class="modal-body">
											                            <div class="form-group">
											                                <label for="new_secretaria">secretaria:</label>
											                                <input type="text" id="new_secretaria" name="new_secretaria" placeholder="Nombre Secretaria" class="form-control" required>
											                            </div>
											                        </div>
											                        <div class="modal-footer">
											                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
											                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='submit_change_secretaria'>
											                        </div>
											                    </div>
											                </div>
											            </div>
											        	</form>
													</div>
												</div>
											</section>
<!-- -------------------------------------------- ABM COMPARTIDAS TENER EN CUANTA PARA DESARROLLAR (ACTUALMENTE SIN IMPLEMENTAR)-------------------------------------------------- -->
											<section>
												<div class="row display-content" id="div_compartidas" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_compartida">Cargar Compartida</button>
														<h3 class="text-center">COMPARTIDAS</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista de compartidas</h4>
															<table class="table table-striped" name="t_compartida" id="t_compartida" width="100%">
																<thead>
																	<th>Compartida</th>
																	<th>Secretaria</th>
																	<th>Dependencia</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="form_compartida" id="form_compartida">
															<div class="modal" id="modal_charge_compartida">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar Compartida</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label for="compartida">Compartida:</label>
																				<input type="text" class="form-control" name="compartida" id="compartida" placeholder="Nombre Compartida"  required="true">
												                            </div>
												                            <div class="form-group">
												                                <label for="compatidaSecretaria">Secretaria: </label>
																				<select class="form-control secretaria required" id="compatidaSecretaria" name="compatidaSecretaria" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="compartidaDependencia">Dependencia: </label>
																				<select class="form-control dependencia required" id="compartidaDependencia" name="compartidaDependencia" required="true">
																					<option value="0">Seleccione una dependencia</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="submit_compartida" id="submit_compartida" value="Registrar Compartida">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
												        <form name="form_change_compartida" id="form_change_compartida">
															<div class="modal" id="modal_change_compartida">
												                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Modificar Compartida</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label for="new_compartida">secretaria:</label>
												                                <input type="text" id="new_compartida" name="new_compartida" placeholder="Nombre Compartida" class="form-control" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="new_compatidaSecretaria">Secretaria: </label>
																				<select class="form-control secretaria required" id="new_compatidaSecretaria" name="new_compatidaSecretaria" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="new_compartidaDependencia">Dependencia: </label>
																				<select class="form-control dependencia required" id="new_compartidaDependencia" name="new_compartidaDependencia" required="true">
																					<option value="0">Seleccione una dependencia</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" value="Modificar" class="btn btn-primary btn-md pull-right" id='change_secretaria'>
												                        </div>
												                    </div>
												                </div>
												            </div>
											        	</form>
													</div>
												</div>
											</section>
											<!--  ABM DE EQUIPOS -->
											<section>
												<div class="row display-content" id="div_equipos" style="display: none">
													<div class="col-md-12">
														<button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_equipo">Cargar Equipo</button>
														<h3 class="text-center">EQUIPOS</h3>
														<div class="col-md-12" style="float: right;">
															<h4>Lista de equipos</h4>
															<table class="table table-striped" name="t_equipo" id="t_equipo" width="100%">
																<thead>
																	<th>Tipo</th>
																	<th>Nº Patrimonio</th>
																	<th>Nº Interno</th>
																	<th>Marca</th>
																	<th>Modelo</th>
																	<th>Dependencia</th>
																	<th>Usuario Asignado</th>
																	<th>Acciones</th>
																</thead>
																<tbody></tbody>
															</table>
														</div>
														<form name="form_equipo" id="form_equipo">
															<div class="modal" id="modal_charge_equipo">
												                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
												                    <div class="modal-content">
												                        <div class="modal-header">
												                        	<h3 class="modal-title">Cargar Equipo</h3>
												                            <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
												                        </div>
												                        <div class="modal-body">
												                            <div class="form-group">
												                                <label for="tipo_equipo">Tipo:</label>
																				<select class="form-control required" id="tipo_equipo" name="tipo_equipo" required>
																					<option value="">TIPO DE EQUIPO</option>
																					<?php echo $TypeEquipmentOption; ?> 
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="patrimonio">Nº Patrimonio:</label>
												                                <input type="number" class="form-control required" name="patrimonio" id="patrimonio" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="interno">Nº Interno:</label>
												                                <input type="number" class="form-control required only-number" name="interno" id="interno" required>
												                            </div>
												                            <div class="form-group">
												                                <label for="marca">Marca:</label>
												                                <input type="text" class="form-control" name="marca" id="marca">
												                            </div>
												                            <div class="form-group">
												                                <label for="modelo">Modelo:</label>
												                                <input type="text" class="form-control" name="modelo" id="modelo">
												                            </div>
												                            <div class="form-group">
												                                <label for="usuario_asignado">Usuario Asignado(legajo):</label>
												                                <select class="form-control selectpicker" name="usuario_asignado" id="usuario_asignado" data-live-search="true">
												                                	<option data-tokens="sin asignar" value="SIN ASIGNAR">SIN ASIGNAR</option>
												                                	<?php echo $optionUser; ?> 
												                                </select>
												                            </div>
												                            <div class="form-group">
												                                <label for="equipoSecretaria">Secretaria: </label>
																				<select class="form-control secretaria required" id="equipoSecretaria" name="equipoSecretaria" required>
																					<option value="0">Seleccione una secretaria</option>
																					<?php echo $optionSecretaria; ?>
																				</select>
												                            </div>
												                            <div class="form-group">
												                                <label for="equipo_dependencia">Dependencia: </label>
																				<select class="form-control dependencia required" id="equipo_dependencia" name="equipo_dependencia" required="true">
																					<option value="0">Seleccione una dependencia</option>
																					<?php //echo $optionArea; ?>
																				</select>
												                            </div>
												                        </div>
												                        <div class="modal-footer">
												                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
												                            <input type="submit" class="btn btn-primary" style="float: right;" name="submit_equipo" id="submit_equipo" value="Registrar Equipo">
												                        </div>
												                    </div>
												                </div>
												            </div>
												        </form>
												        
													</div>
												</div>
											</section>
										</div>
									</div>
								</div>
				            </section>
				        </div>
				    </main>
				    <<!-- script language="javascript" src="js/jquery-3.2.1.min.js"></script> -->
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
					<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

					<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
					<!-- <script language="javascript" src="js/jquery-3.2.1.min.js"></script> -->
					<!-- <script language="javascript" src="js/bootstrap.min.js"></script> -->
					<script language="javascript" src="js/jquery.dataTables.min.js"></script>
    				<script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    				<script language="javascript" src="js/sweetalert.min.js"></script>
					<script language="javascript" src="js/main.js"></script>
					<script language="javascript" src="js/access.js"></script>
					<script language="javascript" src="js/program.js"></script>
					<script language="javascript" src="js/secretary.js"></script>
					<script language="javascript" src="js/user.js"></script>
					<script language="javascript" src="js/location.js"></script>
					<script language="javascript" src="js/compartida.js"></script>
					<script language="javascript" src="js/equipo.js"></script>
					<script type="text/javascript">
						$(document).ready(function(){
							// setInterval(function(){
							// 	getUser();
							// },10000);
							ListProgram = <?php echo json_encode($Program->program); ?>;
							ListSecretaria = <?php echo json_encode($Secretaria->listSecretary); ?>;
							ListUser = <?php echo json_encode($Admin->admin); ?>;
							ListAccess = <?php echo json_encode($Access->access); ?>;
							ListAuditoria = <?php echo json_encode($Access->auditoria); ?>;
							ListCompartida = <?php echo json_encode($Compartida->ListCompartidas); ?>;
							ListLocalidad = <?php echo json_encode($Localidad->localidad); ?>;
							ListEquipo = <?php echo json_encode($Equipo->getEquipo()); ?>;

							$.each(ListSecretaria, function(i,s){
								$.each(s.Dependences, function(k,d){
									ListDependencia.push(d);
								});
						    });
						    // console.log(ListDependencia)
						    // ListDependencia = ListDependencia[0];
							displayProgramTable();
							displaySecretaryTable();
							displayUserTable();
							displayAccessTable();
							displayAuditoriaTable();
							displayLocationTable();
							displayCompartidaTable();
							displayEquipoTable();
						});

						$(document).on('click', '#buscar_usuario', function(e){
							let search = $('#inp_search_user').val();

							$.ajax({
			                    type: "POST",
			                    url: "controller/",
			                    data: $(this).serialize()+"&search="+search+"&pag="+document.title+"&tipo=s",
			                    dataType: "html",
			                })
			                .fail(function(data){
			                    mensaje('fail','Error Peticion ajax');
			                })
			                .done(function(data){
			                    response = JSON.parse(data);
			                    switch(response.Status){
			                        case 'Success':
			                        	$('#inpNombre').val(response.Name);
			                        	$('#inpApellido').val(response.Surname);
			                        	// $('#inpDni').val(response.Dni);
			                        	$('#inpLegajo').val(search)
			                        	mensaje('okey','Usuario encontrado');
			                        break;
			                        case 'Invalid call':
			                            mensaje('fail','Por favor complete todos los datos solicitados');
			                        break;
			                        case 'Unknown User':
			                            mensaje('fail','Numero de legajo invalido');
			                        break;
			                        case 'Error':
			                            mensaje('fail','Ocurrio un error durante la busqueda');
			                        break;
			                    }
			                });
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
