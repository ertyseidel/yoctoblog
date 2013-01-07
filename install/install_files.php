<?php
	
	
	$default_files = array();
	
	$default_files['secure_header'] = <<<DOC
<?php
	/*DO NOT EDIT*/
	if(!(defined('INDEX') || defined('POST_PAGE'))){
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	/*OKAY YOU CAN EDIT NOW*/
DOC;

	$default_files['first_post'] = <<<DOC
<p>At creation, you have a user called "admin", whose password is "root". You need to go change that RIGHT NOW. Go.</p>
<p>The next item of business is to delete the "install.php" file so that you do not have a big security hole! Not that this is particularly secure at this point in time anyway.</p>
<p>To re-do the install, all you have to do is delete the "files" folder in the root of this project.</p>
<p>Credits: <a href="http://ertyseidel.com/">Erty Seidel</a> came up with this.</p>
<p>Joke: How many bloggers does it take to install Yoctoblog? Seven: One to hold the computer, and the other four to turn the ladder round and round. Wait what?</p>
DOC;
	
	$default_files['users_header'] = "if(isset(\$yocto['users'])) unset(\$yocto['users']);\r\n\r\n\$yocto['users'] = array();";

	$default_files['settings'] = array(
		'abs_path' => parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), //The absolute path to the index of the blog
		'title' => 'Yoctoblog', //The title of the blog
		'subtitle' => 'Macroblogging Microplatform', //The subtitle of the blog
		'style' => 'css/default.css', //The stylesheet for the blog
		'lang' => 'en-US', //The language setting of the blog
		'charset' => 'UTF-8', //The character set for the blog
	);
