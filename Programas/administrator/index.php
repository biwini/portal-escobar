<?php
	require_once('controller/ldapController.php');
	$session = new session();
    if(isset($_SESSION['LOGUEADO'])){
        if($_SESSION['LOGUEADO']){
			//Verifico si tiene permisos para estar en esta pagina.
			if(isset($_SESSION["ADMINISTRATOR"])){

				$Ldap = new ldap();
                
				$LdapUserList = $Ldap->getUsers('User');
				$LdapGroupList = $Ldap->getUsers('Group');

				$Admin = ($_SESSION['ADMINISTRATOR'] == 1 && $_SESSION['DEPENDENCIA'] == 2) ? true : false;
				$page = 'active directory';
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<title>Active Directory</title>
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
                <h1 class="page-title"><span class="icon-home2"></span> ACTIVE DIRECTORY</h1>
                <h4>las fechas pueden no ser precisas</h4>
            </header>
            <section class="page-section">
				<div class="card">
                    <div class="row">
                        <div class="col-md-12">
							<h1 class="page-title" style="float: left;"><span class=""></span> GRUPOS AD</h1>
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_groups_ad, 'GruposAD.xls', 'gruposad');return false;">Exportar a Excel</a>                 
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_groups_ad" class="table table-striped" name="tb_groups_ad" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
											<!-- 'cn','description', 'distinguishedname', 'whencreated', 'whenchanged','iscriticalsystemobject' -->
                                            <th>CN</th>                                            
                                            <th>DESCRIPCION</th>
                                            <!-- <th>NOMBRE DEL GRUPO</th>                                             -->
											<th>FECHA DE CREACION</th>
											<th>ULTIMA MODIFICACION</th>
											<th>CRITICAL GROUP</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>        
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-md-12">
							<h1 class="page-title" style="float: left;"><span class=""></span> USUARIOS AD</h1>
                            <div class="btn-add">
                                <a href="#" class="btn btn-success" onclick="Exporter.export(tb_users_ad, 'UsuariosAD.xls', 'usuariosad');return false;">Exportar a Excel</a>                 
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="tb_users_ad" class="table table-striped" name="tb_users_ad" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
											<!-- 'cn','distinguishedname','displayname','name','lastlogon','samaccountname' -->
                                            <th>CN</th>                                            
                                            <th>NOMBRE A MOSTRAR</th>
                                            <th>NOMBRE DE USUARIO</th>                                            
											<th>NOMBRE DE LA CUENTA</th>
											<th>ULTIMA CONEXION</th>
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
    <script src="js/jquery-3.2.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script language="javascript" src="js/libs/datatables/jquery.dataTables.min.js"></script>
    <script language="javascript" src="js/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script language="javascript" src="js/libs/sweetalert/sweetalert.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/export.js"></script>
    <script type="text/javascript">
        
		const LdapUsers = <?php echo json_encode($LdapUserList);?>;
		const LdapGroups = <?php echo json_encode($LdapGroupList);?>;

        let type = 'r';
        let id = 0;
        let $tr;
        let DataTable, DataTableGroup;
        let user;

        $(document).ready(function(){
			$('#tb_users_ad thead th').each( function () {
                var title = $(this).text();
                if(title != 'ACCIONES'){
                    $(this).html(title+ '<div style="width:75%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
                }
                
			});

			$('#tb_groups_ad thead th').each( function () {
                var title = $(this).text();
                if(title != 'ACCIONES'){
                    $(this).html(title+ '<div style="width:75%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
                }
                
			});
			
            DataTable = $('#tb_users_ad').DataTable({
                "data": LdapUsers,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "Cn"},
                    // { "data": "DistName"},
                    { "data": "DisplayName"},
                    { "data": "Name"},
                    { "data": "Accountname"},
                    { "data": "LastLogon"},
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
			
			DataTableGroup = $('#tb_groups_ad').DataTable({
                "data": LdapGroups,
                "deferRender":true,
                "scrollX":true,
                "scrollCollapse":true,
                "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
                "iDisplayLength":10,
                "columns":[
                    { "data": "Cn"},
                    { "data": "Description",
                        "render":function(data, type, full, meta){
                            return '<p style=\'width:400px; white-space: break-spaces;\'>'+full.Description+'</p>';
                        }
                    },
                    // { "data": "DistName"},
                    { "data": "CreationDate"},
					{ "data": "ChanginDate"},
					{ "data": "Critical"},
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

            if(filterValUser !== undefined){ 
                switch (filterTypeUser) {
                    case 'NOMBRE':
                        filterTable = filterTable.filter(p => (p.FullName.includes(filterValUser)));
                    break;
                    case 'LEGAJO':
                        filterTable = filterTable.filter(p => (p.Legajo.includes(filterValUser)));
                    break;
                    case 'MAIL':
                        filterTable = filterTable.filter(p => (p.Email.includes(filterValUser)));
                    break;
                }
            }

            DataTable.rows().remove().draw();

            filterTable.forEach(function(u){  
                DataTable.row.add(u).draw(false);
            });
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
                return $(this).text() == Found.Gender;
            }).prop('selected', true);

            $('#secretaria option').filter(function() {
                return $(this).text() == Found.Secretaria;
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