<?php
	if(!defined('INDEX')) {
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	
	if(isset($_POST['edit_users'])){
		foreach($_POST['edit_users'] as $id => $user){
			if(isset($yocto['users'][$id])){
				if(!empty($user['pass'])) $yocto['users'][$id]['pass'] = $user['pass'];
				$yocto['users'][$id]['name'] = $user['name'];
				$yocto['users'][$id]['level'] = $user['level'];
			} else if(!empty($user['name'])){
				$yocto['users'][$id] = $user;
			}
		}
		save_users();
	}
	
	if(!isset($_SESSION['user']) || $_SESSION['user']['level'] < 3){
		$body .= '<p>You do not have the correct permissions to access this page.</p>';
	} else {
		$body .= '<p>Current Users</p><form action="./?users" method="post"><table>';
		$display_users = $yocto['users'];
		$display_users[] = array('name' => '', 'level' => '1');
		foreach($display_users as $id => $user){
			$body .= "<tr><td>#{$id}</td><td>Username: <input type='text' name='edit_users[{$id}][name]' value='{$user['name']}' /></td><td></td><td>Admin Level: <select name='edit_users[{$id}][level]'>";
			$level = array(1=>false,2=>false,3=>false);
			$level[$user['level']] = 'selected="selected"';
			$body .= '<option value="1" ' . $level[1] . '>User</option>';
			$body .= '<option value="2" ' . $level[2] . '>Author</option>';
			$body .= '<option value="3" ' . $level[3] . '>Administrator</option>';
			$body .= "</td><td>Password: <input type='password' name='edit_users[{$id}][pass]' /></td></tr>";
		}
		$body .= '</table><input type="submit" value="Save Changes"/></form>';
	}