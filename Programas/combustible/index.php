<?php 
    
    require_once('controller/sessionController.php');
    $session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($session->isLogued()){
          //Verifico si tiene permisos para estar en esta pagina.
            if(isset($_SESSION["TICKETS_DE_COMBUSTIBLE"])){

                require_once 'controller/proveedorController.php';
                require_once 'controller/vehiculoController.php';
                require_once 'controller/remitoController.php';
                require_once 'controller/dashboardController.php';

                $Vehiculo = new vehiculo();
                $Proveedor = new proveedor();
                $Remito = new remito();
                $Dashboard = new dashboard();

                $CarList = $Vehiculo->getCars();
                $ProviderList = $Proveedor->getProviders();
                $RemitoList = $Remito->getRemitos();
                $MailList = $Dashboard->getMailSent();

?>

<!DOCTYPE html>
<html lang="es">
<head>
	<title>Dashboard</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="images/favicon-196x196.png">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/libs/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="css/icons.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/app-main.css">
    <link rel="stylesheet" type="text/css" href="css/custom-styles.css">
</head>
<body>
    <?php 
    require('header.php');
    require('menu.php');
    ?>
    <main class="app-main">
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-stats-dots"></span> DASHBOARD</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <select id="type_search" name="type_search" class="form-control">
                                    <option value="PROVEEDOR">PROVEEDOR</option>
                                    <option value="VEHICULO">VEHICULO</option>
                                    <option value="REMITO">Nº VALE</option>
                                    <option value="OC" selected>Nº O/C</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search" id="search" placeholder="Buscar...">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <a href="vehiculos"><div class="col-lg-3">
                            <div class="card bg-info">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="m-r-20 align-self-center"><img src="img/icon-car.png" height="50"></div>
                                        <div class="align-self-center">
                                            <h2 class="text-white m-t-10 m-b-0">Vehiculos </h2>
                                            <h2 class="m-t-0 text-white"><?php echo count($CarList); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>

                        <a href="proveedores"><div class="col-lg-3">
                            <div class="card bg-info">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="m-r-20 align-self-center"><img src="img/user.png" height="50"></div>
                                        <div class="align-self-center">
                                            <h2 class="text-white m-t-10 m-b-0">Proveedores </h2>
                                            <h2 class="m-t-0 text-white"><?php echo count($ProviderList); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>

                        <a href="consultar-vales"><div class="col-lg-3">
                            <div class="card bg-info">
                                <div class="card-body">
                                    <div class="d-flex no-block">
                                        <div class="m-r-20 align-self-center"><img src="img/remito.png" height="50"></div>
                                        <div class="align-self-center">
                                            <h2 class="text-white m-t-10 m-b-0">Vales </h2>
                                            <h2 class="m-t-0 text-white"><?php echo count($RemitoList); ?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div></a>
                    </div>
                </div>
                <header class="page-title-bar">
                    <h1 class="page-title"><span class="icon-mail2"></span> VALES ENVIADOS A PROVEEDORES</h1>
                </header>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" id="tb_mail">
                                    <thead>
                                        <tr>
                                            <th>Nº VALE</th>
                                            <th>PROVEEDOR</th>
                                            <th>USUARIO</th>
                                            <th>FECHA ENVIO</th>
                                            <th>REENVIADO</th>
                                            <th>USUARIO REENVÍO</th>
                                            <th>REENVIAR</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                            foreach ($MailList as $key => $value) {
                                                echo '<tr>
                                                    <td>'.$value['Remito'].'</td>
                                                    <td>'.$value['Provider'].'</td>
                                                    <td>'.$value['User'].'</td>
                                                    <td>'.$value['Date'].'</td>
                                                    <td>'.$value['Reenviado'].'</td>
                                                    <td>'.$value['UsuarioReenvio'].'</td>
                                                    <td><button type=\'button\' class=\'btn btn-primary reenviar\' value=\''.$value['Remito'].'\'>REENVIAR</button></td>
                                                </tr>';
                                            }

                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Nº VALE</th>
                                            <th>PROVEEDOR</th>
                                            <th>USUARIO</th>
                                            <th>FECHA ENVIO</th>
                                            <th>REENVIADO</th>
                                            <th>USUARIO REENVÍO</th>
                                            <th>REENVIAR</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>
    <section>
        <div class="modal" id="modal_oc">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Orden de compra</h3>
                        <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="oc">O/C: </label>
                            <input type="number" class="form-control" name="oc" id="oc" placeholder="Orden de compra..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="secretariaO">Secretaria / Oficina: </label>
                            <input type="text" class="form-control" name="secretariaO" id="secretariaO" placeholder="Secretaria..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="validez">Fecha validez: </label>
                            <input type="date" class="form-control" name="validez" id="validez" disabled>
                        </div>
                        <div class="form-group">
                            <label for="vencimiento">Fecha vencimiento: </label>
                            <input type="date" class="form-control" name="vencimiento" id="vencimiento" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn-primary form-control" data-dismiss="modal"value="Cerrar">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="modal" id="modal_proveedor">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Proveedor</h3>
                        <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="proveedor">Proveedor: </label>
                            <input type="text" class="form-control" name="proveedor" id="proveedor" placeholder="Proveedor..." disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn-primary form-control" data-dismiss="modal"value="Cerrar">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section>
        <div class="modal" id="modal_vehiculo">
            <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Vehiculo</h3>
                        <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="patente">Patente: </label>
                            <input type="text" class="form-control" name="patente" id="patente" placeholder="Patente..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="modelo">Modelo: </label>
                            <input type="text" class="form-control" name="modelo" id="modelo" placeholder="Modelo..." disabled>
                        </div>
                        <div class="form-group">
                            <label for="combustible">Combustible: </label>
                            <select class="form-control" name="combustible" id="combustible" disabled>
                                <option value="" selected disabled>SELEECIONE EL TIPO DE COMBUSTIBLE</option>
                                <option value="NAFTA">NAFTA</option>
                                <option value="NAFTAPP">NAFTA - V POWER / PREMIUM:</option>
                                <option value="GAS">GAS</option>
                                <option value="GASPE">GAS - OIL - V POWER / EURO DIESEL</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="propietario">Perteneciente: </label>
                            <select class="form-control" name="propietario" id="propietario" disabled>
                                <option value="MUNICIPAL">Municipal</option>
                                <option value="PARTICULAR">Particular</option>
                            </select>
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
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn-primary form-control" data-dismiss="modal"value="Cerrar">
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
    <script type="text/javascript">
        // $(window).on('scroll',function(){
        //     if ($(window).scrollTop()) {
        //         $('nav').addClass('affix');
        //     }
        //     else{
        //         $('nav').removeClass('affix'); 
        //     }
        // })
        $('#type_search').change(function(e){
            $('#search').val('');
        });

        $('.reenviar').click(function(e){
            $('#loading').modal({backdrop: 'static', keyboard: false});
            
            let remito = $(this).val()

            $.ajax({
                type: "POST",
                url: url,
                data: "pag="+document.title+"&tipo=rs"+"&remito="+remito,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error peticion ajax','','error');
            })
            .done(function(data){
                $('#loading').modal('hide');
                switch(data.Status){
                    case "Success" :
                        $('#loading').modal('hide');

                        swal('Se reenvio el remito correctamente','','success');
                    break;
                    default: 
                        swal('No se reenvio el remito','error inesperado','error');
                    break;
                }
            });
        });

        function setValues(data){
            let modal = $("#type_search option:selected").val();

            switch(modal){
                case 'PROVEEDOR': 
                    document.getElementById('proveedor').value = data.Proveedor;

                    $('#modal_proveedor').modal('show');
                break;
                case 'VEHICULO': 
                    document.getElementById('patente').value = data.Patente;
                    document.getElementById('modelo').value = data.Model;
                    $('#combustible option[value='+data.FuelType+']').prop("selected",true);
                    $('#propietario option[value='+data.Propietario+']').prop("selected",true);

                    $('#secretaria option').filter(function() {
                      return $(this).text() == data.Secretaria;
                    }).prop('selected', true);

                    console.log(data)

                    $('#modal_vehiculo').modal('show');
                break;
                case 'REMITO': 
                    window.open('http://192.168.122.180/Portal-escobar/Programas/combustible/importToPdf?type=s&remito='+data.Number, '_blank');
                break;
                case 'OC': 
                    document.getElementById('oc').value = data.Oc;
                    document.getElementById('secretariaO').value = data.Secretary;
                    document.getElementById('validez').value = data.ValidityDate;
                    document.getElementById('vencimiento').value = data.ExpirationDate;

                    $('#modal_oc').modal('show');
                break;
            }
        }

        $(document).ready(function(){
            $('#tb_mail').DataTable();
            //INICIALIZO LAS SUGESTIONES DEL BUSCADOR.
            autocompleteFields(document.getElementById('search'));
        });

        function getSuggestions(search){
            return new Promise(resolve => { 
                let type =  $("#type_search option:selected").val();
                let suggestions = new Array();

                resolve(
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: "pag="+document.title+"&tipo=s"+"&search="+search+"&type="+type,
                        dataType: "json",
                    })
                    .fail(function(data){
                        mensaje('fail','No se pueden obtener sugerencias');
                    })
                    .done(function(data){
                        suggestions = data;
                    })
                )

                //resolve(suggestions);
            });
        }

        function autocompleteFields(inp) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                Build(inp,this,this.value);
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");

                if (e.keyCode == 40) {
                    /*If the arrow DOWN key is pressed,
                    increase the currentFocus variable:*/
                    currentFocus++;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 38) { //up
                    /*If the arrow UP key is pressed,
                    decrease the currentFocus variable:*/
                    currentFocus--;
                    /*and and make the current item more visible:*/
                    addActive(x);
                } else if (e.keyCode == 13) {
                    /*If the ENTER key is pressed, prevent the form from being submitted,*/
                    e.preventDefault();
                    if (currentFocus > -1) {
                        /*and simulate a click on the "active" item:*/
                        if (x) x[currentFocus].click();
                    }
                }
            });
            inp.addEventListener('keypress', function(e){
                selectedItem = new Array();
            });
            async function Build(element,event,search) {
                var a, b, i, val = element.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false;}

                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", event.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                a.setAttribute("style", "display:block;position:inherit;");
                /*append the DIV element as a child of the autocomplete container:*/
                event.parentNode.appendChild(a);
                /*for each item in the array...*/
                b = document.createElement("DIV");
                b.setAttribute("id", 'buscando');
                b.setAttribute("class", "buscando");
                /*make the matching letters bold:*/
                b.innerHTML = "<strong>Buscando...</strong>";
                a.appendChild(b);

                let arr = await getSuggestions(search);
                // expected output: 'resolved'
                cont = 0;

                if(arr.length == 0){
                    b.innerHTML = '<strong>Sin resultados...</strong>';
                }else{
                    a.removeChild(b);
                }

                for (i = 0; i < arr.length; i++) {
                /*check if the item starts with the same letters as the text field value:*/
                // if (arr[i].Suggestion.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    if(cont < 5){
                        if (arr[i].Suggestion.toUpperCase().indexOf(search.toUpperCase()) > -1) {
                            cont++;
                            /*create a DIV element for each matching element:*/
                            b = document.createElement("DIV");
                            b.setAttribute("id", arr[i].Id);
                            b.setAttribute("class", "suggestion");
                            /*make the matching letters bold:*/
                            b.innerHTML = "<strong>" + arr[i].Suggestion.substr(0, search.length) + "</strong>";
                            b.innerHTML += arr[i].Suggestion.substr(search.length);
                            /*insert a input field that will hold the current array item's value:*/
                            b.innerHTML += "<input type='hidden' value='" + arr[i].Suggestion + "'>";
                            /*execute a function when someone clicks on the item value (DIV element):*/
                            b.addEventListener("click", function(e) {
                                /*insert the value for the autocomplete text field:*/

                                element.value = this.getElementsByTagName("input")[0].value.split(' | ')[0];
                                // selectedItem = this.getElementsByTagName("input")[0].value.split(' | ')[0];      

                                selectedItem = arr.find(h => h.Suggestion == this.getElementsByTagName("input")[0].value);

                                setValues(selectedItem);
                                
                                /*close the list of autocompleted values,
                                (or any other open lists of autocompleted values:*/
                                closeAllLists();
                            });
                            a.appendChild(b);
                        }
                    }
                }
            }
            function addActive(x) {
                /*a function to classify an item as "active":*/
                if (!x) return false;
                /*start by removing the "active" class on all items:*/
                removeActive(x);
                if (currentFocus >= x.length) currentFocus = 0;
                if (currentFocus < 0) currentFocus = (x.length - 1);
                /*add class "autocomplete-active":*/
                x[currentFocus].classList.add("autocomplete-active");
            }
            function removeActive(x) {
                /*a function to remove the "active" class from all autocomplete items:*/
                for (var i = 0; i < x.length; i++) {
                    x[i].classList.remove("autocomplete-active");
                }
            }
            function closeAllLists(elmnt) {
                /*close all autocomplete lists in the document,
                except the one passed as an argument:*/
                var x = document.getElementsByClassName("autocomplete-items");
                for (var i = 0; i < x.length; i++) {
                    if (elmnt != x[i] && elmnt != inp) {
                        x[i].parentNode.removeChild(x[i]);
                    }
                }
            }
            /*execute a function when someone clicks in the document:*/
            document.addEventListener("click", function (e) {
                closeAllLists(e.target);
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