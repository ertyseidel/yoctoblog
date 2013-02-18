<?php
class Renderer{

	private $action;
	private $template;
	private $_ajaxes;
	private $_messages;
	private $content;
	private $title;
	private $metaManager;

	function __construct(){
		$this->_messages = array();
		$this->ajaxes = array();
		$this->metaManager = new MetaManager();
	}

	function setAction($action, $path = '.'){
		$this->action = $this->metaManager->getActionMeta($action, $path);
		if(!$this->action){
			$this->action = $this->metaManager->getActionMeta('default', $path);
		}
		if(isset($this->action['secure']) && $this->action['secure']){
			//todo
		}
		if(isset($this->action['ajax'])){
			foreach($this->action['ajax'] as $id=>$path){
				$this->registerAjax($id, $path);
			}
		}
		$this->title = $this->metaManager->getTitle();
		if(isset($this->action['title']) && $this->action['title']){
			$this->title .= ' - ' . $this->action['title'];
		}
		$this->template = $this->metaManager->getTemplateMeta($this->action['template']);
	}

	function setTitle($title){
		$this->title = $title;
	}

	function render(){
		if(is_file($this->template['location'])){
			$yocto = $GLOBALS['yocto'];
			include($this->template['location']);
		} else{
			echo("Could not load template file {$this->template['location']}");
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

	function registerAjax($id, $source, $type = 'get', $path = '.'){
		foreach($ajaxes as $id=>$ajax){
			$this->_ajaxes[] = array('id' => $ajax[''], 'source' => $path . '/ajax/' . $source, 'type' => $type);
		}
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