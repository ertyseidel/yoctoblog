<?php

require_once('./core/renderer.class.php');
require_once('./core/component.class.php');

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
$GLOBALS['yocto'] = new Renderer();
if(!is_dir('./content/')){ //install needed
	include('./install/install.php'); //run install
}
$GLOBALS['yocto']->setTemplate('default');
$GLOBALS['yocto']->render();