$(document).ready(function(){
	initSearch();
});

function initSearch () {
	$("#newz_search_form").submit(search);
}

function search() {
	$("#results").prepend('<li id="loader"><img src="images/ajax-loader.gif"/></li>');
	$.ajax({
		url: '?c=newz&a=index&keyword='+ $("#keyword").val(),
		success: handleSearch,
		dataType: 'json'
		
	});
	
	return false;
}


function handleSearch(data) {
	var li = '';
	for(var x in data) {
		li += '<li><div class="name"> ' + data[x].title + '</div>';
		li += '<div class="button" id="report_' + data[x].report_id + '">';
		li += 'download</div></li>';
	}
	$("#results").append(li);
	$("#loader").remove();
	$("#results li div.button").click(download);
}

function download() {
	var id = this.id.replace('report_','');
	
	$.ajax({
		url: '?c=newz&a=queue&id=' + id,
		type: 'post',
		dataType: 'json',
		success: handleDownload
	});
}

function handleDownload(data) {
//	$("#results").html(data);
	if(data.code) {
		// error
		alert("An Error has occurred " + data.reason);
	} else {
		alert("Report queued");
	}
}