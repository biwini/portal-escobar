<?php
require_once('controller/sessionController.php');
$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
          if(isset($_SESSION["TICKETS"])){
            
            if($_SESSION['TICKETS'] != 1){
                header("location: index.php");
                exit();
            }
            include 'controller/motivoController.php';

            $Motivo = new motivo();
            $Motivo->getMotivo();

            $optionMotivo = '';

            foreach ($Motivo->listMotivo as $key => $value) {
                $optionMotivo .= '<option value=\''.$value['Id'].'\'>'.$value['Motivo'].'</option>';
            }

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Motivos</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" type="text/css" href="css/app-main.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <main class="app-main">
        <div class="container-fluid page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-list-numbered"></span> ALTA DE MOTIVOS/SUB-MOTIVOS</h1>
            </header>
            <section>
            <!-- CONTENIDO DE LA PAGINA -->
                <div class="card">
                    <div class="row">
                        <div class="message">
                            <span></span>
                        </div>
                        <div class="col-md-12" id="reception-content5">
                            <button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px;" id="open_modal_submotivo">Cargar Sub Motivo</button>
                            <button type="button" class="btn btn-primary pull-right" style="margin-bottom: 10px; margin-right: 5px;" id="open_modal_motivo">Cargar Motivo</button>
                            
                            <h4>MOTIVOS ACTUALES</h4>
                            <div class="col-md-12">
                                <table class="table table-striped" name="t_motivo" id="t_motivo" width="100%">
                                    <thead>
                                        <th>Motivo</th>
                                        <th>Sub Motivo</th>
                                        <th>Tiempo Estimado</th>
                                        <th>Acciones</th>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <form name="form_motivo" id="form_motivo" autocomplete="off">
                    <div class="modal" id="modal_motivo">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Cargar Motivo</h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="motivo">Motivo: </label>
                                        <input type="text" class="form-control required" name="motivo" id="motivo" placeholder="Motivo" required="true">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="Cargar" id="cargar_motivo" value="Cargar">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <section>
                <form name="form_submotivo" id="form_submotivo" autocomplete="off">
                    <div class="modal" id="modal_submotivo">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Cargar Sub Motivo</h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="slct_motivo">Motivo: </label>
                                        <select class="form-control required" id="slct_motivo" name="slct_motivo">
                                            <option>SELECCIONE UN MOTIVO</option>
                                            <?php echo $optionMotivo; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="sub_motivo">Sub Motivo: </label>
                                        <input type="text" class="form-control required" name="sub_motivo" id="sub_motivo" placeholder="Sub Motivo" required="true">
                                    </div>
                                    <div class="form-group">
                                        <label for="tiempo_estimado">Tiempo estimado en horas(Hs): </label>
                                        <input type="number" class="form-control required only-number" min="0" max="999" name="tiempo_estimado" id="tiempo_estimado" placeholder="Tiempo Estimado" required="true">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="cargar_submotivo" id="cargar_submotivo" value="Cargar">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </section>
            <section>
                <form name="form_change_motivo" id="form_change_motivo" autocomplete="off">
                    <div class="modal" id="modal_change_motivo">
                        <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h3 class="modal-title">Modificar Motivo</h3>
                                    <button class="close pull-right" data-dismiss="modal" aria-hidden="true">&times;</button>    
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="mod_motivo">Motivo: </label>
                                        <input type="text" class="form-control required" name="mod_motivo" id="mod_motivo" placeholder="Motivo" required="true">
                                    </div>
                                    <div class="form-group" id="div_submotivo">
                                        <label>Sub Motivos:</label>
                                        <!-- <input type="text" class="form-control required" name="sub_motivo" id="sub_motivo" placeholder="Sub Motivo" required="true"> -->
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" class="btn btn-danger btn-md pull-left" data-dismiss="modal" name="cancel" value="Cancelar">
                                    <input type="submit" class="btn btn-primary" style="float: right;" name="guardar_cambios" id="guardar_cambios" value="Guardar Cambios">
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
    <script src="js/motivos.js?v=<?php echo time(); ?>"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            // setInterval(function(){
            //  getUser();
            // },10000);
            ListMotivo = <?php echo json_encode($Motivo->listMotivo); ?>;
            displayDataTable();
            console.log(ListMotivo)
        });
        $(document).on('submit', '#form_motivo', function(e){
            e.preventDefault();

            if(validate($(this).find('.required'))){
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data: $(this).serialize()+"&pag="+document.title+"&tipo=i",
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
                            mensaje('okey','Se agrego el motivo');
                            $("#form_motivo")[0].reset();
                            getMotivo();
                            $('#modal_motivo').modal('hide');
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo agregar el motivo');
                        break;
                        case 'Existing Motivo':
                            mensaje('fail','El motivo ingresado ya existe');
                        break;
                    }
                });
            }
        });

        $(document).on('submit', '#form_change_motivo', function(e){
            e.preventDefault();
            name = $('#mod_motivo').val();
            if(idMotivo > 0 && name != ''){
                SubMotivos = getValueSubmotivos();
                console.log(SubMotivos);
                // var FP = new FormData($("#form_change_motivo")[0]);
                // FP.append("pag", 'Motivos');
                // FP.append("tipo", 'u');
                // FP.append("id", idMotivo);
                // FP.append("submotivo", JSON.stringify(SubMotivos));
                // for (var pair of FP.entries()) {
                //     console.log(pair[0]+ ', ' + pair[1]); 
                // }{'pag':document.title,'tipo':'g','state':SelectedFilters,'user':FilterUser,'date_since': $('#date_since').val(),'date_until': $('#date_until').val()},
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data: {'pag': 'Motivos','tipo': 'u','id': idMotivo,'mod_motivo': name, 'submotivo': SubMotivos},
                    dataType: "html",
                })
                .fail(function(data){
                    console.log(data);
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    // console.log(data)
                    response = JSON.parse(data);
                    console.log(response);
                    switch(response.Status){
                        case 'Success':
                            // ProgramDataTable.destroy();
                            idMotivo = 0;
                            mensaje('okey','Se modifico el motivo');
                            $("#form_change_motivo")[0].reset();
                            getMotivo();
                        break;
                        case 'Existing Motivo':
                            mensaje('fail','Ya existe el motivo');
                        break;
                        case 'Unknown Motivo':
                            mensaje('fail','Datos invalidos');
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo modificar el motivo');
                        break;
                        case 'Invalid call':
                            mensaje('fail','Datos invalidos');
                        break;
                    }
                    $('#modal_change_motivo').modal('hide');
                }); 
            }
        });
        $(document).on('submit', '#form_submotivo', function(e){
            e.preventDefault();
            var motivo = $('#slct_motivo').val();
            if(motivo > 0){
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data: $(this).serialize()+"&pag=SubMotivo"+"&tipo=i"+'&id='+motivo,
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
                            // ProgramDataTable.destroy();
                            motivo = 0;
                            mensaje('okey','Se agrego el subMotivo');
                            $("#form_submotivo")[0].reset();
                            getMotivo();
                        break;
                        case 'Existing SubMotivo':
                            mensaje('fail','Ya existe el sub motivo');
                        break;
                        case 'Unknown Motivo':
                            mensaje('fail','Datos invalidos');
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo modificar el motivo');
                        break;
                        case 'Invalid call':
                            mensaje('fail','Datos invalidos');
                        break;
                    }
                    $('#modal_submotivo').modal('hide');
                }); 
            }
        });
        function getValueSubmotivos(){
            result = new Array();
            $.each(ListMotivo, function(i,m){
                if(idMotivo == m.Id){
                    if(m.SubMotivo.length > 0){
                        $.each(m.SubMotivo, function(i,s){
                             result.push({'Id' : s.Id, 'Name' : $('#submotivo_name_'+s.Id).val(), 'Time' : $('#submotivo_estimatedtime_'+s.Id).val()});
                        });
                    }
                }
            });
            return result;
        }
        function getMotivo(){
            $.ajax({
                type: "POST",
                url: "controller/",
                data: "pag="+document.title+"&tipo=g",
                dataType: "html",
            })
            .fail(function(data){
                console.log(data);
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                ListMotivo = JSON.parse(data);
                console.table(ListMotivo)
                console.log(ListMotivo)
                $.each(ListMotivo, function(i,m){
                    $('.motivo').append('<option value=\''+m.Id+'\'>'+m.Motivo+'</option>');
                    $('.mod_motivo').append('<option value=\''+m.Id+'\'>'+m.Motivo+'</option>');
                });
                DataTable.destroy();
                displayDataTable();
            });
        }
        $(window).on('scroll',function(){
            if ($(window).scrollTop()) {
                $('nav').addClass('affix');
            }
            else{
                $('nav').removeClass('affix'); 
            }
        })
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