/**
 * 21/06/2017
 * Daniel Blanco Parla
 * https://github.com/deblanco/xlsExport
 */

'use strict';

class xlsExport {

  // data: array of objects with the data for each row of the table
  // name: title for the worksheet
  constructor(data, title = 'Worksheet') {
    // input validation: new xlsExport([], String)
    if (!Array.isArray(data) || (typeof title !== 'string' || Object.prototype.toString.call(title) !== '[object String]'))
      throw new Error("Invalid input types: new xlsExport(Array [], String)");

    this._data = data;
    this._title = title;
  }

  set setData(data) {
    if (!Array.isArray(data)) throw new Error("Invalid input type: setData(Array [])");

    this._data = data;
  }

  get getData() {
    return this._data;
  }

  exportToXLS(fileName = 'export.xls') {
    if (typeof fileName !== 'string' || Object.prototype.toString.call(fileName) !== '[object String]')
      throw new Error("Invalid input type: exportToCSV(String)");

    const TEMPLATE_XLS = `
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"/>
        <head><!--[if gte mso 9]><xml>
        <x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{title}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml>
        <![endif]--></head>
        <body>{table}</body></html>`;
    const MIME_XLS = 'data:application/vnd.ms-excel;base64,';

    const parameters = { title: this._title, table: this.objectToTable() };
    const computeOutput = TEMPLATE_XLS.replace(/{(\w+)}/g, (x, y) => parameters[y]);

    this.downloadFile(MIME_XLS + this.toBase64(computeOutput), fileName);
  }

  exportToCSV(fileName = 'export.csv') {
    if (typeof fileName !== 'string' || Object.prototype.toString.call(fileName) !== '[object String]')
      throw new Error("Invalid input type: exportToCSV(String)");

    const MIME_CSV = 'data:attachament/csv,';
    this.downloadFile(MIME_CSV + encodeURIComponent(this.objectToSemicolons()), fileName);
  }

  downloadFile(output, fileName) {
    const link = document.createElement('a');
    document.body.appendChild(link);
    link.download = fileName;
    link.href = output;
    link.click();
  }

  toBase64(string) {
    return window.btoa(unescape(encodeURIComponent(string)));
  }

  objectToTable() {
    // extract keys from the first object, will be the title for each column
    const colsHead = `<tr>${Object.keys(this._data[0]).map(key => `<td>${key}</td>`).join('')}</tr>`;

    const colsData = this._data.map(obj => [`<tr>
                ${Object.keys(obj).map(col => `<td>${obj[col] ? obj[col] : ''}</td>`).join('')}
            </tr>`]) // 'null' values not showed
      .join('');

    return `<table>${colsHead}${colsData}</table>`.trim(); // remove spaces...
  }

  objectToSemicolons() {
    const colsHead = Object.keys(this._data[0]).map(key => [key]).join(';');
    const colsData = this._data.map(obj => [ // obj === row
                            Object.keys(obj).map(col => [
                                obj[col] // row[column]
                            ]).join(';') // join the row with ';'
                        ]).join('\n'); // end of row

    return `${colsHead}\n${colsData}`;
  }

}

//var tablesToExcel = (function() 
// {
//     var uri = 'data:application/vnd.ms-excel;base64,'
//     , tmplWorkbookXML = '<?xml version="1.0"?><?mso-application progid="Excel.Sheet"?><Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">'
//       + '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office"><Author>Axel Richter</Author><Created>{created}</Created></DocumentProperties>'
//       + '<Styles>'
//       + '<Style ss:ID="Currency"><NumberFormat ss:Format="Currency"></NumberFormat></Style>'
//       + '<Style ss:ID="Date"><NumberFormat ss:Format="Medium Date"></NumberFormat></Style>'
//       + '</Styles>' 
//       + '{worksheets}</Workbook>'
//     , tmplWorksheetXML = '<Worksheet ss:Name="{nameWS}"><Table>{rows}</Table></Worksheet>'
//     , tmplCellXML = '<Cell{attributeStyleID}{attributeFormula}><Data ss:Type="{nameType}">{data}</Data></Cell>'
//     , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
//     , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
//     return function(tables, wsnames, wbname, appname)
//     {
//         var ctx             = "";
//         var workbookXML     = "";
//         var worksheetsXML   = "";
//         var rowsXML         = "";

//     for (var i = 0; i < tables.length; i++)
//     {
//         if (!tables[i].nodeType) tables[i] = document.getElementById(tables[i]);
//             for (var j = 0; j < tables[i].rows.length; j++)
//             {
//                 rowsXML += '<Row>'
//                 for (var k = 0; k < tables[i].rows[j].cells.length; k++)
//                 {
//                     var dataType = tables[i].rows[j].cells[k].getAttribute("data-type");
//                     var dataStyle = tables[i].rows[j].cells[k].getAttribute("data-style");
//                     var dataValue = tables[i].rows[j].cells[k].getAttribute("data-value");
//                     dataValue = (dataValue)?dataValue:tables[i].rows[j].cells[k].innerHTML;
//                     var dataFormula = tables[i].rows[j].cells[k].getAttribute("data-formula");
//                     dataFormula = (dataFormula)?dataFormula:(appname=='Calc' && dataType=='DateTime')?dataValue:null;
//                     ctx = {  attributeStyleID: (dataStyle=='Currency' || dataStyle=='Date')?' ss:StyleID="'+dataStyle+'"':''
//                     , nameType: (dataType=='Number' || dataType=='DateTime' || dataType=='Boolean' || dataType=='Error')?dataType:'String'
//                     , data: (dataFormula)?'':dataValue
//                     , attributeFormula: (dataFormula)?' ss:Formula="'+dataFormula+'"':''
//                 };
//                 rowsXML += format(tmplCellXML, ctx);
//             }
//                 rowsXML += '</Row>'
//         }
//         ctx = {rows: rowsXML, nameWS: wsnames[i] || 'Sheet' + i};
//         worksheetsXML += format(tmplWorksheetXML, ctx);
//         rowsXML = "";
//     }

//         ctx = {created: (new Date()).getTime(), worksheets: worksheetsXML};
//         workbookXML = format(tmplWorkbookXML, ctx);
//         var link = document.createElement("A");
//         link.href = uri + base64(workbookXML);
//         link.download = wbname || 'Workbook.xls';
//         link.target = '_blank';
//         document.body.appendChild(link);
//         link.click();
//         document.body.removeChild(link);
//     }
// })();