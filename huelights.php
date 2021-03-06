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

//if(!isCookieEnabled())
//{
//    //TODO handle disabled cookie error
//    //myRedirect("error.php?err=DISABLED_COOKIE", TRUE);
//}
//
//
////SESSIONE
//session_start();
//setLanguage();
//
////verifico se è stato effettuato il login
//if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
//{
//    $t=time();
//    $diff=0;
//    $new=FALSE;
//    
//    //VERIFICO SE LA SESSIONE E' SCADUTA
//    if (isset($_SESSION['personAAL_time']))
//    {
//	$t0=$_SESSION['personAAL_time'];
//	$diff=($t-$t0); // inactivity period
//    }
//    else
//	$new=TRUE;
//        
//    if ($new || ($diff > SESSION_TIMEOUT))
//    { 
//	//DISTRUGGO LA SESSIONE
//	mySessionDestroy();
//	myRedirect("login.php?notify=".SESSION_EXPIRED, TRUE);
//    }
//    else
//	$_SESSION['personAAL_time']=time();  //update time 
//    
//}
//else
//    myRedirect("login.php", TRUE);
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
                <script src="js/plugins/Jquery/jquery-1.11.2.js"></script>
                <link rel="stylesheet" href="css/custom.css">


                <!-- MODALS -->
                <link rel="stylesheet" href="css/bootstrap_modals.css">
                <script src="js/plugins/bootstrap/bootstrap_modals.js"></script>

                <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>

                <!-- VELOCITY -->
                <script src="js/plugins/velocity/velocity.min.js"></script>
                <script src="js/plugins/velocity/velocity.ui.min.js"></script>
                
                <!-- javascript functions for DB (ajax requests)-->
                <script src="js/DBinterface.js"></script>

                <!-- ADAPTATION SCRIPTS -->
                <script type="text/javascript">
                    var userName = "<?php echo $_SESSION['personAAL_user']?>";
                    var token = "<?php echo $idtoken ?>";
                </script>
                <script src="./js/plugins/adaptation/sockjs-1.1.1.js"></script>
                <script src="./js/plugins/adaptation/stomp.js"></script>
                <script src="./js/plugins/adaptation/websocket-connection.js"></script>		
                <script src="./js/plugins/adaptation/adaptation-script.js"></script>		
                <script src="./js/plugins/adaptation/delegate.js"></script>
                <script src="./js/plugins/adaptation/context-data.js"></script>
                <script src="./js/plugins/adaptation/jshue.js"></script>
		<script src="./js/plugins/adaptation/command.js"></script>
                
                
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
		    <a class="mdl-navigation__link" href="profile.php"><i class="material-icons">info</i><?php echo(ENTRY_PROFILE);?></a>
		    <a class="mdl-navigation__link" href="contacts.php"><i class="material-icons">group</i><?php echo(ENTRY_CONTACTS);?></a>
                    <a class="mdl-navigation__link" href="huelights.php"><i class="material-icons">flare</i><?php echo(ENTRY_HUELIGHTS);?></a>
                    <a class="mdl-navigation__link" href="logout.php"><i class="material-icons">power_settings_new</i><?php echo(ENTRY_LOGOUT);?></a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <br>
                    <button class="mdl-button mdl-js-button mdl-button--raised" style="margin-left: 2em" onclick="discoverBridge()"><?php echo(DISCOVER_BRIDGE);?></button> <br>
                    <div id="ipHue"></div><br>
                    <br>
<!--                    <button onclick="getUsername()">Set Username</button> <br>-->
                    <button class="mdl-button mdl-js-button mdl-button--raised" style="margin-left: 2em" onclick="getHueUsername()"><?php echo(SET_USERNAME);?></button> <br>
                    <div id="unHue"></div><br>
                    <br>
                    <button class="mdl-button mdl-js-button mdl-button--raised" style="margin-left: 2em" onclick="getLightState();"><?php echo(GET_LIGHT_STATE);?></button><br>
                    <br>
<!--                    Response: <div id="res"></div>
                    <br>-->
                    <div id="color" style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden">
                        <label id="colorset" style="font-family: Arial; font-size: 20; font-weight: 5px; color: blue"><?php echo(SET_COLOR);?></label>
                        <br>
                        <label for="sat"><?php echo(SATURATION);?></label>
                        <input type="number" id="sat"  value="255"></input>
                        <label for="bri"><?php echo(BRIGHTNESS);?></label>
                        <input type="number" id="bri"  value="255"></input>
                        <input type="color" id="colorPicker"/>
                    </div>
                    <br>
                    <div id="status1" style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden">
                        <label id="label1" style="font-family: Arial; font-size: 20; font-weight: 5px; color: blue"><?php echo(LIGHT);?> 1 </label>
                        <br>
                        <button id="btn1chg" onclick="onClickTurnOnAndChangeColor(1);"><?php echo(TURN_ON_AND_CHANGE_COLOR);?></button><br>
                        <br>
                        <button id="btn1on" onclick="turnOnOffLight(1, true);"><?php echo(TURN_ON);?></button> <br>
                        <br>
                        <button id="btn1off" onclick="turnOnOffLight(1, false);"><?php echo(TURN_OFF);?></button> <br>
                        <br>
                        <select id="room1">
                            <option value="LivingRoom"><?php echo(LIVING_ROOM);?></option>
                            <option value="Kitchen"><?php echo(KITCHEN);?></option>
                            <option value="Entrance"><?php echo(ENTRANCE);?></option>
                        </select>
                    </div>
                    <div id="status2" style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden">
                        <label id="label1" style="font-family: Arial; font-size: 20; font-weight: 5px; color: blue"><?php echo(LIGHT);?> 2 </label>
                        <br>
                        <button id="btn2chg" onclick="onClickTurnOnAndChangeColor(2);"><?php echo(TURN_ON_AND_CHANGE_COLOR);?></button><br>
                        <br>
                        <button id="btn2on" onclick="turnOnOffLight(2, true);"><?php echo(TURN_ON);?></button> <br>
                        <br>
                        <button id="btn2off" onclick="turnOnOffLight(2, false);"><?php echo(TURN_OFF);?></button> <br>
                        <br>
                        <select id="room2">
                            <option value="LivingRoom"><?php echo(LIVING_ROOM);?></option>
                            <option value="Kitchen"><?php echo(KITCHEN);?></option>
                            <option value="Entrance"><?php echo(ENTRANCE);?></option>
                        </select>
                    </div>
                    <div id="status3" style="width:auto; height:auto; border:2px solid blue; padding:10px; margin:10px; visibility:hidden">
                        <label id="label3" style="font-family: Arial; font-size: 20; font-weight: 5px; color: blue"><?php echo(LIGHT);?> 3 </label>
                        <br>
                        <button id="btn3chg" onclick="onClickTurnOnAndChangeColor(3);"><?php echo(TURN_ON_AND_CHANGE_COLOR);?></button><br>
                        <br>
                        <button id="btn3on" onclick="turnOnOffLight(3, true);"><?php echo(TURN_ON);?></button> <br>
                        <br>
                        <button id="btn3off" onclick="turnOnOffLight(3, false);"><?php echo(TURN_OFF);?></button> <br>
                        <br>
                        <select id="room3">
                            <option value="LivingRoom"><?php echo(LIVING_ROOM);?></option>
                            <option value="Kitchen"><?php echo(KITCHEN);?></option>
                            <option value="Entrance"><?php echo(ENTRANCE);?></option>
                        </select>
                    </div>
                </div>
            </main>     
	</body>
</html>