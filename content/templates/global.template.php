<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $y->title ?></title>
	<?php echo $y->components->ajaxloader ?>
	<link rel="stylesheet" type="text/css" href="./content/resources/css/style.css" media="all">
</head>
<body>
	<div id="sidebar">
		<?php echo $y->components->sidebar ?>
	</div>
	<div id="all">
		<div id="header">
			<h1><a href="./?action=default"><?php echo $y->title ?></a></h1>
			<p><?php echo $y->subtitle ?></p>
		</div>
		<div id="messages">
		<?php echo $y->messages ?>
		</div>
		<div id="content">
		<?php echo $y->content ?>
		</div>
		<div id="footer"></div>
	</div>
</body>
</html>