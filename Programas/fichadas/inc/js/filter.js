let filterLegajo, filterDni, filterDesde, filterHasta, filterMotivo, filterNombre, filterTipoEmpleado;
let filterReloj, filterIp;
let filterHorario, filterFeriado;
let filterSec, filterDep;

$('#filter_horario').keyup(function(e){
    filterHorario = $(this).val();

    if(filterHorario == ''){
        filterHorario = undefined;
    }

    displayTable();
});

$('#filter_reloj').keyup(function(e){
    filterReloj = $(this).val();

    if(filterReloj == ''){
        filterReloj = undefined;
    }

    displayTable();
});

$('#filter_ip').keyup(function(e){
    filterIp = $(this).val();

    if(filterIp == ''){
        filterIp = undefined;
    }

    displayTable();
});

$('#filter_legajo').keyup(function(e){
    filterLegajo = $(this).val();

    if(filterLegajo == ''){
        filterLegajo = undefined;
    }

    displayTable();
});

$('#filter_empleado').keyup(function(e){
    filterNombre = $(this).val();

    if(filterNombre == ''){
        filterNombre = undefined;
    }

    displayTable();
});

$('#filter_dni').keyup(function(e){
    filterDni = $(this).val();

    if(filterDni == ''){
        filterDni = undefined;
    }

    displayTable();
});

$('#filter_desde').change(function(e){
    filterDesde = $(this).val();

    if(filterDesde == ''){
        filterDesde = undefined;
    }

    displayTable();
});

$('#filter_hasta').change(function(e){
    filterHasta = $(this).val();

    if(filterHasta == ''){
        filterHasta = undefined;
    }

    displayTable();
});

$('#filter_motivo').change(function(e){
    filterMotivo = $(this).val();

    if(filterMotivo == ''){
        filterMotivo = undefined;
    }

    displayTable();
});

$('#filter_tipoempleado').change(function(e){
    filterTipoEmpleado = $('#filter_tipoempleado option:selected').val();

    if(filterTipoEmpleado == ''){
        filterTipoEmpleado = undefined;
    }

    displayTable();
});

$('#filter_feriado').change(function(){
    filterFeriado = $('#filter_feriado option:selected').val();

    if(filterFeriado == 'TODO'){
        filterFeriado = undefined;
    }

    displayTable();
}); 

$('#filter_secretary').change(function(){
    filterSec = $('#filter_secretary option:selected').val();
    setFilterDependences(filterSec);

    if(filterSec == '' || filterSec == 0 || filterSec == undefined){
        filterSec = undefined;
        filterDep = undefined;
    }

    displayTable();
});

$('#filter_dependence').change(function(){
    filterDep = $('#filter_dependence option:selected').val();

    if(filterDep == '' || filterDep == 0){
        filterDep = undefined;
    }

    displayTable();
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

function setFilterDependences(secretary){
    $('#filter_dependence').find('option:not(:first)').remove();
    $('#filter_dependence').prop('selectedIndex',0);
    $('#filter_dependence').change();
    // $('#filter_dependence').dropdown('set selected', '');

    if(secretary == '' || secretary == 0 || secretary == undefined){
        return false;
    }
    
    let Sec = Secretaries.find(s => s.Id == secretary);
    let o;

    Sec.Dependences.forEach(function(d){
        o = new Option(d.Dependence, d.Id);
        $(o).html(d.Dependence);

        $("#filter_dependence").append(o);
    });
}