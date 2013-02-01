<?php
	error_reporting(E_ALL);
	
	//The messages array is used to return messages to the user
	$messages = array();
	
	//The yocto array is used to hold variables and settings (esp. from the settings.php file)
	$yocto = array();
	
	//This needs to be defined or all external files will return a 403 error - This guarentees that the users/settings/etc. files cannot be accessed except by way of this page
	define('POST_PAGE', 1);
	
	//The functions.php file includes all of the functions that the blog uses
	if(is_file('./functions.php')) {
		include_once('./functions.php');
	} else {
		die('You have to have the file "functions.php" in the same folder as the index.');
	}
	
	//The start_yocto() method begins the session and validates the user
	start_yocto();

	//Get the settings file
	if(is_file('./files/settings.php')) {
	include_once('./files/settings.php');
	} else {
		die('You have to have the file "settings.php" in the same folder as the index.');
	}
	
	//Set the $body to be an empty string
	$body = '';
	
	//Render the post that this page is referencing
	render_post($title, $post, $time, $user, $slug);

	include('./include/page.php');
?>