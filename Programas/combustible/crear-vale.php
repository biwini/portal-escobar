<?php 
    
    require_once('controller/remitoController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                require_once('controller/proveedorController.php');
                require_once('controller/vehiculoController.php');
                require_once('controller/ordenCompraController.php');

                $Remito = new remito();
                $Order = new ordencompra();
                $Vehiculo = new vehiculo();
                $Proveedor = new proveedor();

                $CarList = $Vehiculo->getCars();
                $ProviderList = $Proveedor->getProviders();
                $OrderList = $Order->getOrdersAvailableForUse();

?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Crear Vale</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <style type="text/css">
        .text-danger{ color: red;}
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
                <h1 class="page-title"><span class="icon-file-text"></span> CREAR VALE</h1>
            </header>
            <div class="row">
                <div class="col-md-12">
                <?php 

                    if(count($CarList) == 0){
                        echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON VEHÍCULOS</h2>';
                    }

                    if(count($ProviderList) == 0){
                        echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON PROVEEDORES</h2>';
                    }
                    if(count($OrderList) == 0){
                        echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON ORDENES DE COMPRA VALIDAS PARA LA FECHA</h2>';
                    }
                ?>
                </div>
            </div>
            <section class="page-section">
                <div class="card ">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <div class="col-md-3 form-group">
                                    <select class="form-control" id="filter_secretary">
                                        <option value="">TODAS LAS SECRETARIAS</option>
                                        <?php 
                                            foreach ($SecretaryList as $key => $value) {
                                                echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                
                                <a href="consultar-vales" id="new_provider" class="btn btn-primary">
                                    <span class="icon-plus"> Consultar vales </span>
                                </a>
                            </div>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <form name="form_remito" id="form_remito" autocomplete="off">
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="numero">Nº de remito: </label>
                                        <input type="number" class="form-control required " name="" id="numero" placeholder="Nº de remito..." required value="<?php echo $Remito->getLastNumberRemito() + 1; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="fecha">Fecha: </label>
                                        <input type="date" class="form-control required " name="fecha" id="fecha" required value="<?php echo explode(' ',$Remito->fecha)[0]; ?>" disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="proveedor">Proveedor: </label>
                                        <select class="form-control" name="proveedor" id="proveedor" required>
                                            <option value="" disabled selected>SELECCIONE EL PROVEEDOR</option>
                                            <?php 
                                                foreach ($ProviderList as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Proveedor'].'</option>';
                                                }
                                                if(count($ProviderList) == 0){
                                                    echo '<option value=\'\' disabled selected>NO SE ENCONTRARON PROVEEDORES</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="oc">O/C Nº: </label>
                                        <select class="form-control" id="oc" name="oc" required>
                                            <option value="" disabled selected>SELECCIONE LA ORDEN DE COMPRA</option>
                                            <?php
                                                foreach ($OrderList as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Oc'].' | '.$value['Secretary'].' | '.$value['Dependence'].' | '.$value['RemainingFuel'].'</option>';
                                                }
                                                if(count($OrderList) == 0){
                                                    echo '<option value=\'\' disabled selected>NO SE ENCONTRARON ORDENES DE COMPRA DISPONIBLES</option>';
                                                }
                                            ?>
                                        </select>
                                        <!-- <input type="number" class="form-control required" name="oc" id="oc" placeholder="O/N Nº..." required> -->
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="vehiculo">Vehículo: </label>
                                        <select class="form-control" name="vehiculo" id="vehiculo" required>
                                            <option value="" disabled selected>SELECCIONE EL VEHÍCULO</option>
                                            <?php 
                                                // foreach ($CarList as $key => $value) {
                                                //     echo '<option value=\''.$value['Id'].'\'>'.$value['Patente'].' | '.$value['Model'].' | '.$value['Secretaria'].'</option>';
                                                // }

                                                // if(count($CarList) == 0){
                                                //     echo '<option value=\'\' disabled selected>NO SE ENCONTRARON VEHÍCULOS</option>';
                                                // }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6" id="div_NAFTA" style="display: none;"> 
                                    <div class="form-group">
                                        <label for="nafta">Lts. Nafta: </label>
                                        <input type="number" class="form-control required lts" name="nafta" id="nafta" placeholder="Lts. Nafta..." disabled required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6" id="div_NAFTAPP" style="display: none;"> 
                                    <div class="form-group">
                                        <label for="naftapp">Lts. Nafta - V Power / Premiumn: </label>
                                        <input type="number" class="form-control required lts" name="naftapp" id="naftapp" placeholder="Nafta - V Power / Premiumn..." disabled required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6" id="div_GAS" style="display: none;"> 
                                    <div class="form-group">
                                        <label for="gas">Lts. Gas: </label>
                                        <input type="number" class="form-control required lts" name="gas" id="gas" placeholder="Lts. Gas..." disabled required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6" id="div_GASPE" style="display: none;"> 
                                    <div class="form-group">
                                        <label for="gaspe">Lts. Gas - Oil - V Power / Euro Diesel: </label>
                                        <input type="number" class="form-control required lts" name="gaspe" id="gaspe" placeholder="Lts. Gas - Oil - V Power / Euro Diesel..." disabled required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <?php //echo '<label>Belén de Escobar '.date('d/m').' de '.date('Y').'</label>'; ?>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-primary required pull-right" name="submit" id="crear" value="Crear y descargar PDF">
                                    </div>
                                </div>
                            </form> 
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
    <script src="js/main.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
        // $('#loading').modal({backdrop: 'static', keyboard: false});
        // $('#loading').modal('hide');

        const Cars = <?php echo json_encode($CarList); ?>;
        const Orders = <?php echo json_encode($OrderList); ?>;

        $('#vehiculo').change(function(e){
            disableAll();

            let vehiculo = $(this).val();

            let found = Cars.find(c => c.Id == vehiculo);

            console.log(found)

            $('#div_'+found.FuelType).show();
            $('#'+found.FuelType.toLowerCase()).removeAttr('disabled');
        });

        function disableAll(){
            $('#div_NAFTA').hide();
            $('#div_NAFTAPP').hide();
            $('#div_GAS').hide();
            $('#div_GASPE').hide();

            $('#nafta').attr('disabled', 'true');
            $('#naftapp').attr('disabled', 'true');
            $('#gas').attr('disabled', 'true');
            $('#gaspe').attr('disabled', 'true');
        }

        $('#oc').change(function(){
            let selectedOrder = Orders.find(o => o.Id == $(this).val());

            disableAll()
            $('#vehiculo').find('option:not(:first)').remove();
            $('#vehiculo').prop('selectedIndex',0);

            selectedOrder.Cars.forEach(function(car){
                o = new Option(car.Patente, car.Id);
                /// jquerify the DOM object 'o' so we can use the html method
                $(o).html(car.Model+' | '+car.Patente+' | '+car.RemainingFuel);

                $("#vehiculo").append(o);
            });
        });

        $('#filter_secretary').change(function(e){
            let secId = $(this).val();
            let secretary = $('#filter_secretary option:selected').text();

            $('#oc').find('option:not(:first)').remove();
            $('#oc').prop('selectedIndex',0);

            $('#vehiculo').find('option:not(:first)').remove();
            $('#vehiculo').prop('selectedIndex',0);

            let filterOrder = Orders;

            filterOrder.forEach(function(v){
                if(v.Secretary == secretary || secretary == 'TODAS LAS SECRETARIAS'){
                    o = new Option(v.Secretary, v.Id);
                    /// jquerify the DOM object 'o' so we can use the html method
                    $(o).html(v.Oc+' | '+v.Secretary+' | '+v.Dependence+' | '+v.RemainingFuel);

                    $("#oc").append(o);
                }
            });
        });

        $(document).on('submit', '#form_remito', function(e){
            e.preventDefault();

            let valid = false;
            let lts = 0;

            $('.lts').each(function(){
                if($(this).val() != '' && $(this).val() > 0){
                    valid = true;
                    lts = $(this).val();
                }
            });

            if(!valid){
                swal('CAMPOS DE COMBUSTIBLE VACIOS','','');
                return false;
            }

            $('#loading').modal({backdrop: 'static', keyboard: false});

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Remitos'+'&tipo=r',
                dataType: "json",
            })
            .fail(function(data){
                $('#loading').modal('hide');
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                $('#loading').modal('hide');
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', 'Se registro el vale');
                        
                        let remito = $('#numero').val();

                        let oc = $('#oc option:selected').text();

                        let newOc = oc.split(' | ')[0] +' | '+ oc.split(' | ')[1] +' | '+ oc.split(' | ')[2] +' | '+ (oc.split(' | ')[3] - lts);

                        $('select option:contains('+oc+')').text(newOc);

                        window.open('http://192.168.122.180/portal-escobar/Programas/combustible/importToPdf?&remito='+remito, '_blank');

                        $('#form_remito')[0].reset();

                        $('#numero').val(parseInt(remito) + 1);

                        $('#numero').css('border-color','#ededed');
                        $('#oc').css('border-color','#ededed');

                        disableAll();

                        $('#oc').prop('selectedIndex',0);
                        $('#vehiculo').prop('selectedIndex',0);
                        $('#proveedor').prop('selectedIndex',0);
                    break;
                    case 'Not Fuel':
                        swal('Combustible insuficiente', 'Los litros ingresados en el vale sobrepasan a los disponibles en la orden de compra y/o vehículo', 'warning');
                    break;
                    case 'Existing Remito':
                    
                        mensaje('fail','Numero de vale duplicado');
                        $('#numero').css('border-color','red');
                    break;
                    case 'Invalid Oc For Use':
                        swal('Orden de compra invalida', 'La orden de compra no es valida para su uso o esta vencida', 'warning');
                        $('#oc').css('border-color','red');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','no se creo el vale','warning');
                    break;
                    default: 
                        mensaje('fail','No se pudo registrar el vale');
                    break;
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