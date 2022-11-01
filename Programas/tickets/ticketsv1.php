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
                $listEquipos = $Equipo->getEquipo('all');
                $listTiposEquipo = $TipoEquipo->getList();

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <style type="text/css">
        .detail{
            margin-left: 1rem;
        }

        .page-title-md{
            font-size: 1.8rem;
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
                            <div class="col-md-2">
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
                            <div class="col-md-4">
                                <h1 class="page-title-sm"><span class=""></span> EQUIPOS:</h1>
                                <div class="col-md-12">
                                    <div class="form-group col-md-4">
                                        <label class="" for="filter_tipoequipo">TIPO:</label>
                                        <select class="form-control pointer" id="filter_tipoequipo">
                                            <option value="">TODOS</option>
                                            <?php 
                                                foreach ($listTiposEquipo as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Type'].'</option>';  
                                                }
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
                <form name="form_details" id="form_details" autocomplete="off">
                    <div class="modal" id="modal_detail">
                        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title pull-left" style="width: 90%;">Detalle Ticket Nº <span id="detail_ticket"></span> | <span>Creado por: <label id="detail_creador"></label></span></h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>
                                </div>
                                <div class="modal-body">
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
                                            if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){
                                                echo '<div class=\'col-md-12\'>
                                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                                </div>';
                                            }
                                        ?>
                                        <label class="detail" id="detail_finalizado"></label>
                                        </div>
                                        
                                    </div>
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
                                    <div class="col-md-4 form-group">
                                        <h2 class="page-title-md">MOTIVO:</h2>
                                        <label class="detail" id="detail_motivo"></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <h2 class="page-title-md">RESPONSABLE:</h2>
                                        <label class="detail" id="detail_responsable"></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <h2 class="page-title-md">TELÉFONO:</h2>
                                        <label class="detail" id="detail_telefono"></label>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <h2 class="page-title-md">MAIL:</h2>
                                        <label class="detail" id="detail_mail"></label>
                                    </div>
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
                                    <div class="row hide" id="details_ingreso_pc">
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
                                            <div class="col-md-4 form-group">
                                                <h2 class="page-title-md">FALLA TECNICA:</h2>
                                                <label class="detail" id="detail_falla_tecnica"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group">
                                        <h2 class="page-title-md">PARTICIPANTES:</h2>
                                        <label class="detail" id="detail_participante"></label>
                                    </div>
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
                                    <div class="col-md-6 form-group">
                                        <h2 class="page-title-md">COMENTARIO INTERNO:</h2>
                                        <textarea class="form-control" rows="5" name="detail_comentario" id="detail_comentario"></textarea>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <h2 class="page-title-md">COMENTARIO TECNICO:</h2>
                                        <textarea class="form-control" rows="5" name="detail_comentario_tecnico" id="detail_comentario_tecnico"></textarea>
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
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/toTxt.js"></script>
    <script src="js/autocomplete.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script src="js/xls-export.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    <script type="text/javascript">
        const ListTecnico = <?php echo json_encode($Ticket->tecnico); ?>;

        let DataTable;
        let ticketList = <?php echo json_encode($Ticket->getTicket()); ?>;
        let xls = new xlsExport(getExcelData(ticketList), 'Tickets');
        var idselectedTicket = 0;
        var myDropzone;
        
        autocomplete(document.getElementById('detail_retirado'), 'Usuarios', false);

        Dropzone.autoDiscover = false;

        $(document).ready(function(){
            drop = $("div#dropzone").dropzone({     
                autoProcessQueue: false,
                url: url,
                params: {
                     pag: "Ticket",
                     tipo: "ar",
                },
                paramName: 'file',
                clickable: true,
                maxFilesize: 5,
                uploadMultiple: true, 
                maxFiles: 5,
                addRemoveLinks: true,
                acceptedFiles: '.png,.jpg,.pdf,.doc,.txt,.xlsx',
                dictDefaultMessage: 'Da click aquí o arrastra tus archivos y sueltalos aqui.',
                init: function () {
                    myDropzone = this;
                    // Update selector to match your button

                    this.on('sending', function(file, xhr, formData) {
                        // Append all form inputs to the formData Dropzone will POST
                        formData.append('ticket', idselectedTicket);
                        var data = $('#dropzone').serializeArray();
                        $.each(data, function(key, el) {
                            FP.append(el.name, el.value);
                        });
                    });

                    this.on("success", function(file, responseText) {
                        console.log(responseText);
                        myDropzone.removeFile(file);
                    });

                    this.on("error", function(file, responseText) {
                        console.log(responseText);
                        swal('No se cargaron los archivos','error')
                    });
                }
            });
            // JSONToCSVConvertor(ticketList, "Usuarios", true);
            $('#t_ticket thead th').each( function () {
                let title = $(this).text();
                if(title != 'ENCARGADO' && title != 'ESTADO' && title != 'FECHA'){
                    $(this).html(title+ '<div style="width:100%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
                }
            });

            DataTable = $('#t_ticket').DataTable({
                "data": ticketList,
                "rowId": 'IdTicket',
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "bSort" : false,
                "createdRow": function( row, data, dataIndex){
                    switch(data.Estado){
                        case '1': $(row).children().eq(0).addClass('pendiente'); break;
                        case '2': $(row).children().eq(0).addClass('en-proceso'); break;
                        case '3': $(row).children().eq(0).addClass('finalizado'); break;
                    }

                    if(data.Prioridad == 1 && data.Estado != '3'){
                        $(row).children().eq(0).addClass('urgente');
                    }
                },
                "columns":[
                    { "data": "IdTicket",
                     "render":function(data, type, full, meta){
                            return '<div style=\'width:100%;height:100%;text-align:center;font-size:20px;\'><a style="color: black;" href=\'descargar-ticket?ticket='+full.Codigo+'\' target=\'_blank\' rel=\'noreferrer\'> '+full.Codigo+' </a></div>';
                        }
                    },
                    { "data": "Dependencia",
                        "render":function(data, type, full, meta){
                            let dependence = '';
                            let textoTroceado = full.Dependencia.split (" ");

                            for (var i = 1; i <= full.Dependencia.split(' ').length; i++) {
                                dependence += (i % 2 == 0) ? textoTroceado[i - 1]+'<br>' : textoTroceado[i - 1]+' ';
                            }
                            return dependence;
                        }
                    },
                    { "data": "Usuario",
                        "render":function(data, type, full, meta){
                            let usuario = '';
                            let textoTroceado = full.Usuario.split(' ');

                            for (var i = 1; i <= full.Usuario.split(' ').length; i++) {
                                usuario += (i % 2 == 0) ? textoTroceado[i - 1]+'<br>' : textoTroceado[i - 1]+' ';
                            }
                            return usuario;
                        }
                    },
                    { "data": "Telefono"},
                    { "data": "Fecha_Alta"},
                    { "data": "Motivo",
                     "render":function(data, type, full, meta){
                            motivo = full.Motivo.split("/").join("/<br>")
                            if(full.NroEquipo != null){
                                motivo += '-'+full.NroEquipo
                            }
                            return motivo;
                        }
                    },
                    { "data": "Encargado",
                     "render":function(data, type, full, meta){
                            let tecnicos = '';
                            if(full.IdEncargado == null){
                                tecnicos += '<option value=\'0\'>SIN ASIGNAR</option>';
                            }

                            let disabled = (full.Estado == 3) ? 'disabled' : '';
                            $.each(ListTecnico, function(i,t){
                                selected = (t.Id == full.IdEncargado && full.IdEncargado != null) ? 'selected' : '';
                                tecnicos += '<option value=\''+t.Id+'\' '+selected+'>'+t.Name+'</option>';
                            });
                            let newSelect = '<select id=\'tecnico_'+full.IdTicket+'\'  class=\"form-select tecnico pointer btn btn-default\" '+disabled+'>'+tecnicos+'</select>';

                            return newSelect;
                        }
                    },
                    { "data": "Estado",
                     "render":function(data, type, full, meta){
                            let e1 = '',e2 = '',e3 = '';
                            switch(full.Estado){
                                case 1: e1 = 'selected'; break;
                                case 2: e2 = 'selected'; break;
                                case 3: e3 = 'selected'; break;
                            }
                            let disabled = (full.Estado == 3) ? 'disabled' : '';
                            let estado = '';
                            if(full.Estado == 1){
                                estado += '<option value=\'1\' '+e1+'>PENDIENTE</option>';
                            }
                            if(full.Estado <= 2){
                                estado += '<option value=\'2\' '+e2+'>EN CURSO</option>';
                            }
                            estado += '<option value=\'3\' '+e3+'>FINALIZADO</option>';
                            let newSelect = '<select id=\'estado_'+full.IdTicket+'\'  class=\"btn btn-default estado pointer\" '+disabled+'>'+estado+'</select>';

                            return newSelect;
                        }
                    },
                ],
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        let that = this;
                        $( 'input', this.header() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                console.log(this.value)
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });

            // getTicket();
        });

        $('#title_filter').click(function(){
            if($('#row_filter').hasClass('show')){
                $('#title_filter').children('.icon').removeClass('icon-circle-up');
                $('#title_filter').children('.icon').addClass('icon-circle-down');
                $('#row_filter').removeClass('show');
                $('#row_filter').addClass('hide');
            }else{
                $('#title_filter').children('.icon').removeClass('icon-circle-down');
                $('#title_filter').children('.icon').addClass('icon-circle-up');
                $('#row_filter').removeClass('hide');
                $('#row_filter').addClass('show');
            }
        })

        // -------------------------------- FILTROS --------------------------------------------

        $('#actualizar').click(function(){
            updateTicketList(filterPendiente, filterEnCurso, filterFinalizado);
        });
        // -------------------------------- FIN FILTROS -----------------------------------------
        $('#modal_participante').on('hidden.bs.modal', function (e) {
            $("#form_participante")[0].reset();
            $('#modal_detail').modal('show');
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
                     console.log(data);
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
            if($("#"+$tr.find('.tecnico')[0].id+"").val() != 0){
                $.each(ticketList, function(i,t){
                    if(ticket == t.IdTicket && t.NroEquipo != null){
                        ready = (t.RetiradoPor == null && t.FechaRetiro == null) ? false : true;
                    }
                });
                if(!ready){
                    $("#estado_"+ticket+" option[value=\"2\"]").prop("selected",true);
                    swal('Atencion','ANTES DE FINALIZAR ESTE TICKET TIENE QUE ESPECIFICAR QUIEN RETIRA EL EQUIPO CORRESPONDIENTE','warning');
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
                            select = $(this);
                            $.ajax({
                                type: "POST",
                                url: "controller/",
                                data: "pag="+document.title+"&tipo=s&state="+state+'&ticket='+ticket,
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

        $(document).on('click', '.eliminar', function(e){
            $.ajax({
                type: "POST",
                url: "controller/",
                data: "pag="+document.title+"&tipo=dp&ticket="+idselectedTicket+'&participante='+$(this)[0].id.split("_")[1],
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
                        mensaje('okey','Se elimino el participante');
                        getTicket();
                    break;
                    case 'Error':
                        mensaje('fail','No se pudo eliminar participantes');
                    break;
                }
            });

        });
        
        $(document).on('submit', '#form_participante', function(e){
            e.preventDefault();
            var participantes = '';
            $('#form_participante input[type=checkbox]').each(function(){
                if(this.checked) {
                    participantes += $(this).val()+',';
                }
            });
            if(participantes != '' && idselectedTicket != 0){
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data: "pag="+document.title+"&tipo=p&ticket="+idselectedTicket+'&participante='+participantes,
                    dataType: "html",
                })
                .fail(function(data){
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    response = JSON.parse(data);
                    switch(response.Status){
                        case 'Success':
                            mensaje('okey','Se agregaron participantes');
                            $('#form_participante')[0].reset();
                            $('#modal_participante').modal('hide');
                            ticketList.forEach(function(t){
                                if(t.IdTicket == idselectedTicket){
                                    t.Participantes = response.Participantes;
                                }
                            });
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo agregar participantes');
                        break;
                    }
                });
            }
        });

        $(document).on('submit', '#form_details', function(e){
            e.preventDefault();
            if(idselectedTicket != 0){
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data:  $(this).serialize()+"&pag="+document.title+"&tipo=ud&ticket="+idselectedTicket,
                    dataType: "json",
                })
                .fail(function(data){
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    switch(data.Status){
                        case 'Success':
                            mensaje('okey','Se guardaron los cambios');
                            $('#form_details')[0].reset();
                            $('#modal_detail').modal('hide');
                            ticketList.forEach(function(t){
                                if(t.IdTicket == idselectedTicket){
                                    t = data.Ticket;
                                }
                            });
                            $('#dropzone')[0].dropzone.processQueue();
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo guardar los cambios');
                        break;
                    }
                });
            }
        });

        $(document).on('click', '.agregar_tecnico', function(e){    
            $.each(ticketList, function(i,t){
                if(idselectedTicket == t.IdTicket){
                    $.each(t.Participantes, function(k,p){
                        $("#participante_"+p.Id).prop("checked",true);
                    });
                }
            });
            $('#modal_detail').modal('hide');
            $('#modal_participante').modal('show');
        });

        $(document).on('dblclick', '#t_ticket tbody tr', function(e){
            idselectedTicket = $(this)[0].id;

            setTicket(idselectedTicket);
            $('#modal_detail').modal('show');
        });

        function setTicket(idT){
            $.each(ticketList, function(i,t){
                let disabled = (t.Estado == 3) ? 'disabled' : '';
                <?php if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){ ?>
                    disabled = '';
                <?php } ?>;

                if(idT == t.IdTicket){
                    console.log(t);
                    $('#detail_ticket').html(t.Codigo);
                    $('#detail_secretaria').html(t.Secretaria);
                    $('#detail_dependencia').html(t.Dependencia);
                    $('#detail_localidad').html(t.Localidad);
                    $('#detail_responsable').html(t.Usuario);
                    $('#detail_telefono').html(t.Telefono);
                    $('#detail_mail').html(t.Email);
                    $('#detail_fecha').html(t.Fecha_Alta);
                    $('#detail_creador').html(t.Creador);
                    $('#detail_motivo').html(t.Motivo);
                    $('#detail_encargado').html((t.Encargado.trim() == '') ? 'SIN ASIGNAR' : t.Encargado);
                    $('#detail_comentario').html(t.Comentario_Interno);
                    $('#detail_comentario_tecnico').html(t.Comentario_Tecnico);
                    $("#detail_prioridad option[value=\""+t.Prioridad+"\"]").prop("selected",true);
                    $('#detail_asignado').html((t.Fecha_Toma != '') ? 'SIN ASIGNAR' : t.Fecha_Toma);
                    $('#detail_finalizado').html((t.Fecha_Finalizado != '') ? 'SIN FINALIZAR' : t.Fecha_Finalizado);
                    $('#detail_archivo').html(t.Archivos);

                    if(t.NroEquipo != null){
                        $('#detail_patrimonio').html(t.Patrimony);
                        $('#detail_nroequipo').html(t.NroEquipo);
                        $('#detail_equipo').html(t.Equipo);
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

                    <?php if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){ ?>

                        if(t.Fecha_Finalizado != ''){
                            $('#fecha_fin').val(t.Fecha_Finalizado);
                        }else{
                            $('#fecha_fin').val(t.Fecha_Alta);
                        }
                        
                    <?php } ?>

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
                }
            });
        }

        async function updateTicketList(pendiente, curso, finalizado){
            await showmodal();

            ticketList = await getTickets(pendiente, curso, finalizado);
            console.log(ticketList)

            displayTable();
        }

        function getTickets(pendiente, curso, finalizado){
            return new Promise(resolve => { 
                let list;

                resolve(
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {'pag':document.title,'tipo':'g','pendiente':pendiente,'curso':curso, 'finalizado': finalizado},
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','Error Peticion ajax');
                    })
                    .done(function(data){
                        list = data;
                    })
                )
                //resolve(suggestions);
            });
        }

        async function displayTable(modal = true){
            if(modal){
                await showmodal(); 
            }

            let filterTable = ticketList;

            if(filterUrgente !== undefined){
                filterTable = filterTable.filter(t => t.Prioridad == 1);
            }

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(t => t.Secretaria == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(t => t.Dependencia == filterDependence);
            }

            if(filterDateSince !== undefined){
                filterTable = filterTable.filter(t => t.Fecha_Alta >= filterDateSince || filterDateSince <= t.Fecha_Alta);
            }
            if(filterDateUntil !== undefined){
                filterTable = filterTable.filter(t => t.Fecha_Alta <= filterDateUntil+' 23:59:59' || filterDateUntil+' 23:59:59' >= t.Fecha_Alta);
            }

            if(filterAtendido !== undefined){
                filterTable = filterTable.filter(t => t.IdAlta == filterAtendido);
            }
            if(filterEncargado !== undefined){
                if(filterEncargado == 'SIN_ASIGNAR'){
                    filterTable = filterTable.filter(t => t.IdEncargado == null);
                }else{
                    filterTable = filterTable.filter(t => t.IdEncargado == filterEncargado);
                }
            }

            if(filterMotivo !== undefined){
                filterTable = filterTable.filter(t => t.Motivo.split('/')[0] == filterMotivo);
            }
            if(filterSubMotivo !== undefined){
                filterTable = filterTable.filter(t => t.Motivo.split('/')[1] == filterSubMotivo);
            }

            if(filterTypeEquipo !== undefined){
                filterTable = filterTable.filter(t => t.Equipo == filterTypeEquipo);
            }
            if(filterNumEquipo !== undefined){
                filterTable = filterTable.filter(function(t){
                    if(t.NroEquipo != null && t.NroEquipo != ''){
                        return t.NroEquipo.includes(filterNumEquipo)
                    }
                });
            }
            if(filterNumPatrimonio !== undefined){
                filterTable = filterTable.filter(function(t){
                    if(t.Patrimony != null && t.Patrimony != ''){
                        return t.Patrimony.includes(filterNumPatrimonio)
                    }
                });
            }
            if(filterBrand !== undefined){
                filterTable = filterTable.filter(function(t){
                    if(t.Marca != null && t.Marca != ''){
                        return t.Marca.includes(filterBrand)
                    }
                });
            }
            if(filterModel !== undefined){
                filterTable = filterTable.filter(function(t){
                    if(t.Modelo != null && t.Modelo != ''){
                        return t.Modelo.includes(filterModel)
                    }
                });
            }

            xls = new xlsExport(getExcelData(filterTable), 'Tickets');

            DataTable.rows().remove().draw();
            DataTable.rows.add(filterTable);
            DataTable.columns.adjust().draw();

            $('#loading').modal('hide');
        }

        function getExcelData(array){
            let excelData = [];
        
            array.forEach(function(t){
                estado = '';
                switch(t.Estado){
                    case '1': estado = 'PENDIENTE'; break;
                    case '2': estado = 'EN CURSO'; break;
                    case '3': estado = 'FINALIZADO'; break;
                }

                data = {
                    'Ticket': t.Codigo,
                    'Secretaria': t.Secretaria,
                    'Dependencia': t.Dependencia,
                    'Motivo': t.Motivo,
                    'Legajo': t.Legajo,
                    'Usuario': t.UserName,
                    'Mail': t.Email,
                    'Telefono': t.Telefono,
                    'Fecha': t.SimpleDate,
                    'Atendido por': t.Creador,
                    'Encargado': t.Encargado,
                    'Estado': estado
                }

                excelData.push(data);
            });

            return excelData;
        }

        $('#import_to_excel').click(function(){
            xls.exportToXLS('Tickets.xls');
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