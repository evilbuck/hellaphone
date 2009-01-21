$(document).ready(function(){
	initSearch();
	initTabs();
});

function initSearch () {
	$("#newz_search_pane").submit(search);
}

function search() {
	$("#results").prepend('<li id="loader"><img src="images/ajax-loader.gif"/></li>');
	var data = $("#newz_search_pane").serialize();
	$.ajax({
		url: '?c=newz&a=index',//'&q='+ $("#q").val(),
		success: handleSearch,
		dataType: 'json',
		data: data,
		method: 'POST'
	});
	
	return false;
}


function handleSearch(data) {
	var li = '';
	for(var x in data) {
		li += '<li><div class="name"> ' + data[x].title + '</div>';
		li += '<div class="button" id="report_' + data[x].report_id + '">';
		li += 'download</div>';
		li += data[x].description;
		li += '</li>';
	}
	$("#results").html(li);
	$("#loader").remove();
	$("#results li div.button").click(download);
	$('#results li').click(showDescription);
}

function showDescription() {
	$(this).children('ul').slideToggle();
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
	if(data.code) {
		// error
		alert("An Error has occurred " + data.reason);
	} else {
		alert("Report queued");
	}
}

function initTabs() {
	$('#nav a').click(showPane);
}
function showPane() {
	$('#contents .pane').hide();
	var target = $(this).attr('href');
	$(target).show();
	if(target == '#monitor')
		monitor.start();
	else
		monitor.stop();
	return false;
}

var monitor = new Monitor();

function Monitor(){
}
Monitor.prototype.start = function(){ 
	this.fetch();
	this.timer = setInterval(this.fetch, 5000);
}
Monitor.prototype.stop = function() {
	clearInterval(this.timer);
}
Monitor.prototype.fetch = function() {
	// pull new info
	$.ajax({
		url: '?a=status',
		method: 'get',
		success: update,
		dataType: 'json',
		cache: false
	});
}

function update(data) {
	if(data.code) {
		// report error
		var html = '<div>Code: '+data.code+'</div>';
		html += '<div>'+data.reason+'</div>';
	} else {
		// build status report	
		var html = '<fieldset><div class="title">Currently downloading: ' + data.currently_downloading[0].nzbName + '</div>';
		html += '<div class="loader">';
		html += '<div class="info">'+ data.percent_complete + '% complete</div>';
		html += '<div class="bar" style="width: ' + data.percent_complete + '%"></div></div></fieldset>';
		html += '<h4 class="title">Queued Reports</h4>';
		
		html += '<ul id="queue">';
		for(var x in data.queued) {
			var report = data.queued[x];
			html += '<li>'+report['nzbName']+'<span>'+report['total_mb']+'MB</span></li>';
		}
		html += '</ul>';
	}
	$("#monitor").html($(html));
}

