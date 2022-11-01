var ListEquipo = new Array();
var EquipoDataTable;

console.log('hola');
$('#open_modal_equipo').click(function(){
	$('#modal_charge_equipo').modal('toggle');
});
$('#modal_charge_equipo').on('hidden.bs.modal', function (e) {
	$("#form_equipo")[0].reset();
    emptyDependence();
});
$('#modal_change_equipo').on('hidden.bs.modal', function (e) {
	$("#form_change_equipo")[0].reset();
    emptyDependence();
});
$(document).on('submit', '#form_equipo', function(e){
	e.preventDefault();

	$.ajax({
		type: "POST",
		url: "controller/",
		data: $(this).serialize()+"&pag=Equipo"+"&tipo=i",
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
				// EquipoDataTable.destroy();
				getEquipo();
				$("#form_equipo")[0].reset();
				$('#modal_charge_equipo').modal('hide');
				mensaje('okey','Se agrego el equipo');
				$("#usuario_asignado option[value='SIN ASIGNAR']").prop("selected",true);
				$("#usuario_asignado").selectpicker("refresh");
			break;
			case 'Error':
				mensaje('fail','No se pudo agregar el equipo');
			break;
			case 'Existing Intern':
				mensaje('fail','El numero interno ya esta registrado');
			break;
			case 'Existing Patrimony':
				mensaje('fail','El numero de patrimonio ya esta registrado');
			break;
			case 'Invalid call':
				mensaje('fail','Datos invalidos');
			break;
		}
	});
});

var idEquipo = 0;
$(document).on('click', '.update-equipo', function(e){
	idEquipo = $(this)[0].id;
	$.each(ListEquipo, function(i,e){
		if(idEquipo == e.Id){
			emptyDependence();
			setDependences(e.IdSecretary);

			// $('#new_compartida').val(e.Name);
			$("#new_equipoSecretaria option[value='"+e.IdSecretary+"']").prop("selected",true);
			$("#new_equipoDependencia option[value='"+e.IdDependence+"']").prop("selected",true);
		}
	});
	$('#modal_change_equipo').modal('toggle');
});
$(document).on('submit', '#form_change_equipo', function(e){
	e.preventDefault();

	$.ajax({
		type: "POST",
		url: "controller/",
		data: $(this).serialize()+"&pag=Equipo"+"&tipo=u"+'&id='+idEquipo,
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
				mensaje('okey','Se modifico el equipo');
				$("#form_change_equipo")[0].reset();
				getEquipo();
			break;
			case 'Error':
				mensaje('fail','no se pudo modificar el equipo');
			break;
			case 'Existing Intern':
				mensaje('fail','El numero interno ya esta registrado');
			break;
			case 'Existing Patrimony':
				mensaje('fail','El numero de patrimonio ya esta registrado');
			break;
			case 'Invalid call':
				mensaje('fail','Datos invalidos');
			break;
		}
		$('#modal_change_equipo').modal('hide');
	});
});
function getEquipo(){
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag=Equipo"+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		console.log(data);
		alert('Error Peticion ajax');
	})
	.done(function(data){
		ListEquipo = JSON.parse(data);
		console.log(ListEquipo)
		// $('#datosProgramas2 option').remove();
		// $('#datosProgramas option').remove();
		// $.each(ListEquipo, function(i,p){
		// 	newOption = '<option value=\''+l.Id+'\'>'+l.Name+'</option>';
		// 	$('#datosProgramas2').append(newOption);
		// 	$('#datosProgramas').append(newOption);
		// });
		EquipoDataTable.destroy();
		displayEquipoTable();
	});
}
function displayEquipoTable(){
	EquipoDataTable = $('#t_equipo').DataTable({
        "data": ListEquipo,
        "rowId": 'IdEquipo',
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
            { "data": "Type"},
            { "data": "Patrimony"},
            { "data": "Intern"},
            { "data": "Brand"},
            { "data": "Model"},
            { "data": "NameDependence"},
            { "data": "User",
            	"render":function(data, type, full, meta){
            		var newLabel = (full.User['Name'] !== undefined) ? '<label>'+full.User["Name"]+' | '+full.User["Legajo"]+'</label>' : '<label>SIN ASIGNAR</label>';
                    return newLabel;
                }
            },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-info update-equipo\" id=\""+full.IdEquipo+"\" value=\"Modificar\"></div>";
                    return newInput;
                }
            }
        ],
    });
}