<!DOCTYPE html>

<?php
    include 'miscLib.php';
    include 'DButils.php';
 
      
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
    
    session_start();
    
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
        
        
    </head>
   
   <body>

        
        
        <!-- The drawer is always open in large screens. The header is always shown, even in small screens. -->
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
                    
                <!-- Your content goes here -->
                
                
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
        </div>        
    </body>
    

</html>
