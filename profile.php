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

    <!DOCTYPE html>
    <!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
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

        <style>
            input:disabled {
                color: black !important;
            }
        </style>

        <link rel="stylesheet" href="css/custom.css">


        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>

        <!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>

        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>


        <!-- javascript functions for DB (ajax requests)-->
        <script src="js/DBinterface.js"></script>

        <!-- ADAPTATION SCRIPTS -->
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>
        <script src="./js/plugins/adaptation/delegate.js"></script>
        <script src="./js/profile.js"></script>

        <script>
            var prevShoppingListID;
            var prevClickedElement;


            $(document).ready(function() {
                shoppingMenuReduced = false;

                //delete all phone elements if in desktop/tablet mode
                if ($(window).width() <= 479) {
                    var toRemoveElements = document.querySelectorAll('._delete-phone_');
                    for (var i = 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                } else {
                    var toRemoveElements = document.querySelectorAll('._delete-desktop_');
                    for (var i = 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }

                //VELOCITY ANIMATIONS
                $('.patient-info-card, .interest-list-card').velocity('transition.slideUpBigIn', {
                    stagger: 250,
                    display: 'flex'
                });

                // userInfo= new UserData($_SESSION['personAAL_user']);
            });



            function disableAddButton() {
                document.getElementById("add-interest").style.display = "none";
            }

            function enableAddButton() {
                document.getElementById("add-interest").style.display = "block";
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
                    <a href="#patient-info" class="mdl-layout__tab is-active" onclick="disableAddButton()">
                        <?php echo(PROFILE_PROFILECARD_TITLE);?>
                    </a>
                    <a href="#patient-interest" class="mdl-layout__tab" onclick="enableAddButton()">
                        <?php echo(PROFILE_INTERESTS_TITLE);?>
                    </a>
                </div>
            </header>

            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
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
                                //$userInfo= new UserData($_SESSION['personAAL_user']);        
                            ?>
                            <div class="patient-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">
                                <div class="mdl-card__title hide-phone">
                                    <h6 class="mdl-card__title-text">
                                        <?php echo(PROFILE_PROFILECARD_TITLE);?>
                                    </h6>
                                </div>

                                <div class="mdl-card__supporting-text mdl-card--expand">
                                        <div class="icon-textfield">
                                            <i class="material-icons">person</i>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profileName" disabled/>
                                                <label class="mdl-textfield__label" for="profileName"><?php echo(PROFILE_PROFILECARD_NAME);?>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profileSurname" disabled/>
                                                <label class="mdl-textfield__label" for="profileSurname"><?php echo(PROFILE_PROFILECARD_SURNAME);?>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" pattern="\d{4}-\d{2}-\d{2}" id="profileBirthDate" disabled/>
                                                <label class="mdl-textfield__label" for="profileBirthDate"><?php echo(PROFILE_PROFILECARD_BIRTHDATE);?>
                                                </label>
                                                <span class="mdl-textfield__error">YYYY-MM-DD</span>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" pattern="(<?php echo(PROFILE_PROFILECARD_GENDER_MALE);?>|<?php echo(PROFILE_PROFILECARD_GENDER_FEMALE);?>)" id="profileGender" disabled/>
                                                <label class="mdl-textfield__label" for="profileGender"><?php echo(PROFILE_PROFILECARD_GENDER);?>
                                                </label>
                                                <span class="mdl-textfield__error"><?php echo(PROFILE_PROFILECARD_GENDER_MALE);?> or <?php echo(PROFILE_PROFILECARD_GENDER_FEMALE);?></span>
                                            </div>
                                        </div>
                                    
                                        <div class="icon-textfield">
                                            <i class="material-icons">home</i>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profileState" disabled/>
                                                <label class="mdl-textfield__label" for="profileState"><?php echo(PROFILE_PROFILECARD_STATE);?>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profileCity" disabled/>
                                                <label class="mdl-textfield__label" for="profileCity"><?php echo(PROFILE_PROFILECARD_CITY);?>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profilePostalCode" disabled/>
                                                <label class="mdl-textfield__label" for="profilePostalCode"><?php echo(PROFILE_PROFILECARD_POSTALCODE);?>
                                                </label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                                <input class="mdl-textfield__input" type="text" id="profileAddress" disabled/>
                                                <label class="mdl-textfield__label" for="profileAddress"><?php echo(PROFILE_PROFILECARD_ADDRESS);?>
                                                </label>
                                            </div>
                                        </div>

                                </div>

                                <div class="mdl-card__actions mdl-card--border">
                                    <button id="edit_button" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored" onclick="editProfile()">EDIT PROFILE</button>
                                    <button id="cancel_changes" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored" onclick="discardChanges()" style="display:none">CANCEL CHANGES</button>
                                </div>

                            </div>

                            <div class="interest-list-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch hide-phone _delete-phone_">
                                <div class="mdl-card__title">
                                    <h6 class="mdl-card__title-text">
                                        <?php echo(PROFILE_INTERESTS_TITLE);?>
                                    </h6>
                                    <div class="mdl-layout-spacer"></div>
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
                                <div class="mdl-card__actions mdl-card--border">
                                    <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--colored" data-toggle="modal" data-target="#add-interest-modal">ADD INTEREST</button>
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
