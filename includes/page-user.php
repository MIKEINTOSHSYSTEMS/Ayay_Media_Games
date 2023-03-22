<?php

if(isset($_GET['slug']) && $_GET['slug'] != ''){
	$_GET['slug'] = htmlspecialchars($_GET['slug']);
} else {
	header( "Location: /" );
	return;
}

if(file_exists(ABSPATH.'includes/rank.json')){
	$rank = json_decode(file_get_contents(ABSPATH.'includes/rank.json'), true);
	$rank_values = array_values($rank);
}

$page_title = $_GET['slug'];
$meta_description = SITE_DESCRIPTION;

require_once( TEMPLATE_PATH . '/functions.php' );

if(file_exists(TEMPLATE_PATH.'/user.php')){
	require(TEMPLATE_PATH.'/user.php');
	return;
}

//Start page

require( TEMPLATE_PATH.'/includes/header.php' );

$is_visitor = true;
$cur_user = null;

if($login_user && $login_user->username === $_GET['slug']){
	$is_visitor = false;
	$cur_user = $login_user;
} else {
	$cur_user = User::getByUsername(strtolower($_GET['slug']));
}

if(true){
	if(isset($_GET['edit']) && !$is_visitor){
		//Edit user profile
		?>
		<div class="user-page">
			<div class="container">
				<h3 class="single-title">Edit Profile</h3>
				<?php
					if(isset($_GET['status'])){
						$class = 'alert-success';
						$message = '';
						if($_GET['status'] == 'saved'){
							$message = 'Profile updated!';
							if(isset($_GET['info']) && $_GET['info'] != ''){
								$message = $_GET['info'];
							}
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
				<div class="row">
					<div class="col-md-8">
				<div class="section">
					<form id="form-settings" action="<?php echo DOMAIN.'includes/user.php' ?>" method="post">
						<input type="hidden" name="action" value="edit_profile">
						<input type="hidden" name="redirect" value="<?php echo get_permalink('user', $login_user->username) ?>&edit">
						<div class="form-group row">
							<label for="email" class="col-sm-2 col-form-label"><?php _e('Email') ?>:</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="email" minlength="4" value="<?php echo $login_user->email ?>">
							</div>
						</div>
						<div class="form-group row">
							<label for="birth_date" class="col-sm-2 col-form-label"><?php _e('Birth date') ?>:</label>
							<div class="col-sm-10">
								<input type="date" class="form-control" name="birth_date" value="<?php echo $login_user->birth_date ?>" required>
							</div>
						</div>
						<div class="form-group row">
							<label for="bio" class="col-sm-2 col-form-label"><?php _e('About me') ?>:</label>
							<div class="col-sm-10">
								<textarea class="form-control" name="bio" rows="3"><?php echo $login_user->bio ?></textarea>
							</div>
						</div>
						<div class="form-group row">
							<label for="gender" class="col-sm-2 col-form-label"><?php _e('Gender') ?>:</label>
							<div class="col-sm-10">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="gender" id="gender1" value="male">
									<label class="form-check-label" for="gender1">
										<?php _e('Male') ?>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="gender" id="gender2" value="female">
									<label class="form-check-label" for="gender2">
										<?php _e('Female') ?>
									</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="gender" id="gender3" value="unset" checked>
									<label class="form-check-label" for="gender3">
										<?php _e('Unset') ?>
									</label>
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-primary btn-md"><?php _e('Update') ?></button>
					</form>
				</div>
				</div>
				<div class="col-md-4">
					<?php if($options['upload_avatar'] === 'true'){ ?>
						<div class="section">
							<h3 class="section-title"><?php _e('Upload Avatar') ?></h3>
							<form action="<?php echo DOMAIN.'includes/user.php' ?>" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<input type="hidden" name="action" value="upload_avatar">
									<input type="hidden" name="redirect" value="<?php echo get_permalink('user', $login_user->username) ?>&edit">
									<label for="file"><?php _e('Supported format') ?>: png, jpg, jpeg (Max 500kb)</label><br>
									<input type="file" class="form-control" name="avatar" accept=".png,.jpg,.jpeg"/><br>
									<button type="submit" class="btn btn-primary btn-md"><?php _e('Upload') ?></button>
								</div>
							</form>
						</div>
					<?php } ?>
					<div class="section">
						<h3 class="section-title"><?php _e('Choose Avatar') ?></h3>
						<form action="<?php echo DOMAIN.'includes/user.php' ?>" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<input type="hidden" name="action" value="choose_avatar">
								<input type="hidden" name="redirect" value="<?php echo get_permalink('user', $login_user->username) ?>&edit">
								<div class="row avatar-chooser">
								<?php
									if(file_exists(ABSPATH.'images/avatar/default/')){
										$avatars = scan_files('images/avatar/default/');
										foreach ($avatars as $avatar) {
											if(substr($avatar, -4) === '.png'){
												$name = basename($avatar, '.png');
												?>
												<div class="col-3">
														<input type="radio" class="input-hidden" id="avatar-<?php echo $name ?>" name="avatar" value="<?php echo $name ?>" />
														<label for="avatar-<?php echo $name ?>">
															<img src="<?php echo DOMAIN.$avatar ?>">
														</label>
												</div>
												<?php
											}
										}
									}
								?>
								</div>
								<br>
								<button type="submit" class="btn btn-primary btn-md"><?php _e('Change avatar') ?></button>
							</div>
						</form>
					</div>
					<div class="section">
						<h3 class="section-title"><?php _e('Change password') ?></h3>
						<form action="<?php echo DOMAIN.'includes/user.php' ?>" method="post" enctype="multipart/form-data">
							<div class="form-group">
								<input type="hidden" name="action" value="change_password">
								<input type="hidden" name="redirect" value="<?php echo get_permalink('user', $login_user->username) ?>&edit">
								<div class="form-group">
									<label><?php _e('Current password') ?>:</label>
									<input type="password" class="form-control" name="cur_password" minlength="6" value="" required>
								</div>
								<div class="form-group">
									<label><?php _e('New password') ?>:</label>
									<input type="password" class="form-control" name="new_password" minlength="6" value="" required>
								</div>
								<button type="submit" class="btn btn-primary btn-md"><?php _e('Update') ?></button>
							</div>
						</form>
					</div>
				</div>
				</div>
			</div>
		</div>
		<?php
	} else {
		//User profile page
		$exceeded_value = $rank_values[$cur_user->level-1];
		$max_value = 0;
		$min_value = $cur_user->xp-$exceeded_value;
		if($cur_user->level < count($rank)){
			$max_value = $rank_values[$cur_user->level]-$exceeded_value;
		} else {
			$max_value = 100;
			$min_value = 100;
		}
		$percentage_rank_progress = (100/($max_value))*$min_value;
		?>
		<div class="user-page">
			<div class="container">
				<h3 class="single-title"><?php _e('User Profile') ?></h3>
				<div class="row">
					<div class="col-md-4">
						<div class="section">
							<div class="text-center">
								<br>
								<div class="profile-photo">
									<img src="<?php echo get_user_avatar($cur_user->username) ?>">
								</div>
								<div class="profile-username">
									<?php echo $cur_user->username ?>
								</div>
								<div>
									<?php echo $cur_user->gender ?>
								</div>
								<div class="profile-join">
									<?php _e('Joined %a', $cur_user->join_date) ?>
								</div>
								<div class="profile-bio text-secondary">
									"<?php echo $cur_user->bio ?>"
								</div>
								<br>
							</div>
						</div>
					</div>
					<div class="col-md-8">
						<div class="section">
							<h3 class="section-title"><?php _e('Level') ?></h3>
							<img src="<?php echo DOMAIN.'images/ranks/level-'.$cur_user->level.'.png' ?>" class="level-badge">
							<strong><?php echo $cur_user->rank ?> (Lv.<?php echo $cur_user->level ?>)</strong>
							<p class="text-secondary"><?php _e('This player have exceeded %a xp', $rank[$cur_user->rank]) ?></p>
							<div class="progress">
								<div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $cur_user->xp ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $percentage_rank_progress ?>%">
									<span class="sr-only"><?php echo $percentage_rank_progress ?>% <?php _e('Complete') ?></span>
								</div>
							</div>
						</div>
						<?php if(!$is_visitor){ ?>
						<div class="section">
							<h3 class="section-title"><?php _e('Liked Games') ?></h3>
							<div class="profile-gamelist">

							<?php

							if($cur_user){
								if(isset($cur_user->data['likes']) && count($cur_user->data['likes']) > 0){
									?>

									<button class="btn btn-left" id="btn_prev">
										<i class="fa fa-chevron-left" aria-hidden="true"></i>
									</button>
									<button class="btn btn-right" id="btn_next">
										<i class="fa fa-chevron-right" aria-hidden="true"></i>
									</button>
									<ul>

									<?php
									$data = $cur_user->data['likes'];
									$games = [];
									foreach ($data as $id) {
										$game = new Game;
										$res = $game->getById($id);
										if($res){
											$games[] = $res;
										}
									}
									foreach ($games as $game) {
										?>
											<li><div class="profile-game-item">
											<a href="<?php echo get_permalink('game', $game->slug) ?>">
												<div class="list-thumbnail"><img src="<?php echo get_small_thumb($game) ?>" class="small-thumb" alt="<?php echo esc_string($game->title) ?>"></div>
											</a>
										</div></li>
										
										<?php
									}
									?>

									</ul>

									<?php
								} else {
									echo('<p class="text-secondary">No record!</p>');
								}
							}	

							?>
						</div>
						</div>
						<div class="section">
							<h3 class="section-title"><?php _e('Comments') ?></h3>
							<div class="profile-comments">
								<?php
								$sql = 'SELECT * FROM comments WHERE sender_id = :sender_id ORDER BY parent_id asc, id asc';
								$st = $conn->prepare($sql);
								$st->bindValue(":sender_id", $cur_user->id, PDO::PARAM_INT);
								$st->execute();
								$row = $st->fetchAll(PDO::FETCH_ASSOC);

								if(count($row)){
									foreach ($row as $item) {
										?>
										<div class="profile-comment-item id-<?php echo $item['id'] ?>">
											<div class="comment-text">
												"<?php echo $item['comment'] ?>"
											</div>
											<div class="comment-date text-secondary">
												<?php echo $item['created_date'] ?> (Game id <?php echo $item['game_id'] ?>)
											</div>
											<div class="text-danger delete-comment" data-id="<?php echo $item['id'] ?>">
												<?php _e('Delete') ?>
											</div>
										</div>
										<?php
									}
								} else {
									echo('<p class="text-secondary">'._t('No record!').'</p>');
								}
								?>
							</div>
						</div>
					<?php } ?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

require( TEMPLATE_PATH.'/includes/footer.php' );

//End page

?>