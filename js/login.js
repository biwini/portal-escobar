$(document).on('submit','#form-login', function(event) {

	event.preventDefault();
	usuario = document.getElementById('inpUsuario').value;
	contraseña = document.getElementById('inpContraseña').value;
	g = grecaptcha.getResponse();
	$("#mensaje").html();

	if(g == ''){
		$("#mensaje").html('Captcha incompleto');
		grecaptcha.reset();
		return false;
	}

	
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "usuario="+usuario+"&contrasenia="+contraseña+"&g-recaptcha-response="+g,
		dataType: "html",
		error: function(xhr, textStatus, error){
			console.log(xhr.responseText);
            console.log(xhr.statusText);
            console.log(textStatus);
            console.log(error);
			alert("error petición ajax");
		},
		success: function(data){
			console.log(data);
			response = JSON.parse(data);
		    switch(response.Status){
		        case 'Success':
		        	location.reload();
		        break;
		        case 'No Access':
		        	$("#mensaje").html('El usuario: '+usuario+'. No tiene accesos a ningun sistema');
		        	grecaptcha.reset();
		        break;
		        case 'Invalid Captcha':
		        	$("#mensaje").html('Captcha Invalido');
		        	grecaptcha.reset();
		        break;
		        case 'Unknown User':
		        	$("#mensaje").html('Datos de accesos invalidos');
		        	grecaptcha.reset();
		        break;
		    }
		}
	});
	return false;
});