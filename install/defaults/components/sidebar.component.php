<?php if($y->user) { ?>
<p>Hello, <?php echo $y->user->username ?></p>
<?php } ?>
<ul>
	<?php if($y->user){ ?>
		<li><a href="?action=write">New Post</a></li>
		<li><a href="?action=edit">Edit Posts</a></li>
		<li><a href="?action=config">Configure Blog</a></li>
		<li><a href="?action=logout">Log Out</a></li>
	<?php } else { ?>
		<li><a href="?action=login">Log In</a></li>
	<?php } ?>
</ul>