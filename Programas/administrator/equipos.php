<?php
	require_once('controller/equipoController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){
                require 'controller/localidadController.php';

                $Equipo = new equipo();
                $TipoEquipo = new tipoEquipo();
                $Localidad = new localidad();

                $EquipoList = $Equipo->getEquipos();
                $TypeEquipoList = $TipoEquipo->getTypes();
                $LocalidadList = $Localidad->getLocalidades();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'equipos';

                $optionTypes = '';

                foreach ($TypeEquipoList as $key => $value) {
                    $optionTypes .= '<option value=\''.$value['Id'].'\'>'.$value['Type'].'</option>';
                }
                
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Equipos</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <style>

        .ml5{
            margin-left: 5px;
        }
    </style>
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
                <h1 class="page-title"><span class="icon-display"></span> EQUIPOS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12 form-group">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3">
                                <label for="filter_secretary">SECRETARIA:</label>
                                <select class="form-control filter" id="filter_secretary">
                                    <option value="" selected>TODAS LAS SECRETARIAS</option>
                                    <?php 
                                        echo $optionSecretarias;
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_dependence">DEPENDENCIA:</label>
                                <select class="form-control filter" id="filter_dependence">
                                    <option value="" selected>TODAS LAS DEPENDENCIAS</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_tipoequipo">TIPO DE EQUIPO:</label>
                                <select class="form-control filter" id="filter_tipoequipo">
                                    <option value="" selected>TODOS LOS TIPOS</option>
                                    <?php echo $optionTypes; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_value_pi">PATRIMONIO/INTERNO:</label>
                                <div class="input-group">
                                    <div class="input-group-addon select-input">
                                        <select class="form-control" id="filter_patrimonio_interno">
                                            <option value="PATRIMONIO" selected>PATRIMONIO</option>
                                            <option value="INTERNO">INTERNO</option>
                                        </select>
                                    </div>
                                    <input class="form-control" type="number" id="filter_value_pi">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_equipo, 'Equipos.xls', 'equios');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_equipo" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo equipo </span>
                                </a>
                                <a href="#formulario_baja" id="remove_equipo" class="btn btn-primary" data-toggle="modal" style="display: none;" hidden>
                                    <span class="icon-plus">Baja </span>
                                </a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_equipo" class="table table-striped" name="tb_equipo" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>TIPO</th>
                                            <th>Nº PATRIMONIO</th>
                                            <th>Nº INTERNO</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>DEPENDENCIA</th>
                                            <th>USUARIO ASIGNADO</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($EquipoList as $key => $value) {
                                            if($value['State'] == 1){
                                                echo '<tr>
                                                    <td>'.$value['Type'].'</td>
                                                    <td>'.$value['Patrimony'].'</td>
                                                    <td>'.$value['Intern'].'</td>
                                                    <td>'.$value['Brand'].'</td>
                                                    <td>'.$value['Model'].'</td>
                                                    <td>'.$value['Dependence'].'</td>
                                                    <td>'.$value['User'].'</td>
                                                    <td><button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button>
                                                    <button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete ml5\' value=\''.$value['Id'].'\'></button></td>
                                                </tr>';
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>        
                        </div>
                    </div>
                </div>
                <div class="card">
                    
                    <div class="row">
                        <div class="col-md-12">
                        <h1 class="page-title" style="float: left;"><span class=""></span> BAJAS</h1>
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_equipos_baja, 'Bajas_equipo.xls', 'bajas_equipo');return false;">Exportar a Excel</a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_equipos_baja" class="table table-striped" name="tb_equipos_baja" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>TIPO</th>
                                            <th>Nº PATRIMONIO</th>
                                            <th>Nº INTERNO</th>
                                            <th>MARCA</th>
                                            <th>MODELO</th>
                                            <th>DEPENDENCIA</th>
                                            <th>MOTIVO BAJA</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($EquipoList as $key => $value) {
                                            if($value['State'] == 0){
                                                echo '<tr>
                                                    <td>'.$value['Type'].'</td>
                                                    <td>'.$value['Patrimony'].'</td>
                                                    <td>'.$value['Intern'].'</td>
                                                    <td>'.$value['Brand'].'</td>
                                                    <td>'.$value['Model'].'</td>
                                                    <td>'.$value['Dependence'].'</td>
                                                    <td>'.$value['MotivoBaja'].'</td>
                                                </tr>';
                                            }
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
        <form name="form_baja_equipo" id="form_baja_equipo" autocomplete="off">
            <div class="modal" id="formulario_baja">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" style="float: left;">DAR DE BAJA</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal_baja" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="motivo_baja">MOTIVO DE BAJA*: </label>
                                <input type="textbox" class="form-control required" name="motivo_baja" id="motivo_baja" placeholder="Motivo de la baja..." required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="" value="Cancelar">
                            <input type="submit" class="btn btn-primary" style="float: right;" id="btn_baja" value="Dar de baja">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <section>
        <form name="form_equipo" id="form_equipo" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="title">AGREGAR EQUIPO</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="tipo_equipo">TIPO*: </label>
                                <select class="form-control" id="tipo_equipo" name="tipo_equipo" required>
                                    <option value="" disabled selected>SELECCIONE EL TIPO DE EQUIPO</option>
                                    <?php 
                                        echo $optionTypes;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="patrimonio">Nº PATRIMONIO*: </label>
                                <input type="text" class="form-control required" name="patrimonio" id="patrimonio" placeholder="Nº de patrimonio..." required>
                            </div>
                            <div class="form-group">
                                <label for="interno">Nº INTERNO*: </label>
                                <input type="text" class="form-control required" name="interno" id="interno" placeholder="Nº interno..." required>
                            </div>
                            <div class="form-group">
                                <label for="marca">MARCA: </label>
                                <input type="text" class="form-control" name="marca" id="marca" placeholder="Marca...">
                            </div>
                            <div class="form-group">
                                <label for="modelo">MODELO: </label>
                                <input type="text" class="form-control" name="modelo" id="modelo" placeholder="Modelo...">
                            </div>
                            <div class="form-group">
                                <label for="usuario">USUARIO ACTUAL*: </label>
                                <input type="text" class="form-control" id="usuario" name="usuario" placeholder="Buscar usuario...">
                            </div>
                            <div class="form-group">
                                <label for="secretaria">SECRETARIA*: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 
                                        echo $optionSecretarias;
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia">DEPENDENCIA*: </label>
                                <select class="form-control" id="dependencia" name="dependencia" required>
                                    <option value="" disabled selected>SELECCIONE LA DEPENDENCIA</option>
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
    <script src="js/autocomplete.js"></script>
    <script type="text/javascript">
        
        const Equipos = <?php echo json_encode($EquipoList);?>;
        // $('#loading').modal({backdrop: 'static', keyboard: false});
        // $('#loading').modal('hide');
        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable, DataTableBajas;
        let filterTypePatInt, filterValPatInt;

        $(document).ready(function(){
            DataTable = $('#tb_equipo').DataTable();
            DataTableBajas = $('#tb_equipos_baja').DataTable();
            
            autocompleteFields(document.getElementById('usuario'), 'Usuarios');
        });

        $('#filter_value_pi').keyup(function(){
            filterValPatInt = $(this).val();
            filterTypePatInt = $('#filter_patrimonio_interno option:selected').val();

            if(filterValPatInt == ''){
                filterValPatInt = undefined;
            }

            displayTable();
        });

        function reDrawTable(p){ 
            if(p.State == 0){
                DataTableBajas.row.add([
                    p.Type,
                    p.Patrimony,
                    p.Intern,
                    p.Brand,
                    p.Model,
                    p.Dependence,
                    p.MotivoBaja]
                ).draw(false);
            }else{
                DataTable.row.add([
                    p.Type,
                    p.Patrimony,
                    p.Intern,
                    p.Brand,
                    p.Model,
                    p.Dependence,
                    p.User,
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>'+
                    '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete ml5\' value=\''+p.Id+'\'></button></td>']
                ).draw();
            }
            
        }

        async function displayTable(){
            await showmodal();

            filterTable = Equipos;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(p => p.Secretary == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(p => p.Dependence == filterDependence);
            }
            if(filterTypeEquipo !== undefined){
                filterTable = filterTable.filter(p => p.IdType == filterTypeEquipo);
            }
            if(filterValPatInt !== undefined){ 
                if(filterTypePatInt == 'PATRIMONIO'){
                    filterTable = filterTable.filter(p => (p.Patrimony.includes(filterValPatInt)));
                }else{
                    filterTable = filterTable.filter(p => (p.Intern.includes(filterValPatInt)));
                }
            }

            DataTable.rows().remove().draw();
            DataTableBajas.rows().remove().draw();
            filterTable.forEach(function(p){    
                reDrawTable(p)
            });
            $('#loading').modal('hide');
        }
        
        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREGAR EQUIPO');
            $('#enviar').val('Agregar');

            $('#secretaria').prop('selectedIndex',0);
            $('#dependencia').find('option:not(:first)').remove();
            $('#dependencia').prop('selectedIndex',0);

            $("#form_equipo")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');
            element = Equipos.find(p => p.Id == id);

            document.getElementById('patrimonio').value = arr[1].innerText;
            document.getElementById('interno').value = arr[2].innerText;
            document.getElementById('marca').value = arr[3].innerText;
            document.getElementById('modelo').value = arr[4].innerText;
            document.getElementById('usuario').value = (arr[6].innerText == 'SIN ASIGNAR') ? '' : arr[6].innerText;

            $('#tipo_equipo option').filter(function() {
                return $(this).text() == arr[0].innerText;
            }).prop('selected', true);

            $('#secretaria option').filter(function() {
                return $(this).text() == element.Secretary;
            }).prop('selected', true);

            setDependences($('#secretaria option:selected').val());

            $('#dependencia option').filter(function() {
                return $(this).text() == arr[5].innerText;
            }).prop('selected', true);

            $('#title').text('EDITAR EQUIPO');
            $('#enviar').val('Guardar Cambios');
            $("#new_equipo").click();
        });

        $(document).on('click','.delete', function(e){
            id = $(this).val();
            $tr = $(this).parents('tr');
            
            $("#remove_equipo").click();
        });

        $(document).on('submit', '#form_baja_equipo', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag='+document.title+'&tipo=d'+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', 'Se dio de baja el equipo');

                        $("#form_baja_equipo")[0].reset();

                        DataTable.row($tr).remove().draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Equipo':
                        swal('Equipo Invalido', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el equipo' : 'No se pudo editar el equipo');
                    break;
                }
            });
        });

        $(document).on('submit', '#form_equipo', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro el equipo' : 'Se actualizo el equipo');

                        $("#form_equipo")[0].reset();

                        // mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        p = data.Result;

                        reDrawTable(p)
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Equipo':
                        swal('el equipo ya existe','');
                    break;
                    case 'Invalid State':
                        swal('El estado es invalido','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Existing Patrimony Or Intern':
                        swal('El Nº de patromonio o el Nº de interno ya existe', 'No se realizo la accion', 'warning');
                    break;
                    case 'Existing Patrimony':
                        swal('El Nº de patromonio ya existe', 'No se realizo la accion', 'warning');
                    break;
                    case 'Existing Inter':
                        swal('El Nº de interno ya existe', 'No se realizo la accion', 'warning');
                    break;
                    case 'Unknown Equipo':
                        swal('Equipo Invalido', 'No se realizo la accion', 'warning');
                    break;
                    case 'Unknown User':
                        swal('Usuario Invalido', 'No se realizo la accion', 'warning');
                    break;
                    case 'Unknown Dependence Or Secretary':
                        swal('Dependencia o Secretaria es invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el equipo' : 'No se pudo editar el equipo');
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