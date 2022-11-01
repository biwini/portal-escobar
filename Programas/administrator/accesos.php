<?php
	require_once('controller/accessController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
                require_once('controller/programController.php');
                require_once('controller/adminController.php');

				$Access = new access();
                $Program = new program();
                $User = new admin();

				$AccessList = $Access->getAccess();
                $ProgramList = $Program->getPrograms();
                $UserList = $User->getUsers();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'accesos';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Accesos</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <div class="message">
        <span></span>
    </div>
    <main class="app-main">
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-unlocked"></span> ACCESOS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3">
                                <label for="filter_program">PROGRAMA:</label>
                                <select class="form-control" id="filter_program">
									<option value="" selected>TODOS LOS PROGRAMAS</option>
									<?php 
                                        foreach ($ProgramList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Program'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_access">PERMISOS:</label>
                                <select class="form-control" id="filter_access">
                                    <option value="" selected>TODOS LOS PERMISOS</option>
                                    <option value="1">ADMINISTRADOR</option>
                                    <option value="2">USUARIO</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_state">ESTADO:</label>
                                <select class="form-control" id="filter_state">
                                    <option value="" selected>TODOS LOS ACCESOS</option>
                                    <option value="0">DESHABILITADO</option>
                                    <option value="1">HABILITADO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_accesos, 'Accesos.xls', 'accesos');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_access" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo acceso </span>
                                </a>
                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_accesos" class="table table-striped" name="tb_accesos" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>USUARIO</th>
                                            <th>PROGRAMA</th>
                                            <th>ESTADO</th>
                                            <th>ACCESO</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($AccessList as $key => $value) {
											$state = ($value['State'] == 1) ? '<span style=\'color: green;\'>HABILITADO</span>' : '<span style=\'color: red;\'>DESHABILITADO</span>';
											$userAccess = ($value['Permiso'] == 1) ? 'ADMINISTRADOR' : 'USUARIO';
											
											echo '<tr>
												<td>'.$value['UserName'].' | '.$value['Legajo'].'</td>
												<td>'.$value['ProgramName'].'</td>
												<td>'.$state.'</td>
												<td>'.$userAccess.'</td>
												<td><button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button>
												
                                            </tr>';
                                            //<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger edit\' value=\''.$value['Id'].'\'></button></td>
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>        
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <section>
        <form name="form_acceso" id="form_acceso" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">AGREGAR ACCESO</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="programa">DEFINIR ACCESOS AL PROGRAMA*: </label>
                                <select class="form-control" id="programa" name="programa" required>
                                    <option value="" disabled selected>SELECCIONE EL PROGRAMA</option>
                                    <?php 
                                        foreach ($ProgramList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Program'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="permiso">PERMISOS*: </label>
                                <select class="form-control" id="permiso" name="permiso" required>
                                    <option value="" disabled selected>SELECCIONE EL PERMISO</option>
									<option value="2">USUARIO</option>
									<option value="1">ADMINISTRADOR</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia">USUARIO*: </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Buscar usuario..." required>
                            </div>
							<div class="form-group">
                                <label for="estado">ESTADO: </label>
                                <select name="estado" id="estado" class="form-control">
                                        <option value="1">HABILITADO</option>
                                        <option value="0">DESHABILITADO</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                            <input type="submit" class="btn btn-primary" style="float: right;" name="Cargar" id="enviar" value="Agregar">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script language="javascript" src="js/autocomplete.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script type="text/javascript">
        
        const Accesos = <?php echo json_encode($AccessList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_accesos').DataTable();

            autocompleteFields(document.getElementById('usuario'), 'Usuarios');
        });

        function displayTable(){
            
            filterTable = Accesos;

            if(filterProgram !== undefined){
                filterTable = filterTable.filter(p => p.IdProgram == filterProgram);
            }

            if(filterState !== undefined){
                filterTable = filterTable.filter(p => p.State == filterState);
            }

            if(filterAccess !== undefined){
                filterTable = filterTable.filter(p => p.Permiso == filterAccess);
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(p){  
                let state = (p.State == 1) ? '<span style=\'color: green;\'>HABILITADO</span>' : '<span style=\'color: red;\'>DESHABILITADO</span>';
                let access = (p.Permiso == 1) ? 'ADMINISTRADOR' : 'USUARIO';

                DataTable.row.add([
                    p.UserName+' | '+p.Legajo,
                    p.ProgramName,
                    state,
                    access,
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                ).draw(false);
            });
            
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar acceso');
            $('#enviar').val('Agregar');

            $("#form_acceso")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');

            $('#usuario').val(arr[0].innerText.split(' | ')[1])

            $('#programa option').filter(function() {
                return $(this).text() == arr[1].innerText;
            }).prop('selected', true);

            $('#estado option').filter(function() {
                return $(this).text() == arr[2].innerText;
            }).prop('selected', true);

            $('#permiso option').filter(function() {
                return $(this).text() == arr[3].innerText;
            }).prop('selected', true);

            $('#title').text('Editar Accesos');
            $('#enviar').val('Guardar Cambios');
            $("#new_access").click();
        });

        $(document).on('submit', '#form_acceso', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag='+document.title+'&tipo='+type+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'i') ? 'Se registro el acceso' : 'Se actualizo el acceso');

                        $("#form_acceso")[0].reset();

                        mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        p = data.Result;
                        let state = (p.State == 1) ? '<span style=\'color: green;\'>HABILITADO</span>' : '<span style=\'color: red;\'>DESHABILITADO</span>';
                        let access = (p.Permiso == 1) ? 'ADMINISTRADOR' : 'USUARIO';
                        DataTable.row.add([
                            p.UserName+' | '+p.Legajo,
                            p.ProgramName,
                            state,
                            access,
                            '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                        ).draw(false);
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Access':
                        swal('El acceso ya existe','');
                    break;
                    case 'Invalid State':
                        swal('El estado es invalido','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el acceso' : 'No se pudo editar el acceso');
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