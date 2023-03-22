<div class="sidebar">
	<h4 class="widget-title"><i class="fa fa-plus" aria-hidden="true"></i><?php _e('NEW GAMES') ?></h4>
	<div class="widget">
		<?php list_games('new', 9) ?>
	</div>
	<div class="widget">
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-300.php" ?>
	</div>
	<h4 class="widget-title"><i class="fa fa-gamepad" aria-hidden="true"></i><?php _e('RANDOM GAMES') ?></h4>
	<div class="widget">
		<?php list_games('random', 9) ?>
	</div>
</div>