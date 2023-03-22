<form id="form-newpage" method="post">
	<input type="hidden" name="pageId" value="<?php echo esc_int($results['page']->id) ?>"/>
	<div class="form-group">
		<label for="title"><?php _e('Page Title') ?>:</label>
		<input type="text" class="form-control" id="newpagetitle" name="title" placeholder="Name of the page" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['page']->title )?>"/>
	</div>
	<div class="form-group">
		<label for="slug"><?php _e('Page Slug') ?>:</label>
		<input type="text" class="form-control" id="newpageslug" name="slug" placeholder="Page url ex: this-is-sample-page" required autofocus maxlength="255" value="<?php echo htmlspecialchars( $results['page']->slug )?>"/>
	</div>
	<div class="form-group">
		<label for="content"><?php _e('Content') ?>:</label>
		<textarea class="form-control" name="content" rows="12" placeholder="The HTML content of the page" required maxlength="100000"><?php echo htmlspecialchars( $results['page']->content )?></textarea>
	</div>
	<div class="form-group">
		<label for="title"><?php _e('Created Date') ?>:</label>
		<input type="date" class="form-control" name="createdDate" placeholder="YYYY-MM-DD" required maxlength="10" value="<?php echo esc_string($results['page']->createdDate) ? date( "Y-m-d", $results['page']->createdDate ) : "" ?>" />
	</div>
	<input type="submit" class="btn btn-primary"  name="saveChanges" value="<?php _e('Publish') ?>" />
</form>