<?php
	
$_SESSION['semester']=$_GET["sem"];

require_once('settings.php');
	
	$login_url = 'https://accounts.google.com/o/oauth2/v2/auth?scope=' . urlencode('https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/plus.me') . '&redirect_uri=' . urlencode(CLIENT_REDIRECT_URL) . '&response_type=code&client_id=' . CLIENT_ID . '&access_type=online';
	
   	
 session_start();	
     
    
    //pristup bez prethodnog logovanja
	
 
    if (!isset($_SESSION["user_email"]) ) {
        header("Location: ".$login_url);
        die();
    }


	
?>
