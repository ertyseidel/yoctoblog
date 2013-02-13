<?php

$metaCache = array();

function loadMeta($meta){
	global $metaCache;
	if(!isset($metaCache[$meta])){
		if(is_file($meta)){
			try{
				$json = file_get_contents($meta);
				$metaCache[$meta] = json_decode($json, true);
			} catch(Exception $ex){
				echo("Error: Could not read or parse $meta");
				//$GLOBAL['yocto']->messages[] = "Error: Could not read or parse $meta";
			}
		} else{
			echo("Error: Could not find file $meta");
			//$this->messages[] = "Error: Could not find file $meta";
		}
	}
	if(isset($metaCache[$meta])){
		return $metaCache[$meta];
	}
	return false;
}

function getTemplatePathFor($type, $path = '.'){
	$templateMeta = loadMeta($path . '/content/templates/meta.template.json');
	return $path . '/content/templates/' . $templateMeta['templates'][$type]['location'];
}

function getUserById($id, $path = '.'){
	require_once($path . '/class/user.class.php');
	$yoctoMeta = loadMeta($path . '/content/meta.yocto.json');
	foreach($yoctoMeta['users'] as $id => $user){
		$yoctoMeta['users'][$id]['id'] = $id;
	}
	return new User($yoctoMeta['users'][$id]);
}

function getAction($action, $path = '.'){
	$yoctoMeta = loadMeta($path . '/content/meta.yocto.json');
	if(isset($yoctoMeta['actions'][$action])){
		$yoctoMeta['actions'][$action]['action'] = $action;
		return $yoctoMeta['actions'][$action];
	}
}

function saveMeta($array, $destination){
	$fh = fopen($destination, 'w+');
	if($fh){
		fwrite($fh, json_encode($array, JSON_PRETTY_PRINT));
	} else{
		echo("Error: Unable to open $destination for writing.");
	}
}