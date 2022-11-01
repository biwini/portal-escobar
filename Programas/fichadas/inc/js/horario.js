var isEdit = false;
let DataTable;
let listHorarios = [];
let dias = ['domingo','lunes','martes','miercoles','jueves','viernes','sabado'];
let xls;

$(document).ready(start());

async function start(){
    $("#loading").removeAttr("style");
    listHorarios = await getData('todos');
    console.log(listHorarios);

    xls = new xlsExport(getExcelData(listHorarios), 'Horarios');

    DataTable = $('#lista_horarios').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": listHorarios,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columns":[
            { "data": "nombre_horario"},
            { "data": "c_descripcion"},
            { "data": "d_tolerancia_entrada" },
            { "data": "d_tolerancia_salida" },
            { "data": "n_trabaja_feriados",
                "render":function(data, type, full, meta){
                    return (full.n_trabaja_feriados == 1) ? 'SI' : '';
                }
            },
            { "data": "acciones",
                "render":function(data, type, full, meta){
                    return "<button class='f10 mini ui primary basic button edit' data-id='"+full.id_horario+"' value=\""+full.id_horario+"\"><i class='icon edit edit'></i>Editar</button>";
                    // '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.idModalidad+'\'></button> ';
                }
            },
        ],
    });

    $("#loading").hide();

    $('#form_horario').form({
        keyboardShortcuts:false,
        fields: {
            horario: {
                identifier: 'horario',
                rules: [{
                    type   : 'empty',
                    prompt : 'Debe completar el nombre del horario'
                }]
            },
        }
    });

    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true
    });

    $(document).on('click','.edit', async function(e){ //aca es cuando le dan doble click a tabla_horarios, para mostrar el registro

        let id = $(this).val();

        let horario = await getData('buscar', id);

        $("#horario").val(horario.nombre_horario);
        $("#descrip").val(horario.c_descripcion);
        $("#tol1").val(horario.d_tolerancia_entrada);
        $("#tol2").val(horario.d_tolerancia_salida);
        $('#feriado option[value=\''+horario.n_trabaja_feriados+'\']').attr('selected', true);
        $("#feriado_desde").val(horario.d_feriado_desde);
        $("#feriado_hasta").val(horario.d_feriado_hasta);

        horario.dias.forEach(function(e, k){
            console.log(e)
            console.log($('#'+dias[e.idDiaSemana]+'_out').val(), e.tSalida.substring(0,5))
            $('#'+dias[e.idDiaSemana]+'_in').val(e.tEntrada.substring(0,5));
            $('#'+dias[e.idDiaSemana]+'_out').val(e.tSalida.substring(0,5)).change();
            $('#'+dias[e.idDiaSemana]+'_almin').val((e.tEntradaAnticipada == null) ? '' : e.tEntradaAnticipada.substring(0,5));
            $('#'+dias[e.idDiaSemana]+'_alout').val((e.tSalidaAdelantada == null) ? '' : e.tSalidaAdelantada.substring(0,5));

            if(e.nActivo == '0'){
                $('#'+dias[e.idDiaSemana]+'_active').prop('checked', false);
            }else{
                $('#'+dias[e.idDiaSemana]+'_active').prop('checked', true);
            }
        });

        $("#idEditHorario").val(id);
        $("#save_all").hide();
        $("#contentEdit").removeAttr("style");
        $("#menuTab .item").tab("change tab","newHorario");

        isEdit = true;
    });

    $('.menu .item').tab();
    $('.menu .item').tab({
        'onVisible':function(){isEdit = false;DataTable.draw();}
    });

    $("#limpiar_horario").click(function(e){
    	$("#table_horarios").children().find('input').val('');
        $('input').reset();
    });

    $("#cancel_edit_horario").click(function(){
        $("#form_horario").form("reset");
        // $("#table_horarios").children().find('input').reset();
        // $('input').reset();
        $("#save_all").removeAttr("style");
        $("#contentEdit").hide();
        isEdit = false;
    });

    $('#save_all, #save_edit_horario').click(function(){
        $("#form_horario").form("submit");
        if($("#form_horario").form("is valid")){
            $("#loading").removeAttr("style");

            let formPac=$("#form_horario");

            let horarios = [];
            // let dias = [0,1,2,3,4,5,6];
            let editId = (isEdit) ? $('#idEditHorario').val() : 0;

            dias.forEach(function(el,i){
                console.log(el)
                horarios.push({
                    dia: i,
                    entrada: $('#'+el+'_in').val(),
                    salida: $('#'+el+'_out').val(),
                    antes: $('#'+el+'_almin').val(),
                    despues: $('#'+el+'_almout').val(),
                    activo: ($('#'+el+'_active').is(':checked')) ? 1 : 0,
                });
            });

            let haveActive = horarios.find(h => h.activo == 1);

            if(haveActive === undefined){
                alert('Debe cargar minimo un horario');
                return false;
            }
            console.log(horarios)
            // return false;
            values = formPac.form('get values');
            values['page'] = 'Horario';
            values['action'] = (isEdit) ? 'actualizar' : 'insertar';
            values['id'] = editId;
            values['listhorarios'] = horarios

            $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                data:values,
                beforeSend:function(){
                },
                success: function(data){                    
                    if(data.status == 'ok'){
                        if(isEdit){
                            swal('Se modifico el horario','','success');
                        }else{
                            swal('Se creo el horario','','success');
                        }
                        $(':input[type!="time"]').val('');
                        actualizarDatos();
                        $('.menu .item').tab('change tab','tabla_horarios');
                        $('#cancel_edit_horario').click();
                    }else{
                        swal('Error', data.output,'warning');
                    }
                },
                error:function(data){
                    $("#loading").hide();
                }
            });
            $("#loading").hide();
        }
    });
}

$('.checkbox').click(function(e) {
    console.log($(this).parents('tr').children().find(':input[type="time"]'))
    if(this.checked){
        $(this).parents('tr').removeClass('tr-disabled');
        $(this).parents('tr').children().find(':input[type="time"]').removeAttr('disabled', 'false');
    }else{
        $(this).parents('tr').addClass('tr-disabled');
        $(this).parents('tr').children().find(':input[type="time"]').attr('disabled', 'true');
    }
});

$('.calc-hours').change(function(e){
    switch (this.id) {
        case 'lunes_in':
        case 'lunes_out':
            $('#lunes_calc').html(calculateTime($('#lunes_in').val(), $('#lunes_out').val()));
        break;
        case 'martes_in':
        case 'martes_out':
            $('#martes_calc').html(calculateTime($('#martes_in').val(), $('#martes_out').val()));
        break;
        case 'miercoles_in':
        case 'miercoles_out':
            $('#miercoles_calc').html(calculateTime($('#miercoles_in').val(), $('#miercoles_out').val()));
        break;
        case 'jueves_in':
        case 'jueves_out':
            $('#jueves_calc').html(calculateTime($('#jueves_in').val(), $('#jueves_out').val()));
        break;
        case 'viernes_in':
        case 'viernes_out':
            $('#viernes_calc').html(calculateTime($('#viernes_in').val(), $('#viernes_out').val()));
        break;
        case 'sabado_in':
        case 'sabado_out':
            $('#sabado_calc').html(calculateTime($('#sabado_in').val(), $('#sabado_out').val()));
        break;
        case 'domingo_in':
        case 'domingo_out':
            $('#domingo_calc').html(calculateTime($('#domingo_in').val(), $('#domingo_out').val()));
        break;
    }
});

function calculateTime(start, end) {     
    let timeStart = new Date("01/01/2021 " + start).getHours();
    let timeEnd = new Date("01/01/2021 " + end).getHours();
    
    let hourDiff = timeEnd - timeStart;

    if (hourDiff < 0) {
        hourDiff = 24 + hourDiff;
    }

    return (hourDiff >= 10) ? hourDiff+':00hs' : '0'+hourDiff+':00hs';
}

async function actualizarDatos(){
    listHorarios = await getData('todos');                        
    DataTable.rows().remove().draw();
    DataTable.rows.add(listHorarios);
    DataTable.columns.adjust().draw();
}

async function displayTable(modal = true){
    if(modal){
        await showLoading();
    }

    let filterTable = listHorarios;

    if(filterHorario !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.nombre_horario != null && t.nombre_horario != ''){
                return t.nombre_horario.toUpperCase().includes(filterHorario.toUpperCase());
            }
        });
    }

    if(filterFeriado !== undefined){
        filterTable = filterTable.filter(t => t.n_trabaja_feriados == filterFeriado);
    }

    xls = new xlsExport(getExcelData(filterTable), 'Horarios');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    await hideLoading();
}

function getExcelData(array){
    let excelData = [];

    array.sort((a, b) => a.nombre_horario.localeCompare(b.nombre_horario))

    array.forEach(function(t){
        data = {
            'Nombre Horario': t.nombre_horario,
            'Descripcion': t.c_descripcion,
            'Tolerancia Entrada': t.d_tolerancia_entrada,
            'Tolerancia Salida': t.d_tolerancia_salida,
            'Trabaja Feriados': (t.n_trabaja_feriados == 1) ? 'SI' : '',
        }

        excelData.push(data);
    });

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Horarios.xls');
});

function getData(action, id = 0){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'page':'Horario','action':action, 'id': id},
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

function getFormVal(form,id){
    return form.form("get field",id).val();
}
