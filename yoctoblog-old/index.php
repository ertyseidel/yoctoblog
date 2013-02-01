<?php
	/*
	*
	* Yoctoblog idea created by Erty Seidel of http://ertyseidel.com
	* Released as FOSS on 2010-08-26 under GNU GPL v3
	*
	*/
	
	// For debugging purposes - turn this off before release.
	error_reporting(E_ALL);
	
	//The messages array is used to return messages to the user
	$messages = array();
	
	//The yocto array is used to hold variables and settings (esp. from the settings.php file)
	$yocto = array();
	
	//This needs to be defined or all external files will return a 403 error - This guarentees that the users/settings/etc. files cannot be accessed except by way of this page
	define('INDEX', 1);
	
	//The functions.php file includes all of the functions that the blog uses
	if(is_file('./functions.php')) {
		include_once('./functions.php');
	} else {
		die('You have to have the file "functions.php" in the same folder as the index.');
	}
	
	//If the 'files' directory does not exist, then the blog is not installed
	if(!is_dir('./files')) {
		if(is_file('./install/install.php')) {
			$yocto['install'] = true;
			if(!empty($_GET)){
				header('Location: ./');
				die();
			}
			include_once('./install/install.php');
		} else {
			$messages = '<strong>Error:</strong> Unable to locate "files" folder OR install.php in the current directory!';
		}
	}
	
	//The start_yocto() method begins the session and validates the user
	start_yocto();

	//Get the settings file
	if(is_file('./files/settings.php')) {
	include_once('./files/settings.php');
	} else {
		die('You have to have the file "settings.php" in the same folder as the index.');
	}
	
	//Get the page contents, based on the page currently being looked for
	$body = '';
	if(!isset($yocto['install'])) {
		if(isset($_GET['settings'])) {
			include('./include/settings.php');
		} else if(isset($_GET['users'])) {
			include('./include/users.php');
		} else if(isset($_GET['createpost'])) {
			include('./include/createpost.php');
		} else if(isset($_GET['editpost'])) {
			include('./include/manageposts.php');
		} else if(isset($_GET['login'])) {
			include('./include/login.php');
		} else if(isset($_GET['logout'])) {
			include('./include/logout.php');
		} else {
			render_posts();
		}
	}
	
	//Now print out the page
	include('./include/page.php');
?>