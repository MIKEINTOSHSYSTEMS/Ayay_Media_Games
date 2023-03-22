<?php
	if(isset($_GET['status'])){
		if($_GET['status'] == 'saved'){
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'._t('File saved!').'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
	}
?>
<div class="section">
	<form id="form-editstyle" action="request.php" method="post">
		<input type="hidden" name="action" value="updateStyle"/>
		<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=styleeditor"/>
		<div class="form-group">
			<label for="style">style.css</label>
			<textarea class="form-control" name="style" rows="18" required/><?php
					$filecontent=file_get_contents( '../'. TEMPLATE_PATH . '/style/style.css');
					echo htmlspecialchars($filecontent);
				?></textarea>
		</div>
		<button type="submit" class="btn btn-primary btn-md"><?php _e('Save') ?></button>
	</form>
</div>