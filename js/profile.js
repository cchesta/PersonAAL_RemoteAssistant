/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;



var contextUrl = "https://giove.isti.cnr.it:8443/";
//var userName = "cchesta";
var userName = "john";
var appName = "personAAL";

window.onload = init;

function init() {

    //internationalization
    var userLang = getUserLanguage();
    console.log(userLang);

    switch (userLang) {
        case 'en':
            GenderMale = 'Male'
            GenderFemale = 'Female'
            break;

        case 'de':
            GenderMale = 'Mann'
            GenderFemale = 'Frau'
            break;

        case 'no':
            GenderMale = 'Mann'
            GenderFemale = 'Hunn'
            break;

        default:
            GenderMale = 'Male'
            GenderFemale = 'Female'
            break;
    }

    getProfileFromContextManager();
}

function writelog(message) {
    log.innerHTML = log.innerHTML + message + "<br/>";
}


function sendProfileToContextManager(name, surname, birth_date, gender, state, city, postal_code, address) {
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
        url: contextUrl + "cm/rest/user/" + userName + "/profile/",
        dataType: 'json',
        data: JSON.stringify(profileObj),
        success: function (response) {
            $("#response").html(JSON.stringify(response));
        },
        error: function (err) {
            $("#response").html(JSON.stringify(err));
        }
    });
}

function getProfileFromContextManager() {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/profile/",
        dataType: 'json',

        success: function (response) {
            console.log("Context response profile", response);
            document.querySelector('#profileName').parentNode.MaterialTextfield.change(response.name);
            document.querySelector('#profileSurname').parentNode.MaterialTextfield.change(response.surname);
            document.querySelector('#profileBirthDate').parentNode.MaterialTextfield.change(response.birth_date);
            if (response.gender == 'Male')
                document.querySelector('#profileGender').parentNode.MaterialTextfield.change(GenderMale);
            else
                document.querySelector('#profileGender').parentNode.MaterialTextfield.change(GenderFemale);
            document.querySelector('#profileState').parentNode.MaterialTextfield.change(response.state);
            document.querySelector('#profileCity').parentNode.MaterialTextfield.change(response.city);
            document.querySelector('#profilePostalCode').parentNode.MaterialTextfield.change(response.postal_code);
            document.querySelector('#profileAddress').parentNode.MaterialTextfield.change(response.address);
        },
        error: function () {
            console.log("Error while getting body temperature data");
        }
    });
}

function sendInterestListToContextManager(interest_name, interest_category) {
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
        url: contextUrl + "cm/rest/user/" + userName + "/interest_list/",
        dataType: 'json',
        data: JSON.stringify(interestListObj),
        success: function (response) {
            $("#response").html(JSON.stringify(response));
        },
        error: function (err) {
            $("#response").html(JSON.stringify(err));
        }
    });

}

function getInterestListFromContextManager() {
    $.ajax({
        type: "GET",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: contextUrl + "cm/rest/user/" + userName + "/interest_list/",
        dataType: 'json',

        success: function (response) {
            console.log("Context response profile", response);
            $("#interestName").html(response.interest_name);
            $("#interestCategory").html(response.interest_category);
        },
        error: function () {
            console.log("Error while getting interest list data");
        }
    });
}

function changeInterestList(item) {

    //get all menu items
    menuItems = item.parentElement.children;

    //set all other items "unselected"
    for (var i = 0; i < menuItems.length; i++)
        menuItems[i].className = menuItems[i].className.replace(" selected", "");

    item.className += " selected";

    dialogInterestLists = document.getElementById("interest-list-container").children;

    //set all lists hidden
    for (var i = 0; i < dialogInterestLists.length; i++)
        dialogInterestLists[i].className += " hidden";

    selectedList = document.getElementById(item.getAttribute("data-list-id"));

    //make selected list visible
    selectedList.className = selectedList.className.replace(/ hidden/g, "");

}

function addInterest(item) {
    var interestList = document.getElementById("interestList");
    var interests = interestList.children;
    var interestName = item.parentNode.getAttribute("data-interest");

    for (var i = 0; i < interests.length; i++) {
        //interest already in the list
        if (interestName === interests[i].getAttribute("data-interest"))
            return;
    }

    //change icon to "done"
    item.children[0].textContent = "done";

    //create list item
    var listItem = document.createElement("div");
    listItem.className = "mdl-list__item";
    listItem.setAttribute("data-interest", interestName);

    var listItemPrimaryContent = document.createElement("span");
    listItemPrimaryContent.className = "mdl-list__item-primary-content";

    var interestNameSpan = document.createElement("span");
    interestNameSpan.textContent = interestName;

    var aItemList = document.createElement("a");
    aItemList.className = "mdl-list__item-secondary-action";
    aItemList.setAttribute("onclick", "removeInterest(this)");

    var itemListDeleteIcon = document.createElement("i");
    itemListDeleteIcon.className = "material-icons";
    itemListDeleteIcon.textContent = "cancel";

    //add all intern items to listItem
    aItemList.appendChild(itemListDeleteIcon);
    listItemPrimaryContent.appendChild(interestNameSpan);
    listItem.appendChild(listItemPrimaryContent);
    listItem.appendChild(aItemList);

    //add item to list
    interestList.appendChild(listItem);
}

function removeInterest(item) {
    interestList = document.getElementById("interestList");
    var interestName = item.parentNode.getAttribute("data-interest");

    //remove from interests list
    interestList.removeChild(item.parentNode);

    //change button on "add interest dialog"
    //TODO should be optimized
    var sportList = document.getElementById("sports-list").children;
    var programList = document.getElementById("programs-list").children;
    var otherList = document.getElementById("others-list").children;

    for (var i = 0; i < sportList.length; i++) {
        if (interestName === sportList[i].getAttribute("data-interest"))
            sportList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent = "add_circle_outline";
    }

    for (var i = 0; i < programList.length; i++) {
        if (interestName === programList[i].getAttribute("data-interest"))
            programList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent = "add_circle_outline";
    }

    for (var i = 0; i < otherList.length; i++) {
        if (interestName === otherList[i].getAttribute("data-interest"))
            otherList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent = "add_circle_outline";
    }
}

function requestData(n) {
    var response = {
        action: "read",
        nSamples: n.toString()
    };
    socket.send(JSON.stringify(response));


}