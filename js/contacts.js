/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;



window.onload = init;

    function init() {

        //internationalization
        var userLang = getUserLanguage(); 
        console.log(userLang);
        
  
    }


    function sendContactsToContextManager(name,surname,phone_number,email,relationship_type){
        var contactsObj = {
            "name": name,
            "surname": surname,
            "phone_number": phone_number,
            "email": email,
            "relationship_type": relationship_type
        };
        $.ajax({
			type: "POST",
			headers: {
				'Accept': 'application/json',
				'Content-Type': 'application/json'
			},
            url: contextUrl + "cm/rest/user/" + userName + "/contact_list/",			  dataType: 'json',
			data: JSON.stringify(contactsObj),
			success: function (response) {     
				$("#response").html(JSON.stringify(response));
			},
			error : function(err) {
				$("#response").html(JSON.stringify(err));
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
    


function onError(event){
//    stopPressed();
//    window.alert("error");
    console.log('error during websocket connection');
    var data = {message: errorMsg};
    snackbar.MaterialSnackbar.showSnackbar(data);
}

// Detect when the page is unloaded or close
window.onbeforeunload = function() {
    // Request ServerBIT to close the connection to BITalino
    var response = {
        action: "stop"
        };
        socket.send(JSON.stringify(response));

    socket.onclose = function () {};
    socket.close();
    
    stopPressed();
    
};








    
    

