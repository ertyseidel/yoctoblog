<h2>Login to <?php echo $y->title; ?></h2>
<form action="./?action=login" method="post">
	<table>
		<tr>
			<td>Username:</td>
			<td><input type="text" name="username" autofocus /></td>
		</tr>
		<tr>
			<td>Password</td>
			<td><input type="password" name="password" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Login" /></td>
		</tr>
	</table>
</form>