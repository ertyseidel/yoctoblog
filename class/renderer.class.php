<?php
class Renderer{

	private $template;

	private $ajaxes;

	//content for the page
	private $messages;
	private $content;
	private $title;

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
			$yocto = $GLOBALS['yocto'];
			include($this->template);
		} else{
			$this->addMessage("Could not load template file {$this->template}", 'error');
			foreach($this->messages as $msg){
				echo("<p>$msg</p>");
			}
		}
	}

	function addMessage($message, $type){
		if(!in_array()){
			$this->addMessage("'$type' is not a valid type of warning: error, warn, info, debug, or trace", 'warning');
		}
		$this->messages[] = array(
			'message' => $message,
			'type' => $type,
			'backtrace' => debug_backtrace()
		);
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

	/* Template getter and setter by http://stackoverflow.com/questions/4478661/getter-and-setter */

	public function __get($property) {
		if($property == 'ajax'){
			ob_start();
			$this->renderAjax();
			return ob_get_clean();
		} else if($property == 'messsages'){
			foreach($messages as $message){
				echo("<div id='message message-{$message['type']}'>{$message['message']}<div id='backtrace'>{$message['backtrace']}</div></div>");
			}
		} else if (property_exists($this, $property)) {
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