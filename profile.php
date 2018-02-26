<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<?php

include 'miscLib.php';
include 'DButils.php';

//REDIRECT SU HTTPS
//if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//    HTTPtoHTTPS();

if(!isCookieEnabled())
{
    //TODO handle disabled cookie error
    //myRedirect("error.php?err=DISABLED_COOKIE", TRUE);
}


//SESSIONE
session_start();
setLanguage();

//verifico se è stato effettuato il login
if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
{
    $t=time();
    $diff=0;
    $new=FALSE;
    
    //VERIFICO SE LA SESSIONE E' SCADUTA
    if (isset($_SESSION['personAAL_time']))
    {
	$t0=$_SESSION['personAAL_time'];
	$diff=($t-$t0); // inactivity period
    }
    else
	$new=TRUE;
        
    if ($new || ($diff > SESSION_TIMEOUT))
    { 
	//DISTRUGGO LA SESSIONE
	mySessionDestroy();
	myRedirect("login.php?notify=".SESSION_EXPIRED, TRUE);
    }
    else
	$_SESSION['personAAL_time']=time();  //update time 
    
}
else
    myRedirect("login.php", TRUE);

?>

<html>
    <head>
        <title>PersonAAL</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        
        
        <!--  UI CSS & JS-->
        
        <link rel="stylesheet" href="css/material.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        
        
        <link rel="stylesheet" href="css/custom.css">
        
        
        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>
        
<!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
        
        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>

        <!-- ADAPTATION SCRIPTS -->
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>

        <script>
	    var shoppingMenuReduced;
            var prevShoppingListID;
            var prevClickedElement;
	    
            $(document).ready(function() {
		shoppingMenuReduced= false;
                
                console.log($(window).width());
                
                //delete all phone elements if in desktop/tablet mode
                if($(window).width() <= 479)
                {
                    var toRemoveElements= document.querySelectorAll('._delete-phone_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                else
                {
                    var toRemoveElements= document.querySelectorAll('._delete-desktop_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                
                    
                //VELOCITY ANIMATIONS
                $('.patient-info-card, .interest-list-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                
            });
            
            function changeInterestList(item){
                
                //get all menu items
                menuItems= item.parentElement.children;
                
                //set all other items "unselected"
                for(var i=0; i < menuItems.length; i++)
                    menuItems[i].className= menuItems[i].className.replace(" selected", "");
                
                item.className+= " selected";
                
                dialogInterestLists= document.getElementById("interest-list-container").children;
                
                //set all lists hidden
                for(var i=0; i < dialogInterestLists.length; i++)
                    dialogInterestLists[i].className+= " hidden";
                
                selectedList= document.getElementById(item.getAttribute("data-list-id"));
                
                //make selected list visible
                selectedList.className= selectedList.className.replace(/ hidden/g, "");
                
            }
            
            function addInterest(item){
                var interestList= document.getElementById("interestList");
                var interests= interestList.children;
                var interestName= item.parentNode.getAttribute("data-interest");
                
                for(var i=0; i < interests.length; i++)
                {
                    //interest already in the list
                    if(interestName === interests[i].getAttribute("data-interest"))
                        return;
                        
                }
                
                //change icon to "done"
                item.children[0].textContent= "done";
                
                //create list item
                var listItem= document.createElement("div");
                listItem.className= "mdl-list__item";
                listItem.setAttribute("data-interest", interestName);
                
                var listItemPrimaryContent= document.createElement("span");
                listItemPrimaryContent.className= "mdl-list__item-primary-content";
                
                var interestNameSpan= document.createElement("span");
                interestNameSpan.textContent= interestName;
                
                var aItemList= document.createElement("a");
                aItemList.className= "mdl-list__item-secondary-action";
                aItemList.setAttribute("onclick", "removeInterest(this)");
                
                var itemListDeleteIcon= document.createElement("i");
                itemListDeleteIcon.className= "material-icons";
                itemListDeleteIcon.textContent= "cancel";
                
                //add all intern items to listItem
                aItemList.appendChild(itemListDeleteIcon);
                listItemPrimaryContent.appendChild(interestNameSpan);
                listItem.appendChild(listItemPrimaryContent);
                listItem.appendChild(aItemList);
                
                //add item to list
                interestList.appendChild(listItem);
            }
            
            function removeInterest(item){
                interestList= document.getElementById("interestList");
                var interestName= item.parentNode.getAttribute("data-interest");
                
                //remove from interests list
                interestList.removeChild(item.parentNode);
                
                //change button on "add interest dialog"
                //TODO should be optimized
                var sportList= document.getElementById("sports-list").children;
                var programList= document.getElementById("programs-list").children;
                var otherList= document.getElementById("others-list").children;
                
                for(var i= 0; i < sportList.length; i++)
                {
                    if(interestName === sportList[i].getAttribute("data-interest"))
                        sportList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent= "add_circle_outline";
                }
                
                for(var i= 0; i < programList.length; i++)
                {
                    if(interestName === programList[i].getAttribute("data-interest"))
                        programList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent= "add_circle_outline";
                }
                
                for(var i= 0; i < otherList.length; i++)
                {
                    if(interestName === otherList[i].getAttribute("data-interest"))
                        otherList[i].getElementsByClassName("mdl-list__item-secondary-action")[0].getElementsByTagName("i")[0].textContent= "add_circle_outline";
                }
            }
            
            function disableAddButton(){
                document.getElementById("add-interest").style.display= "none";
            }
            
            function enableAddButton(){
                document.getElementById("add-interest").style.display= "block";
            }
	    
	                function showShoppingList(element, listID)
            {
                if(shoppingMenuReduced === false)
                {
                    var animationSequence= [];
                    animationSequence.push({ e: $('.list-hidable'), p: 'transition.fadeOut', o: {duration: 200}});
                    animationSequence.push({ e: $('#shopping-list'), p: { width: '72px'}, o: {sequenceQueue: false, delay: 200}});
		    animationSequence.push({ e: $('#hide-back-arrow'), p: 'transition.fadeIn', o: {sequenceQueue: false, display: 'flex'}});
                    animationSequence.push({ e: $('#'+listID), p: 'transition.fadeIn'});

                    $.Velocity.RunSequence(animationSequence);

                    shoppingMenuReduced= true;
                    prevShoppingListID= listID;
                }
                else if(prevShoppingListID !== listID)
                {
                    var animationSequence= [];
                    animationSequence.push({ e: $('#'+prevShoppingListID), p: 'transition.fadeOut'});
                    animationSequence.push({ e: $('#'+listID), p: 'transition.fadeIn'});
                    
                    $.Velocity.RunSequence(animationSequence);
                    prevShoppingListID= listID;
                    
                    prevClickedElement.style.backgroundColor= "";
                }
                
                element.style.backgroundColor= "rgba(170,170,170, 0.35)";
                prevClickedElement= element;
            }
            
	    function hideInterestList()
            {
                if(shoppingMenuReduced === true)
                {
                    var animationSequence= [];
		    animationSequence.push({ e: $('#'+prevShoppingListID), p: 'transition.fadeOut'});
		    animationSequence.push({ e: $('#hide-back-arrow'), p: 'transition.fadeOut', o: {sequenceQueue: false}});
		    animationSequence.push({ e: $('#shopping-list'), p: { width: '100%'}, o: {sequenceQueue: false, delay: 200}});
                    animationSequence.push({ e: $('.list-hidable'), p: 'transition.fadeIn', o: {duration: 200}});
                    
		    $.Velocity.RunSequence(animationSequence);

		    prevClickedElement.style.backgroundColor= "";

                    shoppingMenuReduced= false;
                }
            }
	    
        </script>
        
    </head>
    <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_PROFILE);?></span>
                    <div class="mdl-layout-spacer"></div>
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="window.location='index.php';">
                        <i class="material-icons">home</i>
                    </button>
                </div>
		
		<div class="mdl-layout__tab-bar mdl-js-ripple-effect">
		    <a href="#patient-info" class="mdl-layout__tab is-active" onclick="disableAddButton()"><?php echo(PROFILE_PROFILECARD_TITLE);?></a>
		    <a href="#patient-interest" class="mdl-layout__tab" onclick="enableAddButton()"><?php echo(PROFILE_INTERESTS_TITLE);?></a>
		</div>
		
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
		    <a class="mdl-navigation__link" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
<!--                    <a class="mdl-navigation__link" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
                    <a class="mdl-navigation__link" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
                    <a class="mdl-navigation__link" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>-->
		    <a class="mdl-navigation__link mdl-navigation__link-selected" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
		
                
                <!-- PHONE LAYOUT -->
		<section class="mdl-layout__tab-panel is-active" id="patient-info">
		    <div class="page-content">
			
                        
                        <div class="mdl-grid">
                            
                            <?php
                            
                                //retreive user information
                                $userInfo= new UserData($_SESSION['personAAL_user']);
                            
                            ?>
                            <div class="patient-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch">
                                <div class="mdl-card__title hide-phone">
                                    <h6 class="mdl-card__title-text">
                                        <?php echo(PROFILE_PROFILECARD_TITLE);?>
                                    </h6>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">
                                    
                                    <div class="info-container">
                                        <div class="icon-textfield">
                                            <i class="material-icons">person</i>
                                        </div>
                                        <ul class="mdl-list">
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_NAME);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->name);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_SURNAME);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->surname);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                        </ul>
                                        <ul class="mdl-list right">
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_BIRTHDATE);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->birthDate);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_GENDER);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                        
                                                        if($userInfo->gender == "male")
                                                            echo(PROFILE_PROFILECARD_GENDER_MALE);
                                                        else if($userInfo->gender == "female")
                                                            echo(PROFILE_PROFILECARD_GENDER_FEMALE);
                                                            
                                                        //echo($userInfo->gender);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="info-container spacer">
                                        <div class="icon-textfield">
                                            <i class="material-icons">home</i>
                                        </div>
                                        <ul class="mdl-list">
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_STATE);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->state);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_CITY);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->city);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                        </ul>
                                        <ul class="mdl-list right">
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_POSTALCODE);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->cap);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item mdl-list__item--two-line">
                                                <span class="mdl-list__item-primary-content">
                                                    <span>
                                                        <?php echo(PROFILE_PROFILECARD_ADDRESS);?>
                                                    </span>
                                                    <span class="mdl-list__item-sub-title">
                                                        <?php
                                                            echo($userInfo->address);
                                                        ?>
                                                    </span>
                                                </span>
                                            </li>
                                        </ul>
                                    </div>

                                </div>

                            </div>
                            
<!--                            <div class="patient-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch">
                                <div class="mdl-card__title hide-phone">
                                    <h6 class="mdl-card__title-text">My Info</h6>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">
                                    
                                    <div class="info-container">
                                        <div class="icon-textfield">
                                            <i class="material-icons">person</i>
                                        </div>
                                        <div class="textfields-container">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-textfield">
                                                <input class="mdl-textfield__input" type="text" id="name">
                                                <label class="mdl-textfield__label" for="name">Name</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-tab-textfield">
                                                <input class="mdl-textfield__input" type="text" id="surname">
                                                <label class="mdl-textfield__label" for="surname">Surname</label>
                                            </div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-tab-textfield">
                                                <input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="age">
                                                <label class="mdl-textfield__label" for="age">Age</label>
                                                <span class="mdl-textfield__error">Input is not a number!</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-choice-group">
                                        <div class="card-group-label">Gender</div>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect first-radio-two" for="gender-option-1">
                                            <input type="radio" id="gender-option-1" class="mdl-radio__button" name="gender-options" value="1" checked>
                                            <span class="mdl-radio__label">male</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="gender-option-2">
                                            <input type="radio" id="gender-option-2" class="mdl-radio__button" name="gender-options" value="2">
                                            <span class="mdl-radio__label">female</span>
                                        </label>
                                    </div>

                                    <div class="card-choice-group">
                                        <div class="card-group-label">Education</div>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect first-radio-three" for="education-option-1">
                                            <input type="radio" id="education-option-1" class="mdl-radio__button" name="education-options" value="1" checked>
                                            <span class="mdl-radio__label" >primary</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="education-option-2">
                                            <input type="radio" id="education-option-2" class="mdl-radio__button" name="education-options" value="2">
                                            <span class="mdl-radio__label">secondary</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="education-option-3">
                                            <input type="radio" id="education-option-3" class="mdl-radio__button" name="education-options" value="3">
                                            <span class="mdl-radio__label">degree</span>
                                        </label>
                                    </div>

                                    <div class="info-container">
                                        <div class="icon-textfield">
                                            <i class="material-icons">home</i>
                                        </div>
                                        <div class="textfields-container">
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-textfield">
                                                <input class="mdl-textfield__input" type="text" id="address">
                                                <label class="mdl-textfield__label" for="address">Address</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="mdl-layout-spacer"></div>
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect right-button">
                                    Ok
                                    </a>

                                </div>

                            </div>-->

                            <div class="interest-list-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch hide-phone _delete-phone_">
                                <div class="mdl-card__title">
                                    <h6 class="mdl-card__title-text">
                                        <?php echo(PROFILE_INTERESTS_TITLE);?>
                                    </h6>
                                    <div class="mdl-layout-spacer"></div>
                                    <button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" data-toggle="modal" data-target="#add-interest-modal">
                                        <i class="material-icons">add</i>
                                    </button>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">
                                    <div id="interestList" class="mdl-list">
                                        <div data-interest="Baseball" class="mdl-list__item">
                                            <span class="mdl-list__item-primary-content">
                                                <span>
                                                    <?php echo(PROFILE_ADDINTERESTS_SPORTS_1);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
                                        </div>
                                        <div data-interest="Swim" class="mdl-list__item">
                                            <span class="mdl-list__item-primary-content">
                                                <span>
                                                    <?php echo(PROFILE_ADDINTERESTS_SPORTS_4);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
                                        </div>
                                        <div data-interest="Documentary" class="mdl-list__item">
                                            <span class="mdl-list__item-primary-content">
                                                <span>
                                                    <?php echo(PROFILE_ADDINTERESTS_PROGRAMS_1);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
                                        </div>
                                        <div data-interest="Cooking" class="mdl-list__item">
                                            <span class="mdl-list__item-primary-content">
                                                <span>
                                                    <?php echo(PROFILE_ADDINTERESTS_OTHERS_2);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
			
		    </div>
		</section>
		
		<section class="mdl-layout__tab-panel hide-desktop_tablet" id="patient-interest">
		    <div class="page-content mdl-grid">
			
			<div class="interest-list-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col-phone no-stretch">
			    <div class="mdl-card__title hide-phone">
				<h6 class="mdl-card__title-text">
                                    <?php echo(PROFILE_INTERESTS_TITLE);?>
                                </h6>
				<div class="mdl-layout-spacer"></div>
				<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" data-toggle="modal" data-target="#add-interest-modal">
				    <i class="material-icons">add</i>
				</button>
			    </div>
			    <div class="mdl-card__supporting-text mdl-card--expand">
				<div id="interestList" class="mdl-list">
				    <div data-interest="Baseball" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>
                                                <?php echo(PROFILE_ADDINTERESTS_SPORTS_1);?>
                                            </span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Swim" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>
                                                <?php echo(PROFILE_ADDINTERESTS_SPORTS_4);?>
                                            </span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Documentary" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>
                                                <?php echo(PROFILE_ADDINTERESTS_PROGRAMS_1);?>
                                            </span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Cooking" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>
                                                <?php echo(PROFILE_ADDINTERESTS_OTHERS_2);?>
                                            </span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				</div>
			    </div>
			</div>
			
                        <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone floating-button-fix-cell"></div>
                        
		    </div>
		</section>
		
                 
                 
                 
                
                <!-- DESKTOP LAYOUT -->
<!--                <div class="page-content hide-phone">
                 Your content goes here 
		    <div class="mdl-grid">

			<div class="patient-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col no-stretch">
			    <div class="mdl-card__title">
				<h6 class="mdl-card__title-text">Patient Info</h6>
			    </div>
			    <div class="mdl-card__supporting-text mdl-card--expand">
				<form action="#">
				    <i class="material-icons">person</i>
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-textfield">
					<input class="mdl-textfield__input" type="text" id="name">
					<label class="mdl-textfield__label" for="sample3">Name</label>
				    </div>
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-tab-textfield">
					<input class="mdl-textfield__input" type="text" id="surname">
					<label class="mdl-textfield__label" for="sample3">Surname</label>
				    </div>
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-tab-textfield">
					<input class="mdl-textfield__input" type="text" pattern="-?[0-9]*(\.[0-9]+)?" id="age">
					<label class="mdl-textfield__label" for="sample4">Age</label>
					<span class="mdl-textfield__error">Input is not a number!</span>
				    </div>

                                    <div class="card-choice-group">
                                        <div class="card-group-label">Gender</div>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect first-radio-two" for="gender-option-1">
                                            <input type="radio" id="gender-option-1" class="mdl-radio__button" name="gender-options" value="1" checked>
                                            <span class="mdl-radio__label">male</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="gender-option-2">
                                            <input type="radio" id="gender-option-2" class="mdl-radio__button" name="gender-options" value="2">
                                            <span class="mdl-radio__label">female</span>
                                        </label>
                                    </div>
                                    <div class="card-choice-group">
                                        <div class="card-group-label">Education</div>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect first-radio-three" for="education-option-1">
                                            <input type="radio" id="education-option-1" class="mdl-radio__button" name="education-options" value="1" checked>
                                            <span class="mdl-radio__label three" >primary</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="education-option-2">
                                            <input type="radio" id="education-option-2" class="mdl-radio__button" name="education-options" value="2">
                                            <span class="mdl-radio__label three">secondary</span>
                                        </label>
                                        <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="education-option-3">
                                            <input type="radio" id="education-option-3" class="mdl-radio__button" name="education-options" value="3">
                                            <span class="mdl-radio__label three">degree</span>
                                        </label>
                                    </div>

				    <i class="material-icons">home</i>
				    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label icon-textfield">
					<input class="mdl-textfield__input" type="text" id="address">
					<label class="mdl-textfield__label" for="sample3">Address</label>
				    </div>
				</form>

			    </div>
			    <div class="mdl-card__actions mdl-card--border">
				<div class="mdl-layout-spacer"></div>
				<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect right-button">
				Ok
				</a>

			    </div>

			</div>

			<div class="interest-list-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col no-stretch">
			    <div class="mdl-card__title">
				<h6 class="mdl-card__title-text">Interests</h6>
				<div class="mdl-layout-spacer"></div>
				<button class="mdl-button mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored" data-toggle="modal" data-target="#add-interest-modal">
				    <i class="material-icons">add</i>
				</button>
			    </div>
			    <div class="mdl-card__supporting-text mdl-card--expand">
				<div id="interestList" class="mdl-list">
				    <div data-interest="Baseball" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>Baseball</span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Swim" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>Swim</span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Documentary" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>Documentary</span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				    <div data-interest="Cooking" class="mdl-list__item">
					<span class="mdl-list__item-primary-content">
					    <span>Cooking</span>
					</span>
					<a class="mdl-list__item-secondary-action" onclick="removeInterest(this)"><i class="material-icons">cancel</i></a>
				    </div>
				</div>
			    </div>
			</div>
			 end grid 
		    </div>
                 
		 end scrollabel page content
                </div>-->


            </main>
        </div>   

        <!-- MODALS -->
	<div id="add-interest-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

<!--                 Modal content-->
                <div class="modal-content">
		    <div class="add-interest-dialog mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch">
			<div class="mdl-card__title">
			    <h6 class="mdl-card__title-text">
                                <?php echo(PROFILE_ADDINTERESTS_TITLE);?>
                            </h6>
			</div>
			<div class="mdl-card__supporting-text mdl-card--expand">
			    <div id="shopping-list" class="shopping-list-action mdl-list mdl-shadow--2dp">
				<div class="mdl-list__item" onclick="showShoppingList(this, 'sports-list')">
				    <span class="mdl-list__item-primary-content">
					<i class="material-icons mdl-list__item-avatar">fitness_center</i>
					<span class="list-hidable">
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
				</div>
				<div class="mdl-list__item" onclick="showShoppingList(this, 'programs-list')">
				    <span class="mdl-list__item-primary-content">
					<i class="material-icons mdl-list__item-avatar">tv</i>
					<span class="list-hidable">
                                            <?php echo(PROFILE_ADDINTERESTS_PROGRAMS);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
				</div>
				<div class="mdl-list__item" onclick="showShoppingList(this, 'others-list')">
				    <span class="mdl-list__item-primary-content">
					<i class="material-icons mdl-list__item-avatar">reorder</i>
					<span class="list-hidable">
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS);?>
                                        </span>
				    </span>
				    <span class="mdl-list__item-secondary-content">
					<a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
				    </span>
				</div>
			    </div>

			    <!-- BACK ARROW-->
			    <i id="hide-back-arrow" class="material-icons" onclick="hideInterestList()">keyboard_arrow_right</i>

			    <!-- SPORTS -->
			    <ul id="sports-list" class="mdl-list shopping-card-content-list ">
				<li data-interest="Baseball" class="mdl-list__item">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_1);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Basketball">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_2);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Football">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_3);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Swim">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_4);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Tennis">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_5);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Volleyball">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_SPORTS_6);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
			    </ul>

			    <!-- TV PROGRAMS -->
			    <ul id="programs-list" class="mdl-list shopping-card-content-list ">
				<li class="mdl-list__item" data-interest="Documentary">
                                        <span class="mdl-list__item-primary-content">
                                            <span>
                                            <?php echo(PROFILE_ADDINTERESTS_PROGRAMS_1);?>
                                        </span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
				</li>
				<li class="mdl-list__item" data-interest="TV news">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_PROGRAMS_2);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Talk show">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_PROGRAMS_3);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
			    </ul>

			    <!-- OTHERS -->
			    <ul id="others-list" class="mdl-list shopping-card-content-list ">
				<li class="mdl-list__item" data-interest="Cinema">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS_1);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Cooking">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS_2);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Monuments">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS_3);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Museums">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS_4);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
				<li class="mdl-list__item" data-interest="Theater">
				    <span class="mdl-list__item-primary-content">
					<span>
                                            <?php echo(PROFILE_ADDINTERESTS_OTHERS_5);?>
                                        </span>
				    </span>
				    <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
				</li>
			    </ul>
			</div>


			<div class="mdl-card__actions mdl-card--border">
			    <div class="mdl-layout-spacer"></div>
			    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect right-button" data-dismiss="modal">
			    Ok
			    </a> 
			</div>
		    </div>
		 
		</div>
	    </div>
	</div>
		    
		    
	<!-- MODAL PRECENDENTE-->	    
<!--        <div id="add-interest-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                 Modal content
                <div class="modal-content">
                    <div class="add-interest-dialog mdl-card">
                            <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text">Add interest</h6>
                                <div class="mdl-layout-spacer"></div>
                                
                                 menu 
                                <button id="demo-menu-lower-right" class="mdl-button mdl-js-button mdl-button--icon">
                                  <i class="material-icons">more_vert</i>
                                </button>

                                <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right">
                                    <li class="mdl-menu__item selected" data-list-id="sports" onclick="changeInterestList(this)">Sports</li>
                                    <li class="mdl-menu__item" data-list-id="programs" onclick="changeInterestList(this)">Program TV</li>
                                    <li class="mdl-menu__item" data-list-id="others" onclick="changeInterestList(this)">Others</li>
                                </ul>
                                
                            </div>
                            <div id="interest-list-container" class="mdl-card__supporting-text">
                                <div id="sports" class="mdl-list">
                                    <div data-interest="Baseball" class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Baseball</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Basketball">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Basketball</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Football">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Football</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Swim">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Swim</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Tennis">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Tennis</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Volleyball">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Volleyball</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    
                                </div>
                                
                                <div id="programs" class="mdl-list hidden">
                                    <div class="mdl-list__item" data-interest="Documentary">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Documentary</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="TV news">
                                        <span class="mdl-list__item-primary-content">
                                            <span>TV news</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Talk show">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Talk show</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                </div>
                                
                                <div id="others" class="mdl-list hidden">
                                    <div class="mdl-list__item" data-interest="Cinema">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Cinema</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Cooking">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Cooking</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">done</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Monuments">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Monuments</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Museums">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Museums</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                    <div class="mdl-list__item" data-interest="Theater">
                                        <span class="mdl-list__item-primary-content">
                                            <span>Theater</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" onclick="addInterest(this)"><i class="material-icons">add_circle_outline</i></a>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div class="mdl-layout-spacer"></div>
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect right-button" data-dismiss="modal">
                                Ok
                                </a> 

                            </div>
                        </div>
                    
                </div>

            </div>
        </div>-->

        
        <button id="add-interest" class="mdl-button mdl-shadow--4dp mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored hide-desktop_tablet floating-button floating-hide" data-toggle="modal" data-target="#add-interest-modal">
            <i class="material-icons">add</i>
        </button>
        
        
        <!-- ALERT MODAL -->
        <div id="alert-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="alert-modal-card mdl-card">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <i class="material-icons">warning</i>
                            <div id="modal-alert-text">
                            </div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(SEND_MESSAGE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
    </body>
  

</html>
