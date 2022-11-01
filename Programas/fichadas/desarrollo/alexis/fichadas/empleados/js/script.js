$(document).ready(start);

function start(){
    $("#menuTab .item").tab();
    
    getSecretary();
    getEmployeeTypes();
    getDependence();
    getEmployeesAmount();
    $("#typeDoc,#gender,#stateCivil").dropdown();
    $("#buttonTabListEmployee").click(getEmployeesAmount);
    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true
    });
    $(".filterTable").dropdown({
        onChange: function(value, text, $selectedItem) {
            closeBtn=$(this).parent().find(".closeFilterTable");
            closeBtn.removeAttr("style");
            getEmployeesAmount();
        }
    });
    $(".closeFilterTable").click(function(){
        filterTable=$(this).parent().find(".filterTable");
        filterTable.dropdown("restore defaults");
        $(this).hide();
    });
    $("#searchEmployee").click(getEmployeesAmount);
    
    $('#formEmployee').form({
        keyboardShortcuts:false,
        fields: {
            fullName: {
                identifier: 'fullName',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir el nombre y apellido'
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
            cuit: {
                identifier: 'cuit',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe escribir un numero de cuit'
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
            },
            employeeTypes: {
                identifier: 'employeeTypes',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe elegir un tipo de empleado'
                    }
                ]
            }
        }
    });
    $("#saveNewEmployee").click(function(){
        form=$("#formEmployee");
        form.form("submit");
        if(form.form("is valid")){
           var formData={
               action:"insertEmployee",
               values:{
                    ":fullname":getFormVal(form,"fullName"),
                    ":typeDoc":getFormVal(form,"typeDoc"),
                    ":nroDoc":getFormVal(form,"nroDoc"),
                    ":cuit":getFormVal(form,"cuit"),
                    ":legajo":getFormVal(form,"legajo"),
                    ":address":getFormVal(form,"address"),
                    ":gender":getFormVal(form,"gender"),
                    ":stateCivil":getFormVal(form,"stateCivil"),
                    ":birthDate":getFormVal(form,"birthDate"),
                    ":email":getFormVal(form,"email"),
                    ":phone":getFormVal(form,"phone"),
                    ":secretary":getFormVal(form,"secretary"),
                    ":dependence":getFormVal(form,"dependence"),
                    ":employeeTypes":getFormVal(form,"employeeTypes")
               }
           };
           $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/employee.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    if(data['status']=="error"){
                        console.log(data);
                        alert("Ocurrio un error");
                    }
                    else{
                        form.form("reset");
                        getEmployeesAmount();
                        $("#menuTab .item").tab("change tab","listEmployee");
                    }
                }
            });
        }
    });
    
    $("#saveEditEmployee").click(function(){
        if(confirm("Esta seguro de editar al empleado?")){
            form=$("#formEmployee");
            form.form("submit");
            if(form.form("is valid")){
            var formData={
                action:"editEmployee",
                id:$("#idEditEmployee").val(),
                values:{
                        ":id":$("#idEditEmployee").val(),
                        ":fullname":getFormVal(form,"fullName"),
                        ":typeDoc":getFormVal(form,"typeDoc"),
                        ":nroDoc":getFormVal(form,"nroDoc"),
                        ":cuit":getFormVal(form,"cuit"),
                        ":legajo":getFormVal(form,"legajo"),
                        ":address":getFormVal(form,"address"),
                        ":gender":getFormVal(form,"gender"),
                        ":stateCivil":getFormVal(form,"stateCivil"),
                        ":birthDate":getFormVal(form,"birthDate"),
                        ":email":getFormVal(form,"email"),
                        ":phone":getFormVal(form,"phone"),
                        ":secretary":getFormVal(form,"secretary"),
                        ":dependence":getFormVal(form,"dependence"),
                        ":employeeTypes":getFormVal(form,"employeeTypes")
                }
            };
            $.ajax({
                    type: "post",
                    dataType: "json",
                    url:"inc/employee.php",
                    data:formData,
                    beforeSend:function(){
                    },
                    success:function(data){
                        if(data['status']=="error"){
                            console.log(data);
                            alert("Ocurrio un error");
                        }
                        else{
                            form.form("reset");
                            $("#saveNewEmployee").removeAttr("style");
                            $("#contentEdit").hide();
                            form.attr("class","ui black segment form");
                            getEmployeesAmount();
                            $("#menuTab .item").tab("change tab","listEmployee");
                        }
                    }
                });
            }
        }
    });
    $("#cancelEditEmployee").click(function(){
        form=$("#formEmployee");
        form.form("reset");
        $("#saveNewEmployee").removeAttr("style");
        $("#contentEdit").hide();
        form.attr("class","ui black segment form");
    });
    $("#tableEmployeesPaginated").on("click",".delEmployee",function(){
        if(confirm("Esta seguro de eliminar al empleado?")){
           var formData={
               action:"deleteEmployee",
               values:{
                    ":id":$(this).attr("data-id")
               }
           };
           $.ajax({
                type: "post",
                dataType: "json",
                url:"inc/employee.php",
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    if(data['status']=="error"){
                        console.log(data);
                        alert("Ocurrio un error");
                    }
                    else{
                        getEmployeesAmount();
                    }
                }
            });
        }
    });
    $("#tableEmployeesPaginated").on("click",".editEmployee",function(){
        id=$(this).attr("data-id");
        if(id==null){
            console.log(id);
            id=$(this).attr("data-id");
        }
        form=$("#formEmployee");
        $(this).attr("disabled","disabled");
        $.getJSON("inc/employee.php?action=getEmployee&id="+id,function(data){
            form.form("reset");
            form.form('set values', {
                fullName:data[0].apellidoNombre,
                typeDoc:data[0].tipoDocumento,
                nroDoc:data[0].nroDocumento,
                cuit:data[0].cuit,
                legajo:data[0].legajo,
                gender:data[0].sexo,
                address:data[0].direccion,
                birthDate:data[0].fechaNacimiento,
                email:data[0].email,
                phone:data[0].telefono,
                stateCivil:data[0].estadoCivil
            });
            $("#secretary").val(data[0].idSecretaria).change();
            $("#dependence").val(data[0].idDependencia).change();
            $("#employeeTypes").val(data[0].idTipoEmpleado).change();
            $("#idEditEmployee").val(id);
            $("#saveNewEmployee").hide();
            $("#contentEdit").removeAttr("style");
            form.attr("class","ui blue segment form");
            $(".editEmployee").removeAttr("disabled");
            $("#menuTab .item").tab("change tab","newEmployee");
        });
    });
}
function getSecretary(){
    $.getJSON("inc/secretary.php?action=getAll",function(data){
        $.each(data,function(key,value){
            $("#secretary").append("<option value='"+value.id+"'>"+value.nombre+"</option>");
            $("#filterSecretaryTable .scrolling").append("<div class='item' data-value='"+value.id+"'>"+value.nombre+"</div>");
            
        }); 
        $("#filterSecretaryTable").dropdown({
            message: {
                noResults     : 'No se encontraron resultados.'
            },
            fullTextSearch: true,
            clearable: true,
            onChange: function(value, text, $selectedItem) {
                closeBtn=$(this).parent().find(".closeFilterTable");
                closeBtn.removeAttr("style");
                getEmployeesAmount();
            }
        });
        
    });
}
function getEmployeeTypes(){
    $.getJSON("inc/employee.php?action=getAllEmployeeTypes",function(data){
        $.each(data,function(key,value){
            name=value.nombre;
            (value.descripcion)?name=value.nombre+" - "+value.descripcion:null;
            $("#employeeTypes").append("<option value='"+value.id+"'>"+name+"</option>");
            $("#filterEmployeeTypesTable .scrolling").append("<div class='item' data-value='"+value.id+"'>"+name+"</div>");
        });
        $("#filterEmployeeTypesTable").dropdown({
            message: {
                noResults     : 'No se encontraron resultados.'
            },
            fullTextSearch: true,
            clearable: true,
            onChange: function(value, text, $selectedItem) {
                closeBtn=$(this).parent().find(".closeFilterTable");
                closeBtn.removeAttr("style");
                getEmployeesAmount();
            }
        });
    });
}
function getDependence(){
    $.getJSON("inc/dependence.php?action=getAll",function(data){
        $.each(data,function(key,value){
            name=value.nombre;
            (value.descripcion)?name=value.nombre+" - "+value.descripcion:null;
            $("#dependence").append("<option value='"+value.id+"'>"+name+"</option>");
            $("#filterDependenceTable .scrolling").append("<div class='item' data-value='"+value.id+"'>"+name+"</div>");
        });
        $("#filterDependenceTable").dropdown({
            message: {
                noResults     : 'No se encontraron resultados.'
            },
            fullTextSearch: true,
            clearable: true,
            onChange: function(value, text, $selectedItem) {
                closeBtn=$(this).parent().find(".closeFilterTable");
                closeBtn.removeAttr("style");
                getEmployeesAmount();
            }
        });
    });
}
function getEmployeesPaginated(pag,s){
    var formData={
        action:"getEmployeesPaginated",
        from:pag[0],
        to:pag[pag.length-1],
        search:s
    };
    $.ajax({
        type: "post",
        dataType: "json",
        url:"inc/employee.php",
        data:formData,
        beforeSend:function(){
        },
        success:function(data){
            $("#tableEmployeesPaginated tbody").html("");
            $.each(data,function(key,value){
                $("#tableEmployeesPaginated tbody").append("<tr><td class='collapsing'>"+value.apellidoNombre+"</td><td class='collapsing'>"+value.nombreSecretaria+"</td><td class='collapsing'>"+value.nombreDependencia+"</td><td class='collapsing'>"+value.nombreTipoEmpleado+"</td><td class='center aligned collapsing'><button class='mini ui primary basic button editEmployee' data-id='"+value.id+"'><i class='icon edit editEmployee'></i>Editar</button><button class='mini ui negative basic button delEmployee' data-id='+value.id+'><i class='icon close'></i>Eliminar</button></td>");
            }); 
        }
    });
}
function getEmployeesAmount(){
    var s={};
    ($("#searchFullName").val())?s['fullName']=$("#searchFullName").val():null;
    ($("#filterSecretaryTable").dropdown("get value"))?s['secretaryId']=$("#filterSecretaryTable").dropdown("get value"):null;
    ($("#filterDependenceTable").dropdown("get value"))?s['dependenceId']=$("#filterDependenceTable").dropdown("get value"):null;
    ($("#filterEmployeeTypesTable").dropdown("get value"))?s['employeeTypeId']=$("#filterEmployeeTypesTable").dropdown("get value"):null;
    var formData={
        action:"getEmployeesAmount",
        search:s
    };
    $.ajax({
        type: "post",
        dataType: "json",
        url:"inc/employee.php",
        data:formData,
        beforeSend:function(){
        },
        success:function(data){
            $.each(data,function(key,value){
                $("#employeesAmount").val(value.total);
                if(value.total==0){
                    $("#labelEmployeesAmount").html("Sin resultados");
                    $("#tableEmployeesPaginated tbody").html("");
                }
                else{
                    $("#labelEmployeesAmount").html(value.total+((value.total>1)?" resultados":" resultado"))
                    $('#paginationTable').pagination({
                        dataSource: function(done){
                            var result = [];
                            for (var i = 1; i <= parseInt(value.total); i++) {
                                result.push(i);
                            }
                            done(result);
                        },
                        showPageNumbers: false,
                        showNavigator: true,
                        showGoInput: true,
                        showGoButton: true,
                        goButtonText:"Ir",
                        callback: function(data, pagination) {
                            getEmployeesPaginated(data,s);
                        }
                    });
                }
                
            }); 
        }
    });
}
// function getLocality(){
//     $.getJSON("inc/locality.php?action=getAll",function(data){
//         $.each(data,function(key,value){
//             $("#locality").append("<option value='"+value.id+"'>"+value.nombre+"</option>");
//         });        
//     });
// }
function getFormVal(form,id){
    return form.form("get field",id).val();
}
function setFormVal(form,id,value){
    return form.form("set value",id,value)
}