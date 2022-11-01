
var	tableToExcel = (function(){
	var	uri = 'data:application/vnd.ms-excel;base64,'
	, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
	, base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
	, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
	return function(table, name) {
	if (!table.nodeType) table = document.getElementById(table)
	var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
	window.location.href = uri + base64(format(template, ctx))
	}
})()
/*<function genPDF()
  {
   html2canvas(document.body,{
   onrendered:function(canvas){

   var img=canvas.toDataURL("image/png");
   var doc = new jsPDF();
   doc.addImage(img,'JPEG',20,20);
   doc.save('test.pdf');
   }

   });

  }
/*
function descargarExcel(){
	var tmpElemento = document.createElement('a');
		// obtenemos la información desde el div que lo contiene en el html
	        
		// Obtenemos la información de la tabla
		var data_type = 'data:application/vnd.ms-excel';
		var tabla_div = document.getElementById('consultas');;
		var tabla_html = tabla_div.replace(/ /g, '%20');
		tmpElemento.href = data_type + ', ' + tabla_html;
		//Asignamos el nombre a nuestro EXCEL
		tmpElemento.download = 'Consulta';
		// Simulamos el click al elemento creado para descargarlo
		tmpElemento.click();
}*/