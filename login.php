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
    
//    $accesstoken = $auth0->getAccessToken();
//    $idtoken = $auth0->getIdToken();
    $userInfo = $auth0->getUser();
    
    
    if(!$userInfo)
    {
       echo("<script>console.log('login: No user info');</script>");
    }
    else
    {
//        $user = $userInfo['sub'];
        $user = $userInfo['nickname'];
        

        $_SESSION['personAAL_user']= $user;	 
        sendGETData("notify", LOGIN_SUCCESS, TRUE);	 
        myRedirect("index.php",TRUE);

    }
    
   
      
    //REDIRECT SU HTTPS
//    if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "")
//	HTTPtoHTTPS();
    
//    if(!isCookieEnabled())
//    {
//	myRedirect("login.php?notify=".DISABLED_COOKIE, TRUE);
//    }
    
     
    //utente loggato
//    if (isset($_SESSION['personAAL_user']) && $_SESSION['personAAL_user'] != "")
//    {
//	$_SESSION['personAAL_time']=time();
//	myRedirect("index.php",TRUE);
//    }
    
    
    //PROCEDURA PER IL LOGIN
//    if(isset($_REQUEST['submit']) && $_REQUEST['submit'] !== "")
//    {
//	$_REQUEST['submit']= "";
//	unset($_REQUEST['submit']);
//	
//	if(!isset($_REQUEST['username']) || !isset($_REQUEST['password']) || $_REQUEST['username'] == "" || $_REQUEST['password'] == "")
//	    sendGETData("notify", EMPTY_CREDENTIAL, TRUE);
//	else
//	{
//            
//	    $user= strip_tags($_REQUEST['username']);
//	    $pw= strip_tags($_REQUEST['password']);
//
//	    if(isValidCredential($user, FALSE) && isValidCredential($pw, TRUE))
//	    {
//		//credenziali sintatticamente valide
//		$result= login($user, $pw);
//
//		//Wrong credentials
//		if($result === FALSE)
//		   sendGETData("notify", WRONG_USERNAME_OR_PASSWORD , TRUE);
////		else if($result === -1)
////		    sendGETData("notify", DB_CONNECTION_ERROR , TRUE);
//		 
//		 //username e password esatti
//		 $_SESSION['personAAL_user']= $user;
//		 
//		 sendGETData("notify", LOGIN_SUCCESS, TRUE);
//		 
//	    }
//	    else
//		sendGETData("notify", INVALID_CREDENTIAL, TRUE);
//	}
//	
//    }
    
 
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
        
        <script src="https://cdn.auth0.com/js/auth0/8.7/auth0.min.js"></script>
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
            
            
<!--            AUTH0 login-->

            <div class="container">
                <div class="login-page clearfix">
                  <div class="login-box auth0-box before">
                    <a class="btn btn-primary btn-lg btn-login btn-block">SignIn</a>
                  </div>
                </div>
            </div>
        </div>        
    </body>
</html>
