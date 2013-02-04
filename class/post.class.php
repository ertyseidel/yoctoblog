<?php
class Post{
	private $id;
	private $title;
	private $content;
	private $author;
	private $comments;

	function __construct($meta){
		$id = $meta['id'];
		$title = $meta['title'];
		$content = file_get_contents($meta['location']);
		$author = $meta['user'];
		$comments = $meta['id'];
	}

	function load($fileLocation){
		$json = json_decode(file_get_contents($fileLocation, true));
	}
}