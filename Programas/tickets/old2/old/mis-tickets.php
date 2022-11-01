<?php
require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
                include 'controller/ticketController.php';

                $Ticket = new ticket();
                $Ticket->allUserTicket = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Mis Tickets</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
    <style type="text/css">

    </style>
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <main class="app-main">
        <div class="container-fluid page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-folder-open"></span> MIS TICKETS</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="content-filter">
                                <ul class="list-inline" id="filter-content">
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="1" name="" id="filter_pendiente" checked><label class="pointer lbl-pendiente" for="filter_pendiente"> Pendientes</label></li>
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="2" name="" id="filter_encurso" checked><label class="pointer lbl-en-proceso" for="filter_encurso"> En Curso</label></li>
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="3" name="" id="filter_finalizado" ><label class="pointer lbl-finalizado" for="filter_finalizado"> Finalizados</label></li>
                                    <li class="list-inline-item"><label for="date_since">Desde</label><input type="date" class="btn btn-default" name="date_since" id="date_since" max="<?php echo date("Y-m-d"); ?>"></li>
                                    <li class="list-inline-item"><label for="date_until">Hasta</label><input type="date" class="btn btn-default" name="date_until" id="date_until" max="<?php echo date("Y-m-d"); ?>"></li>
                                    <li class="list-inline-item"><input type="button" class="btn btn-primary " name="" id="filter_button" value="Filtrar"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12">
                            <table class="table" name="t_ticket" id="t_ticket" width="100%">
                                <thead>
                                    <th>Nº Ticket</th>
                                    <th>Fecha Inicio</th>
                                    <th>Motivo</th>
                                    <th>Tecnico Encargado</th>
                                    <th>Estado</th>
                                    <th>Obs. Tecnicas</th>
                                    <th>Fecha Finalizado</th>
                                    <th>Accion</th>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script src="js/sweetalert.min.js?v=<?php echo time(); ?>"></script>
    <script src="js/mis-tickets.js"></script>
    <script type="text/javascript">
        var DataTable =  $('#t_ticket').DataTable({});
        var ListTickets = <?php echo json_encode($Ticket->getAllUserTicket()); ?>;
        var FilterTickets = ListTickets;
        var SelectedFilters = "1,2";
        var FilterUser = '';
        var DateSince = '';
        var DateUntil = '';
        var FilterDate = false;

        $(document).ready(function(){
            showTicket();
        });
        $(document).on('click', '.confirmar_cierre', function(e){
            code = $(this)[0].id;
            if(code != 0){
                swal({
                  title: "¡Cerrando Ticket!",
                  text: "¿Seguro que desea continuar?",
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
                            url: "controller/",
                            data: {'pag':document.title,'tipo':'cc','code':code},
                            dataType: "html",
                        })
                        .fail(function(data){
                            console.log(data);
                            mensaje('fail','Error Peticion ajax');
                        })
                        .done(function(data){
                            response = JSON.parse(data);
                            switch(response.Status){
                                case 'Success':
                                    mensaje('okey','Se confirmo el cierre');
                                    $.each(ListTickets, function(k,v){
                                        if(v.Codigo == code){
                                            v.CierreConfirmado = 1;
                                            return false;
                                        }
                                    });
                                    showTicket();
                                break;
                                case 'Error':
                                    mensaje('fail','No se pudo confirmar el cierro');
                                break;
                            }
                        });
                    }
                });
            }
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