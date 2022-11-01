<?php 
    
    require_once('controller/liquidacionController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["OBRAS_PARTICULARES"])){

                $Liquidacion = new liquidacion();

                $LiquidationList = $Liquidacion->getLiquidacion();

                // $Admin = ($_SESSION['OBRAS_PARTICULARES'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;

?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Consulta de liqudiaciones</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <style type="text/css">
        .pointer{ cursor: pointer; }
    </style>
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
                <h1 class="page-title"><span class="icon-search"></span> CONSULTA DE LIQUIDACION</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <!-- <div class="col-md-3 form-group">
                                <label for="filter_order_state">ORDENES DE COMPRA:</label>
                                <select class="form-control" id="filter_order_state">
                                    <option value="CURRENT" selected>VIGENTES</option>
                                    <option value="EXPIRED">VENCIDAS</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Nº OC:</label>
                                <input type="number" class="form-control" id="filter_oc" placeholder="Nº Orden de compra...">
                            </div> -->
                            <div class="col-md-3 form-group">
                                <label>DESDE:</label>
                                <input type="date" id="filter_date_since" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>HASTA:</label>
                                <input type="date" id="filter_date_until" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add col-md-6 col-sm-6" style="text-align: left;">           
                                <a href="crear-vale" class="btn btn-primary">
                                    <span class="icon-plus"> Nueva liquidación </span>
                                </a>                                
                            </div> 
                            <div class="btn-add col-md-6 col-sm-6">           
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_liquidacion, 'Tickets de combustible.xls', 'Tickets');return false;">Exportar a Excel</a>                                                    
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_liquidacion" class="table table-striped" name="tb_liquidacion" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>FECHA</th>
                                            <th>RAZON SOCIAL</th>
                                            <th>ZONIFICACIÓN</th>
                                            <th>TIPO LIQUIDACION</th>
                                            <th>DESCUENTO(%)</th>
                                            <th>TOTAL($)</th>
                                            <th>CREADOR</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($LiquidationList as $key => $value) {
                                            echo '<tr>
                                                <td>'.$value['Date'].'</td>
                                                <td>'.$value['RazonSocial'].'</td>
                                                <td>'.$value['Zonificacion'].'</td>
                                                <td>'.$value['Type'].'</td>
                                                <td>'.$value['Discount'].'</td>
                                                <td>'.$value['Total'].'</td>
                                                <td>'.$value['Creator'].'</td>
                                                <td><a href=\'ver-liquidacion?type=S&liquidacion='.$value['Id'].'\' target=\'_blank\'> VER PDF </a></td>
                                            </tr>';
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                
                            </div>        
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <section class="container-fluid">
        <div class="modal fade" id="loading">
            <div class="modal-dialog">
                <div class="modal-dialog-centered">
                    <div class="modal-body" style="opacity: 0.5; height: 100%;width: 100%">
                        <div class="loader" id="loader">Loading...</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/export.js"></script>
    <script src="js/filter.js"></script>
    <script type="text/javascript">

        // $('#loading').modal({backdrop: 'static', keyboard: false});
        // $('#loading').modal('hide');

        const Liquidacion = <?php echo json_encode($LiquidationList); ?>;

        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_liquidacion').DataTable();
        });

        $('.car').click(function(){
            let patente = $(this).text();

            let vehiculo = CarList.find(e => e.Patente == patente);

            document.getElementById('patente').value = vehiculo.Patente;
            document.getElementById('modelo').value = vehiculo.Model;

            $('#secretaria option').filter(function() {
                return $(this).text() == vehiculo.Secretaria;
            }).prop('selected', true);

            $('#propietario option[value='+vehiculo.Propietario+']').prop("selected",true);
            
            $('#formulario').modal('show');
        });

        function displayTable(){
            filterTable = Vales.filter(v => v.OcState == orderState);

            if(dateSince !== undefined){
                filterTable = filterTable.filter(v => (v.Date >= dateSince) );
            }

            if(dateUntil !== undefined){
                filterTable = filterTable.filter(v => (v.Date <= dateUntil) );
            }

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(v => v.Secretary == filterSecretary);
            }

            if(filterDependence !== undefined){
                filterTable = filterTable.filter(v => v.Dependence == filterDependence);
            }

            if(filterOc !== undefined){
                filterTable = filterTable.filter(v => (v.Oc.includes(filterOc)) );
            }

            if(filterPatente !== undefined){
                filterTable = filterTable.filter(v => (v.Car.includes(filterPatente)) );
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(v){
                DataTable.row.add([
                    v.Number,
                    v.Provider,
                    '<span class=\'car pointer\'>'+v.Car+'</span>',
                    v.Oc,
                    v.Combustible,
                    v.Date,
                    v.Creator,
                    '<a href=\'http://192.168.122.180/portal-escobar/Programas/combustible/importToPdf?type=S&remito='+v.Number+' target=\'_blank\'> VER PDF </a>']
                ).draw(false);
            });
        }

        $('#formulario').on('hidden.bs.modal', function (e) {

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