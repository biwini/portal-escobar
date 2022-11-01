<?php 
    
    require_once('controller/vehiculoController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                $Vehiculos = new vehiculo();

                $CarList = $Vehiculos->getCars();

                $Admin = $Vehiculos->Admin;

?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Vehículos</title>
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
                <h1 class="page-title"><span class="icon-travel-taxi-cab"></span> VEHÍCULOS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
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
                                <label for="filter_fuel">TIPO DE COMBUSTIBLE:</label>
                                <select class="form-control" id="filter_fuel">
                                    <option value="" selected>TODOS LOS TIPOS</option>
                                    <option value="NAFTA">NAFTA</option>
                                    <option value="NAFTAPP">NAFTA - V POWER / PREMIUM:</option>
                                    <option value="GAS">GAS</option>
                                    <option value="GASPE">GAS - OIL - V POWER / EURO DIESEL</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add col-md-6" style="text-align: left;">
                                <a href="#formulario" id="new_car" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo vehículo </span>
                                </a>
                            </div>
                            <div class="btn-add col-md-6">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_vehiculos, 'vehiculos-ticket-de-combustible.xls', 'vehiculos');return false;">Exportar a Excel</a>
                            </div>   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_vehiculos" class="table table-striped" name="tb_vehiculos" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PATENTE</th>
                                            <th>MODELO</th>
                                            <th>TIPO DE COMBUSTIBLE</th>
                                            <th>PROPIETARIO</th>
                                            <th>SECRETARIA</th>
                                            <th>DEPENDENCIA</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        foreach ($CarList as $key => $value) {
                                            echo '<tr>
                                                <td>'.$value['Patente'].'</td>
                                                <td>'.$value['Model'].'</td>
                                                <td>'.$value['FuelType'].'</td>
                                                <td>'.$value['Propietario'].'</td>
                                                <td>'.$value['Secretaria'].'</td>
                                                <td>'.$value['Dependencia'].'</td>
                                                <td><button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''.$value['Id'].'\'></button>
                                                <button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button></td>
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
    <section>
        <form name="form_vehiculo" id="form_vehiculo" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">Agregar Vehículo</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="patente">Patente: </label>
                                <input type="text" class="form-control required" name="patente" id="patente" placeholder="Patente..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="modelo">Modelo: </label>
                                <input type="text" class="form-control required" name="modelo" id="modelo" placeholder="Modelo...">
                            </div>
                            <div class="form-group">
                                <label for="combustible">Combustible: </label>
                                <select class="form-control" name="combustible" id="combustible" required="">
                                    <option value="" selected disabled>SELEECIONE EL TIPO DE COMBUSTIBLE</option>
                                    <option value="NAFTA">NAFTA</option>
                                    <option value="NAFTAPP">NAFTA - V POWER / PREMIUM:</option>
                                    <option value="GAS">GAS</option>
                                    <option value="GASPE">GAS - OIL - V POWER / EURO DIESEL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="propietario">Perteneciente: </label>
                                <select class="form-control required" name="propietario" id="propietario" required>
                                    <option value="MUNICIPAL">Municipal</option>
                                    <option value="PARTICULAR">Particular</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="secretaria">Secretaria: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 

                                        foreach ($SecretaryList as $key => $value) {
                                            $d = ($value['Id'] == $_SESSION['SECRETARIA']) ? 'selected' : 'disabled';

                                            if($Admin){
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
                                    <?php echo ($Admin) ? '' : '<option value=\''.$_SESSION['DEPENDENCIA'].'\' selected>'.$Vehiculos->searchDependence($_SESSION['SECRETARIA'], $_SESSION['DEPENDENCIA']).'</option>' ; ?>
                                </select>
                                <!-- <input type="text" class="form-control" name="new_dependencia" id="new_dependencia" style="display: none; margin-top: 5px;" placeholder="Dependencia..." required disabled> -->
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
        const Vehiculos = <?php echo json_encode($CarList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;

        $(document).ready(function(){
            DataTable = $('#tb_vehiculos').DataTable();
        });


        function displayTable(){

            filterTable = Vehiculos;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(v => v.Secretaria == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(v => v.Dependencia == filterDependence);
            }
            if(filterFuel !== undefined){
                filterTable = filterTable.filter(v => v.FuelType == filterFuel);
            }


            DataTable.rows().remove().draw();

            filterTable.forEach(function(t){  
                DataTable.row.add([
                    t.Patente,
                    t.Model,
                    t.FuelType,
                    t.Propietario,
                    t.Secretaria,
                    t.Dependencia,
                    '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+t.Id+'\'></button> '
                    +'<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+t.Id+'\'></button>']
                ).draw(false);
            });
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar Vehículo');
            $('#enviar').val('Agregar');

            <?php if($Admin){ ?>

                $('#secretaria').prop('selectedIndex',0);
                $('#dependencia').find('option:not(:first)').remove();
                $('#dependencia').prop('selectedIndex',0);
            <?php } ?>

            $("#form_vehiculo")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let arr = $(this).parents('tr').children('td');
            let Found = Vehiculos.find(m => m.Id == id);
            console.log(arr[0].innerText)

            document.getElementById('patente').value = arr[0].innerText;
            document.getElementById('modelo').value = arr[1].innerText;
            // $('#combustible option[value='+arr[2].innerText+']').prop("selected",true);
            $('#propietario option[value='+arr[3].innerText+']').prop("selected",true);

            $('#combustible option').filter(function() {
                return $(this).val() == Found.Fuel;
            }).prop('selected', true);

            $('#secretaria option').filter(function() {
              return $(this).text() == arr[4].innerText;
            }).prop('selected', true);

            <?php if($Admin){ ?>
                setDependences($('#secretaria option:selected').val());
            <?php } ?>

            $('#dependencia option').filter(function() {
                return $(this).text() == arr[5].innerText;
            }).prop('selected', true);

            $('#title').text('Editar Vehículo');
            $('#enviar').val('Guardar Cambios');
            $("#new_car").click();
        });

        $(document).on('click','.delete', function(e){
            let row = $(this).parents('tr');
            let vehiculo = $(this).val();

            swal({
              title: "Dando de baja el vehículo...",
              text: "¿Seguro dar de baja este vehículo?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url:  url,
                        data: "id="+vehiculo+"&pag="+document.title+"&tipo=d",
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','error petición ajax');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case "Success" :
                                mensaje('okey','Se dio de baja el vehículo');

                                 DataTable.row(row).remove().draw();
                            break;
                            default: 
                                mensaje('fail','No se pudo dar de baja el vehículo');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#form_vehiculo', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag='+document.title+'&tipo='+type+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'r') ? 'Se registro el vehículo' : 'Se actualizo el vehículo');

                        $("#form_vehiculo")[0].reset();

                        if(type == 'r'){
                            location.reload();
                        }else{
                            mensaje('okey', 'Se guardaron los cambios');

                            DataTable.row($tr).remove().draw();
                            DataTable.row.add([
                                data.Patente,
                                data.Model,
                                data.FuelType,
                                data.Propietario,
                                data.Secretaria,
                                data.Dependencia,
                                '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+id+'\'></button> '
                                +'<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+id+'\'></button>']
                            ).draw(false);
                        }
                        $("#closemodal").click();
                    break;
                    case 'Existing Car':
                        swal('Patente de vehículo ya registrada','','warning');
                    break;
                    case 'Invalid Fuel':
                        swal('Tipo de combustible invalido','','warning');
                    break;
                    case 'Car In Use':
                        swal('No se puede editar la patente del vehículo debido a que esta asociada a un remito','','warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el vehículo' : 'No se pudo editar el vehículo');
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