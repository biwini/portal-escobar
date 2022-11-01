<?php
    require_once('controller/proveedorController.php');
    
    if($session->isLogued()){
        //Verifico si tiene permisos para estar en esta pagina.
        if(isset($_SESSION["OBRAS_PUBLICAS"])){
            $Proveedor = new proveedor();

            $listProveedores = $Proveedor->getProveedores();

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Obras Publicas - Proveedores</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<link rel="icon" type="image/png" href="img/favicon-196x196.png">
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
    <div class="message">
        <span></span>
    </div>
    <main class="app-main">
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-office"></span> PROVEEDORES</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_proveedor, 'Proveedores.xls', 'proveedores');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_tipo" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo proveedor </span>
                                </a>
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_proveedor" class="table table-striped table-hover" name="tb_proveedor" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>CÓDIGO</th>
                                            <th>NOMBRE</th>
                                            <th>C.U.I.T</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
        <form name="form_proveedor" id="form_proveedor" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title" id="title">AGREGAR PROVEEDOR</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="codigo">CODIGO*: </label>
                                <input type="number" class="form-control required" name="codigo" id="codigo" placeholder="Codigo..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="nombre">NOMBRE*: </label>
                                <input type="text" class="form-control required" name="nombre" id="nombre" placeholder="Nombre..." required="true">
                            </div>
                            <div class="form-group">
                                <label for="cuit">C.U.I.T*: </label>
                                <input type="number" class="form-control required" name="cuit" id="cuit" placeholder="Cuit..." required="true">
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
    <script src="js/bootstrap.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script type="text/javascript">
        
        const Proveedores = <?php echo json_encode($listProveedores);?>;

        let type = 'r';
        let id = 0;
        let DataTable;
        let $tr;

        let FirstTwoDigit = '';
        let dniInside = '';
        let LastOneDigit = '';

        $(document).ready(function(){
            DataTable = $('#tb_proveedor').DataTable({
                "data": Proveedores,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "cCodigo"},
                    { "data": "cNombre"},
                    { "data": "nCuit"},
                    { "data": "acciones",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.idProveedor+'\'></button>'+
                            '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.idProveedor+'\'></button> ';
                        }
                    },
                ],
            });
        });

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('AGREGAR PROVEEDOR');
            $('#enviar').val('Agregar');

            $("#form_proveedor")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            let Found = Proveedores.find(m => m.idProveedor == id);

            document.getElementById('codigo').value = Found.cCodigo;
            document.getElementById('nombre').value = Found.cNombre;
            document.getElementById('cuit').value = Found.nCuit;

            $('#title').text('EDITAR PROVEEDOR');
            $('#enviar').val('Guardar Cambios');
            $("#new_tipo").click();
        });

        $(document).on('click','.delete', function(e){
            id = $(this).val();
            $tr = $(this).parents('tr');
            swal({
                title: "¡Eliminando proveedor!",
                text: "¿Seguro que desea Eliminar este proveedor?",
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
                        data: 'pag=Proveedores&tipo=d&id='+id,
                        dataType: "json",
                    })
                    .fail(function(data){
                        swal('Error','Error Peticion ajax','error');
                    })
                    .done(function(data){
                        switch(data.Status){
                            case 'Success' :
                                mensaje('okey','Se elimino el proveedor');

                                Proveedores.forEach(function(m, k){
                                    if(m.idProveedor == id){
                                        Proveedores.splice(k, 1);
                                    }
                                });
                                DataTable.row($tr).remove().draw();
                                id = 0;
                                $tr = undefined;                                   
                            break;
                            case 'Unknown Proveedor':
                                swal('No se encontro el proveedor','');
                            break;
                            default: 
                                mensaje('fail', 'No se pudo eliminar el proveedor');
                            break;
                        }
                    });
                }
            });
        });

        $(document).on('submit', '#form_proveedor', function(e){
            e.preventDefault();

            if(!isCuitValid(FirstTwoDigit+dniInside+LastOneDigit)){
                swal('Cuit invalido','El cuit ingresado es invalido','warning');
                return false;
            }

            if(obtenerVerificador(parseInt(FirstTwoDigit),parseInt(dniInside)) != LastOneDigit){
                swal('Cuit invalido','El cuit ingresado es invalido revise el Numero verificador','warning');
                return false;
            }

            $.ajax({
                type: "POST",
                url: url,
                data: $(this).serialize()+'&pag=Proveedores&tipo='+type+'&id='+id,
                dataType: "json",
            })
            .fail(function(data){
                swal('Error','Error Peticion ajax','error');
            })
            .done(function(data){
                switch(data.Status){
                    case 'Success' :
                        mensaje('okey', (type == 'r') ? 'Se registro el proveedor' : 'Se actualizo el proveedor');

                        $("#form_proveedor")[0].reset();

                        if(type == 'u'){
                            Proveedores.forEach(function(m, k){
                                if(m.idProveedor == data.Response.idProveedor){
                                    Proveedores[k] = data.Response;
                                }
                            });
                            DataTable.row($tr).remove().draw();   
                        }else{
                            Proveedores.push(data.Response);
                        }
                        
                        DataTable.row.add(data.Response).draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing Proveedor Code':
                        swal('El codigo del proveedor ya existe','');
                    break;
                    case 'Existing Proveedor Cuit Or Code':
                        swal('El cuit del proveedor ya existe','');
                    break;
                    case 'Existing Proveedor Code Or Cuit':
                        swal('El cuit o codigo del proveedor ya existe','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Proveedor':
                        swal('proveedor seleccionado invalido', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el proveedor' : 'No se pudo editar el proveedor');
                    break;
                }
            });
        });

        $("#cuit").keyup(function(){
            cuil = $(this).val();

            if(cuil == ''){
                FirstTwoDigit = 0;
                dniInside = 0;
                LastOneDigit = 0;

                return false;
            }

            let t = cuil.substr(2);
            if(cuil.length == 2){
                FirstTwoDigit = cuil;
            }else if (cuil.length > 2) {
                if(FirstTwoDigit != cuil.substring(2,0)){
                    FirstTwoDigit = cuil.substring(2,0);
                }

                dniInside = t.substr(0,t.length-1);
                LastOneDigit = cuil.charAt(cuil.length-1);
            }
        });

        function obtenerVerificador(tipo, numero){
            console.log(tipo, numero)
            numero = tipo * 100000000 + numero;

            var semillas = new Array(2, 3, 4, 5, 6, 7, 2, 3, 4, 5);
            var suma = 0;
        
            for(i = 0; i <= 9; ++i){
                pila = Redondear(numero, i);
                mascara = Redondear(numero, i + 1) * 10;

                suma += (pila - mascara) * semillas[i];
            }

            temporal = suma % 11;   
            resultado = 11-temporal;
        
            if(resultado == 11){
                resultado = 0;
            }
            if(resultado == 10){
                resultado = 9;
            }

            return resultado;
        }

        function Redondear(n, completar){
            divisor = Math.pow(10, completar);
            return Math.ceil((n + 0.01) / divisor) - 1;
        }

        function isCuitValid(cuit){
            const regexCuit = /^(20|23|24|27|30|33|34)([0-9]{9}|-[0-9]{8}-[0-9]{1})$/g;
            return regexCuit.test(cuit);
        }

    </script>
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