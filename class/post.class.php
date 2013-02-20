<?php
class Post{

	private $metaManager;
	private $meta;
	private $content;

	function __construct(&$metaManager, $meta){
		$this->metaManager = $metaManager;
		$this->meta = $meta;
		$this->content = file_get_contents('./content/posts/' . $meta['id'] . ".post.html");
	}

	function getJson($includeContent = false, $includeComments = false){
		$array = array(
			'id' => $this->id,
			'title' => $this->title,
			'author' => $this->author
		);
		if($includeContent) $array['content'] = $this->content;
		if($includeComments) $array['comments'] = $this->comments;
		return json_encode($array);
	}

	function time($format = "Y-m-d h:m:s"){
		return date($format, $this->timestamp);
	}

	public function __get($property) {
		switch($property){
			case 'author':
				return $this->metaManager->yocto['users'][$this->meta['author']]['username'];
			case 'content':
				return $this->content;
			default:
				if(isset($this->meta[$property])) return $this->meta[$property];
		}
	}
}