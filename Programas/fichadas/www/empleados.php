<?php
	require 'controller/sessionController.php';
    $session = new session();

    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["FICHADAS"])){
            $view = false;

            if(isset($_GET['view'])){
                if(!empty($_GET['view'])){
                    $view = true;
                }
            }

            if(isset($_GET['restore'])){
                if(!empty($_GET['restore'])){
                    $restore = true;
                }
            }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Fichadas - Empleados</title>
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
            .borderless td, .borderless th {
                border: none !important;
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
                    <h1 class="page-title"><span class="icon-user"></span> EMPLEADOS</h1>
                </header>
                <section class="">
                    <div id="view_datos" style="display: <?php echo ($view) ? 'block' : 'none' ?>;">
                        <div>
                            <a href="empleados" title="Volver" class="btn btn-danger icon-arrow-left" style="margin-bottom: .5rem;"></a>
                            <!-- <button type="button" title="Volver" class="btn btn-danger icon-arrow-left" style="margin-bottom: .5rem;" id="back"></button> -->
                            <label class="page-title" id="employee_name">Empleado</label>
                            <div class="card">
                                <div class="col-md-6 row">
                                    <h2 class="page-title-md">INFORMACIÓN PERSONAL</h2>
                                </div>
                                <div class="col-md-6 ">
                                    <button title="Editar" type="button" style="margin-right: 5px;float:right;" id="view_edit_employee" class="icon-pencil btn btn-md btn-warning editEmployee" value="" data-id=""></button>
                                </div>
                                
                                <table class="table borderless">
                                    <tbody>
                                        <tr>
                                            <td><strong>FECHA DE NACIMIENTO</strong></td>
                                            <td><p id="birthDate_view"></p></td>
                                            <td><strong>DOCUMENTO</strong></td>
                                            <td><p id="dni_view"></p></td>
                                            <td><strong>SEXO</strong></td>
                                            <td><p id="sexo_view"></p></td>
                                        </tr>
                                        <tr>
                                            <td><strong>TELEFONO</strong></td>
                                            <td><p id="phone_view"></p></td>
                                            <td><strong>LEGAJO</strong></td>
                                            <td><p id="legajo_view"></p></td>
                                            <td><strong>ESTADO CIVIL</strong></td>
                                            <td><p id="civil_view"></p></td>
                                        </tr>
                                        <tr>
                                            <td><strong>DIRECCION</strong></td>
                                            <td><p id="address_view"></p></td>
                                            <td><strong>CUIL</strong></td>
                                            <td><p id="cuil_view"></p></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td><strong>HORARIO</strong></td>
                                            <td><p id="horario_view"></p></td>
                                            <td><strong>EMAIL</strong></td>
                                            <td><p id="email_view"></p></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="card">
                                <h2 class="page-title-md">LICENCIAS PASADAS</h2>
                                <div style="padding-left: 20px;">
                                    <table class="table" id="tb_history_licence" style="width: 100%;">
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
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="view_abm" style="display: <?php echo ($view) ? 'none' : 'block' ?>;">
                        <div class="ui top attached tabular menu f14" id="menuTab">
                            <a class="active item" data-tab="newEmployee" id='buttonTabNewEmployee'>AGREGAR EMPLEADO</a>
                            <a class="item" data-tab="listEmployee" id='buttonTabListEmployee'>BUSCAR EMPLEADO</a>
                            <a class="item" data-tab="listInactiveEmployee" id='buttonTabListInactiveEmployee'>EMPLEADOS INACTIVOS</a>
                            <a class="item <?php echo ($_SESSION["FICHADAS"] == 1) ? '' : 'hide' ?>" data-tab="third" id="ver_tipos">TIPOS DE EMPLEADO</a>
                        </div>

                        <div class="ui bottom attached active tab segment" data-tab="newEmployee" >
                            <div class="ui f14 form" id='formEmployee'>
                                <h1 class="ui header page-title">DATOS DEL EMPLEADO</h1>
                                <div id="view_form">
                                    <div class=" fields equal width">
                                        <div class="field ">
                                            <label>NOMBRE</label>
                                            <input type="text" id="name" name="name">
                                        </div>
                                        <div class="field ">
                                            <label>APELLIDO</label>
                                            <input type="text" id="surname" name="surname" >
                                        </div>
                                        <div class="field ">
                                            <label>NRO. DOCUMENTO</label>
                                            <div class="ui left labeled input">
                                                <div class="ui dropdown label" id="typeDoc">
                                                    <input type="hidden" name='typeDoc' value='DNI'>
                                                    <div class="text">DNI</div>
                                                    <i class="dropdown icon"></i>
                                                    <div class="menu">
                                                        <div class='item active selected' data-value="DNI">DNI</div>
                                                        <div class='item' data-value="LC">LC</div>
                                                        <div class='item' data-value="LE">LE</div>
                                                        <div class='item' data-value="LM">LM</div>
                                                        <div class='item' data-value="LF">LF</div>
                                                        <div class='item' data-value="PAS">PAS</div>
                                                        <div class='item' data-value="CI">CI</div>
                                                        <div class='item' data-value="SD">SD</div>
                                                        <div class='item' data-value="RN">RN</div>
                                                    </div>
                                                </div>
                                                <input type="number" id="nroDoc" name="nroDoc" >
                                            </div>
                                        </div>
                                        <div class="field ">
                                            <label>LEGAJO</label>
                                            <input type="number" id="legajo" name="legajo" >
                                        </div>
                                    </div>
                                    <div class=" fields equal width">
                                        <div class="field ">
                                            <label>CUIL</label>
                                            <input type="text" id="cuit" name="cuit">
                                        </div>
                                        <div class="field ">
                                            <label>FECHA DE NACIMIENTO</label>
                                            <input type="date" id="birthDate" name="birthDate">
                                        </div>
                                        <div class="field ">
                                            <label>EMAIL</label>
                                            <input type="email" id="email" name="email">
                                        </div>
                                        <div class="field ">
                                            <label>TELÉFONO</label>
                                            <div class="ui left labeled input">
                                                <div class="ui dropdown label" id="typePhone">
                                                    <input type="hidden" name='typePhone' value='CELULAR'>
                                                    <div class="text">TIPO</div>
                                                    <i class="dropdown icon"></i>
                                                    <div class="menu">
                                                        <div class='item active selected' data-value="CELULAR">CELULAR</div>
                                                        <div class='item' data-value="CASA">CASA</div>
                                                    </div>
                                                </div>
                                                <input type="text" id="phone" name="phone" class="only-number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="  field ">
                                            <label>DIRECCIÓN</label>
                                            <input type="text" id="address" name="address" >
                                        </div>
                                        <div class="field ">
                                            <label>SEXO</label>
                                            <select class="ui  selection dropdown " id="gender" name="gender">
                                                <option value="">SELECCIONE UN SEXO</option>
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>ESTADO CIVIL</label>
                                            <select class="ui  selection dropdown " id="stateCivil" name="stateCivil">
                                                <option value="">SELECCIONE UN ESTADO CIVIL</option>
                                                <option value="S">SOLTERO</option>
                                                <option value="C">CASADO</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field ">
                                            <label>SECRETARIA</label>
                                            <select class="ui search dropdown searchSelect" id="secretaria" name="secretaria">
                                                <option value="">SELECCIONE UNA SECRETARIA</option>
                                                <?php
                                                    echo $optionSecretarias;
                                                ?>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>DEPENDENCIA</label>
                                            <select class="ui search dropdown searchSelect" id="dependencia" name="dependencia">
                                                <option value="">SELECCIONE UNA DEPENDENCIA</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>TIPO DE EMPLEADO</label>
                                            <select class="ui search dropdown searchSelect" id="employeeTypes" name="employeeTypes">
                                                <option value="">SELECCIONE UN TIPO DE EMPLEADO</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>FECHA DE INGRESO</label>
                                            <input type="date" id="date_admission" name="date_admission" class="only-number" >
                                        </div>
                                    </div>
                                    <div class="fields equal width">
                                        <div class="field ">
                                            <label>HORARIO</label>
                                            <select class="ui search dropdown searchSelect" id="horario" name="horario">
                                                <option value="">SELECCIONE EL HORARIO</option>
                                            </select>
                                        </div>
                                        <div class="field ">
                                            <label>HORAS DE TRABAJO</label>
                                            <input type="number" id="workHours" name="workHours" class="only-number" >
                                        </div>
                                    </div>
                                    
                                    <button class="ui f14 positive basic submit button" id='saveNewEmployee'><i class="green save icon"></i> AGREGAR EMPLEADO</button>
                                    <div id="contentEdit" style="display: none;">
                                        <div class="ui negative message">
                                            <p>
                                                ESTA EDITANDO LA INFORMACION DE UN EMPLEADO
                                            </p>
                                        </div>
                                        <input type="hidden" id='idEditEmployee' value=''>
                                        <button class="ui f14 primary basic submit button" id='saveEditEmployee' ><i class="primary edit icon"></i> GUARDAR CAMBIOS</button>
                                        <button class="ui f14 secondary basic  button" id='cancelEditEmployee' >CANCELAR</button>
                                    </div>            
                                    <div class="ui error message"></div>
                                </div>
                            </div>
                        </div>

                        <div class="ui bottom attached tab segment" data-tab="listEmployee" >
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
                                        <label for="filter_tipoempleado">TIPO EMPLEADO:</label>
                                        <select class="ui search dropdown searchSelect form-control" id="filter_tipoempleado">
                                            <option value="">TODOS</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="filter_empleado">NOMBRE:</label>
                                        <input type="text" class="form-control" id="filter_empleado" placeholder="Nombre...">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="filter_legajo">LEGAJO:</label>
                                        <input type="text" class="form-control" id="filter_legajo" placeholder="Legajo...">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="filter_dni">DNI:</label>
                                        <input type="number" class="form-control" id="filter_dni" placeholder="Dni...">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="ui f14 form" id='formSearchEmployee'>
                                <div>
                                    <h1 class="page-title-md">LISTADO DE EMPLEADOS:</h1>
                                </div>
                                <button class='ui button basic positive f14' style="margin-bottom: 10px;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                                <div style="padding-left: 20px;">
                                    <table class="ui very compact celled f14 table" id='tableEmployeesPaginated' style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>APELLIDO Y NOMBRE</th>
                                                <th>DEPENDENCIA</th>
                                                <th>LEGAJO</th>
                                                <th>DOCUMENTO</th>
                                                <th>TIPO DE EMPLEADO</th>
                                                <th>HORARIO</th>
                                                <th>OPCIONES</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                        
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="ui bottom attached tab segment" data-tab="listInactiveEmployee" >
                            <div class="ui f14 form" id='formSearchEmployee'>
                                <div>
                                    <h1 class="page-title-md">LISTADO DE EMPLEADOS:</h1>
                                </div>
                                <!-- <button class='ui button basic positive f14' style="margin-bottom: 10px;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button> -->
                                <div style="padding-left: 20px;">
                                    <table class="ui very compact celled f14 table" id='tableInactiveEmployeesPaginated' style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>APELLIDO Y NOMBRE</th>
                                                <th>DEPENDENCIA</th>
                                                <th>LEGAJO</th>
                                                <th>DOCUMENTO</th>
                                                <th>TIPO DE EMPLEADO</th>
                                                <th>HORARIO</th>
                                                <th>OPCIONES</th>
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
                                    <span class="icon-plus"> NUEVO TIPO</span>
                                </button>
                                <hr>
                                <h1 class="page-title-md" style="margin-bottom:40px;">LISTADO DE TIPOS DE EMPLEADO 
                                    <button class='ui button basic positive f14' style="margin-bottom: 10px; float:right;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                                </h1>
                            </div>
                            <div class="ui segment blue f14" >
                                <div style="padding-left: 20px;">
                                    <table class="ui very compact celled f14 table" id='tb_tipo' style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>TIPO EMPLEADO</th>
                                                <th>DSCRIPCIÓN</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="ui modal test f14">
                                    <div class="header">
                                        <h3 class="page-title-md" id="title">AGREGAR TIPO DE EMPLEADO</h3>
                                    </div>
                                    <div class="content">
                                        <div class="form-group">
                                            <label for="tipoempleado_abm">TIPO DE EMPLEADO*: </label>
                                            <input type="text" class="form-control required" id="tipoempleado_abm" placeholder="Tipo de empleado..." required="true">
                                        </div>
                                        <div class="form-group">
                                            <label for="descripcion_abm">DESCRIPCION: </label>
                                            <textarea class="form-control" id="descripcion_abm" cols="10" rows="5" placeholder="descripcion..."></textarea>
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
        <script type="text/javascript" src="js/empleados.js"></script>
        <script src="js/filter.js"></script>
        <script type="text/javascript" src="js/tipoEmpleado.js"></script>
        <script src="js/export.js"></script>
        <script src="js/xls-export.js"></script>
        <script type="text/javascript" src="js/sec.js"></script>
        <script>
            var view = <?php echo ($view) ? 'true' : 'false'; ?>;
            $(document).ready(function(){
                <?php 
                    if($view){
                        echo 'prepareView('.$_GET['view'].');';
                    }
                ?>
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