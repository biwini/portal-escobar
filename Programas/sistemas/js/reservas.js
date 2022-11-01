var SelectedDays = new Array();

var rowStart;
var rowStartIndex;

var dayStart;
var dayStartIndex;
var dayEnd;

var dayOn;
var dayOver = "0";

var dayLeave = null;

var hola = new Array();

var isSelected = false;
var isRemoving = false;

var haveReserva = false;
var UpdateReserva = false;
var reservaID = 0;

var $tbl = $("#hola"),
$tblHead = $("#hola thead #days");
$tblBody = $("#hola tbody tr");

$(function(){
    setTableFunctions();
})
$('#modal_reserva').on('hidden.bs.modal', function (e) {
    $("#data_reserva")[0].reset();
    $('#content-estado').hide();
    $('#content-pagado').hide();
    $('#buscador').show();
    $("#data_submit").text("Reservar");
    UpdateReserva = false;
    var reservaID = 0;
});
function setTableFunctions(){
    var $tbl = $("#hola"),
    $tblHead = $("#hola thead #days");
    $tblBody = $("#hola tbody tr");

    $tbl.children("tbody").find("td")
    .on("mouseover",function(e){
        var cellIndex = $(this).index();
        $tblHead.children("th").eq(cellIndex).addClass("selected");
        $tr = $tblBody.eq(rowStartIndex);
        $td =  $tr.children("td").eq($(this).index())
        if(isSelected) {    // Only change css if mouse is down
            console.log(SelectedDays)
            var RowOn = $tblBody.eq(rowStartIndex).attr('id');    //Identifico la fila actual
            if(rowStart == RowOn){ //Comparo la fila actual con la fila en que comenzo la seleccion
                dayOn = $td;    //Identifico el dia actual.
                if (SelectedDays.indexOf($td.attr('class').split(' invalid')[0]) === -1 || dayLeave != null) { //Verifico Si el dia actual no esta ya seleccionado
                    if($td.hasClass('reserva') || haveReserva){
                        $td.addClass('selected');
                        haveReserva = true;
                        $(".selected").each(function(){
                            $(this).addClass('invalid');   //Le agrego una clase para remarcar la seleccion.
                        });
                    }else{
                        haveReserva = false;
                        $(".selected").each(function(){
                            $(this).removeClass('invalid');   //Le agrego una clase para remarcar la seleccion.
                        });
                        $td.addClass('selected');   //Le agrego una clase para remarcar la seleccion.
                    }
                    isRemoving = false;
                    dayOver = $td;
                    SelectedDays.push($td.attr('class').split(' invalid')[0]);
                }else if (SelectedDays.indexOf($td.attr('class').split(' invalid')[0]) > -1) {
                    if(dayOver != "0" && dayOver.index() != cellIndex && !isRemoving){ // Si el dia final es distinto de "0".
                        var i = SelectedDays.indexOf(dayOver.attr('class')); // Obtengo la posicion del elemento dentro del array.
                        SelectedDays.splice(i, 1);  //Elimino el elemento del array.
                        dayOver.removeClass('selected'); //Elimino la clase del elemento.
                        if(dayOver.hasClass('invalid')){
                            dayOver.removeClass('invalid')
                        }
                        isRemoving = true;  // Valido el comienzo de remover los elementos(dias) seleccionados.
                        $.each(SelectedDays, function (i, e) { 
                            if(e.indexOf('reserva') > -1){
                                haveReserva = true;
                                return false;
                            }else{
                                haveReserva = false;
                            }
                        });
                        if(!haveReserva){
                            $(".selected").each(function(){
                                $(this).removeClass('invalid');   //Le agrego una clase para remarcar la seleccion.
                            });
                        }
                    }
                }
            }     
        }
        dayEnd = $(this).attr('class');

    }).on("mouseleave",function(e){
        var cellIndex = $(this).index();
        $tblHead.children("th").eq(cellIndex).removeClass("selected");
        if(isSelected) {    // Only change css if mouse is down
            if (SelectedDays.indexOf(dayOn.attr('class').split(' invalid')[0]) > -1 && dayOn.attr('class').split(' invalid')[0] != dayStart) {
                dayOver = $tblBody.eq(rowStartIndex).children("td").eq($(this).index());
            }
            if(isRemoving){
                $tr = $tblBody.eq(rowStartIndex);
                $td =  $tr.children("td").eq($(this).index()).prev();
                
                if($td.next().hasClass('selected') && $tr.children("td").eq($(this).index()).next().hasClass('selected') || $tr.children("td").eq($(this).index()).hasClass('selected')){
                    RowOn = $tblBody.eq(rowStartIndex).attr('id');  //Identifico la fila actual
                    if(rowStart == RowOn){ //Comparo la fila actual con la fila en que comenzo la seleccion
                        if (dayOn.hasClass('selected') && !dayOver.hasClass('selected')){   //Si el dia sobre el mouse no esta seleccionado.
                            var index = SelectedDays.indexOf(dayOn.attr('class').split(' invalid')[0]);
                            if (index > -1 && dayOn.attr('class').split(' invalid')[0] != dayStart){    //Si el dia actual es distinto al dia en que comenzo.
                                SelectedDays.splice(index, 1);
                                $.each(SelectedDays, function (i, e) { 
                                    if(e.indexOf('reserva') > -1){
                                        console.log(e)
                                        haveReserva = true;
                                        return false;
                                    }else{
                                        haveReserva = false;
                                    }
                                });
                                if(!haveReserva){
                                    console.log("asdasdasdasds")
                                    $(".selected").each(function(){
                                        $(this).removeClass('invalid');   //Le agrego una clase para remarcar la seleccion.
                                    });
                                }
                                console.log(haveReserva)
                                console.log(dayOn)
                                dayOn.removeClass('selected');  //Remuevo la clase.
                                if(dayOver.hasClass('invalid')){
                                    dayOver.removeClass('invalid')
                                }
                            }
                        }else{
                            isRemoving = false;
                        }
                    }
                }
            }

        }
    });

    $tbl.children("tbody")
    .on("mouseenter",function(e){   //Cuando el mouse entra a la tabla.
        if(isSelected) {  //Si esta selecionando
            dayOverIndex = dayOver.index(); //Obtengo la posicion del dia sobre el mouse en la tabla.

            dayLeaveIndex = dayLeave.index()    //Obtengo la posicion del dia sobre el mouse cuando salio de la tabla.
            while(dayLeaveIndex != dayOverIndex){   //Mientras el dia que sali de la tabla sea distinto del dia actual sobre el mouse.
                $td = $tblBody.eq(rowStartIndex).children("td").eq(dayLeaveIndex);  //Obtengo el TD de la tabla del dia en que sali.

                if(dayLeaveIndex > dayOverIndex){   //Si el dia que me fui es mayor al dia actual.
                    if(!$td.hasClass('selected')){  //Si el TD no tiene la clase 'selected'.
                        $td.addClass('selected');   //Le agrego la clase 'selected'.
                    
                        SelectedDays.push($td.attr('class'));   //Le agrego el dia a la lista.
                    }else if(dayLeaveIndex >= dayStartIndex){   //Si el dia en que me fui es mayor o igual al dia en que comenze la seleccion.
                        var index = SelectedDays.indexOf($td.attr('class'));    //Obtengo la posicion del td dentro de la lista
                        SelectedDays.splice(index, 1);  //Remuevo el TD de la lista.
                        $td.removeClass('selected');    //Remuevo la clase 'selected' del TD
                    }
                    dayLeaveIndex--;    
                }else{  //Si el dia en que me fui es menor al dia actual.
                    if(!$td.hasClass('selected')){
                        $td.addClass('selected');
                    
                        SelectedDays.push($td.attr('class'));
                    }else if(dayLeaveIndex <= dayStartIndex){
                        var index = SelectedDays.indexOf($td.attr('class'));
                        SelectedDays.splice(index, 1);
                        $td.removeClass('selected');
                    }
                    dayLeaveIndex++;
                }
            }
            console.log(dayLeaveIndex);
            console.log(dayOverIndex);
            console.log(dayLeave);
            dayLeave = null;
        }
    }).on("mouseleave",function(e){
        if(isSelected) {
            dayLeave = dayOver;
            dayOverIndex = dayOver.index();
        }
    });

    $("#hola tr td").mousedown(function() {
        if(!$(this).hasClass('reserva')){
            $(this).addClass('selected');
            //.split('D')[1]
        	dayStart = $(this).attr('class');
            dayStartIndex = $(this).index();
            dayOver = $(this);
            dayOn = $(this);
        	rowStart = $(this).parents('tr')[0].id;
            rowStartIndex = $(this).parents('tr').index();
            isSelected = true;
            SelectedDays.push(dayStart);
            console.log('Dia Inicio ' +dayStart)
            console.log(dayStartIndex)
            console.log('Habitacion'+rowStart)
        }else{
            UpdateReserva = true;
            reservaID = $(this).children('div')[0].id;
            $('#content-estado').show();
            $('#content-pagado').show();
            $('#buscador').hide();

            nomReserva = $('#'+reservaID+' span.nombre-reserva').text();
            docReserva = $('#'+reservaID+' span.doc-reserva').text();
            dayStart = $('#'+reservaID+' span.date-start-reserva').text();
            dayEnd = $('#'+reservaID+' span.date-end-reserva').text();
            state = $('#'+reservaID+' span.state-reserva').text();
            paid = $('#'+reservaID+' span.paid-reserva').text();

            console.log(dayStart);
            console.log(dayEnd);
            $("#data_submit").text("ACTUALIZAR");
            $("#estado option[value='"+state+"']").prop("selected",true);
            $("#pagado option[value='"+paid+"']").prop("selected",true);

            $("#date_start").val(dayStart);
            $("#date_end").val(dayEnd);

            $.each(listHuesped, function(i,h){
                if(h.Doc == docReserva){
                  $('#type_doc').val(h.TypeDoc);
                  $('#nombre').val(h.Name);
                  $('#apellido1').val(h.LastName);
                  $('#apellido2').val(h.SecondLastName);
                  $('#telefono').val(h.Phone);
                  $('#direccion').val(h.Addres);
                  $('#comuna').val(h.Commune);
                  $('#ciudad').val(h.City);
                  $('#cp').val(h.CP);
                  $('#nacionalidad').val(h.Nationality);
                  $('#sexo').val(h.Sex);
                  $('#documento').val(h.Doc)
                  $('#fecha_nacimiento').val(h.FechaNacimiento)
                  $('#preferencia').val(h.Preferences)
                  $('#email').val(h.Email);
                }
            });

            $("#habitacion option[value="+$(this).parents('tr')[0].id.split('h')[1]+"]").prop("selected",true);

            $('#modal_reserva').modal('show');
        }
    });
    $("body").mouseup(function() {
    	if(isSelected){
            if(!haveReserva){
                $('#modal_reserva').modal('show');
        	    dayEnd = dayEnd.split(' selected')[0].split('D')[1];
        	    isSelected = false;
                isRemoving = false;
                haveReserva = false;

        	    console.log(SelectedDays)

                var date = new Date();
                dayStart = dayStart.split(' selected')[0].split('D')[1];
                month = date.getMonth()+1;
                if(month < 10){
                    month = "0"+month;
                }

                $("#date_start").val(date.getFullYear()+'-'+(month)+'-'+dayStart);

                $("#date_end").val(date.getFullYear()+'-'+(month)+'-'+dayEnd);
                $("#habitacion option[value="+rowStart.split('h')[1]+"]").prop("selected",true);

        	    SelectedDays = new Array();
                dayOver = "0";
                //Busco y elimino la clase de los elementos seleccionados.
                $("#hola tbody tr").find(".selected").each(function(e) {
                    $(this).removeClass('selected');
                });
                SelectedDays = new Array();
                rowStart;
                rowStartIndex;
                dayStart;
                dayStartIndex;
                dayEnd;
                dayOn;
                dayOver = "0";
                dayLeave = null;
                hola = new Array();
            }else{
                console.log(SelectedDays)
                $("#hola tbody tr").find(".selected").each(function(e) {
                    $(this).removeClass('selected');
                    $(this).removeClass('invalid');
                    SelectedDays = new Array();
                    rowStart;
                    rowStartIndex;
                    dayStart;
                    dayStartIndex;
                    dayEnd;
                    dayOn;
                    dayOver = "0";
                    dayLeave = null;
                    hola = new Array();
                    isSelected = false;
                    isRemoving = false;
                    haveReserva = false;
                });
            }
    	}
    });
}
$('#filter_month').change(function(){
    GetReserva();
});
$('#filter_hotel').change(function(){
    $('#name_hotel').html($(this).find('option:selected').text())
    GetReserva();
});
$(document).on('click','.suggestion', function(event) {
  var huesped = $(this).attr('id');
  $.each(listHuesped, function(i,h){
    if(h.ID == huesped){
      $('#type_doc').val(h.TypeDoc);
      $('#nombre').val(h.Name);
      $('#apellido1').val(h.LastName);
      $('#apellido2').val(h.SecondLastName);
      $('#telefono').val(h.Phone);
      $('#direccion').val(h.Addres);
      $('#comuna').val(h.Commune);
      $('#ciudad').val(h.City);
      $('#cp').val(h.CP);
      $('#nacionalidad').val(h.Nationality);
      $('#sexo').val(h.Sex);
      $('#documento').val(h.Doc)
      $('#fecha_nacimiento').val(h.FechaNacimiento)
      $('#preferencia').val(h.Preferences)
      $('#email').val(h.Email);
    }
  });
});