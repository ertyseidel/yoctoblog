<script type="text/javascript" src="./js/ajax-post.js"></script>
<table>
	<tr>
		<td colspan="2"><p>Post #<span id="ajax-id"></span></p></td>
	</tr>
	<tr>
		<td>Title:</td>
		<td><input type="text" name="title" id="ajax-title"/></td>
	</tr>
	<tr>
		<td>Date:</td>
		<td><input type="date" name="date" id="ajax-date" /></td>
	</tr>
	<tr>
		<td>Time:</td>
		<td><input type="time" name="time" id="ajax-time" /></td>
	</tr>
	<tr>
		<td colspan="2">
			<textarea name="content" rows="20" cols="50" id="ajax-content"></textarea>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="button" id="ajax-buttondraft" onclick="ajax_draft()"/><input type="button" id="ajax-buttonpublish" onclick="ajax_publish()"/></td>
	</tr>
</table>