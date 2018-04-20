/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/*******   WEIGHT DATA   *********/
/* Function to retreive the data in a format compatible with flot
 * and draw the weight plot using function drawWeightChart(..)
 * @returns {array[]}
 */
function getWeightPlotData(callback)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'getWeightPlotData', arguments: []},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("getWeightPlotData success");
                              console.log(obj.result);
                              
                              
                              //draw the chart
                              callback(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

/* add weight data directly on database
 * 
 * @param {type} timestamp
 * @param {type} weight
 * @returns {undefined}
 */
function addWeightData(timestamp, weight)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addWeightData', arguments: [timestamp, weight]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addWeightData success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

/*******   FiND Questionnaire DATA   *********/
/* Function to retreive the data in a format compatible with flot
 * and draw the score plot using function drawFindChart(..)
 * @returns {array[]}
 */
function getFindPlotData(callback)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'getFindPlotData', arguments: []},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("getFindPlotData success");
                              console.log(obj.result);
                             
                              //draw the chart
                              callback(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}


/* add FiND questionnaire score data directly on database
 * 
 * @param {type} timestamp
 * @param {type} score
 * @returns {undefined}
 */
function addFindData(timestamp, score)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addFindData', arguments: [timestamp, score]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addFindData success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

/* add survey data on database
 * 
 * @param {type} weight
 * @param {type} height
 * @param {type} age
 * @param {type} motivation
 * @returns {undefined}
 */
function addSurveyData(weight, height, age, motivation)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addSurveyData', arguments: [weight, height, age, motivation]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addSurveyData success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}


    



/*******  ACTIVITY DATA **********/



function addActivity(title, start_date, end_date, all_day,done, type, intensity,callback){
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addActivity', arguments: [title, start_date, end_date, all_day,done, type, intensity]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addActivity success");
                              console.log(obj.result);
                              callback( obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error " + textstatus);
                console.log(ob);
            }
        });
}

function deleteActivity(activityId){
    jQuery.ajax({
        type: "POST",
        url: 'ajax_request.php',
        dataType: 'json',
        data: {functionname: 'deleteActivity', arguments: [activityId]},

        success: function (obj, textstatus) {
            if( !('error' in obj) ) {
                console.log("deleteActivity success");
                callback(activity);
            }
            else {
                console.log("error");
                console.log(obj.error);
            }
        },
        error: function (ob, textstatus) {
            console.log("error " + textstatus);
            console.log(ob);
        }
    });
}


function getActivity( callback){
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'getActivity', arguments: []},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("getActivity success");
                              console.log(obj.result);
                              callback(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error2 " + textstatus);
                console.log(ob);
            }
        });
}

function getActivitiesFromLastAccess(callback){
    console.log("HERE: Last_access");
    jQuery.ajax({
        type: "POST",
        url: 'ajax_request.php',
        dataType: 'json',
        data: {functionname: 'getActivitiesFromLastAccess', arguments: []},

        success: function (obj, textstatus) {
            if( !('error' in obj) ) {
                console.log("getActivitiesFromLastAccess success");
                console.log(obj.result);
                callback(obj.result);
            }
            else {
                console.log("error");
                console.log(obj.error);
            }
        },
        error: function (ob, textstatus) {
            console.log("error" + textstatus);
            console.log(ob);
        }
    });

}

function setActivityDone(activityId){
    jQuery.ajax({
        type: "POST",
        url: 'ajax_request.php',
        dataType: 'json',
        data: {functionname: 'setActivityDone', arguments: [activityId]},

        success: function (obj, textstatus) {
            if( !('error' in obj) ) {
                console.log("setActivityDone success");
                console.log(obj.result);
            }
            else {
                console.log("error");
                console.log(obj.error);
            }
        },
        error: function (ob, textstatus) {
            console.log("error" + textstatus);
            console.log(ob);
        }
    });
}

function updateLastAccess(){
    jQuery.ajax({
        type: "POST",
        url: 'ajax_request.php',
        dataType: 'json',
        data: {functionname: 'updateLastAccess', arguments: []},

        success: function (obj, textstatus) {
            if( !('error' in obj) ) {
                console.log("updateLastAccess success");
                console.log(obj.result);
            }
            else {
                console.log("error");
                console.log(obj.error);
            }
        },
        error: function (ob, textstatus) {
            console.log("error" + textstatus);
            console.log(ob);
        }
    });
}
 

    
/*******   PLAN DATA   *********/

/* set week goals on DB
 * 
 * @param {type} walkGoal
 * @param {type} exerciseGoal
 * @param {type} meetGoal
 * @returns {undefined}

function setWeekGoals(walkGoal, exerciseGoal, meetGoal, actualWeekWalk, actualWeekExercise, actualWeekMeet)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'setWeekGoals', arguments: [walkGoal, exerciseGoal, meetGoal, actualWeekWalk, actualWeekExercise, actualWeekMeet]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("setWeekGoals success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}
*/

/* add new event to DB
 * 
 * @param {obj} fullcalendar event
 * @returns {null}
 */
function addEvent(event)
{
    var eventJSONstring= {
                    type: event.type,
                    start: getDateString(event.start),
                    end: event.end,
                    done: event.done,
                    allDay: event.allDay,
                    durationEditable: false,
                    intervalValue: event.intervalValue
                };
    
    eventJSONstring= JSON.stringify(eventJSONstring);
    console.log(eventJSONstring);
    
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addEvent', arguments: [eventJSONstring]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addEvent success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

function getDateString(timestamp)
        {
            var eventDate= new Date(timestamp);
            
            //month fix
            var month= eventDate.getMonth() + 1;
            if(month < 10)
                month= '0'+ month;
            
            //day fix
            var date= eventDate.getDate();
            if(date < 10)
                date= '0'+ date;
            
            var eventDateString= eventDate.getFullYear() + '-' + month + '-' + date;
            return eventDateString;
        }

/* get all events and set up the calendar view
 * 
 * @returns {null}
 */
function getEvents(callback)
{
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'getEvents', arguments: []},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("getEvent success");
                              console.log(obj.result);
                              callback(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

/* save all the calendar events to DB (should use this at window.onbeforeunload)
 * 
 * @param {calendar} fullcalendar object
 * @returns {null}
 */
function saveEventList(calendar)
{
    var eventList= [];
            
    var calendarEventList= calendar.fullCalendar('clientEvents');

    //create a list of event (saving only needed attributes) from fullcalendar list
    for(var i=0; i < calendarEventList.length; i++)
    {
        var e= {
            title: calendarEventList[i].title,
            start: getDateString(calendarEventList[i].start),
            end: calendarEventList[i].end,
            done: calendarEventList[i].done,
            allDay: calendarEventList[i].allDay,
            durationEditable: false,
            intervalValue: calendarEventList[i].intervalValue
        };

        eventList.push(e);
    }
    eventList= JSON.stringify(eventList);
    
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'saveEventList', arguments: [eventList]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("saveEventList success");
                              console.log(obj.result);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
        
}










/*******   CONTACTS DATA   *********/

/* function that add a contact to DB
 * 
 * @param {string} contactName
 * @param {string} phoneNumber
 * @param {function} confirmCallback
 * @param {function} errorCallback
 * @returns {null}
 */
function addUserContact(contactName, phoneNumber, contactRelationship, confirmCallback, errorCallback)
{
    
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'addUserContact', arguments: [contactName, phoneNumber, contactRelationship]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("addUserContact success");
                              console.log(obj.result);
                              
                              if(obj.result === true)
                                  confirmCallback(contactName);
                              else
                                  errorCallback(contactName);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}


/* delete contact on DB
 * 
 * @param {string} contactName
 * @param {DOMelement} tableRow to delete
 * @param {function} confirmCallback
 * @param {function} errorCallback
 * @returns {undefined}
 */
function deleteUserContact(contactName, tableRow, confirmCallback, errorCallback)
{
    
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            data: {functionname: 'deleteUserContact', arguments: [contactName]},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("deleteUserContact success");
                              console.log(obj.result);
                              
                              if(obj.result === true)
                                  confirmCallback(tableRow);
                              else
                                  errorCallback(contactName);
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
}

/*
 * 
 * @returns {string} string of the content language requested by client browser
 */
function getUserLanguage()
{
    var lang= 'en';
    
    jQuery.ajax({
            type: "POST",
            url: 'ajax_request.php',
            dataType: 'json',
            async: false,
            data: {functionname: 'getUserLanguage', arguments: []},

            success: function (obj, textstatus) {
                          if( !('error' in obj) ) {
                              console.log("getUserLanguage success");
                              
                              lang= obj.result;
                          }
                          else {
                              console.log("error");
                              console.log(obj.error);
                          }
                    },
            error: function (ob, textstatus) {
                console.log("error" + textstatus);
                console.log(ob);
            }
        });
        
    return lang;
}

/*  function to retreive the user data passing throw the login operation
 * 
 * @param {string} username
 * @param {string} password
 * @param {function(userData)} confirmCallback
 * @param {function()} errorCallback
 * @returns {undefined}
 */
function getUserData(username, password, confirmCallback, errorCallback)
{
    jQuery.ajax({
        type: "POST",
        url: 'ajax_request.php',
        dataType: 'json',
        data: {functionname: 'getUserData', arguments: [username, password]},

        success: function (obj, textstatus) {
                      if( !('error' in obj) ) {
                          console.log("getUserData success");
                          //console.log(obj.result);
                          
                            if(obj.result === false)
                            {
                                console.log('wrong credentials');
                                errorCallback();
                            } 
                            else
                            {
                                console.log('valid credentials');
                                confirmCallback(JSON.parse(obj.result));
                            }
                                
                      }
                      else {
                          console.log("error");
                          console.log(obj.error);
                      }
                },
        error: function (ob, textstatus) {
            console.log("error" + textstatus);
            console.log(ob);
        }
    });
}
