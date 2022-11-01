let smMunicipal = 0;
let subTotalColegio = 0;
let subTotalMultas = 0;
let descuento = 100;

$('#sm_municipal').keyup(function(){

    if (!isInt(this.value)){
        return 0;
    }

    smMunicipal = parseFloat(this.value);

    $('.smmunicipal').each(function(){
        this.value = smMunicipal;
    });
});

//Verificamos que el porcentaje maximo ingresado sea igual o menor a 100
$(document).on('keyup','.porcentaje', function(){ 
	if(this.value > 100){
		this.value = 100;
	}else if(this.value < 0){
		this.value = 0;
	}
});
//Solo numeros
$(document).on('keypress','.only-number', function(e){
    console.log(e.charCode)
    if(e.charCode >= 44 && e.charCode <= 57){
        return this.value.replace(/\D/g, ",")
                .replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ".");
    }
    return false;
})
// .on("cut copy paste",function(e){
//     e.preventDefault();
// });

//Seleccionar todo en el input automaticamente.
$(document).on('focus','input[type=text]', function(){ 
    this.select();
});
$(document).on('focus','input[type=number]', function(){   
    if (this.value == 0) {  
        this.select();
    }
});

$(document).on('focusout','input[type=number]', function(){   
    if (this.value < 0 || this.value == '' || this.value == undefined) {  
        this.value = 0;
    }
});

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

function calculateSubTotalColegio(table){
    let rows = $('#'+table+' tbody tr');
    subTotalColegio = 0;
    total = 0;

    rows.each(function(){
        switch (table) {
            case 'table_contrato_colegio_normal':
            case 'table_contrato_colegio_art126_2':
                monto = parseFloat($(this).find('input.monto').val());
                coef = parseFloat($(this).find('input.coef').val());
                recargo = parseFloat($(this).find('input.recargo').val());

                total = ((coef / 100) * monto)+((recargo / 100) * ((coef / 100) * monto));
            break;
            case 'table_contrato_colegio_art13':
            case 'table_contrato_colegio_art126':
                m2 = parseFloat($(this).find('input.m2').val());
                coef = parseFloat($(this).find('input.coef').val());
                referencial = parseFloat($(this).find('input.ref').val());

                total = (m2 * coef) * referencial;
            break;
            case 'table_electromecanico':
                m2 = parseFloat($(this).find('input.m2').val());
                capIX = parseFloat($(this).find('input.cap').val());

                total = m2 * capIX;
            break;
        }

        subTotalColegio += total;

        $(this).find('h3.total')[0].innerHTML = '$ '+number_format(total, 2, ',', '.');
    });

    // for (let i = 1; i <= rows; i++) {
    //     let monto = parseFloat($('#monto_value_'+i).val());
    //     let coef = parseFloat($('#coef_'+i).val());
    //     let recargo = parseFloat($('#recargo_'+i).val());

    //     subTotalColegio += ((coef / 100) * monto)+((recargo / 100) * ((coef / 100) * monto));
    // }

    return subTotalColegio;
}

function calculateSubTotalMultas(){
    let rows = $('#table_multas tbody tr').length;
    subTotalMultas = 0;

    for (let i = 0; i < rows; i++) {
        let m2 = parseFloat($('#m2_'+i).val());
        let cant = parseFloat($('#cant_'+i).val());
        let porcentaje = parseFloat($('#porcentaje_'+i).val());

        subTotalMultas += ((cant * smMunicipal) * m2) * (porcentaje / 100);
    }

    return subTotalMultas;
}

$(document).on('focusout','.contrato-colegio-normal', function(){   
    let table = $(this).parents('table')[0].id;

    document.getElementById('sub_total_colegio').innerHTML = '$ '+number_format(calculateSubTotalColegio(table), 2, ',', '.');

    setTotalLiqNormal();
});

$(document).on('focusout','.contrato-colegio-art13', function(){   
    let table = $(this).parents('table')[0].id;

    document.getElementById('sub_total_arttrc').innerHTML = '$ '+number_format(calculateSubTotalColegio(table), 2, ',', '.');
    document.getElementById('label_monto_obra_art13').innerHTML = '$ '+number_format(subTotalColegio, 2, ',', '.');
});

$(document).on('focusout','.contrato-colegio-art13-2', function(){   
    let capIX,total;

    capIX = parseInt($('#cg_arttrc_cap').val());

    total = subTotalColegio * (capIX / 100);

    document.getElementById('total_arttrc2').innerHTML = '$ '+number_format(total, 2, ',', '.');
    document.getElementById('total_liq').innerHTML = '$ '+number_format(total, 2, ',', '.');
});

$(document).on('focusout','.contrato-colegio-art126', function(){   
    let table = $(this).parents('table')[0].id;

    document.getElementById('sub_total_artvs').innerHTML = '$ '+number_format(calculateSubTotalColegio(table), 2, ',', '.');
    document.getElementById('artvs_monto').value = subTotalColegio;
});

$(document).on('focusout','.contrato-colegio-art126-2', function(){   
    let table = $(this).parents('table')[0].id;

    document.getElementById('sub_total_colegio_art126').innerHTML = '$ '+number_format(calculateSubTotalColegio(table), 2, ',', '.');

    setTotalLiqNormal();
});

$(document).on('focusout','.liquidacion-incendio', function(){   
    let m2,capIX,total;

    m2 = parseInt($('#incendio_m2').val());
    capIX = parseInt($('#inc_cap').val());

    total = m2 * capIX;

    document.getElementById('total_inc').innerHTML = '$ '+number_format(total, 2, ',', '.');
    document.getElementById('total_liq').innerHTML = '$ '+number_format(total, 2, ',', '.');
});

$(document).on('focusout','.tabla-electromecanico', function(){   
    let table = $(this).parents('table')[0].id;

    document.getElementById('total_liq').innerHTML = '$ '+number_format(calculateSubTotalColegio(table), 2, ',', '.');
});

$(document).on('focusout','.tb-multas', function(){   
    let row = $(this).parents('tr').index();
    let m2,cant,porcentaje,total;

    m2 = parseFloat($('#m2_'+row).val());
    cant = parseFloat($('#cant_'+row).val());
    porcentaje = parseFloat($('#porcentaje_'+row).val());

    total = ((cant * smMunicipal) * m2) * (porcentaje / 100);

    document.getElementById('total_multa_'+row).innerHTML = '$ '+number_format(total, 2, ',', '.');
    document.getElementById('sub_total_multa').innerHTML = '$ '+number_format(calculateSubTotalMultas(), 2, ',', '.');

    setTotalLiqNormal();
});

$('#descuento').keyup(function(){
    if(this.value == '' || this.value == undefined){
        descuento = 100;

        return false;
    }

    descuento = 100 - parseInt(this.value);

    setTotalLiqNormal();
});

function setTotalLiqNormal(){
    let subTotal = subTotalColegio + subTotalMultas;

    let totalLiq = subTotal * (descuento / 100);

    $('#sub_total_ab').text('$ '+ number_format(subTotal, 2, ',', '.'));
    $('#total_liq').text('$ '+ number_format(totalLiq, 2, ',', '.'));
}