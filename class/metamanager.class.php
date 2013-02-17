<?php

class MetaManager{

	private $metaCache = array();

	function _loadMeta($meta){
		if(!isset($this->metaCache[$meta])){
			if(is_file($meta)){
				try{
					$json = file_get_contents($meta);
					$this->metaCache[$meta] = json_decode($json, true);
				} catch(Exception $ex){
					echo("Error: Could not read or parse $meta");
					//$GLOBAL['yocto']->messages[] = "Error: Could not read or parse $meta";
				}
			} else{
				echo("Error: Could not find file $meta");
				//$this->messages[] = "Error: Could not find file $meta";
			}
		}
		if(isset($this->metaCache[$meta])){
			return $this->metaCache[$meta];
		}
		return false;
	}

	function getTitle($path = '.'){
		$meta = $this->_loadMeta($path . '/content/meta.yocto.json');
		return $meta['title'];
	}

	function getTemplateMeta($type, $path = '.'){
		$templateMeta = $this->_loadMeta($path . '/content/templates/meta.template.json');
		if(isset($templateMeta['templates'][$type])){
			$templateMeta['templates'][$type]['location'] = $path . '/content/templates/' . $templateMeta['templates'][$type]['location'];
			return $templateMeta['templates'][$type];
		} else{
			echo("Could not get template path for type '$type'");
			return false;
		}
	}

	function getActionMeta($action, $path = '.'){
		$actionMeta = $this->_loadMeta($path . '/content/meta.yocto.json');
		return $actionMeta['actions'][$action];
	}

	function getUserById($id, $path = '.'){
		require_once($path . '/class/user.class.php');
		$yoctoMeta = $this->_loadMeta($path . '/content/meta.yocto.json');
		foreach($yoctoMeta['users'] as $id => $user){
			$yoctoMeta['users'][$id]['id'] = $id;
		}
		return new User($yoctoMeta['users'][$id]);
	}

	function getAction($action, $path = '.'){
		$yoctoMeta = $this->_loadMeta($path . '/content/meta.yocto.json');
		if(isset($yoctoMeta['actions'][$action])){
			$yoctoMeta['actions'][$action]['action'] = $action;
			return $yoctoMeta['actions'][$action];
		}
	}

	function saveMeta($array, $destination){
		$fh = fopen($destination, 'w+');
		if($fh){
			fwrite($fh, json_encode($array, JSON_PRETTY_PRINT));
		} else{
			echo("Error: Unable to open $destination for writing.");
		}
	}

	function getPosts($start, $count){
		$postMeta = $this->_loadMeta('../content/posts/meta.post.json')['posts'];
		foreach($postMeta as $key=>$value){
			$postMeta[$key]['id'] = $key + 1;
		}
		asort($postMeta);
		$postMeta = array_values($postMeta);

		$posts = array();

		if($start < 1) $start = 1;

		for($i = $start - 1; $i < count($postMeta) && $i < $start + $count; $i++){
			$postMeta[$i]['author'] = $this->getUserById($postMeta[$i]['author'], '..');
			$newPost = new Post($postMeta[$i]);
			$posts[] = $newPost; //0-based index
		}
		return $posts;
	}
}