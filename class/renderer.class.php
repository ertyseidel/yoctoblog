<?php
class Renderer{

	private $template;

	public $ajaxes;

	//content for the page
	public $messages;
	public $content;
	public $title;

	function __construct(){
		$this->messages = array();
		$this->ajaxes = array();
	}

	function setTemplate($tmpl){
		$this->template = getTemplatePathFor($tmpl);
	}

	function setTitle($title){
		$this->title = $title;
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

	function renderAjax(){
		if(count($this->ajaxes) == 0) return;
		echo('<script type="text/javascript" src="./js/miniajax.min.js"></script><script type="text/javascript">window.addEventListener(\'load\', function(){');
		foreach($this->ajaxes as $ajax){
			if($ajax['type'] == 'get'){
				echo("ajax.update(\"{$ajax['source']}\", \"{$ajax['id']}\");");
			}
		}
		echo('}, false);</script>');
	}

	function registerAjax($id, $source, $type = 'get'){
		$this->ajaxes[] = array('id' => $id, 'source' => $source, 'type' => $type);
	}
}