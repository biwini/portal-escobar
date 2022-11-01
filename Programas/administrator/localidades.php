<?php
	require_once('controller/localidadController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){

				$Localidad = new localidad();
                
				$LocalidadList = $Localidad->getLocalidades();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'localidades';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Localidades</title>
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
                <h1 class="page-title"><span class="icon-location"></span> LOCALIDADES</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_localidad, 'Localidades.xls', 'localidades');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_localidad" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva localidad </span>
                                </a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_localidad" class="table table-striped" name="tb_localidad" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>LOCALIDAD</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>LOCALIDAD</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>        
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <section>
        <form name="form_localidad" id="form_localidad" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">AGREGAR LOCALIDAD</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="localidad">LOCALIDAD*: </label>
                                <input type="text" class="form-control required" name="localidad" id="localidad" placeholder="Nombre de la localidad..." required="true">
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
        
        const Localidades = <?php echo json_encode($LocalidadList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;
        let user;
        let filterTypeUser, filterValUser;

        $(document).ready(function(){
            $('#tb_localidad thead th').each( function () {
                var title = $(this).text();
                if(title != 'ACCIONES'){
                    $(this).html(title+ '<div style="width:50%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
                }
                
            } );

            DataTable = $('#tb_localidad').DataTable({
                "data": Localidades,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "Name"},
                    { "data": "Editar",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.Id+'\'></button>';
                        }
                    },
                ],
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
                        $( 'input', this.header() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });
        });

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREAGAR LOCALIDAD');
            $('#enviar').val('Agregar');

            $("#form_localidad")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let Found = Localidades.find(p => p.Id == id);

            document.getElementById('localidad').value = Found.Name;

            $('#title').text('EDITAR LOCALIDAD');
            $('#enviar').val('Guardar Cambios');
            $("#new_localidad").click();
        });

        $(document).on('submit', '#form_localidad', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro la localidad' : 'Se actualizo la localidad');

                        $("#form_localidad")[0].reset();

                        mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        Localidades.forEach(e => {
                            if(e.Id == id){
                                e.Name = data.Result.Name;
                            }
                        });
                        
                        DataTable.row.add(data.Result).draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Location':
                        swal('La localidad ya existe','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la localidad' : 'No se pudo editar la localidad');
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
				//si la localidad no tiene Acceso lo envio devuelta a la pagina principal.
				header("location: ../../index.php");
			}
		}
		else{
			//si la Session no esta iniciada lo envio devuelta a la pagina principal.
			header("location: ../../index.php");
		}
	}
?>