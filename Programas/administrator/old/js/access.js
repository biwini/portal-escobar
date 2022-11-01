var ListAccess = new Array();
var ListAuditoria = new Array();
var AccessDataTable;
var AuditoriaDataTable;

$('#open_modal_access').click(function(){
	$('#modal_charge_access').modal('toggle');
});
$(document).on('submit', '#form_access', function(e){
	e.preventDefault();

	if(validate($(this).find('.required'))){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Access"+"&tipo=i",
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
					mensaje('okey','Se creo el acceso');
					$("#form_access")[0].reset();
					getAccess();
				break;
				case 'Error':
					mensaje('fail','No se pudo crear el acceso');
				break;
				case 'Existing Access':
					mensaje('fail','El usuario ya tiene acceso al programa');
				break;
				case 'Invalid Call':
					mensaje('fail','Datos Invalidos');
				break;
			}
		});
	}
});
$(document).on('click', '.delete-access-user', function(e){
	valid = false;
	IdAccess = $(this)[0].id;
	$.each(ListAccess, function(i,a){
		if(IdAccess == a.IdAccess){
			valid = true;
		}
	});
	if(valid){
		swal({
	      title: "Eliminando Acceso...",
	      text: "¿Seguro que desea eliminar el acceso?",
	      icon: "warning",
	      buttons: [
	        'Cancelar',
	        'Eliminar'
	      ],
	      dangerMode: true,
	    }).then(function(isConfirm) {
	      if (isConfirm) {
	      	$.ajax({
			    type: "POST",
			    url: "controller/",
			    data: "pag=Access"+"&tipo=d"+"&id="+IdAccess,
			    dataType: "html",
			})
			.fail(function(data){
			    mensaje('fail','Error Peticion ajax');
			})
			.done(function(data){
				response = JSON.parse(data);
			    switch(response.Status){
			        case 'Success':
			          mensaje('okey','Se Elimino el acceso');
			          getAccess();
			        break;
			        case 'Error':
			          mensaje('fail','No se pudo eliminar el acceso');
			        break;
			        case 'Invalid call':
			          mensaje('fail','Datos invalidos');
			        break;
			        case 'Unknown Access':
			          mensaje('fail','Datos invalidos');
			        break;
			    }
			});
	      }
	    })
	}
});
$(document).on('click', '.change-state-access', function(e){
	valid = false;
	IdAccess = $(this)[0].id;
	$.each(ListAccess, function(i,a){
		if(IdAccess == a.IdAccess){
			valid = true;
		}
	});
	if(valid){
	    accion = $(this).val();
	    $.ajax({
	      type: "POST",
	      url: "controller/",
	      data: "id="+IdAccess+"&pag=Access"+"&tipo=c"+"&action="+accion,
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
	          mensaje('okey','Se modifico el area');
	          getAccess();
	        break;
	        case 'Error':
	          mensaje('fail','No se pudo deshabilitar el programa');
	        break;
	        case 'Invalid call':
	          mensaje('fail','Datos invalidos');
	        break;
	      }
	    });
	}
});
var IdAccess = 0;
$(document).on('click', '.change-access-user', function(e){
	IdAccess = $(this)[0].id;
	$.each(ListAccess, function(i,a){
		if(IdAccess == a.IdAccess){
			$("#mod_permissions_access option[value='"+a.Permiso+"']").prop("selected",true);
			$("#mod_program_access option[value='"+a.IdProgram+"']").prop("selected",true);
		}
	});
	// IdArea = $(this)[0].id;
	// oldArea = $(this).parents('tr').children('td')[0].innerHTML
	// oldUbication = $(this).parents('tr').children('td')[1].innerHTML;
	$('#modal_change_access').modal('show');
});
$(document).on('click', '#change_access', function(e){

  if(IdAccess > 0){
  	modPermissions = $('#mod_permissions_access').val();
  	modProgram = $('#mod_program_access').val();
    $.ajax({
      type: "POST",
      url: "controller/",
      data: "id="+IdAccess+"&pag=Access"+"&tipo=u"+"&permissions_access="+modPermissions+"&program_access="+modProgram,
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
          mensaje('okey','Se modifico el acceso');
          $('#modal_change_access').modal('hide');
          getAccess();
        break;
        case 'Error':
          mensaje('fail','No se pudo modificar el acceso');
        break;
        case 'Invalid call':
          mensaje('fail','Datos invalidos');
        break;
        case 'Unknown Access':
          mensaje('fail','Datos invalidos');
        break;
      }
    });
  }
});
function getAccess(){
	$.ajax({
	    type: "POST",
	    url: "controller/",
	    data: "pag=Access"+"&tipo=g",
	    dataType: "html",
	})
	.fail(function(data){
	    console.log(data);
	    mensaje('fail','Error Peticion ajax');
	})
	.done(function(data){
	    ListAccess = JSON.parse(data);
	    console.log(ListAccess)

	    AccessDataTable.destroy();
	    displayAccessTable();
	});
}

function displayAccessTable(){
	AccessDataTable = $('#t_access').DataTable({
        "data": ListAccess,
        "rowId": 'IdAccess',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
        "iDisplayLength":5,
        "columnDefs": [
	      { "width": "300px", "targets": 4 },
	    ],
        "columns":[
            { "data": "UserName",
            	"render":function(data, type, full, meta){
            		name = full.UserName+' | '+full.Legajo;
            		return name;
                }
            },
            { "data": "ProgramName"},
            { "data": "State",
            	"render":function(data, type, full, meta){
            		permiso = "Deshabilitado";
            		if(full.State == 1){
            			permiso = 'Habilitado';
            		}
            		return permiso;
                }
            },
            { "data": "Permiso",
            	"render":function(data, type, full, meta){
            		permiso = "Usuario";
            		switch(full.Permiso){
            			case "1":
            				permiso = 'Administrador';
            			break;
            		}
            		return permiso;
                }
            },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<input type=\"button\" class=\"btn btn-info change-access-user\" id=\""+full.IdAccess+"\" value=\"Modificar\" style=\"\">";
            		if(full.State == 1){
                   		newInput += "<input type=\"button\" class=\"btn btn-warning change-state-access\" id=\""+full.IdAccess+"\" value=\"Deshabilitar\" style=\"\">";
                   	}else{
                   		newInput += "<input type=\"button\" class=\"btn btn-warning change-state-access\" id=\""+full.IdAccess+"\" value=\"Habilitar\" style=\"\">";
                   	}
                   	newInput += "<input type=\"button\" class=\"btn btn-danger delete-access-user\" id=\""+full.IdAccess+"\" value=\"Eliminar\" style=\"\">";
                    return newInput;
                }
            }
        ],
    });
}
function displayAuditoriaTable(){
	AuditoriaDataTable = $('#t_auditoria').DataTable({
        "data": ListAuditoria,
        "rowId": 'IdAuditoria',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
        "iDisplayLength":5,
        "columns":[
            { "data": "UserName",
            	"render":function(data, type, full, meta){
            		name = full.Name+' '+full.Surname;
            		return name;
                }
            },
            { "data": "Legajo"},
            { "data": "Access"},
            { "data": "Ip"},
            { "data": "Date"}
        ],
    });
}