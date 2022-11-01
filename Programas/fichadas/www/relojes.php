<?php
	require 'controller/sessionController.php';
    $session = new session();

    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["FICHADAS"])){
                if($_SESSION['FICHADAS'] != 1){
                    header("location: ../../index.php");
                }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Fichadas - Relojes</title>
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
        <main class="app-main">
            <div class="">
                <header class="page-title-bar">
                    <h1 class="page-title"><span class="icon-stopwatch"></span> RELOJES BIOMETRICOS</h1>
                </header>
                <section class="">
                    <div class="">
                        <div class="ui top attached tabular menu f14" id="menuTab">
                            <a class="active item" data-tab="newReloj" id='buttonTabNewReloj'>AGREGAR RELOJ</a>
                            <a class="item" data-tab="listRelojes" id='buttonTabListRelojes'>BUSCAR RELOJ</a>
                        </div>
                        <div class="ui bottom attached active tab segment" data-tab="newReloj" >
                            <div class="ui f14 form" id='formulario_reloj'>
                                <h1 class="ui  header">DATOS DEL RELOJ</h1>
                                <div class=" fields equal width">
                                    
                                    <input type="hidden" id="id">
                                    
                                    <div class="field ">
                                        <label>Codigo</label>
                                        <input type="text" id="codigo">
                                    </div>
                                    <div class="field">
                                        <label>Descripcion</label>
                                        <div class="ui input">
                                            <input type="text" id="descripcion" placeholder="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Ubicacion</label>
                                        <div class="ui input">
                                            <input type="text" id="ubicacion" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class=" fields equal width">
                                    <div class="field ">
                                        <label>Dirección IP</label>
                                        <div class="ui  input">
                                            <input type="text"  id="direccionip" placeholder="192.168.0.1">
                                        </div>
                                    </div>
                                    <div class="field ">
                                        <label>Puerto</label>
                                        <div class="ui  input">
                                            <input type="text"  id="puerto" placeholder="3128" >
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Secretaria</label>
                                        <select class="ui search dropdown searchSelect" id="secretaria">
                                        <option value="">Seleccione una secretaria</option>
                                            <?php
                                                echo $optionSecretarias;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="field">
                                        <label>Dependencia</label>
                                        <select class="ui search dropdown searchSelect" id="dependencia">
                                        <option value="">Seleccione una dependencia</option>
                                        </select>
                                    </div>
                                </div>
                                <div class=" fields equal width">
                                    <div class="field">
                                        <label>Usuario</label>
                                        <div class="ui input">
                                            <input type="text" id="usuario" placeholder="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Clave</label>
                                        <div class="ui input">
                                            <input type="text" id="clave" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div class=" fields equal width">
                                    <div class="field">
                                        <label>Marca</label>
                                        <div class="ui input">
                                            <input type="text" id="marca" placeholder="">
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label>Modelo</label>
                                        <div class="ui input">
                                            <input type="text" id="modelo" placeholder="">
                                        </div>
                                    </div>
                                </div>
                                <div style="display: none" id="last_error">
                                    <h2 class="ui  header">Ultimo error al capturar registros:</h2>
                                    <div class=" fields equal width">
                                        <div class="field">
                                            <label>Fecha ultimo error</label>
                                            <div class="ui input">
                                                <input type="text" id="fecha_ultimo_error" disabled>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label>Descripcion del error</label>
                                            <div class="ui input">
                                                <textarea name="" id="descripcion_ultimo_error" cols="30" rows="5" disabled></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ui error message"></div>
                                <button class="ui f14 positive basic submit button" id='save_all'><i class="green save icon"></i> AGREGAR RELOJ</button>
                                <div id='contentEdit' style="display: none;">
                                    <div class="ui negative message">
                                        <p>
                                            ESTA EDITANDO LA INFORMACION DE UN RELOJ
                                        </p>
                                    </div>
                                    <input type="hidden" id='idEditEmployee' value=''>

                                    <button class="ui f14 primary basic submit button" id='save_edit' >
                                        <i class="primary edit icon"></i> GUARDAR CAMBIOS
                                    </button>
                                    <button class="ui f14 secondary basic  button" id='limpiar_controles' >CANCELAR</button>
                                </div>
                            </div>
                        </div>
                        <div class="ui bottom attached tab segment" data-tab="listRelojes" >
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
                                        <label for="filter_reloj">NOMBRE:</label>
                                        <input type="text" class="form-control" id="filter_reloj" placeholder="Nombre...">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="filter_ip">IP:</label>
                                        <input type="text" class="form-control" id="filter_ip" placeholder="Ip...">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div>
                                <h1 class="ui header">LISTADO DE RELOJES</h1>
                                <button class='ui button basic positive f14' style="margin-bottom: 10px;" id='import_to_excel'><i class="file excel icon"></i>Exportar a excel</button>
                            </div>
                            <style>
                                .test{
                                    max-width: 100px;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    white-space: nowrap;
                                }
                            </style>
                            <div style="padding-left: 20px;">
                                <table class="ui select compact f14 table" id='grilla_relojes' style="width: 100%" >
                                    <thead>
                                        <tr>
                                            <th>CODIGÓ</th>
                                            <th>DESCRIPCIÓN</th>
                                            <th>DEPENDENCIA</th>
                                            <th>IP</th>
                                            <th>ULTIMO ERROR</th>
                                            <th>FECHA ULT.ERROR</th>
                                            <th></th>
                                        </tr>
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
        <script src="js/to-csv.js"></script>
        <script src="js/main.js"></script>
        <script src="js/filter.js"></script>
        <script type="text/javascript" src="js/relojes.js"></script>
        <script type="text/javascript" src="js/sec.js"></script>
        <script src="js/xls-export.js"></script>
    </body>
</html>

<?php 
			}
			else{
				//si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
				header("location: ../../index.php");
			}
		}
		else{
			//si la Session no esta iniciada lo envio devuelta a la pagina principal.
			header("location: ../../index.php");
		}
	}
?>