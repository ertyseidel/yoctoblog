<?php

class ComponentManager{

	private $renderer;

	function __construct($renderer){
		$this->renderer = $renderer;
	}

	function __get($property){
		$componentPath = "./content/components/$property.component.php";
		if(is_file($componentPath)){
			$yocto = $GLOBALS['yocto'];
			include($componentPath);
		} else {
			$this->renderer->addMessage("Warning: Could not load component $componentPath", 'warn');
		}
	}

}