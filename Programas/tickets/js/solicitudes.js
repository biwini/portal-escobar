let DataTable;
let xls = new xlsExport(getExcelData(ticketList), 'Tickets');
let filterTable;
var myDropzone;
Dropzone.autoDiscover = false;

$(document).ready(function(){
    drop = $("div#dropzone").dropzone({     
        autoProcessQueue: false,
        url: url,
        params: {
                pag: "Ticket",
                tipo: "ar",
        },
        paramName: 'file',
        clickable: true,
        maxFilesize: 20,
        uploadMultiple: true, 
        maxFiles: 6,
        addRemoveLinks: true,
        acceptedFiles: '.png,.jpg,.pdf,.doc,.txt,.xlsx',
        dictDefaultMessage: 'Da click aquí o arrastra tus archivos y sueltalos aqui.',
        init: function () {
            myDropzone = this;
            // Update selector to match your button
            this.on('sending', function(file, xhr, formData) {
                // Append all form inputs to the formData Dropzone will POST
                formData.append('ticket', idselectedTicket);
                var data = $('#dropzone').serializeArray();
                $.each(data, function(key, el) {
                    FP.append(el.name, el.value);
                });
            });
            this.on("success", function(file, responseText) {
                myDropzone.removeFile(file);
                console.log(responseText)
            });
            this.on("error", function(file, responseText) {
                swal('No se cargaron los archivos','error')
            });
        }
    });

    $('#tipo_disco_ingreso').change(function(){
        let tipo = $(this).val();

        setDiscCapacity(tipo);
    });

    $('#t_ticket thead th').each( function () {
        let title = $(this).text();
        if(title != 'ENCARGADO' && title != 'ESTADO' && title != 'FECHA'){
            $(this).html(title+ '<div style="width:100%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
        }
    });

    DataTable = $('#t_ticket').DataTable({
        "data": ticketList,
        "rowId": 'IdTicket',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "bSort" : false,
        "createdRow": function( row, data, dataIndex){
            switch(data.Estado){
                case '1': $(row).children().eq(0).addClass('pendiente'); break;
                case '2': $(row).children().eq(0).addClass('en-proceso'); break;
                case '3': $(row).children().eq(0).addClass('finalizado'); break;
            }
            if(data.Prioridad == 1 && data.Estado != '3'){
                $(row).children().eq(0).addClass('urgente');
            }
        },
        "columns":[
            { "data": "IdTicket",
                "render":function(data, type, full, meta){
                    return '<div style=\'width:100%;height:100%;text-align:center;font-size:20px;\'><a style="color: black;" href=\'descargar-ticket?ticket='+full.Codigo+'\' target=\'_blank\' rel=\'noreferrer\'> '+full.Codigo+' </a></div>';
                }
            },
            { "data": "Dependencia",
                "render":function(data, type, full, meta){
                    let dependence = '';
                    let textoTroceado = full.Dependencia.split (" ");
                    for (var i = 1; i <= full.Dependencia.split(' ').length; i++) {
                        dependence += (i % 2 == 0) ? textoTroceado[i - 1]+'<br>' : textoTroceado[i - 1]+' ';
                    }
                    return dependence;
                }
            },
            { "data": "Usuario",
                "render":function(data, type, full, meta){
                    let usuario = '';
                    let textoTroceado = full.Usuario.split(' ');
                    for (var i = 1; i <= full.Usuario.split(' ').length; i++) {
                        usuario += (i % 2 == 0) ? textoTroceado[i - 1]+'<br>' : textoTroceado[i - 1]+' ';
                    }
                    return usuario;
                }
            },
            { "data": "Telefono"},
            { "data": "Fecha_Alta"},
            { "data": "Motivo",
                "render":function(data, type, full, meta){
                    motivo = full.Motivo.split("/").join("/<br>");
                    if(full.Equipo.Intern != null){
                        motivo += '-'+full.Equipo.Intern
                    }
                    return motivo;
                }
            },
            { "data": "Encargado",
                "render":function(data, type, full, meta){
                    let tecnicos = '';
                    if(full.IdEncargado == null){
                        tecnicos += '<option value=\'0\'>SIN ASIGNAR</option>';
                    }

                    let disabled = (full.Estado == 3) ? 'disabled' : '';
                    $.each(ListTecnico, function(i,t){
                        selected = (t.Id == full.IdEncargado && full.IdEncargado != null) ? 'selected' : '';
                        tecnicos += '<option value=\''+t.Id+'\' '+selected+'>'+t.Name+'</option>';
                    });
                    let newSelect = '<select id=\'tecnico_'+full.IdTicket+'\'  class=\"form-select tecnico pointer btn btn-default\" '+disabled+'>'+tecnicos+'</select>';

                    return newSelect;
                }
            },
            { "data": "Estado",
                "render":function(data, type, full, meta){
                    let e1 = '',e2 = '',e3 = '';
                    switch(full.Estado){
                        case 1: e1 = 'selected'; break;
                        case 2: e2 = 'selected'; break;
                        case 3: e3 = 'selected'; break;
                    }
                    let disabled = (full.Estado == 3) ? 'disabled' : '';
                    let estado = '';
                    if(full.Estado == 1){
                        estado += '<option value=\'1\' '+e1+'>PENDIENTE</option>';
                    }
                    if(full.Estado <= 2){
                        estado += '<option value=\'2\' '+e2+'>EN CURSO</option>';
                    }
                    estado += '<option value=\'3\' '+e3+'>FINALIZADO</option>';
                    let newSelect = '<select id=\'estado_'+full.IdTicket+'\'  class=\"btn btn-default estado pointer\" '+disabled+'>'+estado+'</select>';

                    return newSelect;
                }
            },
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                let that = this;
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search(this.value).draw();
                    }
                });
            });
        }
    });
});

$('#title_filter').click(function(){
    if($('#row_filter').hasClass('show')){
        $('#title_filter').children('.icon').removeClass('icon-circle-up');
        $('#title_filter').children('.icon').addClass('icon-circle-down');
        $('#row_filter').removeClass('show');
        $('#row_filter').addClass('hide');
    }else{
        $('#title_filter').children('.icon').removeClass('icon-circle-down');
        $('#title_filter').children('.icon').addClass('icon-circle-up');
        $('#row_filter').removeClass('hide');
        $('#row_filter').addClass('show');
    }
});
$('#actualizar').click(function(){
    updateTicketList(filterPendiente, filterEnCurso, filterFinalizado);
});

function setDiscCapacity(tipo){
    $('#cantidad_disco_ingreso').find('option:not(:first)').remove();
    $('#cantidad_disco_ingreso').prop('selectedIndex',0);

    if(tipo == '' || tipo == undefined){
        return false;
    }

    let disc = Discos.find(d => d.Id == tipo);
    let o;

    disc.Discs.forEach(function(d){
        let tamaño = parseInt(d.nCapacidad);
        tamaño = (tamaño >= 1000) ? d.nCapacidad.substring(0,1)+' TB' : d.nCapacidad+' GB';

        o = new Option(tamaño, d.id);
        $(o).html(tamaño);

        $("#cantidad_disco_ingreso").append(o);
    });
}

$(document).on('submit', '#form_participante', function(e){
    e.preventDefault();
    let participantes = '';
    $('#form_participante input[type=checkbox]').each(function(){
        if(this.checked) {
            participantes += $(this).val()+',';
        }
    });
    if(participantes != '' && idselectedTicket != 0){
        $.ajax({
            type: "POST",
            url: "controller/",
            data: "pag="+document.title+"&tipo=p&ticket="+idselectedTicket+'&participante='+participantes,
            dataType: "html",
        })
        .fail(function(data){
            mensaje('fail','Error Peticion ajax');
        })
        .done(function(data){
            response = JSON.parse(data);
            switch(response.Status){
                case 'Success':
                    mensaje('okey','Se agregaron participantes');
                    $('#form_participante')[0].reset();
                    $('#modal_participante').modal('hide');
                    ticketList.forEach(function(t){
                        if(t.IdTicket == idselectedTicket){
                            t.Participantes = response.Participantes;
                        }
                    });
                break;
                case 'Error':
                    mensaje('fail','No se pudo agregar participantes');
                break;
            }
        });
    }
});

$(document).on('submit', '#form_details', function(e){
    e.preventDefault();
    if(idselectedTicket != 0){
        $.ajax({
            type: "POST",
            url: "controller/",
            data:  $(this).serialize()+"&pag="+document.title+"&tipo=ud&ticket="+idselectedTicket,
            dataType: "json",
        })
        .fail(function(data){
            mensaje('fail','Error Peticion ajax');
        })
        .done(function(data){
            
            switch(data.Status){
                case 'Success':
                    mensaje('okey','Se guardaron los cambios');
                    
                    $('#form_details')[0].reset();
                    $('#modal_detail').modal('hide');
                    ticketList.forEach(function(t, k){
                        if(t.IdTicket == idselectedTicket){
                            ticketList[k] = data.Ticket[0];
                        }
                    });
                    $('#dropzone')[0].dropzone.processQueue();
                break;
                case 'Error':
                    mensaje('fail','No se pudo guardar los cambios');
                break;
            }
        });
    }
});

$('#actualizar_equipo').click(function(e){
    $('#modal_detail').modal('hide');
    showFormEquipo(idselectedTicket);
});

$(document).on('submit', '#form_equipo', function(e){
    e.preventDefault();
    
    let found = ticketList.find(t => t.IdTicket == idselectedTicket);
    console.log(found)
    $.ajax({
        type: "POST",
        url: "controller/",
        data:  $(this).serialize()+"&pag=Equipo"+"&tipo=ue&user="+found.Legajo+"&interno_ingreso="+found.Equipo.Intern+"&equipo_ingreso="+found.Equipo.Type,
        dataType: "json",
    })
    .fail(function(data){
        mensaje('fail','Error Peticion ajax');
    })
    .done(function(data){
        switch(data.Status){
            case 'Success':
                mensaje('okey','Se guardaron los cambios del equipo');
                $('#form_equipo')[0].reset();
                $('#modal_equipo').modal('hide');
                ticketList.forEach(function(t, k){
                    if(t.IdTicket == idselectedTicket){
                        ticketList[k].Equipo = data.Equipo[0];
                        ticketList[k].EquipoComplete = true;
                    }
                });

                setTicket(idselectedTicket);
            break;
            case 'Error':
                mensaje('fail','No se pudo guardar los cambios');
            break;
        }
    });
});

$(document).on('click', '.agregar_tecnico', function(e){    
    $.each(ticketList, function(i,t){
        if(idselectedTicket == t.IdTicket){
            $.each(t.Participantes, function(k,p){
                $("#participante_"+p.Id).prop("checked",true);
            });
        }
    });
    $('#modal_detail').modal('hide');
    $('#modal_participante').modal('show');
});

function showFormEquipo(ticket){
    let found = ticketList.find(t => t.IdTicket == ticket);
    $('#form_equipo')[0].reset();

    $('#interno_ingreso').val(found.Equipo.Intern);
    $('#patrimonio_ingreso').val(found.Equipo.Patrimony);
    $('#marca_ingreso').val(found.Equipo.Brand);
    $('#modelo_ingreso').val(found.Equipo.Model);

    $('#equipo_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.Type;
    }).prop('selected', true);
    
    $('#mother_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.IdMother;
    }).prop('selected', true);

    $('#procesador_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.IdProcesador;
    }).prop('selected', true);

    $('#so_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.IdSo;
    }).prop('selected', true);

    $('#bits_so_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.BitsSo;
    }).prop('selected', true);

    $('#ram_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.Ram;
    }).prop('selected', true);

    $('#tipo_disco_ingreso option').filter(function() {
        return $(this).val() == found.Equipo.IdTypeDisc;
    }).prop('selected', true);

    setDiscCapacity(found.Equipo.IdTypeDisc);

    let capacidad = found.Equipo.DiscCapacity;                 
    capacidad = (capacidad >= 1000) ? capacidad.substring(0,1)+' TB' : capacidad+' GB';

    console.log(found.Equipo)

    $('#cantidad_disco_ingreso option').filter(function() {
        return $(this).text() == capacidad;
    }).prop('selected', true);

    $('#modal_equipo').modal('show');
}

async function updateTicketList(pendiente, curso, finalizado){
    await showmodal();

    ticketList = await getTickets(pendiente, curso, finalizado);

    displayTable();
}

function getTickets(pendiente, curso, finalizado){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'pag':document.title,'tipo':'g','pendiente':pendiente,'curso':curso, 'finalizado': finalizado},
                dataType: "json",
            })
            .fail(function(data){
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                list = data;
            })
        )
        //resolve(suggestions);
    });
}

function getHistoryOf(type, id){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'pag':'Solicitudes','tipo':type,'id': id},
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
        await showmodal(); 
    }

    filterTable = ticketList;

    if(filterUrgente !== undefined){
        filterTable = filterTable.filter(t => t.Prioridad == 1);
    }

    if(filterSecretary !== undefined){
        filterTable = filterTable.filter(t => t.Secretaria == filterSecretary);
    }
    if(filterDependence !== undefined){
        filterTable = filterTable.filter(t => t.Dependencia == filterDependence);
    }

    if(filterDateSince !== undefined){
        if(filterPendiente == null, filterEnCurso == null, filterFinalizado != null){
            filterTable = filterTable.filter(t => t.Fecha_Finalizado >= filterDateSince || filterDateSince <= t.Fecha_Finalizado);
        }else{
            filterTable = filterTable.filter(t => t.Fecha_Alta >= filterDateSince || filterDateSince <= t.Fecha_Alta);
        }                
    }
    if(filterDateUntil !== undefined){
        if(filterPendiente == null, filterEnCurso == null, filterFinalizado != null){
            filterTable = filterTable.filter(t => t.Fecha_Finalizado <= filterDateUntil+' 23:59:59' || filterDateUntil+' 23:59:59' >= t.Fecha_Finalizado);
        }else{
            filterTable = filterTable.filter(t => t.Fecha_Alta <= filterDateUntil+' 23:59:59' || filterDateUntil+' 23:59:59' >= t.Fecha_Alta);
        }
    }

    if(filterAtendido !== undefined){
        filterTable = filterTable.filter(t => t.IdAlta == filterAtendido);
    }
    if(filterEncargado !== undefined){
        if(filterEncargado == 'SIN_ASIGNAR'){
            filterTable = filterTable.filter(t => t.IdEncargado == null);
        }else{
            filterTable = filterTable.filter(t => t.IdEncargado == filterEncargado);
        }
    }

    if(filterMotivo !== undefined){
        filterTable = filterTable.filter(t => t.Motivo.split('/')[0] == filterMotivo);
    }
    if(filterSubMotivo !== undefined){
        filterTable = filterTable.filter(t => t.Motivo.split('/')[1] == filterSubMotivo);
    }

    if(filterTypeEquipo !== undefined){
        filterTable = filterTable.filter(t => t.Equipo.TypeName == filterTypeEquipo);
    }
    if(filterNumEquipo !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.Equipo.Intern != null && t.Equipo.Intern != ''){
                return t.Equipo.Intern.includes(filterNumEquipo);
            }
        });
    }
    if(filterNumPatrimonio !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.Equipo.Patrimony != null && t.Equipo.Patrimony != ''){
                return t.Equipo.Patrimony.includes(filterNumPatrimonio);
            }
        });
    }
    if(filterBrand !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.Equipo.Brand != null && t.Equipo.Brand != ''){
                return t.Equipo.Brand.includes(filterBrand);
            }
        });
    }
    if(filterModel !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.Equipo.Model != null && t.Equipo.Model != ''){
                return t.Equipo.Model.includes(filterModel);
            }
        });
    }

    if(filterRetiro !== undefined){
        filterTable = filterTable.filter(t => t.ListoParaRetiro != null);
    }

    console.log(filterTable)

    xls = new xlsExport(getExcelData(filterTable), 'Tickets');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    $('#loading').modal('hide');
}

function getExcelData(array){
    let excelData = [];

    array.forEach(function(t){
        estado = '';
        switch(t.Estado){
            case '1': estado = 'PENDIENTE'; break;
            case '2': estado = 'EN CURSO'; break;
            case '3': estado = 'FINALIZADO'; break;
        }

        data = {
            'Ticket': t.Codigo,
            'Secretaria': t.Secretaria,
            'Dependencia': t.Dependencia,
            'Motivo': t.Motivo,
            'Legajo': t.Legajo,
            'Usuario': t.UserName,
            'Mail': t.Email,
            'Telefono': t.Telefono,
            'Fecha': t.SimpleDate,
            'Atendido por': t.Creador,
            'Encargado': t.Encargado,
            'Estado': estado
        }

        excelData.push(data);
    });

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Tickets.xls');
});