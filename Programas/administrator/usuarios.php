<?php
	require_once('controller/adminController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){

				$User = new admin();
                
				$UserList = $User->getUsers();

                $Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
                $page = 'usuarios';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Usuarios</title>
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
    <div class="message">
        <span></span>
    </div>
    <main class="app-main">
        <div class="page">
            <header class="page-title-bar">
                <h1 class="page-title"><span class="icon-user"></span> USUARIOS</h1>
            </header>
            <section class="page-section">
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="page-title">FILTRAR POR:</h4>
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
                            <!-- <div class="col-md-4">
                                <label for="filter_value_other">OTROS:</label>
                                <div class="input-group">
                                    <div class="input-group-addon select-input">
                                        <select class="form-control" id="filter_type_other">
                                            <option value="NOMBRE" selected>NOMBRE</option>
                                            <option value="LEGAJO">LEGAJO</option>
                                            <option value="MAIL">MAIL</option>
                                        </select>
                                    </div>
                                    <input class="form-control" type="text" id="filter_value_other">
                                </div>
                            </div> -->
                            <!-- <div class="col-md-3">
                                <label for="filter_state">ESTADO:</label>
                                <select class="form-control" id="filter_state">
                                    <option value="" selected>TODAS LOS ESTADOS</option>
                                    <option value="1">HABILITADO</option>
                                    <option value="0">DESHABILITADO</option>
                                </select>
                            </div> -->
                            <!-- <div class="col-md-6" id="filter_date_custom" style="display: none;">
                                <div class="col-md-6">
                                    <label>DESDE:</label>
                                    <input type="date" id="filter_date_since" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label>HASTA:</label>
                                    <input type="date" id="filter_date_until" class="form-control">
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_users, 'Usuarios.xls', 'usuarios');return false;">Exportar a Excel</a>
                                <a href="#formulario" id="new_user" class="btn btn-primary" data-toggle="modal">
                                    <span class="icon-plus"> Nuevo usuario </span>
                                </a>
                                
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_users" class="table table-striped" name="tb_users" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>NOMBRE</th>
                                            <th>APELLIDO</th>
                                            <th>LEGAJO</th>
                                            <th>SEXO</th>
                                            <th>TELEFONO</th>
											<th>EMAIL</th>
                                            <!-- <th>SECRETARIA</th> -->
                                            <th>DEPENDENCIA</th>
                                            <th>ACCIONES</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 

                                        // foreach ($UserList as $key => $value) {
										// 	// $state = ($value['State'] == 1) ? 'HABILITADO' : 'DESHABILITADO';
											
										// 	echo '<tr>
										// 		<td>'.$value['Name'].'</td>
										// 		<td>'.$value['Surname'].'</td>
										// 		<td>'.$value['Legajo'].'</td>
                                        //         <td>'.$value['Gender'].'</td>
                                        //         <td>'.$value['Cellphone'].'</td>
                                        //         <td>'.$value['Email'].'</td>
                                        //         <td>'.$value['Secretaria'].'</td>
                                        //         <td>'.$value['Dependencia'].'</td>
										// 		<td><button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''.$value['Id'].'\'></button></td>
										// 	</tr>';
                                        // }

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
        <form name="form_user" id="form_user" autocomplete="off">
            <div class="modal" id="formulario">
                <div class="modal-dialog modal-md modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title">AGREGAR USUARIO</h3>
                            <button class="close pull-right" data-dismiss="modal" id="closemodal" aria-hidden="true">&times;</button>    
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="search_user">BUSCAR USUARIO: </label>
                                <input type="text" class="form-control" id="search_user" placeholder="Nº Legajo">
                                <input type="button" class="btn btn-primary pull-right" id="buscar_usuario" value="Buscar">
                            </div>
                            <div class="form-group">
                                <label for="ad">EN ACTIVE DIRECTORY: </label>
                                <select class="form-control" id="ad" name="ad">
                                    <option value="1">EXISTE</option>
                                    <option value="0" selected>NO EXISTE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="nombre">NOMBRE*: </label>
                                <input type="text" class="form-control required" name="nombre" id="nombre" placeholder="Nombre" required="true">
                            </div>
                            <div class="form-group">
                                <label for="apellido">APELLIDO*: </label>
                                <input type="text" class="form-control required" name="apellido" id="apellido" placeholder="Apellido" required="true">
                            </div>
                            <!-- <div class="form-group">
                                <label for="inpDni">DNI: </label>
                                <input type="number" class="form-control required" name="inpDni" id="inpDni" placeholder="DNI" required="true">
                            </div> -->
                            <div class="form-group">
                                <label for="sexo">SEXO: </label>
                                <select class="form-control" id="sexo" name="sexo">
                                    <option value="M">MASCULINO</option>
                                    <option value="F">FEMENINO</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="legajo">Nº LEGAJO/DNI*: </label>
                                <input type="text" class="form-control required" name="legajo" id="legajo" placeholder="Nº Legajo" required="true">
                            </div>
                            <div class="form-group">
                                <label for="telefono">TELEFONO*: </label>
                                <input type="number" class="form-control only-number" name="telefono" id="telefono" placeholder="Telefono" required="true">
                            </div>
                            <div class="form-group">
                                <label for="email">E-MAIL*: </label>
                                <input type="email" class="form-control required" name="email" id="email" placeholder="E-mail" required="true">
                            </div>
                            <div class="form-group">
                                <label for="secretaria">SECRETARIA*: </label>
                                <select class="form-control" id="secretaria" name="secretaria" required>
                                    <option value="" disabled selected>SELECCIONE LA SECRETARIA</option>
                                    <?php 
                                        foreach ($SecretaryList as $key => $value) {
                                            echo '<option value=\''.$value['Id'].'\'>'.$value['Secretary'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dependencia">DEPENDENCIA*: </label>
                                <select class="form-control" id="dependencia" name="dependencia" required>
                                    <option value="" disabled selected>SELECCIONE LA DEPENDENCIA</option>
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
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script type="text/javascript">
        
        const Usuarios = <?php echo json_encode($UserList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable;
        let user;
        let filterTypeUser, filterValUser;

        $(document).ready(function(){
            $('#tb_users thead th').each( function () {
                var title = $(this).text();
                if(title != 'ACCIONES'){
                    $(this).html(title+ '<div style="width:100%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
                }
                
            } );

            DataTable = $('#tb_users').DataTable({
                "data": Usuarios,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "bSort" : false,
                "columns":[
                    { "data": "Name"},
                    { "data": "Surname"},
                    { "data": "Legajo"},
                    { "data": "Gender"},
                    { "data": "Cellphone"},
                    { "data": "Email"},
                    { "data": "Dependencia"},
                    { "data": "Editar",
                        "render":function(data, type, full, meta){
                            return '<button title=\'Editar\' type=\'button\' class=\'icon-pencil btn btn-md btn-warning edit\' value=\''+full.Id+'\'></button>';
                        }
                    },
                ],
                initComplete: function () {
                    // Apply the search
                    this.api().columns().every( function () {
                        var that = this;
                        $( 'input', this.header() ).on( 'keyup change clear', function () {
                            if ( that.search() !== this.value ) {
                                that.search(this.value).draw();
                            }
                        });
                    });
                }
            });

            // autocompleteFields(document.getElementById('search_user'), 'Active Directory');
        });

        $('#filter_value_other').keyup(function(){
            filterValUser = $(this).val().toUpperCase();
            filterTypeUser = $('#filter_type_other option:selected').val();

            if(filterValUser == ''){
                filterValUser = undefined;
            }

            displayTable();
        });

        async function displayTable(){
            await showmodal(); 
            filterTable = Usuarios;

            if(filterSecretary !== undefined){
                filterTable = filterTable.filter(u => u.Secretaria == filterSecretary);
            }
            if(filterDependence !== undefined){
                filterTable = filterTable.filter(u => u.Dependencia == filterDependence);
            }

            // if(filterValUser !== undefined){ 
            //     switch (filterTypeUser) {
            //         case 'NOMBRE':
            //             filterTable = filterTable.filter(p => (p.FullName.includes(filterValUser)));
            //         break;
            //         case 'LEGAJO':
            //             filterTable = filterTable.filter(p => (p.Legajo.includes(filterValUser)));
            //         break;
            //         case 'MAIL':
            //             filterTable = filterTable.filter(p => (p.Email.includes(filterValUser)));
            //         break;
            //     }
            // }

            DataTable.rows().remove().draw();
            DataTable.rows.add(filterTable);
            DataTable.columns.adjust().draw();
            // filterTable.forEach(function(u){  
            //     DataTable.row.add(u).draw(false);
            // });
            $('#loading').modal('hide');
        }

        $('#formulario').on('hidden.bs.modal', function (e) {
            type = 'r';
            id = 0;
            $tr = undefined;
            $('#title').text('Agregar Usuario');
            $('#enviar').val('Agregar');

            $('#secretaria').prop('selectedIndex',0);
            $('#dependencia').find('option:not(:first)').remove();
            $('#dependencia').prop('selectedIndex',0);

            $("#form_user")[0].reset();
        });

        $(document).on('click','.edit', function(e){
            type = 'u';
            id = $(this).val();
            $tr = $(this).parents('tr');

            // let arr = $(this).parents('tr').children('td');
            let Found = Usuarios.find(p => p.Id == id);

            document.getElementById('nombre').value = Found.Name;
            document.getElementById('apellido').value = Found.Surname;
            document.getElementById('legajo').value = Found.Legajo;
            document.getElementById('telefono').value = Found.Cellphone;
            document.getElementById('email').value = Found.Email;

            $('#sexo option').filter(function() {
                return $(this).val() == Found.Gender;
            }).prop('selected', true);

            $('#secretaria option').filter(function() {
                return $(this).text() == Found.Secretaria;
            }).prop('selected', true);

            $('#ad option').filter(function() {
                return $(this).val() == Found.Ad;
            }).prop('selected', true);

            setDependences($('#secretaria option:selected').val());

            $('#dependencia option').filter(function() {
                return $(this).text() == Found.Dependencia;
            }).prop('selected', true);

            $('#title').text('Editar Usuario');
            $('#enviar').val('Guardar Cambios');
            $("#new_user").click();
        });

        $('#buscar_usuario').click(function(){
            let search = $('#search_user').val();

            $.ajax({
                type: "POST",
                url: "controller/",
                data: $(this).serialize()+"&search="+search+"&pag=ActiveDirectory"+"&tipo=s",
                dataType: "html",
            })
            .fail(function(data){
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                d = JSON.parse(data);
                switch(d.Status){
                    case 'Success':
                        console.log(data)
                        $('#nombre').val(d.Name);
                        $('#apellido').val(d.Surname);
            
                        $('#legajo').val(d.Legajo)
                        mensaje('okey','Usuario encontrado');
                    break;
                    case 'Invalid call':
                        mensaje('fail','Por favor complete todos los datos solicitados');
                    break;
                    case 'Unknown User':
                        mensaje('fail','Numero de legajo invalido');
                    break;
                    case 'Error':
                        mensaje('fail','Ocurrio un error durante la busqueda');
                    break;
                }
            });
        });

        $(document).on('submit', '#form_user', function(e){
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
                        mensaje('okey', (type == 'i') ? 'Se registro el usuario' : 'Se actualizo el usuario');

                        $("#form_user")[0].reset();

                        mensaje('okey', 'Se guardaron los cambios');

                        DataTable.row($tr).remove().draw();

                        u = data.User;
                        
                        DataTable.row.add(u).draw();
                        
                        $("#closemodal").click();
                    break;
                    case 'Existing User':
                        swal('El usuario ya existe','');
                    break;
                    case 'Existing User Legajo':
                        swal('El usuario ingresado ya existe (legajo)','');
                    break;
                    case 'Incomplete Fields':
                        swal('Formulario incompleto','');
                    break;
                    case 'Unknown Dependence':
                        swal('Dependencia invalida', 'No se realizo la accion', 'warning');
                    break;
                    default: 
                        mensaje('fail', (type == 'r') ? 'No se pudo registrar el usuario' : 'No se pudo editar el usuario');
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
				//si el Usuario no tiene Acceso lo envio devuelta a la pagina principal.
				header("location: ../../index.php");
			}
		}
		else{
			//si la Session no esta iniciada lo envio devuelta a la pagina principal.
			header("location: ../../index.php");
		}
	}
?>