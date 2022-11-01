<?php 
    
    require_once('controller/remitoController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                require_once 'controller/vehiculoController.php';
                require_once 'controller/ordenCompraController.php';

                $Remito = new remito();
                $Vehiculo = new vehiculo();
                $Order = new ordencompra();

                $RemitoList = $Remito->getRemitos();
                $OrderList = $Order->getOrders();
                $CarList = $Vehiculo->getCars();

                $Admin = $Remito->Admin;

?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Consulta de vales</title>
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
                <h1 class="page-title"><span class="icon-file-text"></span> CONSULTA DE VALES</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3 form-group">
                                <label for="filter_order_state">ORDENES DE COMPRA:</label>
                                <select class="form-control" id="filter_order_state">
                                    <option value="CURRENT" selected>VIGENTES</option>
                                    <option value="EXPIRED">VENCIDAS</option>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label>Nº OC:</label>
                                <input type="number" class="form-control" id="filter_oc" placeholder="Nº Orden de compra...">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>DESDE:</label>
                                <input type="date" id="filter_date_since" class="form-control">
                            </div>
                            <div class="col-md-3 form-group">
                                <label>HASTA:</label>
                                <input type="date" id="filter_date_until" class="form-control">
                            </div>
                            <?php if($Admin){ ?>
                            <div class="col-md-3 form-group">
                                <label for="filter_secretary">SECRETARIA:</label>
                                <select class="form-control" id="filter_secretary">
                                    <option value="" selected>TODAS LAS SECRETARIAS</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3 form-group">
                                <label for="filter_dependence">DEPENDENCIA:</label>
                                <select class="form-control" id="filter_dependence">
                                    <option value="" selected>TODAS LAS DEPENDENCIAS</option>
                                </select>
                            </div>
                            <?php } ?>
                            <div class="col-md-3 form-group">
                                <label>PATENTE:</label>
                                <input type="text" id="filter_car_patente" class="form-control" placeholder="Patente...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add col-md-6 col-sm-6" style="text-align: left">
                                <button type="button" class="btn btn-info" id="send_mail" value=""><span class="icon-mail2"> Enviar vales a proveedores</span></button>
                            </div>  
                            <div class="btn-add col-md-6 col-sm-6">           
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_remitos, 'Tickets de combustible.xls', 'Tickets');return false;">Exportar a Excel</a>                     
                                <a href="crear-vale" class="btn btn-primary">
                                    <span class="icon-plus"> Nuevo vale </span>
                                </a>                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_remitos" class="table table-striped" name="tb_remitos" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nº</th>
                                            <th>PROVEEDOR</th>
                                            <th>VEHÍCULO</th>
                                            <th>O/C Nº</th>
                                            <th>COMBUSTIBLE/ LTS.</th>
                                            <th>FECHA</th>
                                            <th>CREADOR</th>
                                            <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($RemitoList as $key => $value) {
                                            if($value['OcState'] == 'CURRENT'){
                                                echo '<tr>
                                                    <td>'.$value['Number'].'</td>
                                                    <td>'.$value['Provider'].'</td>
                                                    <td><span class=\'car pointer\'>'.$value['Car'].'</span></td>
                                                    <td>'.$value['Oc'].'</td>
                                                    <td>'.$value['Combustible'].'</td>
                                                    <td>'.$value['Date'].'</td>
                                                    <td>'.$value['Creator'].'</td>
                                                    <td><a href=\'http://192.168.122.180/portal-escobar/Programas/combustible/importToPdf?type=S&remito='.$value['Number'].'\' target=\'_blank\'> VER PDF </a></td>
                                                </tr>';
                                            }
                                        }

                                        ?>
                                    </tbody>
                                </table>
                                
                            </div>        
                        </div>
                    </div>
                    <div class="row">
                        <table id="simpleTable" border="1" style="display: none">
                            <thead>
                                <tr>
                                    <th>Nº</th>
                                    <th>PROVEEDOR</th>
                                    <th>VEHÍCULO</th>
                                    <th>O/C Nº</th>
                                    <th>COMBUSTIBLE/ LTS.</th>
                                    <th>FECHA</th>
                                    <th>CREADOR</th>
                                    <th>PDF</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 

                                foreach ($RemitoList as $key => $value) {
                                    echo '<tr>
                                        <td>'.$value['Number'].'</td>
                                        <td>'.$value['Provider'].'</td>
                                        <td><span class=\'car pointer\'>'.$value['Car'].'</span></td>
                                        <td>'.$value['Oc'].'</td>
                                        <td>'.$value['Combustible'].'</td>
                                        <td>'.$value['Date'].'</td>
                                        <td>'.$value['Creator'].'</td>
                                        <td>'.$value['CreationDate'].'</td>
                                        
                                    </tr>';
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <section>
        <div class="modal" id="formulario">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Datos del vehículo</h3>
                        <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="patente">Patente: </label>
                            <input type="text" class="form-control" name="patente" id="patente" placeholder="Patente..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo: </label>
                            <input type="text" class="form-control" name="modelo" id="modelo" placeholder="Chofer..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="secretaria">Secretaria: </label>
                            <select class="form-control" id="secretaria" name="secretaria" disabled>
                                <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                <?php 

                                    foreach ($SecretaryList as $key => $value) {
                                        echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                    }

                                    ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="propietario">Perteneciente: </label>
                            <select class="form-control" name="propietario" id="propietario" disabled>
                                <option value="MUNICIPAL">Municipal</option>
                                <option value="PARTICULAR">Particular</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-primary form-control" data-dismiss="modal" value="Cerrar">
                    </div>
                </div>
            </div>
        </div>
    </section>
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

        const Vales = <?php echo json_encode($RemitoList); ?>;
        const CarList = <?php echo json_encode($CarList); ?>;

        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_remitos').DataTable();
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



        $('#send_mail').click(function(){
            swal({
              title: "Enviando Vales...",
              text: "Se enviaran TODOS los vales que aun no an sido enviados a los proveedores correspondientes. ¿Desea continuar?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willSend) => {
                if (willSend) {
                    $('#loading').modal({backdrop: 'static', keyboard: false});
                    $.ajax({
                        type: "POST",
                        url:  url,
                        data: "pag=Remitos"+"&tipo=send",
                        dataType: "json",
                    })
                    .fail(function(data){
                        $('#loading').modal('hide');
                        swal('Error','Ocurrio un error inesperado al momento enviar los email','error')
                    })
                    .done(function(data){
                        $('#loading').modal('hide');
                        switch(data.Status){
                            case "Success" :
                                $('#loading').modal('hide');

                                // swal('Se enviaron los vales correctamente','','success');
                                let span = document.createElement("span");

                                data.Email.forEach(function(d){
                                    if(d.StatusEmail == 'Mail Send'){
                                        span.innerHTML += 'Se envio el vale Nº'+d.RemitoSent+' al proveedor: '+d.SentTo+'<hr>';
                                    }else{
                                        span.innerHTML += 'No se pudo enviar el vale: '+d.RemitoSent+'. Al proveedor: '+d.SentTo+'<hr>';
                                    }
                                });

                                swal({
                                    title: 'Se enviaron los vales correctamente',
                                    content: span,
                                    icon: "success",
                                });

                            break;
                            case 'No Remito To Send':
                                swal('No hay vales nuevos para enviar','');
                            break;
                            default: 
                                swal('No se enviaron los vales','error inesperado','error');
                            break;
                        }
                    });
                }
            });
           
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