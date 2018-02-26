<?php
    

//INTERNATIONALIZATION
//$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);


function setLanguage()
{
    if(isset($_SESSION['languages']))
    {
        $lang = $_SESSION['languages'];

        //echo "Language = " . $lang;
        switch ($lang){

            case "de":
                //echo "PAGE DE";
                include("strings_de.php");
                break;

            case "no":
                //echo "PAGE NO";
                include("strings_no.php");
                break;

            case "en":
                //echo "PAGE EN";
                include("strings_en.php");
             break;   

            default:
                //echo $lang ." PAGE EN - Setting Default";
                include("strings_en.php");//include EN in all other cases of different lang detection
            break;
        }
    }
    else {
        echo "Language undefined";
        include("strings_en.php");
    }
}

//SESSION
define("SESSION_TIMEOUT", 86400);
define("LOGOUT", "LOGOUT");

//ERRORS/NOTIFICATION
define("DISABLED_COOKIE","DISABLED_COOKIE");
define("SESSION_EXPIRED", "SESSION_EXPIRED");
define("WRONG_USERNAME_OR_PASSWORD", "WRONG_USERNAME_OR_PASSWORD");
define("DB_CONNECTION_ERROR", "DB_CONNECTION_ERROR");
define("EMPTY_CREDENTIAL", "EMPTY_CREDENTIAL");
define("INVALID_CREDENTIAL", "INVALID_CREDENTIAL");
define("REGISTRATION_SUCCESSFUL", "REGISTRATION_SUCCESSFUL");
define("REGISTRATION_ERROR", "REGISTRATION_ERROR");



function HTTPtoHTTPS()
{
    header('HTTP/1.1 307 temporary redirect');
    header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
    exit(); 
}
        
function myRedirect($path="", $post= FALSE) {
    if($post == TRUE)
	header('HTTP/1.1 303 See Other');
    else
	header('HTTP/1.1 307 temporary redirect');
    // L’URL relativo è accettato solo da HTTP/1.1
    header("Location: "."./".$path);
    exit();
}


//redirect on same page sending data through GET/POST
function sendGETData($varName="", $value="", $post= FALSE) {
    
    if($post == TRUE)
	header('HTTP/1.1 303 See Other');
    else
	header('HTTP/1.1 307 temporary redirect');
    
    // L’URL relativo è accettato solo da HTTP/1.1
    header("Location: ?".$varName."=".$value);
    
    exit();
}

//redirect to different page sending data through GET/POST
function sendDataToPage($location, $varName="", $value="", $post= FALSE) {
    
    if($post == TRUE)
	header('HTTP/1.1 303 See Other');
    else
	header('HTTP/1.1 307 temporary redirect');
    
    // L’URL relativo è accettato solo da HTTP/1.1
    header("Location:". $location ."?".$varName."=".$value);
    
    exit();
}

//distrugge la sessione corrente
function mySessionDestroy()
{
    $_SESSION=array();
	
	if (ini_get("session.use_cookies"))
	{   $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 3600*24, $params["path"],
                $params["domain"], $params["secure"], $params["httponly"]);
	}
	session_destroy(); 
}


function isCookieEnabled()
{
    setcookie('test', 1, time()+30);

    if(count($_COOKIE) > 0)
    {
	//COOKIE ABILITATI
	$_REQUEST['cookies']="";
	unset($_REQUEST['cookies']);
	//echo("abilitati");
	return TRUE;
    }
    else
    {
	/*Serve nel momento in cui nessuno ha mai settato cookie con il browser, ad esempio nelle modalità
	 * incognito. Se non viene usato questo metodo il primo accesso alla pagina darà sempre e comunque "coockie disabilitati",
	 * al refresh successivo invece darà "cookie abilitati". Questo perchè i cookie vengono inviati solo alla richiesta successiva
	 *  alla creazione degli stessi
	 */
	if(!isset($_REQUEST['cookies']) || $_REQUEST['cookies'] == "" || $_REQUEST['cookies'] != "FALSE")
	   sendGETData("cookies","FALSE", TRUE);
	elseif(isset($_REQUEST['cookies']) && $_REQUEST['cookies'] == "FALSE")
	{
	    //COOKIE NON ABILITATI
	    //echo("cookie non abilitati");
	    return FALSE;
	}
	    
    }
}



//controlla se una stringa contiene solo caratteri alfanumerici e se di massimo 16 caratteri
function isValidCredential($str, $pw)
{
    $pattern='/^\w{2,16}$/';
    
    //include special characters
    if($pw === TRUE)
        $pattern='/^\w{2,16}$/';
        
    
    //if(preg_match($pattern, $str)== 1 && strlen($str) <= 16 && strlen($str) >= 8 && $str!= "")
    $match= preg_match($pattern, $str);
    if($match === 1)
	return TRUE;
    else
        return FALSE;
	
}



function setLoginErrText($errMsg)
{
    echo("<div class=\"login-card-alert-text\">");
            
    
    
    switch ($errMsg)
    {
        case WRONG_USERNAME_OR_PASSWORD;
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". WRONG_USERNAME_OR_PASSWORD_MESSAGE ."</div>");
            break;
        
        case DISABLED_COOKIE;
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". DISABLED_COOKIE_MESSAGE ."</div>");
            break;
        
        case EMPTY_CREDENTIAL;
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". EMPTY_CREDENTIAL_MESSAGE ."</div>");
            break;
        
        case INVALID_CREDENTIAL;
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". INVALID_CREDENTIAL_MESSAGE ."</div>");
            break;
        
        case SESSION_EXPIRED:
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". SESSION_EXPIRED_MESSAGE ."</div>");
            break;
	
	case DB_CONNECTION_ERROR:
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". DB_CONNECTION_ERROR_MESSAGE ."</div>");
            break;
        
        case REGISTRATION_SUCCESSFUL:
            echo("<div class=\"text-info\">". REGISTRATION_SUCCESSFUL_MESSAGE ."</div>");
            break;
        
        case REGISTRATION_ERROR:
            echo("<i class=\"material-icons red\">warning</i>");
            echo("<div class=\"text-danger\">". REGISTRATION_ERROR_MESSAGE ."</div>");
            break;
    }
    
    
    echo("</div>");
}

?>
