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
	$yoctoMeta = loadMeta($path . '/content/meta.yocto.json');
	return $yoctoMeta['users'][$id];
}