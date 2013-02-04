<?php
function loadMeta($meta){
	if(is_file($meta)){
		try{
			$json = file_get_contents($meta);
			return json_decode($json, true);
		} catch(Exception $ex){
			echo("Error: Could not read or parse $meta");
			//$GLOBAL['yocto']->messages[] = "Error: Could not read or parse $meta";
		}
	} else{
		echo("Error: Could not find file $meta");
		//$this->messages[] = "Error: Could not find file $meta";
	}
	return false;
}