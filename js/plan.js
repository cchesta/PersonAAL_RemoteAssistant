/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var socket;

var contextUrl = "https://giove.isti.cnr.it:8443/";
//var userName = "cchesta";
//var userName = "john";
var appName  = "personAAL";


var meetGoal = 0;
var walkGoal =  0;
var exerciseGoal = 0;

var actualMeet = 0;
var actualWalk = 0;
var actualExercise = 0; 

var activityTypeExercise;
var activityTypeWalk;
var activityTypeSocial;

var smWalk = true;
var smExercise = true;
var smMeet = true;

var tokenAC;



window.onload = init;

function init() {

    //internationalization
    var userLang = getUserLanguage(); 
    console.log(userLang);

    switch(userLang)
    {
        case 'en':
            activityTypeWalk = "Walk";
            activityTypeExercise = "Exercise";
            activityTypeSocial = "Social Activity";
            hoursMsg = ' hours ';
            hourMsg = ' hour ';
            minutesMsg = ' minutes ';
            minuteMsg = ' minute '
            break;

        case 'de': 
            activityTypeWalk = "Bewegung";
            activityTypeExercise = "Fitnessübungen";
            activityTypeSocial = "Soziale Aktivitäten";
            hoursMsg = ' stunden ';
            hourMsg = ' stunde ';
            minutesMsg = 'minuten ';
            minuteMsg = ' minute '
            break;

        case 'no':
            activityTypeWalk = "Gå tur";
            activityTypeExercise = "Trening";
            activityTypeSocial = "Sosiale Aktiviteter";
            hoursMsg = ' time ';
            hourMsg = ' timer ';
            minutesMsg = ' minutter ';
            minuteMsg = ' minutt '
            break;

        default:
            activityTypeWalk = "Walk";
            activityTypeExercise = "Exercise";
            activityTypeSocial = "Social Activity";
            hoursMsg = ' hours ';
            hourMsg = ' hour ';
            minutesMsg = ' minutes ';
            minuteMsg = ' minute '
            break;


    }
};



function sendExerciseGoalsToContext(val){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/environment/exerciseGoal/" + val),
        success: function (response) {            
            console.log("Context response Exercise Goal", response);
            $("#exercise_goal_text").html(Number(response.value));
        },
        error: function()
        {
            console.log("Error while sending exercise goal to context");
        }
    });
}


function sendStepGoalToContext(val) {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/stepsGoal/" + val),
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

function sendWalkGoalToContext(val){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/environment/walkGoal/" + val),
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Walk Goal", response);
            $("#walk_goal_text").html(Number(response.value));
        },
        error: function ()
        {
            console.log("Error while sending walk goal to context");
        }
    });
}

function sendMeetGoalToContext(val){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/environment/meetGoal/" + val),
        success: function (response) {            
            console.log("Context response Meet Goal", response);
            $("#meet_goal_text").html(Number(response.value));
        },
        error: function()
        {
            console.log("Error while sending meet goal to context");
        }
    });
}

function getExerciseGoalFromContext(callback3){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ userId + "/environment/exerciseGoal/"),
        dataType: 'json',

        success: function (response) {            
            console.log("Context response exercise goal", response);
            $("#exercise_goal_text").html(Number(response.value));
            exerciseGoal = response.value;
            smExercise = false;
            document.getElementById("slide_01").MaterialSlider.change(response.value);
            $("#inp_text_01").val(response.value);
            callback3();
        },
        error: function ()
        {
            console.log("Error while getting exercise goal");
        }
    });
}



function getWalkGoalFromContext(callback2,callback3){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ userId + "/environment/walkGoal/"),
        dataType: 'json',

        success: function (response) {            
            console.log("Context response Walk Goal", response);
            $("#walk_goal_text").html(Number(response.value));
            walkGoal = response.value;
            smWalk = false;
            document.getElementById("slide_02").MaterialSlider.change(response.value);
            $("#inp_text_02").val(response.value);
            callback2(callback3);
        },
        error: function ()
        {
            console.log("Error while getting walk goal");
        }
    });
}





function getMeetGoalFromContext(callback1,callback2,callback3){
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/environment/meetGoal/"),
        dataType: 'json',

        success: function (response) {            
            console.log("Context response Meet goal", response);
            meetGoal = Number(response.value);
            $("#meet_goal_text").html(Number(response.value));
            smMeet = false;
            document.getElementById("slide_03").value = response.value;
            $("#inp_text_03").val(response.value);
            callback1(callback2,callback3);
        },
        error: function ()
        {
            console.log("Error while getting meet goal");
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


function getHour(value) {
    if (value == null) { return ""; }
    if (value < 0) { return ""; }
    var hours = Math.floor(value / 60);
    var minutes = Math.round(((value % 60)*100)/100);
    var hour = (hours > 1) ? hours + hoursMsg : hours + hourMsg;
    var min = (minutes > 0) ? minutes + minutesMsg : "";
    return hour + min;
}

function getCompletedActivityFromContext(callback){
    var d = new Date();
    var monday = getMondayOfCurrentWeek(d);
    var yyyymmdd = yymmdd(monday);

    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ userId + "/activity/CompletedActivity/history/getValuesFromDateToNow/" + yyyymmdd),
        dataType: 'json',

        success: function (response) {
            console.log("Context response completed activity", response);
            actualMeet = 0;
            //actualWalk = 0;
            actualExercise = 0; 
                         
            if(!response.historyCompletedActivity){
                //actualMeet = 0;
                //actualWalk = 0;
                //actualExercise = 0; 
            }
            else if(response.historyCompletedActivity.constructor!==Array){
                switch(response.historyCompletedActivity.activity_type){
                    case 'Exercise':
                        actualExercise += Number(response.historyCompletedActivity.completed_duration);
                        break;
                    case 'Walk':
                        //actualWalk += Number(response.historyCompletedActivity.completed_duration);
                        break;
                    case 'Social':
                        actualMeet +=1;
                        break;
                }
            }
            else{
            for(var i = 0; i<(response.historyCompletedActivity).length; i++){
                switch((response.historyCompletedActivity)[i].activity_type){
                    case 'Exercise':
                        actualExercise += Number((response.historyCompletedActivity)[i].completed_duration);
                        break;
                    case 'Walk':
                        //actualWalk += Number((response.historyCompletedActivity)[i].completed_duration);
                        break;
                    case 'Social':
                        actualMeet += Number(1);
                        break;
                }
            };
            }
            $("#actual_exercise_text").html(getHour(Number(actualExercise)));
            //$("#actual_walk_text").html(getHour(Number(actualWalk)));
            $("#actual_meet_text").html(Number(actualMeet));
            
            getFitbitActivityHistoryFromContext(callback);
        
        },

        error: function ()
        {
          $("#actual_exercise_text").html(0);
            $("#actual_walk_text").html(0);
            $("#actual_meet_text").html(0);
            console.log("Error while getting completed activity data");
        }
    });
}


function sendCompletedActivityToContext(activity_intensity, activity_name,activity_type,completed_duration,completed_time,completed_timestamp){
    var CompletedActivityObj = {
        "activity_intensity": activity_intensity,
        "activity_name": activity_name,
        "activity_type": activity_type,
        "completed_duration": completed_duration,
        "completed_time":completed_time,
        "completed_timestamp": completed_timestamp
    };
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin' : '*'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + token + "/activity/CompletedActivity/"),			  
        dataType: 'json',
        data: JSON.stringify(CompletedActivityObj),
        success: function (response) {     
            $("#response").html(JSON.stringify(response));
        },
        error : function(err) {
            $("#response").html(JSON.stringify(err));
        }
    });

}




function getDailySteps() {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/"+ userId + "/steps/"),
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


function getToken(callbackT,callbackUC) {
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
                tokenAC = response.access_token;
                //return response.access_token;
                callbackT(callbackUC);
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
                //return;
			}
        
	});
}


function getActivityFromActivityTracker(callbackUC){
    console.log("INSIDE GAFT FUNTION - TOKENAC: ", tokenAC);
    $.ajax({
			type: "GET",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json',
                //'Access-Control-Allow-Origin' : '*',
                //'Access-Control-Allow-Headers' : 'Origin, X-Requested-With, Content-Type, Accept',
                //'Access-Control-Allow-Methods': 'GET,PUT,POST,DELETE,PATCH,OPTIONS'
                'Authorization': 'BEARER '+ tokenAC
			},
			url: encodeURI ( "https://activity-backend-personaal.eu-de.mybluemix.net/api/system/activities/"+ userId),
			dataType: 'json',
            beforeSend: function(xhr){
                xhr.setRequestHeader( 'Authorization', 'BEARER '+ tokenAC);
                console.log("REQUEST HEADER: ",xhr );
            },
			success: function (response) {     
				console.log("Activity from tracker: ", response);
                
                callbackUC(response);
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
               
			}
        
	});
        
    }
    
    
    function getFitbitActivityHistoryFromContext(callback){
        var d = new Date();
        var monday = getMondayOfCurrentWeek(d);
        var yyyymmdd = yymmdd(monday);
        //filtrar dados e usar os ultimos valores do dia
        
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
            actualWalk = 0;
            var steps = 0;
            if(!response.historyFitbitSummary){
                
            }
            else if(response.historyFitbitSummary.constructor!==Array){
                steps = Number(response.historyFitbitSummary.steps);
                actualWalk = (steps/6000)*60;
            }
            else {
                steps += Number((response.historyFitbitSummary)[historyFitbitSummary.length-1].steps);
                actualWalk = (steps/6000)*60;
            }
            $("#actual_walk_text").html(getHour(Number(actualWalk)));
            callback();
        },
        error: function ()
        {
            console.log("Error while getting fitbithistory");
        }
    });
    }







