<?php
    require_once('controller/sessionController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
                
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Consultar Ticket</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/progress-bar.css">
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <main class="app-main">
        <div class="container-fluid page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-search"></span> CONSULTAR TICKET</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12">
                            <form name="ticket" id="ticket">
                                <div class="form-group">
                                    <label>CONSULTAR ESTADO DEL TICKET</label>
                                    <input type="number" class="form-control only-number" name="ticket" id="ticket" placeholder="Nº Ticket" required>
                                </div>
                                <div class="form-group">
                                    <input type="submit" class="btn btn-primary pull-right" name="consultar" id="consultar" name="consultar" value="Consultar">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
            <section class="hide" id="section_result">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12" id="step-bar-content" style="margin-bottom: 15px;">
                            <ol id="step-bar" class="large">
                                <li>PENDIENTE</li>
                                <li>EN CURSO</li>
                                <li id="retiro" class="hide">LISTA PARA RETIRO</li>
                                <li>FINALIZADO</li>
                            </ol>
                        </div>
                        <!-- <div class="col-md-12">
                            <div class="form-group ticket-detail">
                                <div class="col-md-6">
                                    <h3>SECRETARIA:</h3>
                                    <label id="secretaria"></label>
                                </div>
                                <div class="col-md-6">
                                    <h3>MOTIVO:</h3>
                                    <label id="motivo"></label>
                                </div>
                                <div class="col-md-6">
                                    <h3>DEPENDENCIA:</h3>
                                    <label id="dependencia"></label>
                                </div>
                                <div class="col-md-6">
                                    <h3>TECNICO ENCARGADO:</h3>
                                    <label id="tecnico"></label>
                                </div>
                                <div class="col-md-6">
                                    <h3>RESPONSABLE:</h3>
                                    <label id="responsable"> </label>
                                </div>
                                <div class="col-md-6">
                                    <h3>FECHA FINALIZADO:</h3>
                                    <label id="fecha_finalizado"> </label>
                                </div>
                                <div class="col-md-6">
                                    <h3>FECHA INICIO:</h3>
                                    <label id="fecha_inicio"> </label>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-md-12">
                            <div class="form-group ticket-detail">
                                <div class="col-md-6">
                                    <label class=" title">SECRETARIA:</label>
                                    <label  id="secretaria"></label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">MOTIVO:</label>
                                    <label  id="motivo"></label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">DEPENDENCIA:</label>
                                    <label  id="dependencia"></label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">TECNICO ENCARGADO:</label>
                                    <label  id="tecnico"></label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">RESPONSABLE:</label>
                                    <label  id="responsable"> </label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">OBSERVACIONES TECNICAS:</label>
                                    <label  id="observacion"></label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">FECHA INICIO:</label>
                                    <label  id="fecha_inicio"> </label>
                                </div>
                                <div class="col-md-6">
                                    <label class=" title">FECHA FINALIZADO:</label>
                                    <label  id="fecha_finalizado"> </label>
                                </div>
                                <div id="div_retirado" class="hide">
                                    <div class="col-md-6">
                                        <label class=" title">RETIRADO POR:</label>
                                        <label  id="retirado_por"> </label>
                                    </div>
                                    <div class="col-md-6">
                                        <label class=" title">FECHA RETIRO:</label>
                                        <label  id="fecha_retirado"> </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/ticket.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script type="text/javascript">
        $("#step-bar").stepProgressBar(1);
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