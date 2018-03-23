/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var contextUrl = "https://giove.isti.cnr.it:8443/";
//var token = "john";
var appName  = "personAAL";

var socket;



window.onload = init;

function init() {

    //internationalization
    var userLang = getUserLanguage();
    //console.log(userLang);


}


function sendContactsToContextManager(contactsObj) {
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
        url: contextUrl + "cm/rest/user/" + token + "/contact_list/",
        dataType: 'json',
        data: JSON.stringify(contactsObj),
        success: function (response) {
            console.log("Contacts sent successfully.", response)
            //$("#response").html(JSON.stringify(response));
        },
        error: function (err) {
            //$("#response").html(JSON.stringify(err));
            console.log("Error sending contacts: ", err);
        }
    });
}

function getContactsFromContextManager(callback) {
    
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'Access-Control-Allow-Origin': '*'
        },
        url: contextUrl + "cm/rest/user/" + token + "/contact_list/",
        dataType: 'json',
        success: function (response) {            
            contactList = Object.values(response);
            console.log("Contact List", contactList);
            if (contactList[0].length > 0) {
                contactList = contactList[0];
            }
            callback();
        },
        error: function ()
        {
            console.log("Error getting contact list");
            contactList = [];
        }
    });
}


function writelog(message) {
    log.innerHTML = log.innerHTML + message + "<br/>";
}


function requestData(n) {
    var response = {
        action: "read",
        nSamples: n.toString()
    };
    socket.send(JSON.stringify(response));


}



function onError(event) {
    //    stopPressed();
    //    window.alert("error");
    console.log('error during websocket connection');
    var data = {
        message: errorMsg
    };
    snackbar.MaterialSnackbar.showSnackbar(data);
}

// Detect when the page is unloaded or close
window.onbeforeunload = function () {
    // Request ServerBIT to close the connection to BITalino
    var response = {
        action: "stop"
    };
    socket.send(JSON.stringify(response));

    socket.onclose = function () {};
    socket.close();

    stopPressed();

};