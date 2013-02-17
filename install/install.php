<?php
//create the "content" folder, where all of the user data will go
create_folder('./content');
//create the templates folder /content/templates
create_folder('./content/templates');
//create the posts folder /content/posts
create_folder('./content/posts');
//create the resources folder /content/resources
create_folder('./content/resources');
create_folder('./content/components');

//now we copy some of the default content from the install folder to the existing folders
$files = array(
	'templates' => array(
		'meta.template.json',
		'default.template.php',
		'post.template.php',
		'login.template.php'
	),
	'posts' => array(
		'meta.post.json',
		'1.post.html'
	),
	'resources' => array(
		'style.css'
	),
	'components' => array(
		'sidebar.component.php'
	)
);

foreach($files as $destination => $list){
	foreach($list as $file){
		copy_default_file($file, './content/' . $destination);
	}
}

//now we create the default users and settings
$meta = array(
	"users" => array(
		1 => array(
			"username" => "admin",
			"password" => "root"
		)
	),
	"actions" => array(
		"default" => array(
			"template" => "default",
			"title" => '',
			"ajax" => array(
				'ajax-posts' => 'posts.ajax.php'
			)
		),
		"login" => array(
			"template" => "login",
			"title" => 'Login'
		)
	),
	"title"	=> "Yoctoblog",
	"subtitle" => "Standalone Macroblogging Microplatform"
);

saveMeta($meta, './content/meta.yocto.json');
$GLOBALS['yocto']->addMessage('Successfully created meta.yocto.json.', 'info');

$GLOBALS['yocto']->addMessage('First-time installation complete! Welcome to Yoctoblog!', 'info');

function create_folder($dir){
	if(!is_dir($dir)){
		if(mkdir($dir)){
			$GLOBALS['yocto']->addMessage("Created folder $dir", 'info');
		} else{
			$GLOBALS['yocto']->addMessage("Error: Could not create folder $dir - Check your permissions.", 'error');
		}
	} else{
		$GLOBALS['yocto']->addMessage("Error: $dir already exists.", 'error');
	}
}

function copy_default_file($file, $target_dir){
	$origin = './install/defaults/' . $file;
	$destination = $target_dir . '/' . $file;
	if(file_exists($origin) && copy($origin, $destination)){
		$GLOBALS['yocto']->addMessage("Successfully copied $origin to $destination.", 'info');
	} else {
		$GLOBALS['yocto']->addMessage("Error: Could not copy $origin to $destination - Check your permissions.", 'error');
	}
}