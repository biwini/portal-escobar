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
					url: "php/regAdmin.php",
					data: $(this).serialize(),
					dataType: "html",
				})
				.fail(function(data){
						mensaje('fail','ocurrio un error durante la peticion ajax');
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se registro el usuario');
								$("#closemodal").click();
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
			url: "php/deleteUser.php",
			data: "id="+$(this).parents("tr")[0].id,
			dataType: "html",
		})
		.fail(function(data){
				mensaje('fail','ocurrio un error durante la peticion ajax');
		})
		.done(function(data){
			response = JSON.parse(data);
			if(!response.Error){
				switch(response.Result){
					case "Success" :
						swal("¡Listo!", "¡Usuario eliminado con exito!", "success");
							$("#tbUsuarios tbody").html("");
							DataTable.destroy();
							ListUser = new Array();
							GetUser();
					break;
					default: 
						swal("¡Oops!", "¡Problema inesperado!", "warning");
					break;
				}
			}
			else{
				swal("¡Oops!", "¡Error!", "error");
			}
		});
	  }
	});
});
var id = 0;
$(document).on('click','#btnModificar', function() {
	$('#formularioMod').modal('toggle');
	// Creacion de variables para la modificacion.
    var val="";
    camp = new Array();
    camp = ["Acceso","Apellido","Nombre","Dni"];
    l = $(this).parents("tr").find("td").length - 1;
    id = $(this).parents("tr")[0].id;
    // Obtenemos todos los valores contenidos en los <td> de la fila seleccionada
    $(this).parents("tr").find("td").each(function(){
    	//Omitimos los botones.
    	if($(this).html().indexOf("<input") == -1){
        	val = $(this).html();
    		if(val == "Administrador"){
    			val = 1;
    		}else if(val == "Usuario"){
    			val = 2;
    		}
        	document.getElementById("mod"+camp[l - 1]).value = val;
        	l--;
    	}
    });
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
					url: "php/updateUser.php",
					data: $(this).serialize()+"&id="+id,
					dataType: "html",
				})
				.fail(function(data){
						mensaje('fail','ocurrio un error durante la peticion ajax');
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se modifico correctamente');
								$("#tbUsuarios tbody").html("");
								DataTable.destroy();
								ListUser = new Array();
								GetUser();
							break;
							default: 
								mensaje('fail','No se pudo modificar el usuario');
							break;
						}
					}
					else{
						mensaje('fail','No se pudo modificar el usuario');
					}
				});
			}
		});
	}
});
function GetUser(){
	url = "php/getUser.php";
	$.getJSON(url,function(Datos){
		$("#tbUsuarios tbody").html("");
		$.each(Datos, function(i,user){
			if(!user.Error){
				ListUser.push(user);
			}
			else{
				mensaje('fail','No se pudo obtener los usuarios');
			}
		});
	})
	.fail(function(data){
		console.log(data)
	})
	.done(function(data){
		DataTable = $('#tbUsuarios').DataTable({
	        "data": ListUser,
	        "rowId": 'Id',
	     //    "drawCallback": function () {
		    //     console.log( 'Table redrawn '+new Date() );
		    // },
	        "columns":[
	            { "data": "DNI"},
	            { "data": "Nombre"},
	            { "data": "Apellido"},
	            { "data": "Access",
	            "render":function(data, type, full, meta){
				       if(full.Access == 1){
				       		return "Administrador";
				       }
				       else{
				       		return "Usuario";
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
function ModUser(){
	var nom,ape,dni,nac,acceso;

	nom = document.getElementById('nombre').value;
	ape = document.getElementById('apellido').value;
	dni = document.getElementById('dni').value;
	nac = document.getElementById('fnacimiento').value;
	acceso = document.getElementById('acceso').value;

	if(nom != "" && ape != "" && dni != "" && nac != "" && acceso != ""){
		$.ajax({
			type: "POST",
			url: "php/modAdmin.php",
			data: "nom="+nom+"&ape="+ape+"&dni="+dni+"&nac="+nac+"&acceso="+acceso,
			dataType: "html",
			error: function(){
				mensaje('fail','ocurrio un error durante la peticion ajax');
			},
			success: function(data){
				switch(data){
					case "Succes" :

					break;
					default: 

					break;
				}
			}
		});
	}
}
function CambiarContraseña(){
	var pass = document.getElementById('pass').value;
	var repPass = document.getElementById('repPass').value;
	if(pass != "" && repPass != ""){
		$.ajax({
			type: "POST",
			url: "php/modPassAdmin.php",
			data: "pass="+pass+"&repPass="+repPass,
			dataType: "html",
			error: function(){
				mensaje('fail','ocurrio un error durante la peticion ajax');
			},
			success: function(data){
				switch(data){
					case "Succes" :

					break;
					default: 

					break;
				}
			}
		});
	}
}