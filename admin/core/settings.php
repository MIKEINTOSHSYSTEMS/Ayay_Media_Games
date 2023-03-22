<?php
	$warning_list = get_admin_warning();
	if(!empty($warning_list)){
		echo('<div class="site-warning">');
		foreach ($warning_list as $val) {
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'.$val.'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
		echo('</div>');
	}
	if(isset($_GET['status'])){
		$class = 'alert-success';
		$message = '';
		if($_GET['status'] == 'saved'){
			$message = 'Settings saved!';
		} elseif($_GET['status'] == 'error'){
			$class = 'alert-danger';
			$message = 'Error!';
			if(isset($_GET['info'])){
				$message = $_GET['info'];
			}
		}
		echo '<div class="alert '.$class.' alert-dismissible fade show" role="alert">'._t($message).'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}
?>
<div class="section">
	<ul class="nav nav-tabs">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#general"><?php _e('General') ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#advanced"><?php _e('Advanced') ?></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#user"><?php _e('User') ?></a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane tab-container active" id="general">
			<form id="form-settings" action="request.php" method="post">
				<input type="hidden" name="action" value="siteSettings">
				<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
				<div class="form-group row">
					<label for="title" class="col-sm-2 col-form-label"><?php _e('Site title') ?>:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="title" minlength="4" value="<?php echo esc_string(SITE_TITLE) ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="description" class="col-sm-2 col-form-label"><?php _e('Site description') ?>:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="description" minlength="4" value="<?php echo esc_string(SITE_DESCRIPTION) ?>" required>
					</div>
				</div>
				<div class="form-group row">
					<label for="meta_description" class="col-sm-2 col-form-label"><?php _e('Meta description') ?>:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="meta_description" minlength="4" value="<?php echo esc_string(META_DESCRIPTION) ?>" required>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-md"><?php _e('Save changes') ?></button>
			</form>
			<br>
			<form id="form-updatelogo" action="request.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<input type="hidden" name="action" value="updateLogo">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<label for="logo"><?php _e('Site logo') ?>:</label><br>
					<img src="<?php echo DOMAIN . SITE_LOGO ?>" style="background-color: #aebfbc; padding: 10px"><br><br>
					<input type="file" name="logofile" accept=".png, .jpg, .jpeg"/><br><br>
					<button type="submit" class="btn btn-primary btn-md"><?php _e('Upload') ?></button>
				</div>
			</form>
			<form id="form-updatelogo" action="request.php" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<input type="hidden" name="action" value="updateIcon">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<label for="icon"><?php _e('Site icon') ?> (.ico file format):</label><br>
					<img src="<?php echo DOMAIN  ?>favicon.ico" style="background-color: #aebfbc; padding: 10px; width: 50px;"><br><br>
					<input type="file" name="iconfile" accept=".ico"/><br><br>
					<button type="submit" class="btn btn-primary btn-md"><?php _e('Upload') ?></button>
				</div>
			</form>
			<form id="form-settings" action="request.php" method="post">
				<input type="hidden" name="action" value="updateLanguage">
				<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
				<div class="form-group row">
					<label for="code" class="col-sm-2 col-form-label"><?php _e('Site language') ?>:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="language" minlength="2" maxlength="3" placeholder="en" value="<?php echo $options['language'] ?>" required>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-md"><?php _e('Save') ?></button>
			</form>
			<form id="form-settings" action="request.php" method="post">
				<input type="hidden" name="action" value="updatePurchaseCode">
				<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
				<div class="form-group row">
					<label for="code" class="col-sm-2 col-form-label"><span class="text-danger">*</span> <?php _e('Item purchase code') ?>:</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" name="code" minlength="5" placeholder="101010-10aa-0101-01010-a1b010a01b10" required>
					</div>
				</div>
				<button type="submit" class="btn btn-primary btn-md"><?php _e('Update') ?></button>
			</form>
		</div>

		<div class="tab-pane tab-container fade" id="advanced">
			<form id="form-advanced" action="request.php" method="post">
				<div class="form-group">
					<input type="hidden" name="action" value="set_save_thumbs">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="value" value="1" <?php if( IMPORT_THUMB ){ echo 'checked'; } ?>>
					<label><?php _e('Save/import thumbnails') ?>:</label><br>
					<p>Save game thumbnails from fetch and remote games to local server. images also compressed and can reduce file size up to 80%.
					<br>Page will be loaded more quickly.</p>
					<button type="submit" class="btn btn-primary btn-md">Save</button>
				</div>
			</form>
			<form id="form-advanced" action="request.php" method="post" class="<?php if( !IMPORT_THUMB ) echo('disabled-list') ?>">
				<div class="form-group">
					<input type="hidden" name="action" value="set_small_thumb">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="value" value="1" <?php if( SMALL_THUMB ){ echo 'checked'; } ?>>
					<label><?php _e('Small thumbnails') ?>:</label><br>
					<p>Generate small thumbnail (160x160 px) from "thumb_2".<br>
					Can be used to replace "thumb_2" for faster page load, since "thumb_2" have 512px size.<br>
					Require active import thumbnails.</p>
					<button type="submit" class="btn btn-primary btn-md">Save</button>
				</div>
			</form>
			<form id="form-advanced" action="request.php" method="post">
				<div class="form-group">
					<input type="hidden" name="action" value="set_protocol">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="value" value="1" <?php if( URL_PROTOCOL == 'https://' ){ echo 'checked'; } ?>>
					<label><?php _e('Use HTTPS') ?>:</label><br>
					<p>If your site running over https, active this.</p>
					<button type="submit" class="btn btn-primary btn-md">Save</button>
				</div>
			</form>
			<form id="form-advanced" action="request.php" method="post">
				<div class="form-group">
					<input type="hidden" name="action" value="set_prettyurl">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="value" value="1" <?php if( PRETTY_URL ){ echo 'checked'; } ?>>
					<label><?php _e('Pretty URL') ?>:</label><br>
					<p>(Recommended) SEO Friendly URL, but only work with Apache web server.</p>
					<button type="submit" class="btn btn-primary btn-md">Save</button>
				</div>
			</form>
			<form id="form-advanced" action="request.php" method="post">
				<div class="form-group">
					<input type="hidden" name="action" value="set_custom_slug">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="value" value="1" <?php if( CUSTOM_SLUG ){ echo 'checked'; } ?>>
					<label><?php _e('Custom slug') ?>:</label><br>
					<p>If you use unicode (Arabic, Russian, Chinese.etc) characters on your game, page and category title, activate this.<br>
					Basically slug are generated automatically with it's title, but it's won't work with non Latin caharacters.</p>
					<button type="submit" class="btn btn-primary btn-md">Save</button>
				</div>
			</form>
			<form id="form-advanced" action="../sitemap.php" method="post">
				<div class="form-group">
					<label><?php _e('Generate sitemap') ?>:</label><br>
					<p>Exclude all page url. only work if Pretty URL enabled.</p>
					<button type="submit" class="btn btn-primary btn-md"><?php _e('Generate sitemap') ?></button>
				</div>
			</form>
		</div>

		<div class="tab-pane tab-container fade" id="user">
			<form id="form-advanced" action="request.php" method="post">
				<input type="hidden" name="action" value="userSettings">
				<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
				<div class="form-group">
					<input type="checkbox" name="user_register" value="1" <?php if( filter_var($options['user_register'], FILTER_VALIDATE_BOOLEAN) ){ echo 'checked'; } ?>>
					<label><?php _e('User/player registration') ?>:</label><br>
				</div>
				<div class="form-group">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="upload_avatar" value="1" <?php if( filter_var($options['upload_avatar'], FILTER_VALIDATE_BOOLEAN) ){ echo 'checked'; } ?>>
					<label><?php _e('Upload Avatar (User)') ?>:</label><br>
				</div>
				<div class="form-group">
					<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=settings">
					<input type="checkbox" name="comments" value="1" <?php if( filter_var($options['comments'], FILTER_VALIDATE_BOOLEAN) ){ echo 'checked'; } ?>>
					<label><?php _e('Comments') ?>:</label><br>
				</div>
				<button type="submit" class="btn btn-primary btn-md"><?php _e('Save') ?></button>
			</form>
		</div>
	</div>
</div>