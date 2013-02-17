<?php
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

//get the query array from the path
$query = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '0';
$params = preg_split('/\//', $query);

//turn everything in the query array into an int for safety
array_map(function($i){return (int)$i;}, $params);

//get rid of any starting empty cells
while(count($params) > 0 && !strlen($params[0])) array_shift($params);

$action = array_shift($params);

$y->render($action, $params);