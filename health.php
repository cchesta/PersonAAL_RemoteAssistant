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


//MOVED FROM INDEX.PHP
$surveyinfo = new SurveyData($_SESSION['personAAL_user']);
$bmi = sprintf('%0.2f',(($surveyinfo->getWeight() * 10000)/ ($surveyinfo->getHeight()*$surveyinfo->getHeight())));


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
        <link rel="stylesheet" href="css/custom.css">
        <script src="js/plugins/material_design/material.min.js"></script>
        
        <script src="js/plugins/Jquery/jquery-1.9.1.min.js"></script>
        
        <!-- VELOCITY -->
        <script src="js/plugins/velocity/velocity.min.js"></script>
        <script src="js/plugins/velocity/velocity.ui.min.js"></script>
        
        <!-- javascript functions for DB (ajax requests)-->
        <script src="js/DBinterface.js"></script>
        
        <!-- PLOT & WEBSOCKET -->
        <script src="js/plugins/flot/jquery.flot.js"></script>
	    <script src="js/plugins/flot/jquery.flot.time.js"></script>
        <script src="js/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="js/plugins/flot/jquery.flot.axislabels.js"></script>
        <script src="js/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="js/plugins/flot/jquery.flot.navigationControl.js"></script>
        <script src="js/health.js"></script>
        
<!--        <script src='js/jquery-ui.min.js'></script>-->
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
	
        <!-- MODALS -->
        <link rel="stylesheet" href="css/bootstrap_modals.css">
        <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>
        
        <!-- ADAPTATION SCRIPTS -->
        <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
        <script src="./js/plugins/adaptation/stomp.js"></script>
        <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
        <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
        <script src="./js/plugins/adaptation/delegate.js"></script>

     
        
    </head>
    <body>

        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--fixed-drawer">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title"><?php echo(ENTRY_HEALTH);?></span>
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
                    <a class="mdl-navigation__link mdl-navigation__link-selected" href="health.php"><i class="material-icons">local_hospital</i><?php echo(ENTRY_HEALTH);?></a>
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

			<div id="realTimePlot" class="mdl-grid mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grid-no-padding">
                            
                        <div class="weight-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">
                            <div class="mdl-card__title">
                                <div class="mdl-card__title-text">
                                    <?php echo(HEALTH_WEIGHTPLOT_TITLE);?>
                                </div>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-weight2" class="center"></div>
                            </div>
                        </div>
                
                        <div class="plot-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grey no-stretch">
				<div class="mdl-card__supporting-text mdl-card--expand">
				    <div id="plot-ECG" class="center"></div>
				</div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="plot-title"><?php echo(HEALTH_ECGPLOT_TITLE);?></div>
                                    <div class="mdl-layout-spacer"></div>
                                    <div id="heart-rate-value" class="plot-info-value">- </div>
                                    <div id="heart-rate-unit" class="plot-info-measure-unit">bpm</div>
                                    <i class="material-icons">favorite</i>
                                </div>
			    </div>
                                        <!--    
                            <div class="plot-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grey no-stretch">
				<div class="mdl-card__supporting-text mdl-card--expand">
				    <div id="plot-ACC" class="center"></div>
				</div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="plot-title">
                                        <?php echo(HEALTH_ACCPLOT_TITLE);?>
                                    </div>
                                </div>
			    </div>
                        -->  
    

                            <div class="plot-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grey no-stretch">
				<div class="mdl-card__supporting-text mdl-card--expand">
				    <div id="plot-TEMP" class="center"></div>
				</div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <div class="plot-title">
                                        <?php echo(HEALTH_TEMPPLOT_TITLE);?>
                                    </div>
                                </div>
			    </div>
                
                            
                        </div>    


                        <div id="health" class="mdl-grid mdl-cell mdl-cell--4-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone mdl-cell--order-3-phone"> 
                            <div  class="heart-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(HEART_CARD_TITLE);?></h2>
                                </div>
                                <div id="ecg_hr" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                            <!--
                            <div   class="breath-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(BREATH_CARD_TITLE);?></h2>
                                </div>
                                <div id="respiration_rate" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                            -->
                            <div   class="temperature-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(TEMPERATURE_CARD_TITLE);?></h2>
                                </div>
                                <div id="body_temperature" class="mdl-card__actions mdl-card--border"></div>
                            </div>
                             <div  class="bmi-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(BMI_CARD_TITLE);?></h2>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <?php echo($bmi);?>
                                </div>
                            </div>
                            <!--
                        <div  class="weight-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue">
                                <div class="mdl-card__title">
                                    <h2 class="mdl-card__title-text"><?php echo(WEIGHT_CARD_TITLE);?></h2>
                                </div>
                                <div class="mdl-card__actions mdl-card--border">
                                    <?php echo($surveyinfo->getWeight()). ' kg ';?>
                                </div>
                            </div>
                            
                            </div>
                      
                        <div class="find-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">
                            <div class="mdl-card__title">
                                <div class="mdl-card__title-text">
                                    <?php echo(HEALTH_SCOREPLOT_TITLE);?>
                                </div>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-find" class="center"></div>
                            </div>
                        </div>
                        -->

			
			
                        
                        <div class="mdl-cell mdl-cell--12-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone floating-button-fix-cell"></div>
                        
                    </div>

                </div>
            </main>
        </div>   


        <div id="snackbar-log" class="mdl-js-snackbar mdl-snackbar">
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
