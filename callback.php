<?php

	// Twitter for OpenSim (not an app) v0.1.3
	// Developed by devi S.A.S - http://devi.com.co
	// Source code: http://github.com/deviSAS/twitter-opensim
	// Commercial use not allowed. Share & Remix
	
	require_once('./auth/twitterauth.php');
	require_once('./sessions/sessions.php');
	require_once('./templates/templates.php');
	require_once('functions.php');
	require_once('tokens.php');
	
	$U_SESSION = searchSession($_REQUEST['oauth_token']); //Find the session with the current oauth_token
	if(!$U_SESSION) die('no session found'); //If there is no session with this user ID

	$ownerkey = $U_SESSION['user_key'];
	
	/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
	$connection = new TwitterOAuth($U_SESSION['consumer_key'], $U_SESSION['consumer_secret'], $U_SESSION['oauth_token'], $U_SESSION['oauth_token_secret']);

	/* Request access tokens from twitter */
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

	/* Save the access tokens. Normally these would be saved in a database for future use. */
	$U_SESSION['access_token'] = (array)$access_token;

	/* Remove no longer needed request tokens */
	$U_SESSION['oauth_token'] = '';
	$U_SESSION['oauth_token_secret'] = '';

	/* If HTTP response is 200 continue otherwise send to connect page to retry */
	if (200 == $connection->http_code) {
		$U_SESSION['status'] = 'verified';
		$U_SESSION['session_time'] = time();
		storeSession($ownerkey, $U_SESSION);
		
		getPage($U_SESSION['system_url'] . 'allow/true/' . $access_token['screen_name']); /* Send to LSL */
		
		loadTemplate(array('title'=>'Logged In!', 'message' => 'You have logged in into Twitter with your Second Life account, close this window and start tweeting from in-world!'));
	} else {
		getPage($U_SESSION['system_url'] . 'allow/false/');
		deleteSession($ownerkey);
		$error = (array)$connection->http_info;
		$error = json_decode($status_error[0]);
		loadTemplate(array('title'=>'Error:', 'message' => 'Titter responded with the following message: <br/><br/>' . $error));
	}
