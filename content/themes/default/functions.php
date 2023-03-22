<?php

function list_categories(){
	$categories = get_all_categories();
	echo '<ul class="links list-categories">';
	foreach ($categories as $item) {
		echo '<a href="'. get_permalink('category', $item->slug) .'"><li>'. esc_string($item->name) .'</li></a>';
	}
	echo '</ul>';
}
function list_games($type, $amount){
	echo '<div class="row">';
	$data = get_game_list($type, $amount);
	$games = $data['results'];
	foreach ( $games as $game ) { ?>
	<div class="col-4 list-tile">
		<a href="<?php echo get_permalink('game', $game->slug) ?>">
			<div class="list-game">
				<div class="list-thumbnail"><img src="<?php echo get_small_thumb($game) ?>" class="small-thumb" alt="<?php echo esc_string($game->title) ?>"></div>
				<div class="list-content">
					<div class="list-title"><?php echo esc_string($game->title); ?></div>
				</div>
			</div>
		</a>
	</div>
	<?php }
	echo '</div>';
}
function list_games_by_category($cat, $amount){
	echo '<div class="row">';
	$data = get_game_list_category($cat, $amount);
	$games = $data['results'];
	foreach ( $games as $game ) { ?>
		<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
	<?php }
	echo '</div>';
}
function list_games_by_categories($cat, $amount){
	echo '<div class="row">';
	$data = get_game_list_categories($cat, $amount);
	$games = $data['results'];
	foreach ( $games as $game ) { ?>
		<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
	<?php }
	echo '</div>';
}

function show_user_profile_header(){

	global $login_user;

	if($login_user){
	?>
	<div class="user-avatar">
		<img src="<?php echo get_user_avatar() ?>">
	</div>
	<ul class="user-links hidden">
		<li>
			<strong>
				<?php echo $login_user->username ?>
			</strong>
			<div class="label-xp"><?php echo $login_user->xp ?>xp</div>
		</li>
		<hr>
		<a href="<?php echo get_permalink('user', $login_user->username) ?>">
			<li><?php _e('My Profile') ?></li>
		</a>
		<a href="<?php echo get_permalink('user', $login_user->username) ?>&edit">
			<li><?php _e('Edit Profile') ?></li>
		</a>
		<hr>
		<a href="<?php echo DOMAIN ?>admin.php?action=logout">
			<li class="text-danger"><?php _e('Log Out') ?></li>
		</a>
	</ul>
	<?php
	}
}

?>