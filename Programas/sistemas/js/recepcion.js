var room = 0;
var roomPrice = 0;
var id = 0;
$(function(){
	GetHabitacion();
});
$('#modal_end_clean').on('hidden.bs.modal', function (e) {
    $("#end_clean")[0].reset();
});
$(document).on('click','.habitacion', function(event) {
	habitacion = $(this)[0].id;
	$.each(ListHab, function(i,hab){
		if(hab.IdHab == habitacion){
      switch(hab.StateHab){
        case 'DISPONIBLE':
          $('#card-reception').hide();
          $('#back').show();
          $('#card-room-checkin-detail').show();
          $('#card-checkin').show();
    			room = hab.IdHab;
    			roomPrice = parseInt(hab.PriceNight);
          console.log(hab.Piso)
    			$('#precio_tarifa').text(precioTarifaActual + roomPrice);
    			$('#room-name-checkin').text(hab.Hab);
    			$('#room-type-checkin').text(hab.Tipo);
    			$('#room-detail').text(hab.Desc);
    			$('#room-state').text(hab.StateHab);
        break;
        case 'OCUPADO':
          $('#card-reception').hide();
          $('#back').show();
          $('#card-room-checkout-detail').show();
          $('#card-checkout').show();
          room = hab.IdHab;
          id = hab.IdCheckin;
          roomPrice = parseInt(hab.PriceNight);
          $('#room-name').text(hab.Hab);
          $('#room-type').text(hab.Tipo);
          $('#room-price').text('$'+hab.PriceNight);
          
          $('#client-name').text(hab.GuestName);
          $('#client-doc').text(hab.GuestDoc);

          var d = new Date();
          month = d.getMonth()+1;
          if(month < 10){
              month = "0"+month;
          }
          minute = d.getMinutes();
          if(minute < 10){
              minute = "0"+minute;
          }
          day = d.getDate();
          if(day < 10){
              day = "0"+day;
          }
          var Checkout = d.getFullYear()+'-'+month+'-'+day+' '+ d.getHours() + ':' + minute + ':' + d.getSeconds();
          var night = Math.floor((new Date(Checkout) - new Date(hab.DateCheckin))/(1000*60*60*24));
          $('#date-checkin').text(hab.DateCheckin);
          $('#date-checkout').text(Checkout);
          $('#stay-time').text(night + ' Noches')
          if(night == 0){
            $('#total-cost').text(hab.PriceNight);
          }else{
            $('#total-cost').text(hab.PriceNight * night);
          }
        break;
        case 'LIMPIEZA':
          $('#modal_end_clean').modal('show');
          room = hab.IdHab;
          $('#clean_room').text(hab.Hab);
          $('#type_room').text(hab.Tipo);
          $('#detail_room').text(hab.Desc);
        break;
      }
		}
	});
});

$('.back').click(function(){
	$('#room-name').text('');
	$('#room-type').text('');
	$('#room-detail').text('');
	$('#room-state').text('');
	$('#card-reception').show();
	$('#card-room-checkout-detail').hide();
  $('#card-room-checkin-detail').hide();
	$('#card-checkin').hide();
  $('#card-checkout').hide();
	$('#back').hide();
});
$(document).on('click','.suggestion', function(event) {
  var huesped = $(this).attr('id');
  $.each(listHuesped, function(i,h){
    if(h.ID == huesped){
      $('#type_doc').val(h.TypeDoc);
      $('#nombre').val(h.Name);
      $('#apellido1').val(h.LastName);
      $('#documento').val(h.Doc);
      $('#fecha_nacimiento').val(h.FechaNacimiento)
      $('#email').val(h.Email);
    }
  });
});