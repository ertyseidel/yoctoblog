window.addEventListener('load', function(){
	ajax.get('index.php?action=ajax-', function(data){

	});
});

function ajax_draft(){

}

function ajax_publish(){

}

function callback(returnData){
	if(returnData['status'] == 'success'){

	} else {
		postMessage('Saving did not succeed. Try again and check your connection to the server.');
	}
}

function fillData(postData){

}