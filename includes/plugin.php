<?php

defined('ABSPATH') or die('abcd');

define('PLUGIN_PATH', 'content/plugins/');

$plugin_list = get_plugin_list();
//print_r($plugin_list);
function get_plugin_list(){
	$list = [];
	$dirs = scan_folder( PLUGIN_PATH );
	foreach ($dirs as $dir) {
		$info = get_plugin_info($dir);
		if($info){
			array_push($list, $info);
		}
	}
	return $list;
}

function get_plugin_info($name){
	$plugin_dir = ABSPATH . PLUGIN_PATH . $name;
	$json_path = $plugin_dir . '/info.json';

	if(file_exists($json_path)){
		$array = json_decode(file_get_contents($json_path), true);
		if(isset($array['name']) && isset($array['version']) && isset($array['author']) && isset($array['description']) && isset($array['require_version']) && isset($array['tested_version']) && isset($array['type']) && isset($array['target'])){
			$array['path'] = $plugin_dir;
			$array['dir_name'] = $name;
			return $array;
		}
		return false;
	}
	return false;
}

function is_plugin_exist($name){
	global $plugin_list;

	foreach ($plugin_list as $plugin) {
		if($plugin['dir_name'] == $name){
			return true;
		}
	}
}

function load_plugins($type){
	global $plugin_list;
	
	foreach ($plugin_list as $plugin) {
		if($plugin['target'] == $type){
			if(substr($plugin['dir_name'], 0, 1) != '_'){
				require_once( $plugin['path'].'/main.php' );
			}
		}
	}
}

?>