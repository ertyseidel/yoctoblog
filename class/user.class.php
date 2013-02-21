<?php
class User{

	private $meta;

	function __construct($meta){
		$this->meta = array();
		$this->meta['id'] = isset($meta['id']) ? $meta['id'] : 0;
		if(isset($meta['salt']) && isset($meta['password'])){
			$this->meta['salt'] = $meta['salt'];
			$this->meta['password'] = $meta['password'];
		} else{
			$this->meta['salt'] = substr(md5(rand()),0, 7);
			$this->meta['password'] = crypt($meta['password'], $this->meta['salt']);
		}
		$this->meta['username'] = $meta['username'];
	}

	public function __get($property) {
    	if(isset($this->meta[$property])){
			return $this->meta[$property];
		}
	}

	function toArray(){
		return $this->meta;
	}

}