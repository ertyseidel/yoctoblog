<?php

class Yocto{

	private $_metaManager;
	private $_messages;
	private $_ajaxes;

	function __construct($path = '.'){
		require_once($path . '/class/metamanager.class.php');
		require_once($path . '/class/componentmanager.class.php');
		$this->_metaManager = new MetaManager();
		$this->_messages = array();
	}

	function addMessage($message, $type){
		if(!in_array($type, array('error', 'warn', 'info', 'debug', 'trace'))){
			$this->addMessage("'$type' is not a valid type of warning: error, warn, info, debug, or trace", 'warning');
		}
		$this->__messages[] = array(
			'message' => $message,
			'type' => $type
		);
	}

	function registerAjax($id, $source, $type = 'get', $path = '.'){
		$this->_ajaxes[] = array('id' => $id, 'source' => $path . '/ajax/' . $source, 'type' => $type);
	}

	public function __get($property) {
		switch($property){
			case 'components':
				return new ComponentManager($this);
			case 'ajax':
				return $this->_ajaxes;
			case 'messages':
				ob_start();
				foreach($this->_messages as $message){
					echo("<div id='message message-{$message['type']}'>{$message['message']}</div>");
				}
	  			return ob_get_clean();
			case 'title':
				return $this->_metaManager->yocto['title'];
			case 'subtitle':
				return $this->_metaManager->yocto['subtitle'];
			default:
				if (property_exists($this, $property)) {
					return $this->$property;
				}
		}
	}

	function render($action, $params){
		$this->globalTemplate = $this->_metaManager->templates['global'];
		switch($action){
			case 'login':
				$this->actionLogin($action, $params);
				break;
			case 'posts':
				$this->actionPosts($action, $params);
				break;
			case 'config':
				$this->actionConfig($action, $params);
				break;
			case 'edit':
				$this->actionEdit($action, $params);
				break;
			case 'default':
			default:
				$this->actionDefault($action, $params);
				break;
		}
		if(count($this->_messages)) print_r($this->_messages);
	}

	function actionDefault(){
		$y = $this;
		include($this->globalTemplate['location']);
	}

	function actionPosts($action, $params){
		//check if the last param contains a return type
		$returnType = 'html';
		$returnTypes = array('json', 'html', 'rss');
		if(in_array($params[count($params) - 1], $returnTypes)){
			$returnType = $params[count($params) - 1];
			array_pop($params);
		}

		$start = 0;
		$count = 0;

		switch(count($params)){
			case 0: //return the first ten posts
				$start = 0;
				$count = 10;
				break;
			case 1: //return the post with the given id
				$start = $params[0];
				$count = 1;
				break;
			case 2: //return $params[1] posts, starting on $params[0]
				$start = $params[0];
				$count = $params[1];
				break;
			default: //what
		}

		$postMeta = $_metaManager->posts;

		for($i = $start; $i < $count && $i < count($postMeta); $i++){
			$_metaManager->getPostById($postMeta[$i]);
		}
	}
}
