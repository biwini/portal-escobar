<?php
    require_once('controller/fuenteController.php');
    
    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["OBRAS_PUBLICAS"])){
            $Fuente = new fuente();

            $listFuentes = $Fuente->getFuentes();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Obras Publicas</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="img/favicon-196x196.png">
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
                <h1 class="page-title"><span class="icon-newspaper"></span> FUENTES</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_fuente, 'Fuentes.xls', 'fuentes');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_fuente" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva fuente </span>
                                </a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_fuente" class="table table-striped table-hover" name="tb_fuente" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>CÓDIGO</th>
                                            <th>NOMBRE</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
        <form name="form_fuente" id="form_fuente" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="title">AGREGAR FFUENTE</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="codigo">CÓDIGO*: </label>
                                <input type="number" class="form-control required" name="codigo" id="codigo" placeholder="Codigo..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="nombre">NOMBRE*: </label>
                                <input type="text" class="form-control required" name="nombre" id="nombre" placeholder="Nombre..." required="true">
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
        
        const Fuentes = <?php echo json_encode($listFuentes);?>;

        let type = 'r';
        let id = 0;
        let DataTable;
        let $tr;

        $(document).ready(function(){
            DataTable = $('#tb_fuente').DataTable({
                "data": Fuentes,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "cCodigo"},
                    { "data": "cNombre"},
                    { "data": "acciones",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.idFuente+'\'></button>'+
                            '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.idFuente+'\'></button> ';
                        }
                    },
                ],
            });
        });

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREGAR FUENTE');
            $('#enviar').val('Agregar');

            $("#form_fuente")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let Found = Fuentes.find(m => m.idFuente == id);
            console.log(Found)

            document.getElementById('codigo').value = Found.cCodigo;
            document.getElementById('nombre').value = Found.cNombre;

            $('#title').text('EDITAR FUENTE');
            $('#enviar').val('Guardar Cambios');
            $("#new_fuente").click();
        });

        $(document).on('click','.delete', function(e){
            id = $(this).val();
            $tr = $(this).parents('tr');
            swal({
                title: "¡Eliminando fuente!",
                text: "¿Seguro que desea Eliminar esta fuente?",
                icon: "warning",
                buttons: {
                cancel: "No",
                Si: true,
                },
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: 'pag=Fuente&tipo=d&id='+id,
                        dataType: "json",
                    })
                    .fail(function(data){
                        swal('Error','Error Peticion ajax','error');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case 'Success' :
                                mensaje('okey','Se elimino la fuente');

                                Fuentes.forEach(function(m, k){
                                    if(m.idFuente == id){
                                        Fuentes.splice(k, 1);
                                    }
                                });
                                id = 0;
                                $tr = undefined;
                                DataTable.row($tr).remove().draw();   
                            break;
                            case 'Unknown Fuente':
                                swal('No se encontro la fuente','','');
                            break;
                            case 'Have Obra':
                                swal('No se puede eliminar la fuente','La fuente en cuestion esta relacionada a una imputacion','warning');
                            break;
                            default: 
                                mensaje('fail', 'No se pudo eliminar la fuente');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#form_fuente', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Fuente&tipo='+type+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'r') ? 'Se registro la fuente' : 'Se actualizo la fuente');

                        $("#form_fuente")[0].reset();

                        if(type == 'u'){
                            Fuentes.forEach(function(m, k){
                                if(m.idFuente == data.Response.idFuente){
                                    Fuentes[k] = data.Response;
                                }
                            });
                            DataTable.row($tr).remove().draw();   
                        }else{
                            Fuentes.push(data.Response);
                        }

                        DataTable.row.add(data.Response).draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Fuente Code':
                        swal('El codigo de la fuente ya existe','');
                    break;
                    case 'Existing Fuente Name':
                        swal('El nombre de la fuente ya existe','');
                    break;
                    case 'Existing Fuente Code Or Name':
                        swal('El nombre o codigo de la fuente ya existe','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Fuente':
                        swal('Fuente invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la fuente' : 'No se pudo editar la fuente');
                    break;
                }
            });
        });

    </script>
</body>
</html>
<?php 
        }else{
            //si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
            header("location: ../../index.php");
        }
    }else{
        //si la Session no esta iniciada lo envio devuelta a la pagina principal.
        header("location: ../../index.php");
    }
?>