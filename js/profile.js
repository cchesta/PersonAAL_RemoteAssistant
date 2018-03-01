/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;



var contextUrl = "https://giove.isti.cnr.it:8443/";
//var userName = "cchesta";
var userName = "john";
var appName  = "personAAL";

window.onload = init;

    function init() {

        //internationalization
        var userLang = getUserLanguage(); 
        console.log(userLang);

       
        //init snackbar
        snackbar= document.getElementById("snackbar-log");



        //velocity animation
        $('.mdl-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});

    };

    function writelog(message)
    {
        log.innerHTML = log.innerHTML + message +"<br/>";
    }
    

    function sendProfileToContextManager(name,surname,birth_date,gender,state,city,postal_code,address){
        var profileObj = {
            "name": name,
            "surname": surname,
            "birth_date": birth_date,
            "gender": gender,
            "state": state,
            "city": city,
            "postal_code": postal_code, 
            "address": address 
        };
        $.ajax({
			type: "POST",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
            url: contextUrl + "cm/rest/user/" + userName + "/profile/",			  dataType: 'json',
			data: JSON.stringify(profileObj),
			success: function (response) {     
				$("#response").html(JSON.stringify(response));
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
			}
	});
    }

    function getProfileFromContextManager(){
        $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ userName + "/profile/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response profile", response);
            $("#profileName").html(response.name);
            $("#profileSurname").html(response.surname);
            $("#profileBirthDate").html(response.birth_date);
            $("#profileGender").html(response.gender);
            $("#profileState").html(response.state);
            $("#profileCity").html(response.city);
            $("#profilePostalCode").html(response.postal_code);
            $("#profileAddress").html(response.address);
        },
        error: function ()
        {
            console.log("Error while getting body temperature data");
        }
    });
    }

    function sendInterestListToContextManager(interest_name, interest_category){
        var interestListObj = {
            "interest_name": interest_name,
            "interest_category": interest_category
        };
         $.ajax({
			type: "POST",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
            url: contextUrl + "cm/rest/user/" + userName + "/interest_list/",			  dataType: 'json',
			data: JSON.stringify(interestListObj),
			success: function (response) {     
				$("#response").html(JSON.stringify(response));
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
			}
	});
        
    }

    function getInterestListFromContextManager(){
     $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/"+ userName + "/interest_list/", 
        dataType: 'json',
        
        success: function (response) {            
            console.log("Context response profile", response);
            $("#interestName").html(response.interest_name);
            $("#interestCategory").html(response.interest_category);
        },
        error: function ()
        {
            console.log("Error while getting interest list data");
        }
    });
    }
    
    function requestData(n)
    {
        var response = {
            action: "read",
            nSamples: n.toString()
            };
        socket.send(JSON.stringify(response));
        
        
    }
    


    
    

