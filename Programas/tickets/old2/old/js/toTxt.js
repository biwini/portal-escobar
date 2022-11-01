function descargarArchivo(contenidoEnBlob, nombreArchivo) {
    let reader = new FileReader();
    reader.onload = function (event) {
        let save = document.createElement('a');
        save.href = event.target.result;
        save.target = '_blank';
        save.download = nombreArchivo || 'archivo.dat';
        let clicEvent = new MouseEvent('click', {
            'view': window,
                'bubbles': true,
                'cancelable': true
        });
        save.dispatchEvent(clicEvent);
        (window.URL || window.webkitURL).revokeObjectURL(save.href);
    };
    reader.readAsDataURL(contenidoEnBlob);
};

//Función de ayuda: reúne los datos a exportar en un solo objeto
// function obtenerDatos() {
//     return {
//         nombre: document.getElementById('textNombre').value,
//         telefono: document.getElementById('textTelefono').value,
//         fecha: (new Date()).toLocaleDateString()
//     };
// };

//Función de ayuda: "escapa" las entidades XML necesarias
//para los valores (y atributos) del archivo XML
// function escaparXML(cadena) {
//     if (typeof cadena !== 'string') {
//         return '';
//     };
//     cadena = cadena.replace('&', '&amp;')
//         .replace('<', '&lt;')
//         .replace('>', '&gt;')
//         .replace('"', '&quot;');
//     return cadena;
// };

//Genera un objeto Blob con los datos en un archivo TXT
function generarTexto(data) {
    let texto = [];
    $.each(data, function(k,d){
        texto.push('\r\nNº Ticket:');
        texto.push(d.Codigo);
        texto.push('\r\n');
        texto.push(d.Dependencia);
        texto.push(' - '+d.Usuario);
        texto.push(' - '+d.Telefono+' - ');
        texto.push(d.Motivo+'\r\n');
        texto.push('Comentario Interno: '+d.Comentario_Interno);
        texto.push('\r\nEncargado: '+d.Encargado+'\r\n');
    });
    
    //El contructor de Blob requiere un Array en el primer parámetro
    //así que no es necesario usar toString. el segundo parámetro
    //es el tipo MIME del archivo
    return new Blob(texto, {
        type: 'text/plain'
    });
};


//Genera un objeto Blob con los datos en un archivo XML
// function generarXml(datos) {
//     var texto = [];
//     texto.push('<?xml version="1.0" encoding="UTF-8" ?>\n');
//     texto.push('<datos>\n');
//     texto.push('\t<nombre>');
//     texto.push(escaparXML(datos.nombre));
//     texto.push('</nombre>\n');
//     texto.push('\t<telefono>');
//     texto.push(escaparXML(datos.telefono));
//     texto.push('</telefono>\n');
//     texto.push('\t<fecha>');
//     texto.push(escaparXML(datos.fecha));
//     texto.push('</fecha>\n');
//     texto.push('</datos>');
//     //No olvidemos especificar el tipo MIME correcto :)
//     return new Blob(texto, {
//         type: 'application/xml'
//     });
// };

// document.getElementById('boton-xml').addEventListener('click', function () {
//     var datos = obtenerDatos();
//     descargarArchivo(generarXml(datos), 'archivo.xml');
// }, false);

document.getElementById('to_txt').addEventListener('click', function (e) {
    var datos = ListTickets;
    let input = $('#'+e.target.id);
    console.log(datos)
    input.attr('disabled','true')

    descargarArchivo(generarTexto(datos), 'Tickets.txt');
    input.removeAttr('disabled');
}, false);