<?php

session_start();

require( 'config.php' );
require( 'init.php' );
require( 'classes/Collection.php' );
require( 'includes/plugin.php' );

load_language('index');
load_plugins('index');

$page_name = isset( $_GET['viewpage'] ) ? $_GET['viewpage'] : 'homepage';

if(file_exists( 'includes/page-' . $page_name . '.php' )){
	require( 'includes/page-' . $page_name . '.php' );
} else {
	if(file_exists( TEMPLATE_PATH.'/page-' . $page_name . '.php' )){
		require( TEMPLATE_PATH.'/page-' . $page_name . '.php' );
	} else {
		require( 'includes/page-404.php' );
	}
}

?>