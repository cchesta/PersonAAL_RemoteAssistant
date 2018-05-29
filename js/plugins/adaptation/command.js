/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//var ipHueBridge = "";
//var hueUsername = "CFxSBYveJakskmRmNCQnnXJjN4nxG6DdLgq3HYNi";

var hue=jsHue();
var ipHueBridge = "";
var hueUsername = "";
var appName  = "personAAL";
var rooms = new Array();

window.onload = function() {
       //internationalization
    
    var userLang = getUserLanguage(); 
    console.log("User Language: " + userLang);

    switch(userLang)
    {
        case 'en':
            noBridgeMsg = 'No bridges found. :(';
            bridgeFoundMsg = 'Bridge found at IP address';
            errorBridgeMsg = 'Error finding bridges';
            userMsg = 'Hue username';
            errorUserMsg = 'Error while getting user name';
            lightMsg = 'Hue lights'; 
            errorLightMsg = 'Error while setting lights';
        break;
                
        case 'de':
            noBridgeMsg = 'Bridge nicht gefunden. :(';
            bridgeFoundMsg = 'Bridge unter IP Adresse gefunden';
            errorBridgeMsg = 'Fehler beim Suchen der Bridge';
            userMsg = 'HUE Benutzername';
            errorUserMsg = 'Fehler bei Eingabe des Benutzernamens';
            lightMsg = 'HUE Leuchten'; 
            errorLightMsg = 'Fehler bei Lichteinstellung';
        break;
       
        case 'no':
            noBridgeMsg = 'Det er ikke funnet noen bro (bridge)';
            bridgeFoundMsg = 'Bro (bridge) er funnet på følgende IP adresse';
            errorBridgeMsg = 'Feilmelding. Finner ikke bro (bridges)';
            userMsg = 'Hue brukernavn';
            errorUserMsg = 'Det har oppstått en feil med å finne brukernavn'; 
            lightMsg = 'Hue lys';
            errorLightMsg = 'Det har oppstått en feil ved innstilling av lyset';
        break;
                
        default:
            noBridgeMsg = 'No bridges found. :(';
            bridgeFoundMsg = 'Bridge found at IP address';
            errorBridgeMsg = 'Error finding bridges';
            userMsg = 'Hue username';
            errorUserMsg = 'Error while getting user name';
            lightMsg = 'Hue lights'; 
            errorLightMsg = 'Error while setting lights';
        break;
    }
 };  


function discoverBridge()
{
    hue.discover().then(bridges => {
            if(bridges.length === 0) {
                console.log(noBridgeMsg);
                ipHueBridge = noBridgeMsg;
            }
            else {
                bridges.forEach(b => {
                    console.log(bridgeFoundMsg+ '%s.', b.internalipaddress);
                    ipHueBridge = b.internalipaddress.toString();
                    
                });
            }
            console.log('ipHue %s ', ipHueBridge);
            document.getElementById("ipHue").innerHTML = ipHueBridge;
    }).catch(e => console.log(errorBridgeMsg, e));
}

function getHueUsername(){
    var bridge = hue.bridge(ipHueBridge);
    bridge.createUser(appName +'#' + userName).then(data => {
    // extract bridge-generated username from returned data
        if (data[0].success !== undefined)
            {
                hueUsername = data[0].success.username;
                console.log(userMsg + ' %s', hueUsername);
                document.getElementById("unHue").innerHTML = hueUsername;
            }
            else if(data[0].error !== undefined)
            {
                console.log(errorUserMsg , data[0].error.description);
                document.getElementById("unHue").innerHTML = data[0].error.description;
            }
    });
}


function getUsername() {
	var jsonString = { "devicetype" : appName +"#" + userName };
        console.log("Get username request: " + JSON.stringify(jsonString));
        //var jsonString = { "devicetype" : "personAAL#john"};
	$.ajax({
			type: "POST",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
                        url: "http://"+ipHueBridge+"/api/",
			data : JSON.stringify(jsonString),
			success: function (response) {            
				console.log("User name response", JSON.stringify(response));
                                
                                var result = JSON.stringify(response).replace("[","").replace("]","");
                                var objresult = JSON.parse(result);

                                if (objresult.success != undefined)
                                {
                                    hueUsername = objresult.success.username;
                                    console.log(userMsg + '%s', hueUsername);
                                    document.getElementById("unHue").innerHTML = hueUsername;
                                }
                                else if(objresult.error != undefined)
                                {
                                    console.log(errorUserMsg, objresult.error.description);
                                    document.getElementById("unHue").innerHTML = objresult.error.description;
                                }
                                    
			},
			error: function ()
			{
                            alert(errorUserMsg);
			}
		});
}
 
 function onClickTurnOnAndChangeColor(numLight) {
	var sat = $("#sat").val();
	var bri = $("#bri").val();
	var hex = $("#colorPicker").val();
	
	turnOnAndChangeColor(numLight, sat, bri, hex);
        getLightState();
}
//parametri:
//numLigh numero che rappresenta la lampadina (per vedere i numeri delle tue lampadine apri il browser
//			e vai alla seguente url: http://hue-bridge-address/api/nyKMBQUuxGlJvDwacdmK-Qxdcx5OeXwGUyGCIT2f/lights
//			questo servizio restituisce un JSON con la descrizione delle lampadine correttamente installate
// stauration e brighness: saturazione e lucentezza, io li metto sempre al massimo 
// hex: è il valore esadecimale che rappresenta il colore, andrà trasformato in valori xy che è un modo di rappresentare i colori per le lampadine hue

function turnOnAndChangeColor(numLight, saturation, brighness, hex) {	
		
	var _rVal = hexToR(hex);
        var _gVal = hexToG(hex);
        var _bVal = hexToB(hex);
	var xy = toXY(_rVal, _gVal, _bVal);
	var x0 = parseFloat(xy[0]);
	var y1 = parseFloat(xy[1]);
	var jsonString = {
		"on" : true,
		"sat" : parseInt(saturation), 
		"bri" : parseInt(brighness),
		"xy": [ x0, y1]
	};  
	
	$.ajax({
			type: "PUT",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			//this is the url of the rest service runnong on the HUE bridge
			url: "http://"+ipHueBridge+"/api/"+hueUsername+"/lights/"+numLight+"/state",
			data : JSON.stringify(jsonString),		
			success: function (response) {            
				console.log(lightMsg, response);
//				$("#res").html(JSON.stringify(response));
			},
			error: function ()
			{
				alert(errorLightMsg);
			}
		});
		
}
 
function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16);}
function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16);}
function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16);}
function cutHex(h) {return (h.charAt(0)==="#") ? h.substring(1,7):h;}



function toXY(red, green, blue) {
    //Gamma correctie
    red = (red > 0.04045) ? Math.pow((red + 0.055) / (1.0 + 0.055), 2.4) : (red / 12.92);
    green = (green > 0.04045) ? Math.pow((green + 0.055) / (1.0 + 0.055), 2.4) : (green / 12.92);
    blue = (blue > 0.04045) ? Math.pow((blue + 0.055) / (1.0 + 0.055), 2.4) : (blue / 12.92);

    //Apply wide gamut conversion D65
    var X = red * 0.664511 + green * 0.154324 + blue * 0.162028;
    var Y = red * 0.283881 + green * 0.668433 + blue * 0.047685;
    var Z = red * 0.000088 + green * 0.072310 + blue * 0.986039;

    var fx = X / (X + Y + Z);
    var fy = Y / (X + Y + Z);
    /*
     if (isnan(fx)) {
     fx = "0.0f";
     }
     if (isnan(fy)) {
     fy = "0.0f";
     }
     */
    return [fx.toPrecision(4), fy.toPrecision(4)];
}


function turnOnOffLight(num, state) {
	var jsonString = { "on" : state };
	$.ajax({
			type: "PUT",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			//url: "http://146.48.85.37/api/MvRgdGPhMJzPQtdyK3sOaOfrDnT8NxZNNihddg6A/lights/"+num+"/state",
                        url: "http://"+ipHueBridge+"/api/"+hueUsername+"/lights/"+num+"/state",
			data : JSON.stringify(jsonString),		
			success: function (response) {            
				console.log(lightMsg, JSON.stringify(response));
				getLightState();
			},
			error: function ()
			{
				alert(errorLightMsg);
			}
		});
}

function getLightState() {
	$.ajax({
			type: "GET",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			//url: "http://146.48.85.37/api/MvRgdGPhMJzPQtdyK3sOaOfrDnT8NxZNNihddg6A/",
                        url: "http://"+ipHueBridge+"/api/"+hueUsername+"/",
			success: function (response) { 
                                $("#res").html("Light1" + JSON.stringify(response.lights["1"].state) + " Light2" + JSON.stringify(response.lights["2"].state) + " Light3" + JSON.stringify(response.lights["3"].state));
				if(response.lights["1"] !== undefined && response.lights["1"].state.reachable) {
                                    document.getElementById("status1").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    document.getElementById("color").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    var e = document.getElementById("room1");
                                    rooms[1] = e.options[e.selectedIndex].value;
                                    console.log("Room1: " + rooms[1]);
                                    if(response.lights["1"].state.on) {
                                        document.getElementById("btn1chg").disabled = false;
                                        document.getElementById("btn1on").disabled = true;
                                        document.getElementById("btn1off").disabled = false;
                                    } else {
                                        document.getElementById("btn1chg").disabled = false;
                                        document.getElementById("btn1on").disabled = false;
                                        document.getElementById("btn1off").disabled = true;
                                    }			
				} else {
                                    document.getElementById("status1").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden";
				}
				if(response.lights["2"] !== undefined && response.lights["2"].state.reachable) {
                                    document.getElementById("status2").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    document.getElementById("color").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    var e = document.getElementById("room2");
                                    rooms[2] = e.options[e.selectedIndex].value;
                                    console.log("Room2: " + rooms[2]);
                                    if(response.lights["2"].state.on) {
                                        document.getElementById("btn2chg").disabled = false;
                                        document.getElementById("btn2on").disabled = true;
                                        document.getElementById("btn2off").disabled = false;
                                    } else {
                                        document.getElementById("btn2chg").disabled = false;
                                        document.getElementById("btn2on").disabled = false;
                                        document.getElementById("btn2off").disabled = true;
                                    }			
				} else {
                                    document.getElementById("status2").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden";
				}
				if(response.lights["3"] !== undefined && response.lights["3"].state.reachable) {
                                    document.getElementById("status3").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    document.getElementById("color").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:visible";
                                    var e = document.getElementById("room3");
                                    rooms[3] = e.options[e.selectedIndex].value;
                                    console.log("Room3: " + rooms[3]);    
                                    if(response.lights["3"].state.on) {
                                        document.getElementById("btn3chg").disabled = false;
                                        document.getElementById("btn3on").disabled = true;
                                        document.getElementById("btn3off").disabled = false;
                                    } else {
                                        document.getElementById("btn3chg").disabled = false;
                                        document.getElementById("btn3on").disabled = false;
                                        document.getElementById("btn3off").disabled = true;
                                    }			
				} else {
                                    document.getElementById("status3").style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden";
				}
			},
			error: function ()
			{
				alert(errorLightMsg);
			}
		});
}

