$(document).on('submit', '#ticket', function(e){
    e.preventDefault();

    if(validate($(this).find('.required'))){
        $.ajax({
            type: "POST",
            url: "controller/",
            data: $(this).serialize()+"&pag=Ticket"+"&tipo=c",
            dataType: "html",
        })
        .fail(function(data){
            console.log(data);
            mensaje('fail','Error Peticion ajax');
            $('#result_ticket tbody').html('');
        })
        .done(function(data){
            response = JSON.parse(data);
            console.table(response)
            $('#section_result').addClass('hide');
            $('#secretaria').text('');
            $('#dependencia').text('');
            $('#responsable').text('');
            $('#observacion').text('');
            $('#motivo').text('');
            $('#tecnico').text('');
            $('#fecha_inicio').text('');
            $('#fecha_finalizado').text('');
            $('#retirado_por').text('');
            $('#fecha_retirado').text('');
            switch(response.Status){
                case 'Success':
                    mensaje('okey','Se encontro el ticket');
                    tecnico = (response.Encargado.trim() == '') ? 'SIN ASIGNAR' : response.Encargado;
                    $('#section_result').removeClass('hide');
                    $('#secretaria').text(response.Secretaria);
                    $('#dependencia').text(response.Dependencia);
                    $('#responsable').text(response.Usuario);
                    $('#observacion').text(response.Comentario_Tecnico);
                    $('#motivo').text(response.Motivo);
                    $('#tecnico').text(tecnico);
                    $('#fecha_inicio').text(response.FechaAlta);
                    dateEnd = (response.FechaFinalizado == null) ? '-' : response.FechaFinalizado;
                    $('#fecha_finalizado').text(dateEnd);
                    step = (response.Estado == '3') ? 3 : parseInt(response.Estado);

                    if(response.Motivo == 'PC/INGRESO DE PC'){
                        $('#retiro').removeClass('hide');
                        $('#div_retirado').removeClass('hide');
                        $('#retirado_por').text((response.RetiradoPor == null) ? 'SIN RETIRAR' : response.RetiradoPor);
                        $('#fecha_retirado').text((response.FechaRetiro == null) ? 'SIN RETIRAR' : response.FechaRetiro);
                        if(response.ListoParaRetiro == '1'){
                            step++;
                        }
                    }else{
                        $('#retirado_por').addClass('hide');
                        $('#retiro').addClass('hide');
                    }

                    $("#step-bar").stepProgressBar(step);
                break;
                case 'Error':
                    mensaje('fail','Ocurrio un error inesperado al consultar el ticket');
                break;
                case 'Unkown Ticket':
                    mensaje('okey','No se encontro el ticket');
                break;
            }
        });
    }
});

/* JavaScript para incluir. */

jQuery.fn.extend({
    stepProgressBar: function(currentStep) {
        currentStep = currentStep || this.currentStep() || 1;
        let childs = this
                .addClass("step-progress-bar")
                .find("li")
                .removeClass("step-past step-present step-future");

        childs.find(".content-stick").removeClass("step-past step-future");

        let size = childs.length < 1 ? 100 : 100 / childs.length;
        childs.css("width", size + "%");

        for (let i = 0; i < childs.length; i++) {
            let child = $(childs[i]);
            let img = ['<img title=\'Pendiente\' alt=\'Pendiente\' src=\'images/pendiente.png\' height=\'40px\'>',
                '<img title=\'En Curso\' alt=\'En Curso\' src=\'images/in-process.png\' height=\'40px\'>',
                '<img title=\'Listo para retiro\' alt=\'Listo para retiro\' src=\'images/fixed.png\' height=\'40px\'>',
                '<img title=\'Finalizado\' alt=\'Finalizado\' src=\'images/fixed.png\' height=\'40px\'>'
            ];
            if (child.find("span.content-wrapper").length === 0) {
                child.wrapInner("<span class='content-wrapper'></span>");
                if (i > 0) child.append("<span class='content-stick'></span>");
                child.prepend("<span class='content-bullet'>" + img[i] + "</span>");
            }
            let stepName = i < currentStep - 1 ? "step-past"
                    : i === currentStep - 1 ? "step-present"
                    : "step-future";
            child.addClass(stepName);
            if (i > 0) {
                let stickName = stepName === "step-present" ? "step-past" : stepName;
                child.find(".content-stick").addClass(stickName);
            }
            child.css("z-index", childs.length - i);
            child.find(":before").css("z-index", childs.length - i + 2);
        }
        return this;
    },

    currentStep: function() {
        var childs = this.find("li");
        for (let i = 0; i < childs.length; i++) {
            if ($(childs[i]).is(".step-present")) return i + 1;
        }
        return 1;
    },
});