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
	
	/** API CALLS **/
	if($_GET['rq'] == 'url') {
		$access_token = (array) $U_SESSION['access_token'];
		if($U_SESSION['status'] == 'verified') getPage($U_SESSION['system_url'] . 'allow/true/' . $access_token['screen_name']);
		if($system) die('url_updated');
	}
	
	if($_GET['rq'] == 'allow') { // Twitter authentication call
		
		/** Check for status => verified, and use stored session **/
		
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);
		$request_token = $connection->getRequestToken(); // Callback url for this user (ID)

		$U_SESSION['oauth_token'] = $token = $request_token['oauth_token'];
		$U_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
		
		switch ($connection->http_code) {
		  case 200:
			storeSession($ownerkey, $U_SESSION);
			die( 'allow:' . $connection->getAuthorizeURL($token) );
			break;
		  default:
			echo 'error:'; print_r(array(array($CONSUMER_KEY, $CONSUMER_SECRET), $U_SESSION));
			exit;
		}
	} 
	
	/* Implement twitter api calls here */
	
	
	/* 	Simple status update.
		
		Params:
			rq => 'post'
			status => string (required) {POST}
	*/
	if($_GET['rq'] == 'post' && ($U_SESSION['status'] == 'verified')) {
		echo $_POST['status_update'];
		$parameters = array('status' => $_POST['status']);
		
		$access_token = (array) $U_SESSION['access_token'];
		$OAUTH_TOKEN = $access_token['oauth_token'];
		$OAUTH_TOKEN_SECRET = $access_token['oauth_token_secret'];
		
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $OAUTH_TOKEN, $OAUTH_TOKEN_SECRET);
		$status = $connection->post('statuses/update', $parameters);
		switch ($connection->http_code) {
		  case 200:
			getPage($U_SESSION['system_url'] . 'status/updated');
			break;
		  default:
			$status_error = (array)$connection->http_info;
			$status_error = json_decode($status_error[0]); /* ENCODE (base64?) */
			getPage($U_SESSION['system_url'] . 'status/error/' . $status_error->error);
			exit;
		}
	}
	
	print_r($U_SESSION);