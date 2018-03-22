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
        
//   setInterval(getECG_HR, 5000); 
//   setInterval(getRespirationRate, 5000); 
//   setInterval(getBodyTemperature, 5000); 
    setInterval(getDailySteps, 5000); 
    setInterval(getMedicationPlanned, 5000);
    setInterval(getWeight, 5000);
//   setInterval(getMedicationOccurred, 5000);
    setInterval(getTime, 60000);
   
    
};


function getDailySteps() {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ token + "/steps/", 
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


function sendMotivationDataToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + token + "/motivation/" + val,
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
        url: contextUrl + "cm/rest/user/" + token + "/personalData/age/" + val,
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
        url: contextUrl + "cm/rest/user/" + token + "/personalData/weight/" + val,
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

function sendStepGoalToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + token + "/stepsGoal/" + val,
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



//NEW
function sendMeetGoalToContext(val){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + token + "/environment/meetGoal/" + val,
        success: function (response) {            
        console.log("Context response Meet Goal", response);
        },
        error: function()
        {
            console.log("Error while sending meet goal to context");
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

function sendTimeToContextManager(timeValue) {

     $.ajax({
         type: "GET",
         headers: {
             'Accept': 'application/json',
             'Content-Type': 'application/json'
         },
         url: contextUrl + "cm/rest/user/" + token + "/time/" + timeValue,
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
        url: contextUrl + "cm/rest/user/"+ token + "/medication_planned/", 
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
        url: contextUrl + "cm/rest/user/"+ token + "/medication_occurred/", 
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
//			url: "https://giove.isti.cnr.it:8443/cm/rest/user/john/medication_planned",
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