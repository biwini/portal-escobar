$(document).ready(start);

function start(){
  llenar_horasjs();
  $('#lista_horarios tr ').popup();
  $("#tabla_horarios").on("dblclick","#lista_horarios tbody tr",function(){ //aca es cuando le dan doble click a tabla_horarios, para mostrar el registro
  	 $("#table_horarios tbody ").html('');
  	id_clave=$(this).find(".id_clave").val();

  	$.getJSON("inc/horario.php?action=buscar&id="+id_clave,function(data){
        if(data[0]){
        	$.each(data,function(key,value){
	            //$("#especialista").append("<option value='"+value.c_profesional+"'>"+value.d_apellidoynombre+"</option>");
	            $("#horario").val(value.ID_HORARIO);
	            $("#descrip").val(value.NOMBRE_HORARIO);
	            $("#tol1").val(value.D_TOLERANCIA_ENTRADA);
	            $("#tol2").val(value.D_TOLERANCIA_SALIDA);
                $("#fecha").val(value.D_FECHAALTA.substr(0,10));
                //console.log(value.D_FECHAALTA.substr(0,10));
                horario=value.ID_HORARIO;
                dia=value.ID_dia_semana;
                e1=value.H_ENTRADA;
                s1=value.H_SALIDA;
                e2=value.H_HORA_ing_COMIDA;
                s2=value.H_HORA_egr_COMIDA;

                $("#table_horarios tbody ").append("<tr><td class='collapsing'>"+ value.ID_dia_semana +"</td><td>"+ value.H_ENTRADA +"</td><td>"+ value.H_SALIDA +"</td><td>"+ value.H_HORA_egr_COMIDA +"</td><td>"+ value.H_HORA_ing_COMIDA +"</td><td class='collapsing'><input type='hidden' class='tbl_id_horario' data-horario='"+horario +  "' data-dia='"+dia+"'  data-e1='"+e1+"'  data-s1='"+s1+"' data-e2='"+e2+"' data-s2='"+s2+"' value='"+dia+"'><button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");

	        });
            $('.menu .item').tab('change tab','second');
        }
    });


  });



  $('.menu .item').tab();
    getEspecialista();
    $("#e1").change(function(){
      console.log($(this).val())
    })
    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        }
    });
    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
    $('#form_pac').form({
        keyboardShortcuts:false,
        fields: {
            entrada1: {
                identifier: 'e1',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe completar un horario de Ingreso'
                    }
                ]
            },
            salida1: {
                identifier: 's1',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe completar un horario de Salida'
                    }
                ]
            },
            date: {
                identifier: 'date',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir una fecha'
                    }
                ]
            },
            tipo_doc: {
                identifier: 'tipo_doc',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir un tipo de documento'
                    }
                ]
            },
            nro_doc: {
                identifier: 'nro_doc',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir un numero de documento'
                    }
                ]
            },
            nro_beneficio: {
                identifier: 'nro_beneficio',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir un numero de beneficio'
                    },
                    {
                        type:'length[12]',
                        prompt: 'El numero de beneficio debe contener 12 digitos'
                    }
                ]
            },
            nro_parentesco: {
                identifier: 'nro_parentesco',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir un numero de parentesco'
                    },
                    {
                        type:'length[2]',
                        prompt: 'El numero de parentesco debe contener 2 digitos'
                    }
                ]
            },
            name: {
                identifier: 'name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir el nombre y apellido'
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

    $("#limpiar_horario").click(function(e){
    	$("#table_horarios tbody ").html('');
        $('input').val('');
    });


    $("#add_Horario").click(function(e){
       $("#form_practicas").form("submit");
       if($("#form_practicas").form("is valid")){
           dia=$("#Dia").val();
           e1=$("#e1").val();
           s1=$("#s1").val();
           e2=$("#e2").val();
           s2=$("#s2").val();
           var diasemana = new Array( "Sin dia","Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sabado")


           $("#table_horarios tbody ").append("<tr><td class='collapsing'>"+diasemana[dia]+"</td><td>"+e1+"</td><td>"+s1+"</td><td>"+e2+"</td><td>"+s2+"</td><td class='collapsing'><input type='hidden' class='tbl_id_horario' data-horario='"+horario +  "' data-dia='"+dia+"'  data-e1='"+e1+"'  data-s1='"+s1+"' data-e2='"+e2+"' data-s2='"+s2+"' value='"+dia+"'><button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");
           a=setInterval(function(){
               $("#form_practicas").form("reset");
               $("#error_tbl").hide();
               clearInterval(a);
           },100);
       }
   });


    $("#table_horarios").on("click",".del_practica",function(){
        $(this).parent().parent().remove();
    });

    $("#save_all").click(function(){
        $("#form_pac").form("submit");
      //  ($(".tbl_id_practica").length)?$("#error_tbl").hide():$("#error_tbl").removeAttr("style");

    //    if($(".tbl_id_practica").length && $("#form_pac").form("is valid")){
            var formPac=$("#form_pac");
            var listhorarios=[];
            $.each($(".tbl_id_horario"),function(key,value){
                listhorarios.push({
                    horario:$(this).val(),
                    dia:$(this).attr("data-dia"),
                    e1:$(this).attr("data-e1"),
                    s1:$(this).attr("data-s1"),
                    e2:$(this).attr("data-e2"),
                    s2:$(this).attr("data-s2")

               });
            });
           // graba campos cabecer
            var formData={
                action:"insertar",
                horario: getFormVal(formPac,"horario"),
                fecha: getFormVal(formPac,"fecha"),
                descrip: getFormVal(formPac,"descrip"),
                tol1: getFormVal(formPac,"tol1"),
                tol2: getFormVal(formPac,"tol2"),

                listhorarios: listhorarios
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/horario.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    location.reload();
                }
            });
            $("#table_horarios tbody ").html('');
            $('input').val('');
    //    }
    });
    $("#form_pac ").on('dblclick','.input',function(){
        $(this).find('input').removeAttr("disabled");
        console.log(123);

    });
    $("#searchAmb").click(function(){
        $("#searchAmbModal").modal('show');
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

    });
}


function llenar_horasjs(){
  $.getJSON("inc/horario.php?action=todos",function(data){
      $.each(data,function(key,value){
      	$("#lista_horarios tbody").append("<tr data-content='dblclick nuestra horario'><td> " + value.id_horario + "</td><td> " + value.nombre_horario + "</td><input type='hidden' class='id_clave' value="+ value.id_horario +"></tr>");
		console.log(value.id_horario);
      });
   });
}


function getFormVal(form,id){
    return form.form("get field",id).val();
}
