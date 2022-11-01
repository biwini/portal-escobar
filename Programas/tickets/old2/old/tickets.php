<?php
require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"]) && $_SESSION['TICKETS'] == 1){
                include 'controller/ticketController.php';
                include 'controller/motivoController.php';

                $Ticket = new ticket();
                $Ticket->getTecnico();
                $Ticket->getUsers();
                
                $Motivo = new motivo();
                $Motivo->getMotivo();

                $optionMotivo = '';

                foreach ($Motivo->listMotivo as $key => $value) {
                    $optionMotivo .= '<option value=\''.$value['Id'].'\'>'.$value['Motivo'].'</option>';
                }
                // $Ticket->getTicket();
                // $Ticket->getTecnico();
                // $_SESSION['TICKETS'] = 1;
                // var_dump($Ticket->listTicket);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Solicitudes</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
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
                <h1 class="page-title"><span class="icon-history"></span> LISTA DE TICKETS</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px;">
                            <div class="content-filter">
                                <ul class="list-inline" id="filter-content">
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="1" name="" id="filter_pendiente" checked><label class="pointer lbl-pendiente" for="filter_pendiente"> Pendientes</label></li>
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="2" name="" id="filter_encurso" checked><label class="pointer lbl-en-proceso" for="filter_encurso"> En Curso</label></li>
                                    <li class="list-inline-item"><input type="checkbox" class="pointer" value="3" name="" id="filter_finalizado" ><label class="pointer lbl-finalizado" for="filter_finalizado"> Finalizados</label></li>
                                    <li class="list-inline-item"><label for="date_since">Desde</label><input type="date" class="btn btn-default" name="date_since" id="date_since" max="<?php echo date("Y-m-d"); ?>"></li>
                                    <li class="list-inline-item"><label for="date_until">Hasta</label><input type="date" class="btn btn-default" name="date_until" id="date_until" max="<?php echo date("Y-m-d"); ?>"></li>
                                    <li class="list-inline-item"><input type="button" class="btn btn-primary " name="" id="filter_button" value="Filtrar"></li>
                                    <li class="list-inline-item">
                                        <select class="form-select pointer" id="filter_user">
                                            <option value="0">MOSTRAR TODOS</option>
                                            <?php 
                                                foreach ($Ticket->tecnico as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].' '.$value['LastName'].'</option>';  
                                                }
                                            ?>
                                            <option value="SIN_ASIGNAR">SIN ASIGNAR</option>
                                        </select>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12">
                            <table class="table " name="t_ticket" id="t_ticket" width="100%">
                                <thead>
                                    <th>Nº Ticket</th>
                                    <!-- <th>Secretaria</th> -->
                                    <th>Dependencia</th>
                                    <th>Nombre</th>
                                    <th>Telefono</th>
                                    <!-- <th>Email</th> -->
                                    <th>Fecha</th>
                                    <th>Motivo</th>
                                    <!-- <th>Localidad</th> -->
                                    <th>Encargado</th>
<!--                                     <th>Participante</th> -->
                                    <th>Estado</th>
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
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title pull-left" style="width: 90%;">Detalle Ticket Nº <span id="detail_ticket"></span> | <span>Creado por: <label id="detail_creador"></label></span></h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <!-- <div class="col-md-12 form-group">
                                        <label class="col-md-6">Creado por:</label>
                                        <label class="col-md-6" id="detail_creador"></label>
                                    </div> -->
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Secretaria:</label>
                                        <label class="col-md-6" id="detail_secretaria"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Dependencia:</label>
                                        <label class="col-md-6" id="detail_dependencia"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Localidad:</label>
                                        <label class="col-md-6" id="detail_localidad"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Responsable:</label>
                                        <label class="col-md-6" id="detail_responsable"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Telefono:</label>
                                        <label class="col-md-6" id="detail_telefono"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Mail:</label>
                                        <label class="col-md-6" id="detail_mail"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Fecha:</label>
                                        <label class="col-md-6" id="detail_fecha"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Motivo:</label>
                                        <div class="col-md-6">
                                            <label class="" id="detail_motivo"></label>
                                            <!-- <button type="button" class="btn btn-primary" id="add_motivo">Agregar</button> -->
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Archivo:</label>
                                        <div class="col-md-6">
                                            <label class="" id="detail_archivo"></label>
                                            <!-- <button type="button" class="btn btn-primary" id="add_motivo">Agregar</button> -->
                                        </div>
                                    </div>
                                    <!-- --------------------------- -->
                                    <div class="hide" id="details_ingreso_pc">
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Nº Patrimonio:</label>
                                            <div class="col-md-6">
                                                <label class="" id="detail_patrimonio"></label>                                        
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Nº Equipo:</label>
                                            <div class="col-md-6">
                                                <label class="" id="detail_nroequipo"></label>                                        
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Equipo:</label>
                                            <div class="col-md-6">
                                                <label class="" id="detail_equipo"></label>                                        
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Falla_tecnica:</label>
                                            <div class="col-md-6">
                                                <label class="" id="detail_falla_tecnica"></label>                                        
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Listo Para Retiro:</label>
                                            <div class="col-md-6">
                                                <input type="checkbox" class="pointer" name="detail_btn_retiro_listo" id="detail_btn_retiro_listo" value="1">
                                                <label class="pointer" for="detail_btn_retiro_listo">SI</label>
                                                <!-- <button class="btn btn-warning col-md-8" id="asd">Lista para Retirar</button>  -->                                   
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Retirado Por:</label>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control search" name="detail_retirado" id="detail_retirado">                                      
                                            </div>
                                        </div>
                                        <div class="col-md-12 form-group">
                                            <label class="col-md-6">Fecha Retiro:</label>
                                            <div class="col-md-6">
                                                <label class="" id="detail_fecha_retiro"></label>                                    
                                            </div>
                                        </div>
                                    </div>
                                    <!-- --------------------------- -->
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Encargado:</label>
                                        <label class="col-md-6" id="detail_encargado"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Participantes:</label>
                                        <div class="col-md-6">
                                            <label id="detail_participante"></label>
                                        <?php 
                                            // $checkbox = '';
                                            // foreach ($Ticket->tecnico as $key => $value) {
                                            //     $checkbox .= '<div class=\'form-group\'>';
                                            //     $checkbox .= '<input type=\'checkbox\' name=\'participante_'.$value["Id"].'\' id=\'participante_'.$value["Id"].'\' value=\''.$value["Id"].'\'>';
                                            //     $checkbox .= '<label for=\'participante_'.$value["Id"].'\'>'.$value["Name"].' '.$value["LastName"].'</label>';
                                            //     $checkbox .= '</div>';   
                                            // }
                                            // echo $checkbox;
                                        ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Estado:</label>
                                        <div class="col-md-6">
                                            <label class="col-md-6" id="detail_estado"></label>
                                            <input type="button" class="btn btn-warning col-md-6" id="pausa" value="Pusar">
                                            <div class="col-md-12">
                                                <label id="user_pausado"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Comentario Interno:</label>
                                        <textarea class="col-md-6 btn btn-default" name="detail_comentario" id="detail_comentario"></textarea>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Comentario Tecnico:</label>
                                        <textarea class="col-md-6 btn btn-default" name="detail_comentario_tecnico" id="detail_comentario_tecnico"></textarea>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label for="detail_prioridad" class="col-md-6">Prioridad:</label>
                                        <select class=" col-md-6 pull-right" id="detail_prioridad" name="detail_prioridad">
                                            <option value="2">NORMAL</option>
                                            <option value="1">URGENTE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Fecha Asignado:</label>
                                        <label class="col-md-6" id="detail_asignado"></label>
                                    </div>
                                    <div class="col-md-12 form-group">
                                        <label class="col-md-6">Fecha Finalizado:</label>
                                        <div class="col-md-6">
                                            <?php
                                            if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){
                                                echo '<div class=\'col-md-12\'>
                                                    <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                                                </div>';
                                            }
                                        ?>
                                        <label class="col-md-12" id="detail_finalizado"></label>
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
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script src="js/toTxt.js"></script>
    <script src="js/sweetalert.min.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
        var DataTable;
        var ListTickets = [];
        var ListDependencia = [];
        var ListSecretaria = <?php echo json_encode($Ticket->listSecretary); ?>;
        const ListTecnico = <?php echo json_encode($Ticket->tecnico); ?>;

        var ListUser = <?php echo json_encode($Ticket->user); ?>;
        var inputSearch = document.getElementsByClassName("search")[0].id;
        autocomplete(document.getElementById(inputSearch), ListUser);

        $.each(ListSecretaria, function(i,s){
            ListDependencia.push(s.Dependences)
        });
        ListDependencia = ListDependencia[0];
        
        $(document).ready(function(){
            displayDataTable();
            getTicket();
        });
        // -------------------------------- FILTROS --------------------------------------------
        var SelectedFilters = "1,2";
        var FilterUser = "";
        $('#filter_button').click(function(){
            SelectedFilters = "";
            $("#filter-content input[type=checkbox]:checked").each(function(){
                SelectedFilters = SelectedFilters + this.value+",";
            });
            if(SelectedFilters != ""){
                FilterUser = $('#filter_user').val();
                getTicket();
            }
        });
        $('#filter_user').change(function(){
            SelectedFilters = "";
            $("#filter-content input[type=checkbox]:checked").each(function(){
                SelectedFilters = SelectedFilters + this.value+",";
            });
            if(SelectedFilters != ""){
                FilterUser = $('#filter_user').val();
                getTicket();
            }
        });
        // -------------------------------- FIN FILTROS -----------------------------------------
        $('#modal_participante').on('hidden.bs.modal', function (e) {
            $("#form_participante")[0].reset();
            $('#modal_detail').modal('show');
        });
        $('#modal_motivo').on('hidden.bs.modal', function (e) {
            $('#modal_detail').modal('show');
        });
        //console.log(ListTickets)
        $(window).on('scroll',function(){
            if ($(window).scrollTop()) {
                $('nav').addClass('affix');
            }
            else{
                $('nav').removeClass('affix'); 
            }
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
                                // ListTickets = response;
                                mensaje('okey','Se asigno un tecnico');
                                $("#"+$tr.find('.tecnico')[0].id+" option[value='0']").remove();
                                $("#"+$tr.find('.estado')[0].id+" option[value='2']").prop("selected",true);
                                $("#"+$tr.find('.estado')[0].id+" option[value='1']").remove();
                                // console.log($tr.find('.estado')[0].id)
                                //getTicket();

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
                $.each(ListTickets, function(i,t){
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
                                        // getTicket();
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
                    console.log(data);
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    response = JSON.parse(data);
                    switch(response.Status){
                        case 'Success':
                            mensaje('okey','Se agregaron participantes');
                            $('#form_participante')[0].reset();
                            $('#modal_participante').modal('hide');
                            getTicket();
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
                    dataType: "html",
                })
                .fail(function(data){
                    console.log(data);
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    response = JSON.parse(data);
                    console.log(response)
                    switch(response.Status){
                        case 'Success':
                            mensaje('okey','Se guardaron los cambios');
                            $('#form_details')[0].reset();
                            $('#modal_detail').modal('hide');
                            getTicket();
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo guardar los cambios');
                        break;
                    }
                });
            }
        });
        var idselectedTicket = 0;
        $(document).on('click', '.agregar_tecnico', function(e){    
            $.each(ListTickets, function(i,t){
                if(idselectedTicket == t.IdTicket){
                    $.each(t.Participantes, function(k,p){
                        $("#participante_"+p.Id).prop("checked",true);
                    });
                }
            });
            $('#modal_detail').modal('hide');
            $('#modal_participante').modal('show');
        });
        $('#actualizar').click(function(){
            getTicket();
        });

        $('#pausa').click(function(){
            ticket = ListTickets.find(element => element.IdTicket == idselectedTicket);
            swal({
              title: "Pausando ticket...",
              text: "¿Seguro que desea pausar este ticket?",
              icon: "warning",
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
                        data: "pag="+document.title+"&tipo=pdt&state="+ticket.Estado+'&ticket='+idselectedTicket,
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','Error Peticion ajax');
                    })
                    .done(function(response){
                        switch(response.Status){
                            case 'Success':
                                mensaje('okey',(ticket.Paused != 1) ? 'Se pauso el ticket' : 'Se reactivo el ticket');
                                $('#pausa').val((ticket.Paused != 1) ? 'REACTIVAR' : 'PAUSAR');
                                getTicket();
                            break;
                            case 'Error':
                                mensaje('fail','No se pudo pausar/reactivar el ticket');
                            break;
                            default:
                                mensaje('fail','No se pudo pausar/reactivar el ticket');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('dblclick', '#t_ticket tbody tr', function(e){
            console.log($(this))
            idselectedTicket = $(this)[0].id;
            setTicket(idselectedTicket);
            $('#modal_detail').modal('show');
        });

        function setTicket(idT){
            console.log(idT);
            $.each(ListTickets, function(i,t){
                var disabled = (t.Estado == 3) ? 'disabled' : '';
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
                    $('#detail_encargado').html(t.Encargado);
                    $('#detail_comentario').html(t.Comentario_Interno);
                    $('#detail_comentario_tecnico').html(t.Comentario_Tecnico);
                    $("#detail_prioridad option[value=\""+t.Prioridad+"\"]").prop("selected",true);
                    $('#detail_asignado').html(t.Fecha_Toma);
                    $('#detail_finalizado').html(t.Fecha_Finalizado);
                    $('#detail_archivo').html(t.Archivos);

                    if(t.NroEquipo != null){
                        $('#detail_patrimonio').html(t.Patrimony);
                        $('#detail_nroequipo').html(t.NroEquipo);
                        $('#detail_equipo').html(t.Equipo);
                        $('#detail_falla_tecnica').html(t.TecnicFailure);
                        $('#detail_retirado').val(t.RetiradoPor);
                        $('#detail_fecha_retiro').html(t.FechaRetiro);
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
                    estado = '';
                    switch(t.Estado){
                        case '1': estado = 'PENDIENTE'; break;
                        case '2': estado = 'EN CURSO'; break;
                        case '3': estado = 'FINALIZADO'; break;
                    }
                    $('#detail_estado').html(estado);

                    if(t.Estado != '3'){
                        isPaused = (t.Paused == 1) ? 'REACTIVAR' : 'PAUSAR';
                        $('#pausa').val(isPaused);

                        if(t.Paused == 1){
                            userPaused = ListTecnico.find(element => element.Id == t.IdPaused);
                            $('#user_pausado').html('Pausado por: '+userPaused.Name);
                        }
                    }

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
                    var participante = '<table id=\'table_participantes'+t.IdTicket+'\'>';
                    $.each(t.Participantes, function(k,p){
                        participante += '<tr><td><label for=\'eliminar_'+p.Id+'\'>'+p.Name+' '+p.LastName+'</label></td><td><button type=\'button\' id=\'eliminar_'+p.Id+'\' class=\'btn btn-danger eliminar\' '+disabled+'>Eliminar</button></td></tr>';
                    });
                    participante += '</table>';
                    participante += '<button type=\'button\' class=\'agregar_tecnico btn btn-primary\' id=\'agregar_tecnico_'+t.idTicket+'\' '+disabled+'>Agregar</button>';
                    $('#detail_participante').html(participante);
                }
            });
        }
        $('#add_motivo').click(function(){
            $('#modal_motivo').modal('show');
            $('#modal_detail').modal('hide');
        });
        var motivo = 0;
        $('.sub_motivo').click(function(){
            var parent = $(this).parent().parent().parent().find('span')[0].innerHTML;
            $('#selected_motivo').text('');
            $('#selected_motivo').text(parent+'/'+$(this).text());
            motivo = $(this)[0].id;
            if($(this).text() == 'OTRO'){
                $('#motivo_otro').removeClass('hide');
                $('#otro').removeAttr('disabled');
                $('#otro').addClass('required');
                $('#selected_motivo').text('');
                $('#selected_motivo').text('MOTIVO/'+$(this).text());
            }else{
                $('#motivo_otro').addClass('hide');
                $('#motivo_otro').removeClass('required');
                $('#otro').attr('disabled',true);
            }
        });
        function getTicket(){
            $.ajax({
                type: "POST",
                url: "controller/",
                data: {'pag':document.title,'tipo':'g','state':SelectedFilters,'user':FilterUser,'date_since': $('#date_since').val(),'date_until': $('#date_until').val()},
                dataType: "html",
            })
            .fail(function(data){
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                ListTickets = JSON.parse(data);
                console.log(ListTickets)
                DataTable.destroy();
                displayDataTable();
                if(idselectedTicket != 0){
                    setTicket(idselectedTicket);
                }
            });
        }
        function displayDataTable(){
            DataTable = $('#t_ticket').DataTable({
                "data": ListTickets,
                "rowId": 'IdTicket',
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "createdRow": function( row, data, dataIndex){
                    console.log(data.Prioridad)
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
                    // { "data": "Email"},
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
                            var tecnicos = '';
                            if(full.IdEncargado == null){
                                tecnicos += '<option value=\'0\'>TECNICO</option>';
                            }
                            var disabled = (full.Estado == 3) ? 'disabled' : '';
                            $.each(ListTecnico, function(i,t){
                                selected = (t.Id == full.IdEncargado && full.IdEncargado != null) ? 'selected' : '';
                                tecnicos += '<option value=\''+t.Id+'\' '+selected+'>'+t.Name+'</option>';
                            });
                            var newSelect = '<select id=\'tecnico_'+full.IdTicket+'\'  class=\"form-select tecnico pointer btn btn-default\" '+disabled+'>'+tecnicos+'</select>';

                            return newSelect;
                        }
                    },
                    { "data": "Estado",
                     "render":function(data, type, full, meta){
                            var e1 = '',e2 = '',e3 = '';
                            switch(full.Estado){
                                case 1: e1 = 'selected'; break;
                                case 2: e2 = 'selected'; break;
                                case 3: e3 = 'selected'; break;
                            }
                            var disabled = (full.Estado == 3) ? 'disabled' : '';
                            var estado = '';
                            if(full.Estado == 1){
                                estado += '<option value=\'1\' '+e1+'>PENDIENTE</option>';
                            }
                            if(full.Estado <= 2){
                                estado += '<option value=\'2\' '+e2+'>EN CURSO</option>';
                            }
                            estado += '<option value=\'3\' '+e3+'>FINALIZADO</option>';
                            var newSelect = '<select id=\'estado_'+full.IdTicket+'\'  class=\"btn btn-default estado pointer\" '+disabled+'>'+estado+'</select>';

                            return newSelect;
                        }
                    },
                ],
            });
        }
    </script>
    <script src="js/ticket.js?v=<?php echo time(); ?>"></script>
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