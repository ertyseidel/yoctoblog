<?php

class ComponentManager{

	private $yocto;

	function __construct($yocto){
		$this->yocto = $yocto;
	}

	function __get($property){
		$componentPath = "./content/components/$property.component.php";
		if(is_file($componentPath)){
			$y = $this->yocto;
			include($componentPath);
		} else {
			$this->yocto->addMessage("Warning: Could not load component $componentPath", 'warn');
		}
	}

}