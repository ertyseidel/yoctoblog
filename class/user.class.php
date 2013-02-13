<?php
class User{

	private $id;
	private $username;
	private $password;

	function __construct($meta){
		$this->id = $meta['id'];
		$this->username = $meta['username'];
	}

	public function __get($property) {
    	if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
		return $this;
	}

}