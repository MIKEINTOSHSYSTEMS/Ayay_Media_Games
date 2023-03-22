<?php

function is_theme_has_thumbail($theme_name){

	$path = ABSPATH . 'content/themes/' . $theme_name;

	if(file_exists( $path . '/thumbnail.png' )){
		return true;
	}
}

$dirs = scan_folder('content/themes/');
foreach ($dirs as $dir) {
	$json_path = ABSPATH . 'content/themes/' . $dir . '/info.json';
	if(file_exists( $json_path )){
		$theme = json_decode(file_get_contents( $json_path ), true);
		$disabled = '';
		$btn_label = _t('Activate');
		$thumb;
		if( THEME_NAME == $dir){
			$disabled = _t('disabled');
			$btn_label = _t('Activated');
		}
		if(is_theme_has_thumbail($dir)){
			$thumb = DOMAIN . 'content/themes/' . $dir . '/thumbnail.png'; 
		} else {
			$thumb = DOMAIN . 'images/theme-no-thumb.png'; 
		} ?>

		<div class="theme">
			<div class="theme-thumbnail">
				<img src="<?php echo $thumb ?>">
			</div>
			<div class="theme-id-container">
				<div class="theme-name"> <?php echo $theme['name'] ?> </div>
				<div class="theme-action">
					<button type="button" class="btn-theme btn btn-primary <?php echo $disabled ?> btn-sm" id="<?php echo $dir ?>"><?php echo $btn_label ?></button>
				</div>
				<div class="theme-info">
					<div class="theme-author"><?php _e('Author') ?>: <a href="<?php echo $theme['website'] ?>" target="_blank"><?php echo $theme['author'] ?></a></div>
					<div class="theme-version">v<?php echo $theme['version'] ?></div>
				</div>
			</div>
		</div>

		<?php
	}
}

?>