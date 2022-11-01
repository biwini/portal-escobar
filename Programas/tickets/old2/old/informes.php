<?php
require_once('controller/sessionController.php');
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"]) && $_SESSION['TICKETS'] == 1){
                include 'controller/motivoController.php';
                $Motivo = new motivo();
                $Motivo->getMotivo();
                $Motivo->getTecnico();

                $optionMotivo = '';
                foreach ($Motivo->listMotivo as $key => $value) {
                    $optionMotivo .= '<option value=\''.$value['Id'].'\'>'.$value['Motivo'].'</option>';
                }
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Informes</title>
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
        /*.card {
            position: relative;
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid rgba(0,0,0,.125);
            border-radius: .25rem;
        }
        .card-header {
            padding: .75rem 1.25rem;
            margin-bottom: 0;
            color: inherit;
            background-color: rgba(0,0,0,.03);
            border-bottom: 1px solid rgba(0,0,0,.125);
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
                <h1 class="page-title"><span class="icon-stats-bars"></span> INFORMES</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12" style="margin-top: 15px;">
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12">
                            <div id="content-wrapper">
                                <div class="row">
                                    <div class="col-md-12" style="margin-top: 15px;">
                                        <label>FILTRAR INFROMES:</label>
                                        <div class="content-filter">
                                            <ul class="list-inline" id="filter-content">
                                                <li class="list-inline-item"><label for="date_since">Desde:</label><input type="date" class="btn btn-default" name="date_since" id="date_since" max="<?php echo date("Y-m-d"); ?>"></li>
                                                <li class="list-inline-item"><label for="date_until">Hasta:</label><input type="date" class="btn btn-default" name="date_until" id="date_until" max="<?php echo date("Y-m-d"); ?>"></li>
                                                <li class="list-inline-item"><input type="button" class="btn btn-primary " name="" id="filter_button" value="Filtrar"></li>
                                                <li class="list-inline-item">
                                                    <select class="form-select pointer" id="filter_user">
                                                        <option value="0">MOSTRAR TODOS</option>
                                                        <?php 
                                                            foreach ($Motivo->tecnico as $key => $value) {
                                                                echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].' '.$value['LastName'].'</option>';  
                                                            }
                                                        ?>
                                                    </select>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="container-fluid">
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h3 class="card-title col-md-3 pull-left">CANTIDAD DE TICKETS POR DIA</h3>
                                                    <button type="button" class="btn btn-info actualizar col-md-1 pull-right" id="day_daybar_all" value="1">Actualizar</button>
                                                </div>
                                                <div class="card-body" id="canvas-1">
                                                    <canvas id="chartjs-1"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title pull-left">TICKETS CREADOS POR TECNICO</h4>
                                                    <button type="button" class="btn btn-info actualizar pull-right" id="tecnico_bar_topten" value="2">Actualizar</button>
                                                </div>
                                                <div class="card-body" id="canvas-2">
                                                    <canvas id="chartjs-2"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title pull-left">TECNICOS ASIGNADOS POR TICKET</h4>
                                                    <button type="button" class="btn btn-info actualizar pull-right" id="tickets por tecnico_pie_topten" value="3">Actualizar</button>
                                                </div>
                                                <div class="card-body" id="canvas-3">
                                                    <canvas id="chartjs-3"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title pull-left">TIEMPO DE RESPUESTA X TICKET</h4>
                                                    <button type="button" class="btn btn-info actualizar pull-right" id="tiempo respuesta x ticket_time_time" value="4">Actualizar</button>
                                                </div>
                                                <div class="card-body" id="canvas-4">
                                                    <canvas id="chartjs-4"></canvas>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title pull-left">MOTIVOS POR TICKET</h4>
                                                    <select class="form-control " id="motivo_ticket">
                                                        <?php echo $optionMotivo; ?>
                                                    </select>
                                                    <button type="button" class="btn btn-info actualizar pull-right" id="motivoxticket_bar_motivo" value="5">Actualizar</button>
                                                </div>
                                                <div class="card-body" id="canvas-5">
                                                    <canvas id="chartjs-5"></canvas>
                                                </div>
                                            </div>
                                        </div>
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
    <script language="javascript" src="js/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script src="js/sweetalert.min.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript" src="js/Chart.bundle.min.js"></script>
    <script type="text/javascript">
        let mychart1;
        $(document).ready(function(){
            $('.actualizar').each(function(){
                this.click();
            });
        });
        $('#filter_button').click(function(e){
            $('.actualizar').each(function(){
                this.click();
            });
        })
        $('#motivo_ticket').change(function(e){
            $('#motivoxticket_bar_motivo').click();
        })
        $('.actualizar').click(function(e){

            let chart = $(this).val();
            let name = $(this)[0].id.split('_')[0];
            let type = $(this)[0].id.split('_')[1];
            let filters = '';
            filters += '&desde='+$('#date_since').val();
            filters += '&hasta='+$('#date_until').val();
            filters += '&tecnico='+$('#filter_user').val();
            switch(chart){
                case '5':
                    filters += '&motivo='+$('#motivo_ticket').val();
                break;
            }

            $('#canvas-'+chart).html('<canvas id=\'chartjs-'+chart+'\'></canvas>'); 
            $.ajax({
                type: "POST",
                url: "controller/",
                data: "chart="+chart+"&pag="+document.title+"&tipo=a"+filters,
                dataType: "json",
            })
            .fail(function(data){
                console.log(data)
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                if(data.length != 0 ){
                    CreateChart(type,chart,name,data);
                }
            });
        });

        function random_rgb() {
            let o = Math.round, r = Math.random, s = 255;
            return 'rgb(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) +')';
        }
        function CreateChart(type,chart,name,data){
            // if(chart == 4){
            //     let total = data.reduce((currentTotal, item) => {
            //         return parseInt(item.Cantidad) + currentTotal
            //     },0 );

            //     console.log(parseInt(total / data.length))
            // }
            // console.log(data)
            switch(type){
                case 'bar':
                    mychart1 = new Chart(document.getElementById("chartjs-"+chart),{
                        "type":"bar","data":{
                            "labels":[name.toUpperCase()],
                            "datasets":[]
                        },
                        options: {                                        
                            scales: {
                                yAxes: [
                                    {
                                        ticks: {
                                            min: 0, // it is for ignoring negative step.
                                            beginAtZero: true,                          
                                            // stepSize: 1  
                                        }
                                    }
                                ]
                            }
                        }
                    });
                break;
                case 'pie':
                    let tickets = {
                        "label": 'CANTIDAD DE TICKETS',
                        "data": data.map(c => c.Cantidad),
                        "backgroundColor": data.map(c => random_rgb())
                    }
                    mychart1 = new Chart(document.getElementById("chartjs-"+chart),{
                        "type":"pie","data":{
                            "labels": data.map(c => c.Name),
                            "datasets":[tickets]
                        },
                    });
                break;
                case 'time':
                    let ctx = document.getElementById('chartjs-'+chart).getContext('2d');
                    ctx.canvas.width = 1000;
                    ctx.canvas.height = 300;

                    let color = Chart.helpers.color;
                    let cfg = {
                        data: {
                            datasets: [{
                                label: 'Alta de Ticket',
                                backgroundColor: '#2ECCFA',
                                borderColor: '#2ECCFA',
                                data: data.Inicio,
                                type: 'line',
                                pointRadius: 0,
                                fill: false,
                                lineTension: 0,
                                borderWidth: 2
                            },{
                                label: 'Tecnico Asignado',
                                backgroundColor: '#FE9A2E',
                                borderColor: '#FE9A2E',
                                data: data.Toma,
                                type: 'line',
                                pointRadius: 0,
                                fill: false,
                                lineTension: 0,
                                borderWidth: 2
                            },{
                                label: 'Ticket Finalizado',
                                backgroundColor: '#3ADF00',
                                borderColor: '#3ADF00',
                                data: data.Finalizado,
                                type: 'line',
                                pointRadius: 0,
                                fill: false,
                                lineTension: 0,
                                borderWidth: 2
                            }]
                        },
                        options: {
                            animation: {
                                duration: 0
                            },
                            scales: {
                                xAxes: [{
                                    type: 'time',
                                    time: {
                                        unit: 'hour'
                                    },
                                    distribution: 'series',
                                    offset: true,
                                    ticks: {
                                        major: {
                                            enabled: true,
                                            fontStyle: 'bold'
                                        },
                                        source: 'data',
                                        autoSkip: true,
                                        autoSkipPadding: 75,
                                        maxRotation: 0,
                                        sampleSize: 100
                                    },
                                }],
                                yAxes: [{
                                    gridLines: {
                                        drawBorder: false
                                    },
                                    scaleLabel: {
                                        display: true,
                                        labelString: 'Tiempo en horas'
                                    }
                                }]
                            },
                            tooltips: {
                                intersect: false,
                                mode: 'index',
                                callbacks: {
                                    label: function(tooltipItem, myData) {
                                        var label = myData.datasets[tooltipItem.datasetIndex].label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        fecha = new Date(tooltipItem.label);
                                        let item = {'Diff' : ''};
                                        switch(label){
                                            case 'Alta de Ticket: ':
                                                fecha = new Date(tooltipItem.label);
                                            break;
                                            case 'Tecnico Asignado: ': 
                                                item = data.Toma.find((element) => {
                                                    return element.y == tooltipItem.value;
                                                });
                                                if(item !== undefined){
                                                    fecha = new Date(item.Diff);
                                                }
                                            break;
                                            case 'Ticket Finalizado: ': 
                                                item = data.Finalizado.find((element) => {
                                                    return element.y == tooltipItem.value;
                                                });
                                                if(item !== undefined){
                                                    fecha = new Date(item.Diff);
                                                }
                                            break;
                                        }
                                        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric'};

                                        label += fecha.toLocaleDateString("es-ES", options) + 'Hs';
                                        return label;
                                    }
                                }
                            }
                        }
                    };
                
                    mychart1 = new Chart(ctx, cfg);
                break;
                case 'daybar':     
                //console.log(data.map(c => c.Cantidad))
                console.log(data)
                    let days = {
                        "label": 'CANTIDAD DE TICKETS',
                        "data": data.map(c => c.Cantidad),
                        "backgroundColor": data.map(c => random_rgb())
                    }
                    let canvas = document.getElementById('chartjs-'+chart).getContext('2d');
                    canvas.canvas.width = 1000;
                    canvas.canvas.height = 300;

                    mychart1 = new Chart(canvas,{
                        "type":"bar","data":{
                            "labels":['Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo'],
                            "datasets":[days]
                        },
                        options: {                                        
                            scales: {
                                yAxes: [
                                    {
                                        ticks: {
                                            min: 0, // it is for ignoring negative step.
                                            beginAtZero: true,                          
                                            // stepSize: 1  
                                        }
                                    }
                                ]
                            }
                        }
                    });
                break;
            }
            // console.log(data.map(c => c.Dependencia))
            if(type != 'daybar' && type != 'pie' && type != 'time'){
                $.each(data, function(i,d){
                    let newDataset = {
                        "label": d.Name,
                        "data":[parseInt(d.Cantidad)],
                        "backgroundColor":[random_rgb()]
                    }
                    mychart1.data.datasets.push(newDataset);
                    // console.log(mychart1.data)
                });

                mychart1.update();
            }
            // var hola = {
            //             "label":"UGC 17",
            //             "data":[1,3,2,2,2,2,2,2],
            //             "backgroundColor":["rgb(255, 99, 132)",
            //             "rgb(54, 162, 235)","rgb(255, 205, 86)"]
            //         }
            // var myPieChart1 = new Chart(document.getElementById("chartjs-5"),{
            //     "type":"bar","data":{
            //         "labels":["UGC 17","UGC 3","UGC 23","UGC 219","UGC 20","UGC 21","UGC 22","UGC 24"],
            //         "datasets":[hola]
            //     }
            // });
            // var myPieChart2 = new Chart(document.getElementById("chartjs-5"),{
            //     "type":"pie","data":{
            //         "labels":["UGC 17 MÓDULO 3","UGC 3 MÓDULO 1","UGC 23 MÓDULO 1"],
            //         "datasets":[{
            //             "label":"My First Dataset",
            //             "data":[2,1,1],
            //             "backgroundColor":["rgb(255, 99, 132)",
            //             "rgb(54, 162, 235)",
            //             "rgb(255, 205, 86)"]
            //         }]
            //     }
            // });
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
  }
?>