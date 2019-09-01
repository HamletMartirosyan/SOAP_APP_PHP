// AJAX HANDLER ========================================================================================================

function ajaxRequest(method, url, data) {
    let result = NaN;
    $.ajax({
        url: url,
        method: method,
        dataType: 'json',
        data: data,
        contentType: 'application/json; charset=utf-8',
        async: false,
        success: function (response) {
            result = response;
        },
        errors: function (e) {
            alert(e);
        }
    });

    return result;
}

// AJAX HANDLER ========================================================================================================


// GET DATA FROM SOAP ==================================================================================================

function getDataFromSoap() {
    let data = {
        'date': $("#date").val(),
    };
    let url = '/by_date';
    let method = 'GET';
    let response = ajaxRequest(method, url, data);

    drawTable(response);
}

function drawTable(data) {
    let table_data =
        '<table class="table thead-dark">' +
        '<tr class="thead-dark">' +
        '<th>ISO</th>' +
        '<th>Amount</th>' +
        '<th>Rate</th>' +
        '<th>Difference</th>' +
        '</tr>';
    for (let rate in data) {
        table_data += '<tr>';
        for (let item in data[rate]) {
            table_data += '<td>' + data[rate][item] + '</td>'
        }
        table_data += '</tr>';
    }
    table_data += '</table>';

    let attr = document.createAttribute('class');
    attr.value = 'row';
    document.getElementById('table_data').setAttributeNode(attr);
    document.getElementById('table_data').innerHTML = table_data;
}

// GET DATA FROM SOAP ==================================================================================================


// DRAW GOOGLE-CHART DIAGRAM ===========================================================================================

function drawGoogleChart() {
    let start = $('#start_date').val();
    let end = $('#end_date').val();

    if (!(start === '') && !(end === '')) {
        $('#draw').prop("style", "cursor: pointer");

        let method = 'GET';
        let url = '/draw_graphic_debug';
        let data = {
            'start_date': start,
            'end_date': end,
            'iso': $('#iso').val(),
        };
        let response = ajaxRequest(method, url, data);

        chartDrowerHandler(response);
    }
}

function chartDrowerHandler(response) {
    google.charts.load('current', {packages: ['corechart', 'bar']});
    google.charts.setOnLoadCallback(
        function () {
            let data = Array(['Date', 'Rate', { role: 'style' }]);
            let style = 'fill-color : #005BED; stroke-color: #2100f8';

            for (let key in response) {
                let rate = parseFloat(response[key]);
                data.push([key, rate, style]);
            }
            let chartData = google.visualization.arrayToDataTable(data);


            let options = {
                title: 'Rates of days',
                focusTarget: 'category',
                hAxis: {
                    title: '',
                    textStyle: {
                        fontSize: 12,
                        color: '#1800b4',
                        bold: true,
                        italic: false
                    },
                },
                vAxis: {
                    title: 'Rates',
                    textStyle: {
                        fontSize: 18,
                        color: '#060c45',
                        bold: true,
                        italic: false
                    },
                    titleTextStyle: {
                        fontSize: 22,
                        color: '#100327',
                        bold: true,
                        italic: true
                    }
                }
            };

            let attr = document.createAttribute('style');
            attr.value = "width: 100%; height: 450px";
            document.getElementById('curve_chart').setAttributeNode(attr);

            let chart = new google.visualization.ColumnChart(document.getElementById('curve_chart'));
            chart.draw(chartData, options);
        });
}

// DRAW GOOGLE-CHART DIAGRAM ===========================================================================================



// button setting ======================================================================================================

$(document).ready(function () {
    $('#draw').onmouseover(function () {
        if ($('#start_date').val() && $('#end_date').val())
            $('#draw').attr("style", "cursor: pointer");
        else
            $('#draw').attr("style", "cursor: default");
    });
});

// Readonly 'DRAW' button ==============================================================================================
