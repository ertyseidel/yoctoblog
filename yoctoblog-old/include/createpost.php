<?php
	if(!defined('INDEX')) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	
	if(!isset($_SESSION['user']) || $_SESSION['user']['level'] < 2){
		$body = ('<p>You do not have the correct permissions to access this page.</p>');
	} else {
		$valid_post = false;
		if(isset($_POST['create_post_submit_button'])) {
			$valid_post = true;
			if(!isset($_POST['create_post_title_field']) || empty($_POST['create_post_title_field'])) {
				$valid_post = false;
				$messages[] = 'Error: The title is blank or missing.';
			}
			if(!isset($_POST['create_post_text_field']) || empty($_POST['create_post_text_field'])) {	
				$valid_post = false;
				$messages[] = 'Error: The text is blank or missing.';
			}
		}
		
		if($valid_post) {
			//Security is handled in the make_post function
			if(make_post(time(), $_POST['create_post_title_field'], $_POST['create_post_text_field'], $_SESSION['user']['id'])) {
				$body .= '<p>The post was sucessfully published!</p>';
				$body .= '<p><a href="' . $yocto['settings']['abs_path'] . '">Go to the blog index to see the post</a></p>';
				$body .= '<p><a href="./?createpost">Write a new post</a></p>';
			} else {
				$valid_post = false;
			}
		}
		
		if(!$valid_post){
			$title = isset($_POST['create_post_title_field']) ? $_POST['create_post_title_field'] : ''; //TODO: Security
			$text = isset($_POST['create_post_text_field']) ? $_POST['create_post_text_field'] : '<p></p>';
			$body .= <<<DOC
				<form action="./?createpost" method="post" id="create_post_form">
					<p id="create_post_title">Title: <input type="text" name="create_post_title_field" id="create_post_title_field" value="{$title}"></input></p>
					<p>Unfortunately, there is no WYSIWYG for post editing yet, so you will have to hand-write HTML...</p>
					<textarea name="create_post_text_field" id="create_post_text_field">{$text}</textarea>
					<p><input type="submit" value="Create Post" name="create_post_submit_button" id="create_post_submit_button"></input></p>
				</form>
DOC;
		}
	}