<?php

	// Twitter for OpenSim (not an app) v0.1.3
	// Developed by devi S.A.S - http://devi.com.co
	// Source code: http://github.com/deviSAS/twitter-opensim
	// Commercial use not allowed. Share & Remix
	
	/*
	
		Add as many twitter apps as you want, also add avatars UUID allowed to use each app.
		* The user only needs the consumer_key in order to tweet. The consumer secret is kept secret :)
	
	*/
	
	define('sid_secure', 'my-random-string-here'); // Change this to a random string
	define('template', 'default.php');  // Template in use
	
	$twitter_keys = array(	array('consumer_key' => 'YOUR_CONSUMER_KEY_mIyWg',
								  'consumer_secret' => 'YOUR_CONSUMER_KEY_SECRET_YzrQRszLCY',
								  'allowed_avatars' => array('UUID', 'UUID')), /* Allowed avatars (unused) */
							/* Multiple apps :) */
							array('consumer_key' => 'ABC', 
								  'consumer_secret' => '123',
								  'allowed_avatars' => array('UUID', 'UUID')),
						);