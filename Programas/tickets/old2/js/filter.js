let filerState;
let filterSecretary, filterDependence, filterState, filterAccess, filterProgram, filterLocalidad, filterTypeEquipo;

function showmodal() {
    return new Promise(resolve => {
        $('#loading').modal({backdrop: 'static', keyboard: false})
        setTimeout(() => {
            resolve('resolved');
        }, 250);
    });
}

$('#filter_state').change(function(){
    filterState = $(this).val();

    if(filterState == ''){
        filterState = undefined;
    }

    displayTable();
});

$('#filter_tipoequipo').change(function(){
    filterTypeEquipo = $(this).val();

    if(filterTypeEquipo == ''){
        filterTypeEquipo = undefined;
    }

    displayTable();
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