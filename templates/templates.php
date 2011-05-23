<?php
	function loadTemplate($params) {
		extract($params);
		require_once(template);
	}