<!DOCTYPE html>

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

      
    //REDIRECT SU HTTPS
//    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//	HTTPtoHTTPS();
    
    if(!isCookieEnabled())
    {
	myRedirect("login.php?notify=".DISABLED_COOKIE, TRUE);
    }
    
    //LOGOUT
    if(isset($_REQUEST['notify']) && $_REQUEST['notify'] == LOGOUT)
    {
	mySessionDestroy();
    }
    
    //session_start();
    
    //utente loggato
    if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
    {
	$_SESSION['personAAL_time']=time();
	myRedirect("index.php",TRUE);
    }
    
    
    //PROCEDURA PER IL LOGIN
    if(isset($_REQUEST['submit']) && $_REQUEST['submit'] !== "")
    {
	$_REQUEST['submit']= "";
	unset($_REQUEST['submit']);
	
	if(!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || $_REQUEST['username'] == "" || $_REQUEST['password'] == "")
	    sendGETData("notify", EMPTY_CREDENTIAL, TRUE);
	else
	{
            
	    $user= strip_tags($_REQUEST['username']);
	    $pw= strip_tags($_REQUEST['password']);

	    if(isValidCredential($user, FALSE) && isValidCredential($pw, TRUE))
	    {
		//credenziali sintatticamente valide
		$result= login($user, $pw);

		//Wrong credentials
		if($result === FALSE)
		   sendGETData("notify", WRONG_USERNAME_OR_PASSWORD , TRUE);
//		else if($result === -1)
//		    sendGETData("notify", DB_CONNECTION_ERROR , TRUE);
		 
		 //username e password esatti
		 $_SESSION['personAAL_user']= $user;
		 
		 sendGETData("notify", LOGIN_SUCCESS, TRUE);
		 
	    }
	    else
		sendGETData("notify", INVALID_CREDENTIAL, TRUE);
	}
	
    }
 
$selected='en';

function get_options($select)
{
 $languages=array('English'=>'en', 'German'=>'de', 'Norwegian'=>'no');
 $options='';
 while(list($k,$v)=each($languages))
    if($select==$v){ 
        $options.='<option value="'.$v.'" selected>'.$k.'</option>';
    }
    else{ 
        $options.='<option value="'.$v.'">'.$k.'</option>';
    }
    return $options;
}


if(isset($_POST['languages']))
{
    $selected = $_POST['languages'];
}
else
{
    $selected = 'en';
}
    
$_SESSION['languages'] = $selected;
//echo "set Session Language = " . $_SESSION['languages'];
setLanguage();
   
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
        
        <script src='js/plugins/Jquery/jquery.ui.touch-punch.min.js'></script>
        
        
        <!--  AUTH0-->
        
<!--        <script src="http://code.jquery.com/jquery-3.1.0.min.js" type="text/javascript"></script>-->
        <script src="https://cdn.auth0.com/js/auth0/8.7/auth0.min.js"></script>

<!--        <script type="text/javascript" src="//use.typekit.net/iws6ohy.js"></script>
        <script type="text/javascript">try{Typekit.load();}catch(e){}</script>-->

<!--        <meta name="viewport" content="width=device-width, initial-scale=1">-->

        <!-- font awesome from BootstrapCDN -->
<!--        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">-->
<!--        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">-->

        <script>
          var AUTH0_CLIENT_ID = '<?php echo getenv("AUTH0_CLIENT_ID") ?>';
          var AUTH0_DOMAIN = '<?php echo getenv("AUTH0_DOMAIN") ?>';
          var AUTH0_CALLBACK_URL = '<?php echo getenv("AUTH0_CALLBACK_URL") ?>';
        </script>


        <script src="login/public/app.js"> </script>
        <link href="login/public/app.css" rel="stylesheet">
        
        
    </head>
   
   <body class="home">
   
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <span class="mdl-layout-title">PersonAAL</span>
                    
                    <div class="mdl-layout-spacer"></div>
                    
                    <table border="1" width="2" cellspacing="1" cellpadding="1">
                            <tr>
                                <td>
                                <form action=""<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                                <select name="languages" onchange="this.form.submit();">
                                    <?php echo get_options($selected); ?>
                                </select>
                                </form>  
                                </td>
                                <td>
                                    <img src="img/<?php echo $selected; ?>.jpg" width="30" height="20" alt="en"/>
                                </td>
                            </tr>
                    </table>
                </div>
            </header>
            
            <main class="mdl-layout__content">
                <div class="page-content center-content">
                    
                <div class="login-card mdl-card mdl-shadow--4dp">
                    
                    <form role="form" action="login.php" method="POST" onsubmit="return confLogin()">
                        
                        <div class="mdl-card__title">
                            <div class="mdl-card__title-text">
                            <?php echo(LOGIN_WELCOME);?>   
                            </div>
                        </div>
                        <div class="mdl-card__supporting-text mdl-card--expand">
                                <div class="card-choice-textfield">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="text" name="username" id="username">
                                        <label class="mdl-textfield__label" for="username"><?php echo(LOGIN_USERNAME_HINT);?> </label>
                                    </div>
                                </div>
                                <div class="card-choice-textfield">
                                    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
                                        <input class="mdl-textfield__input" type="password" name="password" id="password">
                                        <label class="mdl-textfield__label" for="password"><?php echo(LOGIN_PASSWORD_HINT);?></label>
                                    </div>
                                </div>
                            
                            <?php

                                if(isset($_REQUEST['notify']) && $_REQUEST['notify'] != null && $_REQUEST['notify'] != LOGOUT)
                                {
                                    setLoginErrText($_REQUEST['notify']);
                                }

                            ?>



                        </div>
                        <div class="mdl-card__actions mdl-card--border">
                            <input class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" data-dismiss="modal" type="submit" name ="submit"
                                   value="<?php echo(LOGIN_LOGIN_BUTTON);?>">
                        </div>
                    </form>
                </div>
                <a class="registration-link" href="register.php"><?php echo(LOGIN_REGISTRATION_TEXT);?></a>
                
                </div>
            </main>

<!--            <div class="container">
                <div class="login-page clearfix">
                  <?php if(!$userInfo): ?>
                  <div class="login-box auth0-box before">
                    <a class="btn btn-primary btn-lg btn-login btn-block">SignIn</a>
                  </div>
                  <?php else: ?>
                  <div class="logged-in-box auth0-box logged-in">
                    <h1 id="logo"><img src="//cdn.auth0.com/samples/auth0_logo_final_blue_RGB.png" /></h1>
                    <img class="avatar" src="<?php echo $userInfo['picture'] ?>"/>
                    <h2>Welcome <span class="nickname"><?php echo $userInfo['nickname'] ?></span></h2>
                    <button class="btn-m btn-warning btn-logout">Logout</button>
                  </div>
                  <?php endif ?>
                </div>
            </div>-->

        </div>        
    </body>
    
</html>
