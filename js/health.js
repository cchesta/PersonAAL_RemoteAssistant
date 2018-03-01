/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;

var dataRequestInterval;
var samplingRate;
var nSamples;

var Xcount;
var XmaxCount;
var maxMemData;
var Xincr;
var MaxX;

var plot_ECG;
var plot_TEMP;
var plot_HR;
var plot_BREATH;

var plot_ACC;
var plot_ch5;

var weight_plot;
var bmi_plot;
var find_plot;

var ch0PlotData = []; //ECG - CHANNEL A2
var ch1PlotData = []; //X AXIS
var ch2PlotData = []; //Y AXIS
var ch3PlotData = []; //Z AXIS
var ch4PlotData = []; //TEMPERATURE
var ch5PlotData = [];

var log;
var startPressed;


//snackbar for messages
var snackbar;
var startMsg;
var stopMsg;
var errorMsg;
var weightDateMsg;

//real time plot tables
var realTimePlot;

var breathMsg;


var contextUrl = "https://giove.isti.cnr.it:8443/";
var userName = "john";
var appName = "personAAL";
var height = 1.85;

window.onload = init;

function init() {

    setInterval(getRespirationRate, 5000);
    setInterval(getHeartRate, 5000);
    //setInterval(getTime, 60000);



    //internationalization
    var userLang = getUserLanguage();
    console.log(userLang);

    switch (userLang) {
        case 'en':
            breathMsg = ' breaths/minute'
            break;

        case 'de':
            breathMsg = ' breaths/minute'
            break;

        case 'no':
            breathMsg = ' innpust/minuttet'
            break;

        default:
            breathMsg = ' breaths/minute'
            break;
    }




    //init snackbar
    snackbar = document.getElementById("snackbar-log");

    getWeightData(drawWeightChart);



    var mVoptions = {
        grid: {
            color: '#ffffff',
            labelBoxBorderColor: '#ffffff',
            borderColor: '#ffffff',
            borderWidth: 0
        },
        xaxis: {
            min: 0,
            max: XmaxCount,
            tickLength: 0,
            font: {
                size: '20px'
            }
        },
        axisLabels: {
            show: true
        },
        yaxes: [{
            position: 'left',
            axisLabel: 'mV'

            }]
    };

    var celsiusOptions = {
        grid: {
            color: '#ffffff',
            labelBoxBorderColor: '#ffffff',
            borderColor: '#ffffff',
            borderWidth: 0
        },
        xaxis: {
            min: 0,
            max: XmaxCount,
            tickLength: 0,
            font: {
                size: 20
            }
        },
        axisLabels: {
            show: true
        },
        yaxes: [{
            position: 'left',
            axisLabel: '°C'

                    }]
    };

/*
    plot_ECG = $.plot($("#plot-ECG"), [{
            color: '#ffffff',
            //data: ch0PlotData,
            data: ch1PlotData,
            bars: {
                show: false,
                horizontal: false
            }
            }],
        mVoptions);

    plot_ACC = $.plot($("#plot-ACC"), [
        {
            color: '#ff0000',
            data: ch2PlotData,
            bars: {
                show: false,
                horizontal: false
            }
            },
        {
            color: '#00ff00',
            data: ch3PlotData,
            bars: {
                show: false,
                horizontal: false
            }
            },
        {
            color: '#0000ff',
            data: ch4PlotData,
            bars: {
                show: false,
                horizontal: false
            }
            }
        ], mVoptions);

    plot_TEMP = $.plot($("#plot-TEMP"), [{
        color: '#ffffff',
        //data: ch4PlotData,
        data: ch5PlotData,
        bars: {
            show: false,
            horizontal: false
        }
        }], celsiusOptions);
*/

    //velocity animation
    $('.mdl-card').velocity('transition.slideUpBigIn', {
        stagger: 250,
        display: 'flex'
    });

}



function drawWeightChart() {

    data = [];
    for (var i=0, l=weightArray.length; i<l; i++) {
        data.push(new Array(new Date(weightArray[i].timestamp).getTime(), parseFloat(weightArray[i].weight)));
    }

    var maxDate = data[0][0];
    var minDate = data[data.length-1][0];

    var options = {
        grid: {
            color: '#f2f2f2',
            labelBoxBorderColor: '#f2f2f2',
            borderColor: '#f2f2f2',
            borderWidth: 0,
            hoverable: true
        },
        xaxis: {
            mode: "time",
            min: minDate,
            max: maxDate,
            timeformat: "%d-%m-%Y",
            tickLength: 0, // hide gridlines
            font: {
                size: 20
            }
        },
        axisLabels: {
            show: true
        },
        yaxes: [{
            position: 'left',
            axisLabel: 'Kg'

            }],
        tooltip: {
            show: true,
            content: '%x | Kg: %y',
            xDateFormat: '%d-%m-%Y'
        },
        zoom: {
            interactive: true
        },
        pan: {
            interactive: true
        }
    };



    weight_plot = $.plot($("#plot-weight"), [{
        data: data,
        color: 'rgba(255, 255, 255, 1)',
        points: {
            show: true
        },
        lines: {
            show: true
        }
        }], options);

    //TODO check if is possibile to change label font size via flot (font attribute does not work)
    //fix for label font size
    var fontsize = parseInt($('.tickLabel').css('font-size'));
    $('.tickLabel').css('font-size', (fontsize + 5) + 'px');

    getHeightData(drawBMIChart);
}

function drawBMIChart() {

    data = [];
    for (var i=0, l=weightArray.length; i<l; i++) {
        data.push(new Array(new Date(weightArray[i].timestamp).getTime(), parseFloat(weightArray[i].weight)/(height*height)));
    }

    var maxDate = data[0][0];
    var minDate = data[data.length-1][0];

    var options = {
        grid: {
            color: '#f2f2f2',
            labelBoxBorderColor: '#f2f2f2',
            borderColor: '#f2f2f2',
            borderWidth: 0,
            hoverable: true
        },
        xaxis: {
            mode: "time",
            min: minDate,
            max: maxDate,
            timeformat: "%d-%m-%Y",
            tickLength: 0 // hide gridlines
        },
        axisLabels: {
            show: true
        },
        yaxes: [{
            position: 'left',
            axisLabel: 'Kg/m2'
        }],
        tooltip: {
            show: true,
            content: '%x | BMI: %y',
            xDateFormat: '%d-%m-%Y'
        },
        zoom: {
            interactive: true
        },
        pan: {
            interactive: true
        }
    };



    bmi_plot = $.plot($("#plot-bmi"), [{
        data: data,
        color: 'rgba(255, 255, 255, 1)',
        points: {
            show: true
        },
        lines: {
            show: true
        }
        }], options);

    //TODO check if is possibile to change label font size via flot (font attribute does not work)
    //fix for label font size
    var fontsize = parseInt($('.tickLabel').css('font-size'));
    $('.tickLabel').css('font-size', (fontsize + 5) + 'px');
}

var rrData = [];

function drawRRChart() {
    rrData.push(new Array(new Date().getTime(), parseFloat(respirationRate)));

    var minDate = rrData[0][0];
    var maxDate = rrData[rrData.length-1][0];

    var options = {
        grid: {
            color: '#f2f2f2',
            labelBoxBorderColor: '#f2f2f2',
            borderColor: '#f2f2f2',
            borderWidth: 0,
            hoverable: true
        },
        xaxis: {
            mode: "time",
            min: minDate,
            max: maxDate,
            timeformat: "%H:%M:%S",
            tickLength: 0 // hide gridlines
        },
        axisLabels: {
            show: true
        },
        zoom: {
            interactive: true
        },
        pan: {
            interactive: true
        }
    };

    rr_plot = $.plot($("#plot-BREATH"), [{
        data: rrData,
        color: 'rgba(255, 255, 255, 1)',
        points: {
            show: true
        },
        lines: {
            show: true
        }
        }], options);
    
    //TODO check if is possibile to change label font size via flot (font attribute does not work)
    //fix for label font size
    var fontsize = parseInt($('.tickLabel').css('font-size'));
    $('.tickLabel').css('font-size', (fontsize + 5) + 'px');
}

function writelog(message) {
    log.innerHTML = log.innerHTML + message + "<br/>";
}


function requestData(n) {
    var response = {
        action: "read",
        nSamples: n.toString()
    };
    socket.send(JSON.stringify(response));


}

function ECGconvert(x) {
    return ((x / 1024 - 0.5) * 3.3) / 1.1; //mV
}

function onError(event) {
    //    stopPressed();
    //    window.alert("error");
    console.log('error during websocket connection');
    var data = {
        message: errorMsg
    };
    snackbar.MaterialSnackbar.showSnackbar(data);
}

var heartRate = "";
var respirationRate = "";

function getWeightData(callback) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/weight/history/getNlastValues/10",
        dataType: 'json',

        success: function (response) {
            weightArray = response.historyUserWeight;
            callback();
        },
        error: function () {
            console.log("Error while getting weigth data");
        }
    });
}

function getHeightData(callback) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/height/",
        dataType: 'json',

        success: function (response) {
            height = response.value;
            callback();
        },
        error: function () {
            console.log("Error while getting height data");
            height = 1.85;
            callback();
        }
    });
}


function getRespirationRate() {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/respirationRate/",
        dataType: 'json',

        success: function (response) {
            console.log("Context response Respiration Rate", response);
            respirationRate = response.value;
            drawRRChart();
        },
        error: function () {
            console.log("Error while getting respiration rate data");
        }
    });
}

function getHeartRate() {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/heartRate/",
        dataType: 'json',

        success: function (response) {
            console.log("Context response Heart Rate", response);
            heartRate = response.value;
        },
        error: function () {
            console.log("Error while getting heart rate data");
        }
    });
}

function getTime() {
    var d = new Date();

    function addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }
    var H = addZero(d.getHours()),
        M = addZero(d.getMinutes());
    sendTimeToContextManager(H + ':' + M);
    //setTimeout(getTime, 1000);
}