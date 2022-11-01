$(document).ready(start);

function start(){
    getEspecialista();
    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        }
    });
    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
    $('#form_pac').form({
        keyboardShortcuts:false,
        fields: {
            especialista: {
                identifier: 'especialista',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir un especialista'
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

    $("#add_Horario").click(function(e){
       $("#form_practicas").form("submit");
       if($("#form_practicas").form("is valid")){
           dia=$("#Dia").val();
           e1=$("#e1").val();
           s1=$("#s1").val();
           e2=$("#e2").val();
           s2=$("#s2").val();
           if (dia == 2){
              var wdia="Lunes";
              }else{
                   if (dia == 3) {
                      var wdia="Martes";
                   }else{
                        if (dia == 4) {
                           var wdia="Miercoles";
                        }else{
                            if (dia == 5) {
                             var wdia="Jueves";
                            }else{
                                if (dia == 6) {
                                  var wdia="Viernes";
                                }else{
                                  if (dia == 7) {
                                     var wdia="Sabado";
                                     }else{
                                      var wdia="Domingo";
                                      }
                                    }
                                  }
                                }
                              }
           }
           $("#table_horarios tbody ").append("<tr><td class='collapsing'>"+wdia+"</td><td>"+e1+"</td><td>"+s1+"</td><td>"+e2+"</td><td>"+s2+"</td><td class='collapsing'><input type='hidden' class='tbl_id_practica' value='"+dia+"'><button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button></td>");
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
        ($(".tbl_id_practica").length)?$("#error_tbl").hide():$("#error_tbl").removeAttr("style");

        if($(".tbl_id_practica").length && $("#form_pac").form("is valid")){
            var formPac=$("#form_pac");
            var listPracticas=[];
            $.each($(".tbl_id_practica"),function(key,value){
                listPracticas.push({
                    id_practica:$(this).val(),
                    cant:$(this).attr("data-cant")
                });
            });

            var formData={
                action:"send",
                especialista: getFormVal(formPac,"especialista"),
                date: getFormVal(formPac,"date"),
                tipo_doc: getFormVal(formPac,"tipo_doc"),
                nro_doc: getFormVal(formPac,"nro_doc"),
                nro_beneficio: getFormVal(formPac,"nro_beneficio"),
                nro_parentesco: getFormVal(formPac,"nro_parentesco"),
                name: getFormVal(formPac,"name"),
                practicas: listPracticas
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/paciente.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    location.reload();
                }
            });

        }
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
        //$("#form_pac").form("submit");
    });
}

function getFormVal(form,id){
    return form.form("get field",id).val();
}
