function cargarEntidad(){
	$(document).ready(function(){
		var expediente=document.getElementById("idExpediente").value;
		var entidad=document.getElementById("idEntidad").value;
		var referente=document.getElementById("idReferente").value;
		var responsable=document.getElementById("idResponsable").value;
		var atendido_por=document.getElementById("idAtendidoPor").value;
		var telefono=document.getElementById("idTelefono").value;
		var email=document.getElementById("idEmail").value;
		var novedad=document.getElementById("idNovedad").value;
		var observaciones=document.getElementById("idObservaciones").value;

		if(expediente != "" && entidad != "" && referente != "" && responsable != "" && atendido_por != "" && novedad != "" && observaciones != ""){
			$("#resultado").queue(function(n) {
				$("#resultado").html();
				$.ajax({
					type: "POST",
					url: "php/cargarEntidad.php",
					data: "expediente="+expediente+"&entidad="+entidad+"&referente="+referente+"&responsable="+responsable+"&atendido_por="+atendido_por+"&telefono="+telefono+"&email="+email+"&novedad="+novedad+"&observaciones="+observaciones,
					dataType: "html",
					error: function(){
						alert("error petición ajax");
					},
					success: function(data){
						$("#resultado").html(data);
						n();
					}
				});
			});
		}
		else{
			alert('Complete todos los campos ')
		}
	});
	document.getElementById('resultado').style.display = 'block';
}
function vaciarTextBox(){
	$("input[type=text]").each(function(){
		$($(this)).val('');
	})
	$("textarea").each(function(){
		$($(this)).val('');
	})
	document.getElementById('resultado').style.display = 'none';
	document.getElementById('mensaje').style.display = 'none';
}
function mostrarDivs(num){
	// en caso de que reciba 1 va a mostrar el formulario de alta de registros y va a ocultar los otros dos.
	if (num == 1){
		document.getElementById('divConsultas').style.display = 'none';
		document.getElementById('bttnConsulta').style.background = '';
		document.getElementById('divRegistro').style.display = 'block';
		document.getElementById('bttnRegistro').style.background = '#58D3F7';
		document.getElementById('divModificacion').style.display = 'none';
		vaciarTextBox();
	}
	else{
		// en caso de que reciba 1 va a mostrar el formulario Consultas y va a ocultar los otros dos.
		document.getElementById('divConsultas').style.display = 'block';
		document.getElementById('bttnConsulta').style.background = '#58D3F7';
		document.getElementById('divRegistro').style.display = 'none';
		document.getElementById('bttnRegistro').style.background = '';
		document.getElementById('divModificacion').style.display = 'none';
		//Va a la funcion "mostrarRegistro".
		mostrarRegistro();
	}
}
//Muestra todos los registros de la base de datos
function mostrarRegistro(){
	$(document).ready(function(){
		$("#consultas").queue(function(n) {
			$("#consultas").html();
			$.ajax({
				type: "POST",
				url: "php/obtenerRegistros.php",
				dataType: "html",
				error: function(){
					alert("error petición ajax");
				},
				success: function(data){
					$("#consultas").html(data);
					n();
				}
			});
		});
	});
}
//Identifica el checkbox seleccionado y luego activa o desactiva el campo de texto asociado a ese checkbox.
function activarCampo(val){
	switch (val){
		case 1:var idCheckSeleccionado = 'idFilFechaDesde';break;
		case 2:var idCheckSeleccionado = 'idFilFechaHasta';break;
		case 3:var idCheckSeleccionado = 'idFilEntidad';break;
		case 4:var idCheckSeleccionado = 'idFilAgente';break;
		case 5:var idCheckSeleccionado = 'idFilExpediente';break;
		case 6:var idCheckSeleccionado = 'idFilResponsable';break;
		case 7:var idCheckSeleccionado = 'idFilNovedad';break;
	}
	if(document.getElementById(idCheckSeleccionado).disabled == false){
		document.getElementById(idCheckSeleccionado).disabled = true;
		document.getElementById(idCheckSeleccionado).value = "";
	}
	else{
		document.getElementById(idCheckSeleccionado).disabled = false;
	}
}
//Filtra las consultas.
function filtrarConsulta(){
	var fechaDesde,fechaHasta,entidad,agente,expediente,responsable,novedad;
	//Obtiene los valores de los checkbox que esten seleccionados.
	$('input[type=checkbox]:checked').each(function(){
		switch ($(this).prop('id')){
			case "chboxFechDesde": fechaDesde =document.getElementById('idFilFechaDesde').value;
			break;
			case "chboxFechHasta": fechaHasta =document.getElementById('idFilFechaHasta').value;
			break;
			case "chboxEntidad": entidad = document.getElementById('idFilEntidad').value;
			break;
			case "chboxAgente": agente = document.getElementById('idFilAgente').value;
			break;
			case "chboxExpediente": expediente = document.getElementById('idFilExpediente').value;
			break;
			case "chboxResponsable": responsable = document.getElementById('idFilResponsable').value;
			break;
			case "chboxNovedad": novedad = document.getElementById('idFilNovedad').value;
			break;
		}
	})
	//Si alguna de las variables es distinta de null va a ir a la pagina para obtener los datos segun los checbox seleccionados
	if (fechaDesde != null || fechaHasta != null || entidad != null|| agente != null || expediente != null || responsable != null || novedad != null ){
		$(document).ready(function(){
			$("#consultas").queue(function(n) {
				$("#consultas").html();
				$.ajax({
					type: "POST",
					url: "php/obtenerRegistrosFiltrados.php",
					data: "expediente="+expediente+"&entidad="+entidad+"&responsable="+responsable+"&agente="+agente+"&novedad="+novedad+"&fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta,
					dataType: "html",
					error: function(){
						alert("error petición ajax");
					},
					success: function(data){
						$("#consultas").html(data);
						n();
					}
				});
			});
		});
	}
	else{
		mostrarRegistro();
	}
}
//variable que utilizo para mantener un registro de la consulta seleccionada.
id_registro = 0;
function seleccionar(id){
	//Obtengo el id del radio seleccionado.
	if($("input[type=radio]:checked").prop("checked")){
	  	id_registro = id;
	}
	//Al darle doble Click deselecciono el radio.
	$('#consultas input').on('dblclick', function() {
	     $("input[type=radio]:checked").prop("checked",false);
	     id_registro = 0;
	});
}
function modificarRegistro(){
	//Verifico si el radio esta seleccionado.
	if($("input[type=radio]:checked").prop("checked")){
		$(function(){
			$("#tbModificacion").queue(function(n) {
				$("#tbModificacion").html();
				$.ajax({
					type: "POST",
					url: "php/obtenerDatosModificacion.php",
					data: "id_registro="+id_registro,
					dataType: "html",
					error: function(){
						alert("error petición ajax");
					},
					success: function(data){
						document.getElementById('divConsultas').style.display = 'none';
						document.getElementById('divModificacion').style.display = 'block';
						$("#tbModificacion").html(data);
						n();
					}
				});
			});
		});
	}
}
function modificacionConfirmada(){
	var id_entidad,fecha,entidad,agente,telefono,email,expediente,responsable,novedad,observaciones,referente;
	id_entidad =document.getElementById('modRegistro').value;	
	fecha =document.getElementById('modfecha').value;			
	entidad = document.getElementById('modentidad').value;
	agente = document.getElementById('modagente').value;
	telefono=document.getElementById("modtelefono").value;
	email=document.getElementById("modemail").value;
	expediente = document.getElementById('modexpediente').value;
	responsable = document.getElementById('modresponsable').value;
	referente = document.getElementById('modreferente').value;
	observaciones = document.getElementById('modobservaciones').value;
	novedad = document.getElementById('modnovedad').value;
		
	$("#mensaje").queue(function(n) {
		$("#mensaje").html();
		$.ajax({
			type: "POST",
			url: "php/modificarRegistro.php",
			data: "id_entidad="+id_entidad+"&fecha="+fecha+"&expediente="+expediente+"&entidad="+entidad+"&referente="+referente+"&responsable="+responsable+"&agente="+agente+"&telefono="+telefono+"&email="+email+"&novedad="+novedad+"&observaciones="+observaciones,
			dataType: "html",
			error: function(){
				alert("error petición ajax");
			},
			success: function(data){
				$("#mensaje").html(data);
				mostrarRegistro();
				n();
			}
		});
	});
	document.getElementById('mensaje').style.display = 'block';
	mostrarDivs(2);
	mostrarRegistro();
}
function eliminarRegistro(){
	if($("input[type=radio]:checked").prop("checked")){
		if(confirm("¿SEGURO QUE DESEA ELIMINAR ESTE REGISTRO?")){
			if(id_registro != 0){
				$(function(){
					$("#mensaje").queue(function(n) {
						$("#mensaje").html();
						$.ajax({
							type: "POST",
							url: "php/eliminarRegistro.php",
							data: "id_registro="+id_registro,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								mostrarDivs(2);
								mostrarRegistro();
								document.getElementById('mensaje').style.display = 'block';
								$("#mensaje").html(data);
								n();
							}
						});
					});
				})
			}
		}
	}
}
function imprimirFormularioPDF(){
	
	var pagina = window.open("formularioPDF.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50%,left=50%,width=742,height=700");
}