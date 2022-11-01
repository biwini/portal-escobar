var ListMotivo = new Array();
var DataTable;
$('#open_modal_motivo').click(function(){
	$('#modal_motivo').modal('toggle');
});
$('#open_modal_submotivo').click(function(){
	$('#modal_submotivo').modal('toggle');
});
$('#modal_motivo').on('hidden.bs.modal', function (e) {
	$("#form_motivo")[0].reset();
});
$('#modal_change_motivo').on('hidden.bs.modal', function (e) {
	$("#form_change_motivo")[0].reset();
	$("#div_submotivo").html('<label>Sub Motivos:</label>');
});
var idMotivo = 0;
$(document).on('click', '.change-motivo', function(e){
	idMotivo = $(this)[0].id;
	$.each(ListMotivo, function(i,m){
		if(idMotivo == m.Id){
			$("#mod_motivo").val(m.Motivo);
			console.log(m)
			if(m.SubMotivo.length > 0){
				table = '<table class=\'table\' id=\'t_submotivo\'>';
					table += '<thead>';
						table += '<tr>';
							table += '<th>Nombre</th><th>Tiempo estimado</th><th colspan=\'2\'>Acciones</th>';
						table += '<tr>';
					table += '</thead>';
					table += '<tbody>';
				$.each(m.SubMotivo, function(i,s){
						table += '<tr>';
							table += '<td><input type=\'text\' class=\'form-control submotivo-'+s.Id+'\' name=\'submotivo_name_'+s.Id+'\' id=\'submotivo_name_'+s.Id+'\' value=\''+s.SubMotivo+'\'></td>';
							table += '<td><input type=\'text\' class=\'form-control estimatedtime-submotivo-'+s.Id+'\' name=\'submotivo_estimatedtime_'+s.Id+'\' id=\'submotivo_estimatedtime_'+s.Id+'\' value=\''+s.EstimatedTime+'\'></td>';
							// table += '<td><input type=\'button\' class=\'btn btn-primary update-submotivo\' id=\''+s.Id+'\' value=\'Modificar\'></td>';
							table += '<td><input type=\'button\' class=\'btn btn-danger change-state-submotivo\' value=\'Inhabilitar\'></td>';
						table += '</tr>';
					// input = '<input type=\'text\' class=\'form-control required\' name=\'submotivo_'+s.Id+'\' id=\'sub_motivo_'+s.Id+'\' value=\''+s.SubMotivo+'\' style=\'margin-bottom:10px;\'>';
					// input += '<input type=\'number\' class=\'form-control required\' name=\'tiempo_estimado'+s.Id+'\' id=\'sub_motivo_'+s.Id+'\' value=\''+s.SubMotivo+'\' style=\'margin-bottom:10px;\'>';
					 
				});
					table += '</tbody>';
				table += '</table>';
				$('#div_submotivo').append(table);
			}
		}
	});
	// IdArea = $(this)[0].id;
	// oldArea = $(this).parents('tr').children('td')[0].innerHTML
	// oldUbication = $(this).parents('tr').children('td')[1].innerHTML;
	$('#modal_change_motivo').modal('show');
});
// $(document).on('click', '.update-submotivo', function(e){

// 	if(typeof($('.submotivo-'+$(this)[0].id).attr('disabled') ) == 'undefined') {
// 		$('.submotivo-'+$(this)[0].id).attr('disabled','true');
// 		$('.estimatedtime-submotivo-'+$(this)[0].id).attr('disabled','true');
// 	}else{
// 		$('.submotivo-'+$(this)[0].id).removeAttr('disabled');
// 		$('.estimatedtime-submotivo-'+$(this)[0].id).removeAttr('disabled');
// 	}
// });
function displayDataTable(){
	DataTable = $('#t_motivo').DataTable({
        "data": ListMotivo,
        "rowId": 'Id',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
        "iDisplayLength":5,
        "columns":[
            { "data": "Motivo"},
            { "data": "SubMotivo",
            	"render":function(data, type, full, meta){
            		var list = '<ul>';
            		if(full.SubMotivo.length > 0){
	            		$.each(full.SubMotivo, function(i,m){
	            			if(full.Id == m.IdMotivo){
	            				list += '<li>'+m.SubMotivo+'</li>';
	            			}
						});
	            	}
                    return list+'</ul>';
                }
            },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var timeList = '<ul>';
            		if(full.SubMotivo.length > 0){
	            		$.each(full.SubMotivo, function(i,m){
	            			if(full.Id == m.IdMotivo){
	            				timeList += '<li>'+m.EstimatedTime+'</li>';
	            			}
						});
	            	}
                    return timeList+'</ul>';
                }
            },
            { "data": "Acciones",
            	"render":function(data, type, full, meta){
            		var newInput = "<input type=\"button\" class=\"btn btn-info change-motivo\" id=\""+full.Id+"\" value=\"Modificar\">";
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