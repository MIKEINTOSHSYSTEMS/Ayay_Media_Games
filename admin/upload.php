<?php
session_start();
require_once('../config.php');
require_once('../init.php');

$action = isset( $_POST['action'] ) ? $_POST['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( $action != "login" && $action != "logout" && !$username ) {
	exit('logout');
}

if( ADMIN_DEMO ){
	header('Location: dashboard.php?viewpage=addgame');
	exit();
}
if (!file_exists('tmp')) {
	mkdir('tmp', 0777, true);
}
if (!file_exists('../games')) {
	mkdir('../games', 0777, true);
}
$target_dir = "tmp/";
$target_file = $target_dir . strtolower(str_replace(' ', '-', basename($_FILES["gamefile"]["name"])));
$folder_name = 0;
if(isset($_POST['slug'])){
	$_POST['slug'] = strtolower(preg_replace('~[^A-Za-z0-9-_]~','', $_POST['slug']));
	$folder_name = $_POST['slug'];
} else {
	$folder_name = strtolower(preg_replace('~[^A-Za-z0-9-_]~','', $_POST['title']));
}
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
if($fileType != 'zip'){
	$uploadOk = 0;
}

if ($uploadOk == 0) {
  echo "error1";
} else {
  if (move_uploaded_file($_FILES["gamefile"]["tmp_name"], $target_file)) {
  	$check = array();
	$check['index'] = 'false';
	$check['thumb_1'] = false;
	$check['thumb_2'] = false;
	//uploaded
	$za = new ZipArchive();
	$za->open($target_file);
	for( $i = 0; $i < $za->numFiles; $i++ ){
		$stat = $za->statIndex( $i );
		$name = $stat['name'];
		if($name == 'index.html'){
			$check['index'] = $name;
		}
		if($name == 'thumb_1.png' || $name == 'thumb_1.jpg' || $name == 'thumb_1.jpeg' || $name == 'thumb_1.PNG' || $name == 'thumb_1.JPG'){
			if(!$check['thumb_1']){
				$check['thumb_1'] = $name;
			}
		}
		if($name == 'thumb_2.png' || $name == 'thumb_2.jpg' || $name == 'thumb_2.jpeg' || $name == 'thumb_2.PNG' || $name == 'thumb_2.JPG'){
			if(!$check['thumb_2']){
				$check['thumb_2'] = $name;
			}
		}
	}
	$za->close();
  } else {
	echo "error2";
  }
}
$error = array();
if($uploadOk == 1){
	if(!$check['index']){
		$error['err1'] = 'No index.html on root detected!';
		$uploadOk = 0;
	}
	if(!$check['thumb_1']){
		$error['err2'] = 'No thumb_1.jpg/png on root detected!';
		$uploadOk = 0;
	}
	if(!$check['thumb_2']){
		$error['err3'] = 'No thumb_2.jpg/png on root detected!';
		$uploadOk = 0;
	}
}
if($uploadOk == 0){
	$error['err0'] = 'Upload failed!';
	unlink($target_file);
	header('Location: dashboard.php?viewpage=addgame&status=error&error-data='.json_encode($error));
} else {
	$zip = new ZipArchive;
	$res = $zip->open($target_file);
	if ($res === TRUE) {
		$zip->extractTo('../games/'.$folder_name.'/');
		$zip->close();
	} else {
	  echo 'doh!';
	}
	unlink($target_file);
	$cats = '';
	$i = 0;
	$total = count($_POST['category']);
	foreach ($_POST['category'] as $key) {
		$cats = $cats.$key;
		if($i < $total-1){
			$cats = $cats.',';
		}
		$i++;
	}
	$_POST['ref'] = 'upload';
	$_POST['action'] = 'addGame';
	$_POST['category'] = $cats;
	$_POST['thumb_1'] = '/games/'.$folder_name.'/'.$check['thumb_1'];
	$_POST['thumb_2'] = '/games/'.$folder_name.'/'.$check['thumb_2'];
	$_POST['url'] = '/games/'.$folder_name.'/';
	if( SMALL_THUMB ){
		$output = pathinfo($check['thumb_2']);
		$_POST['thumb_small'] = '/games/'.$folder_name.'/'.$folder_name.'-'.$output['filename'].'_small.'.$output['extension'];
		imgResize('..'.$_POST['thumb_2'], 160, 160, $folder_name);
	}
	//
	$_POST['redirect'] = 'dashboard.php?viewpage=addgame&status=uploaded';
	require 'request.php';
}
?>