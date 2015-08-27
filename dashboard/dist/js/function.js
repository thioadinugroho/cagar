function getTablePrice() {
    var getPeriodeDari = $('#periodeDari').val();
    var getPeriodeHingga = $('#periodeDari').val();
    console.log('http://devbox01.com/komoditi/get.php?periodeDari='+getPeriodeDari+'&periodeHingga='+getPeriodeHingga+'&query=terserah&cb=callbackGetTablePrice');
    $.ajax({
        url:'http://devbox01.com/komoditi/get.php',
        data: {
            'periodeDari':getPeriodeDari,
            'periodeHingga':getPeriodeHingga,
            'query': 'terserah',
            'cb':'callbackGetTablePrice'
        },
        type: 'get',
        dataType: 'jsonp',
        jsonp: false,
        jsonpCallback: 'callbackGetTablePrice',
        crossDomain: true,
        success: function(json) {
          
        },
        error: function() {
          $('.alert').hide();
          $('#errorGet').show();
        }
    });
}

function callbackGetTablePrice(json) {
    if (json.status_code == '000') {
        var tr = '';
        for(var i=0;i<json.komoditi.length;i++) {
           tr += '<tr class="odd">';
           tr += '<td>' + (i + 1) +'</td>';
           tr += '<td>' + json.komoditi[i].provinsi + '</td>';
           tr += '<td>' + json.komoditi[i].average_produsen + '</td>';
           tr += '<td>' + json.komoditi[i].average_pasar + '</td>';
           tr += '<td>Rp/' + json.komoditi[i].unit_price + '</td>';
           tr += '</tr>';
        }

        $('#dataTableHarga tbody').html(tr);
    } else {
        $('.alert').hide();
        $('#errorValidation').show();
    }
}

getTablePrice();

function submitData() {
    var getJenisSentra = $('#jenisSentra').val();
    var getNamaSentra = $('#namaSentra').val();
    var getKodePos = $('#kodePos').val();
    var getNamaKomoditi = $('#namaKomoditi').val();
    var getJenisKomoditi = $('#jenisKomoditi').val();
    var getKuantitas = $('#kuantitas').val();
    var getHarga = $('#harga').val();
    var getSatuan = $('#satuan').val();
    var getHp = $('#hp').val();

    $.ajax({
        url:'http://devbox01.com/komoditi/submit.php',
        data: {
            'jenisSentra':getJenisSentra,
            'namaSentra':getNamaSentra,
            'kodePos':getKodePos,
            'namaKomoditas':getNamaKomoditi,
            'jenisKomoditas':getJenisKomoditi,
            'kuantitas':getKuantitas,
            'harga':getHarga,
            'satuan':getSatuan,
            'hp':getHp,
            'cb':'callbackSubmitData'
        },
        type: 'get',
        dataType: 'jsonp',
        jsonp: false,
        jsonpCallback: 'callbackSubmitData',
        crossDomain: true,
        success: function(json) {
          
        },
        error: function() {
          $('.alert').hide();
          $('#errorSubmit').show();
        }
    });
}

function callbackSubmitData(json) {
    if (json.status_code == '000') {
        $('.alert').hide();
        $('#successSubmit').show();
        $('input:text').val('');
    } else {
        $('.alert').hide();
        $('#errorInput').show();
    }
}

$('#submitData').click(function(e){
    e.preventDefault();
    submitData();
});

function getDashboard() {
    var getPeriodeDari = $('#periodeDari').val();
    var getPeriodeHingga = $('#periodeDari').val();
    
    $.ajax({
        url:'http://devbox01.com/komoditi/get.php',
        data: {
            'periodeDari':getPeriodeDari,
            'periodeHingga':getPeriodeHingga,
            'query': 'dashboard',
            'cb':'callbackDashboard'
        },
        type: 'get',
        dataType: 'jsonp',
        jsonp: false,
        jsonpCallback: 'callbackDashboard',
        crossDomain: true,
        success: function(json) {
          
        },
        error: function() {
          $('.alert').hide();
          $('#errorGet').show();
        }
    });
}

function callbackDashboard(json) {
    if (json.status_code == '000') {
        $('#produsenAverage').text('Rp ' + Math.round(json.komoditi.produsen.average));
        $('#produsenMin').text('Rp ' + Math.round(json.komoditi.produsen.min));
        $('#produsenMax').text('Rp ' + Math.round(json.komoditi.produsen.max));
        $('#produsenConfidenceLevel').text(json.komoditi.produsen.confidence_level.toString() + ' %');
        $('#pasarAverage').text('Rp ' + Math.round(json.komoditi.pasar.max));
        $('#pasarMin').text('Rp ' + Math.round(json.komoditi.pasar.max));
        $('#pasarMax').text('Rp ' + Math.round(json.komoditi.pasar.max));
        $('#pasarConfidenceLevel').text(json.komoditi.pasar.confidence_level.toString() + ' %');
    } else {
        $('.alert').hide();
        $('#errorGet').show();
    }
}

getDashboard();

function getPoint() {
    var getHp = $('#userHp').val();

    $.ajax({
        url:'http://devbox01.com/komoditi/points.php',
        data: {
            'hp':getHp,
            'cb':'callbackPoint'
        },
        type: 'get',
        dataType: 'jsonp',
        jsonp: false,
        jsonpCallback: 'callbackPoint',
        crossDomain: true,
        success: function(json) {
          
        },
        error: function() {
          $('.alert').hide();
          $('#errorGet').show();
        }
    });
}

function callbackPoint(json) {
    if (json.status_code == '000') {
        $('#userPoint').text();
    } else {
        $('.alert').hide();
        $('#errorGet').show();
    }
}

function getPoint() {
    console.log('http://devbox01.com/komoditi/monthlyReport.php?cb=callbackReport');
    $.ajax({
        url:'http://devbox01.com/komoditi/monthlyReport.php',
        data: {
            'cb':'callbackReport'
        },
        type: 'get',
        dataType: 'jsonp',
        jsonp: false,
        jsonpCallback: 'callbackReport',
        crossDomain: true,
        success: function(json) {
          
        },
        error: function() {
          $('.alert').hide();
          $('#errorGet').show();
        }
    });
}

function callbackReport(json) {
    if (json.status_code == '000') {
        $('#monthlyReport').text();
        plot(json.report);
    } else {
        $('.alert').hide();
        $('#errorGet').show();
    }
}

function plot(monthData) {
    var sin = [],
        cos = [];
    for (var i = 0; i < monthData.pasar.length; i ++) {
        sin.push([i, monthData.pasar[i]]);
        cos.push([i, monthData.produsen[i]]);
    }

    var options = {
        series: {
            lines: {
                show: true
            },
            points: {
                show: true
            }
        },
        grid: {
            hoverable: true //IMPORTANT! this is needed for tooltip to work
        },
        tooltip: true,
        tooltipOpts: {
            content: "'%s' of %x.1 is %y.4",
            shifts: {
                x: -60,
                y: 25
            }
        }
    };

    var plotObj = $.plot($("#monthlyReport"), [{
            data: sin,
            label: "&nbsp;Pasar"
        }, {
            data: cos,
            label: "&nbsp;Produsen"
        }],
        options);


}

getPoint();
