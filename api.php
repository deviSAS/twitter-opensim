<?php
	/** API CALLS **/
	
	function apiCall($request) {
		switch($request) {
			// First script call, checks for user registered
			// and updates the system_url with the new one.
			case 'url':
				$access_token = (array) $U_SESSION['access_token'];
				if($U_SESSION['status'] == 'verified') getPage($U_SESSION['system_url'] . 'allow/true/' . $access_token['screen_name']);
				if($system) die('url_updated');
			break;
			// Generates an URL that authenticates the avatar.
			case 'allow': 
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
					echo 'error:' . getError($connection->http_info, true);
				  exit;
				}
			break;
			// Simple status update.
			// @status - string
			case 'post':
				$parameters = array('status' => $_POST['status']);
				$connection = twitterConnect();
				$status = $connection->post('statuses/update', $parameters);
				switch ($connection->http_code) {
					case 200:
						echo 'status:updated';
					break;
					default:
						echo 'status:error:' . getError($connection->http_info, true);
					exit;
				}
			break;
			// Timeline request: Sends to the LSL the lastest post available up to 10
			// @last - integer(id) of the last status received by lsl
			case 'timeline':
				$connection = twitterConnect();
				$paramaters = array('since_id'=>$_POST['last']);
				$timeline = $connection->post('statuses/friends_timeline', $parameters);
				switch ($connection->http_code) {
					case 200:
						$full_timeline = json_decode($timeline);
						$trim_timeline = array();
			
						//Parse the full_timeline into a trimmed one (only neccessary data)
						foreach($full_timeline as $status) {
							$id   = $status->id;
							$user = $status->user->screen_name;
							$msg  = $status->text;
							$trim_timeline[] = array($id, $user, $msg);
						}
						
						//Check the timeline size, output max data and send the remaining to LSL
						
						
					break;
					default:
						getPage($U_SESSION['system_url'] . 'timeline/error/' . getError($connection->http_info, true));
					exit;
				}
			break;
			default:
				echo ':)';
			exit;
		}
	}
	
	// Returns the twitter object authenticated with the access_tokens
	function twitterConnect() {
		$access_token = (array) $U_SESSION['access_token'];
		$OAUTH_TOKEN = $access_token['oauth_token'];
		$OAUTH_TOKEN_SECRET = $access_token['oauth_token_secret'];
		return new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $OAUTH_TOKEN, $OAUTH_TOKEN_SECRET);
	}
	
	// Return a string with the error detail given by twitter.
	function getError((array)$data, $encode = false) {
		$json_data = json_decode($data[0]);
		return ($encode) ? base64_encode($json_data->error) : $json_data->error;
	}