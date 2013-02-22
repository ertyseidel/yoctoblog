<form action="" method="post">
	<table>
		<tr>
			<td>Title:</td>
			<td><input type="text" name="title" value="<?php echo $y->post->title ?>"/></td>
		</tr>
		<tr>
			<td>Date:</td>
			<td><input type="date" name="date" value="<?php echo $y->post->date ?>" /></td>
		</tr>
		<tr>
			<td>Time:</td>
			<td><input type="time" name="time" value="<?php echo $y->post->time ?>" /></td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea name="content" rows="20" cols="50"><?php echo $y->post->content ?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Submit"/></td>
		</tr>
	</table>
</form>