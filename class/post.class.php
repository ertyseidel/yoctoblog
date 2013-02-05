<?php
class Post{
	private $id;
	private $title;
	private $content;
	private $author;
	private $comments;

	function __construct($meta){
		$this->id = $meta['id'];
		$this->title = $meta['title'];
		$this->content = file_get_contents('../content/posts/' . $meta['id'] . ".post.html");
		$this->author = $meta['author'];
		$this->comments = $meta['id'];
	}

	function load($fileLocation){
		$json = json_decode(file_get_contents($fileLocation, true));
	}

	function writeJson($includeContent = false, $includeComments = false){
		$array = array(
			'id' => $this->id,
			'title' => $this->title,
			'author' => $this->author
		);
		if($includeContent) $array['content'] = $this->content;
		if($includeComments) $array['comments'] = $this->comments;
		return json_encode($array);
	}

	function writeHTML(){

	}
}