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

var log;
var startPressed;

var rrTimer;
var hrTimer;

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

var capture;
window.onload = init;

function init() {

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

    capture = false;
    
    //init snackbar
    snackbar = document.getElementById("snackbar-log");

    getWeightData(drawWeightChart);
    getRespirationRate();
    getHeartRate();
    getBodyTemperature();
    
/*
    //velocity animation
    $('.mdl-card').velocity('transition.slideUpBigIn', {
        stagger: 250,
        display: 'flex'
    });
*/

    document.getElementById("hr_plot_chart").style.display = 'none';
    document.getElementById("rr_plot_chart").style.display = 'none';
}

function manageCapture() {

    if (!capture) {
        console.log("start capture");
        document.getElementById("hr_plot_chart").style.display = 'block';
        document.getElementById("rr_plot_chart").style.display = 'block';
        document.getElementById("hr_value_box").style.display = 'none';
        document.getElementById("rr_value_box").style.display = 'none';
        document.getElementById("bt_value_box").style.display = 'none';
        rrTimer = setInterval(getRespirationRate, 5000);
        hrTimer = setInterval(getHeartRate, 5000);
        //setInterval(getTime, 60000);    
        document.getElementById("captureControl").innerHTML = "stop";
        capture = true;
    }
    else {
        console.log("stop capture");    
        document.getElementById("hr_plot_chart").style.display = 'none';
        document.getElementById("rr_plot_chart").style.display = 'none';
        document.getElementById("hr_value_box").style.display = 'block';
        document.getElementById("rr_value_box").style.display = 'block';
        document.getElementById("bt_value_box").style.display = 'block';
        clearInterval(rrTimer);
        clearInterval(hrTimer);
        document.getElementById("captureControl").innerHTML = "play_arrow";
        capture = false;
    }
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
            timeformat: "%d-%m-%y",
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
            xDateFormat: '%d-%m-%y'
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
            timeformat: "%d-%m-%y",
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
            xDateFormat: '%d-%m-%y'
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
    
    if(rrData.length > 10)
        rrData.splice(0,1);

    var minDate = rrData[0][0];
    var maxDate = rrData[rrData.length-1][0];

    if (!capture)
        return;

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
            ticks: 5,
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

var hrData = [];

function drawHRChart() {
    hrData.push(new Array(new Date().getTime(), parseFloat(heartRate)));

    if(hrData.length > 10)
        hrData.splice(0,1);
    
    var minDate = hrData[0][0];
    var maxDate = hrData[hrData.length-1][0];

    if (!capture)
        return;
    
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

    rr_plot = $.plot($("#plot-HR"), [{
        data: hrData,
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
            document.getElementById("respiration_rate_box").innerHTML = respirationRate + breathMsg;
            if (capture)
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
            document.getElementById("ecg_hr_box").innerHTML = heartRate + " bpm";
            if (capture)
                drawHRChart();
        },
        error: function () {
            console.log("Error while getting heart rate data");
        }
    });
}

function getBodyTemperature() {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/bodyTemperature/",
        dataType: 'json',

        success: function (response) {
            console.log("Context response Body Temperature", response);
            bodyTemperature = response.value;
            document.getElementById("body_temperature_box").innerHTML = bodyTemperature + " ºC";
        },
        error: function () {
            console.log("Error while getting body temperature data");
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