<?php
	if(!defined('INDEX')) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	if(isset($_SESSION['user'])) {
		//The user is already logged in
		$messages[] = 'You are already logged in! <a href="./">Return to the homepage?</a>';
	} else {
		$display_form = true; //Display the login form again?
		if(isset($_POST['login_submit'])) {
			$username = isset($_POST['login_username']) ? $_POST['login_username'] : 0;
			if(!$username) $messages[] = 'You need to enter a username.';
			$password = isset($_POST['login_password']) ? sha1($_POST['login_password']) : 0;
			if(!$password) $messages[] = 'You need to enter a password.';
			if(user_login($username, $password)) {
				$display_form = false;
				$messages[] = 'Welcome, ' . $_SESSION['user']['name'] . '! <a href="./">Return to the homepage?</a>';
			} else {
				$messages[] = 'Sorry, that username and/or password did not match.';
			}
		}
		if($display_form) {
$body .= <<<DOC
			<form method="post" action="./?login">
			<table>
				<tr>
					<td><p>Username:</p></td><td><p><input type="text" name="login_username" /></p></td>
				</tr>
				<tr>
					<td><p>Password:</p></td><td><p><input type="password" name="login_password" /></p></td>
				</tr>
				<tr>
					<td colspan="2"><p><input type="submit" name="login_submit" value="Login"/></p></td>
				</tr>
			</table>
			<p><a href="./">Return to the homepage?</a></p>
DOC;
		}
	}