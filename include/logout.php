<?php
	if(!defined('INDEX')) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	user_logout();
	if(isset($_SESSION['user'])) {
		//This should no longer be set
		$messages[] = 'There was an error logging you out!';
	} else {
		$messages[] = 'You have been logged out! <a href="./">Return to the index?</a>';
	}