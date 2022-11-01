const page = 'Reporte';
let test = {1: null, Apellido: '', Nombre: '',Legajo: '',nameSector: '',nameDependency: ''};
$(document).ready(start);

function start(){
    $(".searchSelect").dropdown({
        message: {
            noResults:'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true,
        forceSelection: false
    });
    $("#typeReport").dropdown({
        direction: 'upward'
      });
    t=$('#listEmployee').DataTable({
        "language": {
            "url": "src/DataTables/Spanish.json"
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable": false,
            "data": test,
            "defaultContent": "<input type='checkbox' class='checkbox'>",
            "className":"dt-body-center"
        }],
        "columns":[
            { },
            { "data": "nombre",
                "render":function(data, type, full, meta){
                    return full.apellido+' '+full.nombre
                }
            },
            { "data": "nroDocumento"},
            { "data": "legajo"},
            { "data": "nombreSecretaria"},
            { "data": "nombreDependencia"},
            { "data": "nombreTipoEmpleado"}
        ],
        "order": [[ 1, "asc" ]],
        "select":{
            "style":'multi'
        }
    });
    $("#listEmployee tbody").on("click","tr",function(){
        $(this).toggleClass('selected');
    });
    t.on( 'select', function ( e, dt, type, indexes ) {
        if ( type === 'row' ) {
            c=t.rows( { selected: true } ).count();
            $("#cantEmployeeSelected").html(c);
            if(c>0){
                $('#msjCreateReport').removeAttr('style');
                
            }
            tr=t.rows( indexes ).nodes().to$();
            tr.find("input").prop("checked",true);
        }
    } );
    t.on( 'deselect', function ( e, dt, type, indexes ) {

        if ( type === 'row' ) {
            c=t.rows( { selected: true } ).count();
            $("#cantEmployeeSelected").html(c);
            if(c<1){
                $('#msjCreateReport').hide();
            }
            tr=t.rows( indexes ).nodes().to$();
            tr.find("input").prop("checked",false);
        }
    } );
    $("#masterCheckbox").change(function(){
        if($(this).is(':checked')){
            t.rows().select();
        }
        else{
            t.rows().deselect();
        }
    });
    $("#fromDate").val(dayjs().format("YYYY-MM-01"));
    $("#toDate").val(dayjs().format("YYYY-MM")+"-"+dayjs().daysInMonth());
    getSecretary();
    getDependence();
    $("#fromDate").change(function(){
        fromDate=dayjs($(this).val());
        $("#toDate").val(fromDate.format("YYYY-MM")+"-"+fromDate.daysInMonth());
    });
    $("#searchReport").click(function(){
        $('#msjCreateReport').hide();
        $("#masterCheckbox").prop("checked",false);
        t.rows().remove().draw();
        search={
            idSecretary:$("#secretary").val(),
            idDependence:$("#dependence").val(),
            idEmployee:$("#employee").val(),
            name:$("#searchName").val(),
            dni:$("#searchDni").val(),
            legajo:$("#searchLegajo").val()
            // fromDate:$("#fromDate").val(),
            // toDate:$("#toDate").val()
        };
        console.log(search)
        $.ajax({
            type: "POST",
            url: url,
            data: {page:page,action:'getEmployee',search:search},
            beforeSend: function(){
                $("#loading").removeAttr("style");
            },
            success: function(data){
                $("#loading").hide();
                t.rows().remove().draw();
                t.rows.add(data);
                t.columns.adjust().draw();
            },
            dataType: "json"
          });

    });

    $("#createReport").click(function(){
        data={
            from:$("#fromDate").val(),
            to:$("#toDate").val(),
            type:$("#typeReport").val(),
            listDni:new Array(),
        };
        $.each(t.rows( { selected: true } ).data(),function(key,value){
            console.log(value)
            data.listDni[key] = new Array(value.nroDocumento,value.apellido+' '+value.nombre,value.nombreDependencia,value.nombreTipoEmpleado,value.legajo,value.nHorasDia);
        });
        //console.log(data);
        $("#formListEmployee").val(JSON.stringify(data));
        $("#dniForm").submit();
    });
}

function getSecretary(){
    $.getJSON(url,{page:page,action:"getSecretary"},function(data){
        $.each(data,function(key,value){
            $("#secretary").append("<option value='"+value.idSecretaria+"'>"+value.cNomSecretaria+"</option>");
        });
    });
}
function getDependence(){
    $("#dependence").html("<option value=''>Todas las Dependencias</option>");
    $.getJSON(url,{page:page,action:"getDependence"},function(data){
        $.each(data,function(key,value){
            $("#dependence").append("<option value='"+value.idDependencia+"'>"+value.cNomDependencia+"</option>");
        });
        $("#dependence").dropdown("restore defaults");
    });
}