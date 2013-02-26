<h2 class="post-title"><a href="<?php echo $post->link ?>"><?php echo $post->title ?></a></h2>
<p class="post-detail">By <?php echo $post->author ?> on <?php echo $post->posttime('m/d/Y')?></p>
<hr />
<div class="post-content"><?php echo $post->content ?></div>