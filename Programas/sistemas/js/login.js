
$(document).on('submit','#formLogin', function(event) {

	event.preventDefault();

	$.ajax({
		type: "POST",
		url: "functions/",
		data: $(this).serialize(),
		dataType: "html",
	})
	.fail(function(data){
			alert("error petición ajax");
	})
	.done(function(data){
		response = JSON.parse(data);
		if(!response.Error){
			switch(response.Result){
				case "Success" :
					location.href = 'index';
				break;
				case "Invalid" :
					mensajeError();
				break;
			}
		}else{
				alert("Error");
			}
	});
	
});