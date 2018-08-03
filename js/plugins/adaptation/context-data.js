/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var userName = "john";
var appName  = "personAAL";
var contextUrl = "https://giove.isti.cnr.it:8443/";

window.onload = function() {
       //internationalization
    
    var userLang = getUserLanguage(); 
    console.log("User Language: " + userLang);

    switch(userLang)
    {
        case 'en':
            youdidMsg= 'You did ';
            stepsMsg= ' steps today!';
        break;
                
        case 'de':
            youdidMsg= 'You did ';
            stepsMsg= ' steps today!';
        break;
       
        case 'no':
            youdidMsg= 'Du har gått  ';
            stepsMsg= ' skritt i dag!';
        break;
                
        default:
            youdidMsg= 'You did ';
            stepsMsg= ' steps today!';
        break;
    }

    

//    setInterval(getDailySteps, 60000);
    setInterval(getMedicationPlanned, 60000);
//   setInterval(getMedicationOccurred, 5000);
    setInterval(getTime, 60000);
    setInterval(getHomeTemperature, 60000);
    setInterval(getHomeHumidity, 60000);
    setInterval(getMotion, 60000);
       
};

function getHomeTemperature()
{
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/environment/"+ userId + "Environment/temperature"),
        dataType: 'json',

        success: function (response) {
            console.log("Home temperature: ", response);
            $("#hometemperaturevalue").html(response.value + ' ºC');
        },
        error: function ()
        {
            console.log("Error getting home temperature");
        }
    });
}

function getHomeHumidity()
{
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/environment/"+ userId + "Environment/humidity"),
        dataType: 'json',

        success: function (response) {
            console.log("Home humidity: ", response);
            $("#homehumidityvalue").html(response.value + '%');
        },
        error: function ()
        {
            console.log("Error getting home humidity");
        }
    });
}

function getMotion()
{
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/environment/"+ userId + "Environment/motion"),
        dataType: 'json',

        success: function (response) {
            console.log("Motion: ", response);
            $("#motionvalue").html(response.value);
        },
        error: function ()
        {
            console.log("Error getting motion");
        }
    });
}

/*
function getDailySteps() {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ token + "/steps/"), 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response Daily Steps", response);
            $("#daily_steps").html(youdidMsg + Number(response.value) + stepsMsg);
        },
        error: function ()
        {
            console.log("Error while getting daily steps");
        }
    });
}
*/

function sendMotivationDataToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/motivation/" + val),
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Motivation", response);
        },
        error: function ()
        {
            console.log("Error while sending motivation data to context");
        }
    });
}

function sendAgeToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/personalData/age/" + val),
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Age", response);
        },
        error: function ()
        {
            console.log("Error while sending age to context");
        }
    });
}

function sendWeightToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/weight/" + val),
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Weight", response);
        },
        error: function ()
        {
            console.log("Error while sending weight to context");
        }
    });
}

function sendHeightToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/height/" + val),
        dataType: 'json',
        success: function (response) {
            console.log("Context response Weight", response);
        },
        error: function ()
        {
            console.log("Error while sending weight to context");
        }
    });
}

/*
function sendStepGoalToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + token + "/stepsGoal/" + val),
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Steps Goal", response);
        },
        error: function ()
        {
            console.log("Error while sending steps goal to context");
        }
    });
}
*/

/*
//NEW
function sendMeetGoalToContext(val){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/environment/meetGoal/" + val),
        success: function (response) {            
        console.log("Context response Meet Goal", response);
        },
        error: function()
        {
            console.log("Error while sending meet goal to context");
        }
    });
}
*/


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

function sendTimeToContextManager(timeValue) {

     $.ajax({
         type: "GET",
         headers: {
             'Accept': 'application/json',
             'Content-Type': 'application/json'
         },
         url: encodeURI ( contextUrl + "cm/rest/user/" + token + "/time/" + timeValue),
         dataType: 'json',
         success: function (response) {
             console.log("Context response", response);
         },
         error: function ()
         {
             console.log("Error while sending time data to context");
         }
     });
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
        url: encodeURI ( contextUrl + "cm/rest/user/"+ token + "/medication_planned/"), 
//        url: encodeURI ( contextUrl + "cm/rest/user/roytest/medication_planned/"),
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
        url: encodeURI ( contextUrl + "cm/rest/user/"+ token + "/medication_occurred/"), 
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

//function updateMedicationPlanned() {
//	var medicationObj = {
//		"notification_timestamp": "Fri, 21 Jul 2017 10:00:00 GMT",
//		"notification_time": "10:00",
//		"medication": "Aspirine",
//		"dosage": "60mg"
//	};
//	
//	$.ajax({
//			type: "POST",
//			headers: {
//				'Accept': 'application/json',
//				'Content-Type': 'application/json'
//			},
//			url: encodeURI ( "https://giove.isti.cnr.it:8443/cm/rest/user/john/medication_planned"),
//			dataType: 'json',
//			data: JSON.stringify(medicationObj),
//			success: function (response) {     
//				$("#response").html(JSON.stringify(response));
//			},
//			error : function(err) {
//				$("#response").html(JSON.stringify(err));
//			}
//	});
//}


function getToken() {
	var body = {
        "grant_type": "client_credentials",
"client_id": "WRnJaN3lnvtLsOCFCUFXE9Xmq5Zg77Yj",
"client_secret": "S4BKU_E39ZyQDoNm_2AJspYDjw6SpyQw6m3Mu0xL9AY4X_9oVsfd_sR6nH4uMAsi",
"audience": "https://activity-backend-personaal.eu-de.mybluemix.net"
	};
	console.log("INSIDE FUNCTION GET TOKEN");
	$.ajax({
			type: "POST",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
                //'Access-Control-Allow-Origin' : '*',
                //'Access-Control-Allow-Headers' : 'Origin, X-Requested-With, Content-Type, Accept',
                //'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,PATCH,OPTIONS'
			},
			url: encodeURI ( "https://personaal.eu.auth0.com/oauth/token"),
			dataType: 'json',
			data: JSON.stringify(body),
            beforeSend: function(xhr){
                xhr.setRequestHeader( 'Authorization', 'BEARER ');

            },
			success: function (response) {     
				console.log("Activity token: ", response);
                console.log("TOKEN ", response.access_token);
                return response.access_token;
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
                return;
			}
        
	});
}


function getMondayOfCurrentWeek(d)
{
    var day = d.getDay();
    return new Date(d.getFullYear(), d.getMonth(), d.getDate() + (day == 0?-6:1)-day );
}

function yymmdd(dataObj){
    var month = (dataObj.getMonth()+1);
    var monthStr = month<10? ('0' + month): month;
    var dateStr = dataObj.getDate()< 10? ('0' + dataObj.getDate()): dataObj.getDate();
    return dataObj.getFullYear() + "-" + monthStr + "-" + dateStr;
}


function getWeeklySteps(){
    var d = new Date();
        var monday = getMondayOfCurrentWeek(d);
        var yyyymmdd = yymmdd(monday);
       
        
        $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ userId + "/activity/FitbitDailySummary/history/getValuesFromDateToNow/" + yyyymmdd),
        dataType: 'json',

        success: function (response) {            
            console.log("Context response FitbitHistory", response);
            
            var steps = 0;
            if(!response.historyFitbitSummary){
                
            }
            else if(response.historyFitbitSummary.constructor!==Array){
                steps = Number(response.historyFitbitSummary.steps);
                
            }
            else {
                steps = getOneValADay(response.historyFitbitSummary);
                
            }
            $("#weeklySteps").html(Number(steps));
            //callback();
        },
        error: function ()
        {
            console.log("Error while getting weekly steps");
        }
    });  
    
}

function getOneValADay(historyFitbitSummary){
    var total = 0;

    total += Number(historyFitbitSummary[0].steps);

    for(var i = 1; i<historyFitbitSummary.length; i++ ){
        var activity = historyFitbitSummary[i-1];
        var nextActivity = historyFitbitSummary[i];
        
        var actDay = moment(activity.date);
        var nextDay = moment(nextActivity.date);
        

        if(!moment(nextDay).isSame(actDay, 'day')){
            console.log("inside if");
            total += Number(nextActivity.steps) ; 
    
        }
        
    }

    return total;
    
}


