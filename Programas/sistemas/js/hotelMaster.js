var ListHotel = new Array();
var DataTable;
$(function(){
	GetHotel();
})
$('.close').click(function(){
  $("#form-hotel")[0].reset();
  $("#form-mod-hotel")[0].reset();
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
$(document).on('submit','#form-hotel', function(event) {

	event.preventDefault();
	valid = validar($(this)[0])
    if(valid){
    	swal({
		  title: "Registrando Hotel...",
		  text: "¿Seguro agregar este Hotel?",
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
						alert("error petición ajax");
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se agrego el hotel');
								$("#closemodal").click();
								$("#form-hotel")[0].reset();
								$("#tbHotel tbody").html("");
								DataTable.destroy();
								ListHotel = new Array();
								GetHotel();
							break;
							default: 
								mensaje('fail','No se pudo registrar el hotel');
							break;
						}
					}
					else{
						switch(response.Result){
							case "Existing Hotel" :
								mensaje('fail','El hotel ingresado ya esta registrado');
							break;
							default: 
								mensaje('fail','No se pudo registrar el hotel');
							break;
						}
					}
				});
			}
		});
	}
});
$(document).on('click','#getHotel', function(event) {
	$("#tbHotel tbody").html("");
	DataTable.destroy();
	ListHotel = new Array();
	GetHotel();
});
$(document).on('change','.estado',function(){
	swal({
		title: "Cambiando Estado...",
		text: "¿Seguro que cambiar el estado del hotel?",
		icon: "warning",
		buttons: true,
		dangerMode: true,
	})
	.then((willDelete) => {
		if (willDelete) {
			$(this).attr("disabled","true");
			var state = $(this).val().split(',')[0];
			var idHotel= $(this).val().split(',')[1];

			$.ajax({
				type: "POST",
				url: "functions/",
				data: 'state='+state+'&id='+idHotel+"&pag="+document.title+"&tipo=d",
				dataType: "html",
			})
			.fail(function(data){
					alert("error petición ajax");
			})
			.done(function(data){
				response = JSON.parse(data);
				$(this).attr("disabled","false");
				if(!response.Error){
					switch(response.Result){
						case "Success" :
							$("#tbUsuarios tbody").html("");
							DataTable.destroy();
							ListHotel = new Array();
							GetHotel();
						break;
						default:
							mensaje('fail','No se pudo modificar el estado');						
						break;
					}
				}
				else{
					switch(response.Result){
						default: 
							mensaje('fail','No se pudo modificar el estado');
						break;
					}
				}
			});
		}
	});
});
var id = 0;
$(document).on('click','#btnModificar', function() {
	$('#formularioMod').modal('toggle');
    id = $(this).parents("tr")[0].id;
	$.each(ListHotel, function(i,hotel){
		if(hotel.IdHotel == id){
			document.getElementById('data_mod_hotel').value = hotel.Hotel;
		}
	})
});
$(document).on('submit','#form-mod-hotel', function(event) {
	event.preventDefault();
	valid = validar($(this)[0])
    if(valid){
    	swal({
		  title: "Modificando...",
		  text: "¿Seguro que desea modificar este hotel?",
		  icon: "warning",
		  buttons: true,
		  dangerMode: true,
		})
		.then((willDelete) => {
		  	if (willDelete) {
				$.ajax({
					type: "POST",
					url: "functions/",
					data: $(this).serialize()+"&id="+id+"&pag="+document.title+"&tipo=a",
					dataType: "html",
				})
				.fail(function(data){
						alert("error petición ajax");
				})
				.done(function(data){
					response = JSON.parse(data);
					if(!response.Error){
						switch(response.Result){
							case "Success" :
								mensaje('okey','Se actualizaron los datos del hotel');
								$("#tbHotel tbody").html("");
								DataTable.destroy();
								ListHotel = new Array();
								GetHotel();
							break;
							default:
								mensaje('fail','No se pudo modificar los datos del hotel');
							break;
						}
					}
					else{
						mensaje('fail','No se pudo modificar los datos del hotel');
					}
				});
			}
		});
	}
});
function GetHotel(){
	url = "functions/?pag="+document.title+"&tipo=s";
	$.getJSON(url,function(Datos){
		$("#tbHotel tbody").html("");
		$.each(Datos, function(i,hab){
			if(!hab.Error){
				ListHotel.push(hab);
			}
			else{
				alert("Error");
			}
		});
	})
	.fail(function(data){
		console.log(data)
	})
	.done(function(data){
		console.log(data)
		DataTable = $('#tbHotel').DataTable({
	        "data": ListHotel,
	        "rowId": 'IdHotel',
	     	"deferRender":true,
	        "scrollX":true,
	        "scrollCollapse":true,
		    "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
		    "iDisplayLength":10,
	        "columns":[
	            { "data": "Hotel"},
	            { "data": "Encargado"},
	            { "data": "Estado",
	            	"render":function(data, type, full, meta){
	            		var e1 = "";var e2 = "";
	            		switch(full.Estado){
	            			case 1: 
	            				e1 = "selected";
	            			break;
	            			case 2: 
	            				e2 = "selected";
	            			break;
	            		}
				        var NewSelect = "<select id=\"estado"+full.IdHotel+"\" class=\"form-control estado\">"
				        	+"<option value=\"1,"+full.IdHotel+"\" "+e1+">ACTIVO</option>"
				        	+"<option value=\"2,"+full.IdHotel+"\" "+e2+">INACTIVO</option>"
				        	+"</select>";
				       	return NewSelect;
					}
				},
	            { "data": "action",
	            "render":function(data, type, full, meta){
				       var controls =" <button title=\"Editar\" type=\"button\" class=\"icon-pencil btn btn-md btn-warning\" id=\"btnModificar\"></button>";
				       	return controls;
					}
				}
	        ],
	    });
	})
}