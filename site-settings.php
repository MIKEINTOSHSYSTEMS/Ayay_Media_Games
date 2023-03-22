<?php
	
require( 'includes/load-settings.php' );

define( "SITE_TITLE", $options['site_title'] );
define( "SITE_DESCRIPTION", $options['site_description'] );
define( "META_DESCRIPTION", $options['meta_description'] );
define( "SITE_LOGO", $options['site_logo'] );
define( "THEME_NAME", $options['theme_name'] );
define( "TEMPLATE_PATH", "content/themes/".THEME_NAME );
define( "IMPORT_THUMB", filter_var($options['import_thumb'], FILTER_VALIDATE_BOOLEAN) );
define( "COMPRESSION_LEVEL", 80 );
define( "CUSTOM_SLUG", filter_var($options['custom_slug'], FILTER_VALIDATE_BOOLEAN) );
define( "SMALL_THUMB", filter_var($options['small_thumb'], FILTER_VALIDATE_BOOLEAN) );
define( "ADMIN_DEMO", false );

?>