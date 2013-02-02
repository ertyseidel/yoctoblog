<?php
class Post{
	private $id;
	private $title;
	private $content;
	private $author;
	private $comments;

	function load($fileLocation){
		$json = json_decode(file_get_contents($fileLocation, true));

	}
}