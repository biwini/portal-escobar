// Este script solo funciona en este sistema.

// Cada pagina debera tener una funcion llamada 'displayTable()' con los correspondientes filtros y manejo de las tablas correspondientes a la pagina.


let state = (isInPage(document.getElementById('filter_state'))) ? $('#filter_state option:selected').val().toUpperCase() : false;
let orderState = (isInPage(document.getElementById('filter_order_state'))) ? $('#filter_order_state').val() : 'CURRENT';
let dateSince;
let dateUntil;
let filterSecretary;
let filterDependence;
let filterOc;
let filterFuel;
let filterPatente;

$('#filter_state').change(function(e){
    state = $(this).val().toUpperCase();

    if(state == 'EXPIRED'){
        $('#filter_date_custom').show();
    }else{
        $('#filter_date_custom').hide();
        $('#filter_date_since').val('');
        $('#filter_date_until').val('');
    }

    displayTable();
});

$('#filter_date_since').change(function(e){
    dateSince = $(this).val();

    if(dateSince == ''){
        dateSince = undefined;
    }

    displayTable();
});

$('#filter_date_until').change(function(e){
    dateUntil = $(this).val();

    if(dateUntil == ''){
        dateUntil = undefined;
    }

    displayTable();
});

$('#filter_fuel').change(function(e){
    filterFuel = $(this).val();

    if(filterFuel == ''){
        filterFuel = undefined;
    }

    displayTable();
});

$('#filter_order_state').change(function(e){
    orderState = $(this).val().toUpperCase();

    displayTable();
});

$('#filter_car_patente').keyup(function(e){
    filterPatente = $(this).val().toUpperCase();

    if(filterPatente == ''){
        filterPatente = undefined;
    }

    displayTable();
});

$('#filter_oc').keyup(function(e){
    filterOc = $(this).val();

    if(filterOc == ''){
        filterOc = undefined;
    }

    displayTable();
});

$('#filter_secretary').change(function(){
    setDependences($(this).val(), 'filter');

    filterSecretary = $('#filter_secretary option:selected').text();

    if(filterSecretary == 'TODAS LAS SECRETARIAS'){
        filterSecretary = undefined;
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
    setDependences($(this).val());
});

function setDependences(secretary, filter = undefined){

    if(filter !== undefined){
        $('#filter_dependence').find('option:not(:first)').remove();
        $('#filter_dependence').prop('selectedIndex',0);

        if(secretary == ''){
            return false;
        }
    }else{
        $('#dependencia').find('option:not(:first)').remove();
        $('#dependencia').prop('selectedIndex',0);
    }
    
    let Sec = Secretaries.find(s => s.Id == secretary);

    // console.log(Sec.Dependences)
    let o;

    Sec.Dependences.forEach(function(d){
        if(filter !== undefined){
            o = new Option(d.Dependence, d.Dependence);
        }else{
            o = new Option(d.Dependence, d.Id);
        }
        /// jquerify the DOM object 'o' so we can use the html method
        $(o).html(d.Dependence);

        if(filter !== undefined){
            $("#filter_dependence").append(o);
        }else{
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