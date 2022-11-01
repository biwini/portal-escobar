function quote(text) {
    console.log(text,'"' + text.replace('"', '""') + '"')
    return '"' + text.replace('"', '""') + '"';
}

function toCsv(table){
    var defaults = {
        separator: ',',
        newline: '\r\n',
        ignoreColumns: '',
        ignoreRows: '',
        type:'csv',
        htmlContent: false,
        consoleLog: false,
        trimContent: true,
        quoteFields: true,
        filename: 'tableHTMLExport.csv',
        utf8BOM: true,
        orientation: 'p' //only when exported to *pdf* "portrait" or "landscape" (or shortcuts "p" or "l")
    };
    var options = $.extend(defaults, options);

    var output = "";
    
    if (options.utf8BOM === true) {                
        output += '\ufeff';
    }

    var rows = table.find('tr').not(options.ignoreRows);

    var numCols = rows.first().find("td,th").not(options.ignoreColumns).length;

    rows.each(function() {
        $(this).find("td,th").not(options.ignoreColumns)
            .each(function(i, col) {
                var column = $(col);
                // Strip whitespaces
                var content = options.trimContent ? $.trim(column.text()) : column.text();

                output += options.quoteFields ? quote(content) : content;
                if(i !== numCols-1) {
                    output += options.separator;
                } else {
                    output += options.newline;
                }
            });
    });

    return output;
}

function arrayToCsv(filename, rows) {
    var processRow = function (row) {
        var finalVal = '';
        for (var j = 0; j < row.length; j++) {
            var innerValue = row[j] === null ? '' : row[j].toString();
            if (row[j] instanceof Date) {
                innerValue = row[j].toLocaleString();
            };
            var result = innerValue.replace(/"/g, '""');
            if (result.search(/("|,|\n)/g) >= 0)
                result = '"' + result + '"';
            if (j > 0)
                finalVal += ',';
            finalVal += result;
        }
        return finalVal + '\n';
    };

    var csvFile = '';
    for (var i = 0; i < rows.length; i++) {
        console.log(rows[i])
        csvFile += processRow(rows[i]);
    }

    var blob = new Blob([csvFile], { type: 'text/csv;charset=utf-8;' });
    if (navigator.msSaveBlob) { // IE 10+
        navigator.msSaveBlob(blob, filename);
    } else {
        var link = document.createElement("a");
        if (link.download !== undefined) { // feature detection
            // Browsers that support HTML5 download attribute
            var url = URL.createObjectURL(blob);
            link.setAttribute("href", url);
            link.setAttribute("download", filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    }
}

function download(filename, text) {
    var element = document.createElement('a');
    element.setAttribute('href', 'data:text/csv;charset=utf-8,' + encodeURIComponent(text));
    element.setAttribute('download', filename);

    element.style.display = 'none';
    document.body.appendChild(element);

    element.click();

    document.body.removeChild(element);
}

// OBJETC TO CSV

function getKeys(obj, prefix = '') {
	if (typeof obj === 'undefined' || obj === null) return [];
	return [
		...Object.keys(obj).map(key => `${prefix}${key}`),
		...Object.entries(obj).reduce((acc, [key, value]) => {
			if (typeof value === 'object') return [...acc, ...getKeys(value, `${prefix}${key}.`)];
			return acc;
		}, []),
	];
}
function flatObject(obj, prefix = '') {
	if (typeof obj === 'undefined' || obj === null) return {};
	return Object.entries(obj).reduce((acc, [key, value]) => {
		if (typeof value === 'object') return { ...acc, ...flatObject(value, `${prefix}${key}.`) };
		return { ...acc, [`${prefix}${key}`]: value };
	}, {});
}

function escapeCsvValue(cell) {
	if (cell.replace(/ /g, '').match(/[\s,"]/)) {
		return '"' + cell.replace(/"/g, '""') + '"';
	}
	return cell;
}

function objectsToCsv(arrayOfObjects) {
	// collect all available keys
	const keys = new Set(arrayOfObjects.reduce((acc, item) => [...acc, ...getKeys(item)], []));
	// for each object create all keys
	const values = arrayOfObjects.map(item => {
		const fo = flatObject(item);
		const val = Array.from(keys).map((key) => (key in fo ? escapeCsvValue(`${fo[key]}`) : ''));
		return val.join(',');
	});
	return `${Array.from(keys).join(',')}\n${values.join('\n')}`;
}