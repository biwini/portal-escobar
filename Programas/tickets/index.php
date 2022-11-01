<?php
    require_once('controller/sessionController.php');
    $Session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS"])){
                include 'controller/motivoController.php';
                include 'controller/equipoController.php';

                $Motivo = new motivo();
                $Equipo = new equipo();
                $TipoEquipo = new tipoEquipo();
                
                $Motivo->getMotivo();
                $listTecnicos = $Motivo->getTecnico();
                $listTiposEquipos = $TipoEquipo->getList();
                $listProcesadores = $Equipo->getProcesadores();
                $listMotherBoards = $Equipo->getPlacasMadre();
                $listDiscos = $Equipo->getTipoDisco();
                $listSO = $Equipo->getSistemasOperativos();

                $optionTipoEquipos = '';

                foreach ($listTiposEquipos as $key => $value) {
                    $optionTipoEquipos .= '<option value=\''.$value["Id"].'\'>'.$value["Type"].'</option>';
                }

                
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Ticket</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo-escobar-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="images/logo-escobar-192x192.png" sizes="192x192">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/motivo.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" />
    <style type="text/css">
        #header {
            margin:auto;
            width:500px;
            font-family:Arial, Helvetica, sans-serif;
        }
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
        <div class="menu-fixed"></div>
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-ticket"></span> MESA DE SERVICIOS DEL AREA DE SISTEMAS</h1>
            </header>
            <section>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <form name="ticket" id="ticket" autocomplete="off">
                                <?php if($_SESSION['TICKETS'] == 1 && $Motivo->isTecnicUser){

                                    // if(($_SESSION['LEGAJO'] == 'L6401' || $_SESSION['LEGAJO'] == 'L12306')){
                                    //     echo '<div class=\'col-md-6\'>
                                    //         <label for="fecha_alta">FECHA ALTA:</label>
                                    //         <input type="date" name="fecha_alta" class="form-control" value=\''.date('Y-m-d').'\'>
                                    //     </div>';
                                    // }
                                ?>

                                
                                <div class="form-group col-md-12">
                                    <a href="http://192.168.122.180/portal-escobar/Programas/administrator/usuarios" target="blank" class="btn btn-primary pull-right" title="Nuevo Usuario">Usuarios</a>
                                </div>  
                                <div class="form-group col-md-6">
                                    <label for="legajo">LEGAJO: </label>
                                    <input type="text" class="form-control required search" name="legajo" id="legajo" placeholder="Legajo" required>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="nombre">NOMBRE: </label>
                                    <input type="text" class="form-control required" name="nombre" id="nombre" placeholder="NOMBRE" required disabled>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="apellido">APELLIDO: </label>
                                    <input type="text" class="form-control required" name="apellido" id="apellido" placeholder="APELLIDO" required disabled>
                                </div>
                                <?php } ?>
                                <div class="form-group col-md-6">
                                    <label for="secretaria">SECRETARIA: </label>
                                    <select class="form-control secretaria required" id="secretaria" name="secretaria" required disabled >
                                        <option value="0" disabled selected>SELECCIONA UNA SECRETARIA</option>
                                        <?php echo $optionSecretarias; ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="dependencia">DEPENDENCIA: </label>
                                    <select class="form-control dependencia required" id="dependencia" name="dependencia" required="true" disabled>
                                        <option value="0" disabled selected>SELECIONE SU DEPENDENCIA</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label class="form-label">MOTIVO:</label>
                                    <div class="menu-content-box">
                                        <nav class="menu-container">
                                            <p class="menu-title" id="menu-title">SELECCIONE UN MOTIVO<span class="caret right"></span></p>
                                            <div class="menu-main">
                                                <ul>
                                                    <?php
                                                        $motivo = '';
                                                        
                                                        foreach ($Motivo->listMotivo as $key => $value) {
                                                            
                                                            $motivo .= '<li class=\'menu-item disabled\'><a href=\'#\'><span class=\'item-name\'>'.$value['Motivo'].'</span><span class=\'caret right\'></span></a>';

                                                            if(count($value['SubMotivo']) > 0){
                                                                $motivo .= '<ul class=\'menu-subItem\'>';
                                                                
                                                                foreach ($value['SubMotivo'] as $key2 => $value2) {
                                                                    if($_SESSION['TICKETS'] == 1 && $Motivo->isTecnicUser && $value2['Id'] == 24){ //24 == INGRESO DE PC

                                                                        $motivo .= '<li class=\'link-subItem\'><a href=\'#\' id=\''.$value2['Id'].'\' class=\'sub_motivo pointer\'>'.$value2['SubMotivo'].'</a></li>';
                                                                    }else if($value2['Id'] != 24){
                                                                        $motivo .= '<li class=\'link-subItem\'><a href=\'#\'id=\''.$value2['Id'].'\' class=\'sub_motivo pointer\'>'.$value2['SubMotivo'].'</a></li>';
                                                                    }
                                                                }
                                                                $motivo .= '</ul>';
                                                            }
                                                            $motivo .= '</li>';
                                                        }
                                                        // $motivo .= '<li><span class=\'pointer sub_motivo\'>OTRO</span></li>';
                                                        echo $motivo;
                                                    ?>
                                                </ul>
                                            </div>
                                        </nav>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="prioridad">PRIORIDAD:</label>
                                    <select class="form-control pull-right" id="prioridad" name="prioridad">
                                        <option value="2" selected>NORMAL</option>
                                        <option value="1">URGENTE</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="encargado">ENCARGADO:</label>
                                    <select class="form-control pull-right" id="encargado" name="encargado">
                                        <option value="0">SIN ASIGNAR</option>
                                        <?php 
                                            foreach ($listTecnicos as $key => $value) {
                                                echo '<option value=\''.$value['Id'].'\'>'.$value['Name'].' '.$value['LastName'].'</option>';  
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="">SUBIR ARCHIVOS</label>
                                    <div class="form-group">
                                        <div id="dropzoneDiv">
                                            <div id="dropzone" class="dropzone" style="width: 100%;">
                                                <input type="file" multiple="multiple" class="dz-hidden-input" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx,.xls,.xlsx,.psd,.ai" style="visibility: hidden; position: absolute;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php if($_SESSION['TICKETS'] == 1 && $Motivo->isTecnicUser){ ?>

                                <div class="form-group col-md-6">
                                    <div class="col-md-12">
                                        <label for="obs">COMENTARIO INTERNO:</label>
                                        <textarea class="form-control" id="obs" name="obs" placeholder="COMENTARIO INTERNO..." rows="7,5"></textarea>
                                    </div>
                                </div>
                                <div class="form-group col-md-12">
                                    <input type="checkbox" id="ingreso_de_equipo" value="1">
                                    <label class="form-label" for="ingreso_de_equipo">INGRESO DE EQUIPO</label>
                                </div>
                                <div class="form-group col-md-12 hide" id="ingreso_pc">
                                    <div class="form-group col-md-4">
                                        <label>Nº PATRIMONIO</label>
                                        <input type="number" class="form-control search" name="patrimonio_ingreso" id="patrimonio_ingreso" placeholder="Nº de patrimonio" disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Nº EQUIPO</label>
                                        <input type="number" class="form-control search required" name="interno_ingreso" id="interno_ingreso" placeholder="Nº interno" required disabled>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>TIPO:</label>
                                        <select class="form-control required" id="equipo_ingreso" name="equipo_ingreso" required disabled>
                                            <?php echo $optionTipoEquipos; ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-8">
                                        <label>FALLA TÉCNICA:</label>
                                        <textarea class="form-control" id="falla_ingreso" name="falla_ingreso" rows="4" placeholder="Describa la falla del equipo" required disabled></textarea>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <div class="form-group col-md-12">
                                            <label>MARCA:</label>
                                            <input type="text" class="form-control" name="marca_ingreso" id="marca_ingreso" placeholder="Marca" disabled>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label>MODELO:</label>
                                            <input type="text" class="form-control" name="modelo_ingreso" id="modelo_ingreso" placeholder="Modelo" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>PLACA MADRE:</label>
                                        <select name="mother_ingreso" id="mother_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listMotherBoards as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cModelo'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>PROCESADOR:</label>
                                        <select name="procesador_ingreso" id="procesador_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listProcesadores as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cTipo'].'-'.$value['cModelo'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label>SISTEMA OPERATIVO:</label>
                                        <select name="so_ingreso" id="so_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listSO as $key => $value) {
                                                    echo '<option value=\''.$value['id'].'\'>'.$value['cNombre'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>BITS S.O:</label>
                                        <select name="bits_so_ingreso" id="bits_so_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <option value="64">64</option>
                                            <option value="32">32</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>RAM:</label>
                                        <select name="ram_ingreso" id="ram_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <?php 
                                                for ($i=1; $i <= 24; $i++) { 
                                                    echo '<option value=\''.$i.'\'>'.$i.' GB</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>TIPO DE DISCO:</label>
                                        <select name="tipo_disco_ingreso" id="tipo_disco_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                            <?php 
                                                foreach ($listDiscos as $key => $value) {
                                                    echo '<option value=\''.$value['Id'].'\'>'.$value['Type'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label>TAMAÑO DE DISCO:</label>
                                        <select name="cantidad_disco_ingreso" id="cantidad_disco_ingreso" class="form-control" disabled>
                                            <option value="" selected>SIN DEFINIR</option>
                                        </select>
                                    </div>
                                    
                                </div>
                                <?php } ?>
                                <!-- TELEFONOS IP -->
                                <div class="col-md-6 hide" id="telefono_ip">
                                    <label>NUMERO IP DEL TELEFONO</label>
                                    <input type="number" class="form-control required" name="number" id="number" placeholder="Ej: 192.168.120.129" required disabled>
                                </div>
                                <!-- INTERNET SIN ACCESO A WEB -->
                                <div class="col-md-6 hide" id="pagina_web">
                                    <label>URL DE LA PAGINA:</label>
                                    <input type="text" class="form-control required" name="url" id="url" placeholder="http//example.com" required disabled>
                                </div>
                                <!-- ALTA DE USUARIOS -->
                                <div class="col-md-12 hide" id="alta_user">
                                    <h2>Complete los siguientes datos obligatorios*</h2>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="alta_nombre">Nombre*</label>
                                            <input type="text" class="form-control required" id="alta_nombre" name="alta_nombre" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_apellido">Apellido*</label>
                                            <input type="text" class="form-control required" id="alta_apellido" name="alta_apellido" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_legajo">Legajo*</label>
                                            <input type="text" class="form-control required" id="alta_legajo" name="alta_legajo" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_dni">Dni*</label>
                                            <input type="number" class="form-control required" id="alta_dni" name="alta_dni" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_nacimiento">Fecha Nacimiento*</label>
                                            <input type="date" class="form-control required" id="alta_nacimiento" name="alta_nacimiento" disabled>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="alta_secretaria">Secretaria*</label>
                                            <select class="form-control secretaria required" id="alta_secretaria" name="alta_secretaria" disabled>
                                                <option value="0">SELECCIONE UNA SECRETARIA</option>
                                                <?php echo $optionSecretarias; ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_dependencia">Dependencia*</label>
                                            <select class="form-control dependencia required" id="alta_dependencia" name="alta_dependencia" disabled>
                                                <option value="0">SELECCIONE UNA DEPENDENCIA</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_telefono">Telefono*</label>
                                            <input type="text" class="form-control required" id="alta_telefono" name="alta_telefono" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_email">Email Personal*</label>
                                            <input type="email" class="form-control required" id="alta_email" name="alta_email" disabled>
                                        </div>
                                        <div class="form-group">
                                            <label for="alta_compartida">Compartida</label>
                                            <select class="form-control required" name="alta_compartida" id="alta_compartida" disabled>
                                                <option value="0" selected>NO</option>
                                                <option value="1">SI</option>
                                            </select>
                                        </div>
                                        <div class="form-group hide" id="list_compartidas">
                                            <label for="alta_carpetas">Carpetas</label>
                                            <ul class="list-inline">
                                            </ul>
                                            <!-- <input type="text" class="form-control" id="alta_carpetas" name="alta_carpetas" disabled> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="submit" class="btn btn-primary pull-right" name="cargar" id="cargar" value="Solicitar Ticket" style="margin-top: 10px;">
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
            </section>
        </div>
    </main>
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/select.js"></script>
    <script src="js/main.js"></script>
    <script src="js/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.js"></script>
    <script src="js/autocomplete.js"></script>
    <script src="js/filter.js"></script>
    <script type="text/javascript">
        const ListMotivos = <?php echo json_encode($Motivo->listMotivo); ?>;
        var myDropzone;
        let ticket;

        Dropzone.autoDiscover = false;

        <?php if($_SESSION['TICKETS'] == 1 && $Motivo->isTecnicUser){ ?>

            const Discos = <?php echo json_encode($listDiscos); ?>;
            
            autocomplete(document.getElementById('legajo'), 'Usuarios', true);
            autocomplete(document.getElementById('patrimonio_ingreso'), 'Patrimonio', true);
            autocomplete(document.getElementById('interno_ingreso'), 'Interno', true);

            function completeFields(type, item){
                console.log(item)
                switch(type){
                    case 'legajo':
                        $('#nombre').val(item.Name);
                        $('#apellido').val(item.Surname);
                        $("#secretaria option[value='"+item.IdSecretaria+"']").prop("selected",true);
                        setDependences(item.IdSecretaria);
                        $("#dependencia option[value='"+item.IdDependencia+"']").prop("selected",true);
                    break;
                    case 'patrimonio_ingreso':
                    case 'interno_ingreso':
                        $('#interno_ingreso').val(item.Intern);
                        $('#patrimonio_ingreso').val(item.Patrimony);
                        $('#marca_ingreso').val(item.Brand);
                        $('#modelo_ingreso').val(item.Model);

                        $('#equipo_ingreso option').filter(function() {
                            return $(this).val() == item.Type;
                        }).prop('selected', true);
                        
                        $('#mother_ingreso option').filter(function() {
                            return $(this).val() == item.IdMother;
                        }).prop('selected', true);

                        $('#procesador_ingreso option').filter(function() {
                            return $(this).val() == item.IdProcesador;
                        }).prop('selected', true);

                        $('#so_ingreso option').filter(function() {
                            return $(this).val() == item.IdSo;
                        }).prop('selected', true);

                        $('#bits_so_ingreso option').filter(function() {
                            return $(this).val() == item.BitsSo;
                        }).prop('selected', true);

                        $('#ram_ingreso option').filter(function() {
                            return $(this).val() == item.Ram;
                        }).prop('selected', true);

                        $('#tipo_disco_ingreso option').filter(function() {
                            return $(this).val() == item.IdTypeDisc;
                        }).prop('selected', true);

                        setDiscCapacity(item.IdTypeDisc);

                        $('#cantidad_disco_ingreso option').filter(function() {
                            return $(this).val() == item.IdTypeDisc;
                        }).prop('selected', true);
                    break;
                }
            }

            $('#tipo_disco_ingreso').change(function(){
                let tipo = $(this).val();

                $('#cantidad_disco_ingreso').find('option:not(:first)').remove();
                $('#cantidad_disco_ingreso').prop('selectedIndex',0);

                if(tipo == '' || tipo == undefined){
                    return false;
                }

                let disc = Discos.find(d => d.Id == tipo);
                let o;

                disc.Discs.forEach(function(d){
                    let tamaño = parseInt(d.nCapacidad);
                    tamaño = (tamaño >= 1000) ? d.nCapacidad.substring(0,1)+' TB' : d.nCapacidad+' GB';

                    o = new Option(tamaño, d.id);
                    $(o).html(tamaño);

                    $("#cantidad_disco_ingreso").append(o);
                });
            });

        <?php } ?>

        $(function() {  
            drop = $("div#dropzone").dropzone({     
                autoProcessQueue: false,
                url: "controller/",
                params: {
                     pag: "Ticket",
                     tipo: "ar",
                },
                paramName: 'file',
                clickable: true,
                maxFilesize: 5,
                uploadMultiple: true, 
                maxFiles: 5,
                addRemoveLinks: true,
                acceptedFiles: '.png,.jpg,.pdf,.doc.docx,.txt,.xlsx',
                dictDefaultMessage: 'Da click aquí o arrastra tus archivos y sueltalos aqui.',
                init: function () {
                    myDropzone = this;
                    // Update selector to match your button

                    this.on('sending', function(file, xhr, formData) {
                        // Append all form inputs to the formData Dropzone will POST
                        formData.append('ticket', ticket);
                        var data = $('#dropzone').serializeArray();
                        $.each(data, function(key, el) {
                            FP.append(el.name, el.value);
                            
                        });
                    });

                    this.on("success", function(file, responseText) {
                        console.log(responseText);
                    });

                    this.on("error", function(file, responseText) {
                        console.log(responseText);
                        swal('No se cargaron los archivos','Formato no permitido','error')
                    });
                }
            });
        });

        var motivo = 0;

        $('.sub_motivo').click(function(e){
            e.preventDefault();

            let parent = $(this).parent().parent().parent().find('span')[0].innerHTML;

            $('#menu-title').text('');
            $('#menu-title').text(parent+'/'+$(this).text());

            motivo = $(this)[0].id;

            switch($(this).text()){
                case 'ALTA DE USUARIO':
                    reset();
                break;
                default:
                    reset();
                break;
            }
        });

        var ingresoEquipo = 0;
        $('#ingreso_de_equipo').change(function() {
            if(this.checked) {
                ingresoEquipo = 1;
                $('#ingreso_pc').removeClass('hide');

                $('#ingreso_pc').find('input').each(function() {
                    $(this).removeAttr('disabled');
                });

                $('#ingreso_pc').find('select').each(function() {
                    $(this).removeAttr('disabled');
                });

                $('#ingreso_pc').find('textarea').each(function() {
                    $(this).removeAttr('disabled');
                });
            }else{
                ingresoEquipo = 0;
                $('#ingreso_pc').addClass('hide');

                $('#ingreso_pc').find('input').each(function() {
                    $(this).attr('disabled','true');
                });

                $('#ingreso_pc').find('select').each(function() {
                    $(this).attr('disabled','true');
                });

                $('#ingreso_pc').find('textarea').each(function() {
                    $(this).attr('disabled','true');
                });
            }
        });

        function reset(){
            $('#alta_user').find('input').each(function() {
            $(this).attr('disabled',true);
            });
            $('#alta_user').find('select').each(function() {
                $(this).attr('disabled',true);
            });

            $('#alta_user').addClass('hide');

            $('#telefono_ip').find('input').each(function() {
                $(this).attr('disabled',true);
            });
            $('#telefono_ip').addClass('hide');
        }

        function openInNewTab(url) {
            let win = window.open(url, '_blank', 'noreferrer');            
        }

        function validate($array){
            let valid = true;
            $array.each(function() {
                let attr = $(this).attr('disabled');
                if (typeof attr === typeof undefined && attr === false) {
                    if(($.trim($(this).val()) == "" || $.trim($(this).val()) <= 0) ){
                        valid = false;
                    }
                }
            });
            return valid;
        }

        $(document).on('submit', '#ticket', function(e){
            e.preventDefault();
            // return false;
            if(validate($(this).find('.required')) && motivo != 0){
                $('#loading').modal({backdrop: 'static', keyboard: false});
                
                $.ajax({
                    type: "POST",
                    url: "controller/",
                    data: $(this).serialize()+"&motivo="+motivo+"&ingreso="+ingresoEquipo+"&pag="+document.title+"&tipo=i",
                    dataType: "json",
                })
                .fail(function(data){
                    console.log(data)
                    $('#loading').modal('hide');
                    mensaje('fail','Error Peticion ajax');
                })
                .done(function(data){
                    $('#loading').modal('hide');
                    console.log(data)
                    switch(data.Status){
                        case 'Success':
                            swal('Su numero de ticket es '+data.Ticket,'','success')
                            $("#ticket")[0].reset();
                            reset();
                            motivo = 0;

                            ticket = data.Id;
                            $('#dropzone')[0].dropzone.processQueue();

                            if(ingresoEquipo != 0){
                                openInNewTab('http://192.168.122.180/portal-escobar/Programas/tickets/descargar-ticket?ticket='+data.Ticket);
                            }

                            $('#menu-title').text('SELECCIONE UN MOTIVO');
                        break;
                        case 'Invalid call':
                            mensaje('fail','Por favor complete todos los datos solicitados');
                        break;
                        case 'Invalid User':
                            mensaje('fail','Numero de legajo invalido');
                        break;
                        case 'Invalid User Form':
                            mensaje('fail','Por favor complete el formulario de ABM del usuario');
                        break;
                        case 'Invalid Equipo':
                            //mensaje('fail','Nº de equipo o Nº de patrimonio duplicados');
                            swal('Su numero de ticket es '+data.Ticket,'','success')
                            $("#ticket")[0].reset();
                            reset();
                            motivo = 0;

                            ticket = data.Id;
                            $('#dropzone')[0].dropzone.processQueue();

                            if(ingresoEquipo != 0){
                                openInNewTab('http://192.168.122.180/portal-escobar/Programas/tickets/descargar-ticket?ticket='+data.Ticket);
                            }

                            $('#menu-title').text('SELECCIONE UN MOTIVO');
                        break;
                        case 'Existing User':
                            mensaje('fail','El Dni o el Legajo ingresado ya existen');
                        break;
                        case 'Error':
                            mensaje('fail','No se pudo agregar el motivo');
                        break;
                    }
                });
            }else{
                mensaje('fail','Por favor seleccione un motivo');
            }
        });
        function setDiscCapacity(tipo){
            $('#cantidad_disco_ingreso').find('option:not(:first)').remove();
            $('#cantidad_disco_ingreso').prop('selectedIndex',0);

            if(tipo == '' || tipo == undefined){
                return false;
            }

            let disc = Discos.find(d => d.Id == tipo);
            let o;

            disc.Discs.forEach(function(d){
                let tamaño = parseInt(d.nCapacidad);
                tamaño = (tamaño >= 1000) ? d.nCapacidad.substring(0,1)+' TB' : d.nCapacidad+' GB';

                o = new Option(tamaño, d.id);
                $(o).html(tamaño);

                $("#cantidad_disco_ingreso").append(o);
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
  }
?>