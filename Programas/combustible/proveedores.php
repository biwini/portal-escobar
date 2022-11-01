<?php 
    
    require_once('controller/proveedorController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                // require_once('controller/proveedorController.php');

                // $Remito = new remito();
                $Proveedor = new proveedor();

                $providerList = $Proveedor->getProviders();
                // $RemitoList = $Remito->getRemitosWithAvailableOrders();
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Proveedores</title>
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
                <h1 class="page-title"><span class="icon-user-solid-circle"></span> PROVEEDORES</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add col-md-6" style="text-align: left;">
                                <a href="#formulario" id="new_provider" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo proveedor </span>
                                </a>
                            </div>
                            <div class="btn-add col-md-6">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_proveedores, 'proveedores-ticket-de-combustible.xls', 'proveedores');return false;">Exportar a Excel</a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_proveedores" class="table table-striped" name="tb_proveedores" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PROVEEDOR</th>
                                            <th>EMAIL</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($providerList as $key => $value) {

                                            // $Remtios = '';
                                            // foreach ($value['EmailSent'] as $k => $v) {
                                            //     $Remtios .= '<button type=\'button\' class=\'btn btn-default\' style=\'margin: 0 6px 6px 0;\' value=\''.$v['Remito'].'\'>'.$v['Remito'].'</button>';
                                            // }

                                            echo '<tr>
                                                <td>'.$value['Proveedor'].'</td>
                                                <td>'.$value['Email'].'</td>
                                                <td><button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''.$value['Id'].'\'></button>
                                                <button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button></td>
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
        <form name="form_proveedor" id="form_proveedor" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Agregar Proveedor</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="proveedor">Proveedor: </label>
                                <input type="text" class="form-control required" name="proveedor" id="proveedor" placeholder="Proveedor..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="email">Email: </label>
                                <input type="email" class="form-control required" name="email" id="email" placeholder="Email..." required="true">
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
    <script src="js/export.js"></script>
    <script type="text/javascript">
        // $(window).on('scroll',function(){
        //     if ($(window).scrollTop()) {
        //         $('nav').addClass('affix');
        //     }
        //     else{
        //         $('nav').removeClass('affix'); 
        //     }
        // })
        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_proveedores').DataTable();
        });

        $('')


        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar Proveedor');
            $('#enviar').val('agregar');

            $("#form_proveedor")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');
            console.log(arr[0].innerText)

            document.getElementById('proveedor').value = arr[0].innerText;
            document.getElementById('email').value = arr[1].innerText;

            $('#title').text('Editar Proveedor');
            $('#enviar').val('Guardar Cambios');
            $("#new_provider").click();
        });

        $(document).on('click','.delete', function(e){
            let row = $(this).parents('tr');
            let proveedor = $(this).val();

            swal({
              title: "Dando de baja el Proveedor...",
              text: "¿Seguro dar de baja este proveedor?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url:  url,
                        data: "id="+proveedor+"&pag="+document.title+"&tipo=d",
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','error petición ajax');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case "Success" :
                                mensaje('okey','Se dio de baja el proveedor');

                                 DataTable.row(row).remove().draw();
                            break;
                            default: 
                                mensaje('fail','No se pudo dar de baja el proveedor');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#form_proveedor', function(e){
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
                        mensaje('okey', (type == 'r') ? 'Se registro el proveedor' : 'Se actualizo el proveedor');

                        $("#form_proveedor")[0].reset();

                        if(type == 'r'){
                            location.reload();
                        }else{
                            mensaje('okey', 'Se guardaron los cambios');

                            DataTable.row($tr).remove().draw();
                            DataTable.row.add([data.Proveedor,data.Email,
                                '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+id+'\'></button> '
                                +'<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+id+'\'></button>']
                            ).draw(false);
                        }
                        $("#closemodal").click();
                    break;
                    case 'Invalid Permissions':
                        swal('No posee los permisos necesarios para realizar la acción','','warning');
                    break;
                    case 'Invalid Email':
                        swal('El Email ingresado es invalid','por favor ingrese un email valido','warning');
                    break;
                    case 'Email In Use':
                        swal('El Email ingresado ya esta registrado a otro proveedor','','warning');
                    break;
                    case 'Existing Provider':
                        swal('El nombre del proveedor ingresado ya esta registrado','','warning');
                    break;
                    case 'No Access':
                        swal('No tiene los permisos necesarios para realizar la acción','', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo agregar el proveedor' : 'No se pudo editar el proveedor');
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
        header("location: ../../index.php");
      }
    }
    else{
      header("location: ../../index.php");
    }
  }
?>