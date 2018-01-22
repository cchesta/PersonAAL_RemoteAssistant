/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var ipHueBridge = "192.168.2.167";
var hueUsername = "CFxSBYveJakskmRmNCQnnXJjN4nxG6DdLgq3HYNi";


function hexToR(h) {return parseInt((cutHex(h)).substring(0,2),16);}
function hexToG(h) {return parseInt((cutHex(h)).substring(2,4),16);}
function hexToB(h) {return parseInt((cutHex(h)).substring(4,6),16);}
function cutHex(h) {return (h.charAt(0)==="#") ? h.substring(1,7):h;}


function sendValuesToHueHex(numLight, _sat, _bri, hex) {	
	var _sat = _sat;
	var _bri = _bri;
		
	var _rVal = hexToR(hex);
        var _gVal = hexToG(hex);
        var _bVal = hexToB(hex);
	var xy = toXY(_rVal, _gVal, _bVal);
	var x0 = parseFloat(xy[0]);
	var x1 = parseFloat(xy[1]);
	var jsonString = {
		"on" : true,
		"sat" : parseInt(_sat), 
		"bri" : parseInt(_bri),
		"xy": [ x0, x1]
	};  
	
	$.ajax({
			type: "PUT",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			//this is the url of the rest service running on the HUE bridge
			//url: "http://146.48.85.37/api/MvRgdGPhMJzPQtdyK3sOaOfrDnT8NxZNNihddg6A/lights/"+numLight+"/state",
                        url: "http://"+ipHueBridge+"/api/"+hueUsername+"/lights/"+numLight+"/state",
			data : JSON.stringify(jsonString),		
			success: function (response) {            
				console.log("Context response", response);
				getLightState();
			},
			error: function ()
			{
				alert("Error while sending data to context");
			}
		});
		
}

function sendValuesToHueRGB(numLight, _sat, _bri, _rVal, _gVal, _bVal) {
	
	var xy = toXY(parseFloat(_rVal), parseFloat(_gVal), parseFloat(_bVal));
	var x0 = parseFloat(xy[0]);
	var x1 = parseFloat(xy[1]);
	var jsonString = {
		"on" : true,
		"sat" : parseInt(_sat), 
		"bri" : parseInt(_bri),
		"xy": [ x0, x1]
	};  
	
	$.ajax({
			type: "PUT",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
			//url: "http://146.48.85.37/api/MvRgdGPhMJzPQtdyK3sOaOfrDnT8NxZNNihddg6A/lights/"+numLight+"/state",
                        url: "http://"+ipHueBridge+"/api/"+hueUsername+"/lights/"+numLight+"/state",
			data : JSON.stringify(jsonString),		
			success: function (response) {            
				console.log("Context response", response);
				getLightState();
			},
			error: function ()
			{
				alert("Error while sending data to context");
			}
		});
		
}


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
				console.log("Context response", JSON.stringify(response));
				getLightState();
			},
			error: function ()
			{
				alert("Error while sending data to context");
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
				if(response.lights["1"] !== undefined && response.lights["1"].state.reachable) {
					$("#light1-state").css("color", "green");
					if(response.lights["1"].state.on) {						
						$("#status1 > label.btn-on").addClass("active");
						$("#status1 > label.btn-off").removeClass("active");
					} else {						
						$("#status1 > label.btn-on").removeClass("active");
						$("#status1 > label.btn-off").addClass("active");
					}				
				} else {
					$("#light1-state").css("color", "red");
					$("#status1 > label.btn-on").removeClass("active");
					$("#status1 > label.btn-off").addClass("active");
				}
				if(response.lights["2"] !== undefined && response.lights["2"].state.reachable) {
					$("#light2-state").css("color", "green");
					if(response.lights["2"].state.on) {						
						$("#status2 > label.btn-on").addClass("active");
						$("#status2 > label.btn-off").removeClass("active");
					} else {						
						$("#status2 > label.btn-on").removeClass("active");
						$("#status2 > label.btn-off").addClass("active");
					}
				} else {
					$("#light2-state").css("color", "red");
					$("#status2 > label.btn-on").removeClass("active");
					$("#status2 > label.btn-off").addClass("active");
				}
				if(response.lights["3"] !== undefined && response.lights["3"].state.reachable) {
					$("#light3-state").css("color", "green");
					if(response.lights["3"].state.on) {						
						$("#status3 > label.btn-on").addClass("active");
						$("#status3 > label.btn-off").removeClass("active");
					} else {						
						$("#status3 > label.btn-on").removeClass("active");
						$("#status3 > label.btn-off").addClass("active");
					}				
				} else {
					$("#light3-state").css("color", "red");
					$("#status3 > label.btn-on").removeClass("active");
					$("#status3 > label.btn-off").addClass("active");
				}
			},
			error: function ()
			{
				alert("Error while sending data to hue bridge");
			}
		});
}
