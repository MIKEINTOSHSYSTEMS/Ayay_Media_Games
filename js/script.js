"use strict";
var Stats;
var listGames;
var distributor;
class GameList {
	getList(url){
		$('.fetch-loading').css('display', 'block');
		$('.fetch-list').css('display', 'none');
		let wait = new Promise((res) => {
			let xhr = new XMLHttpRequest();
			xhr.open('GET', url);
			xhr.onload = function() {
				if (xhr.status === 200) {
					let arr = JSON.parse(xhr.responseText);
					res(arr);
				}
				else {
					res(false);
				}
			}.bind(this);
			xhr.send();
		});
		return wait;
	}
	generateList(arr){
		listGames = arr;
		let result = '';
		let dom = document.getElementById("gameList");
		let index = 1;
		for(let i=0; i<arr.length; i++){
			result += '<tr id="tr'+(i+1)+'"><th scope="row">'+index+'</th><td><img src="'+arr[i].thumb_2+'" width="60px" height="auto" class="gamelist"></td><td>'+arr[i].title+'</td><td><span class="categories">'+arr[i].category+'</span></td><td><a href="'+arr[i].url+'" target="_blank">Play</a></td><td><span class"actions"><a href="#" onclick="addData('+i+')"><i class="fa fa-plus circle" aria-hidden="true"></i></a></span></td></tr>';
			index++;	
		}
		dom.innerHTML = result;
		$('.fetch-list').css('display', 'block');
		$('.fetch-loading').css('display', 'none');
	}
}
var getGame = new GameList();
function sendRequest(data, reload, action, id){
	$.ajax({
		url: 'request.php',
		type: 'POST',
		dataType: 'json',
		data:data,
		success: function (data) {
			//console.log(data.responseText);
		},
		error: function (data) {
			//console.log(data.responseText);
		},
		complete: function (data) {
			console.log(data.responseText);
			if(reload){
				location.reload();
			}
			if(action === 'edit-page'){
				set_edit_modal(JSON.parse(data.responseText));
			} else if(action === 'edit-game'){
				set_edit_game_modal(JSON.parse(data.responseText));
			} else if(action === 'edit-category'){
				set_edit_category_modal(JSON.parse(data.responseText));
			} else if(action === 'edit-collection'){
				set_edit_collection_modal(JSON.parse(data.responseText));
			} else if(action === 'remove'){
				show_action_info(data.responseText);
				$('.fetch-list').removeClass('disabled-list');
				if(id){
					remove_from_list(id-1);
				}
			}
		}
	});
}
function addData(id){
	$('.fetch-list').addClass('disabled-list');
	let arr = listGames[id];
	let data = {
		action: 'addGame',
		source: distributor,
		title: arr.title,
		thumb_1: arr.thumb_1,
		thumb_2: arr.thumb_2,
		description: arr.description,
		url: arr.url,
		instructions: arr.instructions,
		width: arr.width,
		height: arr.height,
		category: arr.category,
		tags: '',
	}
	sendRequest(data, false, 'remove', id+1);
}
function remove_from_list(id){
	$("#tr"+(id+1)).remove();
}
function set_edit_modal(data){
	$('#edit-id').val(data.id);
	$('#edit-title').val(data.title);
	$('#edit-slug').val(data.slug);
	$('#edit-content').text(data.content);
	$('#edit-createdDate').val(data.createdDate);
	$('#edit-page').modal('show');
}
function set_edit_game_modal(data){
	$("#edit-category option").prop("selected", false);
	//
	$('#edit-id').val(data.id);
	$('#edit-title').val(data.title);
	$('#edit-slug').val(data.slug);
	$('#edit-description').text(data.description);
	$('#edit-instructions').text(data.instructions);
	$('#edit-url').val(data.url);
	$('#edit-thumb_1').val(data.thumb_1);
	$('#edit-thumb_2').val(data.thumb_2);
	$('#edit-width').val(data.width);
	$('#edit-height').val(data.height);
	//$('#edit-category').val(data.category);
	$.each(data.category.split(","), function(i,e){
		$("#edit-category option[value='" + e + "']").prop("selected", true);
	});
	$('#edit-game').modal('show');
}
function set_edit_category_modal(data){
	$('#edit-id').val(data.id);
	$('#edit-name').val(data.name);
	$('#edit-slug').val(data.slug);
	$('#edit-description').val(data.description);
	$('#edit-meta_description').val(data.meta_description);
	$('#edit-category').modal('show');
}
function set_edit_collection_modal(data){
	$('#edit-id').val(data.id);
	$('#edit-name').val(data.name);
	$('#edit-data').val(data.data);
	$('#edit-collection').modal('show');
}
$(document).ready(function(){
	$("#add-all").on('click', function(){
		let f = $("#gameList > tr");
		f.each(function( index ) {
			let id = Number($( this ).attr('id').substring(2));
			addData(id-1);
		});
	});
	$('select#distributor-options').change(function(){
		$('.fetch-games').removeClass('active show');
		$($(this).val()).addClass('active show');
	});
	$( "form" ).submit(function( event ) {
		let arr = $( this ).serializeArray();
		let source = $(this).attr('class');
		if(source === 'gamemonetize' || source === 'gamedistribution' || source === 'gamepix'){
			event.preventDefault();
			let code = $("#p_code").val();
			distributor = $(this).attr('class');
			if(distributor){
				let url = 'https://api.cloudarcade.net/fetch.php?action=fetch&source='+distributor+'&data='+simple_array(arr)+'&code='+code;
				getGame.getList(url).then((res)=>{
					if(res['error']){
						show_action_info('error - '+res['error']);
					} else {
						getGame.generateList(res);
					}
				});
			}	
		} else if($(this).attr('id') === 'form-remote'){
			event.preventDefault();
			let data = {
				action: 'addGame',
				source: 'remote',
				title: get_value(arr, 'title'),
				thumb_1: get_value(arr, 'thumb_1'),
				thumb_2: get_value(arr, 'thumb_2'),
				description: get_value(arr, 'description'),
				url: get_value(arr, 'url'),
				instructions: get_value(arr, 'instructions'),
				width: get_value(arr, 'width'),
				height: get_value(arr, 'height'),
				tags: '',
			}
			if(get_value(arr, 'slug')){
				data.slug = get_value(arr, 'slug');
			}
			data.category = get_comma(get_category_list(arr));
			sendRequest(data, true);
		} else if($(this).attr('id') === 'form-newpage'){
			event.preventDefault();
			let data = {
				action: 'newPage',
				title: get_value(arr, 'title'),
				slug: (get_value(arr, 'slug').toLowerCase()).replace(/\s+/g, "-"),
				createdDate: get_value(arr, 'createdDate'),
				content: get_value(arr, 'content'),
			}
			sendRequest(data, true);
		} else if($(this).attr('id') === 'form-editpage'){
			event.preventDefault();
			let data = {
				action: 'editPage',
				title: get_value(arr, 'title'),
				slug: (get_value(arr, 'slug').toLowerCase()).replace(/\s+/g, "-"),
				id: get_value(arr, 'id'),
				createdDate: get_value(arr, 'createdDate'),
				content: get_value(arr, 'content'),
			}
			sendRequest(data, true);
		} else if($(this).attr('id') === 'form-editgame'){
			event.preventDefault();
			let data = {
				action: 'editGame',
				title: get_value(arr, 'title'),
				slug: (get_value(arr, 'slug').toLowerCase()).replace(/\s+/g, "-"),
				id: get_value(arr, 'id'),
				description: get_value(arr, 'description'),
				instructions: get_value(arr, 'instructions'),
				url: get_value(arr, 'url'),
				thumb_1: get_value(arr, 'thumb_1'),
				thumb_2: get_value(arr, 'thumb_2'),
				width: get_value(arr, 'width'),
				height: get_value(arr, 'height'),
			}
			data.category = get_comma(get_category_list(arr));
			sendRequest(data, true);
		} else if($(this).attr('id') === 'form-json'){
			event.preventDefault();
			let json = $('textarea[name="json-importer"]').val();
			if(json){
				try {
					json = JSON.parse(json);
					console.log(json);
					let content = [];
					for(let i=0; i<json.length; i++){
						content.push(json[i].title);
						content.push(json[i].url);
						content.push(json[i].width);
						content.push(json[i].height);
						content.push(json[i].thumb_1);
						content.push(json[i].thumb_2);
						content.push(json[i].category);
						content.push(json[i].source);
					}
					for(let i=0; i<json.length; i++){
						if(json[i].hasOwnProperty('slug')){
							if(json[i].slug === ''){
								delete json[i]['slug'];
							}
						}
						json[i].action = 'addGame';
						json[i].tags = '';
						sendRequest(json[i]);
					}
				} catch(err) {
					alert('Error! JSON data not valid');
				}
			} else {
				alert('Data is empty!')
			}
		}
	});
	$('#json-preview').click(function() {
		let json = $('textarea[name="json-importer"]').val();
		if(json){
			try {
				json = JSON.parse(json);
				console.log(json);
				let content = '';
				for(let i=0; i<json.length; i++){
					content += '<tr>';
					content += '<td>'+(i+1)+'</td>';
					content += '<td>'+json[i].title+'</td>';
					content += '<td>'+json[i].slug+'</td>';
					content += '<td><a href="'+json[i].url+'" target="_blank">'+json[i].url+'</a></td>';
					content += '<td>'+json[i].width+'</td>';
					content += '<td>'+json[i].height+'</td>';
					content += '<td><img src="'+json[i].thumb_1+'" width="80" height="80"></td>';
					content += '<td><img src="'+json[i].thumb_2+'" width="80" height="80"></td>';
					content += '<td>'+json[i].category+'</td>';
					content += '<td>'+json[i].source+'</td>';
					content += '</tr>';
				}
				$('#table-json-preview').css('display', 'block');
				$('#json-list-preview').replaceWith(content);
			} catch(err) {
				alert('Error! JSON data not valid');
			}
		} else {
			alert('Data is empty!')
		}
	});
	$('.remove-category').click(function() {
		if(confirm('Are you sure?\nDeleting category also delete all games on it (if there are).')){
			window.open('request.php?action=deleteCategory&id='+$(this).attr('id')+'&redirect=dashboard.php?viewpage=categories', '_self');
		}
	});
	$('.remove-collection').click(function() {
		if(confirm('Are you sure?')){
			window.open('request.php?action=deleteCollection&id='+$(this).attr('id')+'&redirect=dashboard.php?viewpage=collections', '_self');
		}
	});
	$('.activate-plugin').click(function() {
		window.open('request.php?action=pluginAction&name='+$(this).attr('id')+'&plugin_action=activate&redirect=dashboard.php?viewpage=plugin', '_self');
	});
	$('.deactivate-plugin').click(function() {
		window.open('request.php?action=pluginAction&name='+$(this).attr('id')+'&plugin_action=deactivate&redirect=dashboard.php?viewpage=plugin', '_self');
	});
	$('.remove-plugin').click(function() {
		window.open('request.php?action=pluginAction&name='+$(this).attr('id')+'&plugin_action=remove&redirect=dashboard.php?viewpage=plugin', '_self');
	});
	$('button.load-plugin-repo').click(function() {
		$(this).hide();
		let wrapper = $('.plugin-repo-container');
		wrapper.html('<h3>Loading...</h3>');
		let code = $("#p_code").val();
		let ref = window.location.hostname;
		let v = $("#cms-version").text();
		let xhr = new XMLHttpRequest();
		xhr.open('GET', 'https://api.cloudarcade.net/plugin-repo/fetch.php?ref='+ref+'&code='+code+'&v='+v);
		xhr.onload = function() {
			if (xhr.status === 200) {
				wrapper.html(xhr.responseText);
				//
				$('a.add-plugin-repo').click(function() {
					window.open('request.php?action=pluginAction&reqversion='+$(this).data('reqversion')+'&url='+$(this).data('url')+'&plugin_action=add_plugin&redirect=dashboard.php?viewpage=plugin', '_self');
				});
			}
			else {
				wrapper.html('<h3>Failed to load!</h3>');
			}
		}.bind(this);
		xhr.send();
	});
	$( "#newpagetitle" ).click(function() {
		let parent = $( "#newpagetitle" );
		parent.change(function(){
			$( "#newpageslug" ).val((parent.val().toLowerCase()).replace(/\s+/g, "-"));
		});
	});
	$( ".deletepage" ).click(function() {
		let id = $(this).attr('id');
		if(confirm('Are you sure want to delete this page ?')){
			let data = {
				action: 'deletePage',
				id: id,
			}
			sendRequest(data, true);
		}
	});
	$( ".editpage" ).click(function() {
		let id = $(this).attr('id');
		let data = {
			action: 'getPageData',
			id: id,
		}
		sendRequest(data, false, 'edit-page');
	});
	$( ".editgame" ).click(function() {
		let id = $(this).attr('id');
		let data = {
			action: 'getGameData',
			id: id,
		}
		sendRequest(data, false, 'edit-game');
	});
	$( ".editcategory" ).click(function() {
		let id = $(this).attr('id');
		let data = {
			action: 'getCategoryData',
			id: id,
		}
		sendRequest(data, false, 'edit-category');
	});
	$( ".editcollection" ).click(function() {
		let id = $(this).attr('id');
		let data = {
			action: 'getCollectionData',
			id: id,
		}
		sendRequest(data, false, 'edit-collection');
	});
	$( "button.btn-theme" ).click(function() {
		let id = $(this).attr('id');
		window.open('request.php?action=updateTheme&theme='+$(this).attr('id')+'&redirect=dashboard.php?viewpage=themes', '_self');
		//sendRequest(data, false, 'edit-collection');
	});
	$(".custom-file-input").on("change", function() {
	  var fileName = $(this).val().split("\\").pop();
	  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
	});
	$('#stats-option').change(function(){
		let val = $(this).val();
		let params;
		if(val === 'week'){
			params = {"limit":"-1","offset":"0","sub":"-7"};
		} else if(val === 'month'){
			params = {"limit":"-1","offset":"0","sub":"-30"};
		}
		get_data('../includes/statistics.php', params).then((res)=>{
			update_stats(convert_stats_data(res));
		});
	});
	function simple_array(arr){
		let tmp = [];
		arr.forEach((item)=>{
			tmp.push(item.value);
		});
		return JSON.stringify(tmp);
	}
	function get_value(arr, key){
		for(let i=0; i<arr.length; i++){
			if(arr[i].name === key){
				return arr[i].value;
			}
		}
	}
	function get_category_list(arr){
		let cats = [];
		for(let i=0; i<arr.length; i++){
			if(arr[i].name === 'category'){
				cats.push({name: arr[i].value});
			}
		}
		return cats;
	}
	function get_official_info(){
		let v = $("#cms-version").text();
		$.ajax({
			url: 'https://api.cloudarcade.net/get_info.php',
			type: 'POST',
			dataType: 'json',
			data: {version: v},
			success: function (data) {
				//console.log(data.responseText);
			},
			error: function (data) {
				//console.log(data.responseText);
			},
			complete: function (data) {
				let res = JSON.parse(data.responseText);
				$('.official-info').append(res['info']);
				if(res['update']){
					$('.update-info').append('<div class="alert alert-info alert-dismissible fade show" role="alert">New update is available! CloudArcade v'+res['update']+' , open "Updater" for more info!<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
				}
			}
		});
	}
	if($('.official-info').length){
		get_official_info();
	}
	let quote = $('#quote');
	if(quote.length){
		console.log('q')
		$.ajax({
			url: 'https://api.cloudarcade.net/get_quote.php',
			type: 'POST',
			dataType: 'json',
			complete: function (data) {
				let q = JSON.parse(data.responseText);
				quote.html(
				"<blockquote class='quote-text'>\""+ q.text +"\"</blockquote>" +
					"<small class='author'> - "+ q.author +"</small>"
				);
			}
		});
	}
});
function show_action_info(str){
	let type = str.substring(0, 5);
	if(type === 'added' || type === 'exist' || type === 'error'){
		let msg = str.substring(8);
		let alert_type = 'success';
		if(type === 'exist'){
			alert_type = 'warning';
			msg = 'Game already exist! '+msg;
		} else if(type === 'added') {
			msg = 'Game added! '+msg;
		} else if(type === 'error') {
			alert_type = 'danger';
			msg = 'Error! '+msg;
		}
		$('#action-info').html('<div class="alert alert-'+alert_type+' alert-dismissible fade show" role="alert">'+msg+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
	}
}
function get_comma(arr){
	let res = '';
	arr.forEach((item, index)=>{
		res += item['name'];
		if(index < arr.length-1){
			res += ',';
		}
	});
	return res;
}
function openSidebar() {
	let sidebar = document.getElementById("sidebar");
	if(sidebar.style.width === "260px"){ //Close
		closeSidebar();
	} else {
		sidebar.style.width = "260px";
		document.getElementById("content").style.marginLeft = "260px";
		//document.getElementById("content-bar").style.marginLeft = sidebar.style.width;
	}
}
function closeSidebar() {
	document.getElementById("sidebar").style.width = "0";
	document.getElementById("content").style.marginLeft= "0";
	//document.getElementById("content-bar").style.marginLeft= "0";
}
function setTheme(themeName) {
	localStorage.setItem('cloudarcade_admin-theme', themeName);
	document.documentElement.className = themeName;
	if(themeName === 'theme-light'){
		if(Stats){
			Chart.defaults.global.defaultFontColor = '#666';
			Stats.update();
		}
	} else {
		if(Stats){
			Chart.defaults.global.defaultFontColor = '#adbcce';
			Stats.update();
		}
	}
}
// function to toggle between light and dark theme
function toggleTheme() {
	if (localStorage.getItem('cloudarcade_admin-theme') === 'theme-dark') {
		setTheme('theme-light');
	} else {
		setTheme('theme-dark');
	}
}
// Immediately invoked function to set the theme on initial load
(function () {
	if (localStorage.getItem('cloudarcade_admin-theme') === 'theme-dark') {
		setTheme('theme-dark');
		document.getElementById('darkSwitch').checked = true;
	} else {
		setTheme('theme-light');
	}
})();

var dropdown = document.getElementsByClassName("dropdown-btn");
var dropdown_content = document.getElementsByClassName("dropdown-container")[0];
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
  this.classList.toggle("active");
  var dropdownContent = dropdown_content;
  if (dropdownContent.style.display === "block") {
  dropdownContent.style.display = "none";
  } else {
  dropdownContent.style.display = "block";
  }
  });
}