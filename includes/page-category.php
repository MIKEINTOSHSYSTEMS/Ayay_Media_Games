<?php

require_once( TEMPLATE_PATH . '/functions.php' );

$cur_page = 1;
if(isset($_GET['page'])){
	$cur_page = htmlspecialchars($_GET['page']);
	if(!is_numeric($cur_page)){
		$cur_page = 1;
	}
}

$category = Category::getBySlug($_GET['slug']);

if($category){
	$data = get_game_list_category($category->name, 36, 36*($cur_page-1));
	$games = $data['results'];
	$total_games = $data['totalRows'];
	$total_page = $data['totalPages'];
	if(isset($category->meta_description) && $category->meta_description != ''){
		$meta_description = $category->meta_description;
	} else {
		$meta_description = _t('Play %a Games', $category->name).' | '.SITE_DESCRIPTION;
	}
	$archive_title = _t($category->name);
	$page_title = _t('%a Games', $category->name).' | '.SITE_DESCRIPTION;

	require( TEMPLATE_PATH . '/archive.php' );
} else {
	require( ABSPATH . 'includes/page-404.php' );
}

?>