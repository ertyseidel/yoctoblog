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
				if(isset($this->meta['id'])){
					if(!$this->_content){
						$this->_content = file_get_contents('./content/posts/' . $this->meta['id'] . ".post.html");
					}
				}
				return $this->_content;
			case 'date':
				return date('Y-m-d',strtotime($this->meta['timestamp']));
			case 'time':
				return date('H:i:00', strtotime($this->meta['timestamp']));
			case 'link':
				return "./index.php?action=default&start={$this->meta['id']}&count=1";
			case 'editlink':
				return "./index.php?action=edit&id={$this->meta['id']}";
			default:
				if(isset($this->meta[$property])) return $this->meta[$property];
				else return '';
		}
	}
}