// ES MUY IMPORTANTE NO TOCAR SIN SABER ESTA PARTE DEL CODIGO DEBIDO A QUE SINO LA CARGA DE LIQUIDACION PODRIA FALLAR DRASTICAMENTE.

var antLiquidacion = 0;
var cargarLiquidacion = true;
var incomplete = false;
function CargarLiquidacion_Normal_Mora_Art126(idPag){
	$(function(){
		incomplete = false;
		//Variable para saber si es una modificacion o no.
		var Modificacion = false;
		if(idPag == "ModificarLiquidacion"){
			Modificacion = true;
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('headerModificacion');
			}
		}else{
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('');
			}
		}
		//Valores Contrato Colegio.
		if(idPag == "liquidacion" || idPag == "ModificarLiquidacion"){
			var tipoMonto1 = document.getElementById('inpFila1').value;
			var nuevoMonto =document.getElementById('inpNuevoMonto').value;
			var nuevoCoef = document.getElementById('inpNuevoCoef').value;
			var nuevoRecargo =document.getElementById('inpNuevoRecargo').value;
			var nuevoTotal = document.getElementById('lblNuevoTotal').innerHTML;

			var arrayNuevoContrato = [nuevoMonto,nuevoCoef,nuevoRecargo,nuevoTotal,tipoMonto1];

			var tipoMonto2 = document.getElementById('inpFila2').value;
			var demolerMonto =document.getElementById('inpDemolerNuevo').value;
			var demolerCoef = document.getElementById('inpDemolerCoef').value;
			var demolerRecargo =document.getElementById('inpDemolerRecargo').value;
			var demolerTotal = document.getElementById('lblDemolerTotal').innerHTML;

			var arraydemolerContrato = [demolerMonto,demolerCoef,demolerRecargo,demolerTotal,tipoMonto2];

			var tipoMonto3 = document.getElementById('inpFila3').value;
			var declararMonto =document.getElementById('inpDeclararNuevo').value;
			var declararCoef = document.getElementById('inpDeclararCoef').value;
			var declararRecargo =document.getElementById('inpDeclararRecargo').value;
			var declararTotal = document.getElementById('lblDeclararTotal').innerHTML;

			var arrayDeclararContrato = [declararMonto,declararCoef,declararRecargo,declararTotal,tipoMonto3];

			var cantFilas=$("#tblContratoColegio tbody tr").length;
			
			if(cantFilas >= 4){
				for (var i = cantFilas; i >= 4; i--) {
					var inpFila = "inpFila"+ i;
					var tipoMonto = document.getElementById(inpFila).value;
					var filaMonto =document.getElementById(inpFila+'Monto').value;
					var filaCoef = document.getElementById(inpFila+'Coef').value;
					var filaRecargo =document.getElementById(inpFila+'Recargo').value;
					var filaTotal = document.getElementById('lblFila'+i+'Total').innerHTML;

					if(tipoMonto == "" && filaTotal != 0){
						incomplete = true;
					}

					switch(i){
						case 4: var arrayFila4Contrato = [filaMonto,filaCoef,filaRecargo,filaTotal,tipoMonto]; ;break;
						case 5: var arrayFila5Contrato = [filaMonto,filaCoef,filaRecargo,filaTotal,tipoMonto]; ;break;
						case 6: var arrayFila6Contrato = [filaMonto,filaCoef,filaRecargo,filaTotal,tipoMonto]; ;break;
					}
				}
				switch(cantFilas){
					case 4: var arrayContrato = [arrayNuevoContrato,arraydemolerContrato,arrayDeclararContrato,arrayFila4Contrato];break;
					case 5: var arrayContrato = [arrayNuevoContrato,arraydemolerContrato,arrayDeclararContrato,arrayFila4Contrato,arrayFila5Contrato];break;
					case 6: var arrayContrato = [arrayNuevoContrato,arraydemolerContrato,arrayDeclararContrato,arrayFila4Contrato,arrayFila5Contrato,arrayFila6Contrato];break;
				}
			}
			else{
				var arrayContrato = [arrayNuevoContrato,arraydemolerContrato,arrayDeclararContrato];
			}
			if((tipoMonto1 == "" && nuevoTotal != 0) || (tipoMonto2 == "" && demolerTotal != 0) || (tipoMonto3 == "" && declararTotal != 0)){
				incomplete = true;
			}
		}
		else{
			if (idPag == "liquidacionMoratoria" || idPag == "ModificarLiquidacionMoratoria"){
				var porcentaje = document.getElementById('lblPorcentaje').value;
				
				var porcenCondonacion = document.getElementById('porcenCon1').innerHTML;

				var MontoFila1 =document.getElementById('inpMontoFila1').value;
				var CoefFila1 = document.getElementById('inpCoefFila1').value;
				var RecargoFila1 =document.getElementById('inpRecargoFila1').value;
				var totalFila1 = document.getElementById('lblTotalFila1').innerHTML;
				var totalCondFila1 = document.getElementById('totalContraColFila1').innerHTML;

				var arrayContratoFila1 = [MontoFila1,CoefFila1,RecargoFila1,totalFila1,totalCondFila1];

				var MontoFila2 =document.getElementById('inpMontoFila2').value;
				var CoefFila2 = document.getElementById('inpCoefFila2').value;
				var RecargoFila2 =document.getElementById('inpRecargoFila2').value;
				var TotalFila2 = document.getElementById('lblTotalFila2').innerHTML;
				var totalCondFila2 = document.getElementById('totalContraColFila2').innerHTML;

				var arrayContratoFila2 = [MontoFila2,CoefFila2,RecargoFila2,TotalFila2,totalCondFila2];

				var MontoFila3 =document.getElementById('inpMontoFila3').value;
				var CoefFila3 = document.getElementById('inpCoefFila3').value;
				var RecargoFila3 =document.getElementById('inpRecargoFila3').value;
				var TotalFila3 = document.getElementById('lblTotalFila3').innerHTML;
				var totalCondFila3 = document.getElementById('totalContraColFila3').innerHTML;

				var arrayContratoFila3 = [MontoFila3,CoefFila3,RecargoFila3,TotalFila3,totalCondFila3];

				var MontoFila4 =document.getElementById('inpMontoFila4').value;
				var CoefFila4 = document.getElementById('inpCoefFila4').value;
				var RecargoFila4 =document.getElementById('inpRecargoFila4').value;
				var TotalFila4 = document.getElementById('lblTotalFila4').innerHTML;
				var totalCondFila4 = document.getElementById('totalContraColFila4').innerHTML;

				var arrayContratoFila4 = [MontoFila4,CoefFila4,RecargoFila4,TotalFila4,totalCondFila4];

				var MontoFila5 =document.getElementById('inpMontoFila5').value;
				var CoefFila5 = document.getElementById('inpCoefFila5').value;
				var RecargoFila5 =document.getElementById('inpRecargoFila5').value;
				var TotalFila5 = document.getElementById('lblTotalFila5').innerHTML;
				var totalCondFila5 = document.getElementById('totalContraColFila5').innerHTML;

				var arrayContratoFila5 = [MontoFila5,CoefFila5,RecargoFila5,TotalFila5,totalCondFila5];

				var arrayContrato = [arrayContratoFila1,arrayContratoFila2,arrayContratoFila3,arrayContratoFila4,arrayContratoFila5];
				var totalCondonacion = document.getElementById('condonacionContraCol').innerHTML;
			}
			else{
				var cubiertom2 = document.getElementById('inpCubiertoM2').value;
				var cubiertocoef = document.getElementById('inpCubiertoCoef').value;
				var cubiertouref = document.getElementById('inpCubiertoURef').value;
				var cubiertoTotal = document.getElementById('lblCubiertoTotal').innerHTML;

				var arrayCubierto = [cubiertom2,cubiertocoef,cubiertouref,cubiertoTotal];

				var semiCubm2 = document.getElementById('inpSemiCubM2').value;
				var semiCubcoef = document.getElementById('inpSemiCubCoef').value;
				var semiCuburef = document.getElementById('inpSemiCubURef').value;
				var semiCubTotal = document.getElementById('lblSemiCubTotal').innerHTML;

				var arraySemiCub = [semiCubm2,semiCubcoef,semiCuburef,semiCubTotal];

				var piletam2 = document.getElementById('inpPiletaM2').value;
				var piletacoef = document.getElementById('inpPiletaCoef').value;
				var piletauref = document.getElementById('inpPiletaURef').value;
				var piletaTotal = document.getElementById('lblPiletaTotal').innerHTML;

				var arrayPileta = [piletam2,piletacoef,piletauref,piletaTotal];

				var DeclararMonto = document.getElementById('lblDeclararMonto').innerHTML;
				var Declararcoef = document.getElementById('inpDeclararCoef').value;
				var DeclararRecargo = document.getElementById('inpDeclararRecargo').value;
				var DeclararTotal = document.getElementById('lblDeclararTotal').innerHTML;

				var arrayDeclarar = [DeclararMonto,Declararcoef,DeclararRecargo,DeclararTotal];

				var arrayContrato = [arrayCubierto,arraySemiCub,arrayPileta,arrayDeclarar];
			}
		}
		// Valores Multas de las paginas "liquidacion", "liquidacion Moratoria" o "liquidacion Art126".
		var FosM2 = document.getElementById("inpFOSm2").value;
		var FosCant = document.getElementById("inpFOSCant").value;
		var FosSMMunicipal = document.getElementById("inpFOSSMMun").value;
		var FosPorcentaje = document.getElementById("inpFOSPorcentaje").value;
		var FosTotal = document.getElementById('lblFOSTotal').innerHTML;

		var FotM2 = document.getElementById("inpFOTM2").value;
		var FotCant = document.getElementById("inpFOTCant").value;
		var FotSMMunicipal = document.getElementById("inpFOTSMMun").value;
		var FotPorcentaje = document.getElementById("inpFOTPorcentaje").value;
		var FotTotal = document.getElementById('lblFOTTotal').innerHTML;

		var RetirosM2 = document.getElementById("inpRetirosM2").value;
		var RetirosCant = document.getElementById("inpRetirosCant").value;
		var RetirosSMMunicipal = document.getElementById("inpRetirosSMMun").value;
		var RetirosPorcentaje = document.getElementById("inpRetirosPorcentaje").value;
		var RetirosTotal = document.getElementById('lblRetirosTotal').innerHTML;

		var DencidadM2 = document.getElementById("inpDencidadM2").value;
		var DencidadCant = document.getElementById("inpDencidadCant").value;
		var DencidadSMMunicipal = document.getElementById("inpDencidadSMMun").value;
		var DencidadPorcentaje = document.getElementById("inpDencidadPorcentaje").value;
		var DencidadTotal = document.getElementById('lblDencidadTotal').innerHTML;

		var DtoM2 = document.getElementById("inpDtoM2").value;
		var DtoCant = document.getElementById("inpDtoCant").value;
		var DtoSMMunicipal = document.getElementById("inpDtoSMMun").value;
		var DtoPorcentaje = document.getElementById("inpDtoPorcentaje").value;
		var DtoTotal = document.getElementById('lblDtoTotal').innerHTML;

		if(idPag == "liquidacionMoratoria" || idPag == "ModificarLiquidacionMoratoria"){
			var totalCondMultaFila1 = document.getElementById('totalMultaFila1').innerHTML;
			var totalCondMultaFila2 = document.getElementById('totalMultaFila2').innerHTML;
			var totalCondMultaFila3 = document.getElementById('totalMultaFila3').innerHTML;
			var totalCondMultaFila4 = document.getElementById('totalMultaFila4').innerHTML;
			var totalCondMultaFila5 = document.getElementById('totalMultaFila5').innerHTML;
			var totalCondonacionMulta = document.getElementById('condonacionMultas').innerHTML;
			
			var arrayFosMultas = [FosM2,FosCant,FosSMMunicipal,FosPorcentaje,FosTotal,totalCondMultaFila1];
			var arrayFotMultas = [FotM2,FotCant,FotSMMunicipal,FotPorcentaje,FotTotal,totalCondMultaFila2];
			var arrayRetirosMultas = [RetirosM2,RetirosCant,RetirosSMMunicipal,RetirosPorcentaje,RetirosTotal,totalCondMultaFila3];
			var arrayDencidadMultas = [DencidadM2,DencidadCant,DencidadSMMunicipal,DencidadPorcentaje,DencidadTotal,totalCondMultaFila4];
			var arrayDtoMultas = [DtoM2,DtoCant,DtoSMMunicipal,DtoPorcentaje,DtoTotal,totalCondMultaFila5];
		}
		else{
			var arrayFosMultas = [FosM2,FosCant,FosSMMunicipal,FosPorcentaje,FosTotal];
			var arrayFotMultas = [FotM2,FotCant,FotSMMunicipal,FotPorcentaje,FotTotal];
			var arrayRetirosMultas = [RetirosM2,RetirosCant,RetirosSMMunicipal,RetirosPorcentaje,RetirosTotal];
			var arrayDencidadMultas = [DencidadM2,DencidadCant,DencidadSMMunicipal,DencidadPorcentaje,DencidadTotal];
			var arrayDtoMultas = [DtoM2,DtoCant,DtoSMMunicipal,DtoPorcentaje,DtoTotal];
		}

		var arrayMultas = [arrayFosMultas,arrayFotMultas,arrayRetirosMultas,arrayDencidadMultas,arrayDtoMultas];

		// Descuento, Total a Abonar.
		if(idPag == "liquidacion" || idPag == "liquidacionMoratoria" || idPag == "ModificarLiquidacion" || idPag == "ModificarLiquidacionMoratoria"){
			var descuento = document.getElementById('inpDescuento').value;
		}
		var totalAbonar = document.getElementById('lblTotalAbonar').innerHTML;
		if(totalAbonar != 0 && !incomplete){
			if(antLiquidacion == 0){
				antLiquidacion = totalAbonar;
			}
			else if(antLiquidacion == totalAbonar){
					cargarLiquidacion = confirm("Usted ya cargo una liquidacion con un mismo total, ¿esta seguro de que desea cargarla igualmente?");
			}
		}else{
			cargarLiquidacion = false;
		}
		//Las funciones ajax quizas se podrian simplificar añadiendo 2 variables nuevas llamadas "url" en donde guarde la url del destino y "data" en donde guarde los datos que voy a enviar
		//De esta manera tendria un solo ajax que funcionaria tanto como para la carga de la liquidacion y la modificacion de la misma.
		if(idPag == "ModificarLiquidacion"){
			if(!incomplete){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "../php/modificarLiquidacion.php",
							data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&descuento="+descuento+"&totalAbonar="+totalAbonar,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								window.close("Formularios Modificacion/Modificar_liquidacion.php");
								alert("Se Modifico el registro con exito");
								n();
							}
						});
					});
				});
			}else{
				$("#resultados").html("<div class='center-block' style='background-color: red'><span class='label center-block label-danger center-block' style='font-size:15px;'>El nombre del monto no puede estar vacio en la tabla de contrato de colegio.</span></div>");
				$("#resultados").show();
				$("#resultados").fadeOut(5000);
			}
		}

		if(idPag == "liquidacion"){
			if(!incomplete){
				if(cargarLiquidacion){
					$(document).ready(function(){
						$("#resultados").queue(function(n) {
							$("#resultados").html();
							$.ajax({
								type: "POST",
								url: "php/cargarLiquidacion.php",
								data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&descuento="+descuento+"&totalAbonar="+totalAbonar,
								dataType: "html",
								error: function(){
									alert("error petición ajax");
								},
								success: function(data){
									antLiquidacion = totalAbonar;
									$("#resultados").html(data);
									$("#resultados").show();
									$("#resultados").fadeOut(5000);
									n();
								}
							});
						});
					});
				}
			}else{
				$("#resultados").html("<div class='center-block' style='background-color: red'><span class='label center-block label-danger center-block' style='font-size:15px;'>El nombre del monto no puede estar vacio en la tabla de contrato de colegio.</span></div>");
				$("#resultados").show();
				$("#resultados").fadeOut(5000);
			}
		}
		else{
			if(idPag == "ModificarLiquidacionMoratoria"){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "../php/modificarLiquidacion.php",
							data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&totalCondonacionMulta="+totalCondonacionMulta+"&totalCondonacionContraCol="+totalCondonacion+"&porcentaje="+porcentaje+"&descuento="+descuento+"&totalAbonar="+totalAbonar,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								alert("Se modifico el registro con exito");
								window.close("Formularios Modificacion/Modificar_liquidacionMoratoria.php");
								n();
							}
						});
					});
				});
			}
			if(idPag == "liquidacionMoratoria"){
				if(cargarLiquidacion){
					$(document).ready(function(){
						$("#resultados").queue(function(n) {
							$("#resultados").html();
							$.ajax({
								type: "POST",
								url: "php/cargarLiquidacion.php",
								data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&totalCondonacionMulta="+totalCondonacionMulta+"&totalCondonacionContraCol="+totalCondonacion+"&porcentaje="+porcentaje+"&descuento="+descuento+"&totalAbonar="+totalAbonar,
								dataType: "html",
								error: function(){
									alert("error petición ajax");
								},
								success: function(data){
									$("#resultados").html(data);
									$("#resultados").show();
									$("#resultados").fadeOut(5000);
									n();
								}
							});
						});
					});
				}
			}
			else{
				if(idPag == "ModificarLiquidacionArt126"){
					$(document).ready(function(){
						$("#resultados").queue(function(n) {
							$("#resultados").html();
							$.ajax({
								type: "POST",
								url: "../php/modificarLiquidacion.php",
								data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&totalAbonar="+totalAbonar,
								dataType: "html",
								error: function(){
									alert("error petición ajax");
								},
								success: function(data){
									$("#resultados").html(data);
									alert("Se Modifico el registro con exito");
									window.close("Formularios Modificacion/Modificar_liquidacionArt126.php");
									n();
								}
							});
						});
					});
				}
				if(idPag == "liquidacionArt126"){
					if(cargarLiquidacion){
						$(document).ready(function(){
							$("#resultados").queue(function(n) {
								$("#resultados").html();
								$.ajax({
									type: "POST",
									url: "php/cargarLiquidacion.php",
									data: "arrayContrato="+JSON.stringify(arrayContrato)+"&arrayMultas="+JSON.stringify(arrayMultas)+"&idPag="+idPag+"&totalAbonar="+totalAbonar,
									dataType: "html",
									error: function(){
										alert("error petición ajax");
									},
									success: function(data){
										$("#resultados").html();
										$("#resultados").show();
										$("#resultados").fadeOut(5000);
										n();
									}
								});
							});
						});
					}
				}
			}
		}
	});
}
function cargarLiquidacionIncendio(idPag){
	$(function(){
		var destino = document.getElementById('inpDestino').value;
		var M2 = document.getElementById('inpM2').value;
		var CapIX = document.getElementById('inpCapIX').value;
		var total = document.getElementById('lblTotalLiquidacion').innerHTML;

		if(antLiquidacion == 0){
			antLiquidacion = total;
		}
		else{
			if(antLiquidacion == total){
				cargarLiquidacion = confirm("Usted ya cargo una liquidacion con un mismo total, ¿esta seguro de que desea cargarla igualmente?");
			}
		}
		if(idPag == "ModificarLiquidacionIncendio"){
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('headerModificacion');
			}
			$(document).ready(function(){
				$("#resultados").queue(function(n) {
					$("#resultados").html();
					$.ajax({
						type: "POST",
						url: "../php/modificarLiquidacion.php",
						data: "destino="+destino+"&M2="+M2+"&CapIX="+CapIX+"&totalAbonar="+total+"&idPag="+idPag,
						dataType: "html",
						error: function(){
							alert("error petición ajax");
						},
						success: function(data){
							$("#resultados").html(data);
							alert("Se Modifico el registro con exito");
							window.close("Formularios Modificacion/Modificar_liquidacionIncendio.php");
							n();
						}
					});
				});
			});
		}
		else{
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('');
			}
			if(cargarLiquidacion){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "php/cargarLiquidacion.php",
							data: "destino="+destino+"&M2="+M2+"&CapIX="+CapIX+"&total="+total+"&idPag="+idPag,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								$("#resultados").show();
								$("#resultados").fadeOut(5000);
								n();
							}
						});
					});
				});
			}
		}
	});
}
function cargarLiquidacionElectromecanico(idPag){
	$(function(){
		var total = document.getElementById('lblTotalAbonar').innerHTML;

		var hasta50M2 = document.getElementById('inpHasta50M2').value;
		var hasta50CapIX = document.getElementById('inpHasta50CapIX').value;
		var hasta50total = document.getElementById('lblHasta50Total').innerHTML;

		var arrayDestinoHasta50 =[hasta50M2,hasta50CapIX,hasta50total];

		var Exedente50M2 = document.getElementById('inpExedente50M2').value;
		var Exedente50CapIX = document.getElementById('inpExedente50CapIX').value;
		var Exedente50total = document.getElementById('lblExedente50Total').innerHTML;

		var arrayDestinoExedente50 =[Exedente50M2,Exedente50CapIX,Exedente50total];

		var Hasta25M2 = document.getElementById('inpHasta25M2').value;
		var Hasta25CapIX = document.getElementById('inpHasta25CapIX').value;
		var Hasta25total = document.getElementById('lblHasta25Total').innerHTML;

		var arrayDestinoHasta25 =[Hasta25M2,Hasta25CapIX,Hasta25total];

		var Exedente25M2 = document.getElementById('inpExedente25M2').value;
		var Exedente25CapIX = document.getElementById('inpExedente25CapIX').value;
		var Exedente25total = document.getElementById('lblExedente25Total').innerHTML;

		var arrayDestinoExedente25 =[Exedente25M2,Exedente25CapIX,Exedente25total];

		var arrayLiquidacionElectromecanico = [arrayDestinoHasta50,arrayDestinoExedente50,arrayDestinoHasta25,arrayDestinoExedente25];

		if(antLiquidacion == 0){
			antLiquidacion = total;
		}
		else{
			if(antLiquidacion == total){
				cargarLiquidacion = confirm("Usted ya cargo una liquidacion con un mismo total, ¿esta seguro de que desea cargarla igualmente?");
			}
		}

		if(idPag == "ModificarLiquidacionElectromecanico"){
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('headerModificacion');
			}
			$(document).ready(function(){
				$("#resultados").queue(function(n) {
					$("#resultados").html();
					$.ajax({
						type: "POST",
						url: "../php/modificarLiquidacion.php",
						data: "arrayLiquidacionElectromecanico="+JSON.stringify(arrayLiquidacionElectromecanico)+"&totalAbonar="+total+"&idPag="+idPag,
						dataType: "html",
						error: function(){
							alert("error petición ajax");
						},
						success: function(data){
							$("#resultados").html(data);
							alert("Se Modifico el registro con exito");
							window.close("Formularios Modificacion/Modificar_liquidacionElectromecanico.php");
							n();
						}
					});
				});
			});
		}
		else{
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('');
			}
			if(cargarLiquidacion){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "php/cargarLiquidacion.php",
							data: "arrayLiquidacionElectromecanico="+JSON.stringify(arrayLiquidacionElectromecanico)+"&total="+total+"&idPag="+idPag,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								$("#resultados").show();
								$("#resultados").fadeOut(5000);
								n();
							}
						});
					});
				});
			}
		}
	});
}
function cargarLiquidacionCarteles(idPag){
	$(function(){
		var tipo = document.getElementById('inpTipo').value;
		var monto = document.getElementById('inpMonto').value;
		var coef = document.getElementById('inpCoef').value;
		var recargo = document.getElementById('inpRecargo').value;
		var total = document.getElementById('lblTotalLiquidacion').innerHTML;

		if(antLiquidacion == 0){
			antLiquidacion = total;
		}
		else{
			if(antLiquidacion == total){
				cargarLiquidacion = confirm("Usted ya cargo una liquidacion con un mismo total, ¿esta seguro de que desea cargarla igualmente?");
			}
		}
		if(idPag == "ModificarLiquidacionCarteles"){
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('headerModificacion');
			}
			$(document).ready(function(){
				$("#resultados").queue(function(n) {
					$("#resultados").html();
					$.ajax({
						type: "POST",
						url: "../php/modificarLiquidacion.php",
						data: "tipo="+tipo+"&monto="+monto+"&coef="+coef+"&recargo="+recargo+"&totalAbonar="+total+"&idPag="+idPag,
						dataType: "html",
						error: function(){
							alert("error petición ajax");
						},
						success: function(data){
							$("#resultados").html(data);
							alert("Se Modifico el registro con exito");
							window.close("Formularios Modificacion/Modificar_liquidacionCarteles.php");
							n();
						}
					});
				});
			});
		}
		else{
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('');
			}
			if(cargarLiquidacion){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "php/cargarLiquidacion.php",
							data: "tipo="+tipo+"&monto="+monto+"&coef="+coef+"&recargo="+recargo+"&total="+total+"&idPag="+idPag,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								$("#resultados").show();
								$("#resultados").fadeOut(5000);
								n();
							}
						});
					});
				});
			}
		}
	});
}
function CargarLiquidacionArt13(idPag){
	$(function(){
		var cubiertom2 = document.getElementById('inpCubiertoM2').value;
		var cubiertocoef = document.getElementById('inpCubiertoCoef').value;
		var cubiertouref = document.getElementById('inpCubiertoURef').value;
		var cubiertoTotal = document.getElementById('lblCubiertoTotal').innerHTML;

		var arrayCubierto = [cubiertom2,cubiertocoef,cubiertouref,cubiertoTotal];

		var semiCubm2 = document.getElementById('inpSemiCubM2').value;
		var semiCubcoef = document.getElementById('inpSemiCubCoef').value;
		var semiCuburef = document.getElementById('inpSemiCubURef').value;
		var semiCubTotal = document.getElementById('lblSemiCubTotal').innerHTML;

		var arraySemiCub = [semiCubm2,semiCubcoef,semiCuburef,semiCubTotal];

		var arrayContrato = [arrayCubierto,arraySemiCub];

		var destino = document.getElementById('inpArt13Destino').value;
		var CapiX= document.getElementById('inpCapXI').value;
		var DeclararMonto = document.getElementById('lblDeclararMonto').innerHTML;
		var total = document.getElementById('lblArt13Total').innerHTML;

		if(antLiquidacion == 0){
			antLiquidacion = total;
		}
		else{
			if(antLiquidacion == total){
				cargarLiquidacion = confirm("Usted ya cargo una liquidacion con un mismo total, ¿esta seguro de que desea cargarla igualmente?");
			}
		}

		if(idPag == "ModificarLiquidacionArt13"){
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('headerModificacion');
			}
			$(document).ready(function(){
				$("#resultados").queue(function(n) {
					$("#resultados").html();
					$.ajax({
						type: "POST",
						url: "../php/modificarLiquidacion.php",
						data: "arrayContrato="+JSON.stringify(arrayContrato)+"&idPag="+idPag+"&destino="+destino+"&CapIx="+CapiX+"&DeclararMonto="+DeclararMonto+"&totalAbonar="+total,
						dataType: "html",
						error: function(){
							alert("error petición ajax");
						},
						success: function(data){
							$("#resultados").html(data);
							alert("Se Modifico el registro con exito");
							window.close("Formularios Modificacion/Modificar_liquidacionArt13.php");
							n();
						}
					});
				});
			});
		}
		else{
			if(!$("input[type=text].txtHeader").is(':disabled')){
				cambiarValor('');
			}
			if(cargarLiquidacion){
				$(document).ready(function(){
					$("#resultados").queue(function(n) {
						$("#resultados").html();
						$.ajax({
							type: "POST",
							url: "php/cargarLiquidacion.php",
							data: "arrayContrato="+JSON.stringify(arrayContrato)+"&idPag="+idPag+"&destino="+destino+"&CapIx="+CapiX+"&DeclararMonto="+DeclararMonto+"&total="+total,
							dataType: "html",
							error: function(){
								alert("error petición ajax");
							},
							success: function(data){
								$("#resultados").html(data);
								$("#resultados").show();
								$("#resultados").fadeOut(5000);
								n();
							}
						});
					});
				});
			}
		}
	});
}