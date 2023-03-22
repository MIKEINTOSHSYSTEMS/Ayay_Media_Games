<div class="col-md-2 col-sm-3 col-4 item-grid">
	<a href="<?php echo get_permalink('game', $game->slug) ?>">
	<div class="list-game">
		<div class="list-thumbnail"><img src="<?php echo get_small_thumb($game) ?>" class="small-thumb" alt="<?php echo esc_string($game->title) ?>"></div>
		<div class="list-content">
			<div class="star-rating text-center"><img src="<?php echo DOMAIN . TEMPLATE_PATH . '/images/star-'.get_rating('5', $game).'.png' ?>" alt="rating"></div>
			<div class="list-title"><?php echo esc_string($game->title); ?></div>
		</div>
	</div>
	</a>
</div>