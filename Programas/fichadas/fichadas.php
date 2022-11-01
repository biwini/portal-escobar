<?php
require 'controller/sessionController.php';
$session = new session();

if ($session->isLogued()) {
    //Verifico si tiene permisos para estar en esta pagina.
    if (isset($_SESSION["FICHADAS"])) {
        if ($_SESSION['FICHADAS'] != 1) {
            header("location: empleados");
        }

?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <title>Fichadas</title>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <link rel="icon" type="image/png" href="img/favicon-196x196.png">
            <link rel="stylesheet" href="css/report.css" />
            <link rel="stylesheet" href="src/Semantic-UI-CSS-master/semantic.min.css" />
            <link rel="stylesheet" type="text/css" href="src/DataTables/datatables.min.css" />
            <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
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

        <body>
            <?php
            require('header.php');
            require('menu.php');
            ?>
            <main class="app-main">
                <div class="page">
                    <header class="page-title-bar">
                        <h1 class="page-title"><span class="icon-home2"></span> FICHADAS </h1>
                    </header>
                    <section class="">
                        <div class="">
                            <div class="ui f14 form" id='formReport'>
                                <h2 class="ui  header">Reportes</h2>
                                <div class=" fields ">
                                    <div class="field ">
                                        <label>Desde</label>
                                        <input type="date" id="fromDate">
                                    </div>
                                    <div class="field ">
                                        <label>Hasta</label>
                                        <input type="date" id="toDate">
                                    </div>
                                    <div class="field " style="width: 350px;">
                                        <label>Secretaria</label>
                                        <select class="ui search dropdown searchSelect" id="secretary">
                                            <option value="">Todas las Secretarias</option>
                                        </select>
                                    </div>
                                    <div class="field " style="width: 350px;">
                                        <label>Dependencias</label>
                                        <select class="ui search dropdown searchSelect" id="dependence">
                                            <option value="">Todas las Dependencias</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='fields'>

                                    <div class="field " style="width:300px">
                                        <label>Apellido y nombre</label>
                                        <input type='text' id='searchName'>
                                    </div>
                                    <div class="field ">
                                        <label>DNI</label>
                                        <input type='text' id='searchDni'>
                                    </div>
                                    <div class="field ">
                                        <label>Legajo</label>
                                        <input type='text' id='searchLegajo'>
                                    </div>
                                    <div class="field">
                                        <label>.</label>
                                        <button class="ui button basic primary huge" id='searchReport'><i class="search icon"></i> Buscar</button>
                                    </div>
                                </div>

                            </div>
                            <hr>
                            <table id="listEmployee" class="ui celled compact table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id='masterCheckbox'></th>
                                        <th>Apellido y nombre</th>
                                        <th>Nro. Legajo</th>
                                        <th>DNI</th>
                                        <th>Secretaria</th>
                                        <th>Dependencia</th>
                                        <th>Tipo de Empleado</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="ui info icon message" id='loading' style="display: none;">
                                <i class="notched circle loading icon"></i>
                                <div class="content">
                                    <div class="header">
                                        Cargando...
                                    </div>
                                    <p>Se estan buscando los empleados</p>
                                </div>
                            </div>
                            <div class="ui black message" id='msjCreateReport' style="display: none;">
                                <div class="content">
                                    <div class="header" style="margin-bottom: 10px;">
                                        <div>
                                            <label style="margin-right: 10px;">Agregar fichada</label>
                                        </div>

                                        <div class="field" style="margin-right: 10px;">
                                            <label for="datetime">Fecha y hora</label>
                                            <input type="datetime-local" id="datetime" name="datetime" style="color: black;">
                                        </div>

                                        <button class="ui button basic positive massive" id='addFichada'><i class="file icon"></i>Generar</button>
                                    </div>
                                    <p> <b id='cantEmployeeSelected'></b> empleados seleccionados</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </main>
            <script type="text/javascript" src="src/jquery-3.1.1.min.js"></script>
            <!-- <script src="js/bootstrap.min.js"></script> -->
            <script type="text/javascript" src="src/DataTables/datatables.min.js"></script>
            <script type="text/javascript" src="src/dayjs.min.js"></script>
            <script type="text/javascript" src="src/es.min.js"></script>
            <script type="text/javascript" src="src/Semantic-UI-CSS-master/semantic.min.js"></script>
            <script type="text/javascript" src="js/fichadas.js"></script>
            <script src="js/main.js"></script>
        </body>

        </html>
<?php
    } else {
        //si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
        header("location: ../../index.php");
    }
} else {
    //si la Session no esta iniciada lo envio devuelta a la pagina principal.
    header("location: controller/login-test.php");
}
?>