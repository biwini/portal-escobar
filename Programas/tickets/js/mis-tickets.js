let filterPendiente = '1';
let filterEnCurso = '2';
let filterFinalizado;
let filterConfirmado;

let dateSince, dateUntil;

$(document).ready(function(){
    $('#t_ticket thead th').each( function () {
        var title = $(this).text();
        if(title != 'ACCIONES'){
            $(this).html(title+ '<div style="width:100%"><input type="text" class=" form-control" placeholder="buscar..." /></div>' );
        }
    });

    let tickets = ListTickets.filter(t => t.Estado == 1 || t.Estado == 2);

    DataTable = $('#t_ticket').DataTable({
        "data": tickets,
        "rowId": 'IdTicket',
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "createdRow": function( row, data, dataIndex){
            switch(data.Estado){
                case '1': $(row).children().eq(0).addClass('pendiente'); break;
                case '2': $(row).children().eq(0).addClass('en-proceso'); break;
                case '3': $(row).children().eq(0).addClass('finalizado'); break;
            }
        },
        "columns":[
            { "data": "Codigo",
            "render":function(data, type, full, meta){
                    return '<div style=\'width:100%;height:100%;text-align:center;font-size:20px;\'>'+full.Codigo+'</div>';
                }
            },
            { "data": "FechaAlta"},
            { "data": "Motivo",
            "render":function(data, type, full, meta){
                    motivo = full.Motivo.split("/").join("/<br>");
                    return motivo;
                }
            },
            { "data": "Encargado"},
            { "data": "Estado",
                "render":function(data, type, full, meta){
                    var motivo = '';
                    switch(full.Estado){
                        case '1': motivo = 'PENDIENTE'; break;
                        case '2': motivo = 'EN CURSO'; break;
                        case '3': motivo = 'FINALIZADO'; break;
                    }
                    return motivo;
                }
            },
            { "data": "Comentario_Tecnico"},
            { "data": "FechaFinalizado"},
            { "data": "Estado",
                "render":function(data, type, full, meta){
                    var estado = '';
                    if(full.FechaFinalizado != null && full.CierreConfirmado == 0 ){
                        estado = '<button type=\'button\' class=\'btn btn-success confirmar_cierre\' id=\''+full.Codigo+'\' >Confirmar Cierre</button>';
                    }
                    return estado;
                }
            },
        ],
        initComplete: function () {
            // Apply the search
            this.api().columns().every( function () {
                var that = this;
                $( 'input', this.header() ).on( 'keyup change clear', function () {
                    if ( that.search() !== this.value ) {
                        that.search(this.value).draw();
                    }
                });
            });
        }
    });
});

function showmodal() {
    return new Promise(resolve => {
        $('#loading').modal({backdrop: 'static', keyboard: false})
        setTimeout(() => {
            resolve('resolved');
        }, 250);
    });
}

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
$('#filter_pendiente').change(function(){
    filterPendiente = $(this).val();

    if(!this.checked){
        filterPendiente = undefined;
    }

    displayTable();
});
$('#filter_encurso').change(function(){
    filterEnCurso = $(this).val();

    if(!this.checked){
        filterEnCurso = undefined;
    }

    displayTable();
});
$('#filter_finalizado').change(function(){
    filterFinalizado = $(this).val();

    if(!this.checked){
        filterFinalizado = undefined;
    }

    displayTable();
});

$('#filter_confirmado').change(function(){
    filterConfirmado = $(this).val();

    if(!this.checked){
        filterConfirmado = undefined;
    }

    displayTable();
})

async function displayTable(){
    await showmodal(); 
    let filterTable = ListTickets;

    if(dateSince !== undefined){
        filterTable = filterTable.filter(function(t){
            console.log(t.DateAlta >= dateSince || dateSince <= t.DateAlta)
            return (t.DateAlta >= dateSince || dateSince <= t.DateAlta);
        });
    }

    if(dateUntil !== undefined){
        filterTable = filterTable.filter(t => t.DateAlta <= dateUntil || dateUntil >= t.DateAlta);
    }

    if(filterPendiente !== undefined || filterEnCurso !== undefined || filterFinalizado !== undefined){
        filterTable = filterTable.filter(t => t.Estado.includes(filterPendiente) || t.Estado.includes(filterEnCurso) || t.Estado.includes(filterFinalizado));
    }

    if(filterConfirmado !== undefined){
        filterTable = filterTable.filter(t => t.CierreConfirmado == 0);
    }

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    $('#loading').modal('hide');
}
