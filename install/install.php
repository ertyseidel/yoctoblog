<?php
//create the "content" folder, where all of the user data will go
create_folder('./content');
//create the templates folder /content/templates
create_folder('./content/templates');
//create the posts folder /content/posts
create_folder('./content/posts');
//create the users folder /content/users
create_folder('./content/users');

//now we copy some of the default content from the install folder to the existing folders
copy_default_file('meta.user.php', './content/users');
copy_default_file('admin.user.php', './content/users');
copy_default_file('meta.template.php', './content/templates');
copy_default_file('default.template.php', './content/templates');
copy_default_file('meta.post.php', './content/posts');
copy_default_file('1.post.php', './content/posts');

function create_folder($dir){
	yocto['messages'][] = !is_dir($dir) ? mkdir($dir) ? "Created folder  $dir" : "Error: Could not create folder $dir - Check your permissions." : "Error: $dir already exists.";
}

function copy_default_file($file, $target_dir){
	$origin = './install/defaults/' . $file;
	$destination = $target_dir . '/' . $file;
	yocto['messages'][] = file_exists($origin) && copy($origin, $destination)? "Successfully copied $origin to $destination." : "Error: Could not copy $origin to $destination - Check your permissions.";
}