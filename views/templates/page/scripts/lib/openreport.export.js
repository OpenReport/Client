/*
 *$("#convert").click(function() {
    var json = $.parseJSON($("#json").val());
    var csv = jsonExport(json, true, true);
    $("#csv").val(csv);
});

$("#download").click(function() {
    var json = $.parseJSON($("#json").val());
    var csv = jsonExport(json, true, false);
    window.open("data:text/csv;charset=utf-8," + escape(csv))
});
*/



function jsonExport(objArray, lables, quoted) {
    var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;

    var str = '';
    var line = '';

    if (lables) {
        var head = array[0];
        if (quoted) {
            for (var index in array[0]) {
                var value = index + "";
                line += '"' + value.replace(/"/g, '""') + '",';
            }
        } else {
            for (var index in array[0]) {
                line += index + ',';
            }
        }

        line = line.slice(0, -1);
        str += line + '\r\n';
    }

    for (var i = 0; i < array.length; i++) {
        var line = '';

        if (quoted) {
            for (var index in array[i]) {
                var value = array[i][index] + "";
                line += '"' + value.replace(/"/g, '""') + '",';
            }
        } else {
            for (var index in array[i]) {
                line += array[i][index] + ',';
            }
        }

        line = line.slice(0, -1);
        str += line + '\r\n';
    }
    return str;

}
