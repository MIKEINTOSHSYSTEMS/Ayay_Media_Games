<?php

require_once( TEMPLATE_PATH . '/functions.php' );

if ( !isset($_GET['slug']) || !$_GET['slug'] ) {
	require( ABSPATH . 'includes/page-homepage.php' );
	return;
}

$_GET['slug'] = htmlspecialchars($_GET['slug']);

Game::update_views( $_GET['slug'] );
$game = Game::getBySlug( $_GET['slug'] );
if($game){
	$page_title = $game->title . ' | '.SITE_DESCRIPTION;
	$meta_description = str_replace(array('"', "'"), "", strip_tags($game->description));

	require( TEMPLATE_PATH . '/game.php' );
} else {
	require( ABSPATH . 'includes/page-404.php' );
}

?>