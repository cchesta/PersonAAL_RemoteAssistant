/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;


var plot_ECG;   
var plot_ACC;   
var plot_TEMP;   
var plot_ch5;

var weight_plot;
var find_plot;


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
        setInterval(getMedicationPlanned, 5000);
        
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

function getMedicationPlanned() 
{	
//    updateMedicationPlanned();
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ userName + "/medication_planned/", 
//        url: contextUrl + "cm/rest/user/roytest/medication_planned/",
        dataType: 'json',
        
        success: function (response) {            
            console.log("Medication Planned", response);
            $("#medication_planned").html(response.medication); 
            $("#medication_planned_dosage").html(response.dosage);
            $("#medication_planned_time").html(response.notification_time);
        },
        error: function ()
        {
            console.log("Error while getting medication planned");
        }
    });
}

function getMedicationOccurred() 
{	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ userName + "/medication_occurred/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Medication Occurred", response);
            $("#medication_occurred").html(response.medication); 
            $("#medication_occurred_dosage").html(response.dosage);
            $("#medication_occurred_time").html(response.notification_time);
        },
        error: function ()
        {
            console.log("Error while getting medication planned");
        }
    });
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



