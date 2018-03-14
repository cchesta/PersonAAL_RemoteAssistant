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






        <!-- ADAPTATION SCRIPTS -->
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>
        <script src="./js/plan.js"></script>


        <!-- NEW CALENDAR TO SUPPORT START AND END TIME OF ACTIVITIES -->

        <script type="text/javascript" src="CalenStyle-master/demo/js/jquery-1.11.1.min.js"></script>
        <script type="text/javascript" src="CalenStyle-master/demo/js/jquery-ui-custom-1.11.2.min.js"></script>
        <link rel="stylesheet" type="text/css" href="CalenStyle-master/demo/css/jquery-ui-custom-1.11.2.min.css" />
        <script type="text/javascript" src="CalenStyle-master/demo/js/DateTimePicker.js"></script>
        <link rel="stylesheet" type="text/css" href="CalenStyle-master/demo/css/DateTimePicker.css" />
        <link rel="stylesheet" type="text/css" href="CalenStyle-master/src/calenstyle.css" />
        <link rel="stylesheet" type="text/css" href="CalenStyle-master/src/calenstyle-jquery-ui-override.css" />
        <link rel="stylesheet" type="text/css" href="CalenStyle-master/src/calenstyle-iconfont.css" />
        <script type="text/javascript" src="CalenStyle-master/src/calenstyle.js"></script>
        <script type="text/javascript" src="CalenStyle-master/demo/js/CalJsonGenerator.js"></script>
        <link rel="stylesheet" href="ripjar-material-datetime-picker/dist/material-datetime-picker.css">
        


        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>


        <!--STEPPER TO ADD AN ACTIVITY TO CALENDAR-->

        <link rel="stylesheet" href="mdl-stepper-master/stepper.css">
        <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
        <script src="mdl-stepper-master/stepper.js"></script>
        <link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>



        <!--TIME PICKER FOR CALENDAR-->
        <script src="https://unpkg.com/babel-polyfill@6.2.0/dist/polyfill.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/rome/2.1.22/rome.standalone.js"></script>
        <script src="ripjar-material-datetime-picker/dist/material-datetime-picker.js" charset="utf-8"></script>






        <style type="text/css">

            .mdl-dialog {
                width: 95%;
            }

            .mdl-step {
                height: auto;
            }


            .calendarContOuter
            {			
                width: 100%;
                height: 600px;
                margin: 0px auto;

                font-size: 14px;
            }

            .cElemDatePicker
            {
                font-size: 14px;
            }

            #ipAlertStartEnd, #ipAlertTitle
            {
                display:none;
            }

        </style>



        <!--        script for tables-->
        <script>

            //goals
            var exerciseGoal;
            var walkGoal;
            var meetGoal;

            var exerciseActualAmount;
            var walkActualAmount;
            var meetActualAmount;


            var startDate;
            var endDate;
            var allDay;

            var dialog;
            
            var SocialColor = 'A04220'; 
            var WalkColor = '105924';
            var ExerciseColor = '3568BA' ;

            var SocialIcon = 'cs-icon-Meeting';
            var WalkIcon = 'cs-icon-Running';
            var ExerciseIcon = 'cs-icon-Gym';

            var stepsString= "<?php echo(PLAN_GOALS_MIN);?>";
            var exerciseString= "<?php echo(PLAN_GOALS_MIN);?>";
            var meetString= "<?php echo(PLAN_GOALS_ACTIVITY);?>";


            //animation fix for mobile
            var fixGridHeight;



            var calendar, sInputTZOffset = "-00:00";



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


                //SLIDER FOR SETTING GOALS
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

                //SLIDER FOR ADVANCED OPTIONS
                $('#slide_as').on('input',function(){
                    $("#text_as").get(0).MaterialTextfield.change(this.value);
                });
                $('#inp_text_as').keyup(function() {
                    $("#slide_as").get(0).MaterialSlider.change($( '#inp_text_as').val());
                    console.dir($('#slide_as'));
                });


                //GET GOALS FROM CONTEXT MANAGER

                getMeetGoalFromContext(getWalkGoalFromContext,getExerciseGoalFromContext,updateGoalFields);


                $(".calendarContOuter").CalenStyle({

                    inputTZOffset: '',
                    outputTZOffset: '',

                    initialize: function() {
                        calendar = this;
                        getActivity(addActivitiesToCalendar);


                    },

                    calDataSource: [{
                        sourceId: "cal1",
                        sourceFetchType: "ALL",
                        sourceType: "JSON",
                        source: {
                            
                        }
                    }],


                    cellClicked: function(sView, dSelectedDateTime, bIsAllDay, pClickedAt) {

                        var dEndDateTime, sStartDateTime, sEndDateTime;
                        if(bIsAllDay)
                        {
                            dEndDateTime = new Date(dSelectedDateTime);
                            dEndDateTime.setDate(dSelectedDateTime.getDate() + (calendar.setting.allDayEventDuration - 1));

                            sStartDateTime = calendar.getDateInFormat({"date": dSelectedDateTime}, "dd-MM-yyyy", false, false);
                            sEndDateTime = calendar.getDateInFormat({"date": dEndDateTime}, "dd-MM-yyyy", false, false);
                        }
                        else
                        {
                            dEndDateTime = new Date(dSelectedDateTime.getTime() + (calendar.setting.eventDuration * 6E4));

                            sStartDateTime = calendar.getDateInFormat({"date": dSelectedDateTime}, "dd-MM-yyyy HH:mm", calendar.setting.is24Hour, false);
                            sEndDateTime = calendar.getDateInFormat({"date": dEndDateTime}, "dd-MM-yyyy HH:mm", calendar.setting.is24Hour, false);
                        }

                        if(bIsAllDay)
                            document.getElementById('ipAllDay').parentElement.MaterialCheckbox.check();
                        else
                            document.getElementById('ipAllDay').parentElement.MaterialCheckbox.uncheck();

                        $("#aStart").val(sStartDateTime);
                        $("#aEnd").val(sEndDateTime);

                        console.log(dSelectedDateTime);

                        startDate = sStartDateTime;

                        endDate = sEndDateTime;
                        allDay = bIsAllDay;
                        console.log("cellCliked event");
                        dialog = document.querySelector('dialog');
                        initDialog();
                        dialog.showModal();
                    }

                });

            });

            function addActivitiesToCalendar(activitiesArray){
                var icon;
                var color;
                for(var i = 0; i< activitiesArray.length; i++){
                switch(activitiesArray[i].type){
                    case 'Social':
                        color = SocialColor;
                        icon = SocialIcon;
                        break;
                        
                    case 'Walk':
                        color = WalkColor;
                        icon = WalkIcon;
                        break;
                        
                    case 'Exercise':
                        color = ExerciseColor;
                        icon = ExerciseIcon;
                        break;
                }
                    var activityAddedd = [{
                    "id": activitiesArray[i].activityId,
                    "isAllDay": activitiesArray[i].all_day == 0? false: true ,
                    "start": new Date(activitiesArray[i].start_date),
                    "end": new Date(activitiesArray[i].end_date),
                    "tag": activitiesArray[i].type,
                    "title": activitiesArray[i].title,
                    "description": activitiesArray[i].intensity,
                    "singleColor": color,
                    "icon": icon,
                  
                }];
                    
                    //console.log("ACTIVITY_DB: ", activityAddedd);
                calendar.addEventsForSource(activityAddedd, "cal1");
                calendar.refreshView();
                }

                





                //getActivityFromLastAccess()

            }  

            
            
            // Dialog scripts
            $(function() {
                dialog = document.querySelector('dialog');

                $('#dialogSubmit').on('click', function() {
                    var activityType = $("[name=aType]").filter(':checked').val();
                    var activityTitle = $('#aName').val();
                    var activityIntensity;
                    switch(activityType){
                        case "Exercise":
                            activityIntensity = $("[name = exerciseintensity]").filter(':checked').val();
                            break;
                        case 'Walk':
                            activityIntensity = $("#slide_as").val();
                            break;
                        case "Social":
                            activityIntensity = $("[name = socialIntensity]").filter(':checked').val();

                            break;
                    }



                    dialog.close();

                    console.log("Start_time: ", startDate)
                    // Call the DB insert function here and receive the id of the inserted activity, which is used in the activity string sent to the calendar
                    addActivity(activityTitle,startDate,endDate,(allDay ? 1: 0),0,activityType,activityIntensity,addActivityToCalendar);

                });

                $('#dialogCancel').on('click', function() {
                    dialog.close();
                });
            });



            function addActivityToCalendar(activityId){
                var activityType = $("[name=aType]").filter(':checked').val();
                var activityTitle = $('#aName').val();
                var activityIntensity;
                var color;
                var icon;
                switch(activityType){
                    case "Exercise":
                        activityIntensity = $("[name = exerciseintensity]").filter(':checked').val();
                        color = ExerciseColor;
                        icon = ExerciseIcon;
                        break;
                    case 'Walk':
                        activityIntensity = $("#slide_as").val();
                        color = WalkColor;
                        icon = WalkIcon;
                        break;
                    case "Social":
                        activityIntensity = $("[name = socialIntensity]").filter(':checked').val();
                        color = SocialColor;
                        icon = SocialIcon;
                        break;
                }

                var activity = [{
                    "id": activityId, // replace with value returned from DB
                    "isAllDay": allDay, // Optional
                    "start": (allDay? startDate +  ' 00:00': startDate),
                    "end": (allDay? endDate + ' 00:00': endDate),
                    "tag": activityType,
                    "title": activityTitle,
                    "description": activityIntensity, 
                    "singleColor": color,
                    "icon": icon,
                }];
                console.log("ACTIVITY_CAL" , activity);
                calendar.addEventsForSource(activity, "cal1");
                calendar.refreshView();

                ClearDialogFields();

            }



            function initDialog() {
                var element = document.querySelector('.mdl-stepper');
                if (!element) return false;
                var stepper = element.MaterialStepper;
                stepper.goto(1);

                $('#aStart')[0].parentElement.MaterialTextfield.change(startDate);
                $('#aEnd')[0].parentElement.MaterialTextfield.change(endDate);
                var bIsAllDay = $("#ipAllDay").is(':checked');


            };

            function ClearDialogFields() {
                $('#aStart')[0].parentElement.MaterialTextfield.change("");
                $('#aEnd')[0].parentElement.MaterialTextfield.change("");
                $('#aName')[0].parentElement.MaterialTextfield.change("");
                var activityTypeOptions = $("[name=aType]");
                for (var i = 0; i < activityTypeOptions.length; i++) {
                    activityTypeOptions[i].parentNode.MaterialRadio.uncheck();
                }
                document.getElementById('step1').classList.remove('mdl-step--completed');
                document.getElementById('step2').classList.remove('mdl-step--completed');
                document.getElementById('step3').classList.remove('mdl-step--completed');
            };

            // Stepper scripts
            function moveToNext() {
                var element = document.querySelector('.mdl-stepper');
                if (!element) return false;
                var stepper = element.MaterialStepper;
                stepper.goto(stepper.getActiveId() + 1);
                if (stepper.getActiveId() == 3) {
                    var activityTypeOptions = $("[name=aType]");
                    var activityType = activityTypeOptions.filter(':checked').val();
                    switch (activityType) {
                        case "Exercise":
                            $('#advancedsteps').hide();
                            $('#advancedexercise').show();
                            $('#advancedsocial').hide();
                            break;
                            
                            
                        case 'Walk':
                            $('#advancedsteps').show();
                            $('#advancedexercise').hide();
                            $('#advancedsocial').hide();
                        
                            var bIsAllDay = $("#ipAllDay").is(':checked'),
                                dStartDateTime = parseDateInFormat(bIsAllDay, $("#aStart").val()),
                                dEndDateTime = parseDateInFormat(bIsAllDay, $("#aEnd").val());

                            var dif = dEndDateTime.getTime() - dStartDateTime.getTime();
                            var result = Math.round(dif/60000);
                            
                            document.getElementById('slide_as').max = 100 * (result);
                            document.getElementById('slide_as').MaterialSlider.change(0);
                            //$("#slide_as").max= 100* (difMins);
                            $('#advancedexercise').hide();
                            $('#advancedsteps').show();
                            $('#advancedsocial').hide();
                            break;
                            
                            
                        case "Social":
                            $('#advancedsteps').hide();
                            $('#advancedexercise').hide();
                            $('#advancedsocial').show();
                            break;

                    }
                }
            };

            function moveToPrevious() {
                var element = document.querySelector('.mdl-stepper');
                if (!element) return false;
                var stepper = element.MaterialStepper;
                stepper.goto(stepper.getActiveId() - 1);
            };

            function enableNextStep() {
                if ($('#aName').val().length > 0) {
                    $('#step2Button').removeAttr('disabled');
                    $('#dialogSubmit').removeAttr('disabled');
                }
            };






            function validateAllDayChecked()
            {
                console.log("validateAllDayChecked " + ($("#ipAllDay").is(':checked')));
                if($("#ipAllDay").is(':checked'))
                {
                    $("#ipStart-group label").html("Start Date : ");
                    $("#ipEnd-group label").html("End Date : ");

                    $("#ipStart, #ipEnd").data("field", "date");

                    var sDateTimeRegex = /^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))(\s)([0-2]{1}[0-9]{1}):([0-6]{1}[0-9]{1})/;
                    var sDateTimeStart = $("#ipStart").val(),
                        sArrDateTimeStart = sDateTimeStart.match(sDateTimeRegex),
                        sDateTimeEnd = $("#ipEnd").val(),
                        sArrDateTimeEnd = sDateTimeEnd.match(sDateTimeRegex);
                    if(sArrDateTimeStart != null)
                        $("#ipStart").val(sDateTimeStart.split(" ")[0]);
                    if(sArrDateTimeEnd != null)
                        $("#ipEnd").val(sDateTimeEnd.split(" ")[0]);
                }
                else
                {
                    $("#ipStart-group label").html("Start Date Time : ");
                    $("#ipEnd-group label").html("End Date Time : ");

                    $("#ipStart, #ipEnd").data("field", "datetime");

                    var sDateTimeRegex = /^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))(\s)([0-2]{1}[0-9]{1}):([0-6]{1}[0-9]{1})/;
                    var sDateTimeStart = $("#ipStart").val(),
                        sArrDateTimeStart = sDateTimeStart.match(sDateTimeRegex),
                        sDateTimeEnd = $("#ipEnd").val(),
                        sArrDateTimeEnd = sDateTimeEnd.match(sDateTimeRegex);
                    console.log("Arrays : " + sDateTimeStart + " " + sDateTimeEnd);
                    console.log(sArrDateTimeStart);
                    console.log(sArrDateTimeEnd);
                    if(sArrDateTimeStart == null)
                        $("#ipStart").val(sDateTimeStart + " 00:00");
                    if(sArrDateTimeEnd == null)
                        $("#ipEnd").val(sDateTimeEnd + " 00:00");
                }
            }

            function parseDateInFormat(bIsAllDay, sDateTime)
            {
                var dDateTime;
                if(bIsAllDay)
                {
                    var sArrDateTime = sDateTime.match(/^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))/);
                    console.log(sArrDateTime);
                    dDateTime = new Date(sArrDateTime[5], sArrDateTime[3] - 1, sArrDateTime[1], 0, 1, 0, 0);
                }
                else
                {
                    var sArrDateTime = sDateTime.match(/^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))(\s)([0-2]{1}[0-9]{1}):([0-6]{1}[0-9]{1})/);
                    console.log(sArrDateTime);
                    dDateTime = new Date(sArrDateTime[5], sArrDateTime[3] - 1, sArrDateTime[1], sArrDateTime[7], sArrDateTime[8], 0, 0);
                }
                return dDateTime;
            }



            //PICKER TO DEFINE START DATE

            function showTimePickerStart(){
                dialog = document.querySelector('dialog');
                dialog.close();
                var bIsAllDay = $("#ipAllDay").is(':checked');
                var dStartDateTime = parseDateInFormat(bIsAllDay, $("#aStart").val());

                var input1 = document.querySelector('#aStart');                    
                var picker = new MaterialDatetimePicker({
                    default: dStartDateTime,
                    format:'YYYY-MM-DD HH:mm:ss'
                })
                .on('open',() => {if(!bIsAllDay)
                    picker.clickShowClock()})
                .on('submit', (val) => {
                    var sDateTest = parseDateInFormat(bIsAllDay, val.format("DD-MM-YYYY HH:mm"));
                    var eDateTest = parseDateInFormat(bIsAllDay, $("#aEnd").val());
                    if(calendar.compareDateTimes(sDateTest, eDateTest)  <= 0){
                    input1.value = val.format("DD-MM-YYYY HH:mm");
                    startDate = val.format("DD-MM-YYYY HH:mm");
                        document.getElementById('aStart').parentElement.classList.remove('is-invalid');
                    }
                    else{
                        document.getElementById('aStart').parentElement.className+=' is-invalid';
                    }
                })
                .on('close', () => dialog.showModal());

                picker.open();

            }




            //PICKER TO DEFINE END DATE
            function showTimePickerEnd(){       
                dialog = document.querySelector('dialog');
                dialog.close();
                var bIsAllDay = $("#ipAllDay").is(':checked');
                var dEndDateTime = parseDateInFormat(bIsAllDay, $("#aEnd").val());
                var input2 = document.querySelector('#aEnd'); 
                var picker = new MaterialDatetimePicker({
                    default: dEndDateTime,
                    format:'YYYY-MM-DD HH:mm:ss'
                })
                .on('open',() => picker.clickShowClock())
                .on('submit', (val) => {
                    var sDateTest = parseDateInFormat(bIsAllDay, $("#aStart").val());
                    var eDateTest = parseDateInFormat(bIsAllDay, val.format("DD-MM-YYYY HH:mm"));
                    if(calendar.compareDateTimes(sDateTest, eDateTest)  <= 0){
                    input2.value = val.format("DD-MM-YYYY HH:mm");
                    endDate = val.format("DD-MM-YYYY HH:mm");
                        document.getElementById('aStart').parentElement.classList.remove('is-invalid');
                    }
                    else{
                        document.getElementById('aEnd').parentElement.className+=' is-invalid';
                    }
                    
                })
                .on('close', () => dialog.showModal());

                picker.open();

            }


            function validateAllDayChecked()
            {
                console.log("validateAllDayChecked " + ($("#ipAllDay").is(':checked')));
                if($("#ipAllDay").is(':checked'))
                {
                    allDay = true;

                    $("#aStart, #aEnd").data("field", "date");

                    var sDateTimeRegex = /^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))(\s)([0-2]{1}[0-9]{1}):([0-6]{1}[0-9]{1})/;
                    var sDateTimeStart = $("#aStart").val(),
                        sArrDateTimeStart = sDateTimeStart.match(sDateTimeRegex),
                        sDateTimeEnd = $("#aEnd").val(),
                        sArrDateTimeEnd = sDateTimeEnd.match(sDateTimeRegex);
                    if(sArrDateTimeStart != null)
                        $("#aStart").val(sDateTimeStart.split(" ")[0]);
                    if(sArrDateTimeEnd != null)
                        $("#aEnd").val(sDateTimeEnd.split(" ")[0]);
                }
                else
                {
                    allDay = false;
                    $("#aStart, #aEnd").data("field", "datetime");

                    var sDateTimeRegex = /^([0-3]{1}[0-9]{1})(-([0-1]{1}[0-9]{1}))(-([0-9]{4}))(\s)([0-2]{1}[0-9]{1}):([0-6]{1}[0-9]{1})/;
                    var sDateTimeStart = $("#aStart").val(),
                        sArrDateTimeStart = sDateTimeStart.match(sDateTimeRegex),
                        sDateTimeEnd = $("#aEnd").val(),
                        sArrDateTimeEnd = sDateTimeEnd.match(sDateTimeRegex);

                    if(sArrDateTimeStart == null)
                        $("#aStart").val(sDateTimeStart + " 00:00");
                    if(sArrDateTimeEnd == null)
                        $("#aEnd").val(sDateTimeEnd + " 00:00");
                }
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
                cardSequence.push({ e: $('#goal-settings-card'), p: 'transition.slideUpBigIn', o: {display: 'flex',                                                                                       complete: calendarFixedAnimation}});

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

            /*update actual and goal values,*/
            function updateGoalFields(){


                getCompletedActivityFromContext(updateIcons);

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
                            <!-- CHANGE GOALS-->

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

                            <!--VIEW GOALS/ACTUAL-->

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

                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" onclick="showGoalSettingsCard()">
                                        <?php echo(PLAN_GOALS_MODIFY);?>
                                    </a>

                                </div>
                            </div>



                        </div> 


                        <!--TRY NEW CALENDAR HERE -->

                        <div id="calendar-card" class="calendar-card mdl-cell mdl-cell--8-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch mdl-card mdl-shadow--4dp">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text">Planner&nbsp;</h2>
                                <span class="mdl-card__subtitle-text"> - Click on the calendar to start scheduling an activity</span>
                            </div>

                            <div class="mdl-card__media">
                                <div class="calendarContOuter"></div>
                            </div>
                        </div>





                    </div>

                    <!--END CALENDAR-->
                    <!-- Dialog for scheduling activities -->
                    <dialog class="mdl-dialog" id="stepper">
                        <div class="mdl-dialog__content">
                            <ul class="mdl-stepper mdl-stepper--linear" style="max-width:100%">
                                <li id="step1" class="mdl-step mdl-step--editable">
                                    <span class="mdl-step__label">
                                        <span class="mdl-step__title">
                                            <span class="mdl-step__title-text">Confirm date and time</span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">

                                        <label id="ipAllDay-group" class="mdl-checkbox mdl-js-checkbox">
                                            <input id="ipAllDay" type="checkbox" class = "mdl-checkbox__input" onclick="validateAllDayChecked()"> All Day
                                        </label>
                                        <div id="ipAlertStartEnd" class="alert alert-danger" role="alert">
                                            Start DateTime should be earlier than End DateTime
                                        </div>



                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="aStart" onclick="showTimePickerStart()">
                                            <label class="mdl-textfield__label" for="aStart">Start time</label>
                                            <span class="mdl-textfield__error">Start DateTime should be earlier than End DateTime </span>

                                        </div>


                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">

                                            <input class="mdl-textfield__input" type="text" id="aEnd" onclick="showTimePickerEnd()">
                                            <label class="mdl-textfield__label" for="aEnd">End time</label>
                                            <span class="mdl-textfield__error">Start DateTime should be earlier than End DateTime </span>

                                        </div>




                                    </div>



                                    <div class="mdl-step__actions">
                                        <button class="mdl-button mdl-js-button" onclick="moveToNext()" data-stepper-continue>Continue</button>
                                    </div>
                                </li>
                                <li id="step2" class="mdl-step mdl-step--editable">
                                    <span class="mdl-step__label">
                                        <span class="mdl-step__title">
                                            <span class="mdl-step__title-text">Chose type of activity</span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="aName">
                                            <label class="mdl-textfield__label" for="aName">Activity name</label>
                                        </div>
                                        <div>
                                            <div>
                                                <label for="aWalk" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aWalk" name="aType" value="Walk" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label">Walking</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label for="aExercise" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aExercise" name="aType" value="Exercise" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label">Exercise</span>
                                                </label>
                                            </div>
                                            <div>
                                                <label for="aSocial" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aSocial" name="aType" value="Social" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label">Social activity</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-step__actions">
                                        <button id="step2Button" class="mdl-button mdl-js-button" onclick="moveToNext()" data-stepper-continue disabled>Continue</button>
                                        <button class="mdl-button mdl-js-button" onclick="moveToPrevious()" data-stepper-back>Back</button>
                                    </div>
                                </li>
                                <li id="step3" class="mdl-step mdl-step--editable mdl-step--optional">
                                    <span class="mdl-step__label">
                                        <span class="mdl-step__title">
                                            <span class="mdl-step__title-text">Enter activity details</span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">
                                        <div id="advancedexercise" class="mdl-card__supporting-text mdl-card--expand">
                                            <label class="mdl-layout-title">Intensity Of Exercise : </label>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe1" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="High">
                                                    <span class="mdl-radio__label">High</span>
                                                </label>
                                            </div>


                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe2" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="Moderate">
                                                    <span class="mdl-radio__label">Moderate</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe3" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="Low">
                                                    <span class="mdl-radio__label">Low</span>
                                                </label>
                                            </div>


                                        </div>

                                        <div id="advancedsteps" class="mdl-card__supporting-text mdl-card--expand">
                                            <label class="mdl-layout-title">Number of Steps : </label>

                                            <div class="card-choice-group">

                                                <input class="mdl-slider mdl-js-slider" type="range"
                                                       min="0" max="300" tabindex="0" id = "slide_as" step="100">
                                                <form action="">
                                                    <div class="mdl-textfield mdl-js-textfield" id="text_as">
                                                        <input class="mdl-textfield__input" type="text" id="inp_text_as" >
                                                    </div>
                                                </form>
                                            </div>


                                        </div>


                                        <div id="advancedsocial" class="mdl-card__supporting-text mdl-card--expand">
                                            <label class="mdl-layout-title">Type of Social Activity : </label>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Receive Guests">
                                                    <span class="mdl-radio__label">Receive guest</span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Call Someone">
                                                    <span class="mdl-radio__label">Call someone</span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Visit Someone">
                                                    <span class="mdl-radio__label">Visit someone</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Cinema">
                                                    <span class="mdl-radio__label">Cinema</span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button">
                                                    <span class="mdl-radio__label">Theatre</span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Restaurant">
                                                    <span class="mdl-radio__label">Restaurant</span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Pub">
                                                    <span class="mdl-radio__label">Pub</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Religious">
                                                    <span class="mdl-radio__label">Religious</span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Other">
                                                    <span class="mdl-radio__label">Other</span>
                                                </label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mdl-step__actions">
                                        <button class="mdl-button mdl-js-button" onclick="moveToPrevious()" data-stepper-back>Back</button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mdl-dialog__actions">
                            <button type="button" class="mdl-button" id="dialogSubmit" disabled>Submit</button>
                            <button type="button" class="mdl-button close" id="dialogCancel">Cancel</button>
                        </div>
                    </dialog>








                    </main>
                </div>  















            <!-- EVENT DESCRIPTION MODAL -->
            <div id="activity-modal" class="modal fade" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">

                        <div class="activity-modal mdl-card"
                             onclick="window.location='#';">
                            <div id="activity-modal-title" class="mdl-card__title">
                                <div id="activity-modal-title-text" class="mdl-card__title-text">Exercise of 04/10/2016</div>
                                <div class="mdl-layout-spacer"></div>
                                <div class="time-interval">
                                    <div id="event-modal-interval" class="time-interval-text">1h 21'</div>
                                </div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">

                                <a id="activity-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
                                    NO
                                </a>
                                <a id="activity-modal-done" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" "modal">
                                    OK
                                </a>
                            </div>

                        </div>

                    </div>

                </div>
            </div>






            </body>


        </html>



