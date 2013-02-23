<?php

rcopy('./install/defaults/', './content/');

//now we create the default users and settings
$meta = array(
	"users" => array(),
	"title"	=> "Yoctoblog",
	"subtitle" => "Standalone Macroblogging Microplatform",
  "max_user" => 0,
  "max_post" => 0
);

$y->metaManager->saveMeta($meta, './content/meta.yocto.json');

require_once('./class/user.class.php');
$y->createUser('admin', 'root');

$y->addMessage('First-time installation complete! Welcome to Yoctoblog!', 'info');

//From http://www.php.net/manual/en/function.copy.php#104020
function rcopy($src, $dst) {
  if (file_exists($dst)) rrmdir($dst);
  if (is_dir($src)) {
    mkdir($dst);
    $files = scandir($src);
    foreach ($files as $file)
    if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
  }
  else if (file_exists($src)) copy($src, $dst);
}