// DRAW GOOGLE-CHART DIAGRAM ===========================================================================================
function drawGoogleChart() {
    let method = 'GET';
    let url = '/draw_calendar_graphic/';
    let dataType = 'json';
    let data = {
        'start_date': $('#start_date').val(),
        'end_date': $('#end_date').val(),
        'iso': $('#iso').val(),
    };
    let success_data = ajaxHandler(method, url, data, dataType);

    drawGraphic(success_data);
}

function drawGraphic(data) {
    alert('success !!!');
    console.log(data);

    google.charts.load('current', {'packages': ['corechart']});
    google.charts.setOnLoadCallback(drawChart);
}

function drawChart(data) {
    let iso_code = $('#iso').val();
    let arr = [];
    let chart_data = google.visualization.arrayToDataTable([
        ['Date', 'ISO']
    ]);

    /*for (let i = 0; i < data.length; i++) {
        for (let key in data[i]) {
            alert(key + ' // ' + data[i][key][iso_code]);
            arr[key] = data[key][iso_code];
            chart_data.push(arr);
        }
    }*/

    let options = {
        title: 'Company Performance',
        curveType: 'function',
        legend: {position: 'bottom'}
    };

    let chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chart.draw(chart_data, options);

    let attr = document.createAttribute('style');
    attr.value = "width: 900px; height: 500px";
    document.getElementById('curve_chart').setAttributeNode(attr);

    alert(chart_data);

}

// DRAW GOOGLE-CHART DIAGRAM ===========================================================================================


// By_date_by_iso ======================================================================================================
function getDataFromSoap() {
    let data = {
        'date': $("#date").val(),
    };
    let method = 'GET';
    let url = '/by_date/';
    let dataType = 'json';
    let success_data = ajaxHandler(method, url, data, dataType);

    printTable(success_data)
}

function printTable(data) {
    let response =
        '<table class="table thead-dark">' +
        '<tr class="thead-dark">' +
        '<th scope="col">ISO</th>' +
        '<th scope="col">Amount</th>' +
        '<th scope="col">Rate</th>' +
        '<th scope="col">Difference</th>' +
        '</tr>';
    for (let val in data) {
        response += '<tr>';
        for (let item in data[val]) {
            response += '<td>' + data[val][item] + '</td>';
        }
        response += '</tr>';
    }
    response += '</table>';

    let attr = document.createAttribute('class');
    attr.value = 'row';
    document.getElementById('response').setAttributeNode(attr);
    document.getElementById('response').innerHTML = response;
}

// By_date_by_iso ======================================================================================================


// AJAX ================================================================================================================
function ajaxHandler(method, url, data, dataType) {
    $.ajax({
        method: method,
        url: url,
        dataType: dataType,
        data: data,
        contentType: 'application/json; charset=utf-8',
        statusCode: {
            404: function () {
                alert("page not found");
            }
        },
        success: function (data) {
            return data;
        },
        errors: function (e) {
            alert(e);
        }
    })
}
