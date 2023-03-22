<?php

include '../' .TEMPLATE_PATH . '/layout.php';

?>
<?php
	if(isset($_GET['status'])){
		$class = 'alert-success';
		$message = '';
		if($_GET['status'] == 'saved'){
			$message = 'Layout saved!';
		}
		echo '<div class="alert '.$class.' alert-dismissible fade show" role="alert">'._t($message).'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}
?>
<div class="section">
	<form id="form-editlayout" action="request.php" method="post">
		<input type="hidden" name="action" value="updateLayout"/>
		<input type="hidden" name="redirect" value="<?php echo DOMAIN ?>admin/dashboard.php?viewpage=layout"/>
		<?php 
		foreach ($parts as $part) {
			?>
			<div class="form-group">
				<label><?php echo esc_string($part[0]) ?>:</label>
				<p><?php echo esc_string($part[2]) ?></p>
				<textarea class="form-control" name="<?php echo esc_string($part[1]) ?>" rows="3" /><?php
						$filecontent = file_get_contents( '../'. TEMPLATE_PATH . '/'.$part[1]);
						echo htmlspecialchars($filecontent);
					?></textarea>
			</div>
		<?php }
		?>
				
		<button type="submit" class="btn btn-primary btn-md"><?php _e('Save') ?></button>
	</form>
</div>