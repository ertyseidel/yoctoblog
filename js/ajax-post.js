window.addEventListener('load', function(){
	ajax.get('index.php?action=ajax-submitpost', callback);
});

function ajax_draft(){

}

function ajax_publish(){

}

function callback(returnData){
	var jsonReturnData = eval('(' + returnData + ')');
	if(jsonReturnData['success'] == 'true'){
		fillData(jsonReturnData['post']);
	} else {
		postMessage('Saving did not succeed. Try again and check your connection to the server.');
	}
}

function postMessage(message){
	console.log(message); //TODO
}

function fillData(postData){
	document.getElementById('ajax-id').innerHTML = postData['id'];
	document.getElementById('ajax-title').value = typeof(postData['title']) == 'undefined' ? '' : postData['title'];
	document.getElementById('ajax-date').value = postData['date'];
	document.getElementById('ajax-time').value = postData['time'];
	document.getElementById('ajax-content').value = typeof(postData['content']) == 'undefined' ? '' : postData['content'];
	if(postData['status'] == 'new'){
		document.getElementById('ajax-buttondraft').value = 'Save Draft';
		document.getElementById('ajax-buttonpublish').value = 'Publish Post';
	} else if(postData['status'] == 'draft'){
		document.getElementById('ajax-buttondraft').value = 'Save Draft';
		document.getElementById('ajax-buttonpublish').value = 'Publish Post';
	} else if(postData['status'] == 'posted'){
		document.getElementById('ajax-buttondraft').value = 'Unpublish (save as draft)';
		document.getElementById('ajax-buttonpublish').value = 'Update Published Post';
	}
}