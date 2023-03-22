<?php include  TEMPLATE_PATH . "/includes/header.php" ?>
<div class="container">
	<div class="game-container">
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
		<div class="content-wrapper">
		<div class="row">
			<div class="col-md-9 game-content">
				<div class="game-iframe-container">
					<iframe class="game-iframe" id="game-area" src="<?php echo get_game_url($game); ?>" width="<?php echo esc_int($game->width); ?>" height="<?php echo esc_int($game->height); ?>" scrolling="none" frameborder="0" allowfullscreen></iframe>
				</div>
				<div class="single-info-container">
					<div class="header-left">
						<h1 class="single-title"><?php echo htmlspecialchars( $game->title )?></h1>
						<p><?php _e('Played %a times.', esc_int($game->views)) ?></p>
					</div>
					<div class="header-right">
						<div class="stats-vote">
							<?php
								$vote_percentage = '- ';
								if($game->upvote+$game->downvote > 0){
									$vote_percentage = floor(($game->upvote/($game->upvote+$game->downvote))*100);
								}
							?>
							<div class="txt-stats"><b class="text-success"><?php echo $vote_percentage ?>%</b> (<?php echo $game->upvote ?>/<?php echo $game->upvote+$game->downvote ?>)</div>
							<i class="icon-vote fa fa-thumbs-up" id="upvote" data-id="<?php echo $game->id ?>"></i>
							<i class="icon-vote fa fa-thumbs-down" id="downvote" data-id="<?php echo $game->id ?>"></i>
							<div class="vote-status"></div>
						</div>
					</div>
					<div class="action-btn">
						<div class="single-icon"><i class="fa fa-external-link-square" aria-hidden="true"></i><a href="<?php echo esc_url($game->url); ?>" target="_blank"><?php _e('Open in new window') ?></a></div>
						<div class="single-icon"><i class="fa fa-expand" aria-hidden="true"></i><a href="#" onclick="open_fullscreen()"><?php _e('Fullscreen') ?></a></div>
						<div class="social-share"><a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo htmlspecialchars(get_cur_url()); ?>" target="_blank">
							<img src="<?php echo DOMAIN . TEMPLATE_PATH . '/images/facebook.png' ?>" alt="share" class="social-icon">
						</a></div>
						<div class="social-share"><a href="https://twitter.com/intent/tweet?url=<?php echo htmlspecialchars(get_cur_url()); ?>" target="_blank">
							<img src="<?php echo DOMAIN . TEMPLATE_PATH . '/images/twitter.png' ?>" alt="share" class="social-icon">
						</a></div>
					</div>
				</div>
				<b><?php _e('Description') ?>:</b>
				<div class="single-description">
					<?php echo nl2br( $game->description )?>
				</div>
				<br>
				<b><?php _e('Instructions') ?>:</b>
				<div class="single-instructions">
					<?php echo nl2br( $game->instructions )?>
				</div>
				<br>
				<b><?php _e('Categories') ?>:</b>
				<p class="cat-list"> 
					<?php if ( $game->category ) {
						$categories = commas_to_array($game->category);
						foreach ($categories as $cat) {
							$category = Category::getByName($cat); ?>
					<a href="<?php echo get_permalink('category', $category->slug) ?>" class="cat-link"><?php echo esc_string($category->name) ?></a>
					<?php
						}
						} ?>
				</p>
				<?php if($options['comments'] === 'true'){
					?>
					<div class="comments-container">
						<div id="comments">
							
						</div>
					</div>
					<?php
				} ?>
				<div class="single-comments">
				</div>
			</div>
			<div class="col-md-3">
				<?php include  TEMPLATE_PATH . "/parts/sidebar.php" ?>
			</div>
		</div>
	</div>
	<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
	</div>
	<div class="bottom-container">
		<h3 class="item-title"><i class="fa fa-thumbs-up" aria-hidden="true"></i><?php _e('SIMILAR GAMES') ?></h3>
		<?php list_games_by_categories($categories, 12) ?>
	</div>
</div>
<?php include  TEMPLATE_PATH . "/includes/footer.php" ?>