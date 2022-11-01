$(document).ready(start);

function start(){
    $('.menu .item').tab(); //pesta�as solapas 

    cargarEmpleadosCombo(); //carga option select con la tabla de empleados
    
    buscar_registro(); //carga listado

    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        }
    });
    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
	
    // $('#formulario_reloj').form({
    //     keyboardShortcuts:false,
    //     fields: {
    //         descripcion: {
    //             identifier: 'descripcion',
    //             rules: [
    //                 {
    //                     type   : 'empty',
    //                     prompt : 'Debe cargar una descripcion del reloj'
    //                 }
    //             ]
    //         }
    //     }
    // });
	
    $('#form_licencias').form({
        keyboardShortcuts:false,
        fields: {
            codigoPractica: {
                identifier: 'codigoPractica',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'El codigo no debe estar vacio'
                    }
                ]
            },
            idPractica: {
                identifier: 'idPractica',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir una practica'
                    }
                ]
            },
            cantPractica: {
                identifier: 'cantPractica',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'La cantidad no puede estar vacia'
                    }
                ]
            }
        }
    });
	
    $("#save_all").click(function(){
        $("#formulario").form("submit");

        if($("#formulario").form("is valid")){
            var formPac=$("#formulario");

            var formData={
                action:"send",
                codigo: getFormVal(formPac,"codigo"),
                descripcion: getFormVal(formPac,"descripcion"),
                empleado: getFormVal(formPac,"empleado"),
                fechainicio: getFormVal(formPac,"fechainicio"),
                horainicio: getFormVal(formPac,"horainicio"),
                fechafin: getFormVal(formPac,"fechafin"),
                horafin: getFormVal(formPac,"horafin")
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/licencias.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    console.log("dentro sucess save_all js");
                    limpiar();
                }
            });
        }
    });
	
    $("#limpiar_controles").click(function(){	
		limpiar();
    });

	$("#grilla_relojes").on("click",".del_practica",function(){
		console.log("click eliminar" + $(this).parent().find(".id_registro").val());
		if(confirm("¿Desea eliminar?")){
            var formData={
                action:"eliminar",
                id: $(this).parent().find(".id_registro").val(),
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/reloj.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    location.reload();
                }
            });
		}
	});

	$("#grilla_relojes").on("click",".mostrar_registro",function(){
        $('.menu .item').tab("change tab","first");
		$.getJSON("inc/licencias.php?action=mostrar_registro&id="+$(this).parent().find(".id_registro").val(),function(data){
            $.each(data,function(key,value){
			  $("#codigo").val(value.Codigo);
			  $("#descripcion").val(value.descripcion);
			  $("#fechainicio").val(value.fecha_desde.substr(0,10));
			  $("#horainicio").val(value.hora_desde.substr(0,5));
			  $("#fechafin").val(value.fecha_hasta.substr(0,10));
			  $("#horafin").val(value.hora_hasta.substr(0,5));
              $("#empleado").val(value.Empleado).change();
            });
        });
		
		
	});
	
    $("#verRelojes").click(function(){
		buscar_registro();
    });


    $("#searchAmbSubmit").click(function(){
        //$.getJSON('inc/paciente.php?action=search&date=')
    });

}

function limpiar(){
    $("#formulario").form("clear");
        $.getJSON("inc/licencias.php?action=getLastID",function(data){
            $.each(data,function(key,value){
                if(value.UltimoID){
                    $("#codigo").val(value.UltimoID);
                }else{
                    $("#codigo").val(1);
                }

            
            });
        });
}

function cargarEmpleadosCombo(){
    $.getJSON("inc/licencias.php?action=getEmpleados",function(data){
        $.each(data,function(key,value){
            $("#empleado").append("<option value='"+value.id+"'>"+value.apellidoNombre+"</option>");
        }); 
    });
}

function buscar_registro(){
    $("#grilla_relojes tbody").html("");
    $.getJSON("inc/licencias.php?action=getLicencias",function(data){
        $.each(data,function(key,value){
          //console.log("dentro del each");
          wBoton="<button class='ui green basic button mostrar_registro' ><i class='eye icon'></i>Ver</button>";
          $("#grilla_relojes tbody ").append("<tr><td class='collapsing'>"+value.Codigo+"</td><td>"+value.apellidoNombre+"</td><td class='collapsing'>"+value.fecha_desde.substr(0,10)+" a " + value.fecha_hasta.substr(0,10) +"</td><td class='collapsing'><input type='hidden' class='id_registro' value='"+value.Codigo+"'>"+ wBoton +"<button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");

        });
    });
}

function getFormVal(form,id){
    return form.form("get field",id).val();
}
