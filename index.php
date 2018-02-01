<!DOCTYPE html>


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

//verifico se Ã¨ stato effettuato il login
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
        
        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>
        
        
        <!--        CIRCLIFUL-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link href="css/jquery.circliful.css" rel="stylesheet" type="text/css" />
        <script src="js/plugins/circliful//jquery.circliful.min.js"></script>
        
        <link rel="stylesheet" href="css/custom.css">
        
        
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>

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
        <script src="./js/plugins/adaptation/context-data.js"></script>
        <script src="./js/plugins/adaptation/command.js"></script>
        
	<?php
	    $plan= new Plan($_SESSION['personAAL_user']);

	    $walkPerc= 0;
	    if($plan->getWalkGoal() != 0)
		$walkPerc= ($plan->getActualWalk() * 100) / $plan->getWalkGoal();
	    if($walkPerc > 100)
		$walkPerc= 100;
	    //$walkPerc= 25;
            
	    $exercisePerc= 0;
	    if($plan->getExerciseGoal() != 0)
		$exercisePerc= ($plan->getActualExercise() * 100) / $plan->getExerciseGoal();
	    if($exercisePerc > 100)
		$exercisePerc= 100;
	    //$exercisePerc= 50;    
//	    echo($walkPerc.'  '.$exercisePerc);
            
            
                
            $surveyinfo = new SurveyData($_SESSION['personAAL_user']);
            
//            echo("weight= ". $surveyinfo->getWeight().";");
//            echo("height=". $surveyinfo->getHeight() .";");
//            echo("age= ". $surveyinfo->getAge() .";");
//            echo("motivation= ". $surveyinfo->getMotivation() .";");
            
            $bmi = sprintf('%0.2f',(($surveyinfo->getWeight() * 10000)/ ($surveyinfo->getHeight()*$surveyinfo->getHeight())));
            
	?>
	
        <script>
            $(document).ready(function() {
                
            //phone device fix for velocity animations (animation order)
            console.log($(window).width());
            if($(window).width() <= 479)
            {
                //remove ripple effect from cards (performance boost on mobile)
                $('.mdl-card').removeClass("mdl-js-ripple-effect mdl-js-button");
                
                document.getElementById('order-1-phone').style.display= "none";
                document.getElementById('order-2-phone').style.display= "none";
                document.getElementById('order-3-phone').style.display= "none";
                document.getElementById('order-4-phone').style.display= "none";
                document.getElementById('order-5-phone').style.display= "none";
                document.getElementById('order-6-phone').style.display= "none";
                
                var cardSequence= [];
                cardSequence.push({ e: $('#order-1-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex'}});
                cardSequence.push({ e: $('#order-2-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex', sequenceQueue: false, delay: 250}});
                cardSequence.push({ e: $('#order-3-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex', sequenceQueue: false, delay: 250}});
                cardSequence.push({ e: $('#order-4-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex', sequenceQueue: false, delay: 250}});
                cardSequence.push({ e: $('#order-5-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex', sequenceQueue: false, delay: 250}});
                cardSequence.push({ e: $('#order-6-phone'), p: 'transition.slideUpBigIn', o: {display: 'flex', sequenceQueue: false, delay: 250}});

//                //run animation
                $.Velocity.RunSequence(cardSequence);
//                
            }
            else    // desktop/tablet mode
                $('.survey-card, .help-card, .goal-card, .news-card, .sport-card').velocity('transition.slideUpBigIn', {stagger: 250, display: 'flex'});
            
        
            //CIRCLIFUL
            $("#goal-circle-1").circliful({
                animation: 1,
		animationStep: 3,
		foregroundBorderWidth: 5,
		backgroundBorderWidth: 1,
		<?php echo('percent: '. $walkPerc .','); ?>
		iconColor: '#FFFFFF',
                fillColor: '#33ccff',
		//fillColor: '#33ff33',
		//icon: 'f29d',
		iconSize: '55',
		//iconPosition: 'middle',
		fontColor: '#FFFFFF',
		foregroundColor: '#FFFFFF'
            });

            $("#goal-circle-2").circliful({
		animation: 1,
		animationStep: 3,
		foregroundBorderWidth: 5,
		backgroundBorderWidth: 1,
		<?php echo('percent: '. $exercisePerc .','); ?>
		iconColor: '#FFFFFF',
		fillColor: '#33ccff',
		//icon: 'f206',
		iconSize: '55',
		//iconPosition: 'middle',
		fontColor: '#FFFFFF',
		foregroundColor: '#FFFFFF'
	    });
            
            
        } );
        
        
        function confirmSurveyModal()
        {
            //only integer values
            var reg = new RegExp('^[0-9]+$');
            
            var weight= $('#input_weight').val();
            console.log('regexp weight: ' + reg.test(weight));
            if(!reg.test(weight))
                return;
            
            var height= $('#input_height').val();
            console.log('regexp height: ' + reg.test(height));
            if(!reg.test(height))
                return;
            
            var age= $('#input_age').val();
            console.log('regexp age: ' + reg.test(age));
            if(!reg.test(age))
                return;
            sendAgeToContext(age);
            
            var motivation = $("input[type='radio'][name='motivation']:checked").val();
            console.log('motivation: ' + motivation);
            sendMotivationDataToContext(motivation);
            
            var score_5 = $("input[type='radio'][name='answer_5']:checked").val();
            //console.log('score_5: ' + score_5);
            
            var score_6 = $("input[type='radio'][name='answer_6']:checked").val();
            //console.log('score_6: ' + score_6);
            
            var score_7 = $("input[type='radio'][name='answer_7']:checked").val();
            //console.log('score_7: ' + score_7);
            
            var score_8 = $("input[type='radio'][name='answer_8']:checked").val();
            //console.log('score_8: ' + score_8);
            
            var score_9 = $("input[type='radio'][name='answer_9']:checked").val();
            //console.log('score_9: ' + score_9);
            
            var score = Number(score_5) + Number(score_6) + Number(score_7) + Number(score_8) + Number(score_9);
            console.log('score: ' + score);
            console.log(Date.now());
            
            //add weight data to db
            addWeightData(Date.now(), weight);
            
            //add FiND questionnaire data to db
            addFindData(Date.now(), score);
            
            //add survey data to db
            addSurveyData(weight, height, age, motivation);
            
//            setWeight(weight);          
//            setHeight(height);
//            setAge(age);
//            setMotivation(motivation);
            
            $('#survey-modal').modal('hide');
//          $('#input_weight').val("");
        }
        
        function openSurveyModal()
        {
            $('#survey-modal').modal({ keyboard: false, backdrop: 'static'});
            
        }
            
        function deleteSpace(element)
        {
            var val= $(element).val();
            
            if(val === " ")
                $(element).val("");
        }
        

        function updateParametersValue()
        {

        }       
        </script>
                
        
    </head>
    <body>



        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">PersonAAL</span>
                    <div class="mdl-layout-spacer"></div>
                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect" onclick="window.location='index.php';">
                        <i class="material-icons">home</i>
                    </button>
                </div>
            </header>
            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title"><?php echo(MENU_TITLE);?></span>
                <nav class="mdl-navigation">
		    <a class="mdl-navigation__link mdl-navigation__link-selected" href="index.php"><i class="material-icons">home</i><?php echo(ENTRY_HOME);?></a>
                    <a class="mdl-navigation__link" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
                    <a class="mdl-navigation__link" href="plan.php"><i class="material-icons">date_range</i><?php echo(ENTRY_PLAN);?></a>
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



                        <!-- Questionnaire --> 
                        <div id="questionnaire" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch mdl-cell--order-1-desktop mdl-cell--order-3-phone mdl-cell--order-1-tablet">    

                            <div id="order-5-phone" class="survey-card mdl-card mdl-shadow--4dp mdl-js-button mdl-js-ripple-effect mdl-cell mdl-cell--8-col-desktop mdl-cell--4-col-tablet mdl-cell--2-col-phone no-stretch  mdl-cell--order-4-phone"
                                onclick="openSurveyModal()">


                                <div class="mdl-card__title mdl-card--expand">
                                    <div class="mdl-card__title-text"><?php echo(INDEX_SURVEYCARD_TITLE);?></div>
                                </div>
                                <div class="mdl-card__supporting-text hide-phone">
                                    <?php echo(INDEX_SURVEYCARD_TEXT);?>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect">
                                        <?php echo(INDEX_SURVEYCARD_BUTTON);?>
                                    </a>
                                    <div class="mdl-layout-spacer hide-desktop_tablet"></div>
                                    <i class="material-icons hide-desktop_tablet">arrow_forward</i>
                                </div>
                                <div class="mdl-card__menu hide-phone">
                                    <button class="mdl-button mdl-button--icon mdl-js-button mdl-js-ripple-effect">
                                        <i class="material-icons">assignment</i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="order-6-phone" class="mdl-js-button help-card mdl-card mdl-shadow--4dp mdl-js-ripple-effect mdl-cell mdl-cell--2-col-phone mdl-cell--hide-desktop mdl-cell--hide-tablet mdl-cell--order-5-phone"
                            onclick="window.location='#';">
                            <div class="mdl-card__title mdl-card--expand"></div>
                            <div class="mdl-card__actions">
                                <?php echo(INDEX_INFOCARD_TITLE);?>
                            </div>
                        </div>
                            



<!--                    <div id="goals" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch mdl-cell--order-1-desktop mdl-cell--order-3-phone mdl-cell--order-1-tablet">              -->
                       <div id="fitness" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone mdl-cell--order-3-phone">    
                            <div id="order-3-phone" class="goal-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue"
                                onclick="window.location='#';">

                                <div class="mdl-card__title mdl-card--expand mdl-grid goal-grid">
                                    <div class="mdl-cell mdl-cell--8-col-desktop mdl-cell--5-col-tablet no-margin">
                                        <div id="goal-circle-1"></div>
                                    </div>

                                    <div class="mdl-cell mdl-cell--4-col-desktop mdl-cell--3-col-tablet goal-info-container no-margin mdl-cell--hide-phone">
                                        <div class="goal-info">
                                                <?php
                                                    //get walk goal info
                                                    echo($plan->getActualWalk() . ' / ' . $plan->getWalkGoal());
                                                    //echo ("500/2000")
                                                ?>

                                                <div class="goal-info-text">
                                                    <?php echo(INDEX_STEPSCARD_STEPS);?>
                                                </div>

                                                <figure class="goal-info-media">
                                                    <img src="img/walk.jpg">
                                                </figure>
                                        </div>                                       
                                    </div>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="hide-phone">
                                        <?php echo(INDEX_STEPSCARD_STEPS_GOAL);?>
                                    </div> 

                                    <div class="hide-desktop_tablet">
                                        <?php
                                            //get walk goal info (mobile)
                                            echo($plan->getActualWalk() . ' / '. $plan->getWalkGoal() .' '. INDEX_STEPSCARD_STEPS);
                                        ?>
                                    </div>
                                    <div id="daily_steps" class="goal-message-text"></div>
                                </div>
                            </div>

                            <div id="order-4-phone" class="goal-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue"
                                onclick="window.location='#';">

                                <div class="mdl-card__title mdl-card--expand mdl-grid goal-grid">
                                    <div class="mdl-cell mdl-cell--8-col-desktop mdl-cell--5-col-tablet no-margin">
                                        <div id="goal-circle-2"></div>
                                    </div>

                                    <div class="mdl-cell mdl-cell--4-col-desktop mdl-cell--3-col-tablet goal-info-container no-margin mdl-cell--hide-phone">
                                        <div class="goal-info">
                                            <?php
                                                //get exercise goal info
                                                echo($plan->getActualExercise() . ' / ' . $plan->getExerciseGoal());
                                                //echo("30/60")
                                            ?>
                                            <div class="goal-info-text">
                                                <?php echo(INDEX_STEPSCARD_EXERCISE);?>
                                            </div>

                                            <figure class="goal-info-media">
                                                <img src="img/fit.jpg">
                                            </figure>
                                        </div>
                                    </div>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="hide-phone">
                                        <?php echo(INDEX_STEPSCARD_EXERCISEGOAL);?>
                                    </div>
                                    <div class="hide-desktop_tablet">
                                        <?php
                                            //get exercise goal info (mobile)
                                            echo($plan->getActualExercise() . ' / '. $plan->getExerciseGoal() .' '. INDEX_STEPSCARD_EXERCISE);
                                        ?>
                                    </div>
                                    <div id="persuasive-message" class="goal-message-text"></div>
                                </div>
                            </div> 
<!--                    </div>-->
                        
 
                            <div  class="weight-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(WEIGHT_CARD_TITLE);?></h2>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <?php echo($surveyinfo->getWeight()). ' kg ';?>
                                </div>
                            </div>
                            <div  class="bmi-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(BMI_CARD_TITLE);?></h2>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <?php echo($bmi);?>
                                </div>
                            </div>
<!--                            <div   class="weather-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(WEATHER_CARD_TITLE);?></h2>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <iframe width="300" height="80" scrolling="no" frameborder="no" noresize="noresize" src="http://www.ilmeteo.it/box/previsioni.php?citta=8129&type=real1&width=250&ico=1&lang=eng&days=6&font=Arial&fontsize=12&bg=FFFFFF&fg=000000&bgtitle=0099FF&fgtitle=FFFFFF&bgtab=F0F0F0&fglink=1773C2"></iframe>
                                </div>
                            </div>-->
                        </div>

                        <div id="health" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone mdl-cell--order-3-phone"> 
                            <div  class="heart-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(HEART_CARD_TITLE);?></h2>
                                </div>
                                <div id="ecg_hr" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                            <div   class="breath-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(BREATH_CARD_TITLE);?></h2>
                                </div>
                                <div id="respiration_rate" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                            <div   class="temperature-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(TEMPERATURE_CARD_TITLE);?></h2>
                                </div>
                                <div id="body_temperature" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                            <div   class="medication-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(MEDICATION_CARD_TITLE);?></h2>
                                </div>
                                <table class="mdl-data-table mdl-js-data-table" width="100%">
                                    <thead>
                                      <tr>
                                        <th class="mdl-data-table__cell--non-numeric"><?php echo(MEDICATION_PLANNED);?></th>
                                        <th><?php echo(MEDICATION_PLANNED_DOSAGE);?></th>
                                        <th><?php echo(MEDICATION_PLANNED_TIME);?></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <tr>
                                        <td id="medication_planned" class="mdl-data-table__cell--non-numeric"></td>
                                        <td id="medication_planned_dosage"></td>
                                        <td id="medication_planned_time"></td>
                                      </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <!-- NEWS COL -->
<!--                        <div id="grid-news" class="mdl-grid mdl-cell mdl-cell--3-col-desktop mdl-cell--4-col-phone mdl-cell--4-col-tablet no-stretch mdl-cell--order-3-desktop mdl-cell--order-1-phone mdl-cell--order-2-tablet">-->

    <!--                    <div id="order-1-phone" class="mdl-js-button news-card mdl-card mdl-shadow--4dp mdl-js-ripple-effect mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet"
                                 onclick="window.location='#';">
                                <div class="mdl-card__title mdl-card--expand"></div>
                                <div class="mdl-card__actions">
                                    <?php echo(INDEX_NEWS1_TITLE);?>
                                </div>
                            </div>

                            <div id="order-2-phone" class="mdl-js-button sport-card mdl-card mdl-shadow--4dp mdl-js-ripple-effect  mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet"
                                 onclick="window.location='#';">
                                <div class="mdl-card__title mdl-card--expand"></div>
                                <div class="mdl-card__actions">
                                    <?php echo(INDEX_NEWS2_TITLE);?>
                                </div>
                            </div>-->

                                 
<!--                                <iframe width="250" height="130" scrolling="no" frameborder="no" noresize="noresize" src="http://www.ilmeteo.it/box/previsioni.php?citta=8129&type=real1&width=250&ico=1&lang=eng&days=6&font=Arial&fontsize=12&bg=FFFFFF&fg=000000&bgtitle=0099FF&fgtitle=FFFFFF&bgtab=F0F0F0&fglink=1773C2"></iframe>-->
                                 
<!--                                <script type="text/javascript" src="http://output75.rssinclude.com/output?type=js&amp;id=1139594&amp;hash=d3748b2ef80e7c20494fbddd3be41f53"></script>-->

<!--                                <script type="text/javascript" src="http://100widgets.com/js_data.php?id=168"></script>
                                <script type="text/javascript" src="http://100widgets.com/js_data.php?id=268"></script>-->

<!--                        </div>-->

                    </div>
     
                </div>
                
<!--                <BUTTON data-target="#alert-modal" data-toggle="modal">asdasdasd</BUTTON>-->
            </main>
        </div>   

        
        
        <!-- NEW -->
        <!-- INVITATION MODAL -->
        <div id="info-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="info-modal-card mdl-card">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <i class="material-icons">info</i>
                            <div id="modal-alert-text">
                            </div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(ACCEPT_INVITATION_BUTTON);?>      
			    </a>
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(DECLINE_INVITATION_BUTTON);?>
                                
			    </a>
                
			</div>
		    </div>
                    
                </div>

            </div>
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
				<?php echo(CLOSE_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        <!-- INFO MODAL -->
        <div id="info-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="info-modal-card mdl-card">
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <i class="material-icons">info</i>
                            <div id="modal-info-text">
                            </div>
			</div>
			<div class="mdl-card__actions mdl-card--border">
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo"Close";?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
        
        <!--SURVEY MODAL-->
        
        <div id="survey-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                
                <!-- Modal content-->
                <div class="modal-content">
                    
                    <div class="survey-modal-card mdl-card expand-in-modal">
                        <div class="mdl-card__title mdl-card--border">
                            <?php echo(INDEX_SURVEY_TITLE);?>
                        </div>
                        <div class="mdl-card__supporting-text mdl-card--expand">
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION1);?>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield survey-textfield">
                                    <input class="mdl-textfield__input" type="text" id="input_weight" pattern="-?[0-9]+?">
                                    <label class="mdl-textfield__label" for="input_weight">
                                        <?php echo(INDEX_SURVEY_HINT1);?>
                                    </label>
                                    <span class="mdl-textfield__error">Only integer numbers *</span>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION2);?>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield survey-textfield">
                                    <input class="mdl-textfield__input" type="text" id="input_height" pattern="-?[0-9]+?">
                                    <label class="mdl-textfield__label" for="input_height">
                                        <?php echo(INDEX_SURVEY_HINT2);?>
                                    </label>
                                    <span class="mdl-textfield__error">Only integer numbers *</span>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION3);?>
                                </div>
                                <div class="mdl-textfield mdl-js-textfield survey-textfield">
                                    <input class="mdl-textfield__input" type="text" id="input_age" pattern="-?[0-9]+?">
                                    <label class="mdl-textfield__label" for="input_age">
                                        <?php echo(INDEX_SURVEY_HINT3);?>
                                    </label>
                                    <span class="mdl-textfield__error">Only integer numbers *</span>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION4);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
<!--                                <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="wellness" name="motivation" checked>
                                         <?php echo(INDEX_SURVEY_MOTIVATION1);?>
                                    </label>-->
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="health" name="motivation" checked>
                                        <?php echo(INDEX_SURVEY_MOTIVATION2);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="fitness" name="motivation">
                                        <?php echo(INDEX_SURVEY_MOTIVATION3);?>
                                    </label>
<!--                                <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="social" name="motivation">
                                        <?php echo(INDEX_SURVEY_MOTIVATION4);?>
                                    </label>-->
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION5);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="0" name="answer_5" checked>
                                         <?php echo(ANSWER_5a);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="1" name="answer_5">
                                        <?php echo(ANSWER_5b);?>
                                    </label>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION6);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="0" name="answer_6" checked>
                                         <?php echo(ANSWER_6a);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="1" name="answer_6">
                                        <?php echo(ANSWER_6b);?>
                                    </label>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION7);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="0" name="answer_7" checked>
                                         <?php echo(ANSWER_7a);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="1" name="answer_7">
                                        <?php echo(ANSWER_7b);?>
                                    </label>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION8);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="0" name="answer_8" checked>
                                         <?php echo(ANSWER_8a);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="1" name="answer_8">
                                        <?php echo(ANSWER_8b);?>
                                    </label>
                                </div>
                            </div>
                            <div class="survey-question-container">
                                <div class="survey-question">
                                    <?php echo(INDEX_SURVEY_QUESTION9);?>
                                </div>
                                <div class="mdl-radio survey-mdl-radio" id="input_motivation">
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="0" name="answer_9" checked>
                                         <?php echo(ANSWER_9a);?>
                                    </label>
                                    <label class="mdl-radio-label survey-mdl-radio__label">
                                        <input class="mdl-radio__button" type="radio" value="1" name="answer_9">
                                        <?php echo(ANSWER_9b);?>
                                    </label>
                                </div>
                            </div>
                          
			<div class="mdl-card__actions mdl-card--border">
                            <a id="survey-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal">
				<?php echo(CANCEL_BUTTON);?>
			    </a>
                            <a id="survey-modal-cancel" class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" onclick="confirmSurveyModal()">
				<?php echo(CONFIRM_BUTTON);?>
			    </a>
			</div>
		    </div>
                    
                </div>

            </div>
        </div>
            
    </body>
    
</html>
