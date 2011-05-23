<?php

	// Twitter for OpenSim (not an app) v0.1.3
	// Developed by devi S.A.S - http://devi.com.co
	// Source code: http://github.com/deviSAS/twitter-opensim
	// Commercial use not allowed. Share & Remix

	function getPage($web) {
		$html = "";
		  $ch = curl_init($web);
		  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.12) Gecko/20070508 Firefox/1.5.0.12");
		  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt($ch, CURLOPT_HEADER, 0);
		  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		  curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		  curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		  $html = curl_exec($ch);
		  if(curl_errno($ch))
		  {
			  $html = "";
		  }
		  curl_close ($ch);
		return $html;
	}