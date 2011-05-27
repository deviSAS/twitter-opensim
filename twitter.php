<?php

	// Twitter for OpenSim (not an app) v0.1.3
	// Developed by devi S.A.S - http://devi.com.co
	// Source code: http://github.com/deviSAS/twitter-opensim
	// Commercial use not allowed. Share & Remix
	
	require_once('auth/twitterauth.php');
	require_once('sessions/sessions.php');
	require_once('templates/templates.php');
	require_once('tokens.php');
	require_once('functions.php');
	require_once('api.php');
	
	/** Gather some data from LSL **/
	
	$USE_APACHE_HEADERS = FALSE; // switch to false if you need cgi methods
	if($USE_APACHE_HEADERS) {
		$headers 	    = apache_request_headers();
		$ownername     	= $headers["X-SecondLife-Owner-Name"];
		$ownerkey     	= $headers["X-SecondLife-Owner-Key"];
	} else {
		$db             = $GLOBALS;
		$headers 		= $db['HTTP_ENV_VARS'];
		$ownername      = $headers["HTTP_X_SECONDLIFE_OWNER_NAME"];
		$ownerkey     	= $headers["HTTP_X_SECONDLIFE_OWNER_KEY"];
	}
	
	if(empty($ownerkey)) {
		loadTemplate(array('title'=>'Twitter for OpenSim!', 'message'=>'You have installed twitter for OpenSimulator, please configure the <strong>tokens.php</strong> file and set up your in-world script (find it under lsl directory).'));
		exit;
	}
	
	/** Get the consumer key from post (our LSL sends this) **/
	
	$CONSUMER_KEY = $_POST['consumer_key'];
	foreach($twitter_keys as $app) { // Loop trhough our $twitter_keys (see tokens.php)
		if($app['consumer_key'] == $CONSUMER_KEY) { // If we have a match, get the $CONSUMER_SECRET
			$CONSUMER_SECRET = $app['consumer_secret'];
		}
	} 
	
	/** Get the session data (if exists), this data contains in use oauth_tokens (if permissions from user has been granted). **/
	$U_SESSION = getSession($ownerkey,
					array('user_name' => $ownername, 'user_key' => $ownerkey, 'session_date' => time(),
						  'consumer_key' => $CONSUMER_KEY, 'system_url' => $_POST['this_url']));
	

	if($U_SESSION['system_url'] != $_POST['this_url']) { // system_url needs to be the same as $post[this_url]
		$U_SESSION['system_url'] = $_POST['this_url'];
		storeSession($ownerkey, $U_SESSION);
		$system = true;
	}
	
	apiCall($_GET['rq']); // Api moved to api.php