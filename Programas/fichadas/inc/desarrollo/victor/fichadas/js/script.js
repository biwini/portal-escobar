$(document).ready(start);

function start(){
    //getEspecialista();
    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        }
    });
    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
	
    $('#formulario_reloj').form({
        keyboardShortcuts:false,
        fields: {
            descripcion: {
                identifier: 'descripcion',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe cargar una descripcion del reloj'
                    }
                ]
            }
        }
    });
	
    $('#form_practicas').form({
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
    $("#nro_doc").keypress(function(e){
        if(e.which==13){
            searchPaciente($("#tipo_doc").val(),$("#nro_doc").val());
        }

    });
    $("#idPractica").change(function(){
        $("#codigoPractica").val($(this).val());
        $("#cod_label_error").removeAttr("style");
    });
    $(".searchPractica .search").keypress(function(e){
        if($(this).val().length >2){
            $.getJSON("inc/practica.php?action=search&q="+$(this).val(),function(data){
                v={
                    values:data
                };
                $(".searchPractica").dropdown("setup menu",v);
                $(".searchPractica").dropdown("save defaults");
                $(".searchPractica").dropdown("refresh");
            });
        }
    });
    $("#codigoPractica").keypress(function(e){
        if(e.which==13){
            $.getJSON("inc/practica.php?action=get&id="+$(this).val(),function(data){
                $("#cod_label_error").removeAttr("style");
                if(data[0]){
                    v={
                        values:data
                    };
                    $(".searchPractica").dropdown("setup menu",v);
                    $(".searchPractica").dropdown("set selected",$("#codigoPractica").val());
                }
                else{
                    $("#cod_label_error").attr("style","display:inline;");
                    a=setInterval(function(){
                        $(".searchPractica").dropdown("clear");
                        clearInterval(a);
                    },100);
                }
            });
        }
    });
    $("#add_practica").click(function(e){
        $("#form_practicas").form("submit");
        if($("#form_practicas").form("is valid")){
            idPractica=$("#idPractica").val();
            namePractica=$(".searchPractica").dropdown("get text");
            cantPractica=$("#cantPractica").val();
            $("#table_practicas tbody ").append("<tr><td class='collapsing'>"+idPractica+"</td><td>"+namePractica+"</td><td class='collapsing'>"+cantPractica+"</td><td class='collapsing'><input type='hidden' class='tbl_id_practica' data-cant='"+cantPractica+"' value='"+idPractica+"'><button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");
            a=setInterval(function(){
                $("#form_practicas").form("reset");
                $("#error_tbl").hide()
                clearInterval(a);
            },100);
        }
    });
    $("#table_practicas").on("click",".del_practica",function(){
        $(this).parent().parent().remove();
    });
	
	

    $("#save_all").click(function(){
        $("#formulario_reloj").form("submit");
        //($(".tbl_id_practica").length)?$("#error_tbl").hide():$("#error_tbl").removeAttr("style");

        //if($(".tbl_id_practica").length && $("#form_pac").form("is valid")){
        if($("#formulario_reloj").form("is valid")){
            var formPac=$("#formulario_reloj");

            var formData={
                action:"send",
                codigo: getFormVal(formPac,"codigo"),
                descripcion: getFormVal(formPac,"descripcion"),
                dependencia: getFormVal(formPac,"dependencia"),
                usuario: getFormVal(formPac,"usuario"),
                clave: getFormVal(formPac,"clave"),
                ubicacion: getFormVal(formPac,"ubicacion"),
                marca: getFormVal(formPac,"marca"),
                modelo: getFormVal(formPac,"modelo"),
		direccionip: getFormVal(formPac,"direccionip"),
		puerto: getFormVal(formPac,"puerto")
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
	
    $("#limpiar_controles").click(function(){
        
		$("#formulario_reloj").form("clear");
	console.log("antes get JSON");
        $.getJSON("inc/reloj.php?action=getLastID",function(data){
            $.each(data,function(key,value){
			console.log(value.UltimoID);
			$("#codigo").val(value.UltimoID);
            });
        });
		

		// $("#codigo").val("");
		  // $("#descripcion").val("");
		  // $("#direccionip").val("");
		  // $("#puerto").val("");
		  // $("#usuario").val("");
		  // $("#marca").val("");
		  // $("#clave").val("");
		  // $("#modelo").val("");
		  // $("#ubicacion").val("");
		  // $("#observaciones").val("");
		  // $("#dependencia").val("");


    });
	
	
    $("#form_pac ").on('dblclick','.input',function(){
        $(this).find('input').removeAttr("disabled");
        console.log(123);

    });

	$("#grilla_relojes").on("click",".del_practica",function(){
		console.log("click eliminar" + $(this).parent().find(".id_registro_reloj").val());

		if(confirm("¿Desea eliminar?")){
			
     			 	var formData={
                			action:"eliminar",
                			id: $(this).parent().find(".id_registro_reloj").val(),
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



	$("#grilla_relojes").on("click",".mostrar_relojes",function(){
		//console.log();
		$.getJSON("inc/reloj.php?action=search&id="+$(this).parent().find(".id_registro_reloj").val(),function(data){
            $.each(data,function(key,value){
              //value.Codigo
			  $("#codigo").val(value.Codigo);
			  $("#descripcion").val(value.Descripcion);
			  console.log(value.DireccionIP);
			  $("#direccionip").val(value.DireccionIP);
			  $("#puerto").val(value.Puerto);
			  $("#usuario").val(value.Usuario);
			  $("#marca").val(value.Marca);
			  $("#clave").val(value.Clave);
			  $("#modelo").val(value.Modelo);
			  $("#ubicacion").val(value.Ubicacion);
			  $("#observaciones").val(value.Observaciones);
			  $("#dependencia").val(value.Dependencia);
			  
            });
        });
		
		
	});
	
    $("#verRelojes").click(function(){
      //console.log("clic hola");
        //$("#searchAmbModal").modal('show');
		
        $("#grilla_relojes tbody").html("");

        $.getJSON("inc/reloj.php?action=get",function(data){
            $.each(data,function(key,value){
              console.log("dentro del each");
              wBoton="<button class='ui green basic button mostrar_relojes' ><i class='eye icon'></i>Ver</button>";
              $("#grilla_relojes tbody ").append("<tr><td class='collapsing'>"+value.Codigo+"</td><td>"+value.Descripcion+"</td><td class='collapsing'>"+value.Dependencia+"</td><td class='collapsing'><input type='hidden' class='id_registro_reloj' data-cant='"+value.Codigo+"' value='"+value.Codigo+"'>"+ wBoton +"<button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");

            });
        });

    });


    $("#searchAmbSubmit").click(function(){
        //$.getJSON('inc/paciente.php?action=search&date=')
    });

}
function getEspecialista(){
    $.getJSON("inc/especialista.php?action=get",function(data){
        $.each(data,function(key,value){
            $("#especialista").append("<option value='"+value.c_profesional+"'>"+value.d_apellidoynombre+"</option>");
        });
    });
}
function searchPaciente(tipo_doc,nro_doc){
    $.getJSON("inc/paciente.php?action=get&tipo_doc="+tipo_doc+"&nro_doc="+nro_doc,function(data){
        $("#nro_beneficio,#nro_parentesco,#name").val("");
        $("#doc_label_error").removeAttr("style");
        if(data[0]){
            $("#nro_beneficio").val(data[0].id_beneficio).attr("disabled","disabled");
            $("#nro_parentesco").val(data[0].id_parentesco).attr("disabled","disabled");
            $("#name").val(data[0].nombre).attr("disabled","disabled");
            $("#doc_check,#save_pac").show();
        }
        else{
            $("#nro_beneficio,#nro_parentesco,#name").removeAttr("disabled");
            $("#doc_check").removeAttr("style");
            $("#doc_label_error").attr("style","display:inline;");
        }
        //$("#form_pac").form("submit");
    });
}

function getFormVal(form,id){
    return form.form("get field",id).val();
}
