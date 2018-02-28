/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var contextUrl = "https://giove.isti.cnr.it:8443/";
var userName = "john";
var appName  = "personAAL";

var socket;



window.onload = init;

function init() {

    //internationalization
    var userLang = getUserLanguage();
    console.log(userLang);


}


function sendContactsToContextManager(contactsObj) {
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/contact_list/",
        dataType: 'json',
        data: JSON.stringify(contactsObj),
        success: function (response) {
            $("#response").html(JSON.stringify(response));
        },
        error: function (err) {
            $("#response").html(JSON.stringify(err));
        }
    });
}

function getContactsFromContextManager() {
    
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/contact_list/",
        dataType: 'json',
        success: function (response) {            
            console.log("Context response Contact List", response);
            contactList = Object.values(response);
            console.log("list size is " + contactList.length);
            if (contactList.length > 0) {
                var contactsHTML = '';
                for (var i=0, len = contactList.length; i < len; i++) {
                    contactsHTML += '<tr><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored remove-button" onclick="deleteContactConfirm(this)"><i class="material-icons red">remove_circle</i></button><span class="contactName">';
                    contactsHTML += contactList[i].name;
                    contactsHTML += ' ';
                    contactsHTML += contactList[i].surname;
                    contactsHTML += '</span></td><td><span>';
                    contactsHTML += contactList[i].email;
                    contactsHTML += '</span></td><td><button class="mdl-button mdl-js-button mdl-button--icon mdl-button--colored"><i class="material-icons">message</i></button></td></tr>';
                }
                document.getElementById("contact_table").innerHTML = contactsHTML;
                console.log(contactsHTML);
            }
        },
        error: function ()
        {
            console.log("Error while getting contact list");
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