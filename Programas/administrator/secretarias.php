<?php
	require_once('controller/secretaryController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){

				$Secretary = new secretaria();

				$Secretaries = $Secretary->getSecretaries();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'secretarias';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Secretarias</title>
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
                <h1 class="page-title"><span class="icon-library"></span> SECRETARIAS</h1>
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
                                        foreach ($Secretaries as $key => $value) {
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
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_secretaries, 'Secretarias.xls', 'secretarias');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_secretary" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva secretaria </span>
                                </a>
                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_secretaries" class="table table-striped" name="tb_secretaries" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>SECRETARIA</th>
                                            <th>DEPENDENCIAS</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach ($Secretaries as $key => $value) {
                                            $dependences = '';
                                            foreach ($value['Dependences'] as $k => $v) {
                                                $dependences .= $v['Name'].'<br>';
                                            }
											echo '<tr>
												<td>'.$value['Name'].'</td>
												<td>'.$dependences.'</td>
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
        <form name="form_secretaria" id="form_secretaria" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">AGREGAR SECRETARIA</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="secretaria_name">SECRETARIA*: </label>
                                <input type="text" class="form-control required" name="secretaria_name" id="secretaria_name" placeholder="Nombre de la secretaria..." required>
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
        
        const Secretarias = <?php echo json_encode($Secretaries);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;
        let user;

        $(document).ready(function(){
            DataTable = $('#tb_secretaries').DataTable();
        });

        function displayTable(){
            
            filterTable = Secretarias;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(p => p.Name == filterSecretary);
            }
            if(filterState !== undefined){
                filterTable = filterTable.filter(p => p.State == filterState);
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(p){  
                dependencias = '';

                p.Dependences.forEach(function(d){  
                    dependencias += d.Name+'<br>';
                });
                
                DataTable.row.add([
                    p.Name,
                    dependencias,
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                ).draw(false);
            });
            
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar secretaria');
            $('#enviar').val('Agregar');

            $('#secretaria_name').val('');

            $("#form_secretaria")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');

            document.getElementById('secretaria_name').value = arr[0].innerText;

            $('#title').text('Editar secretaria');
            $('#enviar').val('Guardar Cambios');
            $("#new_secretary").click();
        });

        $(document).on('submit', '#form_secretaria', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro la secretaria' : 'Se actualizo la secretaria');

                        $("#form_secretaria")[0].reset();

                        mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        p = data.Result;
                        let dependencias = '';

                        p.Dependences.forEach(function(d){  
                            dependencias += d.Name+'<br>';
                        });

                        DataTable.row.add([
                            p.Name,
                            dependencias,
                            '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+p.Id+'\'></button>']
                        ).draw(false);
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Secretary':
                        swal('la secretaria ya existe','');
                    break;
                    case 'Invalid State':
                        swal('El estado es invalido','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la secretaria' : 'No se pudo editar la secretaria');
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