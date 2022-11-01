<?php
    require ('controller/reportController.php');
    $report = new Report();
    $listEmployee=json_decode($_POST['listEmployee']);
    $listDni=array();

    for ($i=0; $i < count($listEmployee->listDni) ; $i++) { 
        $listDni[$i]=$listEmployee->listDni[$i][0];
    }
    
    $fichadas=$report->getFichadasEmployee($listDni,$listEmployee->from,$listEmployee->to);
    $listFichadas = array();
    foreach($fichadas as $key => $val) {
        for($f=0;$f<count($val);$f++){
            $listFichadas[$key][$val[$f]["fecha"]][] = $val[$f];
        }
    }
    //echo $fichadas;
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Fichadas - Reportes</title>
        <meta charset="utf-8" />
        <link rel="stylesheet" href="css/report.css" />
        <link rel="stylesheet" href="src/Semantic-UI-CSS-master/semantic.min.css" />
        <link rel="stylesheet" type="text/css" href="src/DataTables/datatables.min.css"/>
        <script type="text/javascript" src="src/jquery-3.1.1.min.js"></script>
        <script type="text/javascript" src="src/DataTables/datatables.min.js"></script>
        <!-- <script type="text/javascript" src="src/dayjs.min.js"></script>
        <script type="text/javascript" src="src/es.min.js"></script> -->
        <script type="text/javascript" src="src/moment-with-locales.js"></script>
        <script type="text/javascript" src="src/Semantic-UI-CSS-master/semantic.min.js"></script>
        <script type="text/javascript" src="js/report.js"></script>
        
    </head>
    <body style="padding: 10px;">

        <input type='hidden' id='listEmployee' value='<?php echo $_POST['listEmployee']; ?>'>
        <input type='hidden' id='holidays' value='<?php echo json_encode($report->getHolidays($listEmployee->from,$listEmployee->to)); ?>'>
        <input type='hidden' id='licencias' value='<?php echo json_encode($report->getLicencias($listDni,$listEmployee->from,$listEmployee->to)); ?>'>
        <input type='hidden' id='horarios' value='<?php echo json_encode($report->getHorariosEmployee($listDni)); ?>'>
        <input type='hidden' id='fichadas' value='<?php echo json_encode($listFichadas);  ?>'>
        <button class='ui button basic positive' id='downloadXls'><i class="file excel icon"></i>Exportar a excel</button>
        <div id='contentReports' >

        </div>
        <div class="ui info icon message" id='reportLoading' style='display:none;'>
            <i class="notched circle loading icon"></i>
            <div class="content">
              <div class="header">
                Generando reportes...
              </div>
              <p>Esto puede tardar unos minutos</p>
            </div>
        </div>
    </body>
</html>