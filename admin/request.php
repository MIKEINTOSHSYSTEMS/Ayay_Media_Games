<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
require_once( "../config.php" );
require_once( "../init.php" );

if(count($_POST) == 0){
	$_POST = $_GET;
}

$action = isset( $_POST['action'] ) ? $_POST['action'] : "";
$username = isset( $_SESSION['username'] ) ? $_SESSION['username'] : "";

if ( !$username || !USER_ADMIN ) {
	exit('logout');
}
if(isset($_POST['redirect'])){
	$_POST['redirect'] = esc_url($_POST['redirect']);
}

if( ADMIN_DEMO ){
	if($action == 'getPageData' || $action == 'getGameData' || $action == 'getCategoryData'){
		//
	} else {
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect']);
		}
		exit();
	}
}

switch ( $action ) {
	case 'deleteGame':
		$game = Game::getById( (int)$_POST['id'] );
		if($game){
			$game->delete();
		}
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=deleted');
		}
		break;
	case 'getGameData':
		$page = Game::getById( (int)$_POST['id'] );
		$json = json_encode($page);
		echo $json;
		break;
	case 'editGame':
		$_POST['description'] = html_purify($_POST['description']);
		$_POST['instructions'] = html_purify($_POST['instructions']);
		$game = Game::getById( (int)$_POST['id'] );
		$game->storeFormValues( $_POST );
		$game->update();
		break;
	case 'newPage':
		$_POST['content'] = html_purify($_POST['content']);
		$page = new Page;
		$page->storeFormValues( $_POST );
		$page->insert();
		break;
	case 'deletePage':
		$page = Page::getById( (int)$_POST['id'] );
		$page->delete();
		break;
	case 'getPageData':
		$page = Page::getById( (int)$_POST['id'] );
		$json = json_encode($page);
		echo $json;
		break;
	case 'editPage':
		$_POST['content'] = html_purify($_POST['content']);
		$page = Page::getById( (int)$_POST['id'] );
		$page->storeFormValues( $_POST );
		$page->update();
		break;
	case 'editCategory':
		$info = '';
		$_POST['name'] = htmlspecialchars($_POST['name']);
		$category = new Category;
		$exist = $category->isCategoryExist( $_POST['name'] );
		if($exist){
			echo 'Category name already exist ';
			$_POST['description'] = html_purify($_POST['description']);
			$_POST['meta_description'] = html_purify($_POST['meta_description']);
			$_POST['slug'] = esc_slug($_POST['slug']);
			$category = Category::getById( (int)$_POST['id'] );
			$category->storeFormValues( $_POST );
			$category->update();
		} else { //Update category name
			$_POST['description'] = html_purify($_POST['description']);
			$_POST['meta_description'] = html_purify($_POST['meta_description']);
			$_POST['slug'] = esc_slug($_POST['slug']);
			$category = Category::getById( (int)$_POST['id'] );
			$old_name = $category->name;
			$category->storeFormValues( $_POST );
			$category->update();
			echo 'Category updated ';
			//Update all related games
			$data = Category::getListByCategory($category->id, 10000);
			$games = $data['results'];
			$count = 0;
			foreach ($games as $game) {
				$game->category = str_replace($old_name, $_POST['name'], $game->category);
				$game->update_category();
				$count++;
			}
			$info = '&info=Change '.$old_name.' to '.$_POST['name'].', '.$count.' games affected.';
		}
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=updated'.$info);
		}
		break;
	case 'deleteCategory':
		$category = Category::getById( (int)$_GET['id'] );
		$category->delete();
		$data = Category::getListByCategory((int)$_GET['id'], 10000);
		$games = $data['results'];
		foreach ($games as $game) {
			$game->delete();
		}
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=deleted');
		}
		break;
	case 'newCategory':
		$_POST['name'] = htmlspecialchars($_POST['name']);
		$_POST['description'] = html_purify($_POST['description']);
		$_POST['meta_description'] = html_purify($_POST['meta_description']);
		$category = new Category;
		$exist = $category->isCategoryExist( $_POST['name'] );
		if($exist){
		  echo 'Category already exist ';
		} else {
		  $category->storeFormValues( $_POST );
		  $category->insert();
		}
		if(isset($_POST['redirect'])){
			if($exist){
				header('Location: '.$_POST['redirect'].'&status=exist');
			} else {
				header('Location: '.$_POST['redirect'].'&status=added');
			}
		}
		break;
	case 'getCategoryData':
		$data = Category::getById( (int)$_POST['id'] );
		$json = json_encode($data);
		echo $json;
		break;
	case 'newCollection':
		require( dirname(__FILE__).'/../classes/Collection.php' );
		$_POST['name'] = esc_string($_POST['name']);
		$_POST['data'] = preg_replace('/[^0-9,]+/', '', $_POST['data']);
		$collection = new Collection;
		$exist = $collection->isCollectionExist( $_POST['name'] );
		if($exist){
		  echo 'Collection already exist ';
		} else {
		  $collection->storeFormValues( $_POST );
		  $collection->insert();
		}
		if(isset($_POST['redirect'])){
			if($exist){
				header('Location: '.$_POST['redirect'].'&status=exist');
			} else {
				header('Location: '.$_POST['redirect'].'&status=added');
			}
		}
		break;
	case 'editCollection':
		require( dirname(__FILE__).'/../classes/Collection.php' );
		$_POST['name'] = esc_string($_POST['name']);
		$_POST['data'] = preg_replace('/[^0-9,]+/', '', $_POST['data']);
		$collection = new Collection;
		$collection->storeFormValues( $_POST );
		$collection->update();
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=updated');
		}
		break;
	case 'deleteCollection':
		require( dirname(__FILE__).'/../classes/Collection.php' );
		$collection = Collection::getById( (int)$_GET['id'] );
		$collection->delete();
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=deleted');
		}
		break;
	case 'getCollectionData':
		require( dirname(__FILE__).'/../classes/Collection.php' );
		$data = Collection::getById( (int)$_POST['id'] );
		$json = json_encode($data);
		echo $json;
		break;
	case 'addGame':
		add_game();
		break;
	case 'updateLogo':
		upload_logo();
		break;
	case 'updateIcon':
		upload_icon();
		break;
	case 'updateStyle':
		update_style();
		break;
	case 'updateTheme':
		update_theme();
		break;
	case 'updateLayout':
		update_layout();
		break;
	case 'updateLanguage':
		update_settings('language', $_POST['language']);
		if(isset($_POST['redirect'])){
			header('Location: '.$_POST['redirect'].'&status=saved');
		}
		break;
	case 'siteSettings':
		site_settings();
		break;
	case 'userSettings':
		user_settings();
		break;
	case 'set_save_thumbs':
		set_advanced_setting('set_save_thumbs');
		break;
	case 'set_small_thumb':
		set_advanced_setting('set_small_thumb');
		break;
	case 'set_protocol':
		set_advanced_setting('set_protocol');
		break;
	case 'set_prettyurl':
		set_advanced_setting('set_prettyurl');
		break;
	case 'set_custom_slug':
		set_advanced_setting('set_custom_slug');
		break;
	case 'updatePurchaseCode':
		update_purchase_code();
		break;
	case 'updater':
		updater2();
		break;
	case 'pluginAction':
		plugin_action();
		break;
	default:
		exit;
	}

function add_game(){
	$ref = '';
	if(isset($_POST['ref'])) $ref = $_POST['ref'];
	$_POST['description'] = html_purify($_POST['description']);
	$_POST['instructions'] = html_purify($_POST['instructions']);
	$redirect = 0;
	if(isset($_POST['redirect'])){
		$redirect = $_POST['redirect'];
	}
	if(isset($_POST['slug'])){
		$slug = esc_slug($_POST['slug']);
	} else {
		$slug = esc_slug(strtolower(str_replace(' ', '-', basename($_POST["title"]))));
	}
	$_POST['slug'] = $slug;
	$game = new Game;
	$check=$game->getBySlug($slug);
	if(is_null($check)){
		if($ref != 'upload'){
			if(IMPORT_THUMB){
				import_thumb($_POST['thumb_2'], $slug);
				$name = basename($_POST['thumb_2']);
				$_POST['thumb_2'] = '/thumbs/'.$slug.'-'.$name;
				//
				import_thumb($_POST['thumb_1'], $slug);
				$name = basename($_POST['thumb_1']);
				$_POST['thumb_1'] = '/thumbs/'.$slug.'-'.$name;
				if( SMALL_THUMB ){
					$output = pathinfo($_POST['thumb_2']);
					$_POST['thumb_small'] = '/thumbs/'.$slug.'-'.$output['filename'].'_small.'.$output['extension'];
					imgResize('..'.$_POST['thumb_2'], 160, 160, $slug);
				}
			}
		}
		$game->storeFormValues( $_POST );
		$game->insert();
		$status='added';
		//
		$cats = commas_to_array($_POST['category']);
		if(is_array($cats)){ //Add new category if not exist
			$length = count($cats);
			for($i = 0; $i < $length; $i++){
				$_POST['name'] = $cats[$i];
				$category = new Category;
				$exist = $category->isCategoryExist($_POST['name']);
				if($exist){
				  //
				} else {
					unset($_POST['slug']);
					$_POST['description'] = '';
					$category->storeFormValues( $_POST );
					$category->insert();
				}
				$category->addToCategory($game->id, $category->id);
			}
		}
	}
	else{
		$status='exist';
	}
	if(isset($_POST['source'])) {
		echo $status;
		echo ' - '.$_POST['title'];
	}
	if($redirect){
		header('Location: '.$redirect.'&status='.$status);
	}
}
function upload_logo(){
	$redirect = 0;
	if(isset($_POST['redirect'])){
		$redirect = $_POST['redirect'];
	}
	$target_dir = "../images/";
	$file_name = strtolower(str_replace(' ', '-', basename($_FILES["logofile"]["name"])));
	$target_file = $target_dir . $file_name;
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if(isset($_POST["submit"])) {
	  $check = getimagesize($_FILES["logofile"]["tmp_name"]);
	  if($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	  } else {
		echo "File is not an image.";
		$uploadOk = 0;
	  }
	}
	if ($_FILES["logofile"]["size"] > 500000) {
	  echo "Sorry, your file is too large.";
	  $uploadOk = 0;
	}
	if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg"
	&& $fileType != "gif" ) {
	  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	  $uploadOk = 0;
	}
	if ($uploadOk == 0) {
	  echo "Sorry, your file was not uploaded.";
	} else {
	  if (move_uploaded_file($_FILES["logofile"]["tmp_name"], $target_file)) {
		//echo "The file ". basename( $_FILES["logofile"]["name"]). " has been uploaded.";
		update_settings('site_logo', 'images/'.$file_name);
	  } else {
		echo "Sorry, there was an error uploading your file.";
	  }
	}
	if($redirect){
		header('Location: '.$redirect);
	}
}
function upload_icon(){
	$target_file = ABSPATH . 'favicon.ico';
	$uploadOk = 1;
	$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	if(isset($_POST["submit"])) {
	  $check = getimagesize($_FILES["iconfile"]["tmp_name"]);
	  if($check !== false) {
		echo "File is an image - " . $check["mime"] . ".";
		$uploadOk = 1;
	  } else {
		echo "File is not an image.";
		$uploadOk = 0;
	  }
	}
	if ($_FILES["iconfile"]["size"] > 500000) {
	  echo "Sorry, your file is too large.";
	  $uploadOk = 0;
	}
	if($fileType != "ico" ) {
	  echo "Sorry, only ICO files are allowed.";
	  $uploadOk = 0;
	}
	if ($uploadOk == 0) {
	  echo "Sorry, your file was not uploaded.";
	} else {
	  if (move_uploaded_file($_FILES["iconfile"]["tmp_name"], $target_file)) {
		//
	  } else {
		echo "Sorry, there was an error uploading your file.";
	  }
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function update_style(){
	file_put_contents('../'. TEMPLATE_PATH . '/style/style.css', $_POST['style']);
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function update_layout(){
	foreach ($_POST as $item => $value) {
		if(substr($item, -3) == 'php'){
			$path = str_replace("_",".",$item);
			file_put_contents('../'. TEMPLATE_PATH . '/'.$path, $value);
		}
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function update_theme(){
	update_settings('theme_name', htmlspecialchars($_POST['theme']));
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function update_settings($name, $value){
	$conn = open_connection();
	$sql = "UPDATE options SET value = :value WHERE name = :name";
	$st = $conn->prepare($sql);
	$st->bindValue(":name", $name, PDO::PARAM_STR);
	$st->bindValue(":value", $value, PDO::PARAM_STR);
	$st->execute();
}
function site_settings(){
	update_settings('site_title', htmlspecialchars($_POST['title']));
	update_settings('site_description', htmlspecialchars($_POST['description']));
	update_settings('meta_description', htmlspecialchars($_POST['meta_description']));
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function user_settings(){
	update_settings('comments', (isset($_POST['comments']) ? 'true' : 'false'));
	update_settings('upload_avatar', (isset($_POST['upload_avatar']) ? 'true' : 'false'));
	update_settings('user_register', (isset($_POST['user_register']) ? 'true' : 'false'));
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function set_advanced_setting($type){
	$bool = 'false';
	if($type == 'set_save_thumbs'){
		if(IMPORT_THUMB){
			$bool = 'true';
		}
		$val = 'false';
		if(isset($_POST['value'])){
			$val = 'true';
		}
		update_settings('import_thumb', $val);
	} elseif($type == 'set_small_thumb'){
		if(SMALL_THUMB){
			$bool = 'true';
		}
		$val = 'false';
		if(isset($_POST['value'])){
			$val = 'true';
		}
		update_settings('small_thumb', $val);
	} elseif($type == 'set_protocol'){
		$val = 'http://';
		if(isset($_POST['value'])){
			$val = 'https://';
		}
		update_settings('url_protocol', $val);
	} elseif($type == 'set_prettyurl'){
		if(PRETTY_URL){
			$bool = 'true';
		}
		$val = 'false';
		if(isset($_POST['value'])){
			$val = 'true';
		}
		update_settings('pretty_url', $val);
	} elseif($type == 'set_custom_slug'){
		if(CUSTOM_SLUG){
			$bool = 'true';
		}
		$val = 'false';
		if(isset($_POST['value'])){
			$val = 'true';
		}
		update_settings('custom_slug', $val);
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function update_purchase_code(){
	$info = '';
	$status = 'saved';
	$ch = curl_init('https://api.cloudarcade.net/verify/verify.php?code='.$_POST['code'].'&ref='.DOMAIN.'&v='.VERSION.'&action=update_code&validate');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$curl = curl_exec($ch);
	curl_close($ch);
	if($curl == 'valid'){
		$conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = 'SELECT * FROM options WHERE name = "purchase_code"';
		$st = $conn->prepare( $sql );
		$st->execute();
		$row = $st->fetch();
		if($row){
			$conn = null;
			update_settings('purchase_code', $_POST['code']);
		} else {
			$sql = "INSERT INTO `options` (`name`, `value`) VALUES ('purchase_code', :code)";
			$st = $conn->prepare( $sql );
			$st->bindValue(":code", $_POST['code'], PDO::PARAM_STR);
			$st->execute();
			$conn = null;
		}
	} else {
		$status = 'error';
		$info = '&info=Error! Item Purchase code not valid!';
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status='.$status.$info);
	}
}
function set_save_thumbs(){
	$bool = 'false';
	if(IMPORT_THUMB){
		$bool = 'true';
	}
	$val = 'false';
	if(isset($_POST['value'])){
		$val = 'true';
	}
	update_settings('import_thumb', $val);
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status=saved');
	}
}
function upload_thumb($url){
	if($url) {
		$data = file_get_contents($url);
		$name = basename($url);
		$new = '../thumbs/'.$name;
		file_put_contents($new, $data);
	}
}
function import_thumb($url, $game_slug){
	if($url) {
		if (!file_exists('../thumbs')) {
			mkdir('../thumbs', 0777, true);
		}
		$name = basename($url);
		$new = '../thumbs/'.$game_slug.'-'.$name;
		compressImage($url, $new , COMPRESSION_LEVEL);
	}
}
function compressImage($source, $destination, $quality) {
	$info = getimagesize($source);
	if ($info['mime'] == 'image/jpeg') 
	$image = imagecreatefromjpeg($source);
	elseif ($info['mime'] == 'image/gif') 
	$image = imagecreatefromgif($source);
	elseif ($info['mime'] == 'image/png') 
	$image = imagecreatefrompng($source);

	if ($info['mime'] == 'image/png'){
		imageAlphaBlending($image, true);
		imageSaveAlpha($image, true);
		imagepng($image, $destination, 9);
	} else {
		imagejpeg($image, $destination, $quality);
	}
}
function updater2(){
	$status = 'null';
	$info_data = '';
	$code = esc_string($_POST['code']);
	if(true){
		$ch = curl_init('https://api.cloudarcade.net/verify/verify.php?code='.$code.'&ref='.DOMAIN.'&action=update&v='.VERSION);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl = curl_exec($ch);
		$data = json_decode($curl, true);
		if(isset($data['log'])){
			if (!file_exists(ABSPATH.'admin/backups')) {
				mkdir(ABSPATH.'admin/backups', 0755, true);
			}
			$ignored = ['backups', 'games', 'thumbs', 'vendor'];
			add_to_zip( '../', 'backups/'.$_SESSION['username'].'-cloudarcade-backup-part-'.VERSION.'-'.time().'.zip', $ignored );
			if(isset($data['content'])){
				$path = $data['path'];
				file_put_contents("rf_execute.php", htmlspecialchars_decode($data['content']));
				include 'rf_execute.php';
				unlink('rf_execute.php');
			}
			$status = 'updated';
		} elseif(isset($data['error'])) {
			$status = 'error';
			$info_data = $data['description'];
		} else {
			$status = 'error';
			$info_data = json_encode($data);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status='.$status.'&info='.$info_data);
	}
}
function plugin_action(){
	$status = '';
	$info = '';
	if(isset($_POST['plugin_action'])){
		if(isset($_POST['name'])){
			$target_dir = ABSPATH . 'content/plugins/' . $_POST['name'];
			if(is_dir( $target_dir )){
				if($_POST['plugin_action'] == 'activate'){
					rename($target_dir, ABSPATH . 'content/plugins/' . substr($_POST['name'], 1));
					$status = 'success';
					$info = 'Plugin activated!';
				} else if($_POST['plugin_action'] == 'deactivate'){
					rename($target_dir, ABSPATH . 'content/plugins/' . '_' . $_POST['name']);
					$status = 'warning';
					$info = 'Plugin deactivated!';
				} else if($_POST['plugin_action'] == 'remove'){
					delete_files($target_dir);
					rmdir($target_dir);
					$status = 'warning';
					$info = 'Plugin removed!';
				}
			}
		}
		if(isset($_POST['url']) && $_POST['plugin_action'] == 'add_plugin'){
			if(isset($_POST['reqversion']) && esc_int(VERSION) >= esc_int($_POST['reqversion'])){
				$target = ABSPATH.'content/plugins/tmp_plugin.zip';
				file_put_contents($target, file_get_contents($_POST['url']));
				if(file_exists($target)){
					$zip = new ZipArchive;
					$res = $zip->open($target);
					if ($res === TRUE) {
						$zip->extractTo(ABSPATH.'content/plugins/');
						$zip->close();
						$status = 'success';
						$info = 'Plugin installed!';
					} else {
					  echo 'doh!';
					}
					unlink($target);
				} else {
					echo 'not found';
				}
			} else {
				$status = 'error';
				$info = 'Failed to install!. You\'re using CA v'.VERSION.' and this plugin require CA v'.$_POST['reqversion'];
			}
		}
		if($_POST['plugin_action'] == 'upload_plugin'){
			$status = 'error';
			if(isset($_FILES['plugin_file'])){ //Upload plugin
				$target_file = ABSPATH . 'content/plugins/' . strtolower(str_replace(' ', '-', basename($_FILES["plugin_file"]["name"])));
				$fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
				$uploadOk = 1;
				if($fileType !== 'zip'){
					$uploadOk = 0;
				}
				if ($uploadOk) {
					if (move_uploaded_file($_FILES["plugin_file"]["tmp_name"], $target_file)) {
						//
					} else {
						$uploadOk = 0;
					}
				}
				if ($uploadOk) {
					$zip = new ZipArchive;
					$res = $zip->open($target_file);
					if ($res === TRUE) {
						$zip->extractTo(ABSPATH . 'content/plugins/');
						$zip->close();
						$status = 'success';
						$info = 'Plugin uploaded!';
					} else {
						$uploadOk = 0;
					}
					unlink($target_file);
				}
			}
		}
	}
	if(isset($_POST['redirect'])){
		header('Location: '.$_POST['redirect'].'&status='.$status.'&info='.$info);
	}
}
?>