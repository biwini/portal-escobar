<?php
    require 'controller/motivoController.php';
    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["TICKETS"]) && $_SESSION['TICKETS'] == 1){
            require 'controller/ticketController.php';
            require 'controller/staticsController.php';

            $Ticket = new ticket();
            $Motivo = new motivo();
            $Statics = new statics();
            
            $listTecnicos = $Ticket->getTecnico();
            $listMotivos = $Motivo->getMotivo();
            $Motivo->getTecnico();

            $ticketsData = $Statics->getTicketsData();

            $optionTecnicos = '';
            $optionMotivo = '';
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
	<title>Tickets - Reportes</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/checkbox-switch.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
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
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-stats-bars"></span> TICKETS - REPORTES</h1>
            </header>
            <section class="page-section">
            <div class="row">
                <div class="col-xl-3 col-sm-3 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100 pendiente">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-vote-yea"></i>
                            </div>
                            <div class="mr-5">
                                <p>PENDIENTES : <label class="pull-right" id="ticket_pendientes"><?php echo $ticketsData['pendientes'] ?></label></p>
                            </div>
                            <div class="card-footer text-white clearfix small z-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100 en-proceso">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-vote-yea"></i>
                            </div>
                            <div class="mr-5">
                                <p>EN CURSO : <label class="pull-right" id="ticket_curso"><?php echo $ticketsData['curso'] ?></label></p>
                            </div>
                            <div class="card-footer text-white clearfix small z-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100 finalizado">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-vote-yea"></i>
                            </div>
                            <div class="mr-5">
                                <p>FINALIZADOS : <label class="pull-right" id="ticket_finalizados"><?php echo $ticketsData['finalizados'] ?></label></p>
                            </div>
                            <div class="card-footer text-white clearfix small z-1">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-3 mb-3">
                    <div class="card text-white bg-primary o-hidden h-100" style="background-color: #007bff!important;">
                        <div class="card-body">
                            <div class="card-body-icon">
                                <i class="fas fa-fw fa-vote-yea"></i>
                            </div>
                            <div class="mr-5">
                                <p>TOTALES : <label class="pull-right" id="ticket_totales"><?php echo $ticketsData['todos'] ?></label></p>
                            </div>
                            <div class="card-footer text-white clearfix small z-1">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                    <h1 class="page-title pointer" id="title_filter"><span class=""></span> FILTRAR POR:<span class="icon pull-right icon-circle-up"></span></h1>
                    <div class="row show" id="row_filter">
                        <div class="col-md-12" style="margin-top: 15px;">                            
                            <div class="col-md-3">
                                <h1 class="page-title-sm"><span class=""></span> SECRETARIAS / DEPENDENCIAS:</h1>
                                <div class="col-md-12">
                                    <label class="" for="filter_secretary">SECRETARIA</label>
                                    <select class="form-control form-group" id="filter_secretary">
                                        <option value="ALL" selected>TODAS LAS SECRETARIAS</option>
                                        <?php 
                                            foreach ($SecretaryList as $key => $value) {
                                                echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                            }
                                        ?>
                                    </select>
                                    <label class="" for="filter_dependencia">DEDENDENCIA</label>
                                    <select class="form-control" id="filter_dependencia">
                                        <option value="ALL" selected>TODAS LAS DEPENDENCIAS</option>
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
                                            <option value="ALL">MOSTRAR TODOS</option>
                                            <?php 
                                                echo $optionTecnicos;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="filter_encargado">ENCARGADO</label>
                                        <select class="form-control pointer" id="filter_encargado">
                                            <option value="ALL">MOSTRAR TODOS</option>
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
                                            <option value="ALL">MOSTRAR TODOS</option>
                                            <?php 
                                                echo $optionMotivo;
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="filter_submotivo">SUB MOTIVO</label>
                                        <select class="form-control pointer" id="filter_submotivo">
                                            <option value="ALL">MOSTRAR TODOS</option>
                                        </select>
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
                <button type="button" class="btn btn-success export_to_excel_extend" style="margin-bottom: 5px;" value="detailTickets">Excel de tickets detallado</button>
                <button type="button" class="btn btn-success export_to_excel_extend" style="margin-bottom: 5px;" value="detailEquipment">Excel de equipos detallado</button>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <label for="ticketsbyweek_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="ticketsbyweek_radio" id="ticketsbyweek_radio1" value="ticketsbyweek" checked>
                                <label for="ticketsbyweek_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="ticketsbyweek_radio" id="ticketsbyweek_radio2" value="ticketsbyweek">
                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px; float:right;" value="ticketsbyweek">Excel Simple</button>
                            </div>
                            
                            <div id="bar_chartContainer_ticketsbyweek" style="height: 400px; width: 100%; display: block"></div>
                            <div id="pie_chartContainer_ticketsbyweek" style="height: 400px; width: 100%; display: none"></div>
                        </div>
                        <div class="row"></div>               
                        <div class="col-md-6">
                            <div class="row">
                                <label for="createdticketbytecnic_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="createdticketbytecnic_radio" id="createdticketbytecnic_radio1" value="createdticketbytecnic" checked>
                                <label for="createdticketbytecnic_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="createdticketbytecnic_radio" id="createdticketbytecnic_radio2" value="createdticketbytecnic">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="createdticketbytecnic">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_createdticketbytecnic" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_createdticketbytecnic" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="row">
                                <label for="asignementticketby_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="asignementticketby_radio" id="asignementticketby_radio1" value="asignementticketby" checked>
                                <label for="asignementticketby_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="asignementticketby_radio" id="asignementticketby_radio2" value="asignementticketby">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="asignementticketby">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_asignementticketby" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_asignementticketby" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="ticketbyzone_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="ticketbyzone_radio" id="ticketbyzone_radio1" value="ticketbyzone" checked>
                                <label for="ticketbyzone_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="ticketbyzone_radio" id="ticketbyzone_radio2" value="ticketbyzone">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="ticketbyzone">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_ticketbyzone" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_ticketbyzone" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="toptenticketsdependency_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="toptenticketsdependency_radio" id="toptenticketsdependency_radio1" value="toptenticketsdependency" checked>
                                <label for="toptenticketsdependency_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="toptenticketsdependency_radio" id="toptenticketsdependency_radio2" value="toptenticketsdependency">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="toptenticketsdependency_radio2">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_toptenticketsdependency" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_toptenticketsdependency" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <label for="averagetimeperticket_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="averagetimeperticket_radio" id="averagetimeperticket_radio1" value="averagetimeperticket" checked>
                                <label for="averagetimeperticket_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="averagetimeperticket_radio" id="averagetimeperticket_radio2" value="averagetimeperticket">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="averagetimeperticket">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_averagetimeperticket" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_averagetimeperticket" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="row"></div>
                        <div class="col-md-4">
                            <div class="row">
                                <label for="equipmententry_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="equipmententry_radio" id="equipmententry_radio1" value="equipmententry" checked>
                                <label for="equipmententry_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="equipmententry_radio" id="equipmententry_radio2" value="equipmententry">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="equipmententry">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_equipmententry" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_equipmententry" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <label for="equipmententrybyticket_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="equipmententrybyticket_radio" id="equipmententrybyticket_radio1" value="equipmententrybyticket" checked>
                                <label for="equipmententrybyticket_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="equipmententrybyticket_radio" id="equipmententrybyticket_radio2" value="equipmententrybyticket">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="equipmententrybyticket">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_equipmententrybyticket" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_equipmententrybyticket" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <label for="equipmentbytecnic_radio1">CANTIDAD</label>
                                <input type="radio" class="bar-chart" name="equipmentbytecnic_radio" id="equipmentbytecnic_radio1" value="equipmentbytecnic" checked>
                                <label for="equipmentbytecnic_radio2">PORCENTAJE</label>
                                <input type="radio" class="radio-chart" name="equipmentbytecnic_radio" id="equipmentbytecnic_radio2" value="equipmentbytecnic">

                                <button type="button" class="btn btn-success export_to_excel_simple" style="margin-bottom: 5px;float:right;" value="equipmentbytecnic">Excel Simple</button>
                            </div>

                            <div id="bar_chartContainer_equipmentbytecnic" style="height: 300px; width: 100%;"></div>
                            <div id="pie_chartContainer_equipmentbytecnic" style="height: 300px; width: 100%; display: none;"></div>
                        </div>
                    </div>
                    
                </div>
            </section>
        </div>
    </main>
    <footer>
        <div style="position: relative;">
            <div class="ui info icon message" id='loading' style="display: none;">
                <i class="notched circle loading icon"></i>
                <div class="content">
                    <div class="header">
                        Cargando...
                    </div>
                    <p>Se estan buscando los registros</p>
                </div>
            </div>
        </div>
    </footer>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script src="js/xls-export.js"></script>
    <script src="js/canvasjs.min.js"></script>
    <script type="text/javascript">

        const Motivos = <?php echo json_encode($listMotivos); ?>;

        let dayTicketsChart, createdTicketByChart, asignementTicketByChart, ticketsByZoneChart, topTenTicketsDependencyChart;
        let averageTimePerTicketChart, equipmentEntryByTicketChart, equipmentEntryChart, equipmentByTecnicChart;

        let dayTicketsChartPie, createdTicketByChartPie, asignementTicketByChartPie, ticketsByZonePie, topTenTicketsDependencyPie;
        let averageTimePerTicketPie, equipmentEntryByTicketPie, equipmentEntryPie, equipmentByTecnicPie;

        let xlsDayTicket = new xlsExport([], 'Tickets por dia');
        let xlsCreatedBy = new xlsExport([], 'Tickets atendidos por tecnico');
        let xlsasignementTicket = new xlsExport([], 'Tickets por tecnico encargado');
        let xlsByZone = new xlsExport([], 'Tickets por zona');
        let xlsTopTen = new xlsExport([], 'Tickets Top 10 Dependencias');
        let xlsAverageTime = new xlsExport([], 'Tickets Tiempo de respuesta');
        let xlsEquipmentByTicket = new xlsExport([], 'Equipo Por Ticket');
        let xlsEquipmentEntry = new xlsExport([], 'Equipos');
        let xlsEquipmentByTecnic = new xlsExport([], 'Equipos reparados por tecnico');

        $(document).ready(function(){
           loadCharts();
        });

        async function displayTable(){
            return false;
        }

        async function loadCharts(){
            await showmodal();

            const ticketsOfWeek = await getChartValues('getAllTicketsOfWeek');
            const createdTicketByTecnic = await getChartValues('getCreatedTicketsBy');
            const asignementTicketBy = await getChartValues('getAsignementTicketBy');
            const ticketsByZone = await getChartValues('getTicketByZone');
            const ticketsByDependency = await getChartValues('getTicketsByDependency');
            const averageTimePerTicket = await getChartValues('getAverageTimePerTicket');

            const equipmentEntryByTicket = await getChartValues('getEquipmentEntryByTicket');
            const equipmentEntry = await getChartValues('getEquipmentEntry');
            const equipmentByAsignementTecnic = await getChartValues('getTecnicByEquipment');
            
            dayTicketsChart = new CanvasJS.Chart("bar_chartContainer_ticketsbyweek", {
                exportEnabled: true,
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TICKETS POR DIA"
                },
                axisY: {
                    title: "DIAS"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "DIAS DE LA SEMANA",
                    dataPoints: ticketsOfWeek
                }]
            });

            createdTicketByChart = new CanvasJS.Chart("bar_chartContainer_createdticketbytecnic", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TICKETS CREADOS POR TECNICO"
                },
                axisY: {
                    title: "TICKETS"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "TECNICOS",
                    dataPoints: createdTicketByTecnic
                }]
            });

            asignementTicketByChart = new CanvasJS.Chart("bar_chartContainer_asignementticketby", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TECNICOS ASIGNADOS POR TICKET"
                },
                axisY: {
                    title: "TICKETS"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "TECNICOS",
                    dataPoints: asignementTicketBy
                }]
            });

            ticketsByZoneChart = new CanvasJS.Chart("bar_chartContainer_ticketbyzone", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TICKETS POR LOCALIDAD"
                },
                axisY: {
                    title: "TICKETS"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "LOCALIDAD",
                    dataPoints: ticketsByZone
                }]
            });

            topTenTicketsDependencyChart = new CanvasJS.Chart("bar_chartContainer_toptenticketsdependency", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TOP 10 DEPENDNCIAS CON MAS TICKETS"
                },
                axisY: {
                    title: "TICKETS"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "DEPENDNCIA",
                    dataPoints: ticketsByDependency
                }]
            });

            equipmentEntryByTicketChart = new CanvasJS.Chart("bar_chartContainer_equipmententrybyticket", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "EQUIPOS POR TICKET"
                },
                axisY: {
                    title: "Equipos"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "REGISTROS",
                    dataPoints: equipmentEntryByTicket
                }]
            });

            equipmentEntryChart = new CanvasJS.Chart("bar_chartContainer_equipmententry", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "EQUIPOS"
                },
                axisY: {
                    title: "Tipos de equipo"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "EQUIPOS",
                    dataPoints: equipmentEntry
                }]
            });

            equipmentByTecnicChart = new CanvasJS.Chart("bar_chartContainer_equipmentbytecnic", {
                animationEnabled: true,
                theme: "light1", // "light1", "light2", "dark1", "dark2"
                title:{
                    text: "TECNICO ENCARGADO POR EQUIPO"
                },
                axisY: {
                    title: "Equipos"
                },
                data: [{        
                    type: "column",  
                    showInLegend: true, 
                    legendMarkerColor: "grey",
                    legendText: "TECNICOS",
                    dataPoints: equipmentByAsignementTecnic
                }]
            });

            //---------------------------------BAR CHARTS ------------------------------------------------------------
            averageTimePerTicketChart = new CanvasJS.Chart("bar_chartContainer_averagetimeperticket", {
                animationEnabled: true,
                
                title:{
                    text:"PROMEDIO DE HORAS POR TICKET"
                },
                axisX:{
                    interval: 1
                },
                axisY2:{
                    interlacedColor: "rgba(1,77,101,.2)",
                    gridColor: "rgba(1,77,101,.1)",
                    title: "TIEMPO DE RESPUESTA (HS)"
                },
                data: [{
                    type: "bar",
                    name: "Tiempo de respuesta (HS)",
                    axisYType: "secondary",
                    color: "#014D65",
                    dataPoints: averageTimePerTicket
                }]
            });
            // chart.render();

            //-------------------------------- PIE CHARTS ------------------------------------------------------------

            dayTicketsChartPie = new CanvasJS.Chart("pie_chartContainer_ticketsbyweek", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TICKETS POR DIA"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: ticketsOfWeek
                }]
            });

            createdTicketByChartPie = new CanvasJS.Chart("pie_chartContainer_createdticketbytecnic", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TICKETS CREADOS POR TECNICO"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: createdTicketByTecnic
                }]
            });

            asignementTicketByChartPie = new CanvasJS.Chart("pie_chartContainer_asignementticketby", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TECNICOS ASIGNADOS POR TICKET"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: asignementTicketBy
                }]
            });

            ticketsByZonePie = new CanvasJS.Chart("pie_chartContainer_ticketbyzone", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TICKETS POR LOCALIDAD"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: ticketsByZone
                }]
            });

            topTenTicketsDependencyPie = new CanvasJS.Chart("pie_chartContainer_toptenticketsdependency", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TOP 10 TICKETS X DEPENDENCIA"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: ticketsByDependency
                }]
            });

            averageTimePerTicketPie = new CanvasJS.Chart("pie_chartContainer_averagetimeperticket", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TIEMPO DE RESPUESTA PROMEDIO"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: averageTimePerTicket
                }]
            });

            equipmentEntryByTicketPie = new CanvasJS.Chart("pie_chartContainer_equipmententrybyticket", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "PORCENTAJE DE EQUIPOS POR TICKET"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: equipmentEntryByTicket
                }]
            });

            equipmentEntryPie = new CanvasJS.Chart("pie_chartContainer_equipmententry", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "EQUIPOS"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: equipmentEntry
                }]
            });

            equipmentByTecnicPie = new CanvasJS.Chart("pie_chartContainer_equipmentbytecnic", {
                exportEnabled: true,
                animationEnabled: true,
                title:{
                    text: "TECNICOS POR EQUIPO"
                },
                legend:{
                    cursor: "pointer",
                    itemclick: explodePie
                },
                data: [{
                    type: "pie",
                    showInLegend: true,
                    toolTipContent: "{label}: <strong>{Porcentaje}%</strong>",
                    indexLabel: "{label} - {Porcentaje}%",
                    dataPoints: equipmentByAsignementTecnic
                }]
            });

            dayTicketsChart.render();
            createdTicketByChart.render();
            asignementTicketByChart.render();
            ticketsByZoneChart.render();
            topTenTicketsDependencyChart.render();
            averageTimePerTicketChart.render();
            equipmentEntryByTicketChart.render();
            equipmentEntryChart.render();
            equipmentByTecnicChart.render();

            // dayTicketsChartPie.render();
            // createdTicketByChartPie.render();
            // asignementTicketByChartPie.render();
            // ticketsByZonePie.render();

            // arrXls1 = await getExcelData('');

            xlsDayTicket = new xlsExport(ticketsOfWeek, 'Tickets por dia');
            xlsCreatedBy = new xlsExport(createdTicketByTecnic, 'Tickets atendidos por tecnico');
            xlsasignementTicket = new xlsExport(asignementTicketBy, 'Tickets por tecnico encargado');
            xlsByZone = new xlsExport(ticketsByZone, 'Tickets por zona');
            xlsTopTen = new xlsExport(ticketsByDependency, 'Tickets Top 10 Dependencias');
            xlsAverageTime = new xlsExport(averageTimePerTicket, 'Tickets Tiempo de respuesta');
            xlsEquipmentByTicket = new xlsExport(equipmentEntryByTicket, 'Equipo Por Ticket');
            xlsEquipmentEntry = new xlsExport(equipmentEntry, 'Equipos');
            xlsEquipmentByTecnic = new xlsExport(equipmentByAsignementTecnic, 'Equipos reparados por tecnico');

            await hideLoading();

            console.log(ticketsOfWeek)
        }

        function getChartValues(chart){
            let since = $('#filter_since').val();
            let until = $('#filter_until').val();
            let sec = $('#filter_secretary').val();
            let dep =  $('#filter_dependencia').val();
            let mot = $('#filter_motivo').val();
            let sub = $('#filter_submotivo').val();
            let enc = $('#filter_encargado').val();
            let ate = $('#filter_atendido').val();
            return new Promise(resolve => { 
                let list;                

                resolve(
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            'pag':'Reportes',
                            'tipo':'graficos',
                            'chart': chart,
                            'desde':since,
                            'hasta':until,
                            'secretaria': sec,
                            'dependencia': dep,
                            'motivo': mot,
                            'submotivo': sub,
                            'encargado': enc,
                            'atendido': ate
                        },
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','Error Peticion ajax');
                    })
                    .done(function(data){
                        list = data;
                    })
                )
            });
        }

        function explodePie (e) {
            if(typeof (e.dataSeries.dataPoints[e.dataPointIndex].exploded) === "undefined" || !e.dataSeries.dataPoints[e.dataPointIndex].exploded) {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = true;
            } else {
                e.dataSeries.dataPoints[e.dataPointIndex].exploded = false;
            }
            e.chart.render();

        }

        $('.radio-chart').click(function(e){
            let chart = $(this).val();

            $('#bar_chartContainer_'+chart).css('display', 'none');
            $('#pie_chartContainer_'+chart).css('display', 'block');

            switch (chart) {
                case 'ticketsbyweek':
                    dayTicketsChartPie.render();
                break;
                case 'createdticketbytecnic':
                    createdTicketByChartPie.render();
                break;
                case 'asignementticketby':
                    asignementTicketByChartPie.render();
                break;
                case 'ticketbyzone':
                    ticketsByZonePie.render();
                break;
                case 'toptenticketsdependency':
                    topTenTicketsDependencyPie.render();
                break;
                case 'averagetimeperticket':
                    averageTimePerTicketPie.render();
                break;
                case 'equipmententrybyticket':
                    equipmentEntryByTicketPie.render();
                break;
                case 'equipmententry':
                    equipmentEntryPie.render();
                break;
                case 'equipmentbytecnic':
                    equipmentByTecnicPie.render();
                break;
            }
        });

        $('.bar-chart').click(function(e){
            let chart = $(this).val();

            $('#bar_chartContainer_'+chart).css('display', 'block');
            $('#pie_chartContainer_'+chart).css('display', 'none');
            
            switch (chart) {
                case 'ticketsbyweek':
                    dayTicketsChart.render();
                break;
                case 'createdticketbytecnic':
                    createdTicketByChart.render();
                break;
                case 'asignementticketby':
                    asignementTicketByChart.render();
                break;
                case 'ticketbyzone':
                    ticketsByZoneChart.render();
                break;
                case 'toptenticketsdependency':
                    topTenTicketsDependencyChart.render();
                break;
                case 'averagetimeperticket':
                    averageTimePerTicketChart.render();
                break;
                case 'equipmententrybyticket':
                    equipmentEntryByTicketChart.render();
                break;
                case 'equipmententry':
                    equipmentEntryChart.render();
                break;
                case 'equipmentbytecnic':
                    equipmentByTecnicChart.render();
                break;
            }
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
        });

        $('#actualizar').click(function(e){
            loadCharts();
        });

        $('.export_to_excel_simple').click(function(e){
            let chart = $(this).val();

            switch (chart) {
                case 'ticketsbyweek':
                    xlsDayTicket.exportToXLS('Tickets por dia');
                break;
                case 'createdticketbytecnic':
                    xlsCreatedBy.exportToXLS('Tickets atendidos por tecnico');
                break;
                case 'asignementticketby':
                    xlsasignementTicket.exportToXLS('Tickets por tecnico encargado');
                break;
                case 'ticketbyzone':
                    xlsByZone.exportToXLS('Tickets por zona');
                break;
                case 'toptenticketsdependency':
                    xlsTopTen.exportToXLS('Tickets Top 10 Dependencias');
                break;
                case 'averagetimeperticket':
                    xlsAverageTime.exportToXLS('Tickets Tiempo de respuesta');
                break;
                case 'equipmententrybyticket':
                    xlsEquipmentByTicket.exportToXLS('Equipo Por Ticket');
                break;
                case 'equipmententry':
                    xlsEquipmentEntry.exportToXLS('Equipos');
                break;
                case 'equipmentbytecnic':
                    xlsEquipmentByTecnic.exportToXLS('Equipos reparados por tecnico');
                break;
            }
        });

        $('.export_to_excel_extend').click(async function(e){
            let chart = $(this).val();
            let excelExtend, excelData;

            excelData = await getExcelData(chart);

            excelExtend = new xlsExport(excelData, 'Tickets por dia');

            switch (chart) {
                case 'detailTickets':
                    excelExtend.exportToXLS('Tickets Detallados');
                break;
                case 'detailEquipment':
                    xlsCreatedBy.exportToXLS('Equipo por ticket');
                break;
            }
        });

        function getExcelData(chart){
            let since = $('#filter_since').val();
            let until = $('#filter_until').val();
            let sec = $('#filter_secretary').val();
            let dep =  $('#filter_dependencia').val();
            let mot = $('#filter_motivo').val();
            let sub = $('#filter_submotivo').val();
            let enc = $('#filter_encargado').val();
            let ate = $('#filter_atendido').val();
            return new Promise(resolve => { 
                let list;                

                resolve(
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            'pag':'Reportes',
                            'tipo':'excel',
                            'chart': chart,
                            'desde':since,
                            'hasta':until,
                            'secretaria': sec,
                            'dependencia': dep,
                            'motivo': mot,
                            'submotivo': sub,
                            'encargado': enc,
                            'atendido': ate
                        },
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','Error Peticion ajax');
                    })
                    .done(function(data){
                        list = data;
                    })
                )
            });
        }

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
?>