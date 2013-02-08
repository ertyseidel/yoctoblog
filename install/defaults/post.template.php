<h2 class="post-title"><?php echo $post->title ?></h2>
<p class="post-detail">By <?php echo $post->author ?> on <?php echo $post->time('m/d/Y')?></p>
<hr />
<div class="post-content"><?php echo $post->content ?></div>