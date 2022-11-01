$('#filter_button').click(function(){
    DateSince = $('#date_since').val();
    DateUntil = $('#date_until').val();

    if(DateSince != '' || DateUntil != ''){
        FilterDate = true;
        if(DateSince != ''){
            DateSince = DateSince.split("-");
            console.log(DateSince)
            DateSince = new Date(DateSince[0], DateSince[1], DateSince[2]);
            DateSince.setHours(0,0,0,0);
        }
        if(DateUntil != ''){
            DateUntil = DateUntil.split("-");
            DateUntil = new Date(DateUntil[0], DateUntil[1], DateUntil[2]);
            DateUntil.setHours(0,0,0,0);
        }
    }

    SelectedFilters = '';
    $("#filter-content input[type=checkbox]:checked").each(function(){
        SelectedFilters = SelectedFilters + this.value+",";
    });
    if(SelectedFilters != ""){
        FilterUser = $('#filter_user').val();
        showTicket();
    }
});
function showTicket(){
    FilterTickets = [];
    $.each(ListTickets, function(k,v){
        let filter = SelectedFilters.split(',');
        if(filter.includes(v.Estado) || (v.CierreConfirmado == 0 && v.Estado == 3)){
            FechaTicket = v.FechaAlta.split('/');
            FechaTicket[2] = FechaTicket[2].split(' ')[0]
            FechaTicket = new Date(FechaTicket[2], FechaTicket[1], FechaTicket[0])
            FechaTicket.setHours(0,0,0,0);

            if(FilterDate && DateSince != '' && DateUntil != ''){
                if(FechaTicket >= DateSince && FechaTicket <= DateUntil){
                    FilterTickets.push(v);
                }
            }else if(FilterDate && DateSince != ''){
                if(FechaTicket >= DateSince){
                    FilterTickets.push(v);
                }
            }else if(FilterDate && DateUntil != ''){
                if(FechaTicket <= DateUntil){
                    FilterTickets.push(v);
                }
            }else{
                FilterTickets.push(v);
            }
        }
    });
    DataTable.destroy();
    displayDataTable();
}
function displayDataTable(){
    DataTable = $('#t_ticket').DataTable({
        "data": FilterTickets,
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
    });
}