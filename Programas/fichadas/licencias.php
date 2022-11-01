<?php
require 'controller/sessionController.php';
$session = new session();

if ($session->isLogued()) {
    //Verifico si tiene permisos para estar en esta pagina.
    if (isset($_SESSION["FICHADAS"])) {

?>
        <!DOCTYPE html>
        <html>

        <head>
            <title>Fichadas - Licencias</title>
            <meta charset="utf-8" />
            <link rel="icon" type="image/png" href="img/logo-escobar-32x32.png">
            <link rel="stylesheet" href="css/style.css" />
            <link rel="stylesheet" href="src/Semantic-UI-CSS-master/semantic.min.css" />
            <script type="text/javascript" src="src/jquery-3.1.1.min.js"></script>
            <script type="text/javascript" src="src/moment-with-locales.js"></script>
            <script type="text/javascript" src="src/Semantic-UI-CSS-master/semantic.js"></script>
            <link rel="stylesheet" href="src/DataTables/datatables.min.css" />
            <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
            <!-- <link rel="stylesheet" type="text/css" href="css/bootstrap-modal.css"> -->
            <link rel="stylesheet" type="text/css" href="css/icons.css">
            <link rel="stylesheet" type="text/css" href="css/main.css">
            <link rel="stylesheet" type="text/css" href="css/app-main.css">
            <style>
                .f14 {
                    font-size: 14px !important;
                }

                .f10 {
                    font-size: 10px !important;
                }
            </style>
        </head>

        <body style="padding: 10px;">
            <?php
            require('header.php');
            require('menu.php');
            ?>
            <main class="app-main">
                <div class="">
                    <header class="page-title-bar">
                        <h1 class="page-title"><span class="icon-calendar"></span> LICENCIAS DE EMPLEADOS</h1>
                    </header>
                    <section class="">
                        <div class="">
                            <div class="ui top attached tabular menu f14">
                                <a class="item active" data-tab="first" id="view">NUEVA LICENCIA</a>
                                <a class="item" data-tab="second" id="buscar_licencia">LISTADO DE LICENCIAS</a>
                                <a class="item <?php echo ($_SESSION['FICHADAS'] == 1) ? '' : 'hide'; ?>" data-tab="third" id="ver_motivos">MOTIVOS DE LICENCIA</a>
                            </div>

                            <div class="ui bottom attached tab segment active" data-tab="first">
                                <div class="ui form f14" id='form_licencias'>
                                    <!-- <h3 class="ui right floated header">DATOS DE LA LICENCIA</h3> -->
                                    <div class="fields equal width">
                                        <div class="field">
                                            <label>MOTIVO</label>
                                            <select class="ui search dropdown searchSelect" id="motivo" name="motivo">
                                                <option value="">SELECCIONE UN MOTIVO</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>EMPLEADO</label>
                                            <select class="ui search dropdown searchSelect" id="empleado" name="empleado">
                                                <option value="">SELECCIONE UN EMPLEADO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field ">
                                            <label>INICIO</label>
                                            <input type="date" id="fechainicio" name="fechainicio">
                                        </div>
                                        <div class="field">
                                            <label>FIN</label>
                                            <input type="date" id="fechafin" name="fechafin" placeholder="">
                                        </div>
                                        <div class="field">
                                            <label>COMENTARIOS</label>
                                            <textarea name="comentario" id="comentario" cols="10" rows="5"></textarea>
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field">
                                            <label>MARCAR COMO CERRADO</label>
                                            <input type="checkbox" id="cerrado" name="cerrado">
                                        </div>
                                        <div id="cerradoMotivoField" class="field">
                                            <label>MOTIVO DE CIERRE</label>
                                            <select class="ui search dropdown searchSelect" id="cerradoMotivo" name="cerradoMotivo">
                                                <option value="">SELECCIONE UN MOTIVO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <button class="ui f14 positive basic submit button" id='saveNewLicence'><i class="green save icon"></i> AGREGAR LICENCIA</button>
                                    <div id='contentEdit' style="display: none;">
                                        <div class="ui negative message">
                                            <p>
                                                ESTA EDITANDO LA INFORMACION DE UNA LICENCIA
                                            </p>
                                        </div>
                                        <input type="hidden" id='idEditLicencia' value=''>
                                        <button class="ui f14 primary basic submit button" id='saveEditLicencia'><i class="primary edit icon"></i> GUARDAR CAMBIOS</button>
                                        <button class="ui f14 secondary basic  button" id='cancelEditLicence'>CANCELAR</button>
                                    </div>
                                    <div class="ui error message"></div>
                                    <hr>
                                    <div class="" style="padding-left: 20px; display:none" id="div_historial">
                                        <h2 class="page-title">HISTORIAL</h2>

                                        <div class="row"></div>
                                        <table class="ui very compact celled f14 table" id="grillaLicenciasHistorial" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>COMIENZO</th>
                                                    <th>FINALIZACIÓN</th>
                                                    <th>DUR</th>
                                                    <th>MOTIVO</th>
                                                    <th>EMPLEADO</th>
                                                    <th>DNI</th>
                                                    <th>LEGAJO</th>
                                                    <th>NOTAS</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="ui bottom attached tab segment" data-tab="second">
                                <h1 class="page-title-md pointer" id="title_filter">FILTROS:<span class="icon pull-right icon-circle-up"></span></h1>
                                <div class="row show" id="row_filter">
                                    <div class="col-md-12" style="font-size: 12px;">
                                        <div class="form-group col-md-3">
                                            <label for="filter_secretary">SECRETARIAS:</label>
                                            <select class="ui search dropdown searchSelect form-control" id="filter_secretary">
                                                <option value="0">TODOS</option>
                                                <?php
                                                echo $optionSecretarias;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_dependence">DEPENDENCIA:</label>
                                            <select class="ui search dropdown searchSelect form-control" id="filter_dependence">
                                                <option value="0">TODOS</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_legajo">LEGAJO:</label>
                                            <input type="text" class="form-control" id="filter_legajo" placeholder="Legajo...">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_dni">DNI:</label>
                                            <input type="number" class="form-control" id="filter_dni" placeholder="Dni...">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_desde"> DE CIERRE</label>
                                            <input type="date" class="form-control" id="filter_desde">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_hasta">HASTA:</label>
                                            <input type="date" class="form-control" id="filter_hasta">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="filter_motivo">MOTIVO:</label>
                                            <select class="form-control" id="filter_motivo">
                                                <option value="">TODOS</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="Cerrado">ESTADO DE LICENCIA:</label>
                                            <select class="form-control" id="filter_cerrado">
                                                <option value="TODOS">TODOS</option>
                                                <option value="CERRADO">CERRADO</option>
                                                <option value="ACTIVO">ACTIVO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="ui segment blue form f14">
                                    <div>
                                        <h1 class="page-title-md">LISTADO DE LICENCIAS</h1>
                                    </div>
                                    <button class='ui button basic positive f14' style="margin-bottom: 10px;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                                    <div class="ui error message"></div>
                                    <div style="padding-left: 20px;">
                                        <table class="ui very compact celled f14 table" id='grillaLicencias' style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>COMIENZO</th>
                                                    <th>FINALIZACIÓN</th>
                                                    <th>DUR</th>
                                                    <th>MOTIVO</th>
                                                    <th>EMPLEADO</th>
                                                    <th>DNI</th>
                                                    <th>LEGAJO</th>
                                                    <th>NOTAS</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="ui bottom attached tab segment" data-tab="third">
                                <div class="form-group">
                                    <button type="button" id="new_tipo" class="ui button basic blue f14" data-toggle="modal">
                                        <span class="icon-plus"> NUEVO MOTIVO</span>
                                    </button>
                                    <hr>
                                    <h1 class="page-title-md" style="margin-bottom:40px;">LISTADO DE MOTIVOS
                                        <button class='ui button basic positive f14' style="margin-bottom: 10px; float:right;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                                    </h1>
                                </div>
                                <div class="ui segment blue f14">
                                    <div style="padding-left: 20px;">
                                        <table class="ui very compact celled f14 table" id='tb_motivos' style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th>MOTIVO</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="ui modal test f14">
                                        <div class="header">
                                            <h3 class="page-title-md" id="title">AGREGAR MOTIVO</h3>
                                        </div>
                                        <div class="content">
                                            <div class="form-group">
                                                <label for="motivo_abm">MOTIVO*: </label>
                                                <input type="text" class="form-control required" id="motivo_abm" placeholder="Motivo..." required="true">
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <div class="ui red f14 cancel inverted button " style="float: left;">
                                                <i class="remove icon"></i>CANCELAR
                                            </div>
                                            <div class="ui green ok inverted button f14">
                                                <i class="checkmark icon"></i>
                                                <span id="enviar">AGREGAR</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <?php require 'footer.php'; ?>
            <script type="text/javascript" src="src/jquery-3.1.1.min.js"></script>
            <script type="text/javascript" src="src/moment-with-locales.js"></script>
            <script type="text/javascript" src="src/paginationjs-master/dist/pagination.min.js"></script>
            <script type="text/javascript" src="src/Semantic-UI-CSS-master/semantic.js"></script>
            <script type="text/javascript" src="src/DataTables/datatables.min.js"></script>
            <script src="js/sweetalert.min.js"></script>
            <script src="js/main.js"></script>
            <script src="js/export.js"></script>
            <script type="text/javascript" src="js/licencias.js"></script>
            <script src="js/filter.js"></script>
            <script src="js/xls-export.js"></script>
            <script src="js/motivos.js"></script>
        </body>

        </html>

<?php
    } else {
        //si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
        header("location: ../../index.php");
    }
} else {
    //si la Session no esta iniciada lo envio devuelta a la pagina principal.
    header("location: ../../index.php");
}
?>