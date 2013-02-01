<?php
class Renderer{
	public $content;
	private $template;
	public $messages = array();

	function setTemplate($tmpl){
		$this->template = $this->loadMeta('./content/templates/meta.template.json')[$this->template][$tmpl];
	}

	function render(){
		if($this->template && $this->template != '' && is_file($this->template)){
			include($this->template);
		} else{
			$this->messages[] = "Could not load template file {$this->template}";
			foreach($this->messages as $msg){
				echo("<p>$msg</p>");
			}
		}
	}

	function loadMeta($meta){
		if(is_file($meta)){
			try{
				$json = file_get_contents($meta);
				return json_decode($json);
			} catch(Exception $ex){
				$this->messages[] = "Error: Could not read or parse $meta";
			}
		} else{
			$this->messages[] = "Error: Could not find file $meta";
		}
		return false;
	}

}