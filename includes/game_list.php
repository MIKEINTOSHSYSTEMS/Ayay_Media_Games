<?php

function get_game_list($type, $amount, $page=0){
	if($type == 'new'){
		$data = Game::getList( $amount, 'id DESC', $page );
		return $data;
	} elseif($type == 'random'){
		$data = Game::getList( $amount, 'RAND()', $page );
		return $data;
	} elseif($type == 'popular'){
		$data = Game::getList( $amount, 'views DESC', $page );
		return $data;
	} elseif($type == 'likes'){
		$data = Game::getList( $amount, 'upvote DESC', $page );
		return $data;
	}
}
function get_collection($name, $amount = 12){
	$data = Collection::getListByCollection( $name, $amount );
	return $data;
}
function get_game_list_category($cat_name, $amount, $page=0){
	$cat_id = Category::getIdByName( $cat_name );
	$data = Category::getListByCategory( $cat_id, $amount, $page );
	return $data;
}
function get_game_list_categories($arr, $amount, $page=0){
	$ids = array();
	foreach ($arr as $cat_name) {
		$cat_id = Category::getIdByName( $cat_name );
		array_push($ids, $cat_id);
	}
	$data = Category::getListByCategories( $ids, $amount, $page );
	return $data;
}

?>