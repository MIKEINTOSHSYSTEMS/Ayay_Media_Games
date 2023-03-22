<?php

require_once( TEMPLATE_PATH . '/functions.php' );

if ( !isset($_GET['slug']) || !$_GET['slug'] ) {
	require( ABSPATH . 'includes/page-homepage.php' );
	return;
}

$_GET['slug'] = htmlspecialchars($_GET['slug']);

$page = Page::getBySlug( $_GET['slug'] );
if($page){
	$page_title = $page->title . ' | '.SITE_TITLE;
	$meta_description = str_replace(array('"', "'"), "", strip_tags($page->content));
	
	require( TEMPLATE_PATH . '/page.php' );
} else {
	require( ABSPATH . 'includes/page-404.php' );
}

?>