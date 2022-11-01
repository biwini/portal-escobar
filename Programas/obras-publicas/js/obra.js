let xls = new xlsExport(getExcelData(Obras), 'Obras');

$('.to-miles').focusout(function(e){
    let split = this.value.split(',');

    if(split.length == 1){
        split[1] = '';
    }

    let value = (split[1] != '00') ? split[0].replace(/\D+/g, '') +'.'+ split[1].replace(/\D+/g, '') : split[0].replace(/\D+/g, '');

    this.value = formatNumber(value);
});


$('#importe_est').focusout(function(e){
    if(this.value.split('.').length > 1){
        this.value = this.value.replace('.',',');
    }

    let split = this.value.split(',');

    if(split.length == 1){
        split[1] = '';
    }

    let value = (split[1] != '00') ? split[0].replace(/\D+/g, '') +'.'+ split[1].replace(/\D+/g, '') : split[0].replace(/\D+/g, '');

    if(value == 0 || value == '' || value == undefined){
        return false;
    }

    let def = parseInt($('#importe_def').val());

    if(def == '' ||def == 0 || def == undefined){
        saldoAPagar = value;
    }else{
        saldoAPagar = def;
    }

    this.value = formatNumber(value);

    $('#a_pagar').val(formatNumber(saldoAPagar));
});

$('#importe_def').focusout(function(e){
    if(this.value.split('.').length > 1){
        this.value = this.value.replace('.',',');
    }
    let split = this.value.split(',');

    if(split.length == 1){
        split[1] = '';
    }

    let value = (split[1] != '00') ? split[0].replace(/\D+/g, '') +'.'+ split[1].replace(/\D+/g, '') : split[0].replace(/\D+/g, '');

    let old = saldoAPagar;
    saldoAPagar = value;

    let pago = getPaidAmount();
    let faltaPagar = getUnpaidAmount(); 

    if(pago > saldoAPagar || saldoAPagar < (pago - saldoAPagar) + faltaPagar){
        saldoAPagar = old;
        $('#importe_def').val(saldoAPagar);
        return swal('','El importe de la orden de pago supera el importe definitivo de la obra','warning');
    }
    
    this.value = formatNumber(value);
    calcularOPPagado();

    // $('#a_pagar').val(formatNumber(saldoAPagar));
});

$('#add_compromiso').click(function(e){
    let numero = parseInt($('#numero_compromiso').val());
    let fecha = $('#fecha_compromiso').val();
    let importe = $('#importe_compromiso').val();

    if(numero == '' || numero == 0 || fecha == '' || importe == '' ||importe == 0){
        return swal('Campos incompletos','complete todos los campos', '');
    }

    let found = compromisos.find(c => c.numero == numero);

    if(found !== undefined){
        return swal('Registro de compromiso repetido','El regitro de compromiso ya esta asociado', '');
    }
    
    let pago = getRCPaidAmount() + importe;

    if(pago > saldoAPagar){
        return swal('','El importe del registro de compromiso es mayor al importe definitivo','warning');
    }

    compromisos.push({id: null, numero: numero, fecha: fecha, importe: importe, eliminado: null});

    let tr = '<tr>\
        <td>'+numero+'</td>\
        <td>'+fecha+'</td>\
        <td>'+formatNumber(importe)+'</td>\
        <td><button type=\'button\' class=\'btn btn-danger eliminar_compromiso\' value=\''+numero+'\'>ELIMINAR</button></td>\
    </tr>';

    $('#table_compromiso tbody').append(tr);
    $('#numero_compromiso').val('');
    $('#fecha_compromiso').val('');
    $('#importe_compromiso').val('');

    $('#cant_rc').text(compromisos.length);
});

function getRCPaidAmount(){
    return compromisos.reduce((acc, currentOrder) => {
        return acc + parseInt(currentOrder.importe);
    }, 0);
}

$('#add_op').click(function(e){
    let fecha = $('#fecha_op').val();
    let numero = parseInt($('#numero_op').val());
    let importe = parseInt($('#importe_op').val());
    let pagado = $('#pagado_op').val();
    let ocea = $('#osea_op option:selected').val();
    let numeroOcea = $('#ocea_numero_op').val();
    let fechaOcea = $('#ocea_fecha_op').val();

    if(numero == '' || numero <= 0 || fecha == '' || importe == '' ||importe == 0 || ocea == ''){
        return swal('Campos incompletos','complete todos los campos', '');
    }

    if(ocea == 'SI'){
        if((numeroOcea == '' || numeroOcea <= 0) || fechaOcea == ''){
            return swal('Ocea incompleta','complete el numero y fecha de la ocea', '');
        }
    }

    let found = ordenesPago.find(o => o.numero == numero);

    if(found !== undefined){
        return swal('Orden de pago repetida','La orden de pago ya esta asociada', '');
    }

    let foundOcea = ordenesPago.find(o => (o.numeroOcea == numeroOcea)  && o.numeroOcea != '' && o.numeroOcea != null);

    if(foundOcea !== undefined){
        return swal('Ocea repetida','La ocea ya esta registrada', '');
    }

    let pago = getPaidAmount() + importe;
    let faltaPagar = getUnpaidAmount(); 

    if(pago > saldoAPagar || saldoAPagar < (pago - saldoAPagar) + faltaPagar){
        return swal('','El importe de la orden de pago supera el importe definitivo de la obra','warning');
    }

    ordenesPago.push({
        id: null, 
        numero: numero, 
        fecha: fecha, 
        importe: importe, 
        pagado: pagado, 
        ocea: ocea, 
        numeroOcea: numeroOcea, 
        fechaOcea: fechaOcea,
        eliminado: null
    });

    let tr = '<tr>\
        <td>'+fecha+'</td>\
        <td>'+numero+'</td>\
        <td>'+formatNumber(importe)+'</td>\
        <td>'+pagado+'</td>\
        <td>'+ocea+'</td>\
        <td>'+numeroOcea+'</td>\
        <td>'+fechaOcea+'</td>\
        <td><button type=\'button\' class=\'btn btn-danger eliminar_op\' value=\''+numero+'\'>ELIMINAR</button></td>\
    </tr>';

    $('#table_op tbody').append(tr);
    $('#fecha_op').val('');
    $('#numero_op').val('');
    $('#importe_op').val('');
    $('#pagado_op').val('');
    $('#osea_op option[value=\'NO\']').prop('selected', true).change();

    $('#cant_op').text(ordenesPago.length);

    calcularOPPagado();
});

function calcularOPPagado(){
    let pago = getPaidAmount();
    let sinPagar = getUnpaidAmount(); 

    if(pago > saldoAPagar && sinPagar < 0){
        return swal('','El monto a pagar supera el importe definitivo','');
    }

    let faltaPagar = (saldoAPagar - pago);

    $('#pagado').val(formatNumber(pago));
    // $('#falta_pagar').val(formatNumber(faltaPagar));
    $('#a_pagar').val(formatNumber(faltaPagar))
}

function getPaidAmount(){
    return ordenesPago.filter(op => op.pagado != '').reduce((acc, currentOrder) => {
        return acc + parseInt(currentOrder.importe);
    }, 0);
}

function getUnpaidAmount(){
    return ordenesPago.filter(op => op.pagado == '').reduce((acc, currentOrder) => {
        return acc + parseInt(currentOrder.importe);
    }, 0);
}

$('#osea_op').change(function(e){
    if(this.value == 'SI'){
        $('.ocea_si').show();
    }else{
        $('.ocea_si').children('input').val('');
        $('.ocea_si').hide();
    }
});

$('#add_imputacion').click(function(e){
    let jurisdiccion = $('#jurisdiccion_imp option:selected').val();
    let categoria1 = $('#catprod1_imp').val();
    let categoria2 = $('#catprod2_imp').val();
    let categoria3 = $('#catprod3_imp').val();
    let fuente = $('#fuente_imp option:selected').val();
    let gasto = $('#gasto_imp option:selected').val();
    // let denominacion = $('#denominacion_imp').val();
    let afectacion = $('#afectacion_imp option:selected').val();

    if(jurisdiccion == '' || fuente == '' || gasto == '' || afectacion == ''){
        return swal('Campos incompletos','complete todos los campos', '');
    }

    imputaciones.push({
        id: null, 
        jurisdiccion: jurisdiccion,
        categoria1: categoria1,
        categoria2: categoria2,
        categoria3: categoria3,
        fuente: fuente,
        gasto: gasto,
        afectacion: afectacion,
        eliminado: null
    });

    let tr = '<tr>\
        <td>'+$('#jurisdiccion_imp option:selected').text()+'</td>\
        <td>'+categoria1+'.'+categoria2+'.'+categoria3+'.</td>\
        <td>'+$('#fuente_imp option:selected').text()+'</td>\
        <td>'+$('#gasto_imp option:selected').text()+'</td>\
        <td>'+$('#afectacion_imp option:selected').text()+'</td>\
        <td><button type=\'button\' class=\'btn btn-danger eliminar_op\' value=\''+imputaciones.length+'\'>ELIMINAR</button></td>\
    </tr>';

    $('#table_imputacion tbody').append(tr);
    $('#jurisdiccion_imp').prop("selectedIndex", 0);
    $('#catprod1_imp').val('');
    $('#catprod2_imp').val('');
    $('#catprod3_imp').val('');
    $("#fuente_imp").prop("selectedIndex", 0);
    $('#gasto_imp').prop("selectedIndex", 0);
    // $('#denominacion_imp').val('');
    $('#afectacion_imp').val('');

    $('#cant_imp').text(imputaciones.length);
});

$(document).on('click', '.eliminar_compromiso', function(e){
    for (var i = compromisos.length - 1; i >= 0; i--) {
        if (compromisos[i].numero == this.value) {
            if(compromisos[i].id != null){
                compromisos[i].eliminado = 1;
            }else{
                compromisos.splice(i, 1);
            }
        }
    }

    $('#cant_rc').text(compromisos.filter(rc => rc.eliminado == null).length);
    $(this).parents('tr').remove();
});

$(document).on('click', '.eliminar_op', function(e){
    for (var i = ordenesPago.length - 1; i >= 0; i--) {
        if (ordenesPago[i].numero == this.value) {
            if(ordenesPago[i].id != null){
                ordenesPago[i].eliminado = 1;
            }else{
                ordenesPago.splice(i, 1);
            }
        }
    }

    $('#cant_op').text(ordenesPago.filter(op => op.eliminado == null).length);
    $(this).parents('tr').remove();
    calcularOPPagado();
});

$(document).on('click', '.eliminar_imputacion', function(e){
    if(imputaciones[this.value].id != null){
        imputaciones[this.value].eliminado = 1;
    }else{
        imputaciones.splice(this.value - 1, 1);
    }

    $('#cant_imp').text(imputaciones.filter(im => im.eliminado == null).length);
    $(this).parents('tr').remove();
});

$('.cat-prod').keypress(function(e){
    if(this.value.length > 1){
        this.value = this.value.slice(0,1); 
    }
});

function completeTables(listRC, listOP, listImputaciones){
    compromisos = listRC;
    ordenesPago = listOP;
    imputaciones = listImputaciones;

    $('#table_compromiso tbody').children().remove();
    $('#table_op tbody').children().remove();
    $('#table_imputacion tbody').children().remove();

    
    if(compromisos.length > 0){
        compromisos.forEach(function(m, k){
            let tr = '<tr>\
                <td>'+m.numero+'</td>\
                <td>'+m.fecha+'</td>\
                <td>'+formatNumber(m.importe)+'</td>\
                <td><button type=\'button\' class=\'btn btn-danger eliminar_compromiso\' value=\''+m.numero+'\'>ELIMINAR</button></td>\
            </tr>';

            $('#table_compromiso tbody').append(tr);
        });
    }

    if(ordenesPago.length > 0){
        ordenesPago.forEach(function(m, k){
            let nOcea = (m.numeroOcea == null) ? '' : m.numeroOcea;
            let dOcea = (m.fechaOcea == null) ? '' : m.fechaOcea;
            let pagad = (m.pagado == null) ? '' : m.pagado;
            let tr = '<tr>\
                <td>'+m.fecha+'</td>\
                <td>'+m.numero+'</td>\
                <td>'+formatNumber(m.importe)+'</td>\
                <td>'+pagad+'</td>\
                <td>'+m.ocea+'</td>\
                <td>'+nOcea+'</td>\
                <td>'+dOcea+'</td>\
                <td><button type=\'button\' class=\'btn btn-danger eliminar_op\' value=\''+m.numero+'\'>ELIMINAR</button></td>\
            </tr>';

            $('#table_op tbody').append(tr);
        });
    }
    
    if(imputaciones.length > 0){
        imputaciones.forEach(function(m, k){
            let tr = '<tr>\
                <td>'+$('#jurisdiccion_imp option[value=\''+m.jurisdiccion+'\']').text()+'</td>\
                <td>'+m.categoria1+'.'+m.categoria2+'.'+m.categoria3+'.</td>\
                <td>'+$('#fuente_imp option[value=\''+m.fuente+'\']').text()+'</td>\
                <td>'+$('#gasto_imp option[value=\''+m.gasto+'\']').text()+'</td>\
                <td>'+$('#afectacion_imp option[value=\''+m.afectacion+'\']').text()+'</td>\
                <td><button type=\'button\' class=\'btn btn-danger eliminar_op\' value=\''+imputaciones.length+'\'>ELIMINAR</button></td>\
            </tr>';

            $('#table_imputacion tbody').append(tr);
        });
    }

    $('#cant_rc').text(compromisos.length);
    $('#cant_op').text(ordenesPago.length);
    $('#cant_imp').text(imputaciones.length);

    calcularOPPagado();
}

async function displayTable(){
    await showLoading('Buscando...');

    filterTable = Obras;

    if(filterDesde !== undefined){
        filterTable = filterTable.filter(t => t.fechaCreado >= filterDesde+' 00:00:00' || filterDesde+' 00:00:00' <= t.fechaCreado);
    }

    if(filterHasta !== undefined){
        filterTable = filterTable.filter(t => t.fechaCreado <= filterHasta+' 23:59:59' || filterHasta+' 23:59:59' >= t.fechaCreado);
    }

    if(filterObra !== undefined){
        filterTable = filterTable.filter(t => t.nombre.includes(filterObra.toUpperCase()));
    }

    if(filterExpt !== undefined){
        filterTable = filterTable.filter(t => t.expediente.includes(filterExpt.toUpperCase()));
    }

    if(filterExptAnio !== undefined){
        filterTable = filterTable.filter(t => t.anioExpediente == filterExptAnio);
    }

    if(filterEstado !== undefined){
        filterTable = filterTable.filter(t => t.datosAdicionales.idEstado == filterEstado);
    }

    if(filterEjecutora !== undefined){
        filterTable = filterTable.filter(t => t.idUnidadEjecutora == filterEjecutora);
    }

    if(filterImputado !== undefined){
        filterTable = filterTable.filter(t => t.imputado == filterImputado);
    }

    if(filterProveedor !== undefined){
        filterTable = filterTable.filter(t => t.idProveedor == filterProveedor);
    }

    console.log(filterTable)

    xls = new xlsExport(getExcelData(filterTable), 'Obras');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    await hideLoading();
}

function getExcelData(array){
    // let excelData = [];
    let excelData = [];

    array.forEach(function(t){
        let data;
        let proveedor = Proveedores.find(p => p.idProveedor == t.idProveedor);
        let ejecutora = Ejecutoras.find(p => p.idUnidadEjecutora == t.idUnidadEjecutora);
        let modalidad = Modalidades.find(p => p.idModalidad == t.datosAdicionales.idModalidad);
        let estado = Estados.find(p => p.idEstado == t.idEstado);
        let tipoObra = TiposObras.find(p => p.idTipoObra == t.datosAdicionales.idTipoObra);

        let paid = t.ordenesPago.filter(op => op.pagado != '').reduce((acc, currentOrder) => {
            return acc + parseInt(currentOrder.importe);
        }, 0);

        console.log(modalidad,1)

        data = {
            'Obra': t.nombre,
            'Proyecto': t.datosAdicionales.cCodigo+' - '+t.datosAdicionales.cNombre,
            'Nº Expte': t.expediente,
            'Año Expte': t.anioExpediente,
            'Unidad Ejecutora': ejecutora.cCodigo+' - '+ejecutora.cNombre,
            'Proveedor': (proveedor !== undefined) ? proveedor.cNombre : '',
            'Importe Estimado': formatNumber(t.importeEstimado),
            'Importe Definitivo': formatNumber(t.importeDefinitivo),
            'Imputado': t.imputado,
            'Pagado': formatNumber(paid),
            'Observaciones': t.observaciones,
            'Contratación': (modalidad !== undefined) ? modalidad.cNombre : '',
            'Nº Contratación': t.datosAdicionales.nModalidad,
            'Año Contratación': t.datosAdicionales.nModalidadAnio,
            'Estado': (estado !== undefined) ? estado.cNombre : '',
            'Tipo de obra': (tipoObra !== undefined) ? tipoObra.cNombre : '',
            'Plazo duración': (t.datosAdicionales.nPlazo != null) ? t.datosAdicionales.nPlazo+' '+ t.datosAdicionales.cPlazo : '',
            'Fecha Creacion': t.fechaCreado
        }
        excelData.push(data);
    });

    // console.log(excelData)

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Obras.xls');
});
