$('#secretaria').change(function(){
    setDependences($(this).val());
});

function setDependences(secretary){

    $('#dependencia').find('option:not(:first)').remove();
    $('#dependencia').prop('selectedIndex',0);
    $('#dependencia').change();

    if(secretary == ''){
        return false;
    }
    
    let Sec = Secretaries.find(s => s.Id == secretary);
    let o;

    Sec.Dependences.forEach(function(d){
        o = new Option(d.Dependence, d.Id);
        $(o).html(d.Dependence);

        $("#dependencia").append(o);
    });
}

function getDependenceName(id){
    if(id == null || id == 0 || id == undefined){
        return '';
    }
    let sec = Secretaries.find(function(s){
        dep = s.Dependences.find(d => d.Id == id);
        if(dep != undefined){
            if(dep.Id == id){
                return dep;
            }
        }            
    });

    let dependencia = sec.Dependences.find(d => d.Id == id);

    return dependencia.Dependence;
}

function getSecretaryName(id){
    let sec = Secretaries.find(s => s.Id == id);

    if(sec == undefined){
        return '';
    }

    return sec.Secretary;
}