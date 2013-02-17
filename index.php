<?php
require_once('./class/renderer.class.php');
require_once('./class/componentmanager.class.php');
require_once('./class/metamanager.class.php');

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
$GLOBALS['yocto'] = new Renderer();
if(!is_dir('./content/')){ //install needed
	include('./install/install.php'); //run install
}

$getKeys = array_keys($_GET);

if(!isset($getKeys[0])) $getKeys[0] = 'default';

$GLOBALS['yocto']->setAction($getKeys[0]);
$GLOBALS['yocto']->render();