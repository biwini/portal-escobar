<?php
	require 'controller/sessionController.php';
    $session = new session();

    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["FICHADAS"])){
            if($_SESSION['FICHADAS'] != 1){
                header("location: ../../index.php");
            }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Fichadas - Horarios</title>
        <meta charset="utf-8" />
        <link rel="icon" type="image/png" href="img/favicon-196x196.png">
        <link rel="stylesheet" href="css/style.css" />
        <link rel="stylesheet" href="src/Semantic-UI-CSS-master/semantic.min.css" />
        <link rel="stylesheet" href="src/paginationjs-master/dist/pagination.css" />
        <link rel="stylesheet" href="src/DataTables/datatables.min.css" />
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="css/icons.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <link rel="stylesheet" type="text/css" href="css/app-main.css">
        <style>
            .f14{
                font-size: 14px !important;
            }
            .f10{
                font-size: 10px !important;
            }
        </style>
    </head>
    <body style="padding: 10px;">
        <?php 
        require('header.php');
        require('menu.php');
        ?>
        <main class="app-main" style="overflow: initial;">
            <div class="">
                <header class="page-title-bar">
                    <h1 class="page-title"><span class="icon-clock"></span> HORARIOS</h1>
                </header>
                <section class="">
                    <div class="">
                        <div class="ui top attached tabular menu f14" id="menuTab">
                            <a class="active item" data-tab="newHorario" id='buttonTabnewHorario'>AGREGAR HORARIO</a>
                            <a class="item" data-tab="listHorario" id='buttonTablistHorario'>LISTADO DE HORARIOS</a>
                        </div>
                        <div class="ui bottom attached active tab segment" data-tab="newHorario" >
                            <div class="ui f14 form" id='formEmployee'>
                                <div class="ui blue segment f14 form" id='form_horario'>
                                    <h1 class="ui header">DATOS DEL HORARIO</h1>
                                    <div class="fields equal width">
                                        <div class=" five wide field">
                                            <label>NOMBRE HORARIO:</label>
                                            <input type="text" id="horario" name="horario" placeholder="Codigo horario " size="30">
                                        </div>
                                        <div class="field">
                                            <label>DESCRIPCIÓN:</label>
                                            <input type="text" id="descrip" name="descrip" placeholder="Descripción del horario " size="100">
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field " >
                                            <label>TOLERANCIA ENTRADA (MINUTOS)</label>
                                            <input type="number" class="only-number" min="0" max="3" maxlength="2" id="tol1" name="tol1" placeholder="Tolerancia 1">
                                        </div>
                                        <div class="field " >
                                            <label>TOLERANCIA SALIDA (MINUTOS)</label>
                                            <div class="ui  input">
                                                <input type="number" class="only-number" min="0" max="3" maxlength="2" id="tol2" name="tol2" placeholder="Tolerancia 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field ">
                                            <label>TRABAJA FERIADOS</label>
                                            <select name="feriado" id="feriado">
                                                <option value="0" selected>NO</option>
                                                <option value="1">SI</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>FERIADOS A TRABAJAR</label>
                                            <select name="feriado_trabajo" id="feriado_trabajo" disabled>
                                                <option value="0" selected>TODOS</option>
                                            </select>
                                        </div>
                                        <!-- <div class="field mini">
                                            <label>HORARIO FERIADOS</label>
                                            <div class="ui right left input">
                                                <input type="time" name="feriado_desde" id="feriado_desde" disabled>
                                                <input type="time" name="feriado_hasta" id="feriado_hasta" disabled>
                                            </div>
                                        </div> -->
                                    </div>
                                    <div class="ui error message"></div>
                                </div>
                                <div class="ui segment blue f14 form" id='form_hxd' >
                                    <div class="ui error message"></div>
                                    <style>
                                        .checkbox{
                                            width: 2rem;
                                            height: 2rem;
                                        }
                                        .tr-disabled{
                                            background: lightgrey;
                                            opacity: 0.7;
                                        }
                                        .tr-disabled td input{
                                            background: lightgrey !important;
                                            /* opacity: 0.7; */
                                        }
                                    </style>
                                    <table class="ui very compact table table-bordered" id='table_horarios'>
                                        <thead>
                                            <tr>
                                                <th>DIA</th>
                                                <th></th>
                                                <th>ENTRADA</th>
                                                <th>SALIDA </th>
                                                <th>ANTES</th>
                                                <th>DESPUES</th>
                                                <th>HORAS TOTALES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>LUNES</td>
                                                <td><input type="checkbox" class="checkbox" id="lunes_active" name="lunes_active" checked></td>
                                                <td><input type="time" id="lunes_in" class="calc-hours" name="lunes_in" value="08:00"></td>
                                                <td><input type="time" id="lunes_out" class="calc-hours" name="lunes_out" value="15:00"></td>
                                                <td><input type="time" id="lunes_almin" name="lunes_almin"></td>
                                                <td><input type="time" id="lunes_almout" name="lunes_almout"></td>
                                                <td><label id="lunes_calc">07:00hs</label></td>
                                            </tr>
                                            <tr>
                                                <td>MARTES</td>
                                                <td><input type="checkbox" class="checkbox" id="martes_active" name="martes_active" checked></td>
                                                <td><input type="time" id="martes_in" class="calc-hours" name="martes_in" value="08:00"></td>
                                                <td><input type="time" id="martes_out" class="calc-hours" name="martes_out" value="15:00"></td>
                                                <td><input type="time" id="martes_almin" name="martes_almin"></td>
                                                <td><input type="time" id="martes_almout" name="martes_almout"></td>
                                                <td><label id="martes_calc">07:00hs</label></td>
                                            </tr>
                                            <tr>
                                                <td>MIERCOLES</td>
                                                <td><input type="checkbox" class="checkbox" id="miercoles_active" name="miercoles_active" checked></td>
                                                <td><input type="time" id="miercoles_in" class="calc-hours" name="miercoles_in" value="08:00"></td>
                                                <td><input type="time" id="miercoles_out" class="calc-hours" name="miercoles_out" value="15:00"></td>
                                                <td><input type="time" id="miercoles_almin" name="miercoles_almin"></td>
                                                <td><input type="time" id="miercoles_almout" name="miercoles_almout"></td>
                                                <td><label id="miercoles_calc">07:00hs</label></td>
                                            </tr>
                                            <tr>
                                                <td>JUEVES</td>
                                                <td><input type="checkbox" class="checkbox" id="jueves_active" name="jueves_active" checked></td>
                                                <td><input type="time" id="jueves_in" class="calc-hours" name="jueves_in" value="08:00"></td>
                                                <td><input type="time" id="jueves_out" class="calc-hours" name="jueves_out" value="15:00"></td>
                                                <td><input type="time" id="jueves_almin" name="jueves_almin"></td>
                                                <td><input type="time" id="jueves_almout" name="jueves_almout"></td>
                                                <td><label id="jueves_calc">07:00hs</label></td>
                                            </tr>
                                            <tr>
                                                <td>VIERNES</td>
                                                <td><input type="checkbox" class="checkbox" id="viernes_active" name="viernes_active" checked></td>
                                                <td><input type="time" id="viernes_in" class="calc-hours" name="viernes_in" value="08:00"></td>
                                                <td><input type="time" id="viernes_out" class="calc-hours" name="viernes_out" value="15:00"></td>
                                                <td><input type="time" id="viernes_almin" name="viernes_almin"></td>
                                                <td><input type="time" id="viernes_almout" name="viernes_almout"></td>
                                                <td><label id="viernes_calc">07:00hs</label></td>
                                            </tr>
                                            <tr class="tr-disabled">
                                                <td>SABADO</td>
                                                <td><input type="checkbox" class="checkbox" id="sabado_active" name="sabado_active"></td>
                                                <td><input type="time" id="sabado_in" class="calc-hours" name="sabado_in" value="08:00" disabled></td>
                                                <td><input type="time" id="sabado_out" class="calc-hours" name="sabado_out" value="15:00" disabled></td>
                                                <td><input type="time" id="sabado_almin" name="sabado_almin" disabled></td>
                                                <td><input type="time" id="sabado_almout" name="sabado_almout" disabled></td>
                                                <td><label id="sabado_calc">00:00hs</label></td>
                                            </tr>
                                            <tr class="tr-disabled">
                                                <td>DOMINGO</td>
                                                <td><input type="checkbox" class="checkbox" id="domingo_active" name="domingo_active"></td>
                                                <td><input type="time" id="domingo_in" class="calc-hours" name="domingo_in" value="08:00" disabled></td>
                                                <td><input type="time" id="domingo_out" class="calc-hours" name="domingo_out" value="15:00" disabled></td>
                                                <td><input type="time" id="domingo_almin" name="domingo_almin" disabled></td>
                                                <td><input type="time" id="domingo_almout" name="domingo_almout" disabled></td>
                                                <td><label id="domingo_calc">00:00hs</label></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <div class="ui negative message" id='error_tbl' style='display: none;'>
                                        <div class="header">
                                            DEBE AGREGAR AL MENOS UN HORARIO
                                        </div>
                                    </div>
                                </div>
                                <button class="ui f14 positive basic submit button" id='save_all'><i class="green save icon"></i> AGREGAR HORARIO</button>
                                <div id='contentEdit' style="display: none;">
                                    <div class="ui negative message">
                                        <p>
                                            ESTA EDITANDO LA INFORMACION DE UN HORARIO
                                        </p>
                                    </div>
                                    <input type="hidden" id='idEditHorario' value=''>
                                    <button class="ui f14 primary basic submit button" id='save_edit_horario' ><i class="primary edit icon"></i> GUARDAR CAMBIOS</button>
                                    <button class="ui f14 secondary basic  button" id='cancel_edit_horario' >CANCELAR</button>
                                </div>            
                                <div class="ui error message"></div>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment" data-tab="listHorario" id="tabla_horarios">
                            <h1 class="page-title-md pointer" id="title_filter">FILTROS:<span class="icon pull-right icon-circle-up"></span></h1>
                            <div class="row show" id="row_filter">
                                <div class="col-md-12" style="font-size: 12px;">
                                    <div class="form-group col-md-3">
                                        <label for="filter_horario">Horario:</label>
                                        <input type="text" class="form-control" id="filter_horario" placeholder="Horario...">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="filter_feriado">TRABAJA FERIADOS:</label>
                                        <select class="ui search dropdown searchSelect form-control" id="filter_feriado">
                                            <option value="TODO">TODO</option>
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <button class='ui button basic positive f14' style="margin-bottom: 10px;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                            <div style="padding-left: 20px;">
                                <table class="ui compact table table-striped f14" id='lista_horarios' style="width: 100%;">
                                    <thead>
                                            <th>HORARIO</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>TOLERANCIA ENTRADA</th>
                                            <th>TOLERANCIA SALIDA</th>
                                            <th>FERIADOS</th>
                                            <th>EDITAR</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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
        <script src="js/filter.js"></script>
        <script src="js/export.js"></script>
        <script src="js/xls-export.js"></script>
        <script src="js/to-csv.js"></script>
        <script type="text/javascript" src="js/horario.js"></script>
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