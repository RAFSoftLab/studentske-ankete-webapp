<?php

/*

// Holds the Google application Client Id, Client Secret and Redirect Url
require_once('settings.php');

*/

// Holds the various APIs involved as a PHP class. 


require_once('google-login-api.php');


//This $_GET["code"] variable value received after user has login into their Google Account redirct to PHP script then this variable value has been received
if(isset($_GET["code"]))
{
 //It will Attempt to exchange a code for an valid authentication token.
 $token = $google_client->fetchAccessTokenWithAuthCode($_GET["code"]);

 //This condition will check there is any error occur during geting authentication token. If there is no any error occur then it will execute if block of code/
 if(!isset($token['error']))
 {
  //Set the access token used for requests
  $google_client->setAccessToken($token['access_token']);

  //Store "access_token" value in $_SESSION variable for future use.
  $_SESSION['access_token'] = $token['access_token'];

  //Create Object of Google Service OAuth 2 class
  $google_service = new Google_Service_Oauth2($google_client);

  //Get user profile data from google
  $data = $google_service->userinfo->get();

  //Below you can find Get profile data and store into $_SESSION variable
  if(!empty($data['given_name']))
  {
   $_SESSION['user_first_name'] = $data['given_name'];
  }

  if(!empty($data['family_name']))
  {
   $_SESSION['user_last_name'] = $data['family_name'];
  }

  if(!empty($data['email']))
  {
   $_SESSION['user_email'] = $data['email'];
  }

  if(!empty($data['gender']))
  {
   $_SESSION['user_gender'] = $data['gender'];
  }

  if(!empty($data['picture']))
  {
   $_SESSION['user_image'] = $data['picture'];
  }
 }
}

echo  $_SESSION['user_email_address'] ;


if(!(substr($_SESSION["user_email"],-6)==='raf.rs')){
		//	echo '<br>Da biste mogli da popunite anketu morate biti ulogovani preko <b>raf.rs</b> google naloga!';

		echo '<br>Da biste pristupili rezultatima ankete morate biti ulogovani preko <b>raf.rs</b> google naloga!';
				
			die();
   		}else{
		
		   header('Location: survey.php');
		}


/*

// Google passes a parameter 'code' in the Redirect Url
if(isset($_GET['code'])) {
	try {
		$gapi = new GoogleLoginApi();
		
		// Get the access token 
		$data = $gapi->GetAccessToken(CLIENT_ID, CLIENT_REDIRECT_URL, CLIENT_SECRET, $_GET['code']);

		// Access Tokem
		$access_token = $data['access_token'];
		
		// Get user information
		$user_info = $gapi->GetUserProfileInfo($access_token);

		echo '<pre>';print_r($user_info); echo '</pre>';


		// Now that the user is logged in you may want to start some session variables
		$_SESSION['logged_in'] = 1;		

		$_SESSION['user_email'] = $user_info['emails'][0]['value'];
		
		echo "Google nalog:".$_SESSION['user_email'];
		
		//echo 'Proba';

			
    		if(!(substr($_SESSION["user_email"],-6)==='raf.rs')){
			echo '<br>Da biste mogli da popunite anketu morate biti ulogovani preko <b>raf.rs</b> google naloga!';
				
			die();
   		}else{
		
		   header('Location: survey.php');
		}
	}
	catch(Exception $e) {
		echo $e->getMessage();
		exit();
	}
}
*/

?>
