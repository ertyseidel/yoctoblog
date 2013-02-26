<?php if ($y->authenticated){ ?>
	<p class="post"><b><?php echo $post->title ?></b> by <?php echo $post->author ?> on <?php echo $post->posttime('m/d/Y')?> <a href="<?php echo $post->link ?>">View Post</a> <a href="<?php echo $post->editlink ?>">Edit Post</a></p>
<?php } else { ?>
	<p class="post"><b><a href="<?php echo $post->link ?>"><?php echo $post->title ?></a></b> by <?php echo $post->author ?> on <?php echo $post->posttime('m/d/Y')?></p>
<?php } ?>