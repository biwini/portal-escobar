<?php
	require_once('controller/dependenceController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
                require 'controller/localidadController.php';

                $Dependence = new dependencia();
                $Localidad = new localidad();

                $DependenceList = $Dependence->getDependences();
                $LocalidadList = $Localidad->getLocalidades();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'dependencias';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Dependencias</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
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
                <h1 class="page-title"><span class="icon-office"></span> DEPENDENCIAS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3">
                                <label for="filter_secretary">SECRETARIA:</label>
                                <select class="form-control filter" id="filter_secretary">
                                    <option value="" selected>TODAS LAS SECRETARIAS</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            echo '<option value=\''.$value['Secretary'].'\'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_localidad">LOCALIDAD:</label>
                                <select class="form-control filter" id="filter_localidad">
                                    <option value="" selected>TODAS LAS LOCALIDADES</option>
                                    <?php 
                                        foreach ($LocalidadList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_dependencia, 'Dependencias.xls', 'dependencias');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_dependence" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva dependencia </span>
                                </a>
                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_dependencia" class="table table-striped" name="tb_dependencia" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>DEPENDENCIA</th>
                                            <th>SECRETARIA</th>
                                            <th>LOCALIDAD</th>
                                            <th>DIRECCIÓN</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($DependenceList as $key => $value) {

											echo '<tr>
												<td>'.$value['Name'].'</td>
                                                <td>'.$value['Secretary'].'</td>
                                                <td>'.$value['Location'].'</td>
                                                <td>'.$value['Address'].'</td>
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
        <form name="form_dependencia" id="form_dependencia" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="title">AGREGAR DEPENDENCIA</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="secretaria">SECRETARIA*: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia_name">DEPENDENCIA*: </label>
                                <input type="text" class="form-control required" name="dependencia_name" id="dependencia_name" placeholder="Nombre de la dependencia..." required>
                            </div>
                            <div class="form-group">
                                <label for="localidad">LOCALIDAD: </label>
                                <select class="form-control" id="localidad" name="localidad" required>
                                    <option value="" disabled selected>SELECCIONE LA LOCALIDAD</option>
                                    <?php 
                                        foreach ($LocalidadList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="direccion">DIRECCIÓN: </label>
                                <input type="text" class="form-control required" name="direccion" id="direccion" placeholder="Direccion...">
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
        
        const Dependencias = <?php echo json_encode($DependenceList);?>;
        // $('#loading').modal({backdrop: 'static', keyboard: false});
        // $('#loading').modal('hide');
        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_dependencia').DataTable();
        });

        async function displayTable(){
            await showmodal();

            filterTable = Dependencias;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(p => p.Secretary == filterSecretary);
            }
            if(filterLocalidad !== undefined){
                filterTable = filterTable.filter(p => p.IdLocalidad == filterLocalidad);
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(p){    
                DataTable.row.add([
                    p.Name,
                    p.Secretary,
                    p.Location,
                    p.Address,
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                ).draw(false);
            });
            $('#loading').modal('hide');
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREGAR DEPENDENCIA');
            $('#enviar').val('Agregar');

            $('#secretaria').prop('selectedIndex',0);

            $("#form_dependencia")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');

            document.getElementById('dependencia_name').value = arr[0].innerText;
            document.getElementById('direccion').value = arr[3].innerText;

            $('#secretaria option').filter(function() {
                return $(this).text() == arr[1].innerText;
            }).prop('selected', true);

            $('#localidad option').filter(function() {
                return $(this).text() == arr[2].innerText;
            }).prop('selected', true);

            $('#title').text('EDITAR DEPENDENCIA');
            $('#enviar').val('Guardar Cambios');
            $("#new_dependence").click();
        });

        $(document).on('submit', '#form_dependencia', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro la dependencia' : 'Se actualizo la dependencia');

                        $("#form_dependencia")[0].reset();

                        // mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        p = data.Result;

                        DataTable.row.add([
                            p.Name,
                            p.Secretary,
                            p.Location,
                            p.Address,
                            '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                        ).draw(false);
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Dependence':
                        swal('la dependencia ya existe','');
                    break;
                    case 'Invalid State':
                        swal('El estado es invalido','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Dependence':
                        swal('Dependencia invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la dependencia' : 'No se pudo editar la dependencia');
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