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
var plot_ACC;   
var plot_TEMP;   
var plot_ch5;

var weight_plot;
var find_plot;

var ch0PlotData= [];    //ECG - CHANNEL A2
var ch1PlotData= [];    //X AXIS
var ch2PlotData= [];    //Y AXIS
var ch3PlotData= [];    //Z AXIS
var ch4PlotData= [];    //TEMPERATURE
var ch5PlotData= [];

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
//var userName = "cchesta";
var userName = "john";
var appName  = "personAAL";

window.onload = init;

    function init() {

        setInterval(getECG_HR, 5000); 
        setInterval(getRespirationRate, 5000); 
        setInterval(getBodyTemperature, 5000); 
        setInterval(getTime, 60000);

        
        
                //internationalization
        var userLang = getUserLanguage(); 
        console.log(userLang);
        
        switch(userLang)
    {
        case 'en':
            breathMsg= ' breaths/minute'
        break;
                
        case 'de':
            breathMsg= ' breaths/minute'
        break;
       
        case 'no':
            breathMsg= ' innpust/minuttet'
        break;
                
        default:
            breathMsg= ' breaths/minute'
        break;
        }
        

        
        
        //init real time plots
        realTimePlot= document.getElementById("realTimePlot");
       
        //init snackbar
        snackbar= document.getElementById("snackbar-log");



        
        getWeightPlotData(drawWeightChart);
        getFindPlotData(drawFindChart);


        ch0PlotData.push([0, 0]);
        ch1PlotData.push([0, 0]); 
        ch2PlotData.push([0, 0]); 
        ch3PlotData.push([0, 0]); 
        ch4PlotData.push([0, 0]);
        ch5PlotData.push([0, 0]);


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


        plot_ECG = $.plot($("#plot-ECG"), [{
                color: '#ffffff',
                //data: ch0PlotData,
                data: ch1PlotData,
                bars: {show: false, horizontal: false}
            }],
            mVoptions);
        
        plot_ACC = $.plot($("#plot-ACC"), [
            {
                color: '#ff0000',
                data: ch2PlotData,
                bars: {show: false, horizontal: false}
            },
            {
                color: '#00ff00',
                data: ch3PlotData,
                bars: {show: false, horizontal: false}
            },
            {
                color: '#0000ff',
                data: ch4PlotData,
                bars: {show: false, horizontal: false}
            }
        ], mVoptions);
        
        plot_TEMP = $.plot($("#plot-TEMP"), [{
            color: '#ffffff',
            //data: ch4PlotData,
            data: ch5PlotData,
            bars: {show: false, horizontal: false}
        }], celsiusOptions);


        //velocity animation
        $('.mdl-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});

    }
    

    
    function drawWeightChart(data){
	
        var weightPlotData= [];

//        weightPlotData.push([(new Date(2017, 06, 14)).getTime(), 60]);
//        weightPlotData.push([(new Date(2017, 06, 21)).getTime(), 58]);
//        weightPlotData.push([(new Date(2017, 06, 28)).getTime(), 57]);
//        weightPlotData.push([(new Date(2017, 07, 05)).getTime(), 55]);
//        weightPlotData.push([(new Date(2017, 07, 12)).getTime(), 56]);
//        weightPlotData.push([(new Date(2017, 07, 19)).getTime(), 56]);
//        
//        data=weightPlotData;
        
        console.log("manuali: " +weightPlotData);
        console.log("dal DB: " +data);

        //calculate min and max data
        var maxDate= Math.max.apply(Math, data.map(function(o){return o[0]}));
        var minDate= maxDate - 1000*60*60*24*31*2; //two months before
        console.log("max data: " +maxDate);

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
                    timeformat: "%m-%Y",
                    tickSize: [1, "month"],
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
                content: weightDateMsg+': %x | Kg: %y',
                xDateFormat: '%d-%m-%Y'
            },
            zoom: {
                interactive: true
            },
            pan: {
                interactive: true
            }
        };



        weight_plot = $.plot($("#plot-weight2"), [{
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
        var fontsize=  parseInt($('.tickLabel').css('font-size'));
        $('.tickLabel').css('font-size', (fontsize + 5)+'px');

    }
    
    
      function drawFindChart(data){
          
        var findPlotData= [];
         
//        findPlotData.push([(new Date(2017, 06, 14)).getTime(), 1]);
//        findPlotData.push([(new Date(2017, 06, 21)).getTime(), 2]);
//        findPlotData.push([(new Date(2017, 06, 28)).getTime(), 3]);
//        findPlotData.push([(new Date(2017, 07, 05)).getTime(), 5]);
//        findPlotData.push([(new Date(2017, 07, 12)).getTime(), 4]);
//        findPlotData.push([(new Date(2017, 07, 19)).getTime(), 3]);
//
//        data = findPlotData;

        console.log("manuali: " +findPlotData);
        console.log("dal DB: " +data);
        
        //calculate min and max data
        var maxDate= Math.max.apply(Math, data.map(function(o){return o[0]}));
        var minDate= maxDate - 1000*60*60*24*31*2; //two months before
        console.log("max data: " +maxDate);

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
                    timeformat: "%m-%Y",
                    tickSize: [1, "month"],
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
                axisLabel: 'Score'

            }],
            tooltip: {
                show: true,
                content: weightDateMsg+': %x | score: %y',
                xDateFormat: '%d-%m-%Y'
            },
            zoom: {
                interactive: true
            },
            pan: {
                interactive: true
            }
        };


        find_plot = $.plot($("#plot-find"), [{
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
        var fontsize=  parseInt($('.tickLabel').css('font-size'));
        $('.tickLabel').css('font-size', (fontsize + 5)+'px');

    }
    
    function writelog(message)
    {
        log.innerHTML = log.innerHTML + message +"<br/>";
    }
    
    
    function requestData(n)
    {
        var response = {
            action: "read",
            nSamples: n.toString()
            };
        socket.send(JSON.stringify(response));
        
        
    }
    
function onMessage(event) {
    
    
    var message = JSON.parse(event.data);
    
    
    if (message.action === "connect")
    {
        //connected to the server
        //writelog("Connected to the server");
        
        //send message to perform connection between server and BITalino
        var response = {
        action: "BITalino",
        samplingRate: ''+samplingRate,
        channels: [ 0, 1, 2, 3, 4, 5]
        };
        socket.send(JSON.stringify(response));
        
        //writelog(">> "+ JSON.stringify(response));
        
        return;
    }
    
    if (message.action === "disconnect")
    {
        //writelog(">> "+ message.message);
        
        stopPressed();
        
        return;
    }
    
    if (message.action === "ready")
    {
        //writelog("<< "+ message.message);
        
        //request data from sensors, read 10 samples
        requestData(nSamples);
        
        return;
    }
    
    if (message.action === "info")
    {
        //writelog("<< INFO: "+message.message);
        
        return;
    }
    
    if (message.action === "heartRate")
    {
        console.log("heart rate: " + message.heartRate);
        
        //refresh hear rate
        document.getElementById("heart-rate-value").textContent= message.heartRate;
        
        return;
    }
    
    
    if (message.action === "data")
    {
        var ch0DataY= message.ch0.toString().split(",");    //ECG
        var ch1DataY= message.ch1.toString().split(",");    //X_AXIS
        var ch2DataY= message.ch2.toString().split(",");    //Y_AXIS
        var ch3DataY= message.ch3.toString().split(",");    //Z_AXIS
        var ch4DataY= message.ch4.toString().split(",");    //TEMP
        
        
        for(var x = 0; x < ch0DataY.length; x++)
        {
            //initial x max passed
            if(Xcount > XmaxCount)
            {
                if(MaxX === false)
                {
                
                    plot_ECG = $.plot($("#plot-ECG"), [{
                        color: '#ffffff',
                        data: ch0PlotData,
                        bars: {show: false, horizontal: false}
                    }], {
                        grid: {
                            color: '#f2f2f2',
                            labelBoxBorderColor: '#f2f2f2',
                            borderColor: '#f2f2f2',
                            borderWidth: 0
                        },
                        xaxis: {
                            tickLength: 0
                        }
                    });
                    
                    plot_ACC = $.plot($("#plot-ACC"), [
                        {
                            color: '#ff0000',
                            data: ch1PlotData,
                            bars: {show: false, horizontal: false}
                        },
                        {
                            color: '#00ff00',
                            data: ch2PlotData,
                            bars: {show: false, horizontal: false}
                        },
                        {
                            color: '#0000ff',
                            data: ch3PlotData,
                            bars: {show: false, horizontal: false}
                        }
                    ], {
                        grid: {
                            color: '#f2f2f2',
                            labelBoxBorderColor: '#f2f2f2',
                            borderColor: '#f2f2f2',
                            borderWidth: 0
                        },
                        xaxis: {
                            tickLength: 0
                        }
                    });

                    plot_TEMP = $.plot($("#plot-TEMP"), [{
                        color: '#ffffff',
                        data: ch4PlotData,
                        bars: {show: false, horizontal: false}
                    }], {
                        grid: {
                            color: '#f2f2f2',
                            labelBoxBorderColor: '#f2f2f2',
                            borderColor: '#f2f2f2',
                            borderWidth: 0
                        },
                        xaxis: {
                            tickLength: 0
                        }
                    });
                    
                    

                }

                ch0PlotData= ch0PlotData.slice(1);
                ch1PlotData= ch1PlotData.slice(1);
                ch2PlotData= ch2PlotData.slice(1);
                ch3PlotData= ch3PlotData.slice(1);
                ch4PlotData= ch4PlotData.slice(1);
                
                MaxX= true;
            }
            
            ch0PlotData.push([Xcount, ch0DataY[x]]);
            ch1PlotData.push([Xcount, ch1DataY[x]]);
            ch2PlotData.push([Xcount, ch2DataY[x]]);
            ch3PlotData.push([Xcount, ch3DataY[x]]);
            ch4PlotData.push([Xcount, ch4DataY[x]]);
            
            Xcount+= Xincr;
        }
        
        console.log("number of samples: " + ch0PlotData.length);
        
        plot_ECG.setData([{
            color: '#ffffff',
            data: ch0PlotData,
            bars: {show: false, horizontal: false}
         }]);
        plot_ECG.setupGrid();
        plot_ECG.draw();
        
        plot_ACC.setData([
            {
                color: '#ff0000',
                data: ch1PlotData,
                bars: {show: false, horizontal: false}
            },
            {
                color: '#00ff00',
                data: ch2PlotData,
                bars: {show: false, horizontal: false}
            },
            {
                color: '#0000ff',
                data: ch3PlotData,
                bars: {show: false, horizontal: false}
            }
        ]);
        plot_ACC.setupGrid();
        plot_ACC.draw();
        
        plot_TEMP.setData([{
            color: '#ffffff',
            data: ch4PlotData,
            bars: {show: false, horizontal: false}
         }]);
        plot_TEMP.setupGrid();
        plot_TEMP.draw();
        
      //this creates a request loop
      setTimeout(requestData, dataRequestInterval, nSamples);
      
      return;
    }
    
    
}

function ECGconvert(x)
{
    return ((x/1024 - 0.5) * 3.3) / 1.1;    //mV
}

function onClose(event){
    stopPressed();
}

function onError(event){
//    stopPressed();
//    window.alert("error");
    console.log('error during websocket connection');
    var data = {message: errorMsg};
    snackbar.MaterialSnackbar.showSnackbar(data);
}

var heartRate = "";
var respirationRate = "";
var bodyTemperature = "";


function getBodyTemperature() {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ userName + "/bodyTemperature/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response Body Temperature", response);
            $("#body_temperature").html(response.value + " °C");
            $bodyTemperature=response.value;
        },
        error: function ()
        {
            console.log("Error while getting body temperature data");
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
        url: contextUrl + "cm/rest/user/" +userName + "/respirationRate/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response Respiration Rate", response);
            $("#respiration_rate").html(response.value + breathMsg);
            $respirationRate=response.value;
        },
        error: function ()
        {
            console.log("Error while getting respiration rate data");
        }
    });
}

function getECG_HR() {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/heartRate/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response Hearth Rate", response);
            $("#ecg_hr").html(response.value + " bpm");
            $heartRate=response.value;
        },
        error: function ()
        {
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

