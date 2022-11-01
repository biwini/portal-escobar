<?php
    require_once('controller/ordenPagoController.php');
    
    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["OBRAS_PUBLICAS"])){
            require 'controller/tipoObraController.php';
            
            $OrdenPago = new ordenPago();
            $TipoObra = new tipoObra();

            $listOrdenesPago = $OrdenPago->getOrdenesPago();
            $listObras = $TipoObra->getTiposObra();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Obras Publicas - Ordenes de Pago</title>
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
                <h1 class="page-title"><span class="icon-coin-dollar"></span> ORDENES DE PAGO</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_orden_pago, 'Ordenes-Pago.xls', 'ordenes-pago');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_tipo" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva orden de pago </span>
                                </a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_orden_pago" class="table table-striped table-hover" name="tb_orden_pago" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>OBRA</th>
                                            <th>NÚMERO</th>
                                            <th>FECHA</th>
                                            <th>FECHA PAGO</th>
                                            <th>OCEA</th>
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
        <form name="form_orden_pago" id="form_orden_pago" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="title">AGREGAR ORDEN DE PAGO</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="numero">NÚMERO*: </label>
                                <input type="number" class="form-control required" name="numero" id="numero" placeholder="Número..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="obra">OBRA*: </label>
                                <select class="form-control" name="obra" id="obra">
                                    <option value="" selected disabled>SELEECIONE EL TIPO DE OBRA</option>
                                    <?php
                                        foreach ($listObras as $key => $value) {
                                            echo '<option value=\''.$value['idObraTipo'].'\'>'.$value['cNombre'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="fecha">FECHA*: </label>
                                <input type="date" class="form-control required" name="fecha" id="fecha" required="true">
                            </div>
                            <div class="form-group">
                                <label for="fecha_pago">FECHA PAGO*: </label>
                                <input type="date" class="form-control required" name="fecha_pago" id="fecha_pago" required="true">
                            </div>
                            <div class="form-group">
                                <label for="ocea">OCEA*: </label>
                                <select class="form-control" name="ocea" id="ocea" required>
                                    <option value="" disabled selected></option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
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
        
        const OrdenPago = <?php echo json_encode($listOrdenesPago);?>;

        let type = 'r';
        let id = 0;
        let DataTable;
        let $tr;

        $(document).ready(function(){
            DataTable = $('#tb_orden_pago').DataTable({
                "data": OrdenPago,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "idObra"},
                    { "data": "nNro"},
                    { "data": "dFecha"},
                    { "data": "dPagado"},
                    { "data": "cOCEA"},
                    { "data": "acciones",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.idOP+'\'></button>'+
                            '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.idOP+'\'></button> ';
                        }
                    },
                ],
            });
        });

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREGAR ORDEN DE PAGO');
            $('#enviar').val('Agregar');

            $("#form_orden_pago")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let Found = OrdenPago.find(m => m.idOP == id);

            document.getElementById('numero').value = Found.nNumero;
            document.getElementById('fecha').value = Found.dFecha.split(' ')[0];
            document.getElementById('fecha_pago').value = Found.dPagado.split(' ')[0];

            $('#obra option').filter(function() {
              return $(this).val() == Found.idObra;
            }).prop('selected', true);

            $('#ocea option').filter(function() {
              return $(this).val() == Found.cOCEA;
            }).prop('selected', true);

            $('#title').text('EDITAR ORDEN DE PAGO');
            $('#enviar').val('Guardar Cambios');
            $("#new_tipo").click();
        });

        $(document).on('click','.delete', function(e){
            id = $(this).val();
            $tr = $(this).parents('tr');
            swal({
                title: "¡Eliminando orden de pago!",
                text: "¿Seguro que desea Eliminar esta orden de pago?",
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
                        data: 'pag=OrdenPago&tipo=d&id='+id,
                        dataType: "json",
                    })
                    .fail(function(data){
                        swal('Error','Error Peticion ajax','error');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case 'Success' :
                                mensaje('okey','Se elimino la orden de pago');

                                OrdenPago.forEach(function(m, k){
                                    if(m.idOP == id){
                                        OrdenPago.splice(k, 1);
                                    }
                                });
                                DataTable.row($tr).remove().draw();
                                id = 0;
                                $tr = undefined;                                   
                            break;
                            case 'Unknown Orden':
                                swal('No se encontro la orden de pago','');
                            break;
                            default: 
                                mensaje('fail', 'No se pudo eliminar la orden de pago');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#form_orden_pago', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=OrdenPago&tipo='+type+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'r') ? 'Se registro la orden de pago' : 'Se actualizo la orden de pago');

                        $("#form_orden_pago")[0].reset();

                        if(type == 'u'){
                            OrdenPago.forEach(function(m, k){
                                if(m.idOP == data.Response.idOP){
                                    OrdenPago[k] = data.Response;
                                }
                            });
                            DataTable.row($tr).remove().draw();   
                        }else{
                            OrdenPago.push(data.Response);
                        }
                        
                        DataTable.row.add(data.Response).draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Orden Number':
                        swal('El numero de la orden de pago ya existe','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Orden':
                        swal('orden de pago seleccionada invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la orden de pago' : 'No se pudo editar la orden de pago');
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