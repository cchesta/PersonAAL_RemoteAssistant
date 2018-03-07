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

<!--TODO CAMBIARE IL COLORE DEGLI EVENTI DONE ALL'INIZIALIZZAZIONE DEL CALENDARIO
TODO I VALORI DEGLI OBBIETTIVI DEVONO ESSERE AGGIORNATI SOLO QUANDO L'UTENTE LI SETTA COME "DONE"-->
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
        <script src="./js/plan.js"></script>





        <!--        script for tables-->
        <script>

            //goals
            var exerciseGoal;
            var walkGoal;
            var meetGoal;

            var exerciseActualAmount;
            var walkActualAmount;
            var meetActualAmount;

            //intervals values
            var maxSteps= 20000;
            var maxMeet= 10;
            //var maxCook= 3000;

            var stepsInterval= 500;
            var meetInterval= 1;
            //var cookInterval= 100;

            var stepsString= "<?php echo(PLAN_GOALS_MIN);?>";
            var exerciseString= "<?php echo(PLAN_GOALS_MIN);?>";
            var meetString= "<?php echo(PLAN_GOALS_ACTIVITY);?>";
            //var cookString= "<?php echo(PLAN_GOALS_COOK);?>";

            //animation fix for mobile
            var fixGridHeight;



            var oCal1, sInputTZOffset = "-00:00";



            $(document).ready(function() {

                //goals
                <?php

                $p = new Plan($_SESSION['personAAL_user']);

                echo("walkActualAmount= ". $p->getActualWalk() .";");
                echo("exerciseActualAmount= ". $p->getActualExercise() .";");
                echo("meetActualAmount= ". $p->getActualMeet() .";");
                echo("walkGoal= ". $p->getWalkGoal() .";");
                echo("exerciseGoal= ". $p->getExerciseGoal() .";");
                echo("meetGoal= ". $p->getMeetGoal() .";");

                ?>


                if(walkGoal !== 0 && exerciseGoal !== 0 && meetGoal !== 0)
                {
                    setupGoalSettingsCard();
                    $('#goal-settings-card').hide();
                }
                else
                    $('#goal-view-card').hide();


              

                //for mobile animation fix
                fixGridHeight= document.getElementById("fix-grid").style.height;

                //VELOCITY ANIMATIONS
                if(walkGoal !== 0 && exerciseGoal !== 0 && meetGoal !== 0)
                    $('#goal-view-card, #calendar-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                else
                    $('#goal-settings-card, #calendar-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
                
                  //SLIDER
                $('#slide_01').on('input',function(){
                    $("#text_01").get(0).MaterialTextfield.change(this.value);
                });
                $('#inp_text_01').keyup(function() {
                    $("#slide_01").get(0).MaterialSlider.change($( '#inp_text_01').val());
                    console.dir($('#slide_01'));
                });
                $('#slide_02').on('input',function(){
                    $("#text_02").get(0).MaterialTextfield.change(this.value);
                });
                $('#inp_text_02').keyup(function() {
                    $("#slide_02").get(0).MaterialSlider.change($( '#inp_text_02').val());
                    console.dir($('#slide_02'));
                });
                $('#slide_03').on('input',function(){
                    $("#text_03").get(0).MaterialTextfield.change(this.value);
                });
                $('#inp_text_03').keyup(function() {
                    $("#slide_03").get(0).MaterialSlider.change($( '#inp_text_03').val());
                    console.dir($('#slide_03'));
                });





                /* initialize the external events
                    -----------------------------------------------------------------*/
                $('#external-events .fc-event').each(function() {

                    // store data so the calendar knows to render an event upon drop
                    //                        var uno= $(this).children('.mdl-chip').children('.mdl-chip__text').text().trim();
                    //                         console.log("uno:");
                    //                        console.log(uno);
                    //                        var prova= $.trim(uno.split("\n")[3]);
                    //                        console.log("prova:" + prova);
                    var eventTitle= $(this).children('.mdl-chip').children('.mdl-chip__text').text().trim();
                    var eventType;

                    //switch case on language dipendent title to assign the respective type value
                    switch(eventTitle)
                    {
                        case "<?php echo(PLAN_GOALS_EXERCISE);?>":
                            eventType= "Exercise";
                            break;

                        case "<?php echo(PLAN_GOALS_WALK);?>":
                            eventType= "Walk";
                            break;

                        case "<?php echo(PLAN_GOALS_MEET);?>":
                            eventType= "Meet";
                            break;

                            //                            case "<?php echo(PLAN_GOALS_COOK);?>":
                            //                                eventType= "Cook";
                            //                                break;
                    }

                    $(this).data('event', {
                        title: eventTitle, // use the element's text as the event title
                        stick: true, // maintain when user navigates (see docs on the renderEvent method)
                        type: eventType
                    });

                    // make the event draggable using jQuery UI
                    $(this).draggable({
                        zIndex: 999,
                        revert: true,      // will cause the event to go back to its
                        revertDuration: 0  //  original position after the drag
                    });

                });

      console.log("exercise goal: ", exerciseGoal);        
                
getMeetGoalFromContext(getWalkGoalFromContext,getExerciseGoalFromContext,updateGoalFields);


              
            });




            function setEventDescriptionModal(calEvent){

                //get event date
                var date= new Date(calEvent.start);
                date= date.getDate() + '/' + (date.getMonth()+1) + '/' + date.getFullYear();

                //set modal attributes
                //TODO capire come poter cambiare il titolo della modal in base alla lingua (impossibile con php poichè è codice che viene eseguito a runtime)
                document.getElementById("event-modal-title-text").textContent= calEvent.title + "  " + date;
                //document.getElementById("event-modal-title").style.background= 'url("img/fitness-event.jpg") center / cover';

                var intervalString;

                //get event interval
                if(calEvent.type == "Exercise")
                {
                    var hour= calEvent.intervalValue.split(":")[0];
                    var minute= calEvent.intervalValue.split(":")[1];

                    if(hour == 0)
                        intervalString= minute + "'";
                    else
                        intervalString= hour + "h " + minute + "'";
                }
                else
                {
                    switch(calEvent.type)
                    {
                        case "Walk":
                            intervalString= calEvent.intervalValue + " " + stepsString;
                            break;

                        case "Meet":
                            intervalString= calEvent.intervalValue + " " + meetString;
                            break;

                            //                    case "Cook":
                            //                        intervalString= calEvent.intervalValue + " " + cookString;
                            //                        break;
                    }
                }

                document.getElementById("event-modal-interval").textContent= intervalString;

                //attach onclick function for "event done" and "cancel event"
                document.getElementById("event-modal-cancel").onclick= function() { removeEvent(calEvent);};

                var eventTimestamp= new Date(calEvent.start).getTime();
                var eventDay= new Date(calEvent.start).getDate();
                var eventMonth= new Date(calEvent.start).getMonth();
                var actualTimestamp= Date.now();
                var actualDay= new Date().getDate();
                var actualMonth= new Date().getMonth();

                console.log("event: "+eventTimestamp+" "+eventDay+" "+eventMonth);
                console.log("actual: "+actualTimestamp+" "+actualDay+" "+actualMonth);
                if(calEvent.done === false)
                {
                    document.getElementById("event-modal-done").onclick= function() { setEventDone(calEvent);};
                    document.getElementById("event-modal-done").innerHTML= "<?php echo(DONE_BUTTON);?>";

                    //disable done button if event in the future
                    //		if(actualTimestamp < eventTimestamp)
                    //		    $('#event-modal-done').hide();
                    //		else
                    //		    $('#event-modal-done').show();
                    if(actualDay < eventDay && actualTimestamp < eventTimestamp)
                        $('#event-modal-done').hide();
                    else
                        $('#event-modal-done').show();  
                }
                else
                {
                    document.getElementById("event-modal-done").onclick= function() { setEventUndone(calEvent);};
                    document.getElementById("event-modal-done").innerHTML= "<?php echo(UNDONE_BUTTON);?>";

                    //disable undone button if event in the past
                    //		if(actualTimestamp > eventTimestamp)
                    //		    $('#event-modal-done').hide();
                    //		else
                    //		    $('#event-modal-done').show();
                    //		if(actualDay > eventDay && actualTimestamp > eventTimestamp)
                    //		    $('#event-modal-done').hide();
                    //		else
                    //		    $('#event-modal-done').show();
                }


                $("#event-modal").modal(); 
            }

            function setEventDone(event){
                event.color= '#00cc00';
                event.backgroundColor= '#00cc00';
                event.done= true;
                event.editable= false;   //event no more draggable or editable
                calendar.fullCalendar( 'updateEvent', event );

                //update DB
                saveEventList(calendar);

                //update goal values
                switch(event.type)
                {
                    case "Exercise":
                        var hour= parseInt(event.intervalValue.split(":")[0]);
                        var minute= parseInt(event.intervalValue.split(":")[1]);

                        exerciseActualAmount= parseInt(exerciseActualAmount) + hour*60 + minute;
                        break;

                    case "Walk":

                        walkActualAmount= parseInt(walkActualAmount) + parseInt(event.intervalValue);
                        break;

                    case "Meet":

                        meetActualAmount= parseInt(meetActualAmount) + parseInt(event.intervalValue);
                        break;
                }

                updateGoalFields();


            }

            function setEventUndone(event)
            {
                event.color= '#3a87ad';
                event.backgroundColor= '#3a87ad';
                event.done= false;
                event.editable= false;   //event no more draggable or editable
                calendar.fullCalendar( 'updateEvent', event );

                //update DB
                saveEventList(calendar);

                //update goal values
                switch(event.type)
                {
                    case "Exercise":
                        var hour= parseInt(event.intervalValue.split(":")[0]);
                        var minute= parseInt(event.intervalValue.split(":")[1]);

                        exerciseActualAmount= parseInt(exerciseActualAmount) - hour*60 - minute;
                        if(exerciseActualAmount < 0)
                            exerciseActualAmount= 0;

                        break;

                    case "Walk":

                        walkActualAmount= parseInt(walkActualAmount) - parseInt(event.intervalValue);
                        if(walkActualAmount < 0)
                            walkActualAmount= 0;

                        break;

                    case "Meet":

                        meetActualAmount= parseInt(meetActualAmount) - parseInt(event.intervalValue);
                        if(meetActualAmount < 0)
                            meetActualAmount= 0;

                        break;
                }

                updateGoalFields();

            }

            function showGoalViewCard(){
                
                
                setupGoalViewCard();
                
                
                fixGridHeight= document.getElementById("fix-grid").clientHeight;
                console.log("old height: " + fixGridHeight);

                var cardSequence= [];
                cardSequence.push({ e: $('#goal-settings-card'), p: 'transition.slideDownBigOut', o: {display: 'none'}});
                cardSequence.push({ e: $('#goal-view-card'), p: 'transition.slideUpBigIn', o: {display: 'flex',
                complete: calendarFixedAnimation}});

                //mobile glitch fix for animation
                if($(window).width() <= 479)
                {
                    var gridHeight= document.getElementById("fix-grid").clientHeight;
                    document.getElementById("fix-grid").style.height= gridHeight + "px";
                }

                $.Velocity.RunSequence(cardSequence);
            }

            function showGoalSettingsCard(){

                fixGridHeight= document.getElementById("fix-grid").clientHeight;
                console.log("old height: " + fixGridHeight);

                var cardSequence= [];
                cardSequence.push({ e: $('#goal-view-card'), p: 'transition.slideDownBigOut', o: {display: 'none'}});
                cardSequence.push({ e: $('#goal-settings-card'), p: 'transition.slideUpBigIn', o: {display: 'flex',                                                                                                   complete: calendarFixedAnimation}});

                //mobile glitch fix for animation
                if($(window).width() <= 479)
                {
                    var gridHeight= document.getElementById("fix-grid").clientHeight;
                    document.getElementById("fix-grid").style.height= gridHeight + "px";
                }

                $.Velocity.RunSequence(cardSequence);
            }

            function calendarFixedAnimation(){
                if($(window).width() <= 479)
                {
                    document.getElementById("fix-grid").style.height= "auto";

                    var newFixGridHeight= document.getElementById("fix-grid").clientHeight;
                    console.log("new height: " + newFixGridHeight);

                    if(newFixGridHeight > fixGridHeight)
                        $('#calendar-card').velocity('transition.slideDownIn', {display: 'flex'});
                    else if(newFixGridHeight < fixGridHeight)
                        $('#calendar-card').velocity('transition.slideUpIn', {display: 'flex'});

                }
            }

            function setupGoalViewCard(){


                exerciseGoal = $("#slide_01").val();
                walkGoal = $("#slide_02").val();
                meetGoal = $("#slide_03").val();
                
                //update visualized values
                sendExerciseGoalsToContext(exerciseGoal);
                sendWalkGoalToContext(walkGoal);
                sendMeetGoalToContext(meetGoal);
                updateGoalFields();
            }

            function setupGoalSettingsCard(){
                
            }
            
            function updateIcons()
            {
                console.log("INSIDE ICONS", exerciseGoal, actualExercise);
                
                
                if(actualExercise >= (exerciseGoal * 60))
                    document.getElementById("exercise-result-icon").textContent= "done_all";
                else
                    document.getElementById("exercise-result-icon").textContent= "hourglass_empty";
                
                  if(actualWalk >= (walkGoal * 60))
                    document.getElementById("walk-result-icon").textContent= "done_all";
                else
                    document.getElementById("walk-result-icon").textContent= "hourglass_empty";
                
                if(actualMeet >= meetGoal)
                    document.getElementById("meet-result-icon").textContent= "done_all";
                else
                    document.getElementById("meet-result-icon").textContent= "hourglass_empty";


            }

            /*update actual and goal values, on DB too*/
            function updateGoalFields(){
                //exercise goal
                //document.getElementById("exercise-actual-goal-text").textContent= exerciseActualAmount + " / " + exerciseGoal + " " + exerciseString;
                
                getCompletedActivityFromContext(updateIcons);
                
              


                //walk goal
               // document.getElementById("walk-actual-goal-text").textContent= walkActualAmount + " / " + walkGoal + " " + stepsString;
             
              

                //meet goal
                //if(meetGoal >= 6)
                  //  document.getElementById("meet-actual-goal-text").textContent= meetActualAmount + " / " + meetGoal + " or more activities"; //TODO richiedere traduzione "or more persons"
                //else
                  //  document.getElementById("meet-actual-goal-text").textContent= meetActualAmount + " / " + meetGoal + " " + meetString;

             
                
                //update goals on DB
            
                //sendStepGoalToContext(walkGoal);

                
                //Send goals to context manager
                
            }





        </script>




    </head>
    <body>



        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_PLAN);?></span>
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
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
                    <!--                    <a class="mdl-navigation__link" href="fitness.php"><i class="material-icons">fitness_center</i><?php echo(ENTRY_FITNESS);?></a>
<a class="mdl-navigation__link" href="diet.php"><i class="material-icons">restaurant</i><?php echo(ENTRY_DIET);?></a>
<a class="mdl-navigation__link" href="services.php"><i class="material-icons">local_grocery_store</i><?php echo(ENTRY_SERVICES);?></a>-->
                    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
                    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="login.php?notify=LOGOUT"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">




                    <!-- Your content goes here -->
                    <div class="mdl-grid">


                        <div id="fix-grid" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-phone mdl-cell--3-col-tablet no-stretch">


                            <div id="goal-settings-card" class="goal-settings-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">
                                <div class="mdl-card__title">
                                    <h6 class="mdl-card__title-text">
                                        <?php echo(PLAN_SETGOALS_TITLE);?>
                                    </h6>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">

                                    <div class="card-choice-group">
                                        <div class="card-group-label"><?php echo(PLAN_SETGOALS_EXERCISE.':');?></div>
                                        <input class="mdl-slider mdl-js-slider" type="range"
                                               min="0" max="28" tabindex="0" id = "slide_01">
                                        <form action="">
                                            <div class="mdl-textfield mdl-js-textfield" id="text_01">
                                                <input class="mdl-textfield__input" type="text" id="inp_text_01" >
                                            </div>
                                        </form>
                                    </div>

                                    <div class="card-choice-group">
                                        <div class="card-group-label"><?php echo(PLAN_SETGOALS_WALK.':');?></div>
                                        <input class="mdl-slider mdl-js-slider" type="range"
                                               min="0" max="28" tabindex="0" id = "slide_02">
                                        <form action="">
                                            <div class="mdl-textfield mdl-js-textfield" id="text_02">
                                                <input class="mdl-textfield__input" type="text" id="inp_text_02" >
                                            </div>
                                        </form>
                                    </div>


                                    <div class="card-choice-group">

                                        <div class="card-group-label"><?php echo(PLAN_SETGOALS_SOCIAL_ACTIVITY.':');?></div>
                                        <input class="mdl-slider mdl-js-slider" type="range"
                                               min="0" max="42" tabindex="0" id = "slide_03">
                                        <form action="">
                                            <div class="mdl-textfield mdl-js-textfield" id="text_03">
                                                <input class="mdl-textfield__input" type="text" id="inp_text_03" >
                                            </div>
                                        </form>

                                    </div>

                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect simple-button" onclick="showGoalViewCard()">
                                        Ok
                                    </a>

                                </div>
                            </div>



                            <div id="goal-view-card" class="goal-view-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">
                                <div class="mdl-card__title">
                                    <h6 class="mdl-card__title-text"><?php echo(PLAN_GOALS_TITLE);?></h6>
                                </div>
                                <div class="mdl-card__supporting-text mdl-card--expand">
                                    <ul class="mdl-list" onclick="showGoalSettingsCard()">
                                        <li class="mdl-list__item mdl-list__item--two-line">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">fitness_center</i>
                                                <span>
                                                    <?php echo(PLAN_GOALS_EXERCISE);?>
                                                </span>
                                                <p class="mdl-list__item-sub-title">
                                                <span id="actual_exercise_text"></span>
                                                <span>/</span>
                                                <span id="exercise_goal_text"></span>
                                                    <span> <?php echo(PLAN_GOALS_HOURS);?></span>    
                                                </p>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i id="exercise-result-icon" class="material-icons">done_all</i></a>
                                            </span>
                                        </li>
                                        <li class="mdl-list__item mdl-list__item--two-line">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">directions_walk</i>
                                                <span>
                                                    <?php echo(PLAN_GOALS_WALK);?>
                                                </span>
                                                <p class="mdl-list__item-sub-title">
                                                <span id="actual_walk_text"></span>
                                                <span>/</span>
                                                <span id="walk_goal_text"></span> 
                                                    <span> <?php echo(PLAN_GOALS_HOURS);?></span>
                                                </p>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i id="walk-result-icon" class="material-icons">hourglass_empty</i></a>
                                            </span>
                                        </li>
                                        <li class="mdl-list__item mdl-list__item--two-line">
                                            <span class="mdl-list__item-primary-content">
                                                <i class="material-icons mdl-list__item-avatar">person</i>
                                                <span>
                                                    <?php echo(PLAN_GOALS_SOCIAL_ACTIVITY);?>
                                                </span>
                                                <p class="mdl-list__item-sub-title">
                                                <span id="actual_meet_text"></span>
                                                <span>/</span>
                                                <span id="meet_goal_text"></span>
                                                <span> <?php echo(PLAN_GOALS_SOCIAL_ACTIVITY);?></span>
                                                </p>
                                            </span>
                                            <span class="mdl-list__item-secondary-content">
                                                <a class="mdl-list__item-secondary-action" href="#"><i id="meet-result-icon" class="material-icons">hourglass_empty</i></a>
                                            </span>
                                        </li>
                                    </ul>

                                </div>


                                <div class="mdl-card__actions mdl-card--border">
                                    <!--
<a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" onclick="showGoalSettingsCard()">
<?php echo(PLAN_GOALS_MODIFY);?>
</a>
-->
                                </div>
                            </div>


                        </div>



                        <!--TRY NEW CALENDAR HERE -->

                        <div id="fix-grid" class="mdl-grid mdl-cell mdl-cell--8-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">


                            <div class="calendarContOuter">
                            </div>


                        </div>  





                    </div>

                    </main>
                </div>  


            <!--CREATE EVENT IN NEW CALENDAR -->
            <div id="modal-form" class="modal fade">

                <div class="modal-dtpicker"></div>

                <div class="modal-dialog">
                    <div class="modal-content">

                        <form>

                            <div class="modal-header mdl-card">
                                Add event
                            </div>

                            <div class="modal-body">

                                <div id="ipAlertTitle" class="alert alert-danger" role="alert">
                                    Title should not be Empty
                                </div>

                                <div id="ipTitle-group" class="form-group">
                                    <label for="ipTitle">Title : </label>
                                    <input type="text" class="form-control" id="ipTitle" placeholder="Title">
                                </div>

                                <div id="ipAllDay-group" class="checkbox">
                                    <label>
                                        <input id="ipAllDay" type="checkbox" checked> All Day
                                    </label>
                                </div>

                                <div id="ipAlertStartEnd" class="alert alert-danger" role="alert">
                                    Start DateTime should be earlier than End DateTime
                                </div>

                                <div id="ipStart-group" class="form-group">
                                    <label for="ipStart">Start Date : </label>
                                    <input type="text" class="form-control" id="ipStart" data-field="date" placeholder="Start">
                                </div>

                                <div id="ipEnd-group" class="form-group">
                                    <label for="ipStart">End Date : </label>
                                    <input type="text" class="form-control" id="ipEnd" data-field="date" placeholder="End">
                                </div>

                                <div id="ipDesc-group" class="form-group">
                                    <label for="ipDesc">Description : </label>
                                    <textarea class="form-control" rows="3" id="ipDesc" placeholder="Description"></textarea>
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-default form-btn" data-dismiss="modal">Close</button>
                                <button id="submit" type="button" class="btn btn-default form-btn">Submit</button>
                            </div>

                        </form>

                    </div>
                </div>
            </div>


            <!-- TIMEPICKER MODAL -->
            <div id="timepicker-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="interval-picker-dialog mdl-card">
                            <div class="mdl-card__title">
                                <h6 id="timepicker-modal-title" class="mdl-card__title-text"></h6>

                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">

                                <div class="interval-picker-container">
                                    <div class="interval-picker-hint">
                                        <?php echo(PLAN_SETEVENT_HOUR);?>
                                    </div>
                                    <div class="interval-picker-box">
                                        <div class="interval-picker-element prev-button" onclick="hour_prev()"><i class="material-icons">keyboard_arrow_up</i></div>
                                        <div id="hour_prev" class="interval-picker-element prev">0</div>
                                        <div id="hour_selected" class="interval-picker-element selected">1</div>
                                        <div id="hour_next" class="interval-picker-element next">2</div>
                                        <div class="interval-picker-element next-button" onclick="hour_next()"><i class="material-icons">keyboard_arrow_down</i></div>
                                    </div>

                                    <div class="interval-picker-speratator">:</div>

                                    <div class="interval-picker-box">
                                        <div class="interval-picker-element prev-button" onclick="minute_prev()"><i class="material-icons">keyboard_arrow_up</i></div>
                                        <div id="minute_prev" class="interval-picker-element prev">16</div>
                                        <div id="minute_selected" class="interval-picker-element selected">17</div>
                                        <div id="minute_next" class="interval-picker-element next">18</div>
                                        <div class="interval-picker-element next-button" onclick="minute_next()"><i class="material-icons">keyboard_arrow_down</i></div>
                                    </div>
                                    <div class="interval-picker-hint">
                                        <?php echo(PLAN_SETEVENT_MIN);?>
                                    </div>

                                </div>

                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div id="timepicker-modal-error" class="interval-picker-modal-error"><i class="material-icons">warning</i>Time interval must be greater then 0'</div>

                                <a id="timepicker-button-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                    Cancel
                                </a>
                                <a id="timepicker-modal-OK" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                    OK
                                </a>

                            </div>
                        </div>

                    </div>

                </div>
            </div>



            <!-- INTERVAL PICKER MODAL -->
            <div id="interval-picker-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="interval-picker-dialog mdl-card">
                            <div class="mdl-card__title">
                                <h6 id="interval-picker-modal-title" class="mdl-card__title-text"></h6>

                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">

                                <div class="interval-picker-container">

                                    <div class="interval-picker-box single-box">
                                        <div id="interval_prev_button" class="interval-picker-element prev-button"><i class="material-icons">keyboard_arrow_up</i></div>
                                        <div id="interval_prev" class="interval-picker-element prev">3000</div>
                                        <div id="interval_selected" class="interval-picker-element selected">0</div>
                                        <div id="interval_next" class="interval-picker-element next">100</div>
                                        <div id="interval_next_button" class="interval-picker-element next-button"><i class="material-icons">keyboard_arrow_down</i></div>
                                    </div>
                                    <div id="interval-picker-hint" class="interval-picker-hint">Steps</div>

                                </div>

                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div id="interval-picker-modal-error" class="interval-picker-modal-error"><i class="material-icons">warning</i>Value must be greater then 0</div>

   

                        <a id="interval-picker-button-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                            <?php echo(CANCEL_BUTTON);?>

                        </a>
                        <a id="interval-picker-modal-OK" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                            OK
                        </a>

                    </div>
                </div>

            </div>

        </div>
        </div>



    <!-- EVENT DESCRIPTION MODAL -->
    <div id="event-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="event-modal mdl-card"
                     onclick="window.location='#';">
                    <div id="event-modal-title" class="mdl-card__title">
                        <div id="event-modal-title-text" class="mdl-card__title-text">Exercise of 04/10/2016</div>
                        <div class="mdl-layout-spacer"></div>
                        <div class="time-interval">
                            <div id="event-modal-interval" class="time-interval-text">1h 21'</div>
                        </div>
                    </div>
                    <div class="mdl-card__actions mdl-card--border">

                        <a id="event-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                            <?php echo(REMOVE_EVENT_BUTTON);?>
                        </a>
                        <a id="event-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                            EVENT <?php echo(DONE_BUTTON);?>
                        </a>
                    </div>
                    <!--			<div class="mdl-card__menu">
<button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
<i class="material-icons">assignment</i>
</button>
</div>-->
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



