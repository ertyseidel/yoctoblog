window.addEventListener('load', function(){
	mini.ajax.get('index.php?action=ajax-submitpost&post=new', callback);
});

function ajax_draft(){
	mini.ajax.post('index.php?action=ajax-submitpost&post=draft',
		callback,
		'data=' + stringifyData());
}

function ajax_publish(){
	mini.ajax.post('index.php?action=ajax-submitpost&post=publish',
		callback,
		'data=' + stringifyData());
}

function stringifyData(){
	postData = {
		'id' : document.getElementById('ajax-id').innerHTML,
		'title' : document.getElementById('ajax-title').value,
		'date' : document.getElementById('ajax-date').value,
		'time' : document.getElementById('ajax-time').value,
		'content' : document.getElementById('ajax-content').value
	}
	return encodeURIComponent(JSON.stringify(postData));
}

function callback(returnData){
	clearMessages();
	var jsonReturnData = eval('(' + returnData + ')');
	if(jsonReturnData['success'] == 'true'){
		fillData(jsonReturnData['post']);
	} else {
		postMessage('Saving did not succeed. Try again and check your connection to the server.');
	}
	for(var i = 0; i < jsonReturnData['messages'].length; i++){
		postMessage(jsonReturnData['messages'][i]);
	}
}

function clearMessages(){
	//todo
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