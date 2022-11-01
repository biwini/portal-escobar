//variable que utilizo para mantener un registro de la consulta seleccionada.
id_registro = 0;
var DataTable;
var ListLiquidaciones = new Array();
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
$("#tblConsulta tbody").children('tr').on("click",function(event){
 	console.log("asdasd")
});
function AbrirPagModificacion(tipo){
	switch(tipo){
		case "normal": window.open("Formularios Modificacion/Modificar_liquidacion.php", "_blank");break;
		case "moratoria":  window.open("Formularios Modificacion/Modificar_liquidacionMoratoria.php", "_blank");break;
		case "incendio":  window.open("Formularios Modificacion/Modificar_liquidacionIncendio.php", "_blank");break;
		case "electromecanico":  window.open("Formularios Modificacion/Modificar_liquidacionElectromecanico.php", "_blank");break;
		case "demolicion":  window.open("Formularios Modificacion/Modificar_liquidacionDemolicion.php", "_blank");break;
		case "carteles":  window.open("Formularios Modificacion/Modificar_liquidacionCarteles-Antena.php", "_blank");break;
		case "art126":  window.open("Formularios Modificacion/Modificar_liquidacionArt126.php", "_blank");break;
		case "art13ter":  window.open("Formularios Modificacion/Modificar_liquidacionArt13.php", "_blank");break;
	}
}
function modificarRegistro(boton){
	//Verifico si el radio esta seleccionado.
	if($("input[type=radio]:checked").prop("checked")){
		var fecha = "";
		var cliente = "";
		var tipo = "";
		var zona = "";
		var descuento = "";
		var total = "";
		$.each(ListLiquidaciones, function(i,liq){
			if(liq.Id == $("input[type=radio]:checked").val()){
				id_registro = liq.Id;
				fecha = liq.FechaLiquidacion;
				cliente = liq.RazonSocial;
				tipo = liq.Tipo;
				zona = liq.Zonificacion;
				descuento = liq.Descuento;
				total = liq.Total;
			}
		});
		if(boton == "Ver"){
			$("#tbModificacion").html();
			$.ajax({
				type: "POST",
				url: "php/obtenerDatosModificacion.php",
				data: "id_registro="+id_registro+"&fecha="+fecha+"&zona="+zona+"&descuento="+descuento+"&total="+total+"&cliente="+cliente+"&tipo="+tipo+"&boton="+boton,
				dataType: "html",
			})
			.fail(function(data){
				alert("error petición ajax");
			})
			.done(function(data){
				$("#tbModificacion").html(data);
				AbrirPagModificacion(tipo);
			});
		}
		else{
			$("#tbModificacion").html();
			$.ajax({
				type: "POST",
				url: "php/obtenerDatosModificacion.php",
				data: "id_registro="+id_registro+"&fecha="+fecha+"&zona="+zona+"&descuento="+descuento+"&total="+total+"&cliente="+cliente+"&tipo="+tipo+"&boton="+boton,
				dataType: "html",
				error: function(){
					alert("error petición ajax");
				},
				success: function(data){
					$("#tbModificacion").html(data);
					AbrirPagModificacion(tipo);
				}
			});
		}
	}
}
function confirmarEliminacion(){
	if($("input[type=radio]:checked").prop("checked")){
		var fecha = "";
		var cliente = "";
		var tipo = "";
		var zona = "";
		var descuento = "";
		var total = "";
		$.each(ListLiquidaciones, function(i,liq){
			if(liq.Id == $("input[type=radio]:checked").val()){
				id_registro = liq.Id;
				fecha = liq.FechaLiquidacion;
				cliente = liq.RazonSocial;
				tipo = liq.Tipo;
				zona = liq.Zonificacion;
				descuento = liq.Descuento;
				total = liq.Total;
			}
		});
		$.confirm({
		    title: '¡ALERTA!',
		    containerFluid: true,
		    content: '¿SEGURO QUE DESEA ELIMINAR ESTE REGISTRO?'+
		    '<table border="1">'+
		    	'<thead>'+
		    		'<tr>'+
		    			'<th>Fecha</th>'+
		    			'<th>Cliente</th>'+
		    			'<th>Zonificacion</th>'+
		    			'<th>Tipo Liquidacion</th>'+
		    			'<th>Descuento</th>'+
		    			'<th>Total</th>'+
		    		'</tr>'+
		    	'</thead>'+
		    	'<tbody>'+
		    		'<tr>'+
		    			'<td>'+fecha+'</td>'+
		    			'<td>'+cliente+'</td>'+
		    			'<td>'+zona+'</td>'+
		    			'<td>'+tipo+'</td>'+
		    			'<td>'+descuento+'</td>'+
		    			'<td>'+total+'</td>'+
		    		'</tr>'+
		    	'</tbody>'+
		    '</table>',
		    type: 'red',
		    buttons: {
		        Confirmar: function () {
		        	$.confirm({
					    title: '¡ALERTA!',
					    content: 'UNA VEZ ELIMINADO NO SE PODRA RECUPERAR¿DESEA CONTINUAR?',
					    type: 'red',
					    buttons: {
					    	Confirmar: function(){
					    		eliminarRegistro(cliente,zona,tipo,descuento,total);
					    	},
					    	Cancelar: function () {
		            		},
					    }
					})
		        },
		        Cancelar: function () {
		            
		        },
		    }
		});
	}	
}
function eliminarRegistro(cliente,zona,tipo,descuento,total){
	//Verifico si el radio esta seleccionado.
	if($("input[type=radio]:checked").prop("checked")){
		$("#resultado").html();
		$.ajax({
			type: "POST",
			url: "controller/",
			data: "id_registro="+id_registro+"&tipoLiq="+tipo+"&cliente="+cliente+"&zona="+zona+"&descuento="+descuento+"&total="+total+"&pag="+document.title+"&tipo=d",
			dataType: "html",
		})
		.fail(function(data){
			alert("error petición ajax");
		})
		.done(function(data){
			$("#resultado").html(data);
			$("#resultado").slideDown();
		    setTimeout(function(){
		        $('#resultado').slideUp();
		    },3000);
			filtrarConsulta();
		});
	}
}
function obtenerRegistros(){
	$("#consultas").html();
	$.ajax({
		type: "POST",
		url: "controller/",
		data: "pag="+document.title+"&tipo=g",
		dataType: "html",
	})
	.fail(function(data){
		alert("error petición ajax");
	})
	.done(function(data){
		console.table(JSON.parse(data))
		DataTable.destroy();
		ListLiquidaciones = JSON.parse(data);
		displayTable();
	});
}
function filtrarConsulta(){
	var fechaDesde = document.getElementById('inpFechaDesde').value;
	var fechaHasta = document.getElementById('inpFechaHasta').value;
	var tipoConsulta = "liquidaciones";
	if (fechaDesde != "" || fechaHasta != ""){
		$("#consultas").html();
		$.ajax({
			type: "POST",
			url: "controller/",
			data: "fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&tipoConsulta="+tipoConsulta+'&pag='+document.title+'&tipo=gf',
			dataType: "html",
		})
		.fail(function(data){
			console.log(data)
			alert("error petición ajax");
		})
		.done(function(data){
			console.table(JSON.parse(data))
			DataTable.destroy();
			ListLiquidaciones = JSON.parse(data);
			displayTable();
		});
	}
	else{
		obtenerRegistros();
	}
}
//Funcion para obtener las auditorias
//Defino una variable para almacenar la pagina en la que estoy.
var pagina = 10;
var auditoria = false;
function ConsultasAuditorias(dato){
	var cantRegis = document.getElementById('inpCantAudit').value;
	var pagMax = cantRegis;
	if(auditoria == false){
		document.getElementById('inpFechaDesde').value="";
		document.getElementById('inpFechaHasta').value="";
	}
	if(auditoria == false || dato != ""){
		auditoria = true;
		document.getElementById('inpConsultarAuditoria').style.display = 'block';
		document.getElementById('cantRegLiquidaciones').style.display = 'none';
		document.getElementById('cantRegAuditorias').style.display = 'block';
		document.getElementById('thLiquidaciones').hidden = true;
		document.getElementById('thAuditorias').hidden = false;
		document.getElementById('btnnAntPag').style.display = 'block';
		document.getElementById('btnnSigPag').style.display = 'block';
		document.getElementById('inpConsultar').style.display = 'none';
		document.getElementById('bttnEliminar').style.display = 'none';
		document.getElementById('bttnModificar').style.display = 'none';
		document.getElementById('bttnVerConsulta').style.display = 'none';


		var fechaDesde = document.getElementById('inpFechaDesde').value;
		var fechaHasta = document.getElementById('inpFechaHasta').value;
		//Defino una variable para identificar el tipo de datos que me traera la consulta.
		var tipoConsulta = "auditorias";
		if(dato == "siguiente"){
			pagina = pagina + 10;
			if(pagina > pagMax){
				pagina = pagMax;
			}
		}
		else{
			if(dato == "anterior"){
				pagina = pagina - 10;
				if(pagina < 10){
					pagina = 10;
				}
			}
			else{
				pagina = 10;
			}
		}
		$(document).ready(function(){
			$("#consultas").queue(function(n) {
				$("#consultas").html();
				$.ajax({
					type: "POST",
					url: "php/obtenerRegistrosFiltrados.php",
					data: "fechaDesde="+fechaDesde+"&fechaHasta="+fechaHasta+"&tipoConsulta="+tipoConsulta+"&pagina="+pagina,
					dataType: "html",
					error: function(data){
						alert("error petición ajax");
					},
					success: function(data){
						$("#consultas").html(data);
						console.log(data)
						n();
					}
				});
			});
		});
	}
	else{
		document.getElementById('cantRegLiquidaciones').style.display = 'block';
		document.getElementById('cantRegAuditorias').style.display = 'none';
		document.getElementById('inpConsultar').style.display = 'block';
		document.getElementById('inpConsultarAuditoria').style.display = 'none';
		document.getElementById('thLiquidaciones').hidden = false;
		document.getElementById('thAuditorias').hidden = true;
		document.getElementById('inpFechaDesde').value="";
		document.getElementById('inpFechaHasta').value="";
		auditoria = false;
		filtrarConsulta();
	}
}
function descargarPDF(){
	var pagina = window.open("consultasPDF.php", "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=50%,left=50%,width=742,height=700");
}