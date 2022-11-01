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
	<title>Consulta de Tickets</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
<style type="text/css">
    /* CSS principal da barra de progresso. Não deve ser alterado. */
    #step-bar-content ol.step-progress-bar {
        list-style: none;
        padding: 0;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    #step-bar-content ol.step-progress-bar li {
        display: inline-block;
        vertical-align: top;
        text-align: center;
        flex: 1 1;
        position: relative;
        margin: 0 5px 0;
    }
    #step-bar-content ol.step-progress-bar li span.content-bullet {
        border-radius: 100%;
        display: block;
        text-align: center;
        transform: translateX(-50%);
        margin-left: 50%;
    }
    #step-bar-content ol.step-progress-bar li span.content-wrapper {
        display: inline-block;
        overflow: visible;
        width: 100%;
        padding: 0;
    }
    #step-bar-content ol.step-progress-bar li span.content-stick {
        position: absolute;
        display: block;
        width: 100%;
        height: 8px;
        z-index: -1;
        transform: translate(-50%, -50%);
    }
    /* Cores. Sinta-se livre para alterar. */

    /* Cor padrão.
       Passado: #2dcd73 (verde) e branco.
       Presente: #4c92d9 (azul) e branco.
       Futuro: #dde2e3 (cinza claro) e #869398 (cinza escuro).
    */
    #step-bar-content ol.step-progress-bar li.step-past *,
    #step-bar-content ol.step-progress-bar li.step-present .content-stick {
        color: #2dcd73;
        background: #2dcd73;
    }
    #step-bar-content ol.step-progress-bar li.step-present * {
        color: #4c92d9;
        background: #4c92d9;
    }
    #step-bar-content ol.step-progress-bar li .content-bullet {
        color: white;
    }
    #step-bar-content ol.step-progress-bar li.step-future * {
        color: #869398;
        background: #dde2e3;
    }
    #step-bar-content ol.step-progress-bar li .content-wrapper {
        background: transparent;
    }
    /* Cor especial 1.
       Passado: vemelho
       Presente: laranja
       Futuro: amarelo
       Cor dos números: azul
    */
    #step-bar-content ol.step-progress-bar.cor-especial li.step-past *,
    #step-bar-content ol.step-progress-bar.cor-especial li.step-present .content-stick {
        color: red;
        background: red;
    }
    #step-bar-content ol.step-progress-bar.cor-especial li.step-present * {
        color: orange;
        background: orange;
    }
    #step-bar-content ol.step-progress-bar.cor-especial li.step-future * {
        color: yellow;
        background: yellow;
    }
    #step-bar-content ol.step-progress-bar.cor-especial li .content-bullet {
        color: blue;
    }
    #step-bar-content ol.step-progress-bar.cor-especial li .content-wrapper {
        background: transparent;
    }
    /* Tamanhos. */

    /* Tamanho pequeno:
       Bolinha de 25px de diâmetro.
       Fonte 75%.
       Conector 4px de altura.
    */
    #step-bar-content ol.step-progress-bar.small li .content-bullet {
        width: 25px;
        line-height: 25px;
    }
    #step-bar-content ol.step-progress-bar.small li {
        font-size: 75%;
    }
    #step-bar-content ol.step-progress-bar.small li .content-stick {
        top: 12.5px; /* Metade do diâmetro. */
        height: 4px;
    }
    /* Tamanho médio:
       Bolinha de 37px de diâmetro.
       Fonte 100%.
       Conector 6px de altura.
    */
    #step-bar-content ol.step-progress-bar.mid li .content-bullet {
        width: 37px;
        line-height: 37px;
    }
    #step-bar-content ol.step-progress-bar.mid li {
        font-size: 100%;
    }
    #step-bar-content ol.step-progress-bar.mid li .content-stick {
        top: 18.5px; /* Metade do diâmetro. */
        height: 6px;
    }
    /* Tamanho grande:
       Bolinha de 49px de diâmetro.
       Fonte 120%.
       Conector 8px de altura.
    */
    #step-bar-content ol.step-progress-bar.large li .content-bullet {
        width: 70px;
        line-height: 70px;
    }
    #step-bar-content ol.step-progress-bar.large li {
        font-size: 125%;
    }
    #step-bar-content ol.step-progress-bar.large li .content-stick {
        top: 35.5px; /* Metade do diâmetro. */
        height: 10px;
    }
    .ticket-detail h3{
        color: #00BFFF;
    }
    .title{
        /*text-align: left;*/
        font-size: 20px;
        color: #00BFFF;
    }
    /*.ticket-detail label{
        margin-left: 20%;
    }*/
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
                <h1 class="page-title"><span class="icon-search"></span> CONSULTA DE TICKETS</h1>
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
                                    <label>Consultar Nº de Ticket</label>
                                    <input type="text" class="form-control" name="ticket" id="ticket" placeholder="Nº Ticket" required>
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
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script src="js/ticket.js?v=<?php echo time(); ?>"></script>
    <script src="js/sweetalert.min.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">

/* JavaScript na página. */
        $("#step-bar").stepProgressBar(1);
        $(window).on('scroll',function(){
            if ($(window).scrollTop()) {
                $('nav').addClass('affix');
            }
            else{
                $('nav').removeClass('affix'); 
            }
        })
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