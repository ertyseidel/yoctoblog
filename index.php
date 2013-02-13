<?php
require_once('./class/renderer.class.php');
require_once('./class/componentmanager.class.php');
require_once('./meta-functions.php');

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
$GLOBALS['yocto'] = new Renderer();
if(!is_dir('./content/')){ //install needed
	include('./install/install.php'); //run install
}

$getKeys = array_keys($_GET);

if(!isset($getKeys[0])) $getKeys[0] = 'default';

$actions = getAction($getKeys[0]);

if($actions){
	$GLOBALS['yocto']->setTemplate($actions['template']);
} else{
	$GLOBALS['yocto']->registerAjax("ajax-posts", "./ajax/posts.ajax.php");
	$GLOBALS['yocto']->setTitle('Yoctoblog');
	$GLOBALS['yocto']->setTemplate('default');
	$GLOBALS['yocto']->render();
}