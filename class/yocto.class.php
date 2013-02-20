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
		$this->_messages[] = array(
			'message' => $message,
			'type' => $type
		);
	}

	function registerAjax($id, $source, $type, $path = '.'){
		$this->_ajaxes[] = array('id' => $id, 'source' => $path . '/' . $source, 'type' => $type);
	}

	public function __get($property) {
		switch($property){
			case 'components':
				return new ComponentManager($this);
			case 'messages':
				ob_start();
				foreach($this->_messages as $message){
					echo("<div id='message message-{$message['type']}'>{$message['message']}</div>");
				}
	  			return ob_get_clean();
	  		case 'ajax':
	  			return $this->_ajaxes;
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
				$action = 'default';
				$this->actionDefault($action, $params);
				break;
		}
	}

	function actionDefault(){
		$this->registerAjax('ajax-posts', 'index.php?action=posts', 'get');
		$y = $this;
		include($this->globalTemplate['location']);
	}

	function actionLogin(){
		$y = $this;
		include($this->globalTemplate['location']);
	}

	function actionPosts($action, $params){
		require_once('./class/post.class.php');
		//check if the last param contains a return type
		$returnType = 'html';
		$returnTypes = array('json', 'html', 'rss');

		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$count = isset($_GET['count']) ? $_GET['count'] : 10;

		$postMeta = $this->_metaManager->posts;

		for($i = $start; $i < $count && $i < count($postMeta); $i++){
			$post = $this->_metaManager->getPostById($postMeta[$i]);
			include($this->_metaManager->templates['post']['location']);
		}


	}
}
