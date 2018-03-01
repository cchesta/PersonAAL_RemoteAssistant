
//var userName = "john";
var appName  = "personAAL";
var contextManagerUrl = "https://giove.isti.cnr.it:8443/";

function sendECG_HR(val) {	
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextManagerUrl + "cm/rest/user/"+userName+"/heartRate/"+val,        
        success: function (response) {            
            console.log("Context response", response);
        },
        error: function ()
        {
            console.log("Error while sending data to context");
        }
    });
}

function sendLightDataToContext(val) {       
    var data = {
        PropertyRefs: [
            {
                "namespace": contextManagerUrl+"cm/",
                "propertyName": "light_level",
                "aspectName": "environment",
                "propertyValue": val
            }
        ]
    };
    sendEnvironmentDataToContext(data); 
}

function sendTemperatureDataToContext(val) {    
    var data = {
        PropertyRefs: [
            {
                "namespace": contextManagerUrl+"cm/",
                "propertyName": "temperature",
                "aspectName": "environment",
                "propertyValue": val
            }
        ]
    };
    sendEnvironmentDataToContext(data);
}

function sendEnvironmentDataToContext(data) {
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextManagerUrl+"cm/rest/user/"+userName+"/environment",
        dataType: 'json',
        data: JSON.stringify(data),
        success: function (response) {            
            console.log("Context response", response);
        },
        error: function ()
        {
            console.log("Error while sending environment data to context");
        }
    });
}

function sendMentalStateDataToContext() {
	var attention = 50;
	var meditation = 50;
	var blinking = 50;
	
	var data = {
		PropertyRefs:[
				{
                "namespace": contextManagerUrl+"cm/",
                "propertyName": "curr_attention",
                "aspectName": "mentalState",
                "propertyValue": attention
            },
			{
                "namespace": contextManagerUrl+"cm/",
                "propertyName": "curr_meditation",
                "aspectName": "mentalState",
                "propertyValue": meditation
            },
			{
                "namespace": contextManagerUrl+"cm/",
                "propertyName": "curr_blinking",
                "aspectName": "mentalState",
                "propertyValue": blinking
            }
		]
    };		
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextManagerUrl + "cm/rest/user/"+userName+"/mentalState",
        dataType: 'json',
        data: JSON.stringify(data),
        success: function (response) {            
            console.log("Context response", response);
        },
        error: function ()
        {
            console.log("Error while sending environment data to context");
        }
    });
}