let listLicencias;
let DataTable, DataTableHistory;
let xls;
let historyXls;

$(document).ready(start());

async function start(){
    $('.menu .item').tab(); //pesta�as solapas 
    await showLoading();
    
    await cargarEmpleadosCombo(); //carga option select con la tabla de empleados
    listLicencias = await getData();

    xls = new xlsExport(getExcelData(listLicencias), 'Licencias');
    let columns = [
        { "data": "Comienzo",
            "render":function(data, type, full, meta){
                let now = new Date(full.dFechaInicio);
                let date = (now.getUTCDate() < 10) ? '0'+now.getUTCDate() : now.getUTCDate();
                let month = (now.getUTCMonth() < 9) ? '0'+(now.getUTCMonth() + 1) : now.getUTCMonth() + 1; // Since getUTCMonth() returns month from 0-11 not 1-12
                let year = now.getUTCFullYear();
                    
                let dateStr = date + '-' + month + '-' + year;
                return now.getDayName()+' '+dateStr;
            }
        },
        { "data": "Fin",
            "render":function(data, type, full, meta){
                let now = new Date(full.dFechaFin);
                let date = (now.getUTCDate() < 10) ? '0'+now.getUTCDate() : now.getUTCDate();
                let month = (now.getUTCMonth() < 9) ? '0'+(now.getUTCMonth() + 1) : now.getUTCMonth() + 1; // Since getUTCMonth() returns month from 0-11 not 1-12
                let year = now.getUTCFullYear();
                    
                let dateStr = date + '-' + month + '-' + year;

                return now.getDayName()+' '+dateStr;
            }
        },
        { "data": "Dur",
            "render":function(data, type, full, meta){
                let date1 = new Date(full.dFechaInicio);
                let date2 = new Date(full.dFechaFin);
                let diffTime = Math.abs(date2 - date1);
                let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 

                //return diffDays; 
                return diffDays+1; // Cambio hecho por alexis
            }
        },
        { "data": "cMotivo"},
        { "data": "empleado",
            "render":function(data, type, full, meta){
                return '<a href=\'empleados?view='+full.idEmpleado+'\'>'+full.apellido+' '+full.nombre+'<a>';
            }
        },
        { "data": "nroDocumento"},
        { "data": "legajo"},
        { "data": "Notas",
            "render":function(data, type, full, meta){
                return '<div class=\'ui button icon icon-file-text\' style=\'background: center;\' data-inverted data-tooltip=\''+full.cNotas+'\' data-position=\'left center\'>\
                    <span class=\'icon icon-file-text text-center span-notas\' style=\'font-size: 2rem; color: cornflowerblue;\'></span>\
                </div>';

                // return '<div style=\'text-align:center\'><span class=\'icon icon-file-text text-center span-notas\' style=\'font-size: 2rem; color: cornflowerblue;\'></span>\
                //     <div class=\'ui button popup\' data-title=\'Notas\' data-content=\''+full.cNotas+'\' data-position=\'left center\'></div>\
                // </div>';
            }
        },
        { "data": "acciones",
            "render":function(data, type, full, meta){
                return "<button class='f10 mini ui primary basic button editLicencia ' data-id='"+full.idLicencia+"'><i class='icon edit editLicencia'></i>Editar</button>"+
                "<button class='f10 mini ui negative basic button delLicencia' data-id='"+full.idLicencia+"'><i class='icon close'></i>Eliminar</button>";
            }
        },
    ];

    DataTable = $('#grillaLicencias').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": listLicencias,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "order": [],
        "columns":columns,
    });
    columns.pop()
    DataTableHistory = $('#grillaLicenciasHistorial').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": [],
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "order": [],
        "columns":columns,
    });

    $(".searchSelect").dropdown({
        message: {
            noResults:'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true,
        forceSelection: false
    });

    $('.button').popup({
        on: 'click'
    });

    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
	
    $('#form_licencias').form({
        keyboardShortcuts:false,
        fields: {
            motivo: {
                identifier: 'motivo',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'El codigo no debe estar vacio'
                    }
                ]
            },
            empleado: {
                identifier: 'empleado',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir un empleado'
                    }
                ]
            },
            fechainicio: {
                identifier: 'fechainicio',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe ingresar una fecha'
                    }
                ]
            },
            fechafin: {
                identifier: 'fechafin',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe ingresar una fecha'
                    }
                ]
            }
        }
    });
	
    $('#saveNewLicence').click(async function(){
        $('#form_licencias').form('submit');
        await showLoading('Guardando licencia');

        if($('#form_licencias').form('is valid')){
            let formPac = $("#form_licencias");

            values = formPac.form('get values');
            values['page'] = 'Licencias';
            values['action'] = 'send';
            // values['id'] = $("#idEditEmployee").val();

            $.ajax({
                type: "post",
                dataType: "json",
                url:url,
                data:values,
                beforeSend:function(){
                },
                success:function(data){
                    if(data.status != 'error'){
                        swal('Se reistro la licencia','','success');
                        $('form_licencias').form('reset');

                        buscar_registro();
                    }else{
                        swal('No se registro la licencia',data.output,'warning');
                    }
                },
                error:function(data){
                    console.log(data);
                    swal('Ocurrio un error inesperado','','error');
                }
            });
        }
        await hideLoading();
    });

	$('#grillaLicencias').on('click','.delLicencia',function(){
        let ids = $(this).attr("data-id");
        
		swal({
            title: "¡Eliminando Licencia!",
            text: "¿Seguro que desea Eliminar esta licencia?",
            icon: "warning",
            buttons: {
            cancel: "No",
            Si: true,
            },
        })
        .then((willDelete) => {
            if (willDelete) {
                let formData={
                    page:"Licencias",
                    action:"eliminar",
                    id: ids,
                };

                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: url,
                    data: formData,
                    beforeSend:function(){
                    },
                    success:function(data){
                        swal('Se elimino la licencia','','success');
                        buscar_registro();
                    },
                });
            }
		});
    });
    
    $('#buscar_licencia').click(function(){
        DataTable.columns.adjust().draw(true);
    });
	
    // $("#verLicencias").click(async function(){
    //     $("#loading").removeAttr("style");
    //     await buscar_registro();
    //     DataTable.columns.adjust().draw(true);
    //     $("#loading").hide();
    // });
    await hideLoading();
}

$('#grillaLicencias').on('click','.editLicencia',async function(){
    let id = $(this).attr('data-id');

    if(id == null || id == undefined){
        id = $(this).attr('data-id');
    }

    let form = $('#form_licencias');

    let licencia = await getData('getLicencia', id);

    form.form('reset');
    $('#motivo option[value=\''+licencia[0].idMotivo+'\']').prop('selected', true);
    // $('#motivo').val(licencia[0].idMotivo);
    $('#empleado option[value=\''+licencia[0].idEmpleado+'\']').prop('selected', true);

    $('#comentario').val(licencia[0].cNotas);

    $('#fechainicio').val(licencia[0].dFechaInicio);
    $('#fechafin').val(licencia[0].dFechaFin);

    $('#comentario').val(licencia[0].cNotas);

    $('#motivo').change();
    $('#empleado').change();

    $('#idEditLicencia').val(licencia[0].idLicencia);
    $('#saveNewLicence').hide();
    $('#contentEdit').removeAttr('style');
    $('.editLicence').removeAttr('disabled');
    // $('.menu .item').tab("change tab","first");

    $('#view').click();

    $('#div_historial').show();
    let history = await getData('getHistory', licencia[0].idEmpleado);

    DataTableHistory.rows().remove().draw();
    DataTableHistory.rows.add(history);
    DataTableHistory.columns.adjust().draw();

});

$('#cancelEditLicence').click(function(){
    form = $('#form_licencias');
    form.form('reset');
    $('#saveNewLicence').removeAttr('style');
    $('#contentEdit').hide();
    form.attr('class','ui f14 form');
    $('#div_historial').hide();
});

$("#saveEditLicencia").click(function(){

    swal({
        title: "!Modificando Licencia!",
        text: "¿Seguro que desea modificar esta licencia?",
        icon: "warning",
        buttons: {
        cancel: "No",
        Si: true,
        },
    })
    .then( async (willDelete) => {
        if (willDelete) {
            await showLoading('Guardando cambios');

            form = $("#form_licencias");
            form.form("submit");

            if(form.form("is valid")){

                values = form.form('get values');
                values['page'] = 'Licencias';
                values['action'] = 'editLicencia';
                values['id'] = $("#idEditLicencia").val();

                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: url,
                    data:values,
                    beforeSend:function(){
                    },
                    success:function(data){
                        if(data['status'] == 'error'){
                            swal('No se edito la licencia', data['output'], 'warning');
                        }
                        else{
                            form.form("reset");
                            $("#saveNewLicence").removeAttr("style");
                            $("#contentEdit").hide();
                            form.attr("class","ui f14 form");
                            swal('Se edito la licencia','','success');
                            $('form_licencias').form('reset');
                            buscar_registro();
                            $("#menuTab .item").tab("change tab","second");
                            $('#div_historial').hide();
                        }
                    }
                });
            }

            await hideLoading();
        }
    });
});

async function cargarEmpleadosCombo(){
    $.getJSON(url+"?page=Empleados&action=getEmployeesSimple",function(data){
        $.each(data,function(key,value){
            $("#empleado").append("<option value='"+value.id+"'>"+value.apellido+" "+value.nombre+" - "+value.legajo+"</option>");
        }); 
    });
}

async function buscar_registro(){
    listLicencias = await getData();
    DataTable.rows().remove().draw();
    DataTable.rows.add(listLicencias);
    DataTable.columns.adjust().draw();
}

function getData(action = 'getLicencias', id = 0){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'page':'Licencias','action':action,'id': id},
                dataType: "json",
            })
            .fail(function(data){
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                list = data;
            })
        )
    });
}

async function displayTable(modal = true){
    if(modal){
        await showLoading();
        console.log('holasd231')
    }

    console.log('hola')

    let filterTable = listLicencias;

    if(filterSec !== undefined){
        filterTable = filterTable.filter(t => t.idSecretaria == filterSec);
    }

    if(filterDep !== undefined){
        filterTable = filterTable.filter(t => t.idDependencia == filterDep);
    }

    if(filterLegajo !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.legajo != null && t.legajo != ''){
                return t.legajo.includes(filterLegajo);
            }
        });
    }
    if(filterDni !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.nroDocumento != null && t.nroDocumento != ''){
                return t.nroDocumento.includes(filterDni);
            }
        });
    }

    if(filterMotivo !== undefined){
        filterTable = filterTable.filter(t => t.idMotivo == filterMotivo);            
    }

    if(filterDesde !== undefined){
        filterTable = filterTable.filter(t => t.dFechaInicio >= filterDesde || filterDesde <= t.dFechaInicio);            
    }
    
    if(filterHasta !== undefined){
        filterTable = filterTable.filter(t => t.dFechaFin <= filterHasta || filterHasta >= t.dFechaFin);
    }

    xls = new xlsExport(getExcelData(filterTable), 'Licencias');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    await hideLoading();
}

function getExcelData(array){
    let excelData = [];

    array.sort((a, b) => a.apellido.localeCompare(b.apellido))

    array.forEach(function(t){

        data = {
            'Motivo': t.cMotivo,
            'Empleado': t.apellido +' '+ t.nombre,
            'Legajo': t.legajo,
            'DNI': t.nroDocumento,
            'Notas': t.cNotas,
            'Desde': t.dFechaInicio,
            'Hasta': t.dFechaFin,
        }

        excelData.push(data);
    });

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Licencias.xls');
});