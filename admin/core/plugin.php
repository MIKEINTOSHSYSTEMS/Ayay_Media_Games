<?php

if(ADMIN_DEMO){
	echo('Restricted for "DEMO" mode.');
	return;
}

if(isset($_GET['name'])){
	$_GET['name'] = esc_slug($_GET['name']);
	if(is_plugin_exist($_GET['name'])){
		$plugin = get_plugin_info($_GET['name']);
		echo('<h4 class="plugin-title">'.$plugin['name'].'</h4>');
		require_once($plugin['path'] . '/page.php');
	} else {
		echo('<div class="section">');
		_e('Plugin %a is missing or removed.', $_GET['name']);
		echo('</div>');
	}
} else {
	if(isset($_GET['status'])){
		if($_GET['status'] == 'success'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
			echo isset($_GET['info']) ? $_GET['info'] : 'Plugin successfully installed!';
			echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		} elseif($_GET['status'] == 'warning'){
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">';
			echo isset($_GET['info']) ? $_GET['info'] : 'Failed to install!';
			echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		} elseif($_GET['status'] == 'error'){
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
			echo isset($_GET['info']) ? $_GET['info'] : 'Error!';
			echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
	}

	?>
	<div class="row">
		<div class="col-lg-8">
			<div class="section">
				<?php

				if(count($plugin_list) > 0){ ?>
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>#</th>
									<th><?php _e('Plugin') ?></th>
									<th><?php _e('Description') ?></th>
									<th><?php _e('Action') ?></th>
								</tr>
							</thead>
							<tbody>
								<?php
								
								$index = 0;
								foreach ($plugin_list as $plugin) {
									$index++;
									$is_active = substr($plugin['dir_name'], 0, 1) == '_' ? false : true;

									?>
									<tr>
										<th scope="row"><?php echo $index ?></th>
										<td>
											<strong><?php echo $plugin['name'] ?></strong>
											<br>
											Version <?php echo $plugin['version'] ?> | By <a href="<?php echo $plugin['website'] ?>" target="_blank"><?php echo $plugin['author'] ?></a>
										</td>
										<td><?php echo $plugin['description'] ?></td>
										<td><?php if($is_active) {
											echo('<a href="#" id="'.$plugin['dir_name'].'" class="deactivate-plugin">'._t('Deactivate').'</a>');
										} else {
											echo('<a href="#" id="'.$plugin['dir_name'].'" class="activate-plugin">'._t('Activate').'</a>');
										} ?> | <a href="#" id="<?php echo $plugin['dir_name'] ?>" class="remove-plugin text-danger"><?php _e('Remove') ?></a></td>
									</tr>
									<?php
								}

								?>
							</tbody>
						</table>
					</div>
				<?php } else {
					_e('No plugins installed!');
				} ?>
					
			</div>
		</div>
		<div class="col-lg-4">
			<div class="section">
				<?php _e('Add new plugin') ?><br><br>
				<form id="form-upload-plugin" action="request.php" method="post" enctype="multipart/form-data">
					<div class="form-group">
						<input type="hidden" name="action" value="pluginAction">
						<input type="hidden" name="plugin_action" value="upload_plugin">
						<input type="hidden" name="redirect" value="dashboard.php?viewpage=plugin">
						<label for="plugin_file"><?php _e('Upload plugin') ?> (zip):</label><br>
						<input type="file" class="form-control" name="plugin_file" accept=".zip"/><br>
						<button type="submit" class="btn btn-primary btn-md"><?php _e('Upload') ?></button>
					</div>
				</form>
				<div class="plugin-repository-wrapper">
					<button type="submit" class="load-plugin-repo btn btn-primary btn-md"><?php _e('Load plugin repository') ?></button>
					<div class="plugin-repo-container"></div>
				</div>
			</div>
		</div>
	</div>
	<input type="hidden" name="p_code" value="<?php echo (ADMIN_DEMO ? 'holy-moly' : check_purchase_code()) ?>" id="p_code" />
	<?php
}

?>
	