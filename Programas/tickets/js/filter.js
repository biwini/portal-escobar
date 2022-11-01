let filerState;
let filterDateSince, filterDateUntil;
let filterPendiente = '1', filterEnCurso = '2', filterFinalizado = null, filterUrgente;
let filterAtendido, filterEncargado;
let filterMotivo, filterSubMotivo;
let filterTypeEquipo, filterNumEquipo, filterNumPatrimonio, filterBrand, filterModel, filterRetiro;
let filterSecretary, filterDependence, filterState, filterAccess, filterProgram, filterLocalidad;

function showmodal() {
    return new Promise(resolve => {
        $('#loading').modal({backdrop: 'static', keyboard: false})
        setTimeout(() => {
            resolve('resolved');
        }, 250);
    });
}

function hideLoading(){
    return new Promise(resolve => {
        $('#loading').modal('hide');
        resolve('resolved');
    });
}

$('#filter_since').change(function(){
    filterDateSince = $(this).val();

    if(filterDateSince == ''){
        filterDateSince = undefined;
    }

    displayTable();
});

$('#filter_until').change(function(){
    filterDateUntil = $(this).val();

    if(filterDateUntil == ''){
        filterDateUntil = undefined;
    }

    displayTable();
});

$('#filter_atendido').change(function(){
    filterAtendido = $(this).val();

    if(filterAtendido == ''){
        filterAtendido = undefined;
    }
    
    displayTable();
});

$('#filter_encargado').change(function(){
    filterEncargado = $(this).val();

    if(filterEncargado == ''){
        filterEncargado = undefined;
    }

    displayTable();
});

$('#filter_motivo').change(function(){
    filterMotivo = $('#filter_motivo option:selected').text();

    $('#filter_submotivo').find('option:not(:first)').remove();
    $('#filter_submotivo').prop('selectedIndex',0);

    setSubMotivos($(this).val());

    if(filterMotivo == 'MOSTRAR TODOS'){
        filterMotivo = undefined;
        filterSubMotivo = undefined;
    }

    displayTable();
});

$('#filter_submotivo').change(function(){
    filterSubMotivo = $('#filter_submotivo option:selected').text();

    if(filterSubMotivo == ''){
        filterSubMotivo = undefined;
    }

    displayTable();
});

$('#filter_state').change(function(){
    filterState = $(this).val();

    if(filterState == 'MOSTRAR TODOS'){
        filterState = undefined;
    }

    displayTable();
});

$('#filter_tipoequipo').change(function(){
    filterTypeEquipo = $('#filter_tipoequipo option:selected').text();

    if(filterTypeEquipo == 'TODOS'){
        filterTypeEquipo = undefined;
    }

    displayTable();
});

$('#filter_nequipo').keyup(function(){
    filterNumEquipo = $(this).val();

    if(filterNumEquipo == ''){
        filterNumEquipo = undefined;
    }

    displayTable(false);
});

$('#filter_npatrimonio').keyup(function(){
    filterNumPatrimonio = $(this).val();

    if(filterNumPatrimonio == ''){
        filterNumPatrimonio = undefined;
    }

    displayTable(false);
});

$('#filter_marca').keyup(function(){
    filterBrand = $(this).val();

    if(filterBrand == ''){
        filterBrand = undefined;
    }

    displayTable(false);
});

$('#filter_modelo').keyup(function(){
    filterModel = $(this).val();

    if(filterModel == ''){
        filterModel = undefined;
    }

    displayTable(false);
});

$('#filter_access').change(function(){
    filterAccess = $(this).val();

    if(filterAccess == ''){
        filterAccess = undefined;
    }

    displayTable();
});

$('#filter_program').change(function(){
    filterProgram = $(this).val();

    if(filterProgram == ''){
        filterProgram = undefined;
    }

    displayTable();
});

$('#filter_since').change(function(){
    dateSince = $(this).val();

    if(dateSince == ''){
        dateSince = undefined;
    }

    displayTable();
});

$('#filter_until').change(function(){
    dateUntil = $(this).val();

    if(dateUntil == ''){
        dateUntil = undefined;
    }

    displayTable();
});

$('#filter_retirado').change(function(){
    filterRetiro = $(this).val();

    if(!this.checked){
        filterRetiro = undefined;
    }

    displayTable();
});

$('#filter_pendiente').change(function(){
    filterPendiente = $(this).val();

    if(!this.checked){
        filterPendiente = null;
    }

    updateTicketList(filterPendiente, filterEnCurso, filterFinalizado);
});

$('#filter_encurso').change(function(){
    filterEnCurso = $(this).val();

    if(!this.checked){
        filterEnCurso = null;
    }

    updateTicketList(filterPendiente, filterEnCurso, filterFinalizado);
});

$('#filter_finalizado').change(function(){
    filterFinalizado = $(this).val();

    if(!this.checked){
        filterFinalizado = null;
    }

    updateTicketList(filterPendiente, filterEnCurso, filterFinalizado);
});

$('#filter_urgente').change(function(){
    filterUrgente = $(this).val();

    if(!this.checked){
        filterUrgente = undefined;
    }

    displayTable();
});

$('#filter_localidad').change(function(){
    $('#loading').modal({backdrop: 'static', keyboard: false})
    filterLocalidad = $(this).val();

    if(filterLocalidad == ''){
        filterLocalidad = undefined;
    }

    displayTable();
});

$('#filter_secretary').change(function(){
    if(isInPage(document.getElementById('filter_dependence'))){
        setDependences($(this).val(), 'filter');
    }
    if(isInPage(document.getElementById('filter_dependencia'))){
        console.log('hola')
        setDependences($(this).val(), 'filter2');
    }

    filterSecretary = $('#filter_secretary option:selected').text();

    if(filterSecretary == 'TODAS LAS SECRETARIAS'){
        filterSecretary = undefined;
        filterDependence = undefined;
    }

    displayTable();
});

$('#filter_dependence').change(function(){
    filterDependence = $(this).val();

    if(filterDependence == ''){
        filterDependence = undefined;
    }

    displayTable();
});        

$('#secretaria').change(function(){
    if(isInPage(document.getElementById('dependencia'))){
        setDependences($(this).val());
    }
});

function setSubMotivos(motivo){
    if(motivo == ''){
        return false;
    }

    let mot = Motivos.find(m => m.Id == motivo);
    let o;

    mot.SubMotivo.forEach(function(s){
        o = new Option(s.SubMotivo, s.Id);

        $(o).html(s.SubMotivo);

        $("#filter_submotivo").append(o);
    });
}

function setDependences(secretary, filter = undefined){

    if(filter == 'filter'){
        $('#filter_dependence').find('option:not(:first)').remove();
        $('#filter_dependence').prop('selectedIndex',0);

        if(secretary == ''){
            return false;
        }
    }else if(filter == 'filter2'){
        $('#filter_dependencia').find('option:not(:first)').remove();
        $('#filter_dependencia').prop('selectedIndex',0);
        if(secretary == ''){
            return false;
        }
    }else if(filter === undefined){
        $('#dependencia').find('option:not(:first)').remove();
        $('#dependencia').prop('selectedIndex',0);
    }
    
    let Sec = Secretaries.find(s => s.Id == secretary);

    // console.log(Sec.Dependences)
    let o;

    Sec.Dependences.forEach(function(d){
        if(filter == 'filter'){
            o = new Option(d.Dependence, d.Dependence);
        }else if(filter === undefined || filter == 'filter2'){
            o = new Option(d.Dependence, d.Id);
        }
        /// jquerify the DOM object 'o' so we can use the html method
        $(o).html(d.Dependence);

        if(filter == 'filter'){
            $("#filter_dependence").append(o);
        }else if(filter == 'filter2'){
            $("#filter_dependencia").append(o);
        }else if(filter === undefined){
            $("#dependencia").append(o);
        }
    });

    // o = new Option("OTRO", "OTRO");
    // /// jquerify the DOM object 'o' so we can use the html method
    // $(o).html("OTRO");
    // $("#dependencia").append(o);
}

function isInPage(node) {
  return (node === document.body) ? false : document.body.contains(node);
}