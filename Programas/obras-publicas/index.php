<?php
    require_once('controller/obraController.php');
    
    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["OBRAS_PUBLICAS"])){
            require 'controller/jurisdiccionController.php';
            require 'controller/objetoGastoController.php';
            require 'controller/fuenteController.php';
            require 'controller/unidadEjecutoraController.php';
            require 'controller/proveedorController.php';
            require 'controller/modalidadController.php';
            require 'controller/estadoController.php';
            require 'controller/tipoObraController.php';
            require 'controller/afectacionController.php';
            require 'controller/proyectoController.php';
            
            $Jurisdiccion = new jurisdiccion();
            $UEjecutora = new uEjecutora();
            $Afectacion = new afectacion();
            $Proveedor = new proveedor();
            $Modalidad = new modalidad();
            $TipoObra = new tipoObra();
            $Proyecto = new proyecto();
            $Estado = new estado();
            $Fuente = new fuente();
            $Obra = new obra();
            $OG = new gasto();

            $listObras = $Obra->getObras();

            $listJurisdicciones = $Jurisdiccion->getJurisdicciones();
            $listEjecutoras = $UEjecutora->getUnidadesEjecutoras();
            $listAfectaciones = $Afectacion->getAfectaciones();
            $listModalidades = $Modalidad->getModalidades();
            $listProveedres = $Proveedor->getProveedores();            
            $listTiposObra = $TipoObra->getTiposObra();
            $listProyectos = $Proyecto->getProyectos();
            $listFuentes = $Fuente->getFuentes();
            $listEstados = $Estado->getEstados();            
            $listOG = $OG->getObjetosGasto(); 

            $years = '';
            $years2 = '';
            
            for($i = 2018 ; $i <= date('Y') + 1; $i++){
                $s = ($i == date('Y')) ? 'selected' : '';
                $years .= '<option value=\''.$i.'\' '.$s.'>'.$i.'</option>';
            }

            for($i = 2018 ; $i <= date('Y') + 1; $i++){
                $years2 .= '<option value=\''.$i.'\'>'.$i.'</option>';
            }

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Obras Publicas - Obras</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="img/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/icons2.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
    <style>
        .page-title-md{
            font-size: 2rem;
            margin-top: 0;
            margin-bottom: .5rem;
            color: var(--colorTitle);
        }

        .hide-overflow{
            width: 300px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
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
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-office"></span> OBRAS</h1>
            </header>
            <section class="page-section">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-link active obra-name" id="nav-newobra-tab" data-toggle="tab" href="#nav-newobra" role="tab" aria-controls="nav-newobra" aria-selected="true">NUEVA OBRA</a>
                        <a class="nav-link obra-consultar" id="nav-conslt-tab" data-toggle="tab" href="#nav-conslt" role="tab" aria-controls="nav-conslt" aria-selected="false">CONSULTAR OBRA</a>
                    </div>
                </nav>
                <div class="card">
                    <div class="tab-content row" id="nav-tabContent">
                        <div class="tab-pane fade show active col-md-12" id="nav-newobra" role="tabpanel" aria-labelledby="nav-newobra-tab">
                            <div class="btn-add">
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-link active" id="nav-dataobra-tab" data-toggle="tab" href="#nav-dataobra" role="tab" aria-controls="nav-dataobra" aria-selected="true">DATOS DE LA OBRA</a>
                                        <a class="nav-link" id="nav-dataadi-tab" data-toggle="tab" href="#nav-dataadi" role="tab" aria-controls="nav-dataadi" aria-selected="true">DATOS ADICIONALES</a>
                                        <a class="nav-link" id="nav-cert-tab" data-toggle="tab" href="#nav-cert" role="tab" aria-controls="nav-cert" aria-selected="false">CERTIFICADOS</a>
                                    </div>
                                </nav>                                
                            </div>
                                <!-- idObra, cNombre, nExpte,nExpteAnio, nEstimado, nDefinitivo, cImputado, nPagado,
                                nModalidad,nModalidadAnio, nPlazo, dApertura, dCircuito, dLlamado, nLlamadoAnio, dPropuesta, cPropuestaHora,
                                nAdjudicacion, nAdjudicacionAnio, dContrato, nContrato, dInicioObra, dFinObra, dRecepcion, cObs, idProyecto,
                                idTipoObra, idEstado, idUnidadEjecutora, idModalidad -->
                                
                            <div class="tab-content row form-row" id="nav-tabContent" >                                 
                                <div class="tab-pane fade show active col-md-12" id="nav-dataobra" role="tabpanel" aria-labelledby="nav-dataobra-tab">
                                    <form method="post" name="form_obras" id="form_obras" class="" autocomplete="off">
                                        <div class="form-group col-md-6">
                                            <label for="obra">NOMBRE DE OBRA*: </label>
                                            <input type="text" class="form-control required" name="obra" id="obra" placeholder="Obra..." required="true">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="proyecto">PROYECTO*: </label>
                                            <select class="form-control" name="proyecto" id="proyecto">
                                                <option value="" selected>SIN DEFINIR</option>
                                                <?php 
                                                    foreach ($listProyectos as $key => $value) {
                                                        echo '<option value=\''.$value['idProyecto'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="expediente">Nº EXPTE.*: </label>
                                            <div class="input-group">
                                                <input class="form-control only-number required" type="number" min="0" name="expediente" id="expediente" placeholder="Expediente..." required>
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="anio_expediente" id="anio_expediente">
                                                        <?php 
                                                            echo $years;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="u_ejecutora">UNIDAD EJECUTORA: </label>
                                            <select class="form-control" name="u_ejecutora" id="u_ejecutora" require>
                                                <option value="SIN_DEFINIR" selected>SIN DEFINIR</option>
                                                <?php 
                                                    foreach ($listEjecutoras as $key => $value) {
                                                        echo '<option value=\''.$value['idUnidadEjecutora'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="proveedor">PROVEEDOR*: </label>
                                            <select class="form-control" name="proveedor" id="proveedor" required>
                                                <option value="" disabled selected>SELECCIONE EL PROVEEDOR</option>
                                                <?php 
                                                    foreach ($listProveedres as $key => $value) {
                                                        echo '<option value=\''.$value['idProveedor'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="importe_est">IMPORTE ESTIMADO*: </label>
                                            <input type="text" class="form-control only-number required admit-coma" min="0" name="importe_est" id="importe_est" placeholder="Estimado..." required="true">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="importe_def">IMPORTE DEFINITIVO*: </label>
                                            <input type="text" class="form-control only-number required admit-coma" min="0" name="importe_def" id="importe_def" placeholder="Definitivo..." required="true">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="imputado">IMPUTADO*: </label>
                                            <select class="form-control" name="imputado" id="imputado">
                                                <option value="NO">NO</option>
                                                <option value="SI">SI</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab-ref" role="tablist">
                                                    <a class="nav-link active" id="nav-compromiso-tab" data-toggle="tab" href="#nav-compromiso" role="tab" aria-controls="nav-compromiso" aria-selected="true">REGISTRO DE COMPROMISO [<span id="cant_rc">0</span>]</a>
                                                    <a class="nav-link" id="nav-op-tab" data-toggle="tab" href="#nav-op" role="tab" aria-controls="nav-op" aria-selected="false">ORDENES DE PAGO / OCEA [<span id="cant_op">0</span>]</a>
                                                    <a class="nav-link" id="nav-imp-tab" data-toggle="tab" href="#nav-imp" role="tab" aria-controls="nav-imp" aria-selected="false">IMPUTACIONES [<span id="cant_imp">0</span>]</a>
                                                </div>
                                            </nav>
                                        </div>
                                        <div class="tab-content row" id="nav-tabContent">
                                            <div class="tab-pane fade show active col-md-12" id="nav-compromiso" role="tabpanel" aria-labelledby="nav-compromiso-tab">
                                                <div class="form-group col-md-12 table-responsive">
                                                    <h3 class="page-title">REGISTRO DE COMPROMISO:</h3>
                                                    <div class="col-md-3 form-group">
                                                        <label for="numero_compromiso">NÚMERO:</label>
                                                        <input type="number" class="form-control only-number" min="0" id="numero_compromiso" placeholder="Número...">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="fecha_compromiso">FECHA:</label>
                                                        <input type="date" class="form-control" id="fecha_compromiso">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="importe_compromiso">IMPORTE</label>
                                                        <input type="number" class="form-control only-number admit-coma" min="0" id="importe_compromiso" placeholder="Importe...">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <button type="button" class="btn btn-primary" id="add_compromiso" style="margin-top: 2.5rem;">AÑADIR</button>
                                                    </div>
                                                    <table class="table table-bordered table-striped" id="table_compromiso">
                                                        <thead>
                                                            <tr>
                                                                <th>NÚMERO</th>
                                                                <th>FECHA</th>
                                                                <th>IMPORTE</th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade col-md-12" id="nav-op" role="tabpanel" aria-labelledby="nav-op-tab">
                                                <div class="form-group col-md-12 table-responsive">
                                                    <h3 class="page-title">ORDENES DE PAGO / OCEA:</h3>
                                                    <div class="col-md-3 form-group">
                                                        <label for="fecha_op">FECHA:</label>
                                                        <input type="date" class="form-control" id="fecha_op">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="numero_op">NÚMERO:</label>
                                                        <input type="number" class="form-control only-number" min="0" id="numero_op" placeholder="Número...">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="importe_op">IMPORTE:</label>
                                                        <input type="number" class="form-control only-number admit-coma" min="0" id="importe_op" placeholder="Importe...">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="pagado_op">PAGADO:</label>
                                                        <input type="date" class="form-control" id="pagado_op">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="osea_op">OCEA:</label>
                                                        <select class="form-control" id="osea_op">
                                                            <option value="SI">SI</option>
                                                            <option value="NO" selected>NO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group ocea_si" style="display: none;">
                                                        <label for="ocea_numero_op">NÚMERO:</label>
                                                        <input type="number" class="form-control number-only" min="0" id="ocea_numero_op" placeholder="Numero Ocea...">
                                                    </div>
                                                    <div class="col-md-3 form-group ocea_si" style="display: none;">
                                                        <label for="ocea_fecha_op">FECHA:</label>
                                                        <input type="date" class="form-control" id="ocea_fecha_op">
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <button type="button" class="btn btn-primary" id="add_op" style="margin-top: 2.5rem;">AÑADIR</button>
                                                    </div>
                                                    <table class="table table-bordered table-striped table-responsive" id="table_op">
                                                        <thead>
                                                            <th>FECHA</th>
                                                            <th>Nº</th>
                                                            <th>IMPORTE</th>
                                                            <th>PAGADO</th>
                                                            <th>OCEA</th>
                                                            <th>NUMERO OCEA</th>
                                                            <th>FECHA OCEA</th>
                                                            <th></th>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade col-md-12" id="nav-imp" role="tabpanel" aria-labelledby="nav-imp-tab">
                                                <div class="form-group col-md-12">
                                                    <h3 class="page-title">IMPUTACIONES:</h3>
                                                    <div class="col-md-3 form-group">
                                                        <label for="jurisdiccion_imp">JURISDICCIÓN:</label>
                                                        <select class="form-control" id="jurisdiccion_imp">
                                                            <option value="" disabled selected>SELECCIONE LA JURISDICCIÓN</option>
                                                            <?php 
                                                                foreach ($listJurisdicciones as $key => $value) {
                                                                    echo '<option value=\''.$value['idJurisdiccion'].'\'>'.$value['cCodigo'].' - '.$value['cNombre'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="cat_prog_imp">CATEGORIA PROG:</label>
                                                        <div class="input-group">
                                                            <input class="form-control cat-prod only-number" type="text" id="catprod1_imp" placeholder="Cat. Prod...">
                                                            <div class="input-group-addon select-input">
                                                                <input class="form-control only-number cat-prod" type="text" id="catprod2_imp" placeholder="Cat. Prod...">
                                                            </div>
                                                            <div class="input-group-addon select-input">
                                                                <input class="form-control only-number cat-prod" type="text" id="catprod3_imp" placeholder="Cat. Prod...">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="fuente_imp">FUENTE:</label>
                                                        <select class="form-control" id="fuente_imp">
                                                            <option value="" disabled selected>SELECCIONE LA FUENTE</option>
                                                            <?php 
                                                                foreach ($listFuentes as $key => $value) {
                                                                    echo '<option value=\''.$value['idFuente'].'\'>'.$value['cCodigo'].' - '.$value['cNombre'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <label for="gasto_imp">OBJ. GASTO:</label>
                                                        <select class="form-control" id="gasto_imp">
                                                            <option value="" disabled selected>SELECCIONE EL OBJ. GASTO</option>
                                                            <?php 
                                                                foreach ($listOG as $key => $value) {
                                                                    echo '<option value=\''.$value['idObjetoGasto'].'\'>'.$value['cCodigo'].' - '.$value['cNombre'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <!-- <div class="col-md-3 form-group">
                                                        <label for="denominacion_imp">DENOMINACIÓN:</label>
                                                        <input type="text" class="form-control" id="denominacion_imp" placeholder="Denominación...">
                                                    </div> -->
                                                    <div class="col-md-6 form-group">
                                                        <label for="afectacion_imp">AFECTACIÓN:</label>
                                                        <select class="form-control" id="afectacion_imp">
                                                            <option value="" disabled selected>SELECCIONE LA AFECTACION</option>
                                                            <?php 
                                                                foreach ($listAfectaciones as $key => $value) {
                                                                    echo '<option value=\''.$value['idAfectacion'].'\'>'.$value['cCodigo'].' - '.$value['cNombre'].'</option>';
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 form-group">
                                                        <button type="button" class="btn btn-primary" id="add_imputacion" style="margin-top: 2.5rem;">AÑADIR</button>
                                                    </div>
                                                    <div class=" col-md-12 table-responsive">
                                                        <table class="table table-bordered table-striped" id="table_imputacion">
                                                            <thead>
                                                                <th>JURISDICCIÓN</th>
                                                                <th>CAT. PROG</th>
                                                                <th>FUENTE</th>
                                                                <th>OBJ. GASTO</th>
                                                                <!-- <th>DENOMINACIÓN</th> -->
                                                                <th>AFECTACIÓN</th>
                                                                <th></th>
                                                            </thead>
                                                            <tbody></tbody>
                                                        </table>
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="pagado">PAGADO*: </label>
                                            <input type="text" class="form-control only-number required disabled" name="pagado" id="pagado" placeholder="Definitivo..." required="true" disabled>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="a_pagar">SALDO A PAGAR*: </label>
                                            <input type="text" class="form-control required disabled" name="a_pagar" id="a_pagar" placeholder="Definitivo..." required="true" disabled>
                                        </div>
                                        <!-- <div class="form-group col-md-4">
                                            <label for="falta_pagar">FALTA PAGAR*: </label>
                                            <input type="text" class="form-control" id="falta_pagar" placeholder="Falta pagar..." disabled>
                                        </div> -->
                                        <div class="form-group col-md-12">
                                            <label for="observaciones">OBSERVACIONES*: </label>
                                            <textarea class="form-control" name="observaciones" id="observaciones" cols="20" rows="5" placeholder="Observaciones..."></textarea>
                                        </div>                                      
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-danger btn-md pull-left nueva-obra" id="holasd" data-dismiss="modal" name="cancel" value="Nueva Obra">
                                            <button class="btn btn-primary pull-right" type="submit">Guardar datos</button>
                                        </div>                                    
                                    </form>
                                </div>                                
                                <div class="tab-pane fade col-md-12" id="nav-dataadi" role="tabpanel" aria-labelledby="nav-dataadi-tab">
                                    <form method="post" name="form_adicionales" id="form_adicionales" class="" autocomplete="off">
                                        <div class="form-group col-md-3">
                                            <label for="modalidad_ad">MODALIDAD DE CONTRATACIÓN: </label>                                            
                                            <select class="form-control" name="modalidad_ad" id="modalidad_ad">
                                                <option value="SIN_DEFINIR" selected>SIN DEFINIR</option>
                                                <?php 
                                                    foreach ($listModalidades as $key => $value) {
                                                        echo '<option value=\''.$value['idModalidad'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="modnumero_ad">Nº Y AÑO</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control" min="0" name="modnumero_ad" id="modnumero_ad" placeholder="Número...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="anio_modalidad" id="anio_modalidad">
                                                        <?php 
                                                            echo $years;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="estado_ad">ESTADO: </label>
                                            <select class="form-control" name="estado_ad" id="estado_ad">
                                                <option value="SIN_DEFINIR" selected>SIN DEFINIR</option>
                                                <?php 
                                                    foreach ($listEstados as $key => $value) {
                                                        echo '<option value=\''.$value['idEstado'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="tipoobra_ad">TIPO DE OBRA: </label>
                                            <select class="form-control" name="tipoobra_ad" id="tipoobra_ad">
                                                <option value="SIN_DEFINIR" selected>SIN DEFINIR</option>
                                                <?php 
                                                    foreach ($listTiposObra as $key => $value) {
                                                        echo '<option value=\''.$value['idObraTipo'].'\'>'.$value['cNombre'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="plazo_ad">PLAZO DE DURACIÓN: </label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="plazo_ad" id="plazo_ad" placeholder="Plazo...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="tipoplazo_ad" id="tipoplazo_ad">
                                                        <option value="DIA">DÍAS</option>
                                                        <option value="MES">MES</option>
                                                        <option value="AÑO">AÑO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <input type="number" class="form-control only-number required" min="0" name="plazo_ad" id="plazo_ad" placeholder="Dias..."> -->
                                        </div>
                                        <div class="form-group col-md-12">
                                            <h3 class="page-title">FECHAS:</h3>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="apertura_ad">APERTURA DE EXPTE.:</label>
                                            <input type="date" class="form-control" name="apertura_ad" id="apertura_ad" >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="inicio_ad">INICIO DE CIRCUITO:</label>
                                            <input type="date" class="form-control" name="inicio_ad" id="inicio_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="decllamado_ad">DEC. LLAMADO:</label>
                                            <input type="date" class="form-control" name="decllamado_ad" id="decllamado_ad" >
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="nllamado_ad">Nº Y AÑO:</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="nllamado_ad" id="nllamado_ad" placeholder="Número...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="aniollamado_ad" id="aniollamado_ad">
                                                        <?php 
                                                            echo $years;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="aperturapr_ad">APERTURA PROPUESTA:</label>
                                            <input type="date" class="form-control" name="aperturapr_ad" id="aperturapr_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="folio_ad">FOLIO:</label>
                                            <input type="number" class="form-control only-number" min="0" name="folio_ad" id="folio_ad" placeholder="Folio...">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="hora_ad">HORA APERTURA PROPUESTA:</label>
                                            <input type="time" class="form-control" name="hora_ad" id="hora_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="dAdjudicacion">DEC. DE ADJUDICACION:</label>
                                            <input type="date" class="form-control" name="dAdjudicacion" id="dAdjudicacion" >
                                        </div>
                                        <div class="form-group col-md-8">
                                            <label for="nAdjudicacion_ad">Nº Y AÑO:</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="nAdjudicacion_ad" id="nAdjudicacion_ad" placeholder="Número...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="anioAdjudicacion_ad" id="anioAdjudicacion_ad">
                                                        <?php 
                                                            echo $years;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="contrato_ad">CONTRATO:</label>
                                            <input type="date" class="form-control" name="contrato_ad" id="contrato_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="cfolio_ad">FOLIO:</label>
                                            <input type="number" class="form-control only-number" min="0" name="cfolio_ad" id="cfolio_ad" placeholder="Folio...">
                                        </div>
                                        <div class="row"></div>
                                        <div class="form-group col-md-4">
                                            <label for="obinicio_ad">INICIO DE OBRA:</label>
                                            <input type="date" class="form-control" name="obinicio_ad" id="obinicio_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="fin_ad">FIN DE OBRA:</label>
                                            <input type="date" class="form-control" name="fin_ad" id="fin_ad" >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="recepcion_ad">RECEPCIÓN PROVISORIA DE OBRA:</label>
                                            <input type="date" class="form-control" name="recepcion_ad" id="recepcion_ad" >
                                        </div>
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-danger btn-md pull-left nueva-obra" data-dismiss="modal" name="cancel" value="Nueva Obra">
                                            <button class="btn btn-primary pull-right" type="submit">Guardar datos</button>
                                        </div>
                                    </form>
                                </div>                                
                                <div class="tab-pane fade col-md-12" id="nav-cert" role="tabpanel" aria-labelledby="nav-cert-tab">
                                    <form method="post" name="form_certificado" id="form_certificado" class="" autocomplete="off">
                                        <div class="form-group col-md-4">
                                            <label for="nro_cert">NUMERO:</label>
                                            <select class="form-control" name="nro_cert" id="nro_cert" required>
                                                <option value="SIN_DEFINIR" disabled selected>SIN DEFINIR</option>
                                                <option value="UNICO">UNICO</option>
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                                <option value="6">6</option>
                                                <option value="7">7</option>
                                                <option value="8">8</option>
                                                <option value="9">9</option>
                                                <option value="10">10</option>
                                                <option value="FINAL">FINAL</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="periodo_cert">PERIODO DE OBRA:</label>
                                            <input type="text" class="form-control" name="periodo_cert" id="periodo_cert" placeholder="Periodo...">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="importe_cert">IMPORTE $:</label>
                                            <input type="text" class="form-control only-number to-miles" min="0" name="importe_cert" id="importe_cert" placeholder="Importe...">
                                        </div>
                                        
                                        <div class="form-group col-md-4">
                                            <label for="ampliacion_cert">AMPLIACIÓN:</label>
                                            <input type="text" class="form-control" name="ampliacion_cert" id="ampliacion_cert" placeholder="Ampliación..." >
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="dcontrato_cert">FECHA DE CONTRATO:</label>
                                            <input type="date" class="form-control" name="dcontrato_cert" id="dcontrato_cert">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="importe2_cert">IMPORTE $:</label>
                                            <input type="text" class="form-control only-number to-miles" min="0" name="importe2_cert" id="importe2_cert" placeholder="Importe...">
                                        </div>
                                        <div class="row"></div>
                                        <hr>
                                        <div class="form-group col-md-4">
                                            <label for="prorroga1_cert">PRORROGA:</label>
                                            <input type="date" class="form-control" name="prorroga1_cert" id="prorroga1_cert">
                                        </div>
                                        <div class="form-group col-md-4">                                            
                                            <label for="plazo1_cert">PLAZO:</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="plazo1_cert" id="plazo1_cert" placeholder="Plazo...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="tipoplazo1_cert" id="tipoplazo1_cert">
                                                        <option value="DIA">DIAS</option>
                                                        <option value="MES">MES</option>
                                                        <option value="AÑO">AÑO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <input type="number" class="form-control only-number" min="0" name="plazo1_cert" id="plazo1_cert" placeholder="Dias..."> -->
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="terminacion1_cert">TERMINACIÓN:</label>
                                            <input type="date" class="form-control" name="terminacion1_cert" id="terminacion1_cert">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="prorroga2_cert">PRORROGA:</label>
                                            <input type="date" class="form-control" name="prorroga2_cert" id="prorroga2_cert">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="plazo2_cert">PLAZO EN DÍAS:</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="plazo2_cert" id="plazo2_cert" placeholder="Plazo...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="tipoplazo2_cert" id="tipoplazo2_cert">
                                                        <option value="DIA">DIAS</option>
                                                        <option value="MES">MES</option>
                                                        <option value="AÑO">AÑO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <input type="number" class="form-control only-number" min="0" name="plazo2_cert" id="plazo2_cert" placeholder="Dias..."> -->
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="terminacion2_cert">TERMINACIÓN:</label>
                                            <input type="date" class="form-control" name="terminacion2_cert" id="terminacion2_cert">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="plazogarantia_cert">PLAZO DE GARANTIA DE OBRA:</label>
                                            <div class="input-group">
                                                <input class="form-control" type="number" min="0" name="plazogarantia_cert" id="plazogarantia_cert" placeholder="Plazo...">
                                                <div class="input-group-addon select-input">
                                                    <select class="form-control" name="tipoplazo3_cert" id="tipoplazo3_cert">
                                                        <option value="DIA">DÍAS</option>
                                                        <option value="MES">MES</option>
                                                        <option value="AÑO">AÑO</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <input type="number" class="form-control only-number" min="0" name="plazogarantia_cert" id="plazogarantia_cert" placeholder="Dias..."> -->
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="devolucion_cert">FECHA DEVOLUCIÓN FONDO DE REPARO:</label>
                                            <input type="date" class="form-control" name="devolucion_cert" id="devolucion_cert">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="observaciones_cert">OBSERVACIONES:</label>
                                            <textarea class="form-control" name="observaciones_cert" id="observaciones_cert" cols="10" rows="10"></textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="col-md-12">
                                                <label for="certobra_cert">CERTIFICADO DE OBRA $:</label>
                                                <input type="text" class="form-control only-number to-miles" min="0" name="certobra_cert" id="certobra_cert" placeholder="Cert. De Obra...">
                                            </div>  
                                            <div class="col-md-12">
                                                <label for="retencion_cert">RETENCIÓN $:</label>
                                                <div class="input-group">
                                                    <input class="form-control" type="text" name="retencion_cert" id="retencion_cert" placeholder="Retención...">
                                                    <div class="input-group-addon select-input">
                                                       <input type="text" min="0" class="form-control only-number to-miles" name="imretencion_cert" id="imretencion_cert" placeholder="Importe...">
                                                    </div>
                                                </div>
                                                <!-- <input type="number" class="form-control only-number" name="imretencion_cert" id="imretencion_cert"> -->
                                            </div>  
                                            <div class="col-md-12">
                                                <label for="total_cert">TOTAL $:</label>
                                                <input type="text" min="0" class="form-control only-number disabled" name="total_cert" id="total_cert" placeholder="Total..." disabled>
                                            </div>                                            
                                        </div>
                                        <div class="col-md-12">
                                            <input type="button" class="btn btn-danger btn-md pull-left nueva-obra" data-dismiss="modal" value="Nueva Obra">
                                            <button class="btn btn-primary pull-right" type="submit">Guardar datos</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade col-md-12" id="nav-conslt" role="tabpanel" aria-labelledby="nav-conslt-tab">
                            <div id="filter_div">
                                <h3 class="page-title-md">FILTROS</h3>
                                <div class="form-group col-md-3">
                                    <label for="filter_desde">DESDE:</label>
                                    <input type="date" id="filter_desde" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_hasta">HASTA:</label>
                                    <input type="date" id="filter_hasta" class="form-control">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_estado">ESTADO:</label>
                                    <select class="form-control" id="filter_estado">
                                        <option value="" selected>TODOS</option>
                                        <?php 
                                            foreach ($listEstados as $key => $value) {
                                                echo '<option value=\''.$value['idEstado'].'\'>'.$value['cNombre'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_obra">OBRA:</label>
                                    <input type="text" id="filter_obra" class="form-control" placeholder="Obra...">
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_expt">EXPEDIENTE:</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" id="filter_expt" placeholder="Expediente...">
                                        <div class="input-group-addon select-input">
                                            <select class="form-control" id="filter_expt_anio">
                                                <option value="" selected>TODOS</option>
                                                <?php 
                                                    echo $years2;
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_ejecutora">UNIDAD EJECUTORA:</label>
                                    <select class="form-control" id="filter_ejecutora">
                                        <option value="" selected>TODAS</option>
                                        <?php 
                                            foreach ($listEjecutoras as $key => $value) {
                                                echo '<option value=\''.$value['idUnidadEjecutora'].'\'>'.$value['cNombre'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_imputado">IMPUTADO:</label>
                                    <select class="form-control" id="filter_imputado">
                                        <option value="">TODO</option>
                                        <option value="SI">SI</option>
                                        <option value="NO">NO</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="filter_proveedor">PROVEEDOR:</label>
                                    <select class="form-control" id="filter_proveedor">
                                        <option value="" selected>TODOS</option>
                                        <?php 
                                            foreach ($listProveedres as $key => $value) {
                                                echo '<option value=\''.$value['idProveedor'].'\'>'.$value['cNombre'].'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12 ">
                                <table id="tb_obra" class="table table-striped table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>OBRA</th>
                                            <th>Nº EXPTE.</th>
                                            <th>PROVEEDOR</th>
                                            <th>IMPORTE DEFINITIVO</th>
                                            <th>IMPUTADO</th>
                                            <th>PAGADO</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="btn-add form-group">
                                <a href="#" class="btn btn-success" style="float: left;" id="import_to_excel">Exportar a Excel</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <?php include 'footer.php'; ?> 
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script src="js/xls-export.js"></script>
    <script type="text/javascript">
        
        const Obras = <?php echo json_encode($listObras);?>;
        const Proveedores = <?php echo json_encode($listProveedres);?>;
        const Ejecutoras = <?php echo json_encode($listEjecutoras);?>;
        const Modalidades = <?php echo json_encode($listModalidades);?>;
        const Estados = <?php echo json_encode($listEstados);?>;
        const TiposObras = <?php echo json_encode($listTiposObra);?>;

        var compromisos = [];
        var ordenesPago = [];
        var imputaciones = [];

        let actualObra = [];

        var pagado = 0, saldoAPagar = 0;

        let activeObra = 0;

        let type = 'r';
        let id = 0, certificado = 0;
        let DataTable;
        let $tr;

        $(document).ready(function(){
            DataTable = $('#tb_obra').DataTable({
                "data": Obras,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "responsive": true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "nombre",
                        "render":function(data, type, full, meta){
                            return '<div class=\'hide-overflow\'>'+full.nombre+'</div>';
                        }
                    },
                    { "data": "expediente"},
                    { "data": "proveedor",
                        "render":function(data, type, full, meta){
                            let found = Proveedores.find(p => p.idProveedor == full.idProveedor);
                            return found.cNombre;
                        }
                    },
                    { "data": "importeDefinitivo",
                        "render":function(data, type, full, meta){
                            return formatNumber(full.importeDefinitivo);
                        }
                    },
                    { "data": "imputado"},
                    { "data": "pagado",
                        "render":function(data, type, full, meta){
                            let paid = full.ordenesPago.filter(op => op.pagado != '').reduce((acc, currentOrder) => {
                                return acc + parseInt(currentOrder.importe);
                            }, 0);

                            return formatNumber(paid);
                        }
                    },
                    { "data": "acciones",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Ver\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-eye btn btn-md btn-primary view\' value=\''+full.id+'\'></button>'+
                            '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.id+'\'></button>'+
                            '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.id+'\'></button> ';
                        }
                    },
                ],
            });
        });

        $(document).on('click','.view', function(e){
            $('#nav-newobra input').prop('disabled', true);
            $('#nav-newobra select').prop('disabled', true);
            $('#nav-newobra button').prop('disabled', true);

            $('.nueva-obra').prop('disabled',false);
            
            id = $(this).val();
            actualObra = Obras.find(m => m.id == id);

            if(actualObra == undefined){
                id = 0;
                type = 'r';
                $tr = undefined;

                return swal('Error','No se encontro la obra, pruebe refrescando la pagina, si el problema persiste comuniquese con el administrador','warning');
            }

            setObra(actualObra);

            $('.obra-name').text(actualObra.nombre);
            $('#nav-conslt-tab').click();
            $('#nav-newobra-tab').tab('show');
        });

        $(document).on('click','.edit', function(e){
            $('#holasd').click();
            
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            actualObra = Obras.find(m => m.id == id);

            if(actualObra == undefined){
                id = 0;
                type = 'r';
                $tr = undefined;

                return swal('Error','No se encontro la obra, pruebe refrescando la pagina, si el problema persiste comuniquese con el administrador','warning');
            }

            setObra(actualObra);

            $('.obra-name').text(actualObra.nombre);
            $('#nav-conslt-tab').click();
            $('#nav-newobra-tab').tab('show');
        });

        $(document).on('click','.delete', function(e){
            id = $(this).val();
            $tr = $(this).parents('tr');
            swal({
                title: "¡Eliminando Obra!",
                text: "¿Seguro que desea Eliminar esta obra?",
                icon: "warning",
                buttons: {
                cancel: "No",
                Si: true,
                },
            })
            .then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: 'pag=Obras&tipo=d&id='+id,
                        dataType: "json",
                    })
                    .fail(function(data){
                        swal('Error','Error Peticion ajax','error');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case 'Success' :
                                mensaje('okey','Se elimino la obra');

                                Obras.forEach(function(m, k){
                                    if(m.idObra == id){
                                        Obras.splice(k, 1);
                                    }
                                });
                                id = 0;
                                $tr = undefined;
                                DataTable.row($tr).remove().draw();   
                            break;
                            case 'Unknown Obra':
                                swal('No se encontro la obra','');
                            break;
                            default: 
                                mensaje('fail', 'No se pudo eliminar la fuente');
                            break;
                        }
                    });
                }
            });
        });

        async function setObra(obra){
            await showLoading();
            console.log(obra)
            $('#obra').val(obra.nombre);
            $('#proyecto option[value=\''+obra.proyecto+'\']').prop('selected', true);
            $('#expediente').val(obra.expediente);
            $('#anio_expediente option[value=\''+obra.anioExpediente+'\']').prop('selected', true);
            $('#u_ejecutora option[value=\''+obra.idUnidadEjecutora+'\']').prop('selected', true);
            $('#proveedor option[value=\''+obra.idProveedor+'\']').prop('selected', true);
            $('#importe_est').val(obra.importeEstimado).focusout();
            $('#importe_def').val(obra.importeDefinitivo).focusout();
            $('#imputado').val(obra.imputado);
            $('#observaciones').val(obra.observaciones);
            
            saldoAPagar = obra.importeDefinitivo;

            completeTables(obra.registrosCompromiso, obra.ordenesPago, obra.imputaciones);

            if(obra.datosAdicionales.length != 0){
                await setAditionalData(obra.datosAdicionales);
            }

            if(obra.certificados.length != 0){
                await setCeritificateData(obra.certificados[0]);
            }

            await hideLoading();
        }

        function setAditionalData(AD){
            return new Promise(resolve => {
                console.log((AD.nAdjudicacionAnio == null) ? 'SIN_DEFINIR' : AD.nAdjudicacion)
                $('#dAdjudicacion ').val(AD.dAdjudicacion);
                $('#nAdjudicacion_ad').val(AD.nAdjudicacion);
                $('#anioAdjudicacion_ad option[value=\''+AD.nAdjudicacionAnio+'\']').prop('selected', true);

                $('#estado_ad option[value=\''+AD.idEstado+'\']').prop('selected', true);
                $('#tipoobra_ad option[value=\''+AD.idTipoObra+'\']').prop('selected', true);
                $('#tipoplazo_ad option[value=\''+AD.cPlazo+'\']').prop('selected', true);
                $('#plazo_ad').val(AD.nPlazo);

                $('#modalidad_ad option[value=\''+AD.idModalidad+'\']').prop('selected', true);
                $('#modnumero_ad').val(AD.nModalidad);
                $('#anio_modalidad option[value=\''+AD.nModalidadAnio+'\']').prop('selected', true);

                $('#apertura_ad').val(AD.dApertura);
                $('#inicio_ad').val(AD.dCircuito);

                $('#decllamado_ad').val(AD.dLlamado);
                $('#nllamado_ad').val(AD.nLlamado);
                $('#aniollamado_ad option[value=\''+AD.nLlamadoAnio+'\']').prop('selected', true);

                $('#aperturapr_ad').val(AD.dPropuesta);
                $('#folio_ad').val(AD.nPropuestaFolio);
                $('#hora_ad').val(AD.cPropuestaHora);

                $('#cfolio_ad').val(AD.nContrato);
                $('#contrato_ad').val(AD.dContrato);
                
                $('#obinicio_ad').val(AD.dInicioObra);
                $('#fin_ad').val(AD.dFinObra);
                $('#recepcion_ad').val(AD.dRecepcion);
                resolve('resolved');
            });
        }

        function setCeritificateData(c){
            return new Promise(resolve => {
                certificado = c.idCertificado;

                $('#nro_cert option[value=\''+c.cCertificado+'\']').prop('selected', true);

                $('#ampliacion_cert').val(c.cAmpliacion);
                $('#certobra_cert').val(c.nCertificado);
                $('#dcontrato_cert').val(c.dContrato);
                $('#devolucion_cert').val(c.dDevolucion);

                $('#importe2_cert').val(c.nAmpliacion).focusout();
                $('#importe_cert').val(c.nImporte).focusout();
 
                $('#observaciones_cert').val(c.cObs);
                $('#periodo_cert').val(c.cPeriodo);
                
                $('#tipoplazo1_cert option[value=\''+c.cPlazoProrroga1+'\']').prop('selected', true);
                $('#tipoplazo2_cert option[value=\''+c.cPlazoProrroga2+'\']').prop('selected', true);
                $('#tipoplazo3_cert option[value=\''+c.cPlazoGarantia+'\']').prop('selected', true);

                $('#plazo1_cert').val(c.nProrroga);
                $('#plazo2_cert').val(c.nProrroga2);
                $('#plazogarantia_cert').val(c.nGarantia);
                $('#prorroga1_cert').val(c.dProrroga);
                $('#prorroga2_cert').val(c.dProrroga2);

                $('#retencion_cert').val(c.cRetencion);
                $('#imretencion_cert').val(c.nRetencion);
                $('#terminacion1_cert').val(c.dTerminacion);
                $('#terminacion2_cert').val(c.dTerminacion2);
                
                resolve('resolved');
            });
        }

        $('#nro_cert').change(function(e){
            if(actualObra.length == 0){
                return false;
            }

            let found = actualObra.certificados.find(c => c.cCertificado == this.value);
            console.log(found)

            if(found === undefined){
                certificado = 0;
                
                let value = this.value;

                $('#form_certificado')[0].reset();

                $('#nro_cert option[value=\''+value+'\']').prop('selected', true);

                return false;
            }

            setCeritificateData(found);
        });

        $(document).on('submit', '#form_obras', function(e){
            e.preventDefault();
            showLoading('Cargando Obra');

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Obras&tipo='+type+'&id='+id+'&compromisos='+JSON.stringify(compromisos)+'&ordenesPago='+JSON.stringify(ordenesPago)+'&imputaciones='+JSON.stringify(imputaciones),
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'r') ? 'Se registro la obra' : 'Se actualizo la obra');

                        // $("#form_obras")[0].reset();

                        if(type == 'u'){
                            Obras.forEach(function(m, k){
                                if(m.id == data.Response.id){
                                    Obras[k] = data.Response;
                                }
                            });
                            DataTable.row($tr).remove().draw();   
                        }else{
                            Obras.push(data.Response);
                        }

                        id = data.Response.id;
                        actualObra = Obras.find(m => m.id == id);
                        type = 'u';
                        $('.obra-name').text(data.Response.nombre);
                        
                        DataTable.row.add(data.Response).draw();
                    break;
                    case 'Existing Obra Name':
                        swal('Ya existe una obra con el mismo nombre','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Obra':
                        swal('Obra seleccionada invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar la Obra' : 'No se pudo editar la Obra');
                    break;
                }
            }).always(function(){
                hideLoading();
            });
        });

        $(document).on('submit', '#form_adicionales', function(e){
            e.preventDefault();
            console.log($(this).serialize())
            if(id == 0 || id == '' || id == undefined ||id == NaN){
                return swal('No hay alguna obra activa para agregar datos adicionales','','warning');
            }

            showLoading('Cargando Datos Adicionales');

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Obras&tipo=ua&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', 'Se agregaron los datos adicionales');

                        Obras.forEach(function(m, k){
                            if(m.id == id){
                                Obras[k].datosAdicionales = data.Response;
                            }
                        });
                        actualObra = Obras.find(m => m.id == id);
                    break;
                    case 'Unknown Obra':
                        swal('Obra seleccionada invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail','No se pudo agregar los datos adicionales');
                    break;
                }
            }).always(function(){
                hideLoading();
            });
        });

        $(document).on('submit', '#form_certificado', function(e){
            e.preventDefault();
            
            if(id == 0 || id == '' || id == undefined ||id == NaN){
                return swal('No hay alguna obra activa para agregar el certificado','','warning');
            }

            let t = 'uc';

            if(certificado != 0){
                t = 'uca';
            }

            showLoading('Subiendo Certificado');

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Obras&tipo='+t+'&id='+id+'&certificado='+certificado,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (t == 'uc') ? 'Se agrego el certificado' : 'Se actualizo el certificado');
                        
                        Obras.forEach(function(m, k){
                            if(m.id == id){
                                Obras[k].certificados = data.Response;
                            }
                        });
                        actualObra = Obras.find(m => m.id == id);

                        let found = actualObra.certificados.find(c => c.cCertificado == $('#nro_cert').val());
                        certificado = found.idCertificado;
                    break;
                    case 'Unknown Obra':
                        swal('Obra seleccionada invalida', 'No se realizo la accion', 'warning');
                    break;
                    case 'Unknown Certificado':
                        swal('Certificado Invalido', 'El certificado que intenta editar no existe', 'warning');
                    break;
                    case 'Existing Certificado':
                        swal('Certificado Exististente', 'Ya existe el certificado que intenta cargar', 'warning');
                    break;
                    default: 
                        mensaje('fail','No se pudo agregar los datos adicionales');
                    break;
                }
            }).always(function(){
                hideLoading();
            });
        });

        $('.nueva-obra').click(function(e){
            $('#form_obras')[0].reset();
            $('#form_adicionales')[0].reset();
            $('#form_certificado')[0].reset();

            $('#table_imputacion tbody').children().remove();
            $('#table_op tbody').children().remove();
            $('#table_compromiso tbody').children().remove();

            compromisos = [];
            ordenesPago = [];
            imputaciones = [];

            id = 0;
            actualObra = [];
            saldoAPagar = 0;

            $('#nav-newobra input').prop('disabled', false);
            $('#nav-newobra select').prop('disabled', false);
            $('#nav-newobra button').prop('disabled', false);

            $('#nav-newobra input.disabled').prop('disabled', true);

            $('.obra-name').text('NUEVA OBRA');
        });

        $('#nav-conslt-tab').on('shown.bs.tab', function (e) {
            e.target // newly activated tab
            e.relatedTarget // previous active tab
            DataTable.columns.adjust().draw(false);
        });

    </script>
    <script src="js/obra.js"></script>
</body>
</html>
<?php 
        }else{
            //si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
            header("location: ../../index.php");
        }
    }else{
        //si la Session no esta iniciada lo envio devuelta a la pagina principal.
        header("location: ../../index.php");
    }
?>