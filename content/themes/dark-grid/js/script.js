"use strict";
$(function() {

	var $nav = $('nav.greedy');
	var $btn = $('nav.greedy button');
	var $vlinks = $('nav.greedy .links');
	var $hlinks = $('nav.greedy .hidden-links');

	var numOfItems = 0;
	var totalSpace = 0;
	var breakWidths = [];

	// Get initial state
	$vlinks.children().outerWidth(function(i, w) {
		totalSpace += w;
		numOfItems += 1;
		breakWidths.push(totalSpace);
	});

	var availableSpace, numOfVisibleItems, requiredSpace;

	function check() {

		// Get instant state
		availableSpace = $vlinks.width() - 10;
		numOfVisibleItems = $vlinks.children().length;
		requiredSpace = breakWidths[numOfVisibleItems - 1];

		// There is not enought space
		if (requiredSpace > availableSpace) {
			$vlinks.children().last().prependTo($hlinks);
			numOfVisibleItems -= 1;
			check();
			// There is more than enough space
		} else if (availableSpace > breakWidths[numOfVisibleItems]) {
			$hlinks.children().first().appendTo($vlinks);
			numOfVisibleItems += 1;
		}
		// Update the button accordingly
		$btn.attr("count", numOfItems - numOfVisibleItems);
		if (numOfVisibleItems === numOfItems) {
			$btn.addClass('hidden');
		} else $btn.removeClass('hidden');
	}

	// Window listeners
	$(window).resize(function() {
		check();
	});

	$btn.on('click', function() {
		$hlinks.toggleClass('hidden');
	});

	check();

});
function open_fullscreen() {
	let game = document.getElementById("game-area");
	if (game.requestFullscreen) {
	  game.requestFullscreen();
	} else if (game.mozRequestFullScreen) { /* Firefox */
	  game.mozRequestFullScreen();
	} else if (game.webkitRequestFullscreen) { /* Chrome, Safari and Opera */
	  game.webkitRequestFullscreen();
	} else if (game.msRequestFullscreen) { /* IE/Edge */
	  game.msRequestFullscreen();
	}
};
var can_resize = false;
if(window.location.href.indexOf('game') > 0){
	can_resize = true;
}
$(document).ready(()=>{
	$("#navb").on('show.bs.collapse', function(){
		$('.user-avatar').hide();
	});
	$("#navb").on('hidden.bs.collapse', function(){
		$('.user-avatar').show();
	});
	resize_game_iframe();
	function resize_game_iframe(){
		if(can_resize){
			let iframe = $("iframe.game-iframe");
			let size = {
				width: Number(iframe.attr('width')),
				height: Number(iframe.attr('height')),
			}
			let ratio = (size.height/size.width)*100;
			let win_ratio = (window.innerHeight/window.innerWidth)*100;
			if(win_ratio <= 110){
				if(ratio > 80){
					ratio = 80;
				}
			} else if(win_ratio >= 130){
				if(ratio < 100){
					ratio = 100;
				}
			}
			$('.game-iframe-container').css('padding-top', ratio+'%');
		}
	}
	$(window).resize(function() {
		resize_game_iframe();
	});
	$('.stats-vote #upvote').on('click', function() {
		let data_id = $(this).attr('data-id');
		$.ajax({
			url: '../includes/vote.php',
			type: 'POST',
			dataType: 'json',
			data: {'vote': true, 'action': 'upvote', 'id': data_id},
			success: function (data) {
				//console.log(data.responseText);
			},
			error: function (data) {
				//console.log(data.responseText);
			},
			complete: function (data) {
				console.log(data.responseText);
				$('.icon-vote').hide();
				let elem = $('.vote-status');
				elem.addClass('text-success');
				elem.append('Liked!');
			}
		});
	});
	$('.stats-vote #downvote').on('click', function() {
		let data_id = $(this).attr('data-id');
		$.ajax({
			url: '../includes/vote.php',
			type: 'POST',
			dataType: 'json',
			data: {'vote': true, 'action': 'downvote', 'id': data_id},
			success: function (data) {
				//console.log(data.responseText);
			},
			error: function (data) {
				//console.log(data.responseText);
			},
			complete: function (data) {
				console.log(data.responseText);
				$('.icon-vote').hide();
				let elem = $('.vote-status');
				elem.addClass('text-danger');
				elem.append('Disliked!');
			}
		});
	});
	$('.user-avatar').on('click', ()=>{
		let element = $('.user-links');
		if (element.is(":hidden")) {
			element.removeClass('hidden');
		} else element.addClass('hidden');
	});
	$('#btn_prev').on('click', function() {
		$('.profile-gamelist ul').animate({
			scrollLeft: '-=150'
		}, 300, 'swing');
	});
	
	$('#btn_next').on('click', function() {
		$('.profile-gamelist ul').animate({
			scrollLeft: '+=150'
		}, 300, 'swing');
	});
	$('.delete-comment').on('click', function() {
		let id = $(this).attr('data-id');
		$.ajax({
			url: '../includes/comment.php',
			type: 'POST',
			dataType: 'json',
			data: {'delete': true, 'id': id},
			success: function (data) {
				//console.log(data.responseText);
			},
			error: function (data) {
				//console.log(data.responseText);
			},
			complete: function (data) {
				console.log(data.responseText);
				if(data.responseText === 'deleted'){
					$('.id-'+id).remove();
				}
			}
		}, this);
	});
	let game_id;
	if($('#comments').length){
		game_id = $('.stats-vote #upvote').attr('data-id');
		$.ajax({
			url: '../includes/comment.php',
			type: 'POST',
			dataType: 'json',
			data: {'load': true, 'game_id': game_id},
			success: function (data) {
				//console.log(data.responseText);
			},
			error: function (data) {
				//console.log(data.responseText);
			},
			complete: function (data) {
				let comments = JSON.parse(data.responseText);
				load_comments(convert_comments(comments));
			}
		});
	}
	function convert_comments(array){
		let data = [];
		array.forEach((item)=>{
			let arr = {
				id: Number(item.id),
				created: item.created_date,
				content: item.comment,
				fullname: item.sender_username,
				profile_picture_url: item.avatar,
			};
			if(Number(item.parent_id)){
				arr.parent = Number(item.parent_id);
			}
			if(!arr.fullname){
				arr.fullname = 'Anonymous';
			}
			data.push(arr);
		});
		return data;
	}
	function load_comments(array){
		let read_only = false;
		let avatar = $('.user-avatar img').attr('src');
		if(!avatar){
			avatar = '/images/default_profile.png';
			read_only = true;
		}
		$('#comments').comments({
			enableUpvoting:false,
			roundProfilePictures: true,
			popularText: '',
			profilePictureURL: avatar,
			readOnly: read_only,
			getComments: function(success, error) {
				success(array);
			},
			postComment: function(commentJSON, success, error) {
				commentJSON.source = 'jquery-comments';
				commentJSON.send = true;
				commentJSON.game_id = game_id;
				$.ajax({
					type: 'post',
					url: '../includes/comment.php',
					data: commentJSON,
					success: function(comment) {
						console.log(comment);
						success(commentJSON)
					},
					error: error
				});
			}
		});
	}
});