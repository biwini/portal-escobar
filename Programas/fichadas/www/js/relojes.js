let DataTable;
let listRelojes = [];
let xls;
$(document).ready(start());

async function start(){
    limpiar();

    listRelojes = await(getData());

    xls = new xlsExport(getExcelData(listRelojes), 'Relojes');

    DataTable = $('#grilla_relojes').DataTable({
        "language": {
            "url": "src/Datatables/Spanish.json"
        },
        "data": listRelojes,
        "deferRender":true,
        "scrollX":true,
        "scrollCollapse":true,
        "lengthMenu":[[10, 20, 25, 50, -1], [10, 20, 25, 50, "Todos"]],
        "iDisplayLength":10,
        "columnDefs": [
            { className: "test", "targets": [ 3 ] }
        ],
        "columns":[
            { "data": "Codigo"},
            { "data": "Descripcion"},
            { "data": "Dependencia",
                "render":function(data, type, full, meta){
                    return getDependenceName(full.Dependencia);
                }
            },
            { "data": "DireccionIP"},
            { "data": "descripcion_ultimo_error"},
            { "data": "fecha_ultimo_error"},
            { "data": "acciones",
                "render":function(data, type, full, meta){
                    let wBoton="<button class='ui green basic button mostrar_relojes' ><i class='eye icon'></i>Ver</button>";
                    return "<input type='hidden' class='id_registro_reloj' data-cant='"+full.Codigo+"' value='"+full.Codigo+"'>"+ wBoton+
                    "<button class='ui negative basic button del_practica'><i class='icon close'></i> Quitar</button>";
                }
            },
        ],
    });

    $(".searchSelect").dropdown({
        message: {
            noResults     : 'No se encontraron resultados.'
        },
        fullTextSearch: true,
        clearable: true
    });
    $("#date,#searchAmbDate").val(moment().format("YYYY-MM-DD"));
	$("#buttonTabListRelojes").click(showRelojes);
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
            },
            direccionip: {
                identifier: 'direccionip',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe cargar la direccion ip del reloj'
                    }
                ]
            },
            puerto: {
                identifier: 'puerto',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Debe cargar el puerto del reloj'
                    }
                ]
            }
        }
    });

    $("#save_all").click(function(){
        $("#formulario_reloj").form("submit");
        if($("#formulario_reloj").form("is valid")){
            save_reloj();
        }
    });

    $("#save_edit").click(function(){
        $("#formulario_reloj").form("submit");
        if($("#formulario_reloj").form("is valid")){
            save_reloj();
            limpiar();
            $("#save_all").removeAttr("style");
            $("#contentEdit").hide();
            $("#formulario_reloj").attr("class","ui f14 form");
        }
        $('#last_error').css('display', 'none');
    });

    function save_reloj(){
        let formPac=$("#formulario_reloj");

            let formData={
                page: 'Relojes',
                action:"send",
                codigo: getFormVal(formPac,"codigo"),
                descripcion: getFormVal(formPac,"descripcion"),
                secretaria: getFormVal(formPac,"secretaria"),
                dependencia: getFormVal(formPac,"dependencia"),
                usuario: getFormVal(formPac,"usuario"),
                clave: getFormVal(formPac,"clave"),
                ubicacion: getFormVal(formPac,"ubicacion"),
                marca: getFormVal(formPac,"marca"),
                modelo: getFormVal(formPac,"modelo"),
                direccionip: getFormVal(formPac,"direccionip"),
                puerto: getFormVal(formPac,"puerto"),
                id: getFormVal(formPac,"id")
            };

            $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    if(data['status']=="error"){                        
                        mensaje('fail',data['output']);
                    }
                    else{
                        formPac.form("reset");
                        
                        
                        showRelojes();
                    }
                },
                error:function(data){
                    mensaje('fail','Error');
                }
            });
    }
	
    $("#limpiar_controles").click(function(){
        limpiar();
    });

    function limpiar(){
        $("#formulario_reloj").form("clear");
    
        $.getJSON(url+"?page=Relojes&action=getLastID",function(data){
            $.each(data,function(key,value){
                console.log(value.UltimoID);
                $("#codigo").val(value.UltimoID);
            });
        });

        $("#save_all").removeAttr("style");
        $("#contentEdit").hide();
        $("#formulario_reloj").attr("class","ui f14 form");
        $('#last_error').css('display', 'none');
    }

	$("#grilla_relojes").on("click",".del_practica",function(){
		console.log("click eliminar" + $(this).parent().find(".id_registro_reloj").val());
        //mensaje('fail','Error');
		if(confirm("¿Desea eliminar?")){
            let formData={
                page: 'Relojes',
                action:"eliminar",
                codigo: $(this).parent().find(".id_registro_reloj").val(),
            };
            $.ajax({
                type: "post",
                dataType: "json",
                url: url,
                data:formData,
                beforeSend:function(){
                },
                success:function(data){
                    if(data['status']=="error"){                        
                        mensaje('fail',data['output']);
                    }
                    else{
                        showRelojes();
                    }
                },
                error:function(data){
                    mensaje('fail','Error');
                }
            });
		}
           
	});

	$("#grilla_relojes").on("click",".mostrar_relojes",function(){
		//console.log();
		$.getJSON(url+"?page=Relojes&action=search&id="+$(this).parent().find(".id_registro_reloj").val(),function(data){
            $("#menuTab .item").tab("change tab","newReloj");
            $.each(data,function(key,value){
                //value.Codigo
                $("#id").val(value.id);
                $("#codigo").val(value.Codigo);
                $("#descripcion").val(value.Descripcion);
                $("#direccionip").val(value.DireccionIP);
                $("#puerto").val(value.Puerto);
                $("#usuario").val(value.Usuario);
                $("#marca").val(value.Marca);
                $("#clave").val(value.Clave);
                $("#modelo").val(value.Modelo);
                $("#ubicacion").val(value.Ubicacion);
                $("#observaciones").val(value.Observaciones);
                $('#fecha_ultimo_error').val(value.fecha_ultimo_error);
                $('#descripcion_ultimo_error').val(value.descripcion_ultimo_error);

                $("#idEditEmployee").val(value.id);
                $("#save_all").hide();
                $("#contentEdit").removeAttr("style");

                $("#secretaria").val(value.Secretaria).change();
                $("#dependencia").val(value.Dependencia).change();	  

                $('#last_error').css('display', 'block');
            });
        });
	});
	
    async function showRelojes(){
        listRelojes = await getData();

        $("#grilla_relojes tbody").html("");
        $("#menuTab .item").tab("change tab","listRelojes");
        $('#last_error').css('display', 'none');
        DataTable.rows().remove().draw();
        DataTable.rows.add(listRelojes);
        DataTable.columns.adjust().draw();

    };
    function getData(){
        return new Promise(resolve => { 
            let list = [];
    
            resolve(
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {'page':'Relojes','action':'get'},
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
}

async function displayTable(modal = true){
    if(modal){
        await showLoading();
    }

    let filterTable = listRelojes;

    if(filterSec !== undefined){
        filterTable = filterTable.filter(t => t.idSecretaria == filterSec);
    }

    if(filterDep !== undefined){
        filterTable = filterTable.filter(t => t.idDependencia == filterDep);
    }

    if(filterReloj !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.Descripcion != ''){
                return t.Descripcion.toUpperCase().includes(filterReloj.toUpperCase());
            }
        });
    }

    if(filterIp !== undefined){
        filterTable = filterTable.filter(function(t){
            if(t.DireccionIP != null && t.DireccionIP != ''){
                return t.DireccionIP.includes(filterIp);
            }
        });
    }

    xls = new xlsExport(getExcelData(filterTable), 'Relojes');

    DataTable.rows().remove().draw();
    DataTable.rows.add(filterTable);
    DataTable.columns.adjust().draw();

    await hideLoading();
}

function getExcelData(array){
    let excelData = [];

    array.sort((a, b) => a.Descripcion.localeCompare(b.Descripcion))

    array.forEach(function(t){
        data = {
            'Codigo': t.Codigo,
            'Descripcion': t.Descripcion,
            'Secretaria': getSecretaryName(t.Secretaria),
            'Dependencia': getDependenceName(t.Dependencia),
            'DireccionIP': t.DireccionIP,
            'Descripcion Ultimo Error': t.descripcion_ultimo_error,
            'Fecha Ultimo error': t.fecha_ultimo_error
        }

        excelData.push(data);
    });

    return excelData;
}

$('#import_to_excel').click(function(){
    xls.exportToXLS('Relojes.xls');
});

function getFormVal(form,id){
    return form.form("get field",id).val();
}
