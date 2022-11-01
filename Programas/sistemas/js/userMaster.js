var ListUser = new Array();
var DataTable;
$(function(){
	GetUser();
})
$('.close').click(function(){
  $("#formRegUser")[0].reset();
  $("#formModUser")[0].reset();
});
cont = 0;
function validar($this){
	$.each($this, function(i,input) {
        if(input.value.length <= 0 ) {
            validate = false;
            cont = cont + 1;
            return false;
        }
    });
    if(cont == 0){
    	return true;
    }else{
    	return false;
    }
}
$(document).on('submit','#formRegUser', function(event) {

	event.preventDefault();
	valid = validar($(this)[0])
    if(valid){
    	swal({
		  title: "Atencion",
		  text: "¿Seguro agregar a este usuario?",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  	if (willDelete) {
				$.ajax({
					type: "POST",
					url: "functions/",
					data: $(this).serialize()+"&pag="+document.title+"&tipo=r",
					dataType: "html",
				})
				.fail(function(data){
						mensaje('fail','Ocurrio un error al registrar el usuario');
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se registro el usuario');
								$("#formRegUser")[0].reset();
								$("#tbUsuarios tbody").html("");
								DataTable.destroy();
								ListUser = new Array();
								GetUser();
							break;
							default: 
								mensaje('fail','No se pudo registrar el usuario');
							break;
						}
					}
					else{
						switch(response.Result){
							case "Existing User" :
								mensaje('fail','El usuario ya existe');
							break;
							default: 
								mensaje('fail','No se pudo registrar el usuario');
							break;
						}
					}
				});
			}
		});
	}
});
$(document).on('click','#getUser', function(event) {
	$("#tbUsuarios tbody").html("");
	DataTable.destroy();
	ListUser = new Array();
	GetUser();
});
$(document).on('click','#btnEliminar', function(event) {
	swal({
	  title: "Atencion",
	  text: "¿Seguro que desea eliminar este usuario?",
	  icon: "warning",
	  buttons: true,
	  dangerMode: true,
	})
	.then((willDelete) => {
	  if (willDelete) {
	  	$.ajax({
			type: "POST",
			url: "functions/",
			data: "id="+$(this).parents("tr")[0].id+"&pag="+document.title+"&tipo=e",
			dataType: "html",
		})
		.fail(function(data){
				mensaje('fail','Ocurrio un error al eliminar el usuario');
		})
		.done(function(data){
			response = JSON.parse(data);
			if(!response.Error){
				switch(response.Result){
					case "Success" :
						mensaje('okey','Se elimino el usuario');
							$("#tbUsuarios tbody").html("");
							DataTable.destroy();
							ListUser = new Array();
							GetUser();
					break;
					default: 
						mensaje('fail','No se pudo eliminar el usuario');
					break;
				}
			}
			else{
				mensaje('fail','Ocurrio un error al eliminar el usuario');
			}
		});
	  }
	});
});
var id = 0;
$(document).on('click','#btnModificar', function() {
	$('#formularioMod').modal('toggle');
	// Creacion de variables para la modificacion.
    id = $(this).parents("tr")[0].id;
	$.each(ListUser, function(i,user){
		if(user.Id == id){
			document.getElementById('modHotel').value = user.IdH;
			document.getElementById('modNombre').value = user.Nombre;
			document.getElementById('modApellido').value = user.Apellido;
			document.getElementById('modDni').value = user.DNI;
			document.getElementById('modEmail').value = user.Email;
			document.getElementById('modAcceso').value = user.Access;
		}
	})
});
$(document).on('submit','#formModUser', function(event) {
	event.preventDefault();
	valid = validar($(this)[0])
    if(valid){
    	swal({
		  title: "Atencion",
		  text: "¿Seguro que desea modificar a este usuario?",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  	if (willDelete) {
				$.ajax({
					type: "POST",
					url: "functions/",
					data: $(this).serialize()+"&pag="+document.title+"&tipo=a"+"&id="+id,
					dataType: "html",
				})
				.fail(function(data){
						mensaje('fail','Ocurrio un error al modificar el usuario');
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se actualizaron los datos del usuario');
								$("#tbUsuarios tbody").html("");
								DataTable.destroy();
								ListUser = new Array();
								GetUser();
							break;
							default: 
								mensaje('fail','Ocurrio un error al modificar el usuario');
							break;
						}
					}
					else{
						mensaje('fail','Ocurrio un error al modificar el usuario');
					}
				});
			}
		});
	}
});
function GetUser(){
	url = "functions/?pag="+document.title+"&tipo=s";
	$.getJSON(url,function(Datos){
		$("#tbUsuarios tbody").html("");
		$.each(Datos, function(i,user){
			if(!user.Error){
				ListUser.push(user);
			}
			else{
				mensaje('fail','Ocurrio un error al obtener los usuarios');
			}
		});
	})
	.fail(function(data){
		mensaje('fail','Ocurrio un error al obtener los usuarios');
	})
	.done(function(data){
		console.log(data)
		DataTable = $('#tbUsuarios').DataTable({
	        "data": ListUser,
	        "rowId": 'Id',
	     	"deferRender":true,
	        "scrollX":true,
	        "scrollCollapse":true,
		    "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
		    "iDisplayLength":10,
	        "columns":[
	        	{ "data": "NomH"},
	            { "data": "Nombre"},
	            { "data": "Apellido"},
	            { "data": "DNI"},
	            { "data": "Email"},
	            { "data": "Access",
	            "render":function(data, type, full, meta){
				       if(full.Access == 2){
				       		return "Gerente de Hotel";
				       }
				       else if(full.Access == 3){
				       		return "Recepcionista";
				       }else{
				       		return "Administrador General";
				       }
					}
	        	},
	            { "data": "action",
	            "render":function(data, type, full, meta){
				       var controls = "<button title=\"Eliminar\" type=\"button\" class=\"icon-bin btn btn-md btn-danger\" data\"1\" id=\"btnEliminar\"></button> "
				       		+ " <button title=\"Editar\" type=\"button\" class=\"icon-pencil btn btn-md btn-warning\" id=\"btnModificar\"></button>";
				       	return controls;
					}
				}
	        ],
	    });
	})
}