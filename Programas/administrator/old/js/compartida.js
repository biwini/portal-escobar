var ListCompartida = new Array();
var CompartidaDataTable;
$('#open_modal_compartida').click(function(){
	$('#modal_charge_compartida').modal('toggle');
});
$('#modal_charge_compartida').on('hidden.bs.modal', function (e) {
	$("#form_compartida")[0].reset();
    emptyDependence();
});
$('#modal_change_compartida').on('hidden.bs.modal', function (e) {
	$("#form_change_compartida")[0].reset();
    emptyDependence();
});
$(document).on('submit', '#form_compartida', function(e){
	e.preventDefault();
	var compartida = $.trim($('#compartida').val());
	if(compartida.length >= 2){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Compartida"+"&tipo=i",
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
					// CompartidaDataTable.destroy();
					getCompartida();
					$("#form_compartida")[0].reset();
					$('#modal_charge_compartida').modal('hide');
					mensaje('okey','Se agrego la Compartida');
				break;
				case 'Error':
					mensaje('fail','No se pudo agregar la compartida');
				break;
				case 'Existing Location Name':
					mensaje('fail','La compartida ya existe');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
		});
	}else{
		mensaje('fail','El nombre de la compartida no puede tener menos de 2 letras.');
	}
});

var idCompartida = 0;
$(document).on('click', '.change-name-compartida', function(e){
	idCompartida = $(this)[0].id;
	$.each(ListCompartida, function(i,c){
		if(idCompartida == c.Id){
			emptyDependence();
			setDependences(c.IdSecretary);

			$('#new_compartida').val(c.Name);
			$("#new_compatidaSecretaria option[value='"+c.IdSecretary+"']").prop("selected",true);
			$("#new_compartidaDependencia option[value='"+c.IdDependence+"']").prop("selected",true);
		}
	});
	$('#modal_change_compartida').modal('toggle');
});
$(document).on('submit', '#form_change_compartida', function(e){
	e.preventDefault();
	var compartida = $.trim($('#new_compartida').val());
	if(compartida.length >= 2){
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Compartida"+"&tipo=u"+'&id='+idCompartida,
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
					mensaje('okey','Se modifico la compartida');
					$("#form_change_compartida")[0].reset();
					getCompartida();
				break;
				case 'Error':
					mensaje('fail','no se pudo modificar la compartida');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
			$('#modal_change_compartida').modal('hide');
		});
	}else{
		mensaje('fail','El nombre de la compartida no puede tener menos de 2 letras.');
	}
});
function getCompartida(){
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag=Compartida"+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		console.log(data);
		alert('Error Peticion ajax');
	})
	.done(function(data){
		ListCompartida = JSON.parse(data);
		console.log(ListCompartida)
		// $('#datosProgramas2 option').remove();
		// $('#datosProgramas option').remove();
		// $.each(ListCompartida, function(i,p){
		// 	newOption = '<option value=\''+l.Id+'\'>'+l.Name+'</option>';
		// 	$('#datosProgramas2').append(newOption);
		// 	$('#datosProgramas').append(newOption);
		// });
		CompartidaDataTable.destroy();
		displayCompartidaTable();
	});
}
function displayCompartidaTable(){
	CompartidaDataTable = $('#t_compartida').DataTable({
        "data": ListCompartida,
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
            { "data": "Name"},
            { "data": "Secretary"},
            { "data": "Dependence"},
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-info change-name-compartida\" id=\""+full.Id+"\" value=\"Modificar\"></div>";
                    return newInput;
                }
            }
        ],
    });
}