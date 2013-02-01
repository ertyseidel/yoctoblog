<?php
	if(!isset($yocto)){
		header('HTTP/1.0 403 Forbidden'); 
		die('The server understood the request, but is refusing to fulfill it. You are not allowed to access this page.');
	}
	
	//Get the relative directory, or, we're in the root directory
?>
<!DOCTYPE html>
<html lang="<?php if(isset($yocto['settings']['lang'])) echo($yocto['settings']['lang']); else echo('en-US');?>">
<head>
	<meta charset="<?php if(isset($yocto['settings']['charset'])) echo($yocto['settings']['charset']); else echo('UTF-8');?>">
	<title>Yoctoblog<?php if(isset($yocto['settings']['title'])) echo(' - ' . $yocto['settings']['title']); else echo('Title'); if(defined('POST_PAGE')) echo(' - ' . $title);?></title>
	<link rel="stylesheet" type="text/css" href="<?php if(isset($yocto['settings']['style'])) echo($yocto['settings']['abs_path']);; if(isset($yocto['settings']['style'])) echo($yocto['settings']['style']); else echo('default.css');?>" media="all" />
</head>
<body>
<div id="all">
	<div id="header">
		<h1><a href="<?php echo($yocto['settings']['abs_path']); ?>"><?php if(isset($yocto['settings']['title'])) echo($yocto['settings']['title']);?></a></h1>
		<h2><?php if(isset($yocto['settings']['subtitle'])) echo($yocto['settings']['subtitle']);?></h2>
	</div>
	<div id="sidebar">
		<ul>
			<?php
				echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '">Blog Home</a></p></li>');
				if(!isset($_SESSION['user'])) {
					echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?login">Login</a></p></li>');
				} else {
					echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?logout">Logout</a></p></li>');
					echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?settings">Settings</a></p></li>');
					if($_SESSION['user']['level'] >= 3) { //User is admin
						echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?users">Manage Users</a></p></li>');
					}
					if($_SESSION['user']['level'] >= 2) { //User is poster or admin
						echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?createpost">Create Post</a></p></li>');
						echo('<li><p><a href="' . $yocto['settings']['abs_path'] . '?editpost">Edit Posts</a></p></li>');
					}
				}
			?>
		</ul>
	</div>
	<div id="content">
		<div id="messages">
		<?php
			//Here is where the messages array gets printed
			foreach($messages as $message) {
				echo('<p>' . $message . '</p>');
			}
		?>
		</div>
		<div id="page">
		<?php
			echo($body);
		?>
		</div>
	</div>
	<div id="footer"></div>
</div>
</body>
</html>