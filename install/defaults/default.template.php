<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Title</title>
</head>
<body>
	<div id="all">
		<div id="header"></div>
		<div id="messages">
		<?php
			foreach($GLOBALS['yocto']->messages as $msg){
				echo("<p>$msg</p>");
			}
		?>
		</div>
		<div id="content"></div>
		<div id="footer"></div>
	</div>
</body>
</html>