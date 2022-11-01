var ListHab = new Array();
var DataTable;
$(function(){
	GetHab();
})
$('.close').click(function(){
  $("#formRegHab")[0].reset();
  $("#formModHab")[0].reset();
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
$(document).on('click','#getHab', function(event) {
	$("#tbHabitacion tbody").html("");
	DataTable.destroy();
	ListHab = new Array();
	GetHab();
});
$(document).on('click','#btnEliminar', function(event) {
	swal({
	  title: "Atencion",
	  text: "¿Seguro que desea eliminar esta habitacion?",
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
				mensaje('fail','error petición ajax');
		})
		.done(function(data){
			response = JSON.parse(data);
			if(!response.Error){
				switch(response.Result){
					case "Success" :
						swal("¡Listo!", "¡Habitación eliminada con exito!", "success");
							$("#tbHabitacion tbody").html("");
							DataTable.destroy();
							ListHab = new Array();
							GetHab();
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
$(document).on('submit','#formModHab', function(event) {
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
					data: $(this).serialize()+"&id="+id+"&pag="+document.title+"&tipo=a",
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
								mensaje('okey','Se modifico la habitación');
								$("#tbHabitacion tbody").html("");
								DataTable.destroy();
								ListHab = new Array();
								GetHab();
							break;
							default:
								mensaje('fail','No se pudo modificar la habitación');
							break;
						}
					}
					else{
						mensaje('fail','No se pudo modificar la habitación');
					}
				});
			}
		});
	}
});