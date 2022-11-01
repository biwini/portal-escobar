var ListLocalidad = new Array();
var LocalidadDataTable;
$('#open_modal_location').click(function(){
	$('#modal_localidad').modal('toggle');
});
$('#modal_localidad').on('hidden.bs.modal', function (e) {
	$("#form_localidad")[0].reset();
    emptyDependence();
});
$('#modal_change_localidad').on('hidden.bs.modal', function (e) {
	$("#form_mod_localidad")[0].reset();
    emptyDependence();
});
$(document).on('submit', '#form_localidad', function(e){
	e.preventDefault();
	var location = $.trim($('#localidad').val());
	if(location.length >= 4){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Localidad"+"&tipo=i",
			dataType: "html",
		})
		.fail(function(data){
			console.log(data);
			mensaje('fail','Error peticion ajax');
		})
		.done(function(data){
			
			response = JSON.parse(data);
			switch(response.Status){
				case 'Success':
					// LocalidadDataTable.destroy();
					getProgram();
					$("#form_localidad")[0].reset();
					$('#modal_localidad').modal('hide');
					mensaje('okey','Se agrego la localidad');
				break;
				case 'Error':
					mensaje('fail','No se pudo agregar la localidad');
				break;
				case 'Existing Location Name':
					mensaje('fail','La localidad ya existe');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
		});
	}else{
		mensaje('fail','El nombre de la localidad no puede tener menos de 4 letras.');
	}
});

var idLocation = 0;
$(document).on('click', '.change-name-location', function(e){
	idLocation = $(this)[0].id;
	$.each(ListLocalidad, function(i,l){
		if(idLocation == l.Id){
			$('#modLocalidad').val(l.Location);
		}
	});
	$('#modal_change_localidad').modal('toggle');
});
$(document).on('submit', '#form_mod_localidad', function(e){
	e.preventDefault();
	var localidad = $.trim($('#modLocalidad').val());
	if(localidad.length >= 4){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Localidad"+"&tipo=u"+'&id='+idLocation,
			dataType: "html",
		})
		.fail(function(data){
			console.log(data);
			mensaje('fail','Error peticion ajax');
		})
		.done(function(data){
			response = JSON.parse(data);
			switch(response.Status){
				case 'Success':
					mensaje('okey','Se modifico la localidad');
					$("#form_mod_localidad")[0].reset();
					getLocation();
				break;
				case 'Error':
					mensaje('fail','no se pudo modificar la localidad');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
			$('#modal_change_localidad').modal('hide');
		});
	}else{
		mensaje('fail','El nombre de la localidad no puede tener menos de 4 letras.');
	}
});
function getLocation(){
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag=Localidad"+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		console.log(data);
		alert('Error Peticion ajax');
	})
	.done(function(data){
		ListLocalidad = JSON.parse(data);
		console.log(ListLocalidad)
		// $('#datosProgramas2 option').remove();
		// $('#datosProgramas option').remove();
		// $.each(ListLocalidad, function(i,p){
		// 	newOption = '<option value=\''+l.Id+'\'>'+l.Name+'</option>';
		// 	$('#datosProgramas2').append(newOption);
		// 	$('#datosProgramas').append(newOption);
		// });
		LocalidadDataTable.destroy();
		displayLocationTable();
	});
}
function displayLocationTable(){
	LocalidadDataTable = $('#t_location').DataTable({
        "data": ListLocalidad,
        "rowId": 'Id',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
        "iDisplayLength":5,
    //     aoColumnDefs: [
    //       { bSortable: false, aTargets: [ 4, 5,6 ] },
    //       { sWidth: "16%", aTargets: [  1, 2,3,4,5,6 ] },
    // ],
        "aocolumnDefs": [
            { "width": '100px', "targets": 4 }
        ],
        "fixedColumns": true,
        "columns":[
            { "data": "Location"},
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-info change-name-location\" id=\""+full.Id+"\" value=\"Modificar\"></div>";
                    return newInput;
                }
            }
        ],
    });
}