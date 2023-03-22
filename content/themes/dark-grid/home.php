<?php include  TEMPLATE_PATH . "/includes/header.php" ?>
<div class="container">
	<div class="game-container">
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
		<h3 class="item-title"><i class="fa fa-plus" aria-hidden="true"></i><?php _e('NEW GAMES') ?></h3>
		<div class="grid-layout grid-wrapper">
			<?php
			$index = 0;
			$games = get_game_list('new', 30)['results'];
			foreach ( $games as $game ) { $index++; ?>
				<?php include  TEMPLATE_PATH . "/includes/grid-masonry.php" ?>
			<?php } ?>
		</div>
		<h3 class="item-title"><i class="fa fa-certificate" aria-hidden="true"></i><?php _e('POPULAR GAMES') ?></h3>
		<div class="grid-layout grid-wrapper">
			<?php
			$games = get_game_list('popular', 14)['results'];
			foreach ( $games as $game ) { ?>
				<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
			<?php } ?>
		</div>
		<h3 class="item-title"><i class="fa fa-gamepad" aria-hidden="true"></i><?php _e('YOU MAY LIKE') ?></h3>
		<div class="grid-layout grid-wrapper">
			<?php
			$games = get_game_list('random', 14)['results'];
			foreach ( $games as $game ) { ?>
				<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
			<?php } ?>
		</div>
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
	</div>
</div>
<?php include  TEMPLATE_PATH . "/includes/footer.php" ?>