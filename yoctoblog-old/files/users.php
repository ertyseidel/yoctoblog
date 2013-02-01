<?php
	/*DO NOT EDIT*/
	if(!(defined('INDEX') || defined('POST_PAGE'))){
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	/*OKAY YOU CAN EDIT NOW*/if(isset($yocto['users'])) unset($yocto['users']);

$yocto['users'] = array();$yocto['users'][0] = array('name'=>'admin','pass'=>'dc76e9f0c0006e8f919e0c515c66dbba3982f785','level'=>'3');
	