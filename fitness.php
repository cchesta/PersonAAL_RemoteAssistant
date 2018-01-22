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
        
        
<!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
        
        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>
        
        <!-- javascript functions for DB (ajax requests)-->
        <script src="js/DBinterface.js"></script>
        
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
            var fitnessExercises= [];
	    var searchDifficulty;
	    var bodyPartTags;
            
            $(document).ready(function() {
                document.getElementById("no-result").style.display= "none";
                
                if($(window).width() <= 479)
                {
                    //remove ripple effect from cards (performance boost on mobile)
                    $('.mdl-card').removeClass("mdl-js-ripple-effect mdl-js-button");
                    
                    //delete all desktop/tablet elements if in phone mode
                    var toRemoveElements= document.querySelectorAll('._delete-phone_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                    
                    $(window).on( "orientationchange", function( event ) { 
                        document.getElementById("search").className= document.getElementById("search").className.replace("hide-desktop_tablet", "");
                        document.getElementById("search").style.display= "block";
                    } );
                }
                else
                {
                    //delete all phone elements if in desktop/tablet mode
                    var toRemoveElements= document.querySelectorAll('._delete-desktop_');
                    for(var i= 0; i < toRemoveElements.length; i++)
                        toRemoveElements[i].parentNode.removeChild(toRemoveElements[i]);
                }
                
                /*var ex1= {
                    id: 'fitness1',
                    image: 'img/ex1.gif',
                    title: 'Triceps push up',
                    instructions: [
                        'Use a stable bench or two chairs as support',
                        'Start with folded arms',
                        'Push up using triceps, breath out',
                        'Go down slowly while breathing'
                    ],
                    isometry: false,
                    repetition: 10,
                    series: 3,
                    restTimeMilliseconds: 6000,
                    bodyPartUpper: true,
                    bodyPartLower: false,
                    bodyPartAbdominal: false,
                    difficulty: "medium"
                };
                var ex2= {
                    id: 'fitness2',
                    image: 'img/ex2.gif',
                    title: 'Core stability for lateral abs',
                    instructions: [
                        'Lay on your left/right size',
                        'Stand on your left/right folded arm',
                        'Keep the position, breath regulary'
                    ],
                    isometry: true,
                    repetition: 30,
                    series: 3,
                    restTimeMilliseconds: 6000
                };
                var ex3= {
                    id: 'fitness3',
                    image: 'img/ex3.gif',
                    title: 'Wall chair',
                    instructions: [
                        'Put yourself in a \"sit\" position, leaning to the wall',
                        'Keep the position, breath regulary'
                    ],
                    isometry: true,
                    repetition: 30,
                    series: 3,
                    restTimeMilliseconds: 6000
                };
                var ex4= {
                    id: 'fitness4',
                    image: 'img/ex4.gif',
                    title: 'Squats',
                    instructions: [
                        'Optional: Use additional weights',
                        'Keep your feet larger then the shoulders',
                        'Start going down as if you are tring to sit',
                        'Go down until your back can stay straight',
                        'Return slowly on stand position while breathing out'
                    ],
                    isometry: false,
                    repetition: 15,
                    series: 3,
                    restTimeMilliseconds: 6000
                };
                
                fitnessExercises.push(ex1);
                fitnessExercises.push(ex2);
                fitnessExercises.push(ex3);
                fitnessExercises.push(ex4);*/
                
                
                //get all exercises and create relative cards
                getFitnessExercise(drawFitnessCards, false, false, false, false, null);
                
                //fix for "easy" diffculty not checked
                $('#exercise-option-1').prop("checked", true);
                
                //VELOCITY ANIMATIONS
                if($(window).width() <= 479)
                    $('.fitness-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                else
                    $('.fitness-card, .search-filters-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                
                //show disclaimer modal
                $("#disclaimer-modal").modal(); 
                
            });
            
            function drawFitnessCards(fitnessExerciseList, fromSearch)
            {
                var fitnessCardGrid = document.getElementById('exercise-cards-grid');
                $('.fitness-card').remove();
                
                //save exercise list
                fitnessExercises= fitnessExerciseList;
                
                //hide no-result card
                document.getElementById("no-result").style.display= "none";
                
                if(fitnessExercises === false || fitnessExercises.length <= 0)
                {
                    //show no result card 
                    console.log("Nessun risultato!!!!!");
		    
		    document.getElementById('no-result-text').innerHTML= "<?php echo(SEARCHRESULT_NORESULTS);?>";
                    $('#no-result').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                    
                    return;
                }
                
                
                for(var i=0; i < fitnessExerciseList.length; i++)
                    fitnessCardGrid.appendChild(createFitnessCard(fitnessExerciseList[i]));
                
                //VELOCITY ANIMATIONS
		if($(window).width() <= 479 && fromSearch) //mobile
                {
		    var resultCardText= document.getElementById('no-result-text');
		    resultCardText.innerHTML= "";
		    
		    //create div to show search filters
		    var filterTextDiv= document.createElement("div");
		    filterTextDiv.className="search-filters-text";
		    filterTextDiv.innerHTML= '<b><?php echo(SEARCHRESULT_TITLE);?>:</b>';
		    
		    //insert exercise difficulty
		    if(searchDifficulty !== null)
			filterTextDiv.innerHTML+= '<br><?php echo(FITNESS_SEARCHRESULT_DIFFICULTY);?>: ' + tranlsatedDifficulty(searchDifficulty);
		    
		    //insert body parts list
		    if(bodyPartTags.length > 0)
		    {
			filterTextDiv.innerHTML+= '<br><?php echo(FITNESS_SEARCHRESULT_BODYPARTS);?>:';
			for(var i=0; i < bodyPartTags.length; i++)
			    filterTextDiv.innerHTML+= " " + tranlsatedBodyPart(bodyPartTags[i]);
		    }
		    
		    resultCardText.appendChild(filterTextDiv);
		    $('#no-result, .fitness-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
		}
		else
		    $('.fitness-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                
            }
            
            /*return translated diffuclty string*/
            function tranlsatedDifficulty(difficulty)
            {
                switch(difficulty)
                {
                    case "easy":
                        return "<?php echo(FITNESS_SEARCH_DIFFICULTY_EASY);?>";
                        
                    case "medium":
                        return "<?php echo(FITNESS_SEARCH_DIFFICULTY_MEDIUM);?>";

                    case "hard":
                        return "<?php echo(FITNESS_SEARCH_DIFFICULTY_HARD);?>";
                }
            }
            
            /*return translated diffuclty string*/
            function tranlsatedBodyPart(bodyPart)
            {
                switch(bodyPart)
                {
                    case "upper":
                        return "<?php echo(FITNESS_SEARCH_BODYPARTS_UPPER);?>";
                        
                    case "lower":
                        return "<?php echo(FITNESS_SEARCH_BODYPARTS_LOWER);?>";

                    case "abdominal":
                        return "<?php echo(FITNESS_SEARCH_BODYPARTS_ABDOMINAL);?>";
                }
            }
            
            /*create a fitness card*/
            function createFitnessCard(exercise)
            {

                //prepare card element
                var card= document.createElement('div');
                card.className= "fitness-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-tablet mdl-cell--2-col-phone";
                card.onclick= function() { showFitnessModal(exercise); };
                
                //prepare card title
                var cardTitle= document.createElement('div');
                cardTitle.className= "mdl-card__title mdl-card--expand";
                getExerciseImage(setCardBackground, cardTitle, exercise.id);
                
                //prepare card supporting text
                var cardSupportingText= document.createElement('div');
                cardSupportingText.className= "mdl-card__supporting-text";
                cardSupportingText.innerHTML= exercise.title;
                
                //prepare card actinos 
                var cardActions= document.createElement('div');
                cardActions.className= "mdl-card__actions mdl-card--border";
                
                //prepare card actinos 
                var cardActionsButton= document.createElement('a');
                cardActionsButton.innerHTML= "<?php echo(FITNESS_SHOWEXERCISE_BUTTON)?>";
                cardActionsButton.className= "mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect";
                
                //assemble card
                cardActions.appendChild(cardActionsButton);
                card.appendChild(cardTitle);
                card.appendChild(cardSupportingText);
                card.appendChild(cardActions);
                
                return card;
                
            }
            
            function setCardBackground(HTMLelement, imageBase64)
            {
                HTMLelement.style.background= 'url(data:image/gif;base64,' + imageBase64 + ') center / cover';
                HTMLelement.style.backgroundSize= 'auto 200px';
                HTMLelement.style.backgroundRepeat= 'no-repeat';
            }
            
            function fitnessSearch(){
                //hide no-result card
                document.getElementById("no-result").style.display= "none";
                
                //save chosen difficulty
                var difficultyRadios= $("[name=difficulty-options]");
                searchDifficulty= null;
                
                for(var i=0; i < difficultyRadios.length; i++)
                {
                    if(difficultyRadios[i].checked)
                    {
                        searchDifficulty= difficultyRadios[i].value;
                        break;
                    }
                }
                console.log(searchDifficulty);
                
                
                /*save body part tags
                 * 
                 * 0: upper
                 * 1: lower
                 * 2: abdominal
                 */
                var bodyPartCheckboxes= $("[name=body-part-options]");
		bodyPartTags= [];   //needed to show used search filters
                var bodyPartvalues= [];
                
                for(var i=0; i < bodyPartCheckboxes.length; i++)
                {
                    if(bodyPartCheckboxes[i].checked)
		    {
			bodyPartTags.push(bodyPartCheckboxes[i].value);
                        bodyPartvalues.push(true);
		    }
                    else
                        bodyPartvalues.push(false);
                }
                console.log(bodyPartvalues);
                
                
                
                //get all exercises and create relative cards
                getFitnessExercise(drawFitnessCards, true, bodyPartvalues[0], bodyPartvalues[1], bodyPartvalues[2], searchDifficulty);
                
                //dismiss search modal if in mobile version
                if($(window).width() <= 479)
                {
                    $("#search-modal").modal('hide');
                }
            }
            
            function showFitnessModal(exercise)
            {
                console.log(exercise);
                setupFitnessModal(exercise);
                
                $("#fitness-exercise-modal").modal(); 
            }
            
            function setupFitnessModal(exercise)
            {
                document.getElementById("fitness-exercise-modal-title").textContent= exercise.title + " (" + tranlsatedDifficulty(exercise.difficulty) + ")";
                //set modal background image 
                var modalImageElement= document.getElementById("fitness-exercise-modal-image");
                getExerciseImage(setBackgroundModal, modalImageElement, exercise.id);
                
                var instructionList= document.getElementById("fitness-exercise-modal-list");
                instructionList.innerHTML= "<?php echo(FITNESS_SHOWEXERCISE_TRAINEDPARTS);?>: ";
                if(exercise.bodyPartUpper == true)
                    instructionList.innerHTML+= "<?php echo(FITNESS_SEARCH_BODYPARTS_UPPER);?> ";
                if(exercise.bodyPartLower == true)
                    instructionList.innerHTML+= "<?php echo(FITNESS_SEARCH_BODYPARTS_LOWER);?> ";
                if(exercise.bodyPartAbdominal == true)
                    instructionList.innerHTML+= "<?php echo(FITNESS_SEARCH_BODYPARTS_ABDOMINAL);?> ";
                
                console.log(exercise);
                for(var i=0; i < exercise.instructions.length; i++)
                {
                    var instructionElement= document.createElement("li");
                    instructionElement.className= "mdl-list__item";
                    
                    var firstSpan= document.createElement("span");
                    firstSpan.className= "mdl-list__item-primary-content";
                    firstSpan.innerHTML= '<span class="list-index">' + (i+1) + '</span>' + exercise.instructions[i];
                    
                    instructionElement.appendChild(firstSpan);
                    
                    instructionList.appendChild(instructionElement);
                }
            }
            
            function setBackgroundModal(HTMLelement, imageBase64)
            {
                HTMLelement.style.background= 'url(data:image/gif;base64,' + imageBase64 + ') center / cover';
                
                if($(window).width() <= 479)
                    HTMLelement.style.backgroundSize= 'auto 200px';
                else
                    HTMLelement.style.backgroundSize= 'auto auto';
    
                HTMLelement.style.backgroundRepeat= 'no-repeat';
            }
        </script>
    </head>
    <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_FITNESS);?></span>
                    <div class="mdl-layout-spacer"></div>
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="window.location='index.php';">
                        <i class="material-icons">home</i>
                    </button>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
		    <a class="mdl-navigation__link" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
                    <a class="mdl-navigation__link" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
                    <a class="mdl-navigation__link" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>
		    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    
                    <!-- Your content goes here -->
                    <div class="mdl-grid">

                        <div class="search-filters-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-phone mdl-cell--3-col-tablet no-stretch _delete-phone_">
                            <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text">
                                    <?php echo(SEARCH_TITLE);?>
                                </h6>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="card-choice-group">
                                    <div class="card-group-label"><?php echo(FITNESS_SEARCH_BODYPARTS);?>:</div>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-upper">
                                        <input type="checkbox" id="checkbox-upper" class="mdl-checkbox__input" value="upper" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_UPPER);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-lower">
                                        <input type="checkbox" id="checkbox-lower" class="mdl-checkbox__input" value="lower" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_LOWER);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-abdominal">
                                        <input type="checkbox" id="checkbox-abdominal" class="mdl-checkbox__input" value="abdominal" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_ABDOMINAL);?>
                                        </span>
                                    </label>
                                </div>
                                <div class="card-choice-group">
                                    <div class="card-group-label"><?php echo(FITNESS_SEARCH_DIFFICULTY);?>:</div>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-1">
                                        <input type="radio" id="exercise-option-1" class="mdl-radio__button" name="difficulty-options" value="easy" checked>
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_EASY);?>
                                        </span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-2">
                                        <input type="radio" id="exercise-option-2" class="mdl-radio__button" name="difficulty-options" value="medium">
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_MEDIUM);?>
                                        </span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-3">
                                        <input type="radio" id="exercise-option-3" class="mdl-radio__button" name="difficulty-options" value="hard">
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_HARD);?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect simple-button" onclick="fitnessSearch()">
                                <?php echo(SEARCH_BUTTON);?>
                                </a>

                            </div>
                        </div>


                        <!-- EXERCISE -->
                        <div id="exercise-cards-grid" class="mdl-grid mdl-cell no-stretch grid-no-padding mdl-cell mdl-cell--8-col-desktop mdl-cell--5-col-tablet mdl-cell--4-col-phone">

                            
                            <div id="no-result" class="simple-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone  mdl-cell--8-col-tablet no-stretch"
                            onclick="window.location='#';">
                                <div id="no-result-text" class="mdl-card__supporting-text mdl-card--expand">
                                    
                                </div>
                            </div>

                        </div>

                        <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone floating-button-fix-cell"></div>
                    </div>
                 
                </div>
            </main>
        </div>   


        <!-- FITNESS MODAL -->
        <div id="fitness-exercise-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="fitness-exercise-modal mdl-card">
			<div class="mdl-card__title">
			    <div id="fitness-exercise-modal-title" class="mdl-card__title-text"></div>
			</div>
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <div id="fitness-exercise-modal-image" class="fitness-exercise-modal-image mdl-shadow--2dp">
<!--                                <img id="fitness-exercise-modal-image" src="img/ex1.gif">-->
                            </div>
                            <div class="fitness-exercise-instruction">
                                <ul id="fitness-exercise-modal-list" class="mdl-list">
<!--                                    <li class="mdl-list__item">
                                        <span class="mdl-list__item-primary-content">
                                            <span class="list-index">1</span>Bryan Cranston
                                        </span>
                                    </li>-->
                                </ul>
                            </div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a id="fitness-exercise-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CLOSE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        
        
        
        <!-- SEARCH MODAL FOR MOBILE-->
        <div id="search-modal" class="modal fade _delete-desktop_" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="search-filters-card mdl-card expand-in-modal">
                        <div class="mdl-card__title">
                                <h6 class="mdl-card__title-text">
                                    <?php echo(SEARCH_TITLE);?>
                                </h6>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="card-choice-group">
                                    <div class="card-group-label"><?php echo(FITNESS_SEARCH_BODYPARTS);?>:</div>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-upper">
                                        <input type="checkbox" id="checkbox-upper" class="mdl-checkbox__input" value="upper" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_UPPER);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-lower">
                                        <input type="checkbox" id="checkbox-lower" class="mdl-checkbox__input" value="lower" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_LOWER);?>
                                        </span>
                                    </label>
                                    <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect" for="checkbox-abdominal">
                                        <input type="checkbox" id="checkbox-abdominal" class="mdl-checkbox__input" value="abdominal" name="body-part-options">
                                        <span class="mdl-checkbox__label">
                                            <?php echo(FITNESS_SEARCH_BODYPARTS_ABDOMINAL);?>
                                        </span>
                                    </label>
                                </div>
                                <div class="card-choice-group">
                                    <div class="card-group-label"><?php echo(FITNESS_SEARCH_DIFFICULTY);?>:</div>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-1">
                                        <input type="radio" id="exercise-option-1" class="mdl-radio__button" name="difficulty-options" value="easy" checked>
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_EASY);?>
                                        </span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-2">
                                        <input type="radio" id="exercise-option-2" class="mdl-radio__button" name="difficulty-options" value="medium">
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_MEDIUM);?>
                                        </span>
                                    </label>
                                    <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect" for="exercise-option-3">
                                        <input type="radio" id="exercise-option-3" class="mdl-radio__button" name="difficulty-options" value="hard">
                                        <span class="mdl-radio__label">
                                            <?php echo(FITNESS_SEARCH_DIFFICULTY_HARD);?>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect simple-button" onclick="fitnessSearch()">
                                    <?php echo(SEARCH_BUTTON);?>
                                </a>
                            </div>
                    </div>
                    
                    
                </div>

            </div>
        </div>
        
        
        
        <button id="search" class="mdl-button mdl-shadow--4dp mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored floating-button hide-desktop_tablet" data-toggle="modal" data-target="#search-modal">
            <i class="material-icons">search</i>
        </button>
        
        
        
        <!-- ALERT MODALS -->
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
        
        
        <!-- DISCLAIMER -->
        <div id="disclaimer-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="alert-modal-card mdl-card">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <i class="material-icons">warning</i>
                            <div id="modal-alert-text">
                                <?php echo(FITNESS_ALERT_TITLE);?>
                            </div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CLOSE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
    </body>
    

</html>
