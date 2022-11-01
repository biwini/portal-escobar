var ListUser = new Array();
var UserDataTable;
$('#open_modal_user').click(function(){
	$('#modal_charge_user').modal('toggle');
});
$('#modal_charge_user').on('hidden.bs.modal', function (e) {
	$("#form_user")[0].reset();
    emptyDependence();
});
$('#modal_change_usuario').on('hidden.bs.modal', function (e) {
	$("#form_change_user")[0].reset();
    emptyDependence();
});
$(document).on('submit', '#form_user', function(e){
	e.preventDefault();

	if(validate($(this).find('.required'))){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=User"+"&tipo=i",
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
					mensaje('okey','Se creo el usuario');
					$("#form_user")[0].reset();
					getUser();
					$('#modal_charge_user').modal('hide');
				break;
				case 'Error':
					mensaje('fail','No se pudo crear el usuario');
				break;
				case 'Existing User Legajo':
					mensaje('fail','El usuario ingresado ya existe');
				break;
				// case 'Existing User Dni':
				// 	mensaje('fail','El usuario ingresado ya existe');
				// break;
			}
		});
	}
});
var mon = 0;
$('.monotributo').change(function() {
	if(this.checked) {
		mon = 1;
		$('#inpLegajo').removeAttr('required');
		$('#modLegajo').removeAttr('required');
	}else{
		mon = 0;
		$('#inpLegajo').attr('required','true');
		$('#modLegajo').attr('required','true');
	}
});
// $(document).on('click', '.change-state-user', function(e){
// 	var Id = $.trim($(this)[0].id);

// 	if(Id > 0){
// 		accion = $(this).val();
// 		$.ajax({
// 			type: "POST",
// 			url: "controller/",
// 			data: "id="+Id+"&pag=User"+"&tipo=d"+"&action="+accion,
// 			dataType: "html",
// 		})
// 		.fail(function(data){
// 			console.log(data);
// 			alert('Error Peticion ajax');
// 		})
// 		.done(function(data){
// 			response = JSON.parse(data);
// 			switch(response.Status){
// 				case 'Success':
// 					// ProgramDataTable.destroy();
// 					getArea();
// 				break;
// 				case 'Error':
// 					alert('No se pudo deshabilitar el programa');
// 				break;
// 				case 'Invalid call':
// 					alert('Datos invalidos');
// 				break;
// 			}
// 		});
// 	}
// });
var idUser = 0;
$(document).on('click', '.change-name-user', function(e){
	idUser = $(this)[0].id;
	$.each(ListUser, function(i,u){
		if(idUser == u.Id){
			emptyDependence();
			setDependences(u.IdSecretaria);
			$('#modNombre').val(u.Name);
			$('#modApellido').val(u.Username);
			//$('#modDni').val(u.Dni);
			$('#modSexo').val(u.Gender);
			$('#modLegajo').val(u.Legajo);
			if(u.Monotributo == 1){
				$('#modMonotributo').prop('checked', true);
			}
			$('#modTelefono').val(u.Cellphone);
			$('#modEmail').val(u.Email);
			$("#modSecretaria option[value='"+u.IdSecretaria+"']").prop("selected",true);
			$("#modDependencia option[value='"+u.IdDependencia+"']").prop("selected",true);
		}
	});
	// IdArea = $(this)[0].id;
	// oldArea = $(this).parents('tr').children('td')[0].innerHTML
	// oldUbication = $(this).parents('tr').children('td')[1].innerHTML;
	$('#modal_change_usuario').modal('show');
});
$(document).on('submit', '#form_change_user', function(e){
	e.preventDefault();
	if(idUser > 0){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=User"+"&tipo=u"+'&id='+idUser,
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
					idUser = 0;
					mensaje('okey','Se modifico el usuario');
					$("#form_change_user")[0].reset();
					getUser();
				break;
				// case 'Existing User Dni':
				// 	mensaje('fail','Ya existe un usuario con el mismo DNI');
				// break;
				case 'Existing User Legajo':
					mensaje('fail','Ya existe un usuario con el mismo Legajo');
				break;
				case 'Error':
					mensaje('fail','No se pudo modificar el usuario');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
			$('#modal_change_usuario').modal('hide');
		});
	}
});
function getUser(){
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag=User"+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		console.log(data);
		mensaje('fail','Error Peticion ajax');
	})
	.done(function(data){
		ListUser = JSON.parse(data);
		console.table(ListUser)

		UserDataTable.destroy();
		displayUserTable();
	});
}
function displayUserTable(){
	UserDataTable = $('#t_user').DataTable({
        "data": ListUser,
        "rowId": 'Id',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
        "iDisplayLength":5,
        "columns":[
            { "data": "Name"},
            { "data": "Surname"},
            // { "data": "Dni"},
            { "data": "Legajo"},
            { "data": "Gender"},
            { "data": "Cellphone"},
            { "data": "Email"},
            { "data": "Secretaria"},
            { "data": "Dependencia"},
            // { "data": "State",
            // 	"render":function(data, type, full, meta){
            // 		if(full.State == 1){
            //        		var string = "Habilitado";
            //        	}else{
            //        		var string = "Deshabilitado";
            //        	}
            //         return string;
            //     }
            // },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<input type=\"button\" class=\"btn btn-info change-name-user\" id=\""+full.Id+"\" value=\"Modificar\">";
            		// if(full.State == 1){
              //      		newInput += "<input type=\"button\" class=\"btn btn-warning change-state-area\" id=\""+full.Id+"\" value=\"Deshabilitar\">";
              //      	}else{
              //      		newInput += "<input type=\"button\" class=\"btn btn-warning change-state-area\" id=\""+full.Id+"\" value=\"Habilitar\">";
              //      	}
                    return newInput;
                }
            }
        ],
    });
}