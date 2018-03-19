<?php

include 'miscLib.php';
include 'DButils.php';

// Require composer autoloader
 require __DIR__ . '\login\vendor\autoload.php';
 require __DIR__ . '\login\dotenv-loader.php';

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

    <!--        <script src='js/jquery-ui.min.js'></script>-->
    <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>

    <!-- MODALS -->
    <link rel="stylesheet" href="css/bootstrap_modals.css">
    <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>

    <!-- ADAPTATION SCRIPTS -->
    <script type="text/javascript">
            var userName = "<?php echo $_SESSION['personAAL_user']?>";
    </script>
    <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
    <script src="./js/plugins/adaptation/stomp.js"></script>
    <script src="./js/plugins/adaptation/websocket-connection.js"></script>
    <script src="./js/plugins/adaptation/adaptation-script.js"></script>
    <script src="./js/plugins/adaptation/delegate.js"></script>
    <script src="./js/plugins/adaptation/jshue.js"></script>
    <script src="./js/plugins/adaptation/command.js"></script>

    <script src="js/health.js"></script>

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

                    <div class="mdl-grid mdl-cell mdl-cell--6-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grid-no-padding">

                        <div class="weight-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">

                            <div class="mdl-card__title">
                                <div class="mdl-card__title-text">
                                    <?php echo(HEALTH_WEIGHTPLOT_TITLE);?>
                                </div>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-weight" class="center" style="width:100%;height:350px;"></div>
                            </div>
                        </div>

                        <div class="weight-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--top mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet no-stretch">

                            <div class="mdl-card__title">
                                <div class="mdl-card__title-text">
                                    <?php echo(HEALTH_BMIPLOT_TITLE);?>
                                </div>
                            </div>
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-bmi" class="center" style="width:100%;height:350px;"></div>
                            </div>

                        </div>

                    </div>


                    <div class="mdl-grid mdl-cell mdl-cell--6-col-desktop mdl-cell--8-col-tablet mdl-cell--4-col-phone mdl-cell--order-3-phone">

                         <div id="hr_value_box" class="heart-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue" style="height:228px;">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><?php echo(HEART_CARD_TITLE);?></h2>
                            </div>
                            <div id="ecg_hr_box" class="mdl-card__actions mdl-card--border" ></div>
                        </div>

                        <div id="rr_value_box" class="breath-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue" style="height:228px;">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><?php echo(BREATH_CARD_TITLE);?></h2>
                            </div>
                            <div id="respiration_rate_box" class="mdl-card__actions mdl-card--border"></div>
                        </div>
                        
                        <div id="bt_value_box" class="temperature-info-card mdl-card mdl-shadow--4dp mdl-cell mdl-cell--12-col-desktop mdl-cell--2-col-phone mdl-cell--8-col-tablet b-blue" style="height:228px;">
                            <div class="mdl-card__title">
                                <h2 class="mdl-card__title-text"><?php echo(TEMPERATURE_CARD_TITLE);?></h2>
                            </div>
                            <div id="body_temperature_box" class="mdl-card__actions mdl-card--border"></div>
                        </div>
                        
                        <div id="hr_plot_chart" class="plot-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grey no-stretch" style="display:none">
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-HR" class="center" style="width:100%;height:350px;"></div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div class="plot-title">
                                    <?php echo(HEALTH_HRPLOT_TITLE);?>
                                </div>
                                <div class="mdl-layout-spacer"></div>
                                <div id="heart-rate-value" class="plot-info-value">- </div>
                                <div id="heart-rate-unit" class="plot-info-measure-unit">bpm</div>
                            </div>
                        </div>
                        
                        <div id="rr_plot_chart" class="plot-card mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--4-col-phone mdl-cell--8-col-tablet grey no-stretch" style="display:none">
                            <div class="mdl-card__supporting-text mdl-card--expand">
                                <div id="plot-BREATH" class="center" style="width:100%;height:350px;"></div>
                            </div>
                            <div class="mdl-card__actions mdl-card--border">
                                <div class="plot-title">
                                    <?php echo(HEALTH_BREATHPLOT_TITLE);?>
                                </div>
                                <div class="mdl-layout-spacer"></div>
                                <div id="heart-rate-value" class="plot-info-value">- </div>
                                <div id="heart-rate-unit" class="plot-info-measure-unit">bpm</div>
                            </div>
                        </div>


                    </div>
                        
              </div>
        </main>
        </div>

        <button id="start-capture" class="mdl-button mdl-shadow--4dp mdl-js-button mdl-button--fab mdl-js-ripple-effect mdl-button--colored floating-button" onclick="manageCapture()">
            <i id="captureControl" class="material-icons">play_arrow</i>
        </button>        


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