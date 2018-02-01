/* global stompSessionId */

//var userName = "reply_user";
//var userName = "cchesta";
var username = "john";
var appName  = "personAAL";
var contextManagerUrl = "https://giove.isti.cnr.it:8443/cm/";
	
function subscribeToAdaptationEngine() {
    var subscriptionRequest = '{' +
            '"userName" : "' + userName + '",' +
            '"appName" : "' + appName + '",' +
            '"sessionId" : "' + stompSessionId + '",' +
			'"actionFormat" : "json"' +  
            '}';
    console.log(subscriptionRequest);
    $.ajax({
        type: "POST",
        crossDomain: true,
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: "https://giove.isti.cnr.it:8443/NewAdaptationEngine/rest/subscribe",
        dataType: 'json',
        data: subscriptionRequest,
        success: function (response) {  
            console.log(response);
            if(response.subscribed) {                
//				$("#aeSubscriptionStatus").html("Subscribed to Adaptation Engine <br>");
//				var txt = "";
//                for(var i = 0; i < response.subscribedRules.length; i++) {
//                    txt += "Subscribed Rule Name " + response.subscribedRules[i] + "<br>";
//                }
//                $("#subscribedRules").html(txt);
//				if(response.notSubscribedRules.length > 0)
//					txt = "Not subscribed rules <br>";
//				else
//					txt = "";
//				for(var i = 0; i < response.notSubscribedRules.length; i++) {
//                    txt += "Not Subscribed Rule Name " + response.notSubscribedRules[i] + "<br>";
//                }
//				$("#notSubscribedRules").html(txt + "<br>"+response.errorMessage+"<br><br>");
                    console.log("Subscribed to Adaptation Engine");
               
                    
                    for(var i = 0; i < response.subscribedRules.length; i++) { //stampare regola in linguaggio naturale
                        var str = JSON.stringify(response.subscribedRules[i]);
                        str = JSON.stringify(response.subscribedRules[i], null, 4);
                        console.log("\tSubscribed Rule Name " + str);
                    }
                    console.log("NotSubscribedRules rules:");
                    for(var i = 0; i < response.notSubscribedRules.length; i++) {
                        console.log("\tSubscribed Rule Name " + response.notSubscribedRules[i]);
                    }
                    console.log("error messages: " + response.errorMessage);
            } else {
//                $("#aeSubscriptionStatus").html("Not subscribed to AE: "+response.errorMessage);
                    console.log("subscription failed");
                    
                    
            }
        },
        error: function ()
        {
            //$("#aeSubscriptionStatus").html("Error while subscribing to adaptation engine");
            console.log("error while subscribing");
        }
    });
}
/* Example action format
{
	"actionsList":[
            {
                "action":[
                    {
                        "invokeFunction":{
                            "input":[
                                {
                                    "value": {
                                        "entityReference":null,
                                        "constant":{
                                            "value":"HR too high","type":"STRING"
                                        }
                                    },
                                    "name":"alarmText"
                                },
                                {
                                    "value":{
                                        "entityReference":null,
                                        "constant":{
                                            "value":"CALL",
                                            "type":"STRING"
                                        }
                                    },
                                    "name":"notificationMode"
                                },
                                {
                                    "value":{
                                        "entityReference":null,
                                        "constant":{
                                            "value":"2 TIMES","type":"INT"
                                        }
                                    },
                                    "name":"repetition"
                                }
                            ],
                            "output":[],
                            "name":"Alarms"
                        }
                    }
                ]
            }
	],
	"eventList":[
		{
			"name":"Heart_rate_1476952524270_1",
			"ref":"/user/reply_user/physiological/heartState/@ECG_HR",
			"value":"90",
			"verified":true
		}
	]
}
*/
function applyRule(actions) {	
    
	//$("#actions").html(actions);
        console.log("actions: ");
        console.log(actions);
        
        //showInfoModal(actions);
        
	var parsedActions = $.parseJSON(actions);
        
        $.each(parsedActions.actionsList, function() {
		/* "update": { 
		*        "entityReference": { 
		*              "externalModelId": "ctx",
		*              "xpath": "descendant::grouping[@id='shoppingListContent']/@font-size"
		*        } 
		*  }   
		* k = update 
		* v = { "entityReference : { ... }
		*/
        $.each(this.action, function(k, v) {  
            switch(Object.keys(v)[0]) {
                case "create" : 
                    applyCreate(v.create);
                    break;
                case "read":
                    applyRead(v.read);
                    break;
                case "delete":
                    applyDelete(v._delete);
                    break;
                case "update" :
                    applyUpdate(v.update);
                    break;
                case "while" :
                    applyWhile(v._while);
                    break;
		case "foreach" :
                    applyForeach(v.foreach);
                    break;
		case "for" :
                    applyFor(v._for);
                    break;
		case "loadURL" :
                    applyLoadURL(v.loadURL);
                    break;
                case "invokeFunction" :
                    console.log("invokeFunction");
                    applyInvokeFunction(v.invokeFunction);
                    break;
            }
            
        });
    });
}

function applyCreate(create) {
	console.log("create");
}

function applyRead(read) {
	console.log("read");
}

function applyDelete(_delete) {
	console.log("delete");
}

function applyForeach(foreach) {
	console.log("foreach");
}

function applyFor(_for) {
	console.log("for");
}

function applyLoadURL(loadURL) {
	console.log("loadURL");
}


function applyInvokeFunction(invokeF) {	
    console.log("Apply InvokeFunction");
    if (invokeF.name != null)
    {   
        switch (invokeF.name) {
            case "Alarms" :
                console.log("Alarms");
                applyAlarm(invokeF.input);
                break;
            case "Reminders" :
                console.log("Reminders");
                applyAlarm(invokeF.input);
                break;
        }
    }   
    else{
            console.log("IncreaseFontSize");
            increaseGlobalFontSize();
    }
}



function applyAlarm(inputParams) {
	console.log("Apply Alarm");
    var alarmText;
    var notificationMode;
    var repetition;
    for(var i = 0; i < inputParams.length; i++) {
        if(inputParams[i].name === 'alarmText'){
            alarmText = inputParams[i].value.constant.value;
            //show alert modal
            showAlertModal(alarmText);
        }  
        else if(inputParams[i].name === 'reminderText'){
            alarmText = inputParams[i].value.constant.value;
            $("#persuasive-message").html(alarmText);
        }   
        else if(inputParams[i].name === 'notificationMode')
            notificationMode = inputParams[i].value.constant.value;
        if(inputParams[i].name === 'repetition')
            repetition = inputParams[i].value.constant.value;
    }
    
    
    $.ajax({
        type: "GET",        
        url: "CommandServlet?cmd=sendAlarm&notificationMode="+notificationMode+"&alarmText="+alarmText+"&repetition"+repetition,                
        success: function (response) {   
            console.log(response);
        },
        error: function ()
        {
            console.log("Error sending alarm");
        }
    });
}




/*by davide dimola
 * show alert dialog with "alarmText" message 
 */
function showAlertModal(alarmText)
{
    $("#modal-alert-text").html(alarmText);
    $("#alert-modal").modal({ keyboard: false, backdrop: 'static'});
}

function showInfoModal(text)
{
    $("#modal-info-text").html(text);
    $("#info-modal").modal({ keyboard: false, backdrop: 'static'});
}

/*by davide dimola
 * increase font size of entire application
 */
function increaseGlobalFontSize()
{
    //increase card supporting text size
    $('.mdl-card__supporting-text').css('font-size', 2 +'rem');

    //increase card title text size
    var fontsize=  parseInt($('.mdl-card__title-text').css('font-size'));
    $('.mdl-card__title-text').css('font-size', (fontsize + 10)+'px');

    //increase card action text size
    fontsize=  parseInt($('.mdl-card__actions').css('font-size'));
    $('.mdl-card__actions').css('font-size', (fontsize + 5)+'px');


    //increase navigation link size
    fontsize=  parseInt($('.mdl-navigation__link').css('font-size'));
    $('.mdl-navigation__link .material-icons').css('font-size', (fontsize + 15)+'px');
    $('.mdl-navigation__link').css('font-size', (fontsize + 5)+'px');

    //increase forms text size
    fontsize=  parseInt($('.card-group-label').css('font-size'));
    $('.card-group-label').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-radio__label').css('font-size'));
    $('.mdl-radio__label').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-checkbox__label').css('font-size'));
    $('.mdl-checkbox__label').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-textfield').css('font-size'));
    $('.mdl-textfield').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-textfield__label').css('font-size'));
    $('.mdl-textfield__label').css('font-size', (fontsize + 5)+'px');

    //increase button text size
    fontsize=  parseInt($('.mdl-button').css('font-size'));
    $('.mdl-button').css('font-size', (fontsize - 5)+'px');

    //increase list text size
    fontsize=  parseInt($('.mdl-list__item-primary-content').css('font-size'));
    $('.mdl-list__item-primary-content').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-list__item-sub-title').css('font-size'));
    $('.mdl-list__item-sub-title').css('font-size', (fontsize + 5)+'px');

    //increase calendar card text size
    fontsize=  parseInt($('#external-events h4').css('font-size'));
    $('#external-events h4').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('.mdl-chip__text').css('font-size'));
    $('.mdl-chip__text').css('font-size', (fontsize + 5)+'px');
    fontsize=  parseInt($('#calendar table thead tr').css('font-size'));
    $('#calendar table thead tr').css('font-size', (fontsize + 3)+'px');

    //increase modal text size
    fontsize=  parseInt($('.interval-picker-hint').css('font-size'));
    $('.interval-picker-hint').css('font-size', (fontsize + 4)+'px');
    fontsize=  parseInt($('.recipe-modal-instruction').css('font-size'));
    $('.recipe-modal-instruction').css('font-size', (fontsize + 5)+'px');

    //increase menu font size
    fontsize=  parseInt($('.mdl-menu__item').css('font-size'));
    $('.mdl-menu__item').css('font-size', (fontsize + 5)+'px');

    //increase tabs text size
    fontsize=  parseInt($('.mdl-layout__tab').css('font-size'));
    $('.mdl-layout__tab').css('font-size', (fontsize + 2)+'px');

    //increase icon size
    fontsize=  parseInt($('.add-interest-dialog .material-icons').css('font-size'));
    $('.add-interest-dialog .material-icons').css('font-size', (fontsize + 2)+'px');
    fontsize=  parseInt($('.interest-list-card .material-icons').css('font-size'));
    $('.interest-list-card .material-icons').css('font-size', (fontsize + 2)+'px');
    
    //increase data tables text size
    fontsize=  parseInt($('.mdl-data-table').css('font-size'));
    $('.mdl-data-table').css('font-size', (fontsize + 7)+'px');
    fontsize=  parseInt($('.mdl-data-table .mdl-chip__text').css('font-size'));
    $('.mdl-data-table .mdl-chip__text').css('font-size', (fontsize + 7)+'px');
    fontsize=  parseInt($('.mdl-data-table .material-icons').css('font-size'));
    $('.mdl-data-table .material-icons').css('font-size', (fontsize + 7)+'px');
}


function applyUpdate(update) {
	/* "update": { 
    *        "entityReference": { 
    *              "externalModelId": "ctx",
    *              "xpath": "descendant::grouping[@id='shoppingListContent']/@font-size"
    *        } 
    *  }   
    * k = update 
    * v = { "entityReference : { ... }
    */		 
    var entityReference = update.entityReference;
    var xPath = entityReference.xpath;
    
    var value = update.value;
    
    if(strStartsWith(xPath, "ui:")){
		//If the xPath starts with ui: the action updates the user interface presentation
		//the following examples update the css class, the backgroung color and the width of
		//an ui element with id equal to id_ui_element.
        //ui:elementType[@id='id_ui_element']/@class
        //ui:elementType[@id='id_ui_element']/@background-color
        //ui:elementType[@id='id_ui_element']/@width
		
        var indexOf = xPath.indexOf("@");
	var elemId = xPath.substring(3, indexOf - 1);
		
	var attrName = xPath.substring(indexOf+1);
		
	$("#"+elemId).css(attrName, value.constant.value);
        
    } else if(strStartsWith(xPath, "applianceState")){
		//If the xPath starts with applianceState the action changes the state of an appliance
		//e.g. applianceState/LivingRoom/lightColor/@state
        manageApplianceState(xPath, value.constant.value);
    } else {
        
    }
}

function manageApplianceState(xPath, value) {
    //light1 = LivingRoom
    //light2 = Bathroom
    //light3 = Bedhroom
    
    //presa1 = ventilatore (fan)
    //presa2 = radio
    //presa3 = webcam
    
    var xPathArray = xPath.split("/");
    var roomName = xPathArray[1];
    var applianceName = xPathArray[2];
    var attrToChange = xPathArray[3].substring(1);
    //applianceState/roomName/lightColor/@state
    //applianceState/roomName/lightColor/@color        
    //setBulbValuesXY(bulbIndex, newSatState, newBriState, rgb);
    //
    //applianceState/Kitchen/radio/@state
    //applianceState/LivingRoom/radio/@state
    //applianceState/Bedroom/fan/@state
    //relaySwitch('0', 'on') -- relaySwitch('0', 'off')
    
    switch (applianceName) {
        case "lightColor" :
            updateLightColor(roomName, attrToChange, value);
            break;
        case "radio":
            relaySwitch('2', value);
            break;
        case "fan":
            relaySwitch('0', value);
            break;
    }
}

function updateLightColor(room, attrToChange, value) {
switch (room) {
            case "LivingRoom"://1
                if (attrToChange === 'color') {					
                    sendValuesToHueHex(1, 254, 254, value);
		} 
                else if (attrToChange === 'state') {
                    if(value === 'blink')
                        blinkBulb(1);
                    else if (value === 'on')
                        turnOnOffLight(1, true);
                    else
                        turnOnOffLight(1, false);
                }
                break;
            case "Bathroom"://2
                if (attrToChange === 'color') 
                    sendValuesToHueHex(2, 254, 254, value);
                else if (attrToChange === 'state'){
                    console.log("value: " + value);
                    if (value === 'on')
                        turnOnOffLight(2, true);
                    else
                        turnOnOffLight(2, false);
                }
                break;
            case "Bedroom"://3
                if (attrToChange === 'color') 
                    sendValuesToHueHex(3, 254, 254, value);
                else if (attrToChange === 'state'){
                    if (value === 'on')
                        turnOnOffLight(3, true);
                    else
                        turnOnOffLight(3, false);
                }
                break; 
            }
}



function strStartsWith(str, prefix) {
    return str.indexOf(prefix) === 0;
}