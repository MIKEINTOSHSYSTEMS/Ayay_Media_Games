<?php

require_once( TEMPLATE_PATH . '/functions.php' );

$_GET['slug'] = htmlspecialchars($_GET['slug']);

$cur_page = 1;
if(isset($_GET['page'])){
	$cur_page = htmlspecialchars($_GET['page']);
	if(!is_numeric($cur_page)){
		$cur_page = 1;
	}
}

$data = Game::searchGame($_GET['slug'], 36, 36*($cur_page-1));
$games = $data['results'];
$total_games = $data['totalRows'];
$total_page = $data['totalPages'];
$meta_description = _t('Search %a Games', $_GET['slug']).' | '.SITE_DESCRIPTION;
$archive_title = _t('Search %a', $_GET['slug']);
$page_title = _t('Search %a Games', $_GET['slug']).' | '.SITE_DESCRIPTION;

require( TEMPLATE_PATH . '/search.php' );

?>