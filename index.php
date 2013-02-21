<?php

require_once('./class/user.class.php');

session_start();

require_once('./class/yocto.class.php');

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
$y = new Yocto();

//installation
if(!is_dir('./content/')){ //install needed
	global $y;
	include('./install/install.php'); //run install
}

$query = $_GET;
$action = isset($_GET['action']) ? $_GET['action'] : 'default';

$y->render($action, $query);