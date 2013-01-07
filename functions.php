<?php
	//Standard header for security
	if(!(defined('INDEX') || defined('POST_PAGE'))) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	
	require_once('./install/install_files.php'); //TODO: Only call this when necessary (for performance)
	
	/*
	 * void start_yocto
	 * starts the session and validates the user
	 * @todo validate the user
	 */
	function start_yocto() {
		global $yocto;
		global $messages;
		session_start();
		if(is_file('./files/users.php')) {
			include('./files/users.php');
		} else {
			$messages[] = '<strong>Error:</strong> unable to load users file! (start)';
		}
	}
	
	/*
	 * void refresh_yocto
	 * refreshes the yocto variable from the users file
	 * this is different from start_yocto in that it does not start the sesion
	 */
	function refresh_yocto() {
		global $yocto;
		global $messages;
		if(is_file('./files/settings.php')) {
			$messages[] = 'Refreshed settings!';
			include('./files/settings.php');
		} else {
			$messages[] = '<strong>Error:</strong> unable to load settings file! (refresh)';
		}
		if(is_file('./files/users.php')) {
			$messages[] = 'Refreshed users!';
			include('./files/users.php');
		} else {
			$messages[] = '<strong>Error:</strong> unable to load users file! (refresh)';
		}
	}

	/*
	 * boolean make_dir
	 * creates a directory and returns messages if there is an error
	 * @param $dir string the directory to be created
	 * @return true if the directory has been created, false otherwise
	 */
	function make_dir($dir) {
		global $messages;
		$messages[] = 'Creating folder "' . $dir . '"...';
		if(is_dir($dir)) {
			$messages[] = '<strong>Warning:</strong> Folder "' . $dir . '" already exists!';
		} else {
			if(mkdir($dir)) {	
				$messages[] = 'Done!';
			}else {
				$messages[] = '<strong>Error:</strong> Unable to create folder "' . $dir . '" in current directory!';
				return false;
			}
		}
		return true;
	}
	
	/*
	 * boolean make_file
	 * creates a file with optional predefined contents and returns messages if there is an error
	 * @param $file string the file to be created
	 * @param $contents optional string the contents of the file
	 * @return true if the file has been created, false otherwise
	 */
	function make_file($file, &$contents=0) {
		global $messages;
		$messages[] = 'Creating file "' . $file . '"...';
		if(file_exists($file)) {
			$messages[] = '<strong>Warning:</strong> File "' . $file . '" already exists!';
		}
		$file = fopen($file, 'w');
		if(!$file) {
			$messages[] = '<strong>Error:</strong> Unable to create file "' . $file . '"!';
			return false;
		}
		if($contents) {
			fwrite($file, $contents);
		}
		$messages[] = 'Done!';
		fclose($file);
		return true;
	}
	
	/*
	 * boolean make_user
	 * creates a user in the users.php file
	 * @param $name string the username
	 * @param $pass string the password
	 * @param $level string the administrative level (1=>user, 2=>poster, 3=>admin)
	 * @return true if the user has been created, false otherwise
	 */
	function make_user($name, $pass, $level) {
		global $default_files;
		global $yocto;
		$id = get_next_user_id();
		$yocto['users'][] = array('name'=>$name,'pass'=>$pass,'level'=>$level);
		save_users();
		return true;
	}
	
	/*
	* function save_users()
	* Saves the current $yocto['users'] array to file
	*/
	function save_users(){
		global $yocto;
		global $default_files;
		$file = fopen('./files/users.php', 'w+');
		if(!$file) {
			$messages[] = '<strong>Error:</strong> Unable to open users file!';
			return false;
		}
		fwrite($file, $default_files['secure_header']);
		fwrite($file, $default_files['users_header']);
		foreach($yocto['users'] as $id => $user){
			$id = mkint($id);
			$name = safe($user['name']);
			$pass = sha1($user['pass']);
			$level = mkint($user['level']);
			fwrite($file, "\$yocto['users'][{$id}] = array('name'=>'{$name}','pass'=>'{$pass}','level'=>'{$level}');\r\n\t");
		}
		fclose($file);
	}
	
	/*
	 * int get_next_user_id
	 * searches through the users array for the next available user id
	 * @return the next available user id, or 1 if there are no users
	*/
	function get_next_user_id() {
		global $yocto;
		$next = 0;
		if(!isset($yocto['users'])) return 0;
		return count($yocto['users']);
		return $next;
	}
	
	/*
	 * boolean make_post
	 * creates a post
	 * @param $time int the time the post is to have been created at
	 * @param $title string the title of the post
	 * @param $text string the text of the post
	 * @return true if the directory has been created, false otherwise
	 */
	function make_post($time, $title, $text, $user) {
		global $yocto;
		global $messages;
		$time = mkint($time);
		$slug = generate_post_slug($title);
		$filepath = $title ? $title : date('Y-m-d', $time);
		$filepath = './files/posts/' . $slug;
		if(is_dir($filepath)) {
			$messages[] = 'A post at "' . $filepath . '" already exists! Unfortunately, you cannot have two posts with the same title. Please rename this post.';
			return false;
		}
		if(!make_dir($filepath)) return false;
		$text = safe($text);
		$title = safe($title);
		$post = <<<DOC
	<?php
	\$title = '{$title}';
	\$post = '{$text}';
	\$user = {$user};
	\$time = {$time};
	\$slug = '{$slug}';
	if(defined('INDEX')) {
		\$posts[] = array('title' => \$title, 'post' => \$post, 'time' => \$time, 'user' => \$user, 'slug' => \$slug);
	} else {
		chdir('../../../'); //Kick us up to the root directory
		include('./include/postpage.php');
	}
DOC;
		if(!make_file($filepath . '/index.php', $post)) return false;
		return true;
	}
	
	/*
	 * void render_posts
	 * loops through all folders in the files/posts directory and includes them if they are a post
	*/
	function render_posts() {
		global $messages;
		$posts = array();
		foreach(scandir('./files/posts') as $post) {
			if($post[0] != '.') {
				$path = './files/posts/' . $post . '/index.php';
				if(!is_file($path)) {
					$messages[] = 'Warning: Unable to include post ' . $post . '!';
				} else {
					//Dumps the post into $posts
					include($path);
				}
			}
		}
		//Sort the posts by timestamp
		$sorted_posts = array();
		foreach($posts as $post){
			$sorted_posts[$post['time']] = $post;
		}
		krsort($sorted_posts);
		foreach($sorted_posts as $post){
			render_post($post['title'], $post['post'], $post['time'], $post['user'], $post['slug']);
		}
	}
	
	/*
	 * void render_post
	 * takes a post and adds it to the $body : this is called from inside a post php file
	 * @param $title string the title of the post
	 * @param $post string the text of the post
	 * @param $time int the timestamp the post was made
	 * @param $user int the id of the user who made the post
	 */
	function render_post($title, $post, $time, $user, $slug) {
		global $yocto;
		global $body;
		$title = isset($title) ? ($title ? unsafe($title) : 'Untitled') : 'Untitled';
		$post = isset($post) ? ($post ? unsafe($post) : '[no post]') : '[no post]';
		$user = isset($user) ? ($user !== '' ? get_user_name($user) : '[unknown]') : '[unknown]';
		$time = isset($time) ? ($time ? date('Y-m-d \a\t h:m:s', $time) : '[no date]') : '[no date]';
		$slug = isset($slug) ? ($slug ? unsafe($slug) :  generate_post_slug($title)) : generate_post_slug($title);
		$byline = isset($yocto['byline']) ? $yocto['byline'] : 'By: ';
		$timeline = isset($yocto['timeline']) ? $yocto['timeline'] : 'On: ';
		$body .= ("<div class='post'><div class='post_head'><h1 class='post_title'><a href='{$yocto['settings']['abs_path']}files/posts/{$slug}'>{$title}</a></h1><p class='post_user'>{$byline}{$user}</p><p class='post_time'>{$timeline}{$time}</p></div><div class='post_body'>{$post}</div></div>");
	}
	
	/*
	* string generate_post_slug
	* parses the post title and generates a url-safe post slug
	* 
	*
	*/
	function generate_post_slug($title) {
		$title = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
		$title = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $title);
		$title = strtolower(trim($title, '-'));
		$title = preg_replace("/[\/_|+ -]+/", '-', $title);

		if(empty($title)) {
			return 'untitled';
		}

		return $title;
	}
	
	/*
	* void set_settings
	* sets all settings passed in as a dictionary
	* @param $settings_arr $key=>$val mapping of settings to be set
	*/
	function set_settings($settings_arr){
		global $default_files;
		global $yocto;
		$file = fopen('./files/settings.php', 'w+');
		fwrite($file, $default_files['secure_header']);
		$all_settings = isset($yocto['settings']) ? array_merge($yocto['settings'], $settings_arr) : $settings_arr;
		foreach($all_settings as $key => $value){
			$key = safe($key);
			$value = safe($value);
			fwrite($file, "\t\$yocto['settings']['{$key}'] = '{$value}';\r\n");
		}
		fclose($file);
		refresh_yocto();
	}
	
	/*
	 * boolean user_login
	 * checks the user login credentials
	 * sets the session variables if the user is valid 
	 * returns false if the user is not valid
	 * @param $username string the username
	 * @param $password string the password (as a sha1 hash)
	 * @return true if the user is authorized
	 * @return false if the user is unauthorized
	 */
	function user_login($username, $password) {
		global $yocto;
		foreach($yocto['users'] as $id => $user) {
			if(strtolower($user['name']) == strtolower($username) && $user['pass'] == $password) {
				$_SESSION['user']['id'] = $id;
				$_SESSION['user']['name'] = $user['name'];
				$_SESSION['user']['level'] = $user['level'];
				return true;
			}
		}
		return false;
	}
	
	/*
	 * void user_logout
	 * clears the session variables
	 */
	function user_logout() {
		if(isset($_SESSION['user'])) unset($_SESSION['user']);
	}

	
	/*
	 * string safe
	 * runs the necessary functions on a string to allow it to be put into a file
	 * @param $str string the string to be made safe
	 * @return the string, safe
	*/
	function safe($str) {
		return addslashes($str);
	}
	
	/*
	 * string unsafe
	 * does the opposite of safe(), for echoing
	 * @param $str string the string to be made not-safe
	 * @return the string, unsafe
	*/
	function unsafe($str) {
		return stripslashes($str);
	}
	
	/*
	 * string get_user_name
	 * returns the user name that is associated with the provided id
	 * @param $id int the id of the user you want to find
	 * @return string the name of the requested user
	 */
	function get_user_name($id) {
		global $yocto;
		if(isset($yocto['users'][$id])) return $yocto['users'][$id]['name'];
		return false;
	}
	
	/*
	 * int mkint
	 * returns the input as an int, no matter the input
	 * @param $num mixed the item to be made into an int
	 * @return $num as an int
	*/
	function mkint($num) {
		return (int)$num;
	}