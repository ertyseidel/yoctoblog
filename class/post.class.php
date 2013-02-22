<?php
class Post{

	private $metaManager;
	private $meta;
	private $_content;

	function __construct(&$metaManager, $meta){
		$this->metaManager = $metaManager;
		$this->meta = $meta;
		$this->_content = false;
	}

	function getJson($includeContent = false, $includeComments = false){
		return json_encode($meta);
	}

	function posttime($format = "Y-m-d h:m:s"){
		return date($format, strtotime($this->meta['timestamp']));
	}

	public function __get($property) {
		switch($property){
			case 'author':
				return $this->metaManager->yocto['users'][$this->meta['author']]['username'];
			case 'content':
				if(!$this->_content){
					$this->_content = file_get_contents('./content/posts/' . $this->meta['id'] . ".post.html");
				}
				return $this->_content;
			default:
				if(isset($this->meta[$property])) return $this->meta[$property];
				else return '';
		}
	}
}