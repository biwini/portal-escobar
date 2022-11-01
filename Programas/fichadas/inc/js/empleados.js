let Datatable;
let listEmployee;
let DataTableLicence;
let xls;

$(document).ready(start());

async function start(){
    $("#menuTab .item").tab();

    await showLoading();

    listEmployee = await getData();
    
    await getHorarios();

    xls = new xlsExport(getExcelData(listEmployee), 'Empleados');

    $("#typeDoc,#gender,#stateCivil,#typePhone").dropdown();

    $("#buttonTabListEmployee").click(async function(){
        listEmployee = await getData();
        DataTable.columns.adjust().draw(false);
    });

    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true
    });

    DataTable = $('#tableEmployeesPaginated').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": listEmployee,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columns":[
            { "data": "Nombre",
                "render":function(data, type, full, meta){
                    return full.nombre+" "+full.apellido;
                }
            },
            { "data": "dependencia",
                "render":function(data, type, full, meta){
                    return getDependenceName(full.idDependencia);
                }
            },
            {"data": "legajo"},
            {"data": "nroDocumento"},
            {"data": "nombreTipoEmpleado"},
            {"data": "nombre_horario"},
            { "data": "acciones",
                "render":function(data, type, full, meta){
                    return "<a href='empleados?view="+full.id+"' class='f10 mini ui info basic green button' data-id='"+full.id+"' value=\""+full.id+"\" title=\"Ver\"><i class='icon-eye'></i></a>"+
                        "<button class='f10 mini ui primary basic button editEmployee' data-id='"+full.id+"' value=\""+full.id+"\" title=\"Editar\"><i class='icon edit edit'></i>EDITAR</button>"+
                        '<button title=\'Eliminar\' type=\'button\' class=\'icon-bin btn btn-md btn-danger delete\' value=\''+full.id+'\' title=\"Eliminar\"></button> ';
                }
            },
        ],
    });

    DataTableLicence = $('#tb_history_licence').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": [],
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columns":[
            { "data": "Comienzo",
                "render":function(data, type, full, meta){
                    let now = new Date(full.dFechaInicio);
                    let date = (now.getUTCDate() < 10) ? '0'+now.getUTCDate() : now.getUTCDate();
                    let month = (now.getUTCMonth() < 9) ? '0'+(now.getUTCMonth() + 1) : now.getUTCMonth() + 1; // Since getUTCMonth() returns month from 0-11 not 1-12
                    let year = now.getUTCFullYear();
                        
                    let dateStr = date + '-' + month + '-' + year;
    
                    return now.getDayName()+' '+dateStr;
                }
            },
            { "data": "Fin",
                "render":function(data, type, full, meta){
                    let now = new Date(full.dFechaFin);
                    let date = (now.getUTCDate() < 10) ? '0'+now.getUTCDate() : now.getUTCDate();
                    let month = (now.getUTCMonth() < 9) ? '0'+(now.getUTCMonth() + 1) : now.getUTCMonth() + 1; // Since getUTCMonth() returns month from 0-11 not 1-12
                    let year = now.getUTCFullYear();
                        
                    let dateStr = date + '-' + month + '-' + year;
    
                    return now.getDayName()+' '+dateStr;
                }
            },
            { "data": "Dur",
                "render":function(data, type, full, meta){
                    let date1 = new Date(full.dFechaInicio);
                    let date2 = new Date(full.dFechaFin);
                    let diffTime = Math.abs(date2 - date1);
                    let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
    
                    return diffDays;
                }
            },
            { "data": "cMotivo"},
            { "data": "empleado",
                "render":function(data, type, full, meta){
                    return '<a href=\'empleados?view='+full.idEmpleado+'\'>'+full.apellido+' '+full.nombre+'<a>';
                }
            },
            { "data": "nroDocumento"},
            { "data": "legajo"},
            { "data": "Notas",
                "render":function(data, type, full, meta){
                    return '<div class=\'ui button icon icon-file-text\' style=\'background: center;\' data-inverted data-tooltip=\''+full.cNotas+'\' data-position=\'left center\'>\
                        <span class=\'icon icon-file-text text-center span-notas\' style=\'font-size: 2rem; color: cornflowerblue;\'></span>\
                    </div>';
    
                    // return '<div style=\'text-align:center\'><span class=\'icon icon-file-text text-center span-notas\' style=\'font-size: 2rem; color: cornflowerblue;\'></span>\
                    //     <div class=\'ui button popup\' data-title=\'Notas\' data-content=\''+full.cNotas+'\' data-position=\'left center\'></div>\
                    // </div>';
                }
            },
        ]
    });

    $("#searchEmployee").click();
    
    $('#formEmployee').form({
        keyboardShortcuts:false,
        fields: {
            name: {
                identifier: 'name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir el nombre'
                    }
                ]
            },
            surname: {
                identifier: 'surname',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir el apellido'
                    }
                ]
            },
            typeDoc: {
                identifier: 'typeDoc',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir un tipo de documento'
                    }
                ]
            },
            nroDoc: {
                identifier: 'nroDoc',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir un numero de documento'
                    }
                ]
            },
            birthDate: {
                identifier: 'birthDate',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe poner la fecha de nacimiento'
                    }
                ]
            },
            secretary: {
                identifier: 'secretary',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir una secretaria'
                    }
                ]
            },
            dependence: {
                identifier: 'dependence',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir una dependencia'
                    }
                ]
            }
        }
    });

    await hideLoading();

    $("#saveNewEmployee").click(function(){
        let form = $("#formEmployee");
        form.form("submit");

        values = form.form('get values');
        values['page'] = 'Empleados';
        values['action'] = 'insertEmployee';

        if(form.form("is valid")){
            if($('#cuit').val() != ''){
                if(!isCuitValid(FirstTwoDigit+dniInside+LastOneDigit)){
                    alert('El cuit ingresado es invalido');
                    return false;
                }
        
                if(obtenerVerificador(parseInt(FirstTwoDigit),parseInt(dniInside)) != LastOneDigit){
                    alert('El cuit ingresado es invalido revise el Numero verificador');
                    return false;
                }
            }

           $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                data:values,
                beforeSend:function(){
                },
                success:function(data){
                    if(data['status'] == 'error'){
                        console.log(data);
                        swal(data['output'],'','warning');
                    }
                    else{
                        form.form("reset");
                        swal('Se reistro el empleado','','success');
                        actualizarDatos();
                        // $('#buttonTabListEmployee').click();
                    }
                }
            });
        }
    });
    
    $("#saveEditEmployee").click(function(){
        let form = $("#formEmployee");
        form.form("submit");
        if(form.form("is valid")){
            swal({
                title: "!Modificando Empleado!",
                text: "¿Seguro que desea modificar este empleado?",
                icon: "",
                buttons: {
                cancel: "No",
                Si: true,
                },
            })
            .then( async (willDelete) => {
                if (willDelete) {
                        if($('#cuit').val() != ''){
                            if(!isCuitValid(FirstTwoDigit+dniInside+LastOneDigit)){
                                alert('El cuit ingresado es invalido');
                                return false;
                            }
                    
                            if(obtenerVerificador(parseInt(FirstTwoDigit),parseInt(dniInside)) != LastOneDigit){
                                alert('El cuit ingresado es invalido revise el Numero verificador');
                                return false;
                            }
                        }

                        await showLoading('Editando empleado...');

                        values = form.form('get values');
                        values['page'] = 'Empleados';
                        values['action'] = 'editEmployee';
                        values['id'] = $("#idEditEmployee").val();

                        $.ajax({
                            type: "post",
                            dataType: "json",
                            url: url,
                            data:values,
                            beforeSend:function(){
                            },
                            success:function(data){
                                if(data['status'] == "error"){
                                    console.log(data);
                                    swal(data['output'],'','warning');
                                }
                                else{
                                    swal('Se Modifico el empleado','','success');
                                    form.form("reset");
                                    $("#saveNewEmployee").removeAttr("style");
                                    $("#contentEdit").hide();
                                    actualizarDatos();
                                }
                            }
                        });

                        await hideLoading();
                    
                }
            });
        }
    });

    $("#cancelEditEmployee").click(function(){
        let form=$("#formEmployee");
        form.form("reset");
        $("#saveNewEmployee").removeAttr("style");
        $('#idEditEmployee').val(0);
        $("#contentEdit").hide();
        // form.attr("class","ui black segment form");
    });

    $("#tableEmployeesPaginated").on("click",".delete",function(){
        swal({
            title: "!Eliminando Empleado!",
            text: "¿Seguro que desea eliminar este empleado?",
            icon: "warning",
            buttons: {
            cancel: "No",
            Si: true,
            },
        })
        .then( async (willDelete) => {
            if (willDelete) {
                let formData = {
                    action: 'deleteEmployee',
                    id: $(this).val(),
                    page: 'Empleados',      
                };
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: url,
                    data:formData,
                    beforeSend:function(){
                    },
                    success:function(data){
                        if(data['status'] == 'error'){
                            console.log(data);
                            swal(data['output'],'','warning');
                        }
                        else{
                            actualizarDatos();
                            swal('Se dio de baja el empleado','','success');
                        }
                    }
                });
            }
        });
    });

    $(document).on('click','.editEmployee',function(){
        $('#view_datos').hide();
        $('#view_abm').show();
        let id=$(this).attr('data-id');

        if(id==null){
            id=$(this).attr('data-id');
        }

        let form = $('#formEmployee');

        $(this).attr('disabled','disabled');

        $.getJSON(url+'?page=Empleados&action=getEmployee&id='+id,function(data){
            form.form('reset');

            form.form('set values', {
                name:data[0].nombre,
                surname:data[0].apellido,
                workHours:data[0].nHorasDia,
                date_admission:data[0].dFechaAdmision,
                typeDoc:data[0].tipoDocumento,
                nroDoc:data[0].nroDocumento,
                cuit:data[0].cuit,
                legajo:data[0].legajo,
                gender:data[0].sexo,
                address:data[0].direccion,
                birthDate:data[0].fechaNacimiento,
                email:data[0].email,
                typePhone: data[0].telefonos[0].cTipo.toUpperCase(),
                phone:data[0].telefonos[0].cNumero,
                stateCivil:data[0].estadoCivil
            });

            $('#secretaria').val(data[0].idSecretaria).change();
            $('#dependencia').val(data[0].idDependencia).change();
            $('#employeeTypes').val(data[0].idTipoEmpleado).change();
            $('#horario').val(data[0].id_horario).change();
            $('#idEditEmployee').val(id);
            $('#saveNewEmployee').hide();
            $('#contentEdit').removeAttr('style');
            // form.attr('class','f14 ui blue segment form');
            // $('#buttonTabNewEmployee').click();
            $('#menuTab .item').tab('change tab','newEmployee');
            $('#cuit').keyup();
        });
        $('.editEmployee').removeAttr('disabled');
    });

    start2();
}

$('#back').click(function(){
    $('#view_edit_employee').val('');
    $('#view_edit_employee').attr('data-id','');
    $('#view_datos').hide();
    $('#cancelEditEmployee').click();
    $('#view_form').show();
});

async function prepareView(id){
    await showLoading('Buscando empleado...');

    $('#view_edit_employee').attr('data-id',id);

    let empleado = await getData('getEmployee', id);

    await completeView(empleado[0]);
    let history = await getData('getHistory', empleado[0].id,'Licencias');

    DataTableLicence.rows().remove().draw();
    DataTableLicence.rows.add(history);

    $('#view_datos').show();
    $('#view_abm').hide();
    $('#menuTab .item').tab('change tab','newEmployee');

    DataTableLicence.columns.adjust().draw();

    await hideLoading();
}

function completeView(e){
    return new Promise(resolve => {      
        let telefonos = '';
        
        e.telefonos.forEach(function(t){
            telefonos += t.cTipo+': '+t.cNumero+' / ';
        });

        $('#employee_name').text('Empleado: '+e.nombre+' '+e.apellido);
        $('#birthDate_view').text(e.fechaNacimiento);
        $('#dni_view').text(e.nroDocumento);

        $('#sexo_view').text(e.sexo);
        $('#phone_view').text(telefonos);
        $('#legajo_view').text(e.legajo);
        $('#civil_view').text(e.estadoCivil);
        $('#address_view').text(e.direccion);

        $('#cuil_view').text(e.cuit);
        $('#horario_view').text($("#horario option[value='"+e.id_horario+"']").text());
        $('#email_view').text(e.email);

        resolve('resolved');
    });
}

async function actualizarDatos(){
    listEmployee = await getData();                        
    DataTable.rows().remove().draw();
    DataTable.rows.add(listEmployee);
    DataTable.columns.adjust().draw();
}

async function getHorarios(){
    $.getJSON(url+"?page=Horario&action=todos",function(data){
        $.each(data,function(key,value){
            $("#horario").append("<option value='"+value.id_horario+"'>"+value.nombre_horario+"</option>");
        });
    })
    .fail(function(data) {
         console.log(data)
    })
}

function getData(action = 'getEmployeesPaginated', id = 0, page = 'Empleados'){
    return new Promise(resolve => { 
        let list;

        resolve(
            $.ajax({
                type: "POST",
                url: url,
                data: {'page':page,'action':action,'id':id},
                dataType: "json",
            })
            .fail(function(data){
                mensaje('fail','Error Peticion ajax');
            })
            .done(function(data){
                list = data;
            })
        )
    });
}

function getFormVal(form,id){
    return form.form("get field",id).val();
}
function setFormVal(form,id,value){
    return form.form("set value",id,value)
}

async function displayTable(modal = true){
    if(modal){
        await showLoading();
    }

    let filterTable = listEmployee;

    if(filterSec !== undefined){
        filterTable = filterTable.filter(t => t.idSecretaria == filterSec);
    }

    if(filterDep !== undefined){
        filterTable = filterTable.filter(t => t.idDependencia == filterDep);
    }

    if(filterNombre !== undefined){
        filterTable = filterTable.filter(function(t){
            let fullName = t.nombre.toUpperCase()+' '+t.apellido.toUpperCase();

            if(fullName != ''){
                return fullName.includes(filterNombre.toUpperCase());
            }
        });
    }

    if(filterLegajo !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.legajo != null && t.legajo != ''){
                return t.legajo.includes(filterLegajo);
            }
        });
    }
    if(filterDni !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.nroDocumento != null && t.nroDocumento != ''){
                return t.nroDocumento.includes(filterDni);
            }
        });
    }

    if(filterTipoEmpleado !== undefined){
        filterTable = filterTable.filter(t => t.idTipoEmpleado == filterTipoEmpleado);
    }

    xls = new xlsExport(getExcelData(filterTable), 'Empleados');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    await hideLoading();
}

function getExcelData(array){
    let excelData = [];

    array.sort((a, b) => a.apellido.localeCompare(b.apellido))

    array.forEach(function(t){

        data = {
            'Nombre': t.nombre,
            'Apellido': t.apellido,
            'Documento': t.nroDocumento,
            'Cuit': t.cuit,
            'Fecha nacimiento': t.fechaNacimiento,
            'Estado civil': t.estadoCivil,
            'Direccion': t.direccion,
            'Telefono': t.telefono,
            'Email': t.email,
            'Legajo': t.legajo,
            'Secretaria': getSecretaryName(t.idSecretaria),
            'Dependencia': getDependenceName(t.idDependencia),
            'Tipo Empleado': t.nombreTipoEmpleado,
            'Horario': t.nombre_horario,
            'Horas dia': t.nHorasDia
        }

        excelData.push(data);
    });

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Empleados.xls');
});

let cuil, FirstTwoDigit = 0, dniInside = 0, LastOneDigit = 0;

$("#cuit").keyup(function(){
    cuil = $(this).val();

    if(cuil == ''){
        FirstTwoDigit = 0;
        dniInside = 0;
        LastOneDigit = 0;

        return false;
    }

    let t = cuil.substr(2);
    if(cuil.length == 2){
        FirstTwoDigit = cuil;
    }else if (cuil.length > 2) {
        if(FirstTwoDigit != cuil.substring(2,0)){
            FirstTwoDigit = cuil.substring(2,0);
        }

        dniInside = t.substr(0,t.length-1);
        LastOneDigit = cuil.charAt(cuil.length-1);
    }
});

function obtenerVerificador(tipo, numero){
    console.log(tipo, numero)
    numero = tipo * 100000000 + numero;

    var semillas = new Array(2, 3, 4, 5, 6, 7, 2, 3, 4, 5);
    var suma = 0;

    for(i = 0; i <= 9; ++i){
        pila = Redondear(numero, i);
        mascara = Redondear(numero, i + 1) * 10;

        suma += (pila - mascara) * semillas[i];
    }

    temporal = suma % 11;   
    resultado = 11-temporal;

    if(resultado == 11){
        resultado = 0;
    }
    if(resultado == 10){
        resultado = 9;
    }

    return resultado;
}

function Redondear(n, completar){
    divisor = Math.pow(10, completar);
    return Math.ceil((n + 0.01) / divisor) - 1;
}

function isCuitValid(cuit){
    const regexCuit = /^(20|23|24|27|30|33|34)([0-9]{9}|-[0-9]{8}-[0-9]{1})$/g;
    return regexCuit.test(cuit);
}