<?php
    require 'controller/ticketController.php';

    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
                

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
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <link rel="stylesheet" type="text/css" href="css/checkbox-switch.css">
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
                        <div class="form-group col-md-2" id="filter_state_checkbox">
                            <label class="page-title-sm">ESTADOS:</label>                
                            <div class="">
                                <label for="filter_pendiente" class="margin-r pointer">PENDIENTES</label>
                                <label class="el-switch el-switch-sm">
                                    <input type="checkbox" class="filter_state" id="filter_pendiente" value="1" checked>
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                            <div>
                                <label for="filter_encurso" class="margin-r pointer">EN CURSO</label>
                                <label class="el-switch el-switch-sm el-switch-yellow">
                                    <input type="checkbox" class="filter_state" id="filter_encurso" value="2" checked>
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                            <div>
                                <label class="margin-r pointer" for="filter_finalizado">FINALIZADOS</label>
                                <label class="el-switch el-switch-sm el-switch-green">
                                    <input type="checkbox" class="filter_state" value="3" id="filter_finalizado">
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                            <div>
                                <label class="margin-r pointer" for="filter_confirmado">CONFIRMADO</label>
                                <label class="el-switch el-switch-sm el-switch-green">
                                    <input type="checkbox" class="filter_state" value="1" id="filter_confirmado">
                                    <span class="el-switch-style"></span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-md-3">
                            <h2 class="page-title-sm">FECHAS:</h2>
                            <div class="col-md-12">
                                <label for="filter_since">DESDE:</label>
                                <input type="date" id="filter_since" class="form-control">
                            </div>
                            <div class="col-md-12">
                                <label for="filter_until">HASTA:</label>
                                <input type="date" id="filter_until" class="form-control">
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
                                    <th>Nº TICKET</th>
                                    <th>FECHA INICIO</th>
                                    <th>MOTIVO</th>
                                    <th>TECNICO ENCARGADO</th>
                                    <th>ESTADO</th>
                                    <th>OBS. TECNICAS</th>
                                    <th>FECHA FINALIZADO</th>
                                    <th>ACCIONES</th>
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
    <script src="js/main.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/mis-tickets.js"></script>
    <script type="text/javascript">
        const ListTickets = <?php echo json_encode($Ticket->getAllUserTicket()); ?>;

        let DataTable;

        $(document).on('click', '.confirmar_cierre', function(e){
            code = $(this)[0].id;
            if(code != 0){
                swal({
                  title: "¡Cerrando Ticket!",
                  text: "¿Seguro que desea continuar?",
                  icon: "",
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