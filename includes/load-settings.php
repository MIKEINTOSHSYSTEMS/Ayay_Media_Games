<?php

$options = load_site_settings();

define( "PRETTY_URL", filter_var($options['pretty_url'], FILTER_VALIDATE_BOOLEAN) );
define( "URL_PROTOCOL", $options['url_protocol'] );
define( "DOMAIN", URL_PROTOCOL . $_SERVER['SERVER_NAME'] . '/');

function load_site_settings(){
	$conn = open_connection();
	$sql = "SELECT * FROM options";
	$st = $conn->prepare($sql);
	$st->execute();
	$row = $st->fetchAll();
	$opt = array();
	foreach ($row as $item) {
		$opt[$item['name']] = $item['value'];
	}
	return $opt;
}

?>