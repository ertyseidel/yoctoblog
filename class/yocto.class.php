<?php

class Yocto{

	private $_metaManager;
	private $_ajaxes;
	private $_messages;

	function __construct($path = '.'){
		require_once($path . '/class/metamanager.class.php');
		require_once($path . '/class/componentmanager.class.php');
		$this->_metaManager = new MetaManager();
		if(isset($_SESSION['messages'])){
			$this->_messages = $_SESSION['messages'];
		} else {
			$this->_messages = array();
		}
		$_SESSION['messages'] = array();
	}

	public function __get($property) {
		switch($property){
			case 'components':
				return new ComponentManager($this);
			case 'messages':
				ob_start();
				$this->_messages = array_merge($this->_messages, $_SESSION['messages']);
				$_SESSION['messages']  = array();
				foreach($this->_messages as $message){
					echo("<div class='message message-{$message['type']}'>{$message['message']}</div>");
				}
	  			return ob_get_clean();
	  		case 'ajax':
	  			return $this->_ajaxes;
			case 'title':
				return $this->_metaManager->yocto['title'];
			case 'subtitle':
				return $this->_metaManager->yocto['subtitle'];
			case 'authenticated':
				return isset($_SESSION['user']);
			case 'metaManager':
				return $this->_metaManager;
			case 'user':
				return isset($_SESSION['user']) ? $_SESSION['user'] : false;
			default:
				if (property_exists($this, $property)) {
					return $this->$property;
				}
		}
	}

	function addMessage($message, $type){
		if(!in_array($type, array('error', 'warn', 'info', 'debug', 'trace'))){
			$this->addMessage("'$type' is not a valid type of warning: error, warn, info, debug, or trace", 'warning');
		}
		$_SESSION['messages'][] = array(
			'message' => $message,
			'type' => $type
		);
	}

	function registerAjax($id, $source, $call, $path = '.'){
		$this->_ajaxes[] = array('id' => $id, 'source' => $path . '/' . $source, 'call' => $call);
	}

	function loadContent($location, $path = '.'){
		ob_start();
		$y = $this;
		include($location);
		$this->content = ob_get_clean();
	}

	function createUser($username, $password, $path = '.'){
		$user = new User(array(
			'username' => $username,
			'password' => $password
		));
		$yoctoMeta = $this->_metaManager->yocto;
		$nextId = ++$yoctoMeta['max_user'];
		$user = $user->toArray();
		if(!$user['id']) unset($user['id']);
		$yoctoMeta['users'][$nextId] = $user;
		$this->_metaManager->saveMeta($yoctoMeta, $path . '/content/meta.yocto.json');
	}

	function createPost(){
		$yoctoMeta = $this->_metaManager->yocto;
		$meta = array(
			'id' => ++$yoctoMeta['max_post'],
			'author' => $_SESSION['user']->username,
			'timestamp' => date('c')
		);
	}

	function deleteUser($id, $path = '.'){
		$yoctoMeta = $this->_metaManager->yocto;
		if(count($yoctoMeta['users']) == 1){
			$this->addMessage('You cannot delete the last existing user!', 'error');
			return false;
		}
		$postMeta = $this->_metaManager->posts;
		foreach($postMeta as $post){
			if($post['author'] == $id){
				$this->addMessage('You must delete all of a user\'s posts before you can delete the user!', 'error');
				return false;
			}
		}
		if(!isset($yoctoMeta['users'][$id])){
			$this->addMessage('User with the given id does not exist.', 'error');
			return false;
		}
		unset($yoctoMeta['users'][$id]);
		$this->_metaManager->saveMeta($yoctoMeta, $path . '/content/meta.yocto.json');
		return true;
	}

	function metaToPost($meta){
		return new Post($this->_metaManager, $meta);
	}

	function redirect($path){
		header("Location: $path");
		die();
	}

	function render($action, $params){
		$this->globalTemplate = $this->_metaManager->templates['global'];
		switch($action){
			case 'login':
			case 'logout':
				$this->actionLogin($params);
				break;
			case 'ajax-posts':
				$this->actionPosts($params);
				break;
			case 'ajax-submitpost':
				$this->actionSubmitPost($params);
				break;
			case 'config':
				$this->actionConfig($params);
				break;
			case 'write':
				$this->actionWrite($params);
				break;
			case 'edit':
				$this->actionEdit($action);
				break;
			case 'default':
			default:
				$action = 'default';
				$this->actionDefault($action);
				break;
		}
	}

	function actionLogin($params){
		require_once('./class/user.class.php');
		if(isset($_SESSION['user'])) $this->addMessage('You have been logged out.', 'info');
		unset($_SESSION['user']);
		if(isset($_POST['username']) && isset($_POST['password'])){
			foreach($this->_metaManager->yocto['users'] as $id => $user){
				if($user['username'] == $_POST['username'] && $user['password'] == crypt($_POST['password'], $user['salt'])){
					$user['id'] = $id;
					$_SESSION['user'] = new User($user);
					$this->addMessage("Welcome, {$user['username']}. You have been logged in.", 'info');
				}
			}
		}
		if(isset($_SESSION['user'])){
			$this->redirect('index.php?action=default');
		} else {
			$this->loadContent($this->_metaManager->templates['login']);
			$y = $this;
			include($this->globalTemplate);
		}
	}

	function actionPosts($params){
		//check if the last param contains a return type
		$returnType = 'html';
		$returnTypes = array('json', 'html', 'rss');

		$start = isset($params['start']) ? $params['start'] : 0;
		$count = isset($params['count']) ? $params['count'] : 10;

		$postMeta = $this->_metaManager->posts;

		for($i = $start; $i < $count && $i < count($postMeta); $i++){
			$post = $this->metaToPost($postMeta[$i]);
			include($this->_metaManager->templates['post']);
		}
	}

	function actionSubmitPost($params){
		$status = array(
			'success' => false,
			'messages' => array(),
			'post' => false
		);
		if(count($_POST)){

		}
		echo(json_encode($status));
	}

	function actionConfig($params){
		if(count($_POST)){
			$yoctoMeta = $this->_metaManager->yocto;
			if(isset($_POST['title'])) $yoctoMeta['title'] = $_POST['title'];
			if(isset($_POST['subtitle'])) $yoctoMeta['subtitle'] = $_POST['subtitle'];
			if(isset($_POST['username'])){
				foreach($_POST['username'] as $id => $username){
					if(isset($_POST['password'][$id]) && strpos($_POST['password'][$id], '---') === false){
						if(isset($yoctoMeta['users'][$id])){
							$yoctoMeta['users'][$id]['username'] = $username;
							$yoctoMeta['users'][$id]['password'] = crypt($_POST['password'][$id], $yoctoMeta['users'][$id]['salt']);
						}
					}
				}
			}
			$this->_metaManager->saveMeta($yoctoMeta, './content/meta.yocto.json');
			if(isset($_POST['username_new']) && isset($_POST['password_new'])){
				if($_POST['username_new'] && $_POST['password_new']){
					$this->createUser($_POST['username_new'], $_POST['password_new']);
				}
			}
			$this->redirect('index.php?action=config');
			if(isset($_POST['delete'])){
				foreach($_POST['delete'] as $id => $value){
					if($value){
						$this->deleteUser($id);
					}
				}
			}
		}
		$this->loadContent($this->_metaManager->templates['config']);
		$y = $this;
		include($this->globalTemplate);
	}

	function actionWrite($params){
		$this->registerAjax('ajax-submitpost', 'index.php?action=ajax-submitpost', 'delayed');
		$this->loadContent($this->_metaManager->templates['edit']);
		if(isset($params['id'])){
			$this->post = $this->metaToPost($this->_metaManager->posts[$params['id']]);
		} else{
			$this->post = $this->createPost();
		}
		if(count($_POST)){

		}
		$y = $this;
		$this->loadContent($this->_metaManager->templates['edit']);
		include($this->globalTemplate);
	}

	function actionEdit($params){
		$y = $this;
		$this->loadContent($this->_metaManager->templates['edit']);
		include($this->globalTemplate);
	}

	function actionDefault($params){
		$this->registerAjax('ajax-posts', 'index.php?action=ajax-posts', 'onload');
		$this->loadContent($this->_metaManager->templates['homepage']);
		$y = $this;
		include($this->globalTemplate);
	}


}
