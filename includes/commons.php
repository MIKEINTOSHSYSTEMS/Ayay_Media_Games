<?php

defined('ABSPATH') or die('abcd commons');

function get_all_categories(){
	$data = Category::getList();
	return $data['results'];
}
function get_user($username){
	$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$sql = 'SELECT * FROM users WHERE username = :username';
	$st = $conn->prepare( $sql );
	$st->bindValue( ":username", $username, PDO::PARAM_STR );
	$st->execute();
	$row = $st->fetch();
	$conn = null;
	if ( $row ) return $row;
	return false;
}
function is_login(){
	if(isset( $_SESSION['username'] )){
		return true;
	} else {
		return false;
	}
}
function show_logout(){
	if(is_login()){
		echo '<a href="'.DOMAIN.'admin.php?action=logout"> Log out </a>';
	}
}
function get_permalink($type, $slug = 0){
	if($type == 'game'){
		if( PRETTY_URL ){
			return DOMAIN . 'game/' . $slug;
		} else {
			return DOMAIN . 'index.php?viewpage=game&slug=' . $slug;
		}
	} else if($type == 'archive'){
		if( PRETTY_URL ){
			return DOMAIN . 'archive/' . $slug;
		} else {
			return DOMAIN . 'index.php?viewpage=archive&slug=' . $slug;
		}
	} else if($type == 'search'){
		if( PRETTY_URL ){
			return DOMAIN . 'search/' . $slug;
		} else {
			return DOMAIN . 'index.php?viewpage=search&key=' . $slug;
		}
	} else if($type == 'category'){
		if( PRETTY_URL ){
			return DOMAIN . 'category/' . strtolower($slug);
		} else {
			return DOMAIN . 'index.php?viewpage=category&slug=' . strtolower($slug);
		}
	} else if($type == 'page'){
		if( PRETTY_URL ){
			return DOMAIN . 'page/' . $slug;
		} else {
			return DOMAIN . 'index.php?viewpage=page&slug=' . $slug;
		}
	} else {
		if( PRETTY_URL ){
			if(!$slug){
				$slug = '';
			}
			return DOMAIN . $type .'/' . $slug;
		} else {
			if(!$slug){
				$slug = '';
			} else {
				$slug = '&slug='.$slug;
			}
			return DOMAIN . 'index.php?viewpage='.$type.$slug;
		}
	}
}
function get_small_thumb($game){
	$thumb = (isset($game->thumb_small) && $game->thumb_small != '' ? esc_url($game->thumb_small) : esc_url($game->thumb_2));
	if(substr($thumb, 0, 1) == '/'){
		$thumb = DOMAIN . substr($thumb, 1);
	}
	return $thumb;
}
function get_game_url($game){
	$url = esc_url($game->url);
	if(substr($url, 0, 7) == '/games/'){
		$url = DOMAIN . substr($url, 1);
	}
	return $url;
}
function commas_to_array($str){
	return preg_split("/\,/", $str);
}
function html_purify($html_content){
	require_once '../vendor/HTMLPurifier/HTMLPurifier.auto.php';
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$clean_html = $purifier->purify($html_content);
	return $clean_html;
}
function esc_string($str){
	return filter_var($str, FILTER_SANITIZE_STRING);
}
function esc_int($int){
	return filter_var($int, FILTER_SANITIZE_NUMBER_INT);
}
function esc_url($str){
	return filter_var($str, FILTER_SANITIZE_URL);
}
function esc_slug($str){
	return preg_replace('~[^A-Za-z0-9-_]~','', $str);
}
function imgResize($path, $rs_width=160, $rs_height=160, $slug = '') {
	$x = getimagesize($path);
	$width  = $x['0'];
	$height = $x['1'];
	switch ($x['mime']) {
	  case "image/gif":
		 $img = imagecreatefromgif($path);
		 break;
	  case "image/jpg":
	  case "image/jpeg":
		 $img = imagecreatefromjpeg($path);
		 break;
	  case "image/png":
		 $img = imagecreatefrompng($path);
		 break;
	}
	$img_base = imagecreatetruecolor($rs_width, $rs_height);
	if($x['mime'] == "image/png"){
		imageAlphaBlending($img_base, false);
		imageSaveAlpha($img_base, true);
	}
	imagecopyresampled($img_base, $img, 0, 0, 0, 0, $rs_width, $rs_height, $width, $height);
	$path_info = pathinfo($path);
	$output = $path_info['dirname'].'/'.$slug.'-'.$path_info['filename'].'_small.'.$path_info['extension'];
	switch ($path_info['extension']) {
	  case "gif":
		 imagegif($img_base, $output);  
		 break;
	case "jpg":
	case "jpeg":
		 imagejpeg($img_base, $output);
		 break;
	  case "png":
		 imagepng($img_base, $output, 9);
		 break;
	}
}
function check_purchase_code(){
	return true;
}
function get_admin_warning(){
	$results = [];
	if(!check_purchase_code() && !ADMIN_DEMO){
		array_push($results, 'Please provide your <b>Item Purchase code</b>. You can submit or update your Purchase code on site settings.');
	}
	if(URL_PROTOCOL == 'http://'){
		if(is_https()){
			array_push($results, 'You\'re using HTTPS but current config use HTTP, you can switch to HTTPS in Settings -> Advanced.');
		}
	}
	if(!check_writeable()){
		array_push($results, 'CloudArcade don\'t have permissions to modify files, uploaded files can\'t be saved and can\'t do backup or update. Change all folders and files CHMOD to 777 to fix this.');
	}
	if(!class_exists('ZipArchive')){
		array_push($results, '"ZipArchive" extension is missing or disabled. Can\'t do backup or update.');
	}
	if( !ini_get('allow_url_fopen') ) {
		array_push($results, '"allow_url_fopen" is disabled/off, please turn it on. Can\'t import thumbnails.'.ini_get('allow_url_fopen'));
	}
	return $results;
}
function is_https() {
	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
		return true;
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
		return true;
	} else {
		return false;
	}
}
function check_writeable(){
	if (is_writable('../config.php') && is_writable('../site-settings.php') && is_writable('../admin/upload.php')) {
		return true;
	} else {
		return false;
	}
}
function get_cur_url(){
	return DOMAIN . substr($_SERVER['REQUEST_URI'], 1);
}
function get_rating($type, $game){
	if($type == '5'){
		if($game->upvote+$game->downvote > 0){
			return round(($game->upvote/($game->upvote+$game->downvote))*5);
		} else {
			return 0;
		}
	}
}
function is_user_admin($username){
	$conn = open_connection();
	$sql = "SELECT * FROM users WHERE username = :username";
	$st = $conn->prepare($sql);
	$st->bindValue(":username", $username, PDO::PARAM_STR);
	$st->execute();
	$row = $st->fetch();
	if ($row) {
		if($row['role'] === 'admin'){
			return true;
		}
	}
	return false;
}
function scan_folder($path){
	$array = [];

	$dirs = scandir( ABSPATH . $path);
	$dirs = array_diff($dirs, array('.', '..'));

	foreach ($dirs as $dir) {
		if(is_dir( ABSPATH . $path . $dir)){
			if($dir != '.' || $dir != '..'){
				array_push($array, $dir);
			}
		}
	}

	return $array;
}
function scan_files($path){
	$directory = new \RecursiveDirectoryIterator(ABSPATH.$path);
	$iterator = new \RecursiveIteratorIterator($directory);
	$files = array();
	foreach ($iterator as $info) {
		if (is_file($info->getPathname())) {
			$files[] = str_replace(ABSPATH, '', $info->getPathname());
		}
	}
	return $files;
}
function delete_files($target) {
	if(is_dir($target)){
		$files = glob( $target . '*', GLOB_MARK );

		foreach( $files as $file ){
			delete_files( $file );      
		}

		rmdir( $target );
	} elseif(is_file($target)) {
		unlink( $target );  
	}
}

function add_to_zip($source, $destination, $ignores = []) {
	if (extension_loaded('zip') && is_login()) {
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				if (is_dir($source)) {
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {

						$ignored = false;

						foreach ($ignores as $ignore) {
							if(strpos($file, $ignore)){
								$ignored = true;
								break;
							}
						}

						if($ignored){
							continue;
						}

						if (is_dir($file)) {
							if(count(glob("$file/*")) > 0){ //If folder not empty
								$zip->addEmptyDir(str_replace($source . DIRECTORY_SEPARATOR, '', $file . DIRECTORY_SEPARATOR));
							}
						} else if (is_file($file)) {
							$zip->addFromString(str_replace($source . DIRECTORY_SEPARATOR, '', $file), file_get_contents($file));
						}
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
			}
			return $zip->close();
		}
	}
	return false;
}

function getIpAddr() {
	if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
		$ipAddr = $_SERVER["HTTP_CF_CONNECTING_IP"];
	} elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ipAddr = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ipAddr = strtok($_SERVER['HTTP_X_FORWARDED_FOR'], ',');
	} else {
		$ipAddr = $_SERVER['REMOTE_ADDR'];
	}
	return $ipAddr;
}

function get_user_avatar($username = null){
	global $login_user;
	$user;
	if(!$username){
		if($login_user){
			$username = $login_user->username;
			$user = $login_user;
		}
	} else {
		$cur_user = User::getByUsername($username);
		if($cur_user){
			$user = $cur_user;
		}
	}
	if($user){
		if(file_exists(ABSPATH.'images/avatar/'.$username.'.png')){
			return DOMAIN.'images/avatar/'.$username.'.png';
		} elseif($user->avatar){
			return DOMAIN.'images/avatar/default/'.$user->avatar.'.png';
		}
	}
	return DOMAIN.'images/default_profile.png';
}

$lang_data = [];

function load_language($type){
	global $lang_data;
	global $options;
	if($type === 'index'){
		$file = TEMPLATE_PATH.'/locales/'.$options['language'].'.json';
	} elseif($type === 'admin'){
		$file = ABSPATH.'locales/'.$options['language'].'.json';
	}
	if(file_exists($file)){
		$lang_data = json_decode(file_get_contents($file), true);
	}
}

function translate($str, $val1 = null, $val2 = null){
	global $lang_data;
	$translated = $str;
	if(isset($lang_data[$str])){
		$translated = $lang_data[$str];
	}
	if(!is_null($val1)){
		$translated = str_replace('%a', $val1, $translated);
	}
	if(!is_null($val2)){
		$translated = str_replace('%b', $val2, $translated);
	}
	return $translated;
}

function _t($str, $val1 = null, $val2 = null){
	return translate($str, $val1, $val2);
}

function _e($str, $val1 = null, $val2 = null){
	echo translate($str, $val1, $val2);
}

?>