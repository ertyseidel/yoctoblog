<?php

require_once('../class/metamanager.class.php');
require_once('../class/post.class.php');

//get the query array from the path
$query = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '0';
$queryArr = preg_split('/\//', $query);

//check to see if the last parameter specifies a return type
$returnType = 'html';
$returnTypes = array('json', 'html', 'rss');
if(in_array($queryArr[count($queryArr) - 1], $returnTypes)){
	$returnType = $queryArr[count($queryArr) - 1];
	array_pop($queryArr);
}

//turn everything in the query array into an int for safety
array_map(function($i){return (int)$i;}, $queryArr);

//get rid of any starting empty cells
while(count($queryArr) > 0 && !strlen($queryArr[0])) array_shift($queryArr);

$start = 0;
$count = 0;

switch(count($queryArr)){
	case 0: //return the first ten posts
		$start = 0;
		$count = 10;
		break;
	case 1: //return the post with the given id
		$start = $queryArr[0];
		$count = 1;
		break;
	case 2: //return $queryArr[1] posts, starting on $queryArr[0]
		$start = $queryArr[0];
		$count = $queryArr[1];
		break;
	default: //what
}

switch($returnType){
	case 'json':
		echo('[');
		foreach($posts as $post){
			echo($post->getJson());
		}
		echo(']');
		break;
	case 'html':
		$template = $meta->templates['html'];
		foreach($posts as $post){
			include($template['location']);
		}
		break;
	case 'rss':
		$template = $meta->templates['rss'];
		foreach($posts as $post){
			include($template['location']);
		}
}
