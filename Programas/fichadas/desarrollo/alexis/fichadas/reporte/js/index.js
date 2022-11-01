$(document).ready(start);
url="controller/";
page="Reporte";
function start(){
    $(".searchSelect").dropdown({
        message: {
            noResults:'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true
    });
    $("#typeReport").dropdown();
    t=$('#listEmployee').DataTable({
        "language": {
            "url": "src/DataTables/Spanish.json"
        },
        "columnDefs": [ {
            "targets": 0,
            "orderable": false,
            "data": null,
            "defaultContent": "<input type='checkbox' class='checkbox'>",
            "className":"dt-body-center"
        }],
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
    // $("#secretary").change(function(){
    //     getDependence($(this).val());
    // });
    $("#searchReport").click(function(){
        $('#msjCreateReport').hide();
        $("#masterCheckbox").prop("checked",false);
        t.rows().remove().draw();
        search={
            idSecretary:$("#secretary").val(),
            idDependence:$("#dependence").val(),
            idEmployee:$("#employee").val()
            // fromDate:$("#fromDate").val(),
            // toDate:$("#toDate").val()
        };
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
                $.each(data,function(key,value){
                    row=t.row.add([null,value.Apellido+" "+value.Nombre,value.Legajo,value.nameSector,value.nameDependency]).draw("false");
    
                });
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
            data.listDni[key]=new Array(value[2],value[1],value[4]);
        });
        //console.log(data);
        $("#formListEmployee").val(JSON.stringify(data));
        $("#dniForm").submit();
    });
}

function getSecretary(){
    $.getJSON(url,{page:page,action:"getSecretary"},function(data){
        $.each(data,function(key,value){
            $("#secretary").append("<option value='"+value.id+"'>"+value.name+"</option>");
        });
    });
}
function getDependence(){
    $("#dependence").html("<option value=''>Todas las Dependencias</option>");
    $.getJSON(url,{page:page,action:"getDependence"},function(data){
        $.each(data,function(key,value){
            $("#dependence").append("<option value='"+value.id+"'>"+value.name+"</option>");
        });
        $("#dependence").dropdown("restore defaults");
    });
}