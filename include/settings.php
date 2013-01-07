<?php
	if(!defined('INDEX')) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	
	if(isset($_POST['settings'])){
		set_settings($_POST['settings']);
	}
	
	$body .= '<p>Settings</p><form method="post" action="./?settings"><table>';
	foreach($yocto['settings'] as $key => $value){
		$body .= "<tr><td>{$key}</td><td><input type='text' name='settings[{$key}]' value='{$value}' /></td></tr>";
	}
	$body .= '</table><input type="submit" value="Save Changes" /></form>';