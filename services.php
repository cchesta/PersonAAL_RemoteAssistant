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
            var shoppingList;
            var shoppingMenuReduced;
            var prevShoppingListID;
            var prevClickedElement;
            var snackbar;
            
            String.prototype.capitalizeFirstLetter = function() {
                return this.charAt(0).toUpperCase() + this.slice(1);
            };
            
            $(document).ready(function() {
                shoppingList= [];
                shoppingMenuReduced= false;
                
                console.log($(window).width());
                
                //snackbar
                snackbar= document.getElementById("snackbar-alert");
                
                if($(window).width() <= 479)
                {
                    //delete all desktop/tablet elements if in phone mode
                    var toRemoveElements= document.querySelectorAll('._delete-phone_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                else
                {
                    //delete all phone elements if in desktop/tablet mode
                    var toRemoveElements= document.querySelectorAll('._delete-desktop_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                
                //VELOCITY ANIMATIONS
                $('.shopping-card, .service-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
            });
            
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
            
	    function hideShoppingList()
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
	    
            function showServiceModal(serviceType)
            {
                var today= new Date();
                //var serviceTypeString= serviceType.replace("_", " ").capitalizeFirstLetter();
                
                var serviceTypeString;
                switch(serviceType)
                {
                    case 'assistance':
                        serviceTypeString= "<?php echo(SERVICES_SERVICES_ASSISTANCE);?>";
                        break;
                        
                    case 'electrical_problem':
                        serviceTypeString= "<?php echo(SERVICES_SERVICES_ELECTRIC);?>";
                        break;
                        
                    case 'communication_problem':
                        serviceTypeString= "<?php echo(SERVICES_SERVICES_COMMUNICATION);?>";
                        break;
                        
                    case 'general_repairs':
                        serviceTypeString= "<?php echo(SERVICES_SERVICES_REPAIRS);?>";
                        break;
                    
                    case 'cleaning_service':
                        serviceTypeString= "<?php echo(SERVICES_SERVICES_CLEANING);?>";
                        break;
                }
                
                //get the current date
                var dd = today.getDate();
                var mm = today.getMonth()+1; //January is 0
                var yyyy = today.getFullYear();
                var h= today.getHours();
                var m= today.getMinutes();
                
                //fix for single digit strings
                if(dd <10)
                    dd='0'+dd;
                if(mm<10)
                    mm='0'+mm;
                if(h<10)
                    h='0'+h;
                if(m<10)
                    m='0'+m;
                
                var titleString= "[" + serviceTypeString + "] " + dd + "/" + mm + "/" + yyyy + " " + h + ":" + m;
                
                document.getElementById("service-modal-title").value= titleString;
                document.getElementById("service-modal-message").value= "";
                
                document.getElementById("service-modal-done").onclick= function(){
                    var message= "<?php echo(SERVICES_SNACKBAR_MESSAGE);?> " + document.getElementById("service-modal-title").value + " <?php echo(SERVICES_SNACKBAR_SENT);?>!";
                    var data = {message: message};
                    snackbar.MaterialSnackbar.showSnackbar(data);
                };
                
                console.log(document.getElementById("service-modal-done"));
                
                $("#service-modal").modal(); 
            }
            
           
        function selectFood(checkboxElement)
        {
            var foodName= checkboxElement.parentNode.parentNode.parentNode.getElementsByTagName('span')[0].innerText;
            console.log(foodName);
            
            if(checkboxElement.checked == true)
            {
                shoppingList.push(foodName);
            }
            else
            {
                var index = shoppingList.indexOf(foodName);
                
                //remove element from shoppingList
                if (index > -1)
                    shoppingList.splice(index, 1);
                
            }
            
            //disable/enable buy button if the shoppingList is empty/notEmpty
            if(shoppingList.length <= 0)
            {
                $('#buy-button').prop('disabled', true);
                $('#clear-button').prop('disabled', false);
            }
            else
            {
                $('#buy-button').prop('disabled', false);
                $('#clear-button').prop('disabled', false);
            }
                
                
                
        }
            
        function buy()
        {
            setupBuyConfirmModal();
            $('#buy-modal').modal({ keyboard: false, backdrop: 'static'});
        }
        
        function setupBuyConfirmModal()
        {
            var list= document.getElementById('modal-shopping-list');
            list.innerHTML= "";
            
            for(var i=0; i < shoppingList.length; i++)
            {
                var listElement= document.createElement('li');
                listElement.innerHTML= shoppingList[i];
                
                list.appendChild(listElement);
            }
            
        }
        
        function clearAll()
        {
            //uncheck all checkbox (trigger the click button due to technical problems
            $("input[type='checkbox']").each(function(){
                
                if($(this).prop('checked') == true)
                    $(this).trigger('click');
                
            });
            
            $('#clear-button').prop('disabled', true);
            $('#buy-button').prop('disabled', true);
        }
        
                
                
        </script>


        
        
    </head>
    <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_SERVICES);?></span>
                    <div class="mdl-layout-spacer"></div>
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="window.location='index.php';">
                        <i class="material-icons">home</i>
                    </button>
                </div>
                
                <div class="mdl-layout__tab-bar mdl-js-ripple-effect">
		    <a href="#shopping" class="mdl-layout__tab is-active"><?php echo(SERVICES_SHOPPING_TITLE);?></a>
		    <a href="#services" class="mdl-layout__tab"><?php echo(SERVICES_SERVICES_TITLE);?></a>
		</div>
                
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
		    <a class="mdl-navigation__link" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
                    <a class="mdl-navigation__link" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
                    <a class="mdl-navigation__link" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>
		    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    
                    
                    <!-- phone mode -->
                    <section class="mdl-layout__tab-panel is-active" id="shopping">
                        <div class="page-content">
                            <div class="mdl-grid">
                                
                                <div class="shopping-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch">
                                    <div class="mdl-card__title hide-phone">
                                        <h6 class="mdl-card__title-text"><?php echo(SERVICES_SHOPPING_TITLE);?></h6>
                                    </div>
                                    <div class="mdl-card__supporting-text mdl-card--expand">
                                        <div id="shopping-list" class="shopping-list-action mdl-list mdl-shadow--2dp">
                                            <div class="mdl-list__item" onclick="showShoppingList(this, 'drink-list')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">local_bar</i>
                                                    <span class="list-hidable">
                                                        <?php echo(SERVICES_SHOPPING_DRINKS);?>
                                                    </span>
                                                </span>
                                                <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
                                            </div>
                                            <div class="mdl-list__item" onclick="showShoppingList(this, 'frozen-food-list')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">local_pizza</i>
                                                    <span class="list-hidable">
                                                        <?php echo(SERVICES_SHOPPING_FROZENFOODS);?>
                                                    </span>
                                                </span>
                                                <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
                                            </div>
                                            <div class="mdl-list__item" onclick="showShoppingList(this, 'pasta-list')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">room_service</i>
                                                    <span class="list-hidable">
                                                        <?php echo(SERVICES_SHOPPING_PASTA);?>
                                                    </span>
                                                </span>
                                                <span class="mdl-list__item-secondary-content">
                                                    <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
                                                </span>
                                            </div>
                                            <div class="mdl-list__item" onclick="showShoppingList(this, 'meat-list')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">restaurant</i>
                                                    <span class="list-hidable">
                                                        <?php echo(SERVICES_SHOPPING_MEAT);?>
                                                    </span>
                                                </span>
                                                <span class="mdl-list__item-secondary-content">
                                                    <a class="mdl-list__item-secondary-action list-hidable" href="#"><i class="material-icons">navigate_next</i></a>
                                                </span>
                                            </div>
                                        </div>
                                        
					<!-- BACK ARROW-->
					<i id="hide-back-arrow" class="material-icons" onclick="hideShoppingList()">keyboard_arrow_right</i>
					
                                        <!-- DRINK CONTENTS -->
                                        <ul id="drink-list" class="mdl-list shopping-card-content-list ">
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_DRINKS_1);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-drink-1">
                                                        <input type="checkbox" id="list-drink-1" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_DRINKS_2);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-drink-2">
                                                        <input type="checkbox" id="list-drink-2" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_DRINKS_3);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-drink-3">
                                                        <input type="checkbox" id="list-drink-3" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                        </ul>
                                        
                                        <!-- frozen-food CONTENTS -->
                                        <ul id="frozen-food-list" class="mdl-list shopping-card-content-list ">
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_FROZENFOODS_1);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-frozen-food-1">
                                                        <input type="checkbox" id="list-frozen-food-1" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_FROZENFOODS_2);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-frozen-food-2">
                                                        <input type="checkbox" id="list-frozen-food-2" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_FROZENFOODS_3);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-frozen-food-3">
                                                        <input type="checkbox" id="list-frozen-food-3" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_FROZENFOODS_4);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-frozen-food-4">
                                                        <input type="checkbox" id="list-frozen-food-4" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                        </ul>
                                        
                                        <!-- pasta CONTENTS -->
                                        <ul id="pasta-list" class="mdl-list shopping-card-content-list ">
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Spaghetti
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-1">
                                                        <input type="checkbox" id="list-pasta-1" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Fusilli
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-2">
                                                        <input type="checkbox" id="list-pasta-2" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Lasagne
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-3">
                                                        <input type="checkbox" id="list-pasta-3" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Rigatoni
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-4">
                                                        <input type="checkbox" id="list-pasta-4" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Pennette
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-5">
                                                        <input type="checkbox" id="list-pasta-5" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    Farfalle
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-pasta-6">
                                                        <input type="checkbox" id="list-pasta-6" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                        </ul>
                                        
                                        <!-- meat CONTENTS -->
                                        <ul id="meat-list" class="mdl-list shopping-card-content-list ">
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_MEAT_1);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-meat-1">
                                                        <input type="checkbox" id="list-meat-1" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_MEAT_2);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-meat-2">
                                                        <input type="checkbox" id="list-meat-2" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                            <li class="mdl-list__item">
                                                <span class="mdl-list__item-primary-content">
                                                    <?php echo(SERVICES_SHOPPING_MEAT_3);?>
                                                </span>
                                                <span class="mdl-list__item-secondary-action">
                                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="list-meat-3">
                                                        <input type="checkbox" id="list-meat-3" class="mdl-checkbox__input" onchange="selectFood(this)"/>
                                                    </label>
                                                </span>
                                            </li>
                                        </ul>
                                        
                                    </div>
                                    
                                    
                                    <div class="mdl-card__actions mdl-card--border">
                                        <button id="clear-button" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" disabled onclick="clearAll()">
                                            <?php echo(DESELECT_BUTTON);?>
                                        </button>
                                        <button id="buy-button" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" disabled onclick="buy()">
                                            <?php echo(BUY_BUTTON);?>
                                        </button>
                                    </div>
                                </div>


                                <!-- SERVICE LIST -->
                                <div class="service-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch hide-phone">
                                    <div class="mdl-card__title hide-phone">
                                        <h6 class="mdl-card__title-text">
                                            <?php echo(SERVICES_SERVICES_TITLE);?>
                                        </h6>
                                    </div>
                                    <div class="mdl-card__supporting-text mdl-card--expand">
                                        <div class="service-list-action mdl-list">
                                            <div class="mdl-list__item" onclick="showServiceModal('assistance')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">accessible</i>
                                                    <span>
                                                        <?php echo(SERVICES_SERVICES_ASSISTANCE);?>
                                                    </span>
                                                </span>
                                                <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                            </div>
                                            <div class="mdl-list__item" onclick="showServiceModal('electrical_problem')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">settings_input_component</i>
                                                    <span>
                                                        <?php echo(SERVICES_SERVICES_ELECTRIC);?>
                                                    </span>
                                                </span>
                                                <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                            </div>
                                            <div class="mdl-list__item" onclick="showServiceModal('communication_problem')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">settings_input_antenna</i>
                                                    <span>
                                                        <?php echo(SERVICES_SERVICES_COMMUNICATION);?>
                                                    </span>
                                                </span>
                                                <span class="mdl-list__item-secondary-content">
                                                    <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                                </span>
                                            </div>
                                            <div class="mdl-list__item" onclick="showServiceModal('general_repairs')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">build</i>
                                                    <span>
                                                        <?php echo(SERVICES_SERVICES_REPAIRS);?>
                                                    </span>
                                                </span>
                                                <span class="mdl-list__item-secondary-content">
                                                    <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                                </span>
                                            </div>
                                            <div class="mdl-list__item" onclick="showServiceModal('cleaning_service')">
                                                <span class="mdl-list__item-primary-content">
                                                    <i class="material-icons mdl-list__item-avatar">local_laundry_service</i>
                                                    <span>
                                                        <?php echo(SERVICES_SERVICES_CLEANING);?>
                                                    </span>
                                                </span>
                                                <span class="mdl-list__item-secondary-content">
                                                    <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </div>
                    </section>
                    
                    <section class="mdl-layout__tab-panel" id="services">
                        <div class="page-content">
                            
                            <div class="service-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone">
                                <div class="mdl-card__title hide-phone">
                                    <h6 class="mdl-card__title-text">Services</h6>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">
                                    <div class="service-list-action mdl-list">
                                        <div class="mdl-list__item" onclick="showServiceModal('assistance')">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">accessible</i>
                                                <span>
                                                    <?php echo(SERVICES_SERVICES_ASSISTANCE);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </div>
                                        <div class="mdl-list__item" onclick="showServiceModal('electrical_problem')">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">settings_input_component</i>
                                                <span>
                                                    <?php echo(SERVICES_SERVICES_ELECTRIC);?>
                                                </span>
                                            </span>
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </div>
                                        <div class="mdl-list__item" onclick="showServiceModal('communication_problem')">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">settings_input_antenna</i>
                                                <span>
                                                    <?php echo(SERVICES_SERVICES_COMMUNICATION);?>
                                                </span>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                            </span>
                                        </div>
                                        <div class="mdl-list__item" onclick="showServiceModal('general_repairs')">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">build</i>
                                                <span>
                                                    <?php echo(SERVICES_SERVICES_REPAIRS);?>
                                                </span>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                            </span>
                                        </div>
                                        <div class="mdl-list__item" onclick="showServiceModal('cleaning_service')">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">local_laundry_service</i>
                                                <span>
                                                    <?php echo(SERVICES_SERVICES_CLEANING);?>
                                                </span>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </section>
                    
                    
                    
                    <!-- DESKTOP MODE -->
<!--                    <div class="mdl-grid">

                        <div class="shopping-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone no-stretch">
                            <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text">Shopping</h6>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="shopping-list-action mdl-list">
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">local_bar</i>
                                            <span>Drinks</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">local_pizza</i>
                                            <span>Frozen Foods</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">room_service</i>
                                            <span>Pasta</span>
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </span>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">restaurant</i>
                                            <span>Meat</span>
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </span>
                                    </div>
                                  </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div class="mdl-layout-spacer"></div>
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                Buy
                                </a>

                            </div>
                        </div>


                        <div class="shopping-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone no-stretch">
                            <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text">Services</h6>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="shopping-list-action mdl-list">
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">accessible</i>
                                            <span>Assistance</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">settings_input_component</i>
                                            <span>Electrical problem</span>
                                        </span>
                                        <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">settings_input_antenna</i>
                                            <span>Communication problem</span>
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </span>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">build</i>
                                            <span>General repairs</span>
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </span>
                                    </div>
                                    <div class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <i class="material-icons mdl-list__item-avatar">local_laundry_service</i>
                                            <span>Cleaning service</span>
                                        </span>
                                        <span class="mdl-list__item-secondary-content">
                                            <a class="mdl-list__item-secondary-action" href="#"><i class="material-icons">navigate_next</i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>-->
                 
                    
                    
                    
                    
                    <!-- end page content -->
                </div>
            </main>
        </div>   


        
        <!-- FITNESS MODAL -->
        <div id="service-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="service-modal mdl-card expand-in-modal">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <form action="#">
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label expand">
                                    <input class="mdl-textfield__input" type="text" id="service-modal-title" value=" ">
                                    <label class="mdl-textfield__label" for="service-modal-title">
                                        <?php echo(SERVICES_SERVICES_FORM_HINT_TITLE);?>                                    
                                    </label>
                                </div>
                                
                                <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-expand">
                                    <textarea class="mdl-textfield__input" type="text" rows= "3" id="service-modal-message"></textarea>
                                    <label class="mdl-textfield__label" for="service-modal-message">
                                        <?php echo(SERVICES_SERVICES_FORM_HINT_MESSAGE);?>
                                    </label>
                                </div>
                            </form>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a id="service-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CANCEL_BUTTON);?>    
			    </a>
                            <a id="service-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(SEND_BUTTON);?>   
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        
        <!-- BUY MODAL -->
        <div id="buy-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="service-modal mdl-card expand-in-modal">
                        <div class="mdl-card__title">
                            <?php echo(SERVICES_SHOPPING_CONFIRMED_TITLE);?>
                        </div>
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <ul id="modal-shopping-list">
                                
                            </ul>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a id="service-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" onclick="clearAll()">
				OK
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        
        <div id="snackbar-alert" class="mdl-js-snackbar mdl-snackbar">
            <div class="mdl-snackbar__text"></div>
            <button class="mdl-snackbar__action" type="button"></button>
        </div>
        
        
        
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
