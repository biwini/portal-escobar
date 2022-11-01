var ListProgram = new Array();
var ProgramDataTable;
$('#open_modal_program').click(function(){
	$('#modal_charge_program').modal('toggle');
});
$('#modal_charge_program').on('hidden.bs.modal', function (e) {
	$("#charge_program")[0].reset();
    emptyDependence();
});
$('#modal_change_program').on('hidden.bs.modal', function (e) {
	$("#change_program")[0].reset();
    emptyDependence();
});
$(document).on('submit', '#charge_program', function(e){
	e.preventDefault();
	var program = $.trim($('#inpPrograma').val());
	var url = $.trim($('#programUrl').val());
	if(program.length >= 4){
		programArea = $.trim($('#programDependencia').val());
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Program"+"&tipo=i",
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
					// ProgramDataTable.destroy();
					getProgram();
					$("#charge_program")[0].reset();
					$('#modal_charge_program').modal('hide');
					mensaje('okey','Se creo el programa');
				break;
				case 'Error':
					mensaje('fail','No se pudo crear el programa');
				break;
				case 'Existing Program':
					mensaje('fail','El programa ya existe');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
		});
	}else{
		mensaje('fail','Ingrese un nombre valido para el programa.');
	}
});
$(document).on('click', '.change-state-program', function(e){
	var Id = $.trim($(this)[0].id);

	if(Id > 0){
		accion = $(this).val();
		$.ajax({
			type: "POST",
			url: "controller/",
			data: "program="+Id+"&pag=Program"+"&tipo=d"+"&action="+accion,
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
					mensaje('okey','Se actualizo el estado del programa');
					getProgram();
				break;
				case 'Error':
					mensaje('fail','No se pudo cambiar el estado del programa');
				break;
				case 'Invalid Form':
					mensaje('fail','Datos invalidos');
				break;
			}
		});
	}
});
var IdProgram = 0;
$(document).on('click', '.change-name-program', function(e){
	IdProgram = $(this)[0].id;
	$.each(ListProgram, function(i,p){
		if(IdProgram == p.Id){
			emptyDependence();
			setDependences(p.IdSecretaria);
			$('#modProgram').val(p.Name);
			$('#modProgramUrl').val(p.Url);
			$("#modProgramSecretaria option[value='"+p.IdSecretaria+"']").prop("selected",true);
			$("#modProgramDependencia option[value='"+p.IdDependencia+"']").prop("selected",true);
		}
	});
	$('#modal_change_program').modal('toggle');
});
$(document).on('submit', '#change_program', function(e){
	e.preventDefault();
	var program = $.trim($('#modProgram').val());
	var modUrl = $.trim($('#modProgramUrl').val());
	if(program.length >= 4){
		modProgramDependence = $('#modProgramDependencia').val();
		$.ajax({
			type: "POST",
			url: "controller/",
			data: $(this).serialize()+"&pag=Program"+"&tipo=u"+'&id='+IdProgram,
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
					mensaje('okey','Se modifico el programa');
					$('#modProgram').val("")
					$('#modProgramUrl').val("")
					getProgram();
				break;
				case 'Error':
					mensaje('fail','no se pudo modificar el programa');
				break;
				case 'Invalid call':
					mensaje('fail','Datos invalidos');
				break;
			}
			$('#modal_change_program').modal('hide');
		});
	}else{
		mensaje('fail','Ingrese un nombre valido');
	}
});
function getProgram(){
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag=Program"+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		console.log(data);
		alert('Error Peticion ajax');
	})
	.done(function(data){
		ListProgram = JSON.parse(data);
		console.log(ListProgram)
		$('#datosProgramas2 option').remove();
		$('#datosProgramas option').remove();
		$.each(ListProgram, function(i,p){
			newOption = '<option value=\''+p.Id+'\'>'+p.Name+'</option>';
			$('#datosProgramas2').append(newOption);
			$('#datosProgramas').append(newOption);
		});
		ProgramDataTable.destroy();
		displayProgramTable();
	});
}
function displayProgramTable(){
	ProgramDataTable = $('#t_program').DataTable({
        "data": ListProgram,
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
            { "data": "Secretaria"},
            { "data": "Dependencia"},
            { "data": "Url",
            	"render":function(data, type, full, meta){
            		var url = (full.Url == null) ? "" : full.Url;
            		var programa = (full.Url == null) ? "" : full.Name;
            		var newInput = "<a href=\""+url+"\" target=\"blanck\" reel=\"noopener\">"+programa+"</a>";
                    return newInput;
                }
            },
            { "data": "State",
            	"render":function(data, type, full, meta){
            		if(full.State == 1){
                   		var string = "Habilitado";
                   	}else{
                   		var string = "Deshabilitado";
                   	}
                    return string;
                }
            },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-info change-name-program\" id=\""+full.Id+"\" value=\"Modificar\"></div>";
            		if(full.State == 1){
                   		newInput += "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-warning change-state-program\" id=\""+full.Id+"\" value=\"Deshabilitar\"></div>";
                   	}else{
                   		newInput += "<div class=\"col-md-12\"><input type=\"button\" class=\"form-control btn btn-warning change-state-program\" id=\""+full.Id+"\" value=\"Habilitar\"></div>";
                   	}
                    return newInput;
                }
            }
        ],
    });
}