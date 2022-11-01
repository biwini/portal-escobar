let Motivos;
let id = 0;
let action = 'add';
let DataTableMotivos;
let $tr;

$(document).ready(start());

$('#ver_motivos').click(function(){
    DataTableMotivos.columns.adjust().draw();
});

$('#new_tipo').click(function(){
    id = 0;
    action = 'add';
    $('.test.modal')
        .modal('setting', 'transition', 'scale')
        .modal('show')
      ;
});

$(document).on('click','.editMotivo', function(e){
    id = $(this).val();
    action = 'update';
    $tr = $(this).parents('tr');

    let Found = Motivos.find(m => m.id == id);

    document.getElementById('motivo_abm').value = Found.cMotivo;

    $('#title').text('EDITAR MOTIVO');
    $('#enviar').val('GUARDAR CAMBIOS');

    $('.test.modal')
        .modal('setting', 'transition', 'scale')
        .modal('show')
      ;
});

async function start(){
    Motivos = await getDataMotivos('getMotivos');

    $.each(Motivos,function(key,value){
        $("#motivo").append("<option value='"+value.id+"'>"+value.cMotivo+"</option>");
        $("#filter_motivo").append("<option value='"+value.id+"'>"+value.cMotivo+"</option>");
    }); 

    DataTableMotivos = $('#tb_motivos').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": Motivos,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columns":[
            { "data": "cMotivo"},
            { "data": "acciones",
                "render":function(data, type, full, meta){
                    return '<button title=\'Editar\' type=\'button\' style=\'margin-right: 5px;\' class=\'icon-pencil btn btn-md btn-warning editMotivo\' value=\''+full.id+'\'></button>'+
                    '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger deleteMotivo\' value=\''+full.id+'\'></button> ';
                }
            },
        ],
    });

    $('.ui.modal.test').modal({
        onHide: function(){
            $('#motivo_abm').val('');
            id = 0;
            action = 'add';
            $tr = undefined;
            $('#title').text('AGREGAR MOTIVO');
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
    let motivo = $('#motivo_abm').val();
    console.log(motivo)
    // return false;
    if(motivo == ''){
        swal('Campos incompletos','Complete todos los campos e intentelo nuevamente', 'warning');

        return false;
    }

    $.ajax({
        type: "POST",
        url: url,
        data: {'page':'Motivos','action':action, 'motivo': motivo, 'id': id},
        dataType: "json",
    })
    .fail(function(data){
        swal('Error','Error Peticion ajax','error');
    })
    .done(function(data){
        if(data.status == 'success'){
            $('#motivo_abm').val('');
            swal((action == 'add') ? 'Se agrego el motivo' : 'Se actualizo el motivo','','success');
    
            if(action == 'update'){
                Motivos.forEach(function(m, k){
                    if(m.id == data.response.id){
                        Motivos[k] = data.response;
                    }
                });
                DataTableMotivos.row($tr).remove().draw();   
            }else{
                Motivos.push(data.response);
            }
            
            DataTableMotivos.row.add(data.response).draw();
    
            $('.test.modal').modal('hide');
        }else{
            swal(data.status, data.output, 'warning');
        }
    });

    return false;
}

$(document).on('click','.deleteMotivo', function(e){
    id = $(this).val();
    $tr = $(this).parents('tr');
    
    swal({
        title: "¡Eliminando motivo!",
        text: "¿Seguro que desea Eliminar esta motivo?",
        icon: "warning",
        buttons: {
        cancel: "No",
        Si: true,
        },
    })
    .then(async (willDelete) => {
        if (willDelete) {
            let data = await getDataMotivos('delete',id);

            if(data.status == 'success'){
                swal('Se elimino el motivo','','success');

                Motivos.forEach(function(m, k){
                    if(m.id == id){
                        Motivos.splice(k, 1);
                    }
                });

                DataTableMotivos.row($tr).remove().draw();
                id = 0;
                $tr = undefined;     
            }
            else{
                swal(data.status, data.output,'warning');
            }
        }
    });
});

function getDataMotivos(action = 'getMotivos', id = 0, motivo = ''){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'page':'Motivos','action':action, 'motivo': motivo, 'id': id},
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