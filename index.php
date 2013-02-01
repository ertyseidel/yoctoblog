<?php

// For debugging purposes - turn this off before release.
error_reporting(E_ALL);

//main render section
check_security();
if(install_needed()){
	do_install();
}
render_page();

function install_needed(){
	return !is_dir('./content/');
}

function do_install(){
	include('./install/install.php');
}

function check_security(){
	//todo
}

function render_page($yocto){
	//todo
	echo('Yoctoblog page rendered');
}