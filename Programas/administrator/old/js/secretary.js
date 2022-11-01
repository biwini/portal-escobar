var ListSecretaria = new Array();
var SecretariaDataTable;
var ListDependencia = [];
var DependenciaDataTable;

$('#open_modal_dependencia').click(function(){
  $('#modal_charge_dependencia').modal('toggle');
});
$('#open_modal_secretaria').click(function(){
  $('#modal_charge_secretaria').modal('toggle');
});

$(document).on('submit', '#form_dependencia', function(e){
  e.preventDefault();
  var dependencia = $.trim($('#dependencia').val());
  var ubicacion = $.trim($('#ubicacion').val());

  if(dependencia.length >= 3){
    $.ajax({
      type: "POST",
      url: "controller/",
      data: $(this).serialize()+"&pag=Dependencia"+"&tipo=i",
      dataType: "html",
    })
    .fail(function(data){
      console.log(data);
      mensaje('fail','Error Peticion ajax');
    })
    .done(function(data){
      response = JSON.parse(data);
      switch(response.Status){
        case 'Success':
          // ProgramDataTable.destroy();
          mensaje('okey','Se Creo el Dependencia');
          $("#form_dependencia")[0].reset();
          getSecretary();
        break;
        case 'Error':
          mensaje('fail','no se pudo crear el Dependencia');
        break;
        case 'Existing Dependencia':
          mensaje('fail','El Dependencia ingresada ya existe');
        break;
        default:
          mensaje('fail','Formulario incompleto');
        break;
      }
      $('#modal_charge_dependencia').modal('hide');
    });
  }
});
$(document).on('click', '.change-state-dependencia', function(e){
  var Id = $.trim($(this)[0].id);

  if(Id > 0){
    accion = $(this).val();
    $.ajax({
      type: "POST",
      url: "controller/",
      data: "id="+Id+"&pag=Dependencia"+"&tipo=d"+"&action="+accion,
      dataType: "html",
    })
    .fail(function(data){
      console.log(data);
      mensaje('fail','Error Peticion ajax');
    })
    .done(function(data){
      response = JSON.parse(data);
      switch(response.Status){
        case 'Success':
          mensaje('okey','Se cambio el estado de la dependencia');
          getSecretary();
        break;
        case 'Error':
          mensaje('fail','No se pudo cambiar el estado de la Dependencia');
        break;
        case 'Invalid call':
          mensaje('fail','Datos invalidos');
        break;
      }
    });
  }
});
var idDependencia = 0;
$(document).on('click', '.change-name-dependencia', function(e){
  console.log($(this)[0])
  idDependencia = $(this)[0].id;
  $('#modal_change_dependencia').modal('toggle');
  $.each(ListDependencia, function(i,d){
    if(idDependencia == d.IdDependence){
      $("#new_dependencia").val(d.Name);
      $("#new_ubicacion option[value='"+d.IdLocation+"']").prop("selected",true);
      $("#new_dependence_secretary option[value=\'"+d.IdSecretary+"']").prop("selected",true);
      $("#new_direccion").val(d.Address);
    }
  });
});
$(document).on('submit', '#form_change_dependencia', function(e){
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: "controller/",
    data: $(this).serialize()+"&pag=Dependencia"+"&tipo=u"+'&id='+idDependencia,
    dataType: "html",
  })
  .fail(function(data){
    console.log(data);
    mensaje('fail','Error Peticion ajax');
  })
  .done(function(data){
    response = JSON.parse(data);
    switch(response.Status){
      case 'Success':
        mensaje('okey','Se modifico la dependencia');
        $("#form_change_dependencia")[0].reset();
        getSecretary();
      break;
      case 'Error':
        mensaje('fail','No se pudo modificar la dependencia');
      break;
      case 'Invalid call':
        mensaje('fail','Datos invalidos');
      break;
    }
    $('#modal_change_dependencia').modal('toggle');
  });
});

//-----------------------------SECRETARIAS--------------------------------------

$(document).on('submit', '#form_secretaria', function(e){
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: "controller/",
    data: $(this).serialize()+"&pag=Secretaria"+"&tipo=i",
    dataType: "html",
  })
  .fail(function(data){
    console.log(data);
    mensaje('fail','Error Peticion ajax');
  })
  .done(function(data){
    response = JSON.parse(data);
    switch(response.Status){
      case 'Success':
        // ProgramDataTable.destroy();
        mensaje('okey','Se Creo la secretaria');
        $("#form_secretaria")[0].reset();
        getSecretary();
      break;
      case 'Error':
        mensaje('fail','no se pudo crear la secretaria');
      break;
      case 'Existing Secretaria':
        mensaje('fail','La secretaria ya existe');
      break;
    }
    $('#modal_charge_secretaria').modal('hide');
  });
});

var idSecretaria = 0;
$(document).on('click', '.change-name-secretaria', function(e){
  console.log($(this)[0])
  idSecretaria = $(this)[0].id;
  $('#modal_change_secretaria').modal('toggle');
  $.each(ListSecretaria, function(i,s){
    if(idSecretaria == s.IdSecretaria){
      $("#new_secretaria").val(s.Name);
    }
  });
});
$(document).on('submit', '#form_change_secretaria', function(e){
  e.preventDefault();
  $.ajax({
    type: "POST",
    url: "controller/",
    data: $(this).serialize()+"&pag=Secretaria"+"&tipo=u"+'&id='+idSecretaria,
    dataType: "html",
  })
  .fail(function(data){
    console.log(data);
    mensaje('fail','Error Peticion ajax');
  })
  .done(function(data){
    response = JSON.parse(data);
    switch(response.Status){
      case 'Success':
        mensaje('okey','Se modifico la secretaria');
        $("#form_change_secretaria")[0].reset();
        getSecretary();
      break;
      case 'Error':
        mensaje('fail','No se pudo modificar la secretaria');
      break;
      case 'Invalid call':
        mensaje('fail','Datos invalidos');
      break;
    }
    $('#modal_change_secretaria').modal('toggle');
  });
});


function getSecretary(){
  $.ajax({
    type: "POST",
    url: "controller/",
    data: "pag=Secretaria"+"&tipo=g",
    dataType: "html",
  })
  .fail(function(data){
    console.log(data);
    mensaje('fail','Error Peticion ajax');
  })
  .done(function(data){
    ListSecretaria = JSON.parse(data);
    ListDependencia = [];
    $.each(ListSecretaria, function(i,s){
      $.each(s.Dependences, function(k,d){
        ListDependencia.push(d);
      });
    });

    SecretariaDataTable.destroy();
    DependenciaDataTable.destroy();
    displaySecretaryTable();
  });
}

function displaySecretaryTable(){
	SecretariaDataTable = $('#t_secretaria').DataTable({
      "data": ListSecretaria,
      "rowId": 'IdSecretary',
      "deferRender":true,
      "scrollX":true,
      "scrollCollapse":true,
      "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
      "iDisplayLength":5,
      "columns":[
          { "data": "Name"},
          { "data": "Dependences",
            "render":function(data, type, full, meta){
              var list = '<ul>';
              if(full.Dependences.length > 0){
                $.each(full.Dependences, function(i,d){
                  if(full.IdSecretaria == d.IdSecretary){
                    list += '<li>'+d.Name+'</li>';
                  }
                });
              }
              return list+'</ul>';
            }
          },
          { "data": "Acciones",
          	"render":function(data, type, full, meta){
          		var newInput = "<input type=\"button\" class=\"btn btn-info change-name-secretaria\" id=\""+full.IdSecretaria+"\" value=\"Modificar\">";
              return newInput;
            }
          }
      ],
  });
  DependenciaDataTable = $('#t_dependencia').DataTable({
      "data": ListDependencia,
      "rowId": 'IdDependence',
      "deferRender":true,
      "scrollX":true,
      "scrollCollapse":true,
      "lengthMenu":[[5, 10, 20, 25, 50, -1], [5,10, 20, 25, 50, "Todos"]],
      "iDisplayLength":5,
      "columns":[
          { "data": "Name"},
          { "data": "Secretary",
            "render":function(data, type, full, meta){
              var string = '';
              $.each(ListSecretaria, function(i,s){
                if(full.IdSecretary == s.IdSecretaria){
                  string = s.Name;
                }
              });
              return string;
            }
          },
          { "data": "Location"},
          { "data": "Address"},
          { "data": "State",
            "render":function(data, type, full, meta){
              if(full.State == 1){
                var string = "Habilitado";
              }else{
                var string = "Deshabilitado";
              }
              return string;
            }
          },
          { "data": "Acciones",
            "render":function(data, type, full, meta){
              var newInput = "<input type=\"button\" class=\"btn btn-info change-name-dependencia\" id=\""+full.IdDependence+"\" value=\"Modificar\">";
              if(full.State == 1){
                newInput += "<input type=\"button\" class=\"btn btn-warning change-state-dependencia\" id=\""+full.IdDependence+"\" value=\"Deshabilitar\">";
              }else{
                newInput += "<input type=\"button\" class=\"btn btn-warning change-state-dependencia\" id=\""+full.IdDependence+"\" value=\"Habilitar\">";
              }
              return newInput;
            }
          }
      ],
  });
}