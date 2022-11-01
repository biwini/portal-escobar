
//Funciones para todas las paginas
//Verificamos que el porcentaje maximo ingresado sea 100

$(document).on('keyup','.porcentaje', function(e) {
	if($(this).val() > 100){
		$(this).val(100);
	}else if($(this).val() < 0){
		$(this).val(0)
	}
});

// Obtenemos la Hora local de la computadora
$(document).ready(function(){
	$("#hora").queue(function(n) {
		$("#hora").html();
		$.ajax({
			type: "POST",
			url: "php/hora.php",
			dataType: "html",
			error: function(){
				alert("error petición ajax");
			},
			success: function(data){
				$("#hora").html(data);
				n();
			}
		});
	});
});
//Solo numeros.
$(function(){
	$(document).on('keypress','.validanumericos', function(e){
		console.log(e.charCode)
		if(e.charCode >= 44 && e.charCode <= 57){
	        return this.value.replace(/\D/g, ",")
                    .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
	    }
	    return false;
	})
	.on("cut copy paste",function(e){
		e.preventDefault();
	});

});
//Seleccionar todo automaticamente.
$(document).ready(function(){
	$(document).on('focus','input[type=text]', function(){ 
		this.select();
	});
});

$(document).ready(function(){
	$(document).on('focus','input[type=number]', function(){   
	    if (this.value == 0) {  
			this.select();
		}
	});
});
//Fin de funciones para todas las paginas.
//Funcion para cambiar el valor de la zona.
function setearZona(Zona){
	//Identificacion del campo afectado y seteo con el nuevo valor para evitar errores al momento de cargar la liquidacion.
	switch(Zona){
		case "rural":
			$('input[name=inpRuralComp]').val("X");
			$('input[name=inpUrbana]').val("");
			$('input[name=inpResExtraUrb]').val("");
			$('input[name=inpClubCampo]').val("");
		break;
		case "urbana":
			$('input[name=inpRuralComp]').val("");
			$('input[name=inpUrbana]').val("X");
			$('input[name=inpResExtraUrb]').val("");
			$('input[name=inpClubCampo]').val("");
		break;
		case "extraUrb":
			$('input[name=inpRuralComp]').val("");
			$('input[name=inpUrbana]').val("");
			$('input[name=inpResExtraUrb]').val("X");
			$('input[name=inpClubCampo]').val("");
		break;
		case "club":
			$('input[name=inpRuralComp]').val("");
			$('input[name=inpUrbana]').val("");
			$('input[name=inpResExtraUrb]').val("");
			$('input[name=inpClubCampo]').val("X");
		break;
	}	
}
var filaNueva = 4;
function AgregarFilaLiqNormal(){
	var trs=$("#tblContratoColegio tr").length;
	if(trs<9){
		var newRow =
			"<tr id=\""+filaNueva+"\">"
				+"<td><input type=\"text\" name=\"inpFila"+filaNueva+"\" id=\"inpFila"+filaNueva+"\"></td>"
				+"<td>"
					+"<label><STRONG>$</STRONG></label>"
					+"<input min=\"0\" class=\"validanumericos\" type=\"number\" name=\"inpFila"+filaNueva+"Monto\" id=\"inpFila"+filaNueva+"Monto\" onchange=\"obtenerResultados('tblContratoColegio','fila"+filaNueva+"','pagLiquidacion')\" value=\"0\">"
				+"</td>"
				+"<td>"
					+"<input min=\"0\" class=\"validanumericos porcentaje\" min=\"0\" max=\"100\" type=\"number\" name=\"inpFila"+filaNueva+"Coef\" id=\"inpFila"+filaNueva+"Coef\" onchange=\"obtenerResultados('tblContratoColegio','fila"+filaNueva+"','pagLiquidacion')\" value=\"0\">"
					+"<label><STRONG>%</STRONG></label>"
				+"</td>"
				+"<td>"
					+"<input min=\"0\" class=\"validanumericos porcentaje\" min=\"0\" max=\"100\" type=\"number\" name=\"inpFila"+filaNueva+"Recargo\" id=\"inpFila"+filaNueva+"Recargo\" onchange=\"obtenerResultados('tblContratoColegio','fila"+filaNueva+"','pagLiquidacion')\" value=\"0\">"
					+"<label><STRONG>%</STRONG></label>"
				+"</td>"
				+"<td>"
					+"<label><STRONG>$</STRONG></label>"
				+"</td>"
				+"<td>"
					+"<label id=\"lblFila"+filaNueva+"Total\">0</label>"
				+"</td>"
			+"</tr>";
		$(newRow).appendTo("#tblContratoColegio tbody");
		filaNueva = filaNueva + 1;
	}
	$.cookie('cantFilas', filaNueva);
}
function QuitarFilaLiqNormal(){
	var trs=$("#tblContratoColegio tr").length;
	if(trs>4){
		// Eliminamos la ultima columna
		$("#tblContratoColegio tbody tr:last").remove();
		filaNueva = filaNueva - 1;
	}
	$.cookie('cantFilas', filaNueva);
}
function number_format (number, decimals, decPoint, thousandsSep) { // eslint-disable-line camelcase
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    if (('' + n).indexOf('e') === -1) {
      return +(Math.round(n + 'e+' + prec) + 'e-' + prec)
    } else {
      var arr = ('' + n).split('e')
      var sig = ''
      if (+arr[1] + prec > 0) {
        sig = '+'
      }
      return (+(Math.round(+arr[0] + 'e' + sig + (+arr[1] + prec)) + 'e-' + prec)).toFixed(prec)
    }
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec).toString() : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}
function SoloVista(){
	//funcion para impedir que impriman la pagina y no puedan cambiar la liquidacion
	$("input[type=text]").prop('disabled',true);
	$("input[type=number]").prop('disabled',true);
	$(".validanumericos").each(function(){
		val = $(this).val();
        $(this).val(number_format(val, 2, ',', '.'));
    });
    // window.print();
    // window.close();
}
//funcion para cambiar los valores de la cabezera desde el formulario de liquidacion.
function cambiarValor(header){
	if($("input[type=text].txtHeader").is(':disabled')){
		$("input[type=text].txtHeader").removeAttr('disabled');
		$("input[type=date]").removeAttr('disabled');
	}
	else{
		var fecha = document.getElementById('inpFecha').value;
		var nombre = document.getElementById('inpNombre').value;
		var rural = document.getElementById('inpRuralComp').value;
		var urbana = document.getElementById('inpUrbana').value;
		var residencial = document.getElementById('inpResExtraUrb').value;
		var club = document.getElementById('inpClubCampo').value;

		if(fecha != "" && nombre != ""){
			$("#mensajeCabezera").html("");
			if(rural != "" || urbana != "" || residencial != "" || club != ""){
				$("#mensajeCabezera").html("");
				$("input[type=text].txtHeader").attr('disabled',true);
				$("input[type=date]").attr('disabled',true);
				//obtengo el valor de los input para actualizar los valores
				var circ = document.getElementById('inpCirc').value;
				var seccion = document.getElementById('inpSeccion').value;
				var fraccion = document.getElementById('inpFraccion').value;
				var chacra = document.getElementById('inpChacra').value;
				var partida = document.getElementById('inpPartida').value;
				var quinta = document.getElementById('inpQuinta').value;
				var manzana = document.getElementById('inpManzana').value;
				var parcela = document.getElementById('inpParcela').value;
				var uf = document.getElementById('inpUF').value;
				if(header == "headerModificacion"){
					var pagina = "../php/actualizarVariablesSession.php";
				}
				else{
					var pagina = "php/actualizarVariablesSession.php";
				}
				
				$("#resultados").queue(function(n) {
					$("#resultados").html();
					$.ajax({
						type: "POST",
						url: pagina,
						data: "fecha="+fecha+"&nombre="+nombre+"&circ="+circ+"&seccion="+seccion+"&fraccion="+fraccion+"&chacra="+chacra+"&partida="+partida+"&quinta="+quinta+"&manzana="+manzana+"&parcela="+parcela+"&uf="+uf+"&rural="+rural+"&urbana="+urbana+"&residencial="+residencial+"&club="+club,
						dataType: "html",
						error: function(){
							alert("error petición ajax");
						},
						success: function(data){
							$("#resultados").html(data);
							n();
						}
					});
				});
				
			}
			else{
				$("#mensajeCabezera").html("Tiene que elejir una zona");
			}
		}
		else{
			$("#mensajeCabezera").html("No puede dejar los campos nombre o fecha vacios");
		}
	}
}
//funcion para Imprimir el pdf
function imprimirPDF(formulario){
	//oculto el menu para que no moleste al momento de la impresion.
	if(formulario == "FormModificacion"){
		$('#bttnCambiarValores').hide();
		document.getElementById('bttnPDF').style.display = 'none';
		//Codigo para imprimir en PDF.
		window.print();
		//Al terminar de imprimir me vuelve a mostrar el menu.
		if(formulario != "FormModificacion"){
			document.getElementById('menu').style.display = 'block';
		}
		$('#bttnCambiarValores').show();
		document.getElementById('bttnPDF').style.display = 'block';
	}
	else{
		$('#bttnCambiarValores').hide();
		document.getElementById('menu').style.display = 'none';
		document.getElementById('bttnPDF').style.display = 'none';
		document.getElementById('bttnCargar').style.display = 'none';
		//Codigo para imprimir en PDF.
		window.print();
		//Al terminar de imprimir me vuelve a mostrar el menu.
		document.getElementById('menu').style.display = 'block';
		$('#bttnCambiarValores').show();
		document.getElementById('bttnPDF').style.display = 'block';
		document.getElementById('bttnCargar').style.display = 'block';
	}
}
// function ajusarPorcentajes(idCampo){
// 	//Obtengo el valor del porcentaje escrito.
// 	var porcentaje = document.getElementById(idCampo).value;
// 	//si el porcentaje escrito es mayor a 100 le cambio el valor a 100.
// 	if (porcentaje > 100) {
// 		document.getElementById(idCampo).value = 100;
// 	}
// }
//Obtengo los valores de las tablas de la Pagina liquidacion Moratoria.
function actualizarPorcentajeCondonacion(){
	obtenerResultados('tblContratoColegio','contraColFila1','pagLiqMonita')
	obtenerResultados('tblContratoColegio','contraColFila2','pagLiqMonita');
	obtenerResultados('tblContratoColegio','contraColFila3','pagLiqMonita');
	obtenerResultados('tblContratoColegio','contraColFila4','pagLiqMonita');
	obtenerResultados('tblContratoColegio','contraColFila5','pagLiqMonita');
	obtenerResultados('tblMultas','filaFOS','pagLiqMonita');
	obtenerResultados('tblMultas','filaFOT','pagLiqMonita');
	obtenerResultados('tblMultas','filaRetiros','pagLiqMonita');
	obtenerResultados('tblMultas','filaDencidad','pagLiqMonita');
	obtenerResultados('tblMultas','filaDTO','pagLiqMonita');
	//Voy a la funcion para Actualizar el total de las Condonaciones
	actualizarTotalCondonaciones();
}
//Actualizo el total de las Condonaciones
function actualizarTotalCondonaciones(){
	//Obtengo el valor del porcentaje escrito.
	var porcentaje = document.getElementById('lblPorcentaje').value;
	//si el porcentaje escrito es mayor a 100 le cambio el valor a 100.
	if (porcentaje > 100) {
		document.getElementById('lblPorcentaje').value = 100;
		porcentaje = 100;
		for(var i=1; i <= 10;i++){
			document.getElementById('porcenCon'+i).innerHTML = porcentaje;
		}
	}
	for(var i=1; i <= 10;i++){
		document.getElementById('porcenCon'+i).innerHTML = porcentaje;
	}
}
//variables globales que utilizaria para guardar los totales de la tabla liquidacion de la pagina "Carteles-Antena","Incendio" y "Electromecanica".
var totalHasta50 = 0;
var totalExedenteH50 = 0;
var totalHasta25 = 0;
var totalExedenteH25 = 0;
//Funcion para calcular la liquidacion de la pagina "Carteles-Antena","Incendio" y "Electromecanica".
function calcularLiquidacion(idFila,idPag){
	//si el id de la pagina es "pagLiqCarteles" calcula su total.
	if(idPag == "pagLiqCarteles"){
		// Obtengo los valores de los campos de la tabla Liquidacion.
		var monto = parseFloat(document.getElementById('inpMonto').value);
		var coef = parseFloat(document.getElementById('inpCoef').value);
		var recargo = parseFloat(document.getElementById('inpRecargo').value);
		// Calculo el total.
		var total = (monto*(coef/100))+(recargo/100)*(monto*(coef/100));
		// Escribo el total el los label correspondientes.
		document.getElementById('lblTotalLiquidacion').innerHTML=number_format(total, 2, ',', '.');
		document.getElementById('lblTotalAbonar').innerHTML=number_format(total, 2, ',', '.');
	}
	else{
		//si el id de la pagina es "pagLiqElec" calcula su total.
		if (idPag == "pagLiqElec"){
			//Dependiendo de la fila en la que este ingresando los datos va a ir a una funcion para calcular el total de esa fila.
			//Luego guardo el total en una variable global correspondiente para esa fila(variables de la linea "62","63","64","65").
			switch(idFila){
				case "filaHasta50": totalHasta50 = calcularTotalLiquidacion(idFila);break;
				case "filaExedenteH50": totalExedenteH50 = calcularTotalLiquidacion(idFila);break;
				case "filaHasta25": totalHasta25 = calcularTotalLiquidacion(idFila);break;
				case "filaExedenteH25": totalExedenteH25 = calcularTotalLiquidacion(idFila);break;
			}
			// Calculo para calcular el total a Abonar.
			var total = totalHasta50 + totalExedenteH50 + totalHasta25 + totalExedenteH25;
			// Escribo el total en el label correspondiente.
			document.getElementById('lblTotalAbonar').innerHTML=number_format(total, 2, ',', '.');
		}
		else{
			//si el id de la pagina es "pagIncendio" calcula su total.
			if (idPag == "pagIncendio"){
				// Obtengo los valores de los campos de la tabla Liquidacion.
				var m2 = parseFloat(document.getElementById('inpM2').value);
				var capIX = parseFloat(document.getElementById('inpCapIX').value);
				// Calculo el total.
				var total = m2 * capIX;
				// Escribo el total en el label correspondiente.
				document.getElementById('lblTotalLiquidacion').innerHTML=number_format(total, 2, ',', '.');
				document.getElementById('lblTotalAbonar').innerHTML=number_format(total, 2, ',', '.');
			}
			else{
				//si el id de la pagina es "pagDemolicion" calcula su total.
				if (idPag == "pagDemolicion"){
					var m2 = parseFloat(document.getElementById('inpM2').value);
					var $xm2 = parseFloat(document.getElementById('inp$xm2').value);


				}
			}
		}
	}
}

//funcion para calcular el total de la fila de la pagina "LiquidacionElectromecanica" (ver linea 86 hasta 89).
function calcularTotalLiquidacion(idFila){
	// Dependiendo de la fila en la que este escribiendo le asigna el valor a unas variables locales.
	switch(idFila){
		case "filaHasta50":
			var m2 = parseFloat(document.getElementById('inpHasta50M2').value);
			var capIX = parseFloat(document.getElementById('inpHasta50CapIX').value);
			var idFilaTotal ='lblHasta50Total';
		break;
		case "filaExedenteH50":
			var m2 = parseFloat(document.getElementById('inpExedente50M2').value);
			var capIX = parseFloat(document.getElementById('inpExedente50CapIX').value);
			var idFilaTotal = 'lblExedente50Total';
		break;
		case "filaHasta25":
			var m2 = parseFloat(document.getElementById('inpHasta25M2').value);
			var capIX = parseFloat(document.getElementById('inpHasta25CapIX').value);
			var idFilaTotal = 'lblHasta25Total';
		break;
		case "filaExedenteH25":
			var m2 = parseFloat(document.getElementById('inpExedente25M2').value);
			var capIX = parseFloat(document.getElementById('inpExedente25CapIX').value);
			var idFilaTotal = 'lblExedente25Total';
		break;
	}
	// Calculo para obtener el total de la fila.
	var total = m2 * capIX;
	// Muestra el total en el label de la fila correspondiente.
	document.getElementById(idFilaTotal).innerHTML=number_format(total, 2, ',', '.');
	// Retorno el total para asi guardarlo en la variable global correspondiente.
	return total;
}
// Fin de las funciones para Calcular El total de las liquidaziones de las paginas "Carteles-Antena","Incendio" y "Electromecanica".

// Variables globales para calcular el total de la pagina Liquidacion Art 126 y Art 13.
var totalCubierto = 0;
var totalSemiCub = 0;
var totalPileta = 0;
var montoTotal = 0;
// funciones de la pagina Liquidacion Art 126 y Art 13.
function calcularMontoObra(idTabla,idFila,idPag){
	//Dependiendo de la fila en la que este ingresando los datos va a ir a una funcion para calcular el total de esa fila.
	//Luego guardo el total en una variable global correspondiente para esa fila(variables de la linea "155","156","157","158").
	switch (idFila){
		case "filaCubierto":totalCubierto = calcularTotalFilaMonto(idFila); break;
		case "filaSemiCub":totalSemiCub = calcularTotalFilaMonto(idFila); break;
		case "filaPileta":totalPileta = calcularTotalFilaMonto(idFila); break;
	}
	// Dependiendo de la pagina en la que este calcula el monto total de la tabla contrato de colegio.
	switch (idPag){
		case "pagLiqArt126": montoTotal = totalCubierto + totalSemiCub + totalPileta; break;
		// En caso de que el idPag sea igual a pagLiqArt13 va a calcular  el monto total de la tabla contrato de colegio y luego va una funcion para calcuar el total a abonar
		case "pagLiqArt13":  montoTotal = totalCubierto + totalSemiCub; calcularTotalArt13(); break;
	}

	document.getElementById('lblTotalMontoObra').innerHTML=number_format(montoTotal, 2, ',', '.');
	document.getElementById('lblDeclararMonto').innerHTML=number_format(montoTotal, 2, ',', '.');
}
// Funcion para calcular el total de las filas de la tabla Contrato de colegio de la pagina Liquidacion Art 126 y Art 13 (ver lineas 164 a 166).
function calcularTotalFilaMonto(idFila){
	switch (idFila){
		// Dependiendo de la fila en la que este escribiendo obtiene sus valores y se lo asigna a unas variables locales.
		case "filaCubierto":
			var m2 = parseFloat(document.getElementById('inpCubiertoM2').value);
			var coef = parseFloat(document.getElementById('inpCubiertoCoef').value);
			var referencial = parseFloat(document.getElementById('inpCubiertoURef').value);
			var idfilaTotal = "lblCubiertoTotal";
		break;
		case "filaSemiCub":
			var m2 = parseFloat(document.getElementById('inpSemiCubM2').value);
			var coef = parseFloat(document.getElementById('inpSemiCubCoef').value);
			var referencial = parseFloat(document.getElementById('inpSemiCubURef').value);
			var idfilaTotal = "lblSemiCubTotal";
		break;
		case "filaPileta":
			var m2 = parseFloat(document.getElementById('inpPiletaM2').value);
			var coef = parseFloat(document.getElementById('inpPiletaCoef').value);
			var referencial = parseFloat(document.getElementById('inpPiletaURef').value);
			var idfilaTotal = "lblPiletaTotal";
		break;
	}
	// calculo para obtener el total de la fila.
	var total = (m2*coef)*referencial;
	// Escribe el total en el label correspondiente.
	document.getElementById(idfilaTotal).innerHTML=number_format(total, 2, ',', '.');
	// Retorno el total para asi guardarlo en la variable global correspondiente.
	return total;
}
// Funcion para calcular el Sub Total de la tabla Contrato de colegio de la pagina Liquidacion Art 126(A esta funcion vengo directamente desde la pagina).
function calcularSubTotalArt126(){
	// Calculo el monto.
	var monto = totalCubierto + totalSemiCub + totalPileta;
	// Obtengo los valores de las filas correspondientes.
	var coef = parseFloat(document.getElementById('inpDeclararCoef').value);
	var recargo = parseFloat(document.getElementById('inpDeclararRecargo').value);
	// Calculo el total de la tabla.
	var total = ((coef/100)*monto)+((recargo/100)*((coef/100)*monto));
	// Le asigno el total de la tabla a la variable global "subTotalA"(ver linea 389) ya que la utilizare luego para calcular el total a abonar.
	subTotalA = total;
	// Muestro por pantalla el total y el subtotal en los labels correspondientes.
	document.getElementById('lblDeclararTotal').innerHTML = number_format(total, 2, ',', '.');
	document.getElementById('lblSubTotalA').innerHTML = number_format(subTotalA, 2, ',', '.');
	obtenerResultados('','','pagLiqArt126')
}
// Funcion para Calcular el total de la pagina Liquidacion Art13(ver linea 172).
function calcularTotalArt13(){
	// Obtengo el valor de los campos de tabla.
	var capIX = parseFloat(document.getElementById('inpCapXI').value);
	// Calculo el total.
	var total = montoTotal * (capIX/100);
	// Escribo el total en los labels correspondientes.
	document.getElementById('lblArt13Total').innerHTML = number_format(total, 2, ',', '.');
	document.getElementById('lblTotalAbonar').innerHTML = number_format(total, 2, ',', '.');
}
//fin de las funciones de la pag Liquidacion Art 126 y Art13.

// Variables globales para calcular el total de las condonaciones de la pagina "Liquidacion Moratoria".
var totalCondonacionContrCol = 0;
var totalCondonacionMultas = 0;
// Inicio de las Funciones para obtener el total a abonar de las Paginas "Liquidacion Moratoria", "Liquidacion", "Liquidacion Art126".
function obtenerResultados(idTabla,idFila,idPag){
	// Dependiendo de la tabla en la que este va a una funcion correspondiente para calcular su total y le lleva el id de la fila y el id de la pagina.
	switch (idTabla){
		// funcion en linea 402.
		case "tblContratoColegio": contratoColegio(idFila,idPag);break;
		// funcion en linea 307.
		case "tblMultas": Multas(idFila,idPag);break;
	}
	// Si idPag es igual a pagLiqMonita ó pagLiquidacion.
	var descuento = 0;
	if(idPag == "pagLiqMonita" || idPag == "pagLiquidacion"){
		// defino una variable con el valor del descuento.
		var valorDescuento = 100;
		//Obtengo el porcentaje de descuento escrito.
		porcentajeDescuento = parseFloat(document.getElementById('inpDescuento').value);
		// si es mayor a 100 le cambio el valor a 100.
		if (porcentajeDescuento > 100) {
			document.getElementById('inpDescuento').value = 100;
			porcentajeDescuento = 100;
		}
		//Obtengo el descuento restando los valores de "valorDescuento" y "porcentajeDescuento"
		descuento = valorDescuento - porcentajeDescuento;
	}
	// Dependiendo de la pagina en la que este me va a calcular el sub Total y el total a Abonar.
	switch (idPag){
		case "pagLiqMonita":
			if(totalCondonacionMultas == "-"){
				totalCondonacionMultas = "";
			}
			else{
				if (totalCondonacionContrCol == "-") {
					totalCondonacionContrCol = "";
				}
			}
			// Calculo el total A + B y el Total a Abonar.
			var TotalAB = totalCondonacionContrCol + totalCondonacionMultas;
			var totalAbonar = TotalAB * (descuento / 100);
			
			document.getElementById('lblSubTotalAB').innerHTML=number_format(TotalAB, 2, ',', '.');
			document.getElementById('lblTotalAbonar').innerHTML=number_format(totalAbonar, 2, ',', '.');
		break;
		case "pagLiquidacion":
			// Calculo el total A + B y el Total a Abonar.
			var TotalAB = subTotalA + subTotalB;
			var totalAbonar = TotalAB * (descuento / 100);
			document.getElementById('lblSubTotalAB').innerHTML=number_format(TotalAB, 2, ',', '.');
			document.getElementById('lblTotalAbonar').innerHTML=number_format(totalAbonar, 2, ',', '.');
		break;
		case "pagLiqArt126":
			// Calculo el total A + B
			var TotalAB = subTotalA + subTotalB;
			document.getElementById('lblSubTotalAB').innerHTML=number_format(TotalAB, 2, ',', '.');
			document.getElementById('lblTotalAbonar').innerHTML=number_format(TotalAB, 2, ',', '.');
		break;
	}
}
// Fin de las Funciones para obtener el total a abonar de las Paginas "Liquidacion Moratoria", "Liquidacion", "Liquidacion Art126".

//inicio de las funciones utilizadas para calcular las multas.
//variables globales utilizadas para almacenar los totales de las multas.
var subTotalB = 0;
var totalFos = 0;
var totalFot = 0;
var totalRetiro = 0;
var totalDensidad= 0;
var totalDTO = 0;
//funcion para Calcular el total las multas (ver linea 246).
function Multas(idFila,idPag){
	// Dependiendo de la fila en la que este voy a una funcion en la que me calcula el total de la fila  y se lo asigno a una varible global 
	switch (idFila){
		case "filaFOS":totalFos = CalcularTotalMulta(idFila,idPag);break;
		case "filaFOT":totalFot = CalcularTotalMulta(idFila,idPag);break;
		case "filaRetiros":totalRetiro = CalcularTotalMulta(idFila,idPag);break;
		case "filaDencidad":totalDensidad = CalcularTotalMulta(idFila,idPag);break;
		case "filaDTO":totalDTO = CalcularTotalMulta(idFila,idPag);break;
	}
	// Calculo el Sub total B.
	subTotalB = totalFos + totalFot + totalRetiro + totalDensidad + totalDTO;
	//Muestro el SubTotalB en el label correspondiente.
	document.getElementById('lblSubTotalB').innerHTML=number_format(subTotalB, 2, ',', '.');
}
// Variables globales para Calcular las Condonaciones.
var filaMultaCon1 = 0;
var filaMultaCon2 = 0;
var filaMultaCon3 = 0;
var filaMultaCon4 = 0;
var filaMultaCon5 = 0;
function CalcularTotalMulta(idFila,idPag){
	var total = 0;
	// Dependiendo de la fila en la que este obtengo los valores de los elementos en esa fila y los asigno a variables locales.
	switch (idFila){
		case "filaFOS":
			var m2 = parseFloat(document.getElementById("inpFOSm2").value);
			var cant = parseFloat(document.getElementById("inpFOSCant").value);
			var SMMunicipal = parseFloat(document.getElementById("inpFOSSMMun").value);
			var porcentaje = parseFloat(document.getElementById("inpFOSPorcentaje").value);
			var idCeldaTotal = "lblFOSTotal";
			var totalCondonacionFila = "totalMultaFila1";
		break;
		case "filaFOT":
			var m2 = parseFloat(document.getElementById("inpFOTM2").value);
			var cant = parseFloat(document.getElementById("inpFOTCant").value);
			var SMMunicipal = parseFloat(document.getElementById("inpFOTSMMun").value);
			var porcentaje = parseFloat(document.getElementById("inpFOTPorcentaje").value);
			var idCeldaTotal = "lblFOTTotal";
			var totalCondonacionFila = "totalMultaFila2";
		break;
		case "filaRetiros":
			var m2 = parseFloat(document.getElementById("inpRetirosM2").value);
			var cant = parseFloat(document.getElementById("inpRetirosCant").value);
			var SMMunicipal = parseFloat(document.getElementById("inpRetirosSMMun").value);
			var porcentaje = parseFloat(document.getElementById("inpRetirosPorcentaje").value);
			var idCeldaTotal = "lblRetirosTotal";
			var totalCondonacionFila = "totalMultaFila3";
		break;
		case "filaDencidad":
			var m2 = parseFloat(document.getElementById("inpDencidadM2").value);
			var cant = parseFloat(document.getElementById("inpDencidadCant").value);
			var SMMunicipal = parseFloat(document.getElementById("inpDencidadSMMun").value);
			var porcentaje = parseFloat(document.getElementById("inpDencidadPorcentaje").value);
			var idCeldaTotal = "lblDencidadTotal";
			var totalCondonacionFila = "totalMultaFila4";
		break;
		case "filaDTO":
			var m2 = parseFloat(document.getElementById("inpDtoM2").value);
			var cant = parseFloat(document.getElementById("inpDtoCant").value);
			var SMMunicipal = parseFloat(document.getElementById("inpDtoSMMun").value);
			var porcentaje = parseFloat(document.getElementById("inpDtoPorcentaje").value);
			var idCeldaTotal = "lblDtoTotal";
			var totalCondonacionFila = "totalMultaFila5";
		break;
	}
	// Calculo el total de la Fila.
	total = ((cant*SMMunicipal)*m2)*(porcentaje / 100);
	// Escribo el total en el label correspondiente.
	document.getElementById(idCeldaTotal).innerHTML=number_format(total, 2, ',', '.');

	// Si el id de la pagina es pagLiqMonita(Liquidacion Moratoria) calculo las condonaciones.
	if(idPag == "pagLiqMonita"){

		//obtenemos el valor del porcentaje de la condonacion.
		var porcentajeCondonacion = parseFloat(document.getElementById("lblPorcentaje").value);
		//calculamos el total de la condonacion.
		var totalCondonacion = total-(((porcentaje / 100)*((SMMunicipal*cant)*m2))*(porcentajeCondonacion/100));
		//Mostramos el valor total en un label correspondiente para cada fila.
		document.getElementById(totalCondonacionFila).innerHTML=number_format(totalCondonacion, 2, ',', '.');
		// Dependiendo de la Fila de la Condonacion 
		switch (totalCondonacionFila){
			case "totalMultaFila1" : filaMultaCon1 = totalCondonacion; break;
			case "totalMultaFila2" : filaMultaCon2 = totalCondonacion; break;
			case "totalMultaFila3" : filaMultaCon3 = totalCondonacion; break;
			case "totalMultaFila4" : filaMultaCon4 = totalCondonacion; break;
			case "totalMultaFila5" : filaMultaCon5 = totalCondonacion; break;
		}
		// Calculo el SubTotal de la Condonacion.
		var subTotalBCondonacion = filaMultaCon1 + filaMultaCon2 + filaMultaCon3 + filaMultaCon4 + filaMultaCon5;
		// Escribo el Sub Total en el label correspondiente.
		document.getElementById('condonacionMultas').innerHTML=number_format(subTotalBCondonacion, 2, ',', '.');
		totalCondonacionMultas = subTotalBCondonacion;
	}
	// Retorno el total de la fila.
	return total;
}
//Fin de las funciones utilizadas para calcular las multas.

//variables globales utilizadas para almacenar los totales de la tabla "contrato de colegio".
var subTotalA = 0;
var totalNuevo = 0;
var totalDemoler = 0;
var totalDeclarar = 0;
var totalContraFila4 = 0;
var totalContraFila5 = 0;
var totalContraFila6 = 0;
var totalFila4 = 0;
var totalFila5 = 0;
//funciones para Calcular el contrato de colegio de todas las paginas.
function contratoColegio(idFila,idPag){
	switch (idFila){
		case "filaNuevo":totalNuevo = CalcularTotalContratoColegio(idFila,idPag);break;
		case "filaDemoler":totalDemoler = CalcularTotalContratoColegio(idFila,idPag);break;
		case "filaDeclarar":totalDeclarar = CalcularTotalContratoColegio(idFila,idPag);break;
		case "fila4":totalContraFila4 = CalcularTotalContratoColegio(idFila,idPag);break;
		case "fila5":totalContraFila5 = CalcularTotalContratoColegio(idFila,idPag);break;
		case "fila6":totalContraFila6 = CalcularTotalContratoColegio(idFila,idPag);break;
		//Para el contrato de colegio de la pagina Liquidacion Moratoria Utilizo las mismas 3 variables que utilizo en la pagina Liquidacion.
		case "contraColFila1": totalNuevo = CalcularTotalContratoColegio(idFila,idPag);break;
		case "contraColFila2": totalDemoler = CalcularTotalContratoColegio(idFila,idPag);break;
		case "contraColFila3": totalDeclarar = CalcularTotalContratoColegio(idFila,idPag);break;
		case "contraColFila4" : totalFila4 =CalcularTotalContratoColegio(idFila,idPag);break;
		case "contraColFila5" : totalFila5 =CalcularTotalContratoColegio(idFila,idPag);break;
	}
	// si el idPag es igual "pagLiqMonita" calcula el subTotal A y lo escribo en el label correspondiente.
	if (idPag == "pagLiqMonita") {
		subTotalA = totalNuevo + totalDemoler + totalDeclarar + totalFila4 +totalFila5;
		document.getElementById('lblSubTotalA').innerHTML=number_format(subTotalA, 2, ',', '.');
	}
	// Sino Calculo el Subtotal A y lo escribo en el label correspondiente
	else{
		subTotalA = totalNuevo + totalDemoler + totalDeclarar + totalContraFila4 + totalContraFila5 + totalContraFila6;
		document.getElementById('lblSubTotalA').innerHTML=number_format(subTotalA, 2, ',', '.');
	}
}
// Variables globales para almacenar el total de las condonaciones del contrato de colegio.
var fila1 = 0, fila2 = 0, fila3 = 0, fila4 = 0, fila5 = 0;
function CalcularTotalContratoColegio(idFila,idPag){
	var total;
	// Dependiendo de la fila en la que este obtengo los valores de los elementos en esa fila y los asigno a variables locales.
	switch (idFila){
		case "filaNuevo":
				var Monto = parseFloat(document.getElementById('inpNuevoMonto').value);
				var Coef = parseFloat(document.getElementById('inpNuevoCoef').value);
				var Recargo =parseFloat(document.getElementById('inpNuevoRecargo').value);
				var idCeldaTotal = "lblNuevoTotal";
		break;
		case "fila4":
				var Monto = parseFloat(document.getElementById('inpFila4Monto').value);
				var Coef = parseFloat(document.getElementById('inpFila4Coef').value);
				var Recargo =parseFloat(document.getElementById('inpFila4Recargo').value);
				var idCeldaTotal = "lblFila4Total";
		break;
		case "fila5":
				var Monto = parseFloat(document.getElementById('inpFila5Monto').value);
				var Coef = parseFloat(document.getElementById('inpFila5Coef').value);
				var Recargo =parseFloat(document.getElementById('inpFila5Recargo').value);
				var idCeldaTotal = "lblFila5Total";
		break;
		case "fila6":
				var Monto = parseFloat(document.getElementById('inpFila6Monto').value);
				var Coef = parseFloat(document.getElementById('inpFila6Coef').value);
				var Recargo =parseFloat(document.getElementById('inpFila6Recargo').value);
				var idCeldaTotal = "lblFila6Total";
		break;
		case"contraColFila1":
				var Monto =parseFloat(document.getElementById('inpMontoFila1').value);
				var Coef = parseFloat(document.getElementById('inpCoefFila1').value);
				var Recargo =parseFloat(document.getElementById('inpRecargoFila1').value);
				var idCeldaTotal = "lblTotalFila1";
				var fila = "totalContraColFila1";
		break;
		case "filaDemoler":
				var Monto =parseFloat(document.getElementById('inpDemolerNuevo').value);
				var Coef = parseFloat(document.getElementById('inpDemolerCoef').value);
				var Recargo =parseFloat(document.getElementById('inpDemolerRecargo').value);
				var idCeldaTotal = "lblDemolerTotal";
		break;
		case "contraColFila2":
				var Monto =parseFloat(document.getElementById('inpMontoFila2').value);
				var Coef = parseFloat(document.getElementById('inpCoefFila2').value);
				var Recargo =parseFloat(document.getElementById('inpRecargoFila2').value);
				var idCeldaTotal = "lblTotalFila2";
				var fila = "totalContraColFila2";
		break;
		case "filaDeclarar":
				var Monto =parseFloat(document.getElementById('inpDeclararNuevo').value);
				var Coef = parseFloat(document.getElementById('inpDeclararCoef').value);
				var Recargo =parseFloat(document.getElementById('inpDeclararRecargo').value);
				var idCeldaTotal = "lblDeclararTotal";
		break;
		case "contraColFila3":
				var Monto =parseFloat(document.getElementById('inpMontoFila3').value);
				var Coef = parseFloat(document.getElementById('inpCoefFila3').value);
				var Recargo =parseFloat(document.getElementById('inpRecargoFila3').value);
				var idCeldaTotal = "lblTotalFila3";
				var fila = "totalContraColFila3";
		break;
		case "contraColFila4":
			var Monto =parseFloat(document.getElementById('inpMontoFila4').value);
			var Coef = parseFloat(document.getElementById('inpCoefFila4').value);
			var Recargo =parseFloat(document.getElementById('inpRecargoFila4').value);
			var idCeldaTotal = "lblTotalFila4";
			var fila = "totalContraColFila4";
		break;
		case "contraColFila5":
			var Monto =parseFloat(document.getElementById('inpMontoFila5').value);
			var Coef = parseFloat(document.getElementById('inpCoefFila5').value);
			var Recargo =parseFloat(document.getElementById('inpRecargoFila5').value);
			var idCeldaTotal = "lblTotalFila5";
			var fila = "totalContraColFila5";
		break;
	}
	// Si los valores que obtengo son distintos de null calculo el total y los muestro por pantalla.
	if(Monto != null && Coef != null && Recargo != null){
		total = ((Coef / 100)*Monto)+((Recargo / 100)*((Coef / 100)*Monto));
		document.getElementById(idCeldaTotal).innerHTML=number_format(total, 2, ',', '.');
	}
	else{
		if (Monto != null && Coef != null) {
			total = Monto * (Coef / 100);
			document.getElementById(idCeldaTotal).innerHTML=number_format(total, 2, ',', '.');
		}
		else{
			if (Monto != null) {
				total = Monto;
				document.getElementById(idCeldaTotal).innerHTML=number_format(total, 2, ',', '.');
			}
		}
	}
	// Si el idPag es igual a "pagLiqMonita".
	if (idPag == "pagLiqMonita"){
		//H34-((F34*(E34*C34))*J34)
		// Obtiene el valor del porcentaje.
		var porcentaje = parseFloat(document.getElementById("lblPorcentaje").value);
		// Calculo el total de Fila.
		var totalFila = total-(((Recargo / 100)*((Coef / 100)*Monto)) * (porcentaje / 100));
		// Escribo el total de la fila en el label correspondiente.
		document.getElementById(fila).innerHTML=number_format(totalFila, 2, ',', '.');

		switch (fila){
			case "totalContraColFila1" : fila1 = totalFila; break;
			case "totalContraColFila2" : fila2 = totalFila; break;
			case "totalContraColFila3" : fila3 = totalFila; break;
			case "totalContraColFila4" : fila4 = totalFila; break;
			case "totalContraColFila5" : fila5 = totalFila; break;
		}
		//Calculo el subTotalB de la Condonacion de contrato de colegio.
		var subTotalACondonacion = fila1 + fila2 + fila3 + fila4 + fila5;
		//Muestro por pantalla el total de la condonacion en el label correspondiente.
		document.getElementById('condonacionContraCol').innerHTML=number_format(subTotalACondonacion, 2, ',', '.');
		//le asigno el SubtotalACondonacion a la variable global totalCondonacionContrCol.
		totalCondonacionContrCol = subTotalACondonacion;
	}
	// Retorno el total de la fila.
	return total;
}