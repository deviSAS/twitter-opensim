<?php

	// Twitter for OpenSim (not an app) v0.1.3
	// Developed by devi S.A.S - http://devi.com.co
	// Source code: http://github.com/deviSAS/twitter-opensim
	// Commercial use not allowed. Share & Remix
	
	function getSession($UUID, $initParams) {
		$session_file = sessionFile($UUID);
		if(is_file($session_file)) {
			return (array) json_decode(fileContents($session_file));
		}
		else {
			//Create session and return default init params.
			writeSession($session_file, $initParams);
			return $initParams;
		}
	}
	
	function searchSession($token) {
		if ($handle = opendir('./sessions')) {
			while (false !== ($file = readdir($handle))) {
				$session = (array) json_decode(fileContents('./sessions/' . $file));
				if($session['oauth_token'] == $token) return $session;
			}
		} return false;
	}
	
	// Returns the *.se file contents
	function fileContents($fileName) {
		$file = fopen($fileName, 'r');
		if(!$file) return false;
		$content = fread($file, filesize($fileName));
		fclose($file); return $content;
	}
	
	// Writes the given $data into $session file (*.se)
	function writeSession($session, $data) {
		$json_data = json_encode($data);
		truncateSession($session);
		$file = fopen($session, 'w+');
		fwrite($file, $json_data);
		fclose($file);
	}
	
	// Wipes out the $session file (*.se)
	function truncateSession($session) {
		$file = fopen($session, 'w');
		fclose($file);
	}
	
	// Deletes a session
	function deleteSession($UUID) {
		unlink(sessionFile($UUID));
	}
	
	// Same behaviour as #writeSession but $UUID param is given to identify the $session
	function storeSession($UUID, $params) {
		$session_file = sessionFile($UUID);
		//if(is_file($session_file))	
		writeSession($session_file, $params);
		//else echo 'error:wrong_sid';
	}
	
	// Returns the encrypted session_file
	function sessionFile($UUID) {
		return 'sessions/' . $UUID . sid_secure . '.se';
	}
		