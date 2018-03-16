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
   'scope' => 'openid profile',
   'persist_id_token' => true,
   'persist_access_token' => true,
   'persist_refresh_token' => true,
 ]);


$auth0->logout(); 
mySessionDestroy();
echo("<script>console.log('Logout, returinig to login');</script>");
myRedirect("login.php", TRUE);
die();

?>