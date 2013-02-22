<?php

class MetaManager{

	private $metaCache = array();

	function __get($meta){
		switch($meta){
			case 'posts':
				if(isset($metaCache['posts'])) return $metaCache['posts'];
				$postMeta = $this->_loadMeta('/content/posts/meta.post.json')['posts'];
				asort($postMeta);
				foreach($postMeta as $key=>$value){
					$postMeta[$key]['id'] = $key;
				}
				$postMeta = array_values($postMeta);
				return $postMeta;
			case 'templates':
				if(isset($metaCache['templates'])) return $metaCache['templates'];
				$templateMeta = $this->_loadMeta('/content/templates/meta.template.json');
				$metaCache['templates'] = $templateMeta['templates'];
				foreach($metaCache['templates'] as $key=>$value){
					$metaCache['templates'][$key] = './content/templates/' . $metaCache['templates'][$key];
				}
				return $metaCache['templates'];
			case 'yocto':
				if(isset($metaCache['yocto'])) return $metaCache['yocto'];
				$yoctoMeta = $this->_loadMeta('/content/meta.yocto.json');
				$metaCache['yocto'] = $yoctoMeta;
				return $metaCache['yocto'];
		}
	}

	function _loadMeta($meta, $path = '.'){
		if(!isset($this->metaCache[$meta])){
			if(is_file($path . $meta)){
				try{
					$json = file_get_contents($path . $meta);
					$this->metaCache[$meta] = json_decode($json, true);
				} catch(Exception $ex){
					echo("Error: Could not read or parse $path$meta");
					//$GLOBAL['yocto']->messages[] = "Error: Could not read or parse $meta";
				}
			} else{
				echo("Error: Could not find file $path$meta");
				//$this->messages[] = "Error: Could not find file $meta";
			}
		}
		if(isset($this->metaCache[$meta])){
			return $this->metaCache[$meta];
		}
		return false;
	}

	function saveMeta($array, $destination){
		$fh = fopen($destination, 'w+');
		if($fh){
			fwrite($fh, json_encode($array, JSON_PRETTY_PRINT));
		} else{
			echo("Error: Unable to open $destination for writing.");
		}
		$this->resetCache();
	}

	function resetCache(){
		$this->metaCache = array();
	}

}