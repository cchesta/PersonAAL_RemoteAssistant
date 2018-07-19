/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var socket;



var contextUrl = "https://giove.isti.cnr.it:8443/";
//var token = "cchesta";
//var userName = "john";
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

}

function writelog(message) {
    log.innerHTML = log.innerHTML + message + "<br/>";
}

function showShoppingList(element, listID) {
    if (shoppingMenuReduced === false) {
        var animationSequence = [];
        animationSequence.push({
            e: $('.list-hidable'),
            p: 'transition.fadeOut',
            o: {
                duration: 200
            }
        });
        animationSequence.push({
            e: $('#shopping-list'),
            p: {
                width: '72px'
            },
            o: {
                sequenceQueue: false,
                delay: 200
            }
        });
        animationSequence.push({
            e: $('#hide-back-arrow'),
            p: 'transition.fadeIn',
            o: {
                sequenceQueue: false,
                display: 'flex'
            }
        });
        animationSequence.push({
            e: $('#' + listID),
            p: 'transition.fadeIn'
        });

        $.Velocity.RunSequence(animationSequence);

        shoppingMenuReduced = true;
        prevShoppingListID = listID;
    } else if (prevShoppingListID !== listID) {
        var animationSequence = [];
        animationSequence.push({
            e: $('#' + prevShoppingListID),
            p: 'transition.fadeOut'
        });
        animationSequence.push({
            e: $('#' + listID),
            p: 'transition.fadeIn'
        });

        $.Velocity.RunSequence(animationSequence);
        prevShoppingListID = listID;

        prevClickedElement.style.backgroundColor = "";
    }

    element.style.backgroundColor = "rgba(170,170,170, 0.35)";
    prevClickedElement = element;
}

function hideInterestList() {
    if (shoppingMenuReduced === true) {
        var animationSequence = [];
        animationSequence.push({
            e: $('#' + prevShoppingListID),
            p: 'transition.fadeOut'
        });
        animationSequence.push({
            e: $('#hide-back-arrow'),
            p: 'transition.fadeOut',
            o: {
                sequenceQueue: false
            }
        });
        animationSequence.push({
            e: $('#shopping-list'),
            p: {
                width: '100%'
            },
            o: {
                sequenceQueue: false,
                delay: 200
            }
        });
        animationSequence.push({
            e: $('.list-hidable'),
            p: 'transition.fadeIn',
            o: {
                duration: 200
            }
        });

        $.Velocity.RunSequence(animationSequence);

        prevClickedElement.style.backgroundColor = "";

        shoppingMenuReduced = false;
    }
}

function editProfile() {
    if (document.getElementById("profileName").disabled) {
        document.getElementById("edit_button").innerHTML = saveButtonLabel;
        document.getElementById("cancel_changes").style.display = "block";
        document.getElementById("profileName").removeAttribute("disabled");
        document.getElementById("profileSurname").removeAttribute("disabled");
        document.getElementById("profileBirthDate").removeAttribute("disabled");
        document.getElementById("profileGender").removeAttribute("disabled");
        document.getElementById("profileState").removeAttribute("disabled");
        document.getElementById("profileCity").removeAttribute("disabled");
        document.getElementById("profilePostalCode").removeAttribute("disabled");
        document.getElementById("profileAddress").removeAttribute("disabled");
    } else {
        birth_date = document.getElementById("profileBirthDate").value + "T00:00:00+01:00";
        sendProfileToContextManager(document.getElementById("profileName").value,
            document.getElementById("profileSurname").value,
            birth_date,
            document.getElementById("profileGender").value,
            document.getElementById("profileState").value,
            document.getElementById("profileCity").value,
            document.getElementById("profilePostalCode").value,
            document.getElementById("profileAddress").value);
        document.getElementById("edit_button").innerHTML = editButtonLabel;
        document.getElementById("cancel_changes").style.display = "none";
        document.getElementById("profileName").setAttribute("disabled", true);
        document.getElementById("profileSurname").setAttribute("disabled", true);
        document.getElementById("profileBirthDate").setAttribute("disabled", true);
        document.getElementById("profileGender").setAttribute("disabled", true);
        document.getElementById("profileState").setAttribute("disabled", true);
        document.getElementById("profileCity").setAttribute("disabled", true);
        document.getElementById("profilePostalCode").setAttribute("disabled", true);
        document.getElementById("profileAddress").setAttribute("disabled", true);
    }
}

function discardChanges() {
    getProfileFromContextManager();
    document.getElementById("edit_button").innerHTML = editButtonLabel;
    document.getElementById("cancel_changes").style.display = "none";
    document.getElementById("profileName").setAttribute("disabled", true);
    document.getElementById("profileSurname").setAttribute("disabled", true);
    document.getElementById("profileBirthDate").setAttribute("disabled", true);
    document.getElementById("profileGender").setAttribute("disabled", true);
    document.getElementById("profileState").setAttribute("disabled", true);
    document.getElementById("profileCity").setAttribute("disabled", true);
    document.getElementById("profilePostalCode").setAttribute("disabled", true);
    document.getElementById("profileAddress").setAttribute("disabled", true);
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
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/profile/"),
        dataType: 'json',
        data: JSON.stringify(profileObj),
        success: function (response) {
            console.log("enviado:", JSON.stringify(profileObj));
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
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/profile/"),
        dataType: 'json',

        success: function (response) {
            console.log("Context response profile", response);
            document.querySelector('#profileName').parentNode.MaterialTextfield.change(response.name);
            document.querySelector('#profileSurname').parentNode.MaterialTextfield.change(response.surname);
            document.querySelector('#profileBirthDate').parentNode.MaterialTextfield.change(response.birth_date.substring(0, 10));
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
            console.log("Error getting profile from context manager");
        }
    });
}

function formatAndSendInterestsList() {
    var interestsListFormated = {
        interest_list: []
    };

    for (var i in interestsList) {
        var interest = interestsList[i];
        interestsListFormated.interest_list.push({
            "interest_category": interest.interest_category,
            "interest_name": interest.interest_name
        });
    }

    sendInterestListToContextManager(interestsListFormated);
}

function sendInterestListToContextManager(interestListObj) {
    $.ajax({
        type: "POST",
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        },
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/interest_list/"),
        dataType: 'json',
        data: JSON.stringify(interestListObj),
        success: function (response) {
            console.log("Interests successfully sent", response);
            //$("#response").html(JSON.stringify(response));
        },
        error: function (err) {
            console.log("Error sending interests", err);
            //$("#response").html(JSON.stringify(err));
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
        url: encodeURI ( contextUrl + "cm/rest/user/" + userId + "/interest_list/"),
        dataType: 'json',

        success: function (response) {
            console.log("Context response interests", response);
            interestsList = Object.values(response);
            if (interestsList[0].length > 0) {
                interestsList = interestsList[0];
                updateAddInterestListItems();
                updateInterestList();
            } else {
                //interestsList = [];
                updateAddInterestListItems();
                updateInterestList();
            }
            console.log(interestsList);
            getProfileFromContextManager();
        },
        error: function () {
            console.log("Error while getting interest list data");
            interestsList = [];
        }
    });
}

function checkForItemInInterestsList(item) {
    for (var i = 0; i < interestsList.length; i++) {
        if (interestsList[i].interest_name == item)
            return true;
    }
    return false;
}

function enterListItem(name) {
    var interestList = document.getElementById("interestList");
    //create list item
    var listItem = document.createElement("div");
    listItem.className = "mdl-list__item";
    listItem.setAttribute("data-interest", name);

    var listItemPrimaryContent = document.createElement("span");
    listItemPrimaryContent.className = "mdl-list__item-primary-content";

    var interestNameSpan = document.createElement("span");
    interestNameSpan.textContent = name;

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

function updateInterestList() {
    for (var i=0; i < interestsList.length; i++) {
        if (interestsList[i].interest_name != "")
            enterListItem(interestsList[i].interest_name);
        else {
            interestsList.splice(i, 1);
            i--;
        }
    }
}

function updateAddInterestListItems() {
    var sportItems = document.getElementById("sports-list").children;
    for (var i = 0; i < sportItems.length; i++) {
        if (checkForItemInInterestsList(sportItems[i].getAttribute("data-interest"))) {
            sportItems[i].children[1].children[0].innerHTML = "done";
        } else {
            sportItems[i].children[1].children[0].innerHTML = "add_circle_outline";
        }
    }

    var programItems = document.getElementById("programs-list").children;
    for (var i = 0; i < programItems.length; i++) {
        if (checkForItemInInterestsList(programItems[i].getAttribute("data-interest"))) {
            programItems[i].children[1].children[0].innerHTML = "done";
        } else {
            programItems[i].children[1].children[0].innerHTML = "add_circle_outline";
        }
    }

    var otherItems = document.getElementById("others-list").children;
    for (var i = 0; i < otherItems.length; i++) {
        if (checkForItemInInterestsList(otherItems[i].getAttribute("data-interest"))) {
            otherItems[i].children[1].children[0].innerHTML = "done";
        } else {
            otherItems[i].children[1].children[0].innerHTML = "add_circle_outline";
        }
    }
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

    enterListItem(interestName);

    //add item to interestsList and send to context manager
    var newInterest = {
        interest_category: '',
        interest_name: ''
    };
    var interestCategory = item.parentNode.parentNode.getAttribute("id");
    interestCategory = interestCategory.substring(0, interestCategory.indexOf('-'));
    newInterest.interest_category = interestCategory;
    newInterest.interest_name = interestName;

    interestsList.push(newInterest);
    console.log(interestsList);
    formatAndSendInterestsList();
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

    // remove item from interestsList and send to context manager
    for (var i = 0; i < interestsList.length; i++) {
        if (interestsList[i].interest_name == interestName) {
            interestsList.splice(i, 1);
            break;
        }
    }
    console.log(interestsList);
    formatAndSendInterestsList();
}

function requestData(n) {
    var response = {
        action: "read",
        nSamples: n.toString()
    };
    socket.send(JSON.stringify(response));


}