<?php
	if(!defined('INDEX')){
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	$messages[] = 'Installing Yoctoblog...';
	
	if(install_blog()){
		$messages[] = 'Yoctoblog installed correctly! <a href="">Go to your new Yoctoblog</a>';
	} else{
		$messages[] = '<strong>Error:</strong> Yoctoblog install was interrupted!';
	}

	function install_blog(){
		global $yocto;
		global $messages;
		//Get the default files
		if(!chdir('./install/')) return false;
		if(is_file('./install_files.php')){
			include('./install_files.php');
		} else{
			$messages[] = '<strong>Error:</strong> Could not find file "install_files.php" in install directory!';
			return false;
		}
		//Change the directory to the index directory
		if(!chdir('../')) return false;
		//global $default_files;
		//Create the directories
		if(!(make_dir('./files') && make_dir('./files/posts') && make_dir('./files/uploads'))) return false;
		
		//Create the database files
		if(!(make_file('./files/users.php') && make_file('./files/settings.php'))) return false;
		
		//Fill the settings file
		set_settings($default_files['settings']);
		
		//Fill the users file with a default user
		if(!(make_user('admin', 'root', 3))) return false;
		
		//Refresh the yocto array
		refresh_yocto();
		
		//Create the first post
		if(!(make_post(time(), 'Welcome to Yoctoblog!', $default_files['first_post'], 0))) return false;
		
		return true;
	}