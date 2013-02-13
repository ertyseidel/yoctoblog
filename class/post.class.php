<?php
class Post{
	private $id;
	private $title;
	private $content;
	private $author;
	private $comments;
	private $timestamp;

	function __construct($meta){
		$this->id = $meta['id'];
		$this->title = $meta['title'];
		$this->content = file_get_contents('../content/posts/' . $meta['id'] . ".post.html");
		$this->author = getUserById($meta['author'], '..');
		$this->comments = $meta['id'];
		$this->timestamp = $meta['timestamp'];
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