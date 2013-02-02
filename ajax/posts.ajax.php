<?php

//get the query array from the path
$query = $_SERVER['PATH_INFO'];
$queryArr = preg_split('/\//', $query);

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

echo($start . "," . $count);