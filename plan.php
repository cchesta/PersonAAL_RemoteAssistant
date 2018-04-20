<?php

include 'miscLib.php';
include 'DButils.php';

// Require composer autoloader
 require __DIR__ . DIRECTORY_SEPARATOR . 'login' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';
 require __DIR__ . DIRECTORY_SEPARATOR . 'login' . DIRECTORY_SEPARATOR . 'dotenv-loader.php';

 use Auth0\SDK\Auth0;

 $domain        = getenv('AUTH0_DOMAIN');
 $client_id     = getenv('AUTH0_CLIENT_ID');
 $client_secret = getenv('AUTH0_CLIENT_SECRET');
 $redirect_uri  = getenv('AUTH0_CALLBACK_URL');

 $auth0 = new Auth0([
   'domain' => $domain,
   'client_id' => $client_id,
   'client_secret' => $client_secret,
   'redirect_uri' => $redirect_uri,
   'audience' => 'https://' . $domain . '/userinfo',
   'persist_id_token' => true,
   'persist_access_token' => true,
   'persist_refresh_token' => true,
 ]);

 $userInfo = $auth0->getUser();
 $idtoken = $auth0->getIdToken();

 if(!$userInfo)
 {
    echo("<script>console.log('index: No user info');</script>");
    myRedirect("login.php", TRUE);
 }
 else
 {
     echo("<script>console.log('index user_id: ".$userInfo['sub']."');</script>");
     echo("<script>console.log('index nickname: ".$userInfo['nickname']."');</script>");
     $user = $userInfo['sub'];

     //SESSIONE
//     session_start();
     $_SESSION['personAAL_user'] = $user;
     setLanguage();
 }


//REDIRECT SU HTTPS
//if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//    HTTPtoHTTPS();

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
        <script type="text/javascript">
            var userName = "<?php echo $_SESSION['personAAL_user']?>";
            var token = "<?php echo $idtoken ?>";
            var userId = "<?php echo $userInfo['sub']?>";
        </script>
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>
        <script src="./js/plugins/adaptation/context-data.js"></script>
        <script src="./js/plugins/adaptation/jshue.js"></script>
        <script src="./js/plugins/adaptation/command.js"></script>
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
        <script type="text/javascript" src="CalenStyle-master/i18n/calenstyle-i18n.js"></script>
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
            
            .mdl-step__content {
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
            var activityCard;
            var lastAccessActivitiesArray;
            
            var SocialColor = '#A04220';
            var WalkColor = '#105924';
            var ExerciseColor = '#3568BA' ;

            var SocialIcon = 'cs-icon-Meeting';
            var WalkIcon = 'cs-icon-Running';
            var ExerciseIcon = 'cs-icon-Gym';

            var stepsString= "<?php echo(PLAN_GOALS_MIN);?>";
            var exerciseString= "<?php echo(PLAN_GOALS_MIN);?>";
            var meetString= "<?php echo(PLAN_GOALS_ACTIVITY);?>";


            //animation fix for mobile
            var fixGridHeight;



            var calendar, sInputTZOffset = "-00:00";

            var activityEdit = null;



            $(document).ready(function() {

                //goals
                <?php
//
//                $p = new Plan($_SESSION['personAAL_user']);
//
//                echo("walkActualAmount= ". $p->getActualWalk() .";");
//                echo("exerciseActualAmount= ". $p->getActualExercise() .";");
//                echo("meetActualAmount= ". $p->getActualMeet() .";");
//                echo("walkGoal= ". $p->getWalkGoal() .";");
//                echo("exerciseGoal= ". $p->getExerciseGoal() .";");
//                echo("meetGoal= ". $p->getMeetGoal() .";");
//
                ?>
                        
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

                var sLang = "<?php echo($_SESSION['languages']);?>";
                var i18n = $.CalenStyle.i18n[sLang];
                var oViewDisplayNames = i18n["viewDisplayNames"];

                $(".calendarContOuter").CalenStyle({

                    language: sLang,

                    inputTZOffset: '',
                    outputTZOffset: '',

                    viewsToDisplay : [
                        {
                            "viewName": "DetailedMonthView",
                            viewDisplayName: oViewDisplayNames["DetailedMonthView"]
                        },
                        {
                            "viewName": "WeekView",
                            viewDisplayName: oViewDisplayNames["WeekView"]
                        },
                        {
                            "viewName": "DayView",
                            viewDisplayName: oViewDisplayNames["DayView"]
                        }
                    ],
                    visibleView: "WeekView",

                    businessHoursSource: [
                        {
                            day: 0,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 1,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 2,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 3,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 4,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 5,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        },
                        {
                            day: 6,
                            times: [{startTime: "00:00", endTime: "24:00"}]
                        }

                    ],

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
                    },

                    eventClicked: function (visibleView, sElemSelector, oEvent) {
                        var thisObj = this;
                        console.log(oEvent);
                        showEditDeleteDialog(sElemSelector, oEvent);
                    }

                });

            });

            function showEditDeleteDialog(selector, event) {
                activityEditDeleteCard = document.querySelector('#activityEditDeleteDialog');
                $('#ed_title').html(event.title);
                $('#ed_type').html(event.description);
                $('#ed_start_date').html(event.start.toString());
                $('#ed_end_date').html(event.end.toString());
                switch(event.tag){
                    case "Exercise":
                        $('#activityEditDeleteDialog').css({'background':ExerciseColor});
                        break;
                    case "Walk":
                        $('#activityEditDeleteDialog').css({'background':WalkColor});
                        break;
                    case "Social":
                        $('#activityEditDeleteDialog').css({'background':SocialColor});
                        break;
                }
                activityEditDeleteCard.showModal();

                activityEditDeleteCard.querySelector('#dialogEdit').onclick = function(){
                    console.log("EDIT ACTIVITY: ", selector);
                    activityEditDeleteCard.close();
                    activityEdit = event;
                    dialog = document.querySelector('dialog');
                    initEditDialog();
                    dialog.showModal();
                };

                activityEditDeleteCard.querySelector('#dialogDelete').onclick = function(){
                    console.log("DELETE ACTIVITY: ", selector);
                    deleteActivity(event.id);
                    var sArrRemoveIds = new Array();
                    var oRemove = {
                        removeIds : event.calEventId
                    };
                    sArrRemoveIds.push(oRemove);
                    calendar.removeEvents(sArrRemoveIds);
                    calendar.refreshView();
                    activityEditDeleteCard.close();
                };

                activityEditDeleteCard.querySelector('#dialogCancel').onclick = function(){
                    activityEditDeleteCard.close();
                };

            }


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

                getActivitiesFromLastAccess(fillActivityCard);
            }

            function fillActivityCard(lastAccessActivities){
                lastAccessActivitiesArray =  lastAccessActivities;
                activityCard = document.querySelector('#activityDialog');
                //console.log("activity card: ", activityCard);

                ShowActivityLAstAccess(0);
            }

            function ShowActivityLAstAccess(i){
                let aux = i;
                if(i>=lastAccessActivitiesArray.length){
                    console.log("END: ", i);
                    updateGoalFields();
                    updateLastAccess();
                    return;
                }
                $('#ac_title').html(lastAccessActivitiesArray[i][0]);
                $('#ac_type').html(lastAccessActivitiesArray[i][1]);
                $('#ac_start_date').html(lastAccessActivitiesArray[i][2]);
                switch(lastAccessActivitiesArray[i][1]){
                    case "Exercise":
                        $('#activityDialog').css({'background':ExerciseColor});
                        break;
                    case "Walk":
                        $('#activityDialog').css({'background':WalkColor});
                        break;
                    case "Social":
                        $('#activityDialog').css({'background':SocialColor});
                        break;
                }
                activityCard.showModal();
                console.log("OUTSITE EVENT LISTENER ", i);
                console.log("ARRAY: ",lastAccessActivitiesArray[i] );

                activityCard.querySelector('#dialogNo').onclick = function(){
                    console.log("SAID NO INSIDE ", i);
                    activityCard.close();
                    ShowActivityLAstAccess(i+1);

                };

                activityCard.querySelector('#dialogYes').onclick = function(){
                    var activity_intensity = lastAccessActivitiesArray[i][4];
                    var activity_name = lastAccessActivitiesArray[i][0] ;
                    var activity_type = lastAccessActivitiesArray[i][1];
                    //activity duration
                    var start = new Date(lastAccessActivitiesArray[i][2]);
                    var end = new Date (lastAccessActivitiesArray[i][3]);
                    var difference = end.getTime() - start.getTime();
                    var completed_duration = Math.round(difference/60000);
                    var completed_time = start.getHours() + '.' + ('0'+start.getMinutes()).slice(-2);
                    var completed_timestamp = moment(start).format() ;
                    console.log("SAID YES INSIDE", i);
                    //SEND TO CM
                    sendCompletedActivityToContext(activity_intensity, activity_name,activity_type,completed_duration,completed_time,completed_timestamp);
                    setActivityDone(lastAccessActivitiesArray[i][5]);
                    console.log("ACTIVITY ID YES: ", lastAccessActivitiesArray[i][5]);
                    activityCard.close();
                    ShowActivityLAstAccess(i+1);
                };

            }

            function initEditDialog() {
                if (activityEdit) {
                    var dEndDateTime, sStartDateTime, sEndDateTime;
                    if (activityEdit.isAllDay) {
                        allDay = true;
                        document.getElementById('ipAllDay-group').MaterialCheckbox.check();
                        sStartDateTime = calendar.getDateInFormat({"date": activityEdit.start}, "dd-MM-yyyy", false, false);
                        sEndDateTime = calendar.getDateInFormat({"date": activityEdit.end}, "dd-MM-yyyy", false, false);
                    } else {
                        allDay = false;
                        document.getElementById('ipAllDay-group').MaterialCheckbox.uncheck();
                        sStartDateTime = calendar.getDateInFormat({"date": activityEdit.start}, "dd-MM-yyyy HH:mm", calendar.setting.is24Hour, false);
                        sEndDateTime = calendar.getDateInFormat({"date": activityEdit.end}, "dd-MM-yyyy HH:mm", calendar.setting.is24Hour, false);
                    }
                    document.getElementById('aStart').value = sStartDateTime;
                    document.getElementById('aStart').parentElement.classList.add('is-dirty');
                    document.getElementById('aEnd').value = sEndDateTime;
                    document.getElementById('aEnd').parentElement.classList.add('is-dirty');
                    document.getElementById('aName').value = activityEdit.title;
                    document.getElementById('aName').parentElement.classList.add('is-dirty');
                    startDate = sStartDateTime;
                    endDate = sEndDateTime;
                    switch (activityEdit.tag) {
                        case 'Walk':
                            document.getElementById('aWalk').parentNode.MaterialRadio.check();
                            document.getElementById('slide_as').value = activityEdit.description;
                            document.getElementById('inp_text_as').value = activityEdit.description;
                            document.getElementById('inp_text_as').parentElement.classList.add('is-dirty');
                            break;
                        case 'Exercise':
                            document.getElementById('aExercise').parentNode.MaterialRadio.check();
                            switch (activityEdit.description) {
                                case 'High':
                                    document.getElementById('radioe1').parentNode.MaterialRadio.check();
                                    break;
                                case 'Moderate':
                                    document.getElementById('radioe2').parentNode.MaterialRadio.check();
                                    break;
                                case 'Low':
                                    document.getElementById('radioe3').parentNode.MaterialRadio.check();
                                    break;
                            }
                            break;
                        case 'Social':
                            document.getElementById('aSocial').parentNode.MaterialRadio.check();
                            switch (activityEdit.description) {
                                case 'Receive Guests':
                                    document.getElementById('radio1').parentNode.MaterialRadio.check();
                                    break;
                                case 'Call Someone':
                                    document.getElementById('radio2').parentNode.MaterialRadio.check();
                                    break;
                                case 'Visit Someone':
                                    document.getElementById('radio3').parentNode.MaterialRadio.check();
                                    break;
                                case 'Cinema':
                                    document.getElementById('radio4').parentNode.MaterialRadio.check();
                                    break;
                                case 'Theatre':
                                    document.getElementById('radio5').parentNode.MaterialRadio.check();
                                    break;
                                case 'Restaurant':
                                    document.getElementById('radio6').parentNode.MaterialRadio.check();
                                    break;
                                case 'Pub':
                                    document.getElementById('radio7').parentNode.MaterialRadio.check();
                                    break;
                                case 'Religious':
                                    document.getElementById('radio8').parentNode.MaterialRadio.check();
                                    break;
                                default:
                                    document.getElementById('radio9').parentNode.MaterialRadio.check();
                                    document.getElementById('other_social_activity').value = activityEdit.description;
                                    document.getElementById('other_social_activity').parentElement.classList.add('is-dirty');
                                    document.getElementById('other_social_activity_group').style.display = 'block';
                                    break;
                            }
                            break;
                    };
                }
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
                            if (activityIntensity == "Other") {
                                activityIntensity = document.getElementById('other_social_activity').value;
                            }
                            break;
                    }



                    dialog.close();

                    console.log("Start_time: ", startDate)

                    // Call the DB insert function here and receive the id of the inserted activity, which is used in the activity string sent to the calendar
                    if (activityEdit) {
                        updateActivity(activityTitle, startDate, endDate, (allDay ? 1 : 0), 0, activityType, activityIntensity, activityEdit.id);
                        var oEvent = {
                            "isAllDay": allDay, // Optional
                            "start": (allDay? startDate +  ' 00:00': startDate),
                            "end": (allDay? endDate + ' 00:00': endDate),
                            "tag": activityType,
                            "title": activityTitle,
                            "description": activityIntensity
                        };
                        var oEventToReplace = {
                            replaceId : activityEdit.calEventId,
                            event : [oEvent]
                        }
                        calendar.replaceEvents([oEventToReplace]);
                        calendar.refreshView();
                    }
                    else {
                        addActivity(activityTitle, startDate, endDate, (allDay ? 1 : 0), 0, activityType, activityIntensity, addActivityToCalendar);
                    }

                    activityEdit = null;
                });

                $('#dialogCancel').on('click', function() {
                    dialog.close();
                    activityEdit = null;
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
                if ($('#aName').val().length == 0) {
                    var activityTypeOptions = $("[name=aType]");
                    var activityType = activityTypeOptions.filter(':checked').val();
                    switch (activityType) {
                        case "Exercise":
                            $('#aName')[0].parentElement.MaterialTextfield.change("Exercise");
                            break;
                        case "Walk":
                            $('#aName')[0].parentElement.MaterialTextfield.change("Walk");
                            break;
                        case "Social":
                            $('#aName')[0].parentElement.MaterialTextfield.change("Social");
                            break;
                    };
                };
                $('#step2Button').removeAttr('disabled');
                $('#dialogSubmit').removeAttr('disabled');
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

                if(walkGoal !== 0 && exerciseGoal !== 0 && meetGoal !== 0)
                {
                    $('#goal-settings-card').hide();
                }
                else
                    $('#goal-view-card').hide();
            }

            /*update actual and goal values,*/
            function updateGoalFields(){


                getCompletedActivityFromContext(updateIcons);

            }

            function showOtherTypeSocialInput() {
                if (document.getElementById('radio9').checked) {
                    document.getElementById('other_social_activity_group').style.display = 'block';
                }
                else {
                    document.getElementById('other_social_activity_group').style.display = 'none';
                }
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
		                <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		                <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="huelights.php"><i class="material-icons">flare</i><?php echo(ENTRY_HUELIGHTS);?></a>
                    <a class="mdl-navigation__link" href="logout.php"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
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
                                <h2 class="mdl-card__title-text"><?php echo(PLAN_CALENDAR_TITLE);?>&nbsp;</h2>
                                <span class="mdl-card__subtitle-text"> - <?php echo(PLAN_CALENDAR_INSTRUCTIONS);?></span>
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
                                            <span class="mdl-step__title-text"><?php echo(STEPPER_STEP1);?></span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">

                                        <label id="ipAllDay-group" class="mdl-checkbox mdl-js-checkbox">
                                            <input id="ipAllDay" type="checkbox" class = "mdl-checkbox__input" onclick="validateAllDayChecked()"> <?php echo(STEPPER_STEP1_ALLDAY);?>
                                        </label>
                                        <div id="ipAlertStartEnd" class="alert alert-danger" role="alert">
                                            <?php echo(STEPPER_STEP1_ERROR);?>
                                        </div>



                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="aStart" onclick="showTimePickerStart()">
                                            <label class="mdl-textfield__label" for="aStart"><?php echo(STEPPER_STEP1_START);?></label>
                                            <span class="mdl-textfield__error"><?php echo(STEPPER_STEP1_ERROR);?></span>

                                        </div>


                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">

                                            <input class="mdl-textfield__input" type="text" id="aEnd" onclick="showTimePickerEnd()">
                                            <label class="mdl-textfield__label" for="aEnd"><?php echo(STEPPER_STEP1_END);?></label>
                                            <span class="mdl-textfield__error"><?php echo(STEPPER_STEP1_ERROR);?></span>

                                        </div>




                                    </div>



                                    <div class="mdl-step__actions">
                                        <button class="mdl-button mdl-js-button" onclick="moveToNext()" data-stepper-continue><?php echo(STEPPER_CONTINUE);?></button>
                                    </div>
                                </li>
                                <li id="step2" class="mdl-step mdl-step--editable">
                                    <span class="mdl-step__label">
                                        <span class="mdl-step__title">
                                            <span class="mdl-step__title-text"><?php echo(STEPPER_STEP2);?></span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">
                                        <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                            <input class="mdl-textfield__input" type="text" id="aName">
                                            <label class="mdl-textfield__label" for="aName"><?php echo(STEPPER_STEP2_ACTIVITYNAME);?></label>
                                        </div>
                                        <div>
                                            <p><?php echo(STEPPER_STEP2_ACTIVITYTYPE);?></p>
                                            <div>
                                                <label for="aWalk" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aWalk" name="aType" value="Walk" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP2_ACTIVITYWALK);?></span>
                                                </label>
                                            </div>
                                            <div>
                                                <label for="aExercise" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aExercise" name="aType" value="Exercise" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP2_ACTIVITYEXERCISE);?></span>
                                                </label>
                                            </div>
                                            <div>
                                                <label for="aSocial" class="mdl-radio mdl-js-radio">
                                                    <input type="radio" id="aSocial" name="aType" value="Social" class="mdl-radio__button" onclick="enableNextStep()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP2_ACTIVITYSOCIAL);?></span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mdl-step__actions">
                                        <button id="step2Button" class="mdl-button mdl-js-button" onclick="moveToNext()" data-stepper-continue disabled><?php echo(STEPPER_CONTINUE);?></button>
                                        <button class="mdl-button mdl-js-button" onclick="moveToPrevious()" data-stepper-back><?php echo(STEPPER_BACK);?></button>
                                    </div>
                                </li>
                                <li id="step3" class="mdl-step mdl-step--editable mdl-step--optional">
                                    <span class="mdl-step__label">
                                        <span class="mdl-step__title">
                                            <span class="mdl-step__title-text"><?php echo(STEPPER_STEP3);?></span>
                                        </span>
                                    </span>
                                    <div class="mdl-step__content">
                                        <div id="advancedexercise" class="mdl-card__supporting-text mdl-card--expand">
                                            <label class="mdl-layout-title"><?php echo(STEPPER_STEP3_EXERCISEINTENSITY);?></label>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe1" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="High">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_EXERCISEINTENSITYHIGH);?></span>
                                                </label>
                                            </div>


                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe2" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="Moderate">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_EXERCISEINTENSITYMODERATE);?></span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radioe3" name="exerciseintensity" type="radio" class = "mdl-radio__button" value="Low">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_EXERCISEINTENSITYLOW);?></span>
                                                </label>
                                            </div>


                                        </div>

                                        <div id="advancedsteps" class="mdl-card__supporting-text mdl-card--expand">
                                            <label class="mdl-layout-title"><?php echo(STEPPER_STEP3_WALKSTEPS);?></label>

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
                                            <label class="mdl-layout-title"><?php echo(STEPPER_STEP3_SOCIALTYPE);?></label>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio1" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Receive Guests" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPERECEIVE);?></span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio2" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Call Someone" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPECALL);?></span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio3" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Visit Someone" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPEVISIT);?></span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio4" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Cinema" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPECINEMA);?></span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio5" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Theatre" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPETHEATRE);?></span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio6" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Restaurant" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPERESTAURANT);?></span>
                                                </label>
                                            </div>
                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio7" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Pub" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPEPUB);?></span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio8" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Religious" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPERELIGIOUS);?></span>
                                                </label>
                                            </div>

                                            <div class="radio">
                                                <label class="mdl-radio mdl-js-radio mdl-js-ripple-effect">
                                                    <input id="radio9" name="socialIntensity" type="radio" class = "mdl-radio__button" value="Other" onclick="showOtherTypeSocialInput()">
                                                    <span class="mdl-radio__label"><?php echo(STEPPER_STEP3_SOCIALTYPEOTHER);?></span>
                                                </label>
                                            </div>

                                            <div class="mdl-textfield mdl-js-textfield" id="other_social_activity_group" style="display: none;">
                                                <input class="mdl-textfield__input" type="text" id="other_social_activity">
                                                <label class="mdl-textfield__label" for="other_social_activity"><?php echo(STEPPER_STEP3_SOCIALTYPEOTHERNAME); ?></label>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="mdl-step__actions">
                                        <button class="mdl-button mdl-js-button" onclick="moveToPrevious()" data-stepper-back><?php echo(STEPPER_BACK);?></button>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="mdl-dialog__actions">
                            <button type="button" class="mdl-button" id="dialogSubmit" disabled><?php echo(STEPPER_SUBMIT);?></button>
                            <button type="button" class="mdl-button close" id="dialogCancel"><?php echo(STEPPER_CANCEL);?></button>
                        </div>
                    </dialog>








                    </main>
                </div>






        <!--DIALOG WITH PLANNED ACTIVITIES  -->
        <dialog id="activityDialog" class="mdl-dialog " style="z-index:9; width:fit-content; top: 60px">
            <h5 class="mdl-dialog__title" style="color: white"><?php echo(COMPLETED_TITLE);?></h5>
            <div class="mdl-dialog__content">


                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_NAME);?>: </span>
                <span id="ac_title" style="color: white"></span>
                <div></div>
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_TYPE);?>: </span>
                <span id="ac_type" style="color: white"></span>
                <div></div>
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_START);?>: </span>
                <span id="ac_start_date" style="color: white"></span>


            </div>
            <div class="mdl-dialog__actions">
                <button id="dialogYes" type="button" class="mdl-button" style="color: white"><?php echo(COMPLETED_YES);?></button>
                <button id="dialogNo" type="button" class="mdl-button" style="color: white"><?php echo(COMPLETED_NO);?></button>
            </div>

        </dialog>


        <!--DIALOG FOR ACTIVITY EDIT OR DELETE -->
        <dialog id="activityEditDeleteDialog" class="mdl-dialog " style="z-index:9; width:fit-content; top: 60px">
            <div class="mdl-dialog__content">
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_NAME);?>: </span>
                <span id="ed_title" style="color: white"></span>
                <div></div>
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_TYPE);?>: </span>
                <span id="ed_type" style="color: white"></span>
                <div></div>
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_START);?>: </span>
                <span id="ed_start_date" style="color: white"></span>
                <div></div>
                <span style="font-weight:bold; color: white"><?php echo(ACTIVIY_END);?>: </span>
                <span id="ed_end_date" style="color: white"></span>
            </div>
            <div class="mdl-dialog__actions">
                <button id="dialogEdit" type="button" class="mdl-button" style="color: green"><?php echo(ACTIVIY_EDIT);?></button>
                <button id="dialogDelete" type="button" class="mdl-button" style="color: red"><?php echo(ACTIVIY_DELETE);?></button>
                <button id="dialogCancel" type="button" class="mdl-button" style="color: white"><?php echo(CANCEL_BUTTON);?></button>
            </div>

        </dialog>








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



