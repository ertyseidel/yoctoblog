<?php
class Renderer{

	private $template;
	private $ajaxes;
	private $_messages;
	private $_content;
	private $_title;

	function __construct(){
		$this->_messages = array();
		$this->_ajaxes = array();
	}

	function setTemplate($tmpl){
		$this->template = getTemplatePathFor($tmpl);
	}

	function setTitle($title){
		$this->_title = $title;
	}

	function render(){
		if($this->template && $this->template != '' && is_file($this->template)){
			$yocto = $GLOBALS['yocto'];
			include($this->template);
		} else{
			echo("Could not load template file {$this->template}");
		}
	}

	function addMessage($message, $type){
		if(!in_array($type, array('error', 'warn', 'info', 'debug', 'trace'))){
			$this->addMessage("'$type' is not a valid type of warning: error, warn, info, debug, or trace", 'warning');
		}
		$this->_messages[] = array(
			'message' => $message,
			'type' => $type
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

	public function __get($property) {
		if($property == 'ajax'){
			ob_start();
			$this->renderAjax();
			return ob_get_clean();
		} else if($property == 'messages'){
			ob_start();
			foreach($this->_messages as $message){
				echo("<div id='message message-{$message['type']}'>{$message['message']}</div>");
			}
	  		return ob_get_clean();
		} else if($property == 'components'){
			return new ComponentManager($this);
		} else if (property_exists($this, $property)) {
			return $this->$property;
		}
	}
}