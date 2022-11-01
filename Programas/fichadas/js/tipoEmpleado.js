let TiposEmpleado;
let id = 0;
let action = 'add';
let DataTableTiposEmpleado;
let $tr;

$('#ver_tipos').click(function(){
    DataTableTiposEmpleado.columns.adjust().draw();
});

$('#new_tipo').click(function(){
    id = 0;
    action = 'add';
    $('.test.modal')
        .modal('setting', 'transition', 'scale')
        .modal('show')
      ;
});

$(document).on('click','.editTipo', function(e){
    id = $(this).val();
    action = 'update';
    $tr = $(this).parents('tr');

    let Found = TiposEmpleado.find(m => m.id == id);

    document.getElementById('tipoempleado_abm').value = Found.nombre;
    document.getElementById('descripcion_abm').value = Found.descripcion;

    $('#title').text('EDITAR TIPO DE EMPLEADO');
    $('#enviar').val('GUARDAR CAMBIOS');

    $('.test.modal')
        .modal('setting', 'transition', 'scale')
        .modal('show')
      ;
});

function setTypeEmployeeData(array){
    $('#employeeTypes').find('option:not(:first)').remove();
    $('#employeeTypes').prop('selectedIndex',0);
    $('#employeeTypes').change();

    $('#filter_tipoempleado').find('option:not(:first)').remove();
    $('#filter_tipoempleado').prop('selectedIndex',0);
    // $('#filter_tipoempleado').change();

    $.each(array,function(key,value){

        let name = (value.descripcion != null) ? value.nombre+" - "+value.descripcion : value.nombre;

        $("#employeeTypes").append("<option value='"+value.id+"'>"+name+"</option>");
        $('#filter_tipoempleado').append("<option value='"+value.id+"'>"+name+"</option>");
    }); 
}

async function start2(){
    TiposEmpleado = await getDataTiposEmpleado('getTiposEmpleado');

    setTypeEmployeeData(TiposEmpleado);

    DataTableTiposEmpleado = $('#tb_tipo').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": TiposEmpleado,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columns":[
            { "data": "nombre"},
            { "data": "descripcion"},
            { "data": "acciones",
                "render":function(data, type, full, meta){
                    return '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning editTipo\' value=\''+full.id+'\'></button>'+
                    '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger deleteTipo\' value=\''+full.id+'\'></button> ';
                }
            },
        ],
    });

    $('.ui.modal.test').modal({
        onHide: function(){
            $('#tipoempleado_abm').val('');
            $('#descripcion_abm').val('');
            id = 0;
            action = 'add';
            $tr = undefined;
            $('#title').text('AGREGAR TIPO DE EMPLEADO');
            $('#enviar').val('AGREGAR');
            console.log('hola')
        },
        onShow: function(){
            console.log('shown');
        },
        onApprove: sendFormData,
    });
};

let sendFormData = () => {
    let tipo = $('#tipoempleado_abm').val();
    let desc = $('#descripcion_abm').val();

    if(tipo == ''){
        swal('Campos incompletos','Complete todos los campos obligatorios e intentelo nuevamente', 'warning');

        return false;
    }

    $.ajax({
        type: "POST",
        url: url,
        data: {'page':'TiposEmpleado','action':action, 'tipo': tipo, 'desc': desc, 'id': id},
        dataType: "json",
    })
    .fail(function(data){
        swal('Error','Error Peticion ajax','error');
    })
    .done(function(data){
        if(data.status == 'success'){
            $('#tipoempleado_abm').val('');
            $('#descripcion_abm').val('');
            swal((action == 'add') ? 'Se agrego el tipo de empleado' : 'Se actualizo el tipo de empleado','','success');
    
            if(action == 'update'){
                TiposEmpleado.forEach(function(m, k){
                    if(m.id == data.response.id){
                        TiposEmpleado[k] = data.response;
                    }
                });
                DataTableTiposEmpleado.row($tr).remove().draw();   
            }else{
                TiposEmpleado.push(data.response);
            }
            
            DataTableTiposEmpleado.row.add(data.response).draw();
    
            $('.test.modal').modal('hide');
        }else{
            swal(data.status, data.output, 'warning');
        }
    });

    return false;
}

$(document).on('click','.deleteTipo', function(e){
    id = $(this).val();
    $tr = $(this).parents('tr');
    
    swal({
        title: "¡Eliminando Tipo de empleado!",
        text: "¿Seguro que desea Eliminar este tipo de empleado?",
        icon: "warning",
        buttons: {
        cancel: "No",
        Si: true,
        },
    })
    .then(async (willDelete) => {
        if (willDelete) {
            let data = await getDataTiposEmpleado('delete',id);

            if(data.status == 'success'){
                swal('Se elimino el tipo de empleado','','success');

                TiposEmpleado.forEach(function(m, k){
                    if(m.id == id){
                        TiposEmpleado.splice(k, 1);
                    }
                });

                DataTableTiposEmpleado.row($tr).remove().draw();
                id = 0;
                $tr = undefined;     
            }
            else{
                swal(data.status, data.output,'warning');
            }
        }
    });
});

function getDataTiposEmpleado(action = 'getTiposEmpleado', id = 0, tipo = ''){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'page':'TiposEmpleado','action':action, 'tipo': tipo, 'id': id},
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