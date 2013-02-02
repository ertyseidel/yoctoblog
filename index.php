<?php

require_once('./core/classes/renderer.class.php');
require_once('./core/classes/post.class.php');

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
$GLOBALS['yocto'] = new Renderer();
if(!is_dir('./content/')){ //install needed
	include('./install/install.php'); //run install
}
$GLOBALS['yocto']->setTitle('Welcome to Yoctoblog');
$GLOBALS['yocto']->setTemplate('default');
$GLOBALS['yocto']->render();