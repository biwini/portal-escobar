<?php 
    
    require_once('controller/ordenCompraController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                require_once 'controller/vehiculoController.php';

                $Orden = new ordencompra();
                $Car = new vehiculo();

                $OrderList = $Orden->getOrders();
                $CarList = $Car->getCars();

                $Admin = $Orden->Admin;

?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Ordenes de compra</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
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
                <h1 class="page-title"><span class="icon-list-numbered"></span> ORDEN DE COMPRA</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
                            <div class="col-md-3">
                                <label for="filter_state">MOSTRANDO LAS ORDENES:</label>
                                <select class="form-control" id="filter_state">
                                    <option value="current" selected>VIGENTES Y PROXIMAS</option>
                                    <option value="expired">VENCIDAS</option>
                                </select>
                            </div>
                            <?php if($Admin){ ?>
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <label for="filter_dependence">DEPENDENCIA:</label>
                                <select class="form-control" id="filter_dependence">
                                    <option value="" selected>TODAS LAS DEPENDENCIAS</option>
                                </select>
                            </div>
                            <?php } ?>
                            
                            <div class="col-md-6" id="filter_date_custom" style="display: none;">
                                <div class="col-md-6">
                                    <label>DESDE:</label>
                                    <input type="date" id="filter_date_since" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>HASTA:</label>
                                    <input type="date" id="filter_date_until" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="filter_oc">Nº OC:</label>
                                <input type="number" id="filter_oc" class="form-control" placeholder="Nº de Orden de compra...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add col-md-6" style="text-align: left;">
                                <a href="#formulario" id="new_car" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nueva orden de compra </span>
                                </a>
                            </div>
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_orden, 'Ordenes de compra.xls', 'ordenes de compra');return false;">Exportar a Excel</a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_orden" class="table table-striped" name="tb_orden" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Nº O/C</th>
                                            <th>SECRETARIA</th>
                                            <th>DEPENDENCIA</th>
                                            <th>COMBUSTIBLE</th>
                                            <th>LTS. RESTANTE</th>
                                            <th>FECHA VALIDEZ</th>
                                            <th>FIN VALIDEZ</th>
                                            <th>CREADOR</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($OrderList as $key => $value) {
                                            if($value['State'] == 'CURRENT'){
                                                echo '<tr>
                                                    <td>'.$value['Oc'].'</td>
                                                    <td>'.$value['Secretary'].'</td>
                                                    <td>'.$value['Dependence'].'</td>
                                                    <td>'.$value['Fuel'].'</td>
                                                    <td>'.$value['RemainingFuel'].'</td>
                                                    <td>'.$value['ValidityDate'].'</td>
                                                    <td>'.$value['ExpirationDate'].'</td>
                                                    <td>'.$value['User'].'</td>
                                                    <td><button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button></td>
                                                </tr>';
                                            }
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
    <section>
        <form name="form_orden" id="form_orden" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Agregar Orden de compra</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="oc">O/C: </label>
                                <input type="number" class="form-control required" name="oc" id="oc" placeholder="Orden de compra..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="secretaria">Secretaria: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 

                                        foreach ($SecretaryList as $key => $value) {
                                            $d = ($value['Id'] == $_SESSION['SECRETARIA']) ? 'selected' : 'disabled';

                                            if(($Admin)){
                                                $d = '';
                                            }

                                            echo '<option value=\''.$value['Id'].'\' '.$d.'>'.$value['Secretary'].'</option>';
                                        }

                                        ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia">Dependencia: </label>
                                <select class="form-control" id="dependencia" name="dependencia" required>
                                    <option value="" disabled selected>SELECCIONE LA DEPENDENCIA</option>
                                     <?php echo ($Admin) ? '' : '<option value=\''.$_SESSION['DEPENDENCIA'].'\' selected>'.$Orden->searchDependence($_SESSION['SECRETARIA'], $_SESSION['DEPENDENCIA']).'</option>' ; ?>
                                </select>
                                <!-- <input type="text" class="form-control" name="new_dependencia" id="new_dependencia" style="display: none; margin-top: 5px;" placeholder="Dependencia..." required disabled> -->
                            </div>
                            <div class="form-group">
                                <label for="combustible">Límite de combustible: </label>
                                <input type="number" class="form-control required" name="combustible" id="combustible" placeholder="Limite de litros de combustible..." required="true">
                            </div>
                            <div class="form-group" id="car-list">
                                <label for="">Vehículos: </label>
                                <?php
                                    // foreach ($CarList as $key => $car) {
                                    //     echo '<div class="form-group">';
                                    //         echo '<input type=\'checkbox\' value=\''.$car['Id'].'\' id=\'vehiculo_'.$car['Id'].'\' name=\'vehiculo[]\' class=\'vehiculo\'>';
                                    //         echo '<label for=\'vehiculo_'.$car['Id'].'\' style=\'padding-left: 5px;\'>'.$car['Patente'].'</label>';
                                    //     echo '</div>';
                                    // }
                                ?>
                            </div>
                            <div class="form-group table-responsive">
                                <table id="car-table" class="table table-bordered">
                                    <thead>
                                        <th>MODELO</th>
                                        <th>PATENTE</th>
                                        <th>LTS. DISPONIBLES (<span id="lts_remaining">0</span> de <span id="max_lts">0</span>)</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-group">
                                <label for="validez">Fecha validez: </label>
                                <input type="date" class="form-control" name="validez" id="validez" required>
                            </div>
                            <div class="form-group">
                                <label for="vencimiento">Fecha vencimiento: </label>
                                <input type="date" class="form-control required" name="vencimiento" id="vencimiento" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                            <input type="submit" class="btn btn-primary" style="float: right;" name="Cargar" id="enviar" value="Agregar">
                        </div>
                    </div>
                </div>
            </div>
        </form>
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
        
        const Ordenes = <?php echo json_encode($OrderList);?>;
        const Vehiculos = <?php echo json_encode($CarList);?>;

        console.log(Ordenes)

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;
        let user;

        let maxLts = 0;
        let remaining = 0;

        $(document).ready(function(){
            DataTable = $('#tb_orden').DataTable();

            displayCars();
        });

        function displayTable(){
            
            filterTable = Ordenes.filter(o => o.State == state);

            if(state == 'EXPIRED'){

                if(dateSince !== undefined){
                    filterTable = filterTable.filter(o => o.ValidityDate >= dateSince || dateSince <= o.ExpirationDate);
                }

                if(dateUntil !== undefined){
                    filterTable = filterTable.filter(o => o.ExpirationDate <= dateUntil || dateUntil >= o.ValidityDate);
                }

                // filterTable = filterTable.filter(o => (o.ValidityDate >= dateSince && o.ExpirationDate <= dateUntil) || (dateSince <= o.ExpirationDate));
            }

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(o => o.Secretary == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(o => o.Dependence == filterDependence);
            }
            if(filterOc !== undefined){
                filterTable = filterTable.filter(o => (o.Oc.includes(filterOc)));
            }


            DataTable.rows().remove().draw();

            filterTable.forEach(function(t){  
                DataTable.row.add([
                    t.Oc,
                    t.Secretary,
                    t.Dependence,
                    t.Fuel,
                    t.RemainingFuel,
                    t.ValidityDate,
                    t.ExpirationDate,
                    t.User,
                    '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+t.Id+'\'></button>']
                ).draw(false);
            });
        }

        function displayCars(){
            $('#car-list').html('<label for="">VEHÍCULOS: </label>');
            carSecretary = $('#secretaria option:selected').text();

            let availableCars = Vehiculos.filter(v => v.Secretaria == carSecretary);

            availableCars.forEach(function(c){
                //Create the label element
                let $label = $("<label>").text(c.Model+' '+c.Patente);
                $label.attr('for', 'vehiculo_'+c.Id+'');
                //Create the input element
                let $input = $('<input type="checkbox">').attr({id: 'vehiculo_'+c.Id+'', name: 'vehiculo[]', class: 'vehiculo', value: c.Id});
 
                $('#car-list').append('<div class=\'form-group\'></div>');
                $('#car-list div:last-child').append($input);
                $('#car-list div:last-child').append($label);
            });
        }
        
        $(document).on('change', '.vehiculo', function(e){
            car = Vehiculos.find(v => v.Id == this.value);

            if(this.checked){
                newItem = '<tr id=\'row_'+car.Id+'\'>'
                    +'<td>'+car.Model+'</td>'
                    +'<td>'+car.Patente+'</td>'
                    +'<td><input type=\'number\' id=\'car_in_use_'+car.Id+'\' name=\'lts_'+car.Id+'\' min=\'1\' class=\'form-control lts_use\' required></td>'
                +'</tr>';

                $('#car-table tbody').append(newItem);
            }else{
                $('#row_'+car.Id).remove();
            }
            ltsRemaining();
        });

        $('#combustible').keyup(function(e){
            ltsRemaining();
        });

        $(document).on('keyup', '.lts_use', function(e){
            ltsRemaining();
        });

        $('#secretaria').change(function(){
            displayCars();
        });

        function ltsRemaining(){
            maxLts = $('#combustible').val();
            remaining = (maxLts != '') ? maxLts : 0;

            $('.lts_use').css('border-color','#cccccc');

            $('#max_lts').text(remaining);
            $('#lts_remaining').text(remaining);

            $('.vehiculo:checkbox:checked').each(function () {
                car = Vehiculos.find(v => v.Id == this.value);
                lts = $('#car_in_use_'+car.Id).val();

                remaining = remaining - lts;
                
                if(remaining < 0){
                    $('.lts_use').css('border-color','red');
                }
                $('#lts_remaining').text(remaining);
            });   
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            user = undefined;
            $('#title').text('Agregar Orden de compra');
            $('#enviar').val('Agregar');
            $('#max_lts').text('0');
            $('#lts_remaining').text('0');

            <?php if($Admin){ ?>
                $('#secretaria').prop('selectedIndex',0);
                $('#dependencia').find('option:not(:first)').remove();
                $('#dependencia').prop('selectedIndex',0);
                $('#car-list').html('<label for="">VEHÍCULOS: </label>');
            <?php } ?>
            $('#car-table tbody').html('');
            $("#form_orden")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');

            document.getElementById('oc').value = arr[0].innerText;

            $('#secretaria option').filter(function() {
                return $(this).text() == arr[1].innerText;
            }).prop('selected', true);

            <?php if($Admin){ ?>
                setDependences($('#secretaria option:selected').val());
            <?php } ?>

            $('#dependencia option').filter(function() {
                return $(this).text() == arr[2].innerText;
            }).prop('selected', true);

            document.getElementById('combustible').value = arr[3].innerText;
            document.getElementById('validez').value = arr[5].innerText;
            document.getElementById('vencimiento').value = arr[6].innerText;

            displayCars();

            let contentOrder = Ordenes.find(o => o.Oc == parseInt(arr[0].innerText));

            contentOrder.Cars.forEach(function(c){
                $('#vehiculo_'+c.Id).click();

                $('#car_in_use_'+c.Id).val(c.MaxLts);

                let today = getDate();
                console.log((today >= contentOrder.ValidityDate), today, contentOrder.ValidityDate)
                if(today >= contentOrder.ValidityDate){
                    $('#vehiculo_'+c.Id).attr('disabled', 'true');

                    $('#car_in_use_'+c.Id).attr('disabled', 'true');
                }
            });

            console.log(contentOrder)

            user = arr[7].innerText;

            $('#title').text('Editar Orden de de compra');
            $('#enviar').val('Guardar Cambios');
            $("#new_car").click();
        });

        function getDate(){
            date = new Date();
            //Año
            y = date.getFullYear();
            //Mes
            m = date.getMonth() + 1;
            //Día
            d = date.getDate();

            m = (m < 10) ? '0'+m : m;
            d = (d < 10) ? '0'+d : d;

            return y+'-'+m+'-'+d;
        }

        $(document).on('submit', '#form_orden', function(e){
            e.preventDefault();

            let haveSelectedCar = false;

            if(remaining < 0){
                swal('Límite de combustible excedido','','warning');
                return false;
            }

            $('.vehiculo:checkbox').each(function () {
                if(this.checked){
                    isInPage(document.getElementById('#lts_'+this.value));

                    haveSelectedCar = (parseInt($('#lts_'+this.value).val()) < 1) ? false : true;
                    return false;
                }
            });

            if(!haveSelectedCar){
                swal('La orden de compra no tiene vehículos asociados','seleccione mínimo 1 vehículo e intentelo nuevamente','warning');
                return false;
            }

            swal({
              title: (type == 'r') ? 'Registrando orden de compra...' : 'Modificando orden de compra...',
              text: (remaining > 0) ? '¿Seguro que desea realizar la acción?  Quedan '+remaining+' Lts. de combustible disponibles' : '¿Seguro que desea realizar la acción? ',
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((action) => {
                if (action) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: $(this).serialize()+'&pag='+document.title+'&tipo='+type+'&id='+id,
                        dataType: "json",
                    })
                    .fail(function(data){
                        console.log(data)
                        swal('Error','Error Peticion ajax','error');
                    })
                    .done(function(data){
                        console.log(data)
                        switch(data.Status){
                            case 'Success' :
                                mensaje('okey', (type == 'r') ? 'Se registro la orden de compra' : 'Se actualizo la orden de compra');

                                $("#form_orden")[0].reset();
                                console.log(data)

                                if(type == 'r'){
                                    location.reload();
                                }else{
                                    mensaje('okey', 'Se guardaron los cambios');

                                    DataTable.row($tr).remove().draw();
                                    DataTable.row.add([
                                        data.Oc,
                                        data.Secretary,
                                        data.Dependence,
                                        data.Fuel,
                                        data.RemainingFuel,
                                        data.ValidityDate,
                                        data.ExpirationDate,
                                        user,
                                        '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+id+'\'></button>']
                                    ).draw(false);
                                }
                                $("#closemodal").click();
                            break;
                            case 'Order In Use':
                                swal('Orden en uso, no se puede editar','');
                            break;
                            case 'Invalid Fuel':
                                swal('Límite de combustible de combustible excedido','','warning');
                            break;
                            case 'Incomplete Fields':
                                swal('Formulario incompleto','');
                            break;
                            case 'Unknown Dependence':
                                swal('Dependencia invalida', 'No se registro la orden de compra', 'warning');
                            break;
                            case 'Existing Order':
                                swal('Nº de orden de compra existente',' El Nº de la orden de compra que esta intentando dar de alta ya esta registrado', 'warning');
                            break;
                            case 'No Access':
                                swal('No tiene los permisos necesarios para realizar la acción','', 'warning');
                            break;
                            case 'Invalid Cars':
                                swal('Vehiculos invalidos, no se registro la orden de compra','Asegúrese de que haya completado todos los datos correspondientes al vehículo correctamente', 'warning');
                            break;
                            default: 
                                mensaje('fail', (type == 'r') ? 'No se pudo registrar la orden de compra' : 'No se pudo editar la orden de compra');
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