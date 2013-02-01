<?php
	/*DO NOT EDIT*/
	if(!(defined('INDEX') || defined('POST_PAGE'))){
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	/*OKAY YOU CAN EDIT NOW*/	$yocto['settings']['abs_path'] = '/yoctoblog/';
	$yocto['settings']['title'] = 'Yoctoblog';
	$yocto['settings']['subtitle'] = 'Macroblogging Microplatform';
	$yocto['settings']['style'] = 'css/default.css';
	$yocto['settings']['lang'] = 'en-US';
	$yocto['settings']['charset'] = 'UTF-8';
