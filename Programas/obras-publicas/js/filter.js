//------------------------------- FILTROS -------------------------------------------//
let filterDesde, filterHasta, filterObra, filterExpt, filterExptAnio, filterEstado, filterEjecutora, filterImputado, filterProveedor;

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

$('#filter_obra').keyup(function(e){
    filterObra = $(this).val();

    if(filterObra == ''){
        filterObra = undefined;
    }
    
    displayTable();
});

$('#filter_expt').keyup(function(e){
    filterExpt = $(this).val();

    if(filterExpt == ''){
        filterExpt = undefined;
    }
    
    displayTable();
});

$('#filter_expt_anio').change(function(e){
    filterExptAnio = $(this).val();

    if(filterExptAnio == ''){
        filterExptAnio = undefined;
    }
    
    displayTable();
});

$('#filter_estado').change(function(e){
    filterEstado = $(this).val();

    if(filterEstado == ''){
        filterEstado = undefined;
    }
    
    displayTable();
});

$('#filter_ejecutora').change(function(e){
    filterEjecutora = $(this).val();

    if(filterEjecutora == ''){
        filterEjecutora = undefined;
    }
    
    displayTable();
});

$('#filter_imputado').change(function(e){
    filterImputado = $(this).val();

    if(filterImputado == ''){
        filterImputado = undefined;
    }
    
    displayTable();
});

$('#filter_proveedor').change(function(e){
    filterProveedor = $(this).val();

    if(filterProveedor == ''){
        filterProveedor = undefined;
    }
    
    displayTable();
});

function isInPage(node) {
  return (node === document.body) ? false : document.body.contains(node);
}