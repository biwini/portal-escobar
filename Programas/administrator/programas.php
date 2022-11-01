<?php
	require_once('controller/programController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){

				$Program = new program();

				$ProgramList = $Program->getPrograms();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'programas';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Programas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
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
                <h1 class="page-title"><span class="icon-embed2"></span> PROGRAMAS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3">
                                <label for="filter_secretary">SECRETARIA:</label>
                                <select class="form-control" id="filter_secretary">
                                    <option value="" selected>TODAS LAS SECRETARIAS</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_dependence">DEPENDENCIA:</label>
                                <select class="form-control" id="filter_dependence">
                                    <option value="" selected>TODAS LAS DEPENDENCIAS</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_state">ESTADO:</label>
                                <select class="form-control" id="filter_state">
                                    <option value="" selected>TODAS LOS ESTADOS</option>
                                    <option value="1">HABILITADO</option>
                                    <option value="0">DESHABILITADO</option>
                                </select>
                            </div>
                            <!-- <div class="col-md-6" id="filter_date_custom" style="display: none;">
                                <div class="col-md-6">
                                    <label>DESDE:</label>
                                    <input type="date" id="filter_date_since" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>HASTA:</label>
                                    <input type="date" id="filter_date_until" class="form-control">
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_programs, 'Programas.xls', 'programas');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_program" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo programa </span>
                                </a>
                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_programs" class="table table-striped" name="tb_programs" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PROGRAMA</th>
                                            <th>SECRETARIA</th>
                                            <th>DEPENDENCIA</th>
                                            <th>URL</th>
                                            <th>ESTADO</th>
											<th>USUARIOS</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($ProgramList as $key => $value) {
											$state = ($value['State'] == 1) ? 'HABILITADO' : 'DESHABILITADO';
											
											echo '<tr>
												<td>'.$value['Program'].'</td>
												<td>'.$value['Secretary'].'</td>
												<td>'.$value['Dependence'].'</td>
												<td>'.$value['Url'].'</td>
												<td>'.$state.'</td>
												<td><a href=\'accesos?view=users&program='.$value['Id'].'\'>VER USUARIOS</a></td>
												<td><button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button></td>
											</tr>';
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
        <form name="form_programa" id="form_programa" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">AGREGAR PROGRAMA</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="oc">PROGRAMA*: </label>
                                <input type="text" class="form-control required" name="programa" id="programa" placeholder="Nombre del programa..." required>
                            </div>
                            <div class="form-group">
                                <label for="secretaria">SECRETARIA*: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            $d = ($value['Id'] == $_SESSION['SECRETARIA']) ? 'selected' : 'disabled';

                                            if(($Admin)){
                                                $d = '';
                                            }

                                            echo '<option value=\''.$value['Id'].'\' '.$d.'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia">DEPENDENCIA*: </label>
                                <select class="form-control" id="dependencia" name="dependencia" required>
                                    <option value="" disabled selected>SELECCIONE LA DEPENDENCIA</option>
									<?php 
										echo ($Admin) ? '' : '<option value=\''.$_SESSION['DEPENDENCIA'].'\' selected>'.$Program->searchDependence($_SESSION['SECRETARIA'], $_SESSION['DEPENDENCIA']).'</option>' ; 
									?>
                                </select>
                                <!-- <input type="text" class="form-control" name="new_dependencia" id="new_dependencia" style="display: none; margin-top: 5px;" placeholder="Dependencia..." required disabled> -->
                            </div>
                            <div class="form-group">
                                <label for="url">URL: </label>
                                <input type="text" class="form-control" name="url" id="url" placeholder="Url del sistema dentro de la intranet...">
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
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script type="text/javascript">
        
        const Programas = <?php echo json_encode($ProgramList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;
        let user;

        $(document).ready(function(){
            DataTable = $('#tb_programs').DataTable();
        });

        function displayTable(){
            
            filterTable = Programas;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(p => p.Secretary == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(p => p.Dependence == filterDependence);
            }
            if(filterState !== undefined){
                filterTable = filterTable.filter(p => p.State == filterState);
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(p){  
                let state = (p.State == filterState) ? 'HABILITADO' : 'DESHABILITADO';

                DataTable.row.add([
                    p.Program,
                    p.Secretary,
                    p.Dependence,
                    p.Url,
                    state,
                    '<a href=\'accesos?view=users&program='+p.Id+'\'>VER USUARIOS</a>',
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                ).draw(false);
            });
            
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar Programa');
            $('#enviar').val('Agregar');

            $('#secretaria').prop('selectedIndex',0);
            $('#dependencia').find('option:not(:first)').remove();
            $('#dependencia').prop('selectedIndex',0);

            $("#form_programa")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');
            console.log(arr[0].innerText)

            document.getElementById('programa').value = arr[0].innerText;
            document.getElementById('url').value = arr[3].innerText;

            $('#estado option').filter(function() {
                return $(this).text() == arr[4].innerText;
            }).prop('selected', true);

            $('#secretaria option').filter(function() {
                return $(this).text() == arr[1].innerText;
            }).prop('selected', true);

            setDependences($('#secretaria option:selected').val());

            $('#dependencia option').filter(function() {
                return $(this).text() == arr[2].innerText;
            }).prop('selected', true);

            $('#title').text('Editar Programa');
            $('#enviar').val('Guardar Cambios');
            $("#new_program").click();
        });

        $(document).on('submit', '#form_programa', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro el programa' : 'Se actualizo el programa');

                        $("#form_programa")[0].reset();

                        mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        p = data.Programa;
                        let state = (p.State == 1) ? 'HABILITADO' : 'DESHABILITADO';
                        DataTable.row.add([
                            p.Program,
                            p.Secretary,
                            p.Dependence,
                            p.Url,
                            state,
                            '<a href=\'accesos?view=users&program='+p.Id+'\'>VER USUARIOS</a>',
                            '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                        ).draw(false);
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Program':
                        swal('El programa ya existe','');
                    break;
                    case 'Invalid State':
                        swal('El estado es invalido','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Dependence':
                        console.log(data)
                        swal('Dependencia invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el programa' : 'No se pudo editar el programa');
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