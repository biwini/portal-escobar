<?php 
    
    require_once('controller/liquidacionController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["OBRAS_PARTICULARES"])){

                $Liquidacion = new liquidacion();
                
?>  

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Crear liquidación</title>
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
                <h1 class="page-title"><span class="icon-file-text"></span> CREAR LIQUIDACIÓN</h1>
            </header>
            <div class="row">
                <div class="col-md-12">
                <?php 

                    // if(count($CarList) == 0){
                    //     echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON VEHÍCULOS</h2>';
                    // }

                    // if(count($ProviderList) == 0){
                    //     echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON PROVEEDORES</h2>';
                    // }
                    // if(count($OrderList) == 0){
                    //     echo '<h2 class=\'text-danger\'>-NO SE ENCONTRARON ORDENES DE COMPRA VALIDAS PARA LA FECHA</h2>';
                    // }
                ?>
                </div>
            </div>
            <section class="page-section">
                <form name="form_liquidacion_normal" id="form_liquidacion_normal" autocomplete="off">
                    <div class="card ">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12 col-sm-12">
                                    <div class="form-group col-md-4">
                                        <label for="tipo_liq">TIPO DE LIQUIDACIÓN: </label>
                                        <select class="form-control" name="tipo_liq" id="tipo_liq">
                                            <option value="NORMAL" selected>NORMAL</option>
                                            <option value="ART13">ART. 13</option>
                                            <option value="ART126">ART. 126</option>
                                            <option value="CARTELES">CARTELES</option>
                                            <option value="DEMOLICION">DEMOLICION</option>
                                            <option value="ELECTROMECANICO">ELECTROMECANICO</option>
                                            <option value="INCENDIO">INCENDIO</option>
                                            <option value="MORATORIA">MORATORIA</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="fecha">FECHA: </label>
                                        <input type="date" class="form-control required " value="<?php echo explode(' ',$Liquidacion->fecha)[0]; ?>" name="fecha" id="fecha" required disabled>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="nombre">NOMBRE Y APELLIDO: </label>
                                        <input type="text" class="form-control required " name="nombre" id="nombre" placeholder="Nombre y apellido..." required>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4">
                                    <div class="form-group">
                                        <label for="sm_municipal">S.M.MUNICIPAL: </label>
                                        <input type="number" class="form-control required " name="sm_municipal" id="sm_municipal" placeholder="S.M. Municipal..." required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h1 class="page-title" style="text-align: center;"> NOMENCLATURA CATASTRAL</h1>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="circ">CIRC:</label>
                                        <input type="text" class="form-control" id="circ" name="circ">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="seccion">SECCIÓN</label>
                                        <input type="text" class="form-control" id="seccion" name="seccion">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="fraccion">FRACCIÓN</label>
                                        <input type="text" class="form-control" id="fraccion" name="fraccion">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="chacra">CHACRA</label>
                                        <input type="text" class="form-control" id="chacra" name="chacra">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="quinta">QUINTA</label>
                                        <input type="text" class="form-control" id="quinta" name="quinta">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="manzana">MANZANA</label>
                                        <input type="text" class="form-control" id="manzana" name="manzana">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="parcela">PARCELA</label>
                                        <input type="text" class="form-control" id="parcela" name="parcela">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label for="uf">UF</label>
                                        <input type="number" class="form-control" id="uf" name="uf">
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group" >
                                        <label for="partida">PARTIDA</label>
                                        <input type="text" class="form-control" id="partida" name="partida">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h1 class="page-title" style="text-align: center;"> ZONIFICACIÓN</h1>
                                <div class="col-md-6 col-sm-6">
                                    <div class="form-group">
                                        <label for="zonificacion">ZONIFICACIÓN:</label>
                                        <select name="zonificacion" id="zonificacion" class="form-control" required>
                                            <option value="" disabled selected>SELECCIONE LA ZONIFICACIÓN</option>
                                            <option value="rural">RURAL/COMPLEM SEMIURB-IND</option>
                                            <option value="urbana">URBANA</option>
                                            <option value="residencial">RESIDENCIAL EXTRAURBANA</option>
                                            <option value="club">CLUB DE CAMPO</option>
                                            <option value="decuatro">DE4</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <style>
                        /* enable absolute positioning */
                        .inner-addon { 
                            position: relative; 
                        }

                        /* style icon */
                        .inner-addon .icon {
                        position: absolute;
                        padding: 10px;
                        pointer-events: none;
                        }

                        /* align icon */
                        .left-addon .icon  { left:  5px;}
                        .right-addon .icon { right: 5px;}

                        /* add padding  */
                        .left-addon input  { padding-left:  30px; }
                        .right-addon input { padding-right: 30px; }

                        .icon-percent:before {
                            content: "\0025";
                        }

                        .tb-text-center > thead > tr > th{
                            text-align: center;
                        }

                        /* .icon-percent { content: "\0025"; } */
                    </style>
                    <div class="card ">
                        <div class="row content-liquidacion liq_normal liq_carteles">
                            <h1 class="page-title liq_normal" style="text-align: center;"> CONTRATO DEL COLEGIO</h1>
                            <div class="col-md-12 col-sm-12">
                                <button type="button" class="btn btn-info liq_normal" id="add_row" value=""><span class="icon-plus"> Añadir fila</span></button>
                                <button type="button" class="btn btn-warning liq_normal" id="remove_row" value=""><span class="icon-minus"> Quitar fila</span></button>
                                <div class="table-responsive">
                                    
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_normal">
                                        <thead>
                                            <th colspan="2">MONTO DE OBRA</th>
                                            <th>COEF%</th>
                                            <th>RECARGO</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><input type="text" class="form-control" name="cg_monto_name[]" placeholder="Tipo monto..." required /></td>
                                                <td class="inner-addon left-addon">
                                                    <i class="icon icon-coin-dollar"></i>
                                                    <input type="number" class="form-control contrato-colegio-normal monto" id="monto_value_1" name="cg_monto_value[]" value="0" />
                                                </td>
                                                <td class="inner-addon right-addon">
                                                    <i class="icon icon-percent"></i>
                                                    <input type="number" class="form-control contrato-colegio-normal coef porcentaje" id="coef_1" name="cg_coef[]" value="0" />
                                                </td>
                                                <td class="inner-addon right-addon">
                                                    <i class="icon icon-percent"></i>
                                                    <input type="number" class="form-control contrato-colegio-normal recargo porcentaje" id="recargo_1" name="cg_recargo[]" value="0" />
                                                </td>
                                                <td class="inner-addon left-addon">
                                                    
                                                    <h3 id="total_colegio_1" class="total">$ 0</h3>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" style="text-align:right;"><h2>SUBTOTAL(A)</h2></th>
                                                <th colspan="2"><h2 id="sub_total_colegio">$ 0</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_art13">
                            <h1 class="page-title" style="text-align: center;"> CONTRATO DEL COLEGIO</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_art13">
                                        <thead>
                                            <th>DESTINO</th>
                                            <th>(M²)</th>
                                            <th>COEF.%</th>
                                            <th>U.REFERENCIAL</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['CUBIERTO','SEMICUBIERTO'];
                                                $rowLowerName = ['cubierto', 'semicubierto'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'arttrc_type_'.$i.'\' name=\'cg_arttrc_destino[]\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <i class=\'icon icon-coin-dollar\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art13 m2\' id=\'arttrc_cubierto_'.$i.'\' name=\'cg_arttrc_m2[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art13 coef porcentaje\' id=\'arttrc_coef_'.$i.'\' name=\'cg_arttrc_coef[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art13 ref porcentaje\' id=\'arttrc_ref_'.$i.'\' name=\'cg_arttrc_ref[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            
                                                            <h3 id=\'total_arttrc_'.$i.'\' class=\'total\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" style="text-align:right;"><h2>MONTO OBRA</h2></th>
                                                <th colspan="2"><h2 id="sub_total_arttrc">$ 0</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_art13">
                            <h1 class="page-title" style="text-align: center;"> CONTRATO DEL COLEGIO</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_art13_2">
                                        <thead>
                                            <th>TIPO</th>
                                            <th>DESTINO</th>
                                            <th>MONTO DE OBRA</th>
                                            <th>CAP IX($/M²)</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['ARTICULO 13'];
                                                $rowLowerName = ['articulo 13'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'arttrc_type\' name=\'cg_arttrc_tipo\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <input type=\'text\' class=\'form-control\' id=\'arttrc_des\' name=\'cg_arttrc_des\' value=\'0\' />
                                                        </td>
                                                        <td style=\'text-align: center; font-weight: bold;\'>
                                                            <label id="label_monto_obra_art13">$ 0</label>
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art13-2 porcentaje\' id=\'cg_arttrc_cap\' name=\'cg_arttrc_cap\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            
                                                            <h3 id=\'total_arttrc2\' class=\'total\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_art126">
                            <h1 class="page-title" style="text-align: center;"> CONTRATO DEL COLEGIO</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_art126">
                                        <thead>
                                            <th>DESTINO</th>
                                            <th>(M²)</th>
                                            <th>COEF.%</th>
                                            <th>U.REFERENCIAL</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['CUBIERTO','SEMICUBIERTO','PILETA'];
                                                $rowLowerName = ['cubierto', 'semicubierto','pileta'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'artvs_type_'.$i.'\' name=\'cg_artvs_destino[]\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <i class=\'icon icon-coin-dollar\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art126 m2\' id=\'artvs_cubierto_'.$i.'\' name=\'cg_artvs_m2[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art126 coef porcentaje\' id=\'artvs_coef_'.$i.'\' name=\'cg_artvs_coef[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art126 ref porcentaje\' id=\'artvs_ref_'.$i.'\' name=\'cg_artvs_ref[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            
                                                            <h3 id=\'total_artvs_'.$i.'\' class=\'total\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" style="text-align:right;"><h2>MONTO OBRA</h2></th>
                                                <th colspan="2"><h2 id="sub_total_artvs">$ 0</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_art126">
                            <h1 class="page-title" style="text-align: center;"> CONTRATO DEL COLEGIO</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_art126_2">
                                        <thead>
                                            <th colspan="2">MONTO DE OBRA</th>
                                            <th>COEF.%</th>
                                            <th>RECARGO</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['A DECLARAR'];
                                                $rowLowerName = ['a declarar'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'artvs_type\' name=\'cg_monto_name[]\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td class="inner-addon left-addon">
                                                            <i class="icon icon-coin-dollar"></i>
                                                            <input type="number" class="form-control contrato-colegio-art126-2 monto disabled" id="artvs_monto" value="0" />
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'artvs_monto_real\' name=\'cg_monto_value[]\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art126-2 coef porcentaje\' id=\'artvs_coef\' name=\'cg_coef[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control contrato-colegio-art126-2 recargo porcentaje\' id=\'cg_artvs_recargo\' name=\'cg_recargo[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <h3 id=\'total_artvs2\' class=\'total\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" style="text-align:right;"><h2>SUBTOTAL(A)</h2></th>
                                                <th colspan="2"><h2 id="sub_total_colegio_art126">$ 0</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_incendio">
                            <h1 class="page-title" style="text-align: center;"> LIQUIDACIÓN</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_contrato_colegio_incendio">
                                        <thead>
                                            <th>TIPO</th>
                                            <th>DESTINO</th>
                                            <th>(M²)</th>
                                            <th>CAP IX ($/M²)</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $rowName = ['SEG. C/ INCENDIO'];
                                                $rowLowerName = ['seg. c/ incendio'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'arttrc_type_'.$i.'\' name=\'inc_destino\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td>DEPOS-LOCAL</td>
                                                        <td>
                                                            <input type=\'number\' class=\'form-control liquidacion-incendio\' id=\'incendio_m2\' name=\'inc_m2\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            
                                                            <input type=\'number\' class=\'form-control liquidacion-incendio\' id=\'inc_cap\' name=\'inc_cap\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <h3 id=\'total_inc\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_electromecanico">
                            <h1 class="page-title" style="text-align: center;"> LIQUIDACIÓN</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_electromecanico">
                                        <thead>
                                            <th>TIPO</th>
                                            <th>DESTINO</th>
                                            <th>(M²)</th>
                                            <th>CAP IX ($/M²)</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['HASTA 50 HP','EXEDENTE','HASTA 25 HP', 'EXEDENTE'];
                                                $rowLowerName = ['hasta 50 hp', 'exedente','hasta 25 hp', 'exedente'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    $type = ($i == 0) ? '<td style=\'text-align: center; font-weight: bold;\' rowspan=\'4\'>ELECTROMECANICO<br>INDUSTRIA</td>' : '';

                                                    echo '<tr>
                                                        '.$type.'
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'elec_destino_'.$i.'\' name=\'elec_destino[]\' value=\''.$rowName[$i].'\' />
                                                        </td>
                                                        <td>
                                                            <input type=\'number\' class=\'form-control tabla-electromecanico m2\' id=\'elec_m2_'.$i.'\' name=\'elec_m2[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control tabla-electromecanico cap porcentaje\' id=\'elec_cap_'.$i.'\' name=\'elec_cap[]\' value=\'0\' />
                                                        </td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            
                                                            <h3 id=\'total_elec'.$i.'\' class=\'total\'>$ 0</h3>
                                                        </td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row content-liquidacion liq_normal liq_art126">
                            <h1 class="page-title" style="text-align: center;"> MULTAS</h1>
                            <div class="col-md-12 col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered tb-text-center" id="table_multas">
                                        <thead>
                                            <th>MULTAS</th>
                                            <th>(M²)</th>
                                            <th>CANT</th>
                                            <th>S.M.MUNICIPAL</th>
                                            <th>PORCENTAJE</th>
                                            <th>TOTAL</th>
                                        </thead>
                                        <tbody>
                                            <?php

                                                $rowName = ['F.O.S', 'F.O.T', 'RETIROS', 'DENSIDAD', 'DTO.1281/14'];
                                                $rowLowerName = ['fos', 'fot', 'retiros', 'densidad', 'dto'];

                                                for ($i = 0; $i < count($rowName); $i++) { 
                                                    echo '<tr>
                                                        <td style=\'text-align: center; font-weight: bold;\'>'.$rowName[$i].'
                                                            <input type=\'text\' class=\'form-control hidden\' style=\'display:none;\' id=\'mt_type'.$i.'\' name=\'mt_type[]\' value=\''.$rowLowerName[$i].'\' />
                                                        </td>
                                                        <td><input type=\'number\' class=\'form-control tb-multas\' id=\'m2_'.$i.'\' name=\'mt_m2[]\' value=\'0\' /></td>
                                                        <td><input type=\'number\' class=\'form-control tb-multas\' id=\'cant_'.$i.'\' name=\'mt_cant[]\' value=\'0\' /></td>
                                                        <td class=\'inner-addon left-addon\'>
                                                            <i class=\'icon icon-coin-dollar\'></i>
                                                            <input type=\'number\' class=\'form-control tb-multas smmunicipal disabled\' id=\'sm_'.$i.'\' name=\'mt_sm[]\' value=\'0\' disabled />
                                                        </td>
                                                        <td class=\'inner-addon right-addon\'>
                                                            <i class=\'icon icon-percent\'></i>
                                                            <input type=\'number\' class=\'form-control tb-multas porcentaje\' id=\'porcentaje_'.$i.'\' name=\'mt_porcentaje[]\' value=\'0\' />
                                                        </td>
                                                        <td><h3 id=\'total_multa_'.$i.'\'>$ 0</h3></td>
                                                    </tr>';
                                                }

                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right;"><h2>SUBTOTAL(B)</h2></th>
                                                <th colspan="2"><h2 id="sub_total_multa">$ 0</h2></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card ">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-4 col-sm-4 content-liquidacion liq_normal liq_art126">
                                    <div class="form-group">
                                        <label for="fecha">SUBTOTAL (A) + (B) </label>
                                        <h3 id="sub_total_ab">$ 0</h3>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 content-liquidacion liq_normal">
                                    <div class="form-group">
                                        <label for="fecha">DESCUENTO </label>
                                        <div class="inner-addon right-addon">
                                            <i class="icon icon-percent"></i>
                                            <input type="number" class="form-control porcentaje" id="descuento" name="descuento" value="0">
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 liq_all">
                                    <div class="form-group">
                                        <label for="fecha">TOTAL A ABONAR</label>
                                        <h3 id="total_liq">$ 0</h3>
                                    </div>
                                </div>
                                <div class="btn-add">
                                   <input type="submit" class="btn btn-primary" value="CARGAR LIQUIDACIÓN">
                                </div>  
                            </div>
                        </div>
                    </div>
                </form>
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
    <script language="javascript" src="js/liquidaciones.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript">
        // $('#loading').modal({backdrop: 'static', keyboard: false});
        // $('#loading').modal('hide');

        $(document).ready(function(){
            showLiq()
        });

        $('#tipo_liq').change(function(){
            showLiq();
        });

        function showLiq(){
            let actualLiq = $('#tipo_liq').val().toLowerCase();

            $('.content-liquidacion').each(function(){
                $(this).children().find('input[type=text]').each(function(){
                    this.value = this.defaultValue;
                });
                // console.log($(this).children().find('button'))
                // console.log($(this).children().find('input[type=text]'))
                $(this).children().find('input[type=number]').val(0);
                $(this).children().find('input').attr('disabled','true');
                $(this).children().find('button').attr('disabled','true');
                $(this).children().find('button').addClass('hide');
                $(this).attr('hidden','true');
            });

            $('.liq_'+actualLiq).each(function(){
                $(this).removeAttr('hidden');
                $(this).children().find('input').removeAttr('disabled');
                $(this).children().find('button.liq_'+actualLiq).removeAttr('disabled');
                $(this).children().find('button.liq_'+actualLiq).removeClass('hide');
                $(this).children().find('input.disabled').attr('disabled','true');
            });
        }

        $('#add_row').click(function(){
            let rowIndex = $('#table_contrato_colegio_normal tbody tr').length + 1;
            let newRow = '<tr>'
                +'<td><input type=\'text\' class=\'form-control\' name=\'cg_monto_name[]\' placeholder="Tipo monto..." required /></td>'
                +'<td class=\'inner-addon left-addon\'>'
                    +'<i class=\'icon icon-coin-dollar\'></i>'
                    +'<input type=\'number\' class=\'form-control contrato-colegio-normal monto\' id=\'monto_value_'+rowIndex+'\' name=\'cg_monto_value[]\' value=\'0\' />'
                +'</td>'
                +'<td class=\'inner-addon right-addon\'>'
                    +'<i class=\'icon icon-percent\'></i>'
                    +'<input type=\'number\' class=\'form-control contrato-colegio-normal coef porcentaje\' id=\'coef_'+rowIndex+'\' name=\'cg_coef[]\' value=\'0\' />'
                +'</td>'
                +'<td class=\'inner-addon right-addon\'>'
                    +'<i class=\'icon icon-percent\'></i>'
                    +'<input type=\'number\' class=\'form-control contrato-colegio-normal recargo porcentaje\' id=\'recargo_'+rowIndex+'\' name=\'cg_recargo[]\' value=\'0\' />'
                +'</td>'
                +'<td class=\'inner-addon left-addon\'>'
                    +'<h3 id=\'total_colegio_'+rowIndex+'\' class=\'total\'>$ 0</h3>'
                +'</td>'
            +'</tr>';

            $('#table_contrato_colegio_normal tbody').append(newRow);

        });

        $('#remove_row').click(function(){
            if($('#table_contrato_colegio_normal tbody tr').length > 1){
                $('#table_contrato_colegio_normal tbody tr:last').remove();

                setTotal();
            }
        });

        // $('#filter_secretary').change(function(e){
        //     let secId = $(this).val();
        //     let secretary = $('#filter_secretary option:selected').text();

        //     $('#oc').find('option:not(:first)').remove();
        //     $('#oc').prop('selectedIndex',0);

        //     $('#vehiculo').find('option:not(:first)').remove();
        //     $('#vehiculo').prop('selectedIndex',0);

        //     let filterOrder = Orders;
        //     let filterCar = Cars;

        //     filterOrder.forEach(function(v){
        //         if(v.Secretary == secretary){
        //             o = new Option(v.Secretary, v.Id);
        //             /// jquerify the DOM object 'o' so we can use the html method
        //             $(o).html(v.Oc+' | '+v.Secretary+' | '+v.Dependence+' | '+v.RemainingFuel);

        //             $("#oc").append(o);
        //         }
        //     });

        //     filterCar.forEach(function(v){
        //         if(v.Secretaria == secretary){
        //             o = new Option(v.Secretaria, v.Id);
        //             /// jquerify the DOM object 'o' so we can use the html method
        //             $(o).html(v.Patente+' | '+v.Model+' | '+v.Secretaria);

        //             $("#vehiculo").append(o);
        //         }
        //     });
        // });

        $(document).on('submit', '#form_liquidacion_normal', function(e){
            e.preventDefault();

            let valid = false;

            $('.required').each(function(){
                if($(this).val() != '' && $(this).val() != undefined){
                    valid = true;
                }
                if($(this).val() > 0){
                    valid = true;
                }
            });

            if(!valid){
                swal('LIQUIDACION INCOMPLETA','','warning');
                return false;
            }

            $('#loading').modal({backdrop: 'static', keyboard: false});

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Liquidacion'+'&tipo=normal',
                dataType: "json",
            })
            .fail(function(data){
                console.log(data)
                $('#loading').modal('hide');
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                console.log(data)
                $('#loading').modal('hide');
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', 'Se registro la liquidacion');

                        $('#form_liquidacion_normal')[0].reset();

                        showLiq();
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