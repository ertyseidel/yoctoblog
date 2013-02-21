<form action="" method="post">
	<table>
		<tr>
			<td>Blog Title:</td>
			<td><input type="text" name="title" value="<?php echo($y->metaManager->yocto['title']);?>"></td>
		</tr>
		<tr>
			<td>Blog Subtitle:</td>
			<td><input type="text" name="subtitle" value="<?php echo($y->metaManager->yocto['subtitle']);?>"></td>
		</tr>
		<tr>
			<td>Users:</td>
			<td>
				<table>
					<?php foreach($y->metaManager->yocto['users'] as $id=>$user){ ?>
						<tr>
							<td colspan="2"><b>User #<?php echo($id); ?></b></td>
						</tr>
						<tr>
							<td>Username:</td>
							<td><input type="text" name="username[<?php echo($id); ?>]" value="<?php echo($user['username'])?>" /></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="password[<?php echo($id); ?>]" value="----" /></td>
						</tr>
						<tr>
							<td>Delete User:</td>
							<td><input type="checkbox" name="delete[<?php echo($id); ?>]" value="true" /></td>
						</tr>
					<?php } ?>
					<tr>
						<td colspan="2"><b>Create New User:</b></td>
						<tr>
							<td>Username:</td>
							<td><input type="text" name="username_new" value="" /></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><input type="password" name="password_new" value="" /></td>
						</tr>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Submit"></td>
		</tr>
	</table>
</form>