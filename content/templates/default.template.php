<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo($GLOBALS['yocto']->title); ?></title>
</head>
<body>
	<div id="all">
		<div id="header">
			<h1><?php echo($GLOBALS['yocto']->title); ?></h1>
		</div>
		<div id="messages">
		<?php
			foreach($GLOBALS['yocto']->messages as $msg){
				echo("<p>$msg</p>");
			}
		?>
		</div>
		<div id="content">
		<?php
			echo($GLOBALS['yocto']->content);
		?>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>