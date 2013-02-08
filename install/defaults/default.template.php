<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $yocto->title ?></title>
	<?php echo $yocto->ajax ?>
	<link rel="stylesheet" type="text/css" href="./content/resources/style.css" media="all">
</head>
<body>
	<div id="all">
		<div id="header">
			<h1><?php echo $yocto->title ?></h1>
		</div>
		<div id="sidebar">
			<?php echo $yocto->components->sidebar ?>
		</div>
		<div id="messages">
		<?php
			foreach($yocto->messages as $msg){
				echo "<p>$msg</p>";
			}
		?>
		</div>
		<div id="content">
		<?php
			echo $yocto->content
		?>
		</div>
		<div id="ajax-posts"></div>
		<div id="footer"></div>
	</div>
</body>
</html>