<?php
    require 'controller/ticketController.php';
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"]) && $_SESSION['TICKETS'] == 1){
                
                require 'controller/motivoController.php';
                require_once 'controller/equipoController.php';

                $Ticket = new ticket();
                $Motivo = new motivo();
                $Equipo = new equipo();
                $TipoEquipo = new tipoEquipo();

                $listTecnicos = $Ticket->getTecnico();
                $listMotivos = $Motivo->getMotivo();
                $listTiposEquipos = $TipoEquipo->getList();
                $listProcesadores = $Equipo->getProcesadores();
                $listMotherBoards = $Equipo->getPlacasMadre();
                $listDiscos = $Equipo->getTipoDisco();
                $listSO = $Equipo->getSistemasOperativos();

                $optionTipoEquipos = '';

                foreach ($listTiposEquipos as $key => $value) {
                    $optionTipoEquipos .= '<option value=\''.$value["Id"].'\'>'.$value["Type"].'</option>';
                }

                $optionMotivo = '';
                $optionTecnicos = '';

                foreach ($listMotivos as $key => $value) {
                    $optionMotivo .= '<option value=\''.$value['Id'].'\'>'.$value['Motivo'].'</option>';
                }

                foreach ($listTecnicos as $key => $value) {
                    $optionTecnicos .= '<option value=\''.$value['Id'].'\'>'.$value['Name'].' '.$value['LastName'].'</option>';  
                }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Solicitudes</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/checkbox-switch.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/libs/dropzone/dropzone.css">
    <style type="text/css">
        .detail{
            margin-left: 1rem;
        }

        .page-title-md{
            font-size: 1.7rem;
            margin-top: 0;
            margin-bottom: .5rem;
            color: var(--colorTitle);
        }
    </style>
    <script>
        const Motivos = <?php echo json_encode($listMotivos); ?>;
    </script>
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
        <div class="container-fluid page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-history"></span> LISTA DE TICKETS</h1>
            </header>
            <section>
                <div class="card">
                    <h1 class="page-title pointer" id="title_filter"><span class=""></span> FILTRAR POR:<span class="icon pull-right icon-circle-up"></span></h1>
                    <div class="row show" id="row_filter">
                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="col-md-3">
                                <label class="page-title-sm">ESTADOS:</label>
                                <div class="col-md-12" id="filter_state_content">
                                    <div class="">
                                        <label for="filter_pendiente" class="margin-r pointer">PENDIENTES</label>
                                        <label class="el-switch el-switch-sm">
                                            <input type="checkbox" id="filter_pendiente" value="1" checked>
                                            <span class="el-switch-style"></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label for="filter_encurso" class="margin-r pointer">EN CURSO</label>
                                        <label class="el-switch el-switch-sm el-switch-yellow">
                                            <input type="checkbox" id="filter_encurso" value="2" checked>
                                            <span class="el-switch-style"></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="margin-r pointer" for="filter_finalizado">FINALIZADOS</label>
                                        <label class="el-switch el-switch-sm el-switch-green">
                                            <input type="checkbox" id="filter_finalizado" value="3">
                                            <span class="el-switch-style"></span>
                                        </label>
                                    </div>
                                    <div>
                                        <label class="margin-r pointer" for="filter_urgente">URGENTES</label>
                                        <label class="el-switch el-switch-sm el-switch-red">
                                            <input type="checkbox" id="filter_urgente" value="4">
                                            <span class="el-switch-style"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h1 class="page-title-sm"><span class=""></span> SECRETARIAS / DEPENDENCIAS:</h1>
                                <div class="col-md-12">
                                    <label class="" for="filter_secretary">SECRETARIA</label>
                                    <select class="form-control form-group" id="filter_secretary">
                                        <option value="" selected>TODAS LAS SECRETARIAS</option>
                                        <?php 
                                            foreach ($SecretaryList as $key => $value) {
                                                echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                            }
                                        ?>
                                    </select>
                                    <label class="" for="filter_secretary">DEDENDENCIA</label>
                                    <select class="form-control" id="filter_dependence">
                                        <option value="" selected>TODAS LAS DEPENDENCIAS</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h1 class="page-title-sm"><span class=""></span> FECHAS:</h1>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="filter_since">DESDE</label>
                                        <input type="date" id="filter_since" value="" class="form-control pointer">
                                    </div>
                                    <div class="form-group">
                                        <label for="filter_until">HASTA</label>
                                        <input type="date" id="filter_until" class="form-control pointer">
                                    </div>     
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h1 class="page-title-sm"><span class=""></span> TECNICOS:</h1>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="" for="filter_atendido">ATENDIDO POR:</label>
                                        <select class="form-control pointer" id="filter_atendido">
                                            <option value="">MOSTRAR TODOS</option>
                                            <?php 
                                                echo $optionTecnicos;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="filter_encargado">ENCARGADO</label>
                                        <select class="form-control pointer" id="filter_encargado">
                                            <option value="">MOSTRAR TODOS</option>
                                            <?php 
                                                echo $optionTecnicos;
                                            ?>
                                            <option value="SIN_ASIGNAR">SIN ASIGNAR</option>
                                        </select>
                                    </div>   
                                </div>
                            </div>
                            <div class="col-md-3">
                                <h1 class="page-title-sm"><span class=""></span> MOTIVOS:</h1>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="" for="filter_motivo">MOTIVO:</label>
                                        <select class="form-control pointer" id="filter_motivo">
                                            <option value="">MOSTRAR TODOS</option>
                                            <?php 
                                                echo $optionMotivo;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="filter_submotivo">SUB MOTIVO</label>
                                        <select class="form-control pointer" id="filter_submotivo">
                                            <option value="">MOSTRAR TODOS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h1 class="page-title-sm"><span class=""></span> EQUIPOS:</h1>
                                <div class="col-md-10">
                                    <div class="form-group col-md-4">
                                        <label class="" for="filter_tipoequipo">TIPO:</label>
                                        <select class="form-control pointer" id="filter_tipoequipo">
                                            <option value="">TODOS</option>
                                            <?php 
                                                echo $optionTipoEquipos;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label class="" for="filter_nequipo">Nº EQUIPO</label>
                                        <input type="number" id="filter_nequipo" class="form-control only-number" placeholder="Nº EQUIPO...">
                                    </div> 
                                    <div class="form-group col-md-4">
                                        <label class="" for="filter_npatrimonio">Nº PATRIMONIO</label>
                                        <input type="number" id="filter_npatrimonio" class="form-control only-number" placeholder="Nº PATRIMONIO...">
                                    </div>  
                                    <div class="form-group col-md-4">
                                        <label class="" for="filter_marca">MARCA</label>
                                        <input type="text" id="filter_marca" class="form-control" placeholder="MARCA...">
                                    </div>  
                                    <div class="form-group col-md-4">
                                        <label class="" for="filter_modelo">MODELO</label>
                                        <input type="text" id="filter_modelo" class="form-control" placeholder="MODELO...">
                                    </div>                                         
                                </div>
                                <div class="col-md-2">
                                    <label for="filter_retirado" class="margin-r pointer">RETIRO</label>
                                    <label class="el-switch el-switch-sm">
                                        <input type="checkbox" id="filter_retirado" value="1" >
                                        <span class="el-switch-style"></span>
                                    </label>
                                </div>                       
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary pull-right" id="actualizar">ACTUALIZAR</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        
                    <button type="button"  id="import_to_excel" class="btn btn-success pull-right" style="margin-bottom: 5px;" >Exportar a Excel</button>
                        <div class="col-md-12">
                            
                            <table class="table " name="t_ticket" id="t_ticket" width="100%">
                                <thead>
                                    <th>Nº TICKET</th>
                                    <!-- <th>Secretaria</th> -->
                                    <th>DEPENDENCIA</th>
                                    <th>USUARIO</th>
                                    <th>TELÉFONO</th>
                                    <!-- <th>Email</th> -->
                                    <th>FECHA</th>
                                    <th>MOTIVO</th>
                                    <!-- <th>Localidad</th> -->
                                    <th>ENCARGADO</th>
<!--                                     <th>Participante</th> -->
                                    <th>ESTADO</th>
                                    <!-- <th>Comentario Interno</th> -->
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="col-md-12">
                            <input type="button" class="btn btn-primary pull-left" name="to_txt" id="to_txt" value="DESCARGAR TXT">
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <form name="form_details" id="form_details" autocomplete="off">
                    <div class="modal" id="modal_detail">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">                                    
                                    <h3 class="modal-title pull-left" style="width: 90%;">Detalle Ticket Nº <span id="detail_ticket"></span> | <span>Creado por: <label id="detail_creador"></label></span></h3>
                                    <div class="col-md-12 row">
                                        <nav>
                                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">TICKET</a>
                                                <a class="nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">USUARIO</a>
                                                <a class="nav-link" id="nav-files-tab" data-toggle="tab" href="#nav-files" role="tab" aria-controls="nav-files" aria-selected="false">ARCHIVOS</a>
                                                <a class="nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">EQUIPO</a>                                                
                                            </div>
                                        </nav>
                                    </div>  
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>                                
                                </div>                                
                                <div class="modal-body row">                                                                                                      
                                    <div class="tab-content" id="nav-tabContent">
                                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">FECHA INCIO:</h2>
                                                    <label class="detail" id="detail_fecha"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">FECHA ASIGNADO:</h2>
                                                    <label class="detail" id="detail_asignado"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">FECHA FINALIZADO:</h2>
                                                    <div class="col-md-12">
                                                        <?php
                                                        // if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){
                                                        //     echo '<div class=\'col-md-12\'>
                                                        //         <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                                        //     </div>';
                                                        // }
                                                    ?>
                                                    <label class="detail" id="detail_finalizado"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">SECRETARIA:</h2>
                                                    <label class="detail" id="detail_secretaria"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">DEPENDENCIA:</h2>
                                                    <label class="detail" id="detail_dependencia"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">LOCALIDAD:</h2>
                                                    <label class="detail" id="detail_localidad"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">MOTIVO:</h2>
                                                    <label class="detail" id="detail_motivo"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">RESPONSABLE:</h2>
                                                    <label class="detail" id="detail_responsable"></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">ESTADO:</h2>
                                                    <label class="detail" id="detail_estado"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">ENCARGADO:</h2>
                                                    <label class="detail" id="detail_encargado"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">PRIORIDAD:</h2>
                                                    <select class="form-control" id="detail_prioridad" name="detail_prioridad">
                                                        <option value="2">NORMAL</option>
                                                        <option value="1">URGENTE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">PARTICIPANTES:</h2>
                                                    <label class="detail" id="detail_participante"></label>
                                                </div>                                                
                                            </div>
                                            <div class="col-md-12">
                                                <div class="col-md-6 form-group">
                                                    <h2 class="page-title-md">COMENTARIO INTERNO:</h2>
                                                    <textarea class="form-control" rows="5" name="detail_comentario" id="detail_comentario"></textarea>
                                                </div>
                                                <div class="col-md-6 form-group">
                                                    <h2 class="page-title-md">COMENTARIO TECNICO:</h2>
                                                    <textarea class="form-control" rows="5" name="detail_comentario_tecnico" id="detail_comentario_tecnico"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">NOMBRE:</h2>
                                                    <label class="detail" id="detail_nombre"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">LEGAJO/DNI:</h2>
                                                    <label class="detail" id="detail_legajo"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">SECRETARIA:</h2>
                                                    <label class="detail" id="detail_user_secretaria"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">DEPENDENCIA:</h2>
                                                    <label class="detail" id="detail_user_dependencia"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">TELÉFONO:</h2>
                                                    <label class="detail" id="detail_telefono"></label>
                                                </div>
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">MAIL:</h2>
                                                    <label class="detail" id="detail_mail"></label>
                                                </div>
                                            </div> 
                                            <div class="col-md-12">
                                                <h2 class="page-title-md">HISTORIAL DEL USUARIO:</h2>
                                                <div class="col-md-12 table-responsive">
                                                    <table class="table table-hover table-responsive" id="tb_user_history" style="width: 100%;">
                                                        <thead>
                                                            <th>TICKET</th>
                                                            <th>MOTIVO</th>
                                                            <th>ENCARGADO</th>
                                                            <th>COM. INTERNO</th>
                                                            <th>COM. TECNICO</th>
                                                            <th>F. INICIO</th>
                                                            <th>F. ASIGNADO</th>
                                                            <th>F. FIN</th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-files" role="tabpanel" aria-labelledby="nav-files-tab">
                                            <div class="col-md-12">
                                                <div class="col-md-4 form-group">
                                                    <h2 class="page-title-md">ARCHIVOS:</h2>
                                                    <label class="" id="detail_archivo"></label>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="">SUBIR ARCHIVOS:</label>
                                                    <div id="dropzoneDiv">
                                                        <div id="dropzone" class="dropzone" style="width: 100%;">
                                                            <input type="file" multiple="multiple" class="dz-hidden-input" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.psd,.ai" style="visibility: hidden; position: absolute;">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                                            <div class="hide" id="details_ingreso_pc">
                                                <div class="col-md-12">
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">TIPO DE EQUIPO:</h2>
                                                        <label class="detail" id="detail_equipo"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">Nº PATRIMONIO:</h2>
                                                        <label class="detail" id="detail_patrimonio"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">Nº EQUIPO:</h2>
                                                        <label class="detail" id="detail_nroequipo"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">MARCA:</h2>
                                                        <label class="detail" id="detail_marca"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">MODELO:</h2>
                                                        <label class="detail" id="detail_modelo"></label>
                                                    </div>
                                                </div>
                                                <div class="hide col-md-12" id="div_detail_pc">
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">PLACA MADRE:</h2>
                                                        <label class="detail" id="detail_mother"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">PROCESADOR:</h2>
                                                        <label class="detail" id="detail_procesador"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">SISTEMA OPERATIVO:</h2>
                                                        <label class="detail" id="detail_sistema"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">RAM:</h2>
                                                        <label class="detail" id="detail_ram"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">DISCO:</h2>
                                                        <label class="detail" id="detail_disc"></label>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">FECHA DE RETIRO:</h2>
                                                        <label class="detail" id="detail_fecha_retiro"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">LISTO PARA RETIRO:</h2>
                                                        <input type="checkbox" class="pointer" name="detail_btn_retiro_listo" id="detail_btn_retiro_listo" value="1">
                                                        <label class="pointer" for="detail_btn_retiro_listo">SI</label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <h2 class="page-title-md">RETIRADO POR:</h2>
                                                        <input type="text" class="form-control search detail" name="detail_retirado" id="detail_retirado">
                                                    </div>
                                                    <div class="col-md-8 form-group">
                                                        <h2 class="page-title-md">FALLA TECNICA:</h2>
                                                        <label class="detail" id="detail_falla_tecnica"></label>
                                                    </div>
                                                    <div class="col-md-4 form-group">
                                                        <!-- <h2 class="page-title-md">ACTUALIZAD DATOS:</h2> -->
                                                        <button type="button" class="btn btn-primary" id="actualizar_equipo">ACTUALIZAR DATOS</button>
                                                    </div>
                                                    <!-- <div class="col-md-2 form-group">
                                                        <h2 class="page-title-md">FALLA TECNICA:</h2>
                                                        <label class="detail" id="detail_falla_tecnica"></label>
                                                    </div> -->
                                                </div>
                                                <div class="col-md-12">
                                                    <h2 class="page-title-md">HISTORIAL DEL EQUIPO:</h2>
                                                    <div class="col-md-12">
                                                        <table class="table" id="tb_equipo_history" style="width: 100%;">
                                                            <thead>
                                                                <th>TICKET</th>
                                                                <th>MOTIVO</th>
                                                                <th>ENCARGADO</th>
                                                                <th>FALLA EQUIPO</th>
                                                                <th>COM. INTERNO</th>
                                                                <th>COM. TECNICO</th>
                                                                <th>F. INICIO</th>
                                                                <th>F. ASIGNADO</th>
                                                                <th>F. FIN</th>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                  
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="guardar" id="guardar" value="Guardar Cambios">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <section>
                <form name="form_participante" id="form_participante" autocomplete="off">
                    <div class="modal" id="modal_participante">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Agregar Participante</h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <?php 
                                            $checkbox = '';
                                            foreach ($Ticket->tecnico as $key => $value) {
                                                $checkbox .= '<div class=\'form-group\'>';
                                                $checkbox .= '<input type=\'checkbox\' name=\'participante_'.$value["Id"].'\' id=\'participante_'.$value["Id"].'\' value=\''.$value["Id"].'\'>';
                                                $checkbox .= '<label for=\'participante_'.$value["Id"].'\'>'.$value["Name"].' '.$value["LastName"].'</label>';
                                                $checkbox .= '</div>';   
                                            }
                                            echo $checkbox;
                                        ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="agregar" id="agregar" value="Agregar participantes">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <section>
                <form name="form_equipo" id="form_equipo" autocomplete="off">
                    <div class="modal" id="modal_equipo">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">ACTUALIZAR DATOS EQUIPO</h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <div class="form-group col-md-4">
                                        <label>Nº EQUIPO</label>
                                        <input type="number" class="form-control search required" name="interno_ingreso" id="interno_ingreso" placeholder="Nº interno" required disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Nº PATRIMONIO</label>
                                        <input type="number" class="form-control search only-number" name="patrimonio_ingreso" id="patrimonio_ingreso" placeholder="Nº de patrimonio">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>TIPO:</label>
                                        <select class="form-control required" id="equipo_ingreso" name="equipo_ingreso" required disabled>
                                            <?php echo $optionTipoEquipos; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>MARCA:</label>
                                        <input type="text" class="form-control" name="marca_ingreso" id="marca_ingreso" placeholder="Marca...">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>MODELO:</label>
                                        <input type="text" class="form-control" name="modelo_ingreso" id="modelo_ingreso" placeholder="Modelo...">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>PLACA MADRE:</label>
                                        <select name="mother_ingreso" id="mother_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listMotherBoards as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cModelo'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>PROCESADOR:</label>
                                        <select name="procesador_ingreso" id="procesador_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listProcesadores as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cTipo'].'-'.$value['cModelo'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>SISTEMA OPERATIVO:</label>
                                        <select name="so_ingreso" id="so_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listSO as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cNombre'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>BITS S.O:</label>
                                        <select name="bits_so_ingreso" id="bits_so_ingreso" class="form-control" required>
                                        <option value="" disabled selected>SIN DEFINIR</option>
                                            <option value="64">64</option>
                                            <option value="32">32</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>RAM:</label>
                                        <select name="ram_ingreso" id="ram_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                            <?php 
                                                for ($i=1; $i <= 24; $i++) { 
                                                    echo '<option value=\''.$i.'\'>'.$i.' GB</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>TIPO DE DISCO:</label>
                                        <select name="tipo_disco_ingreso" id="tipo_disco_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listDiscos as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Type'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>TAMAÑO DE DISCO:</label>
                                        <select name="cantidad_disco_ingreso" id="cantidad_disco_ingreso" class="form-control" required>
                                            <option value="" disabled selected>SIN DEFINIR</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="" id="" value="Actualizar equipo">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/autocomplete.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/xls-export.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="js/libs/dropzone/dropzone.js"></script>
    <script type="text/javascript">
        const ListTecnico = <?php echo json_encode($Ticket->tecnico); ?>;
        const Discos = <?php echo json_encode($listDiscos); ?>;

        var ticketList = <?php echo json_encode($Ticket->getTicket()); ?>;
        var idselectedTicket = 0;
        
        
        autocomplete(document.getElementById('detail_retirado'), 'Usuarios', false);

        $('#modal_participante').on('hidden.bs.modal', function (e) {
            $("#form_participante")[0].reset();
            $('#modal_detail').modal('show');
        });
        $('#modal_equipo').on('hidden.bs.modal', function (e) {
            $('#modal_detail').modal('show');
        });
        $('#modal_detail').on('hidden.bs.modal', function (e) {
            $('#nav-home-tab').tab('show');
        });

        $(document).on('change', '.tecnico', function(e){
            $tr = $(this).parents("tr");
            
            if($(this).val() != 0){
                ticket = $(this).parents("tr")[0].id;
                 $.ajax({
                     type: "POST",
                     url: "controller/",
                     data: "pag="+document.title+"&tipo=t&tec="+$(this).val()+'&ticket='+ticket,
                     dataType: "html",
                 })
                 .fail(function(data){
                     mensaje('fail','Error Peticion ajax');
                 })
                 .done(function(data){
                     response = JSON.parse(data);
                    switch(response.Status){
                        case 'Success':
                            mensaje('okey','Se asigno un tecnico');
                            $("#"+$tr.find('.tecnico')[0].id+" option[value='0']").remove();
                            $("#"+$tr.find('.estado')[0].id+" option[value='2']").prop("selected",true);
                            $("#"+$tr.find('.estado')[0].id+" option[value='1']").remove();
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo asignar al tecnico');
                        break;
                    }
                 });
             }
        });
        
        $(document).on('change', '.estado', function(e){
            $tr = $(this).parents("tr");
            let ticket = $(this).parents("tr")[0].id;
            let ready = true;
            let equipoComplete = false;
            let isPc = false;
            if($("#"+$tr.find('.tecnico')[0].id+"").val() != 0){
                $.each(ticketList, function(i,t){
                    if(ticket == t.IdTicket && t.Equipo.Intern != null){
                        ready = (t.RetiradoPor == null && t.FechaRetiro == null) ? false : true;
                        equipoComplete = t.EquipoComplete;
                        isPc = (t.Equipo.IdEquipo != null && t.Equipo.Type == 1) ? true : false;
                    }
                });
                if(!ready){
                    $("#estado_"+ticket+" option[value=\"2\"]").prop("selected",true);
                    swal('Atencion','ANTES DE FINALIZAR ESTE TICKET TIENE QUE ESPECIFICAR QUIEN RETIRA EL EQUIPO CORRESPONDIENTE','warning');
                    return false;
                }
                if(!equipoComplete && isPc){
                    idselectedTicket = ticket;
                    showFormEquipo(ticket);
                    $('#modal_equipo').modal('show');
                    $("#estado_"+ticket+" option[value=\"2\"]").prop("selected",true);
                    return false;
                }
                if($(this).val() != 0){
                    swal({
                      title: "¡Finalizando Ticket!",
                      text: "¿Seguro que desea dar como finalizado a este ticket?",
                      icon: "warning",
                      buttons: {
                        cancel: "No",
                        Si: true,
                      },
                    })
                    .then((willDelete) => {
                        if (willDelete) {
                            state = $(this).val();
                            $.ajax({
                                type: "POST",
                                url: "controller/",
                                data: "pag="+document.title+"&tipo=s&state="+state+'&ticket='+ticket,
                                dataType: "html",
                            })
                            .fail(function(data){
                                mensaje('fail','Error Peticion ajax');
                            })
                            .done(function(data){
                                response = JSON.parse(data);
                                switch(response.Status){
                                    case 'Success':
                                        $("#"+$tr.find('.tecnico')[0].id+"").prop("disabled", true);
                                        mensaje('okey','Se actualizo el estado');

                                        ticket = 0;
                                        $($tr).addClass('finalizado');
                                        $('#estado_'+ticket+' option[value="2"]').remove();
                                        
                                        DataTable.row($tr).remove().draw();
                                    break;
                                    case 'Error':
                                        mensaje('fail','No se pudo cambiar el estado');
                                    break;
                                    case 'Invalid State':
                                        mensaje('fail','Por favor seleccione un estado valido');
                                    break;
                                }
                            });
                        }else{
                            $("#estado_"+ticket+" option[value=\"2\"]").prop("selected",true);
                        }
                    });
                }
            }else{
                $("#"+$(this)[0].id+" option[value='1']").prop("selected",true);
                mensaje('fail','Tiene que seleccionar un tecnico');
            }
        });

        $(document).on('dblclick', '#t_ticket tbody tr',async function(e){
            idselectedTicket = $(this)[0].id;

            await setTicket(idselectedTicket);
            $('#modal_detail').modal('show');
        });

        async function setTicket(idT){
            await showmodal();
            let t = ticketList.find(t => t.IdTicket == idT);
            let disabled = (t.Estado == 3) ? 'disabled' : '';

            $('#detail_ticket').html(t.Codigo);
            $('#detail_secretaria').html(t.Secretaria);
            $('#detail_dependencia').html(t.Dependencia);
            $('#detail_localidad').html(t.Localidad);
            $('#detail_responsable').html(t.Usuario);

            // USUARIO
            $('#detail_nombre').html(t.UserName);
            $('#detail_legajo').html(t.Legajo);
            $('#detail_user_secretaria').html(t.Secretaria);
            $('#detail_user_dependencia').html(t.Dependencia);
            $('#detail_telefono').html(t.Telefono);
            $('#detail_mail').html(t.Email);

            //HISTORIAL DE USUARIO
            $('#tb_user_history tbody').html('');

            let userHistory = await getHistoryOf('hu',t.Legajo);
            $.each(userHistory, function(i,h){
                let tr = '<tr>'+
                    '<td>'+h.Codigo+'</td>'+
                    '<td>'+h.Motivo+'</td>'+
                    '<td>'+h.Encargado+'</td>'+
                    '<td><textarea disabled>'+h.ComInterno+'</textarea></td>'+
                    '<td><textarea disabled>'+h.ComTecnico+'</textarea></td>'+
                    '<td>'+h.Inicio+'</td>'+
                    '<td>'+h.Asignado+'</td>'+
                    '<td>'+h.Fin+'</td>'+
                '</tr>';
                $('#tb_user_history tbody').append(tr);
            });
            
            $('#detail_fecha').html(t.Fecha_Alta);
            $('#detail_creador').html(t.Creador);
            $('#detail_motivo').html(t.Motivo);
            $('#detail_encargado').html((t.Encargado.trim() == '') ? 'SIN ASIGNAR' : t.Encargado);
            $('#detail_comentario').html(t.Comentario_Interno);
            $('#detail_comentario_tecnico').html(t.Comentario_Tecnico);
            $("#detail_prioridad option[value=\""+t.Prioridad+"\"]").prop("selected",true);
            $('#detail_asignado').html((t.Fecha_Toma == '') ? 'SIN ASIGNAR' : t.Fecha_Toma);
            $('#detail_finalizado').html((t.Fecha_Finalizado == '') ? 'SIN FINALIZAR' : t.Fecha_Finalizado);
            $('#detail_archivo').html(t.Archivos);

            if(t.Equipo.Intern != null){
                $('#detail_patrimonio').html(t.Equipo.Patrimony);
                $('#detail_nroequipo').html(t.Equipo.Intern);
                $('#detail_equipo').html(t.Equipo.TypeName);
                $('#detail_marca').html(t.Equipo.Brand);
                $('#detail_modelo').html(t.Equipo.Model);
                if(t.Equipo.Type == 1){
                    $('#detail_mother').html(t.Equipo.Mother);
                    $('#detail_procesador').html(t.Equipo.Procesador);
                    $('#detail_sistema').html(t.Equipo.So+' '+t.Equipo.BitsSo+' BITS');
                    $('#detail_ram').html(t.Equipo.Ram + 'GB');

                    let capacidad = t.Equipo.DiscCapacity;
                    let disco = (t.Equipo.IdTypeDisc == 1) ? 'HDD' : 'SDD';                    
                    capacidad = (capacidad >= 1000) ? capacidad.substring(0,1)+' TB' : capacidad+' GB';
                    console.log(disco, capacidad)
                    $('#detail_disc').html(disco+' '+capacidad);

                    $('#div_detail_pc').removeClass('hide');
                }else{
                    $('#div_detail_pc').addClass('hide');
                }
                
                $('#detail_falla_tecnica').html(t.TecnicFailure);
                $('#detail_retirado').val(t.RetiradoPor);
                $('#detail_fecha_retiro').html((t.FechaRetiro != '') ? 'SIN RETIRAR' : t.FechaRetiro);
                if(t.ListoParaRetiro != null || t.ListoParaRetiro == 1){
                    $('#detail_btn_retiro_listo').prop("checked", true);
                    $('#detail_btn_retiro_listo').attr("disabled", true);
                }else{
                    $('#detail_btn_retiro_listo').removeAttr("disabled");
                    $('#detail_btn_retiro_nolisto').prop("checked", true);
                }
                if(t.RetiradoPor == null){
                    $('#detail_retirado').removeAttr("disabled", true);
                }else{
                    $('#detail_retirado').attr("disabled", true);
                }

                //HISTORIAL DE EQUIPO
                $('#tb_equipo_history tbody').html('');

                let equipoHistory = await getHistoryOf('he',t.IdEquipo);
                console.log(equipoHistory)
                $.each(equipoHistory, function(i,h){
                    let tr = '<tr>'+
                        '<td>'+h.Codigo+'</td>'+
                        '<td>'+h.Motivo+'</td>'+
                        '<td>'+h.Encargado+'</td>'+
                        '<td>'+h.FallaEquipo+'</td>'+
                        '<td>'+h.ComInterno+'</td>'+
                        '<td>'+h.ComTecnico+'</td>'+
                        '<td>'+h.Inicio+'</td>'+
                        '<td>'+h.Asignado+'</td>'+
                        '<td>'+h.Fin+'</td>'+
                    '</tr>';
                    $('#tb_equipo_history tbody').append(tr);
                });

                $('#details_ingreso_pc').removeClass('hide');
            }else{
                $('#details_ingreso_pc').addClass('hide');
            }
                
            let estado = '';
            switch(t.Estado){
                case '1': estado = 'PENDIENTE'; break;
                case '2': estado = 'EN CURSO'; break;
                case '3': estado = 'FINALIZADO'; break;
            }
            
            $('#detail_estado').html(estado);

            if(disabled == 'disabled'){
                $('#detail_comentario').attr('disabled',true);
                $('#detail_comentario_tecnico').attr('disabled',true);
                $('#guardar').attr('disabled',true);
            }else{
                $('#detail_comentario').removeAttr('disabled');
                    $('#detail_comentario_tecnico').removeAttr('disabled');
                $('#guardar').removeAttr('disabled');
            }
            let participante = '<table id=\'table_participantes'+t.IdTicket+'\'>';
            $.each(t.Participantes, function(k,p){
                participante += '<tr><td><label for=\'eliminar_'+p.Id+'\'>'+p.Name+' '+p.LastName+'</label></td><td><button type=\'button\' id=\'eliminar_'+p.Id+'\' class=\'btn btn-danger eliminar\' '+disabled+'>Eliminar</button></td></tr>';
            });
            participante += '</table>';
            participante += '<button type=\'button\' class=\'agregar_tecnico btn btn-primary\' id=\'agregar_tecnico_'+t.idTicket+'\' '+disabled+'>Agregar</button>';
            $('#detail_participante').html(participante);

            $('#loading').modal('hide');
        }
    </script>
    <script src="js/solicitudes.js"></script>
    <script src="js/toTxt.js"></script>
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