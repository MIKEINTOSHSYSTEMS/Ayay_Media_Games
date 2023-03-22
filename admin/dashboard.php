<?php
session_start();

$action = isset( $_POST['action'] ) ? $_POST['action'] : "";

require "../config.php";
require "../init.php";

if ( !$login_user ) {
	exit('logout');
}

if(!USER_ADMIN){
	exit('Access forbidden!');
}

load_language('admin');

require( "../includes/plugin.php" );

if(count($plugin_list) > 0){
	// If plugin exist
}

$pages = array (
	array(_t('Dashboard'), 'dashboard', 'home'),
	array(_t('Game list'), 'gamelist', 'gamepad'),
	array(_t('Add game'), 'addgame', 'plus-circle'),
	array(_t('Categories'), 'categories', 'th-large'),
	array(_t('Collections'), 'collections', 'th-list'),
	array(_t('Pages'), 'pages', 'book'),
	array(_t('Settings'), 'settings', 'cog'),
	array(_t('Themes'), 'themes', 'palette'),
	array(_t('Plugins'), 'plugin', 'plug'),
	array(_t('Style editor'), 'styleeditor', 'paint-brush'),
	array(_t('Layout'), 'layout', 'columns'),
	array(_t('Updater'), 'update', 'sync-alt'),
);

?>

<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
	<meta charset="utf-8">
	<title>Dashboard</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<!-- Google Fonts -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
	<link rel="stylesheet" type="text/css" href="../<?php echo TEMPLATE_PATH; ?>/style/bootstrap.min.css" />
	<!-- Font Awesome icons (free version)-->
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
	<!-- Material Design Bootstrap -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style/admin.css?v=<?php echo VERSION ?>">
	<script type="text/javascript" src="../js/jquery-3.5.1.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<script type="text/javascript" src="../js/chart/utils.js"></script>
	<script type="text/javascript" src="../js/chart/Chart.min.js"></script>
	<!-- MDB core JavaScript -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/js/mdb.min.js"></script>
</head>
<body>
<nav class="admin-bar navbar navbar-expand-lg navbar-light top-nav" id="mainNav">
	<button class="navbar-toggler" onclick="openSidebar()"><span class="navbar-toggler-icon"></span></button>
	<a class="navbar-brand" href="#"><img src="../images/ayay.png" class="logo"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
	<div class="quicklinks">
		<a href="<?php echo DOMAIN ?>admin.php?action=logout">
			<button type="button" class="btn btn-outline-danger btn-md"><?php _e('LOG OUT') ?></button>
		</a>
		<a href="<?php echo DOMAIN ?>" target="_blank">
			<button type="button" class="btn btn-primary btn-md"><?php _e('VISIT SITE') ?></button>
		</a>
	</div>
	</div>
</nav>
<div class="admin-container">
	<div class="sidebar" id="sidebar">
		<div class="admin-menu">
			<ul id="menu-list">
				<?php
				$page_name = _t('Dashboard');
				$page_slug = 'dashboard';
				if(isset($_GET['viewpage'])){
					$page_slug = htmlspecialchars($_GET['viewpage']);
				}
				$i = 0;
				foreach ($pages as $item) {
					$active = '';
					if($item[1] == $page_slug){
						$page_name = _t(esc_string($item[0]));
						$page_slug = esc_string($item[1]);
						$active = 'active';
					}
					if($item[1] == 'plugin'){
						//Dropdown
						echo '<li class="'.$active.'">';

						?>
						<div class="li-list dropdown-btn">
							<i class="fa fa-<?php echo $item[2] ?>" aria-hidden="true"></i>
							<?php echo esc_string($item[0]); ?>
							<i class="fa fa-caret-down"></i>

						</div>
						<?php

						echo '</li>';

						?>
						
						<div class="dropdown-container <?php echo $active ?>">
							<a href="?viewpage=<?php echo $item[1] ?>">
								<?php
									$selected_plugin = '';
									$active_child = '';
									if(isset($_GET['name'])){
										$selected_plugin = $_GET['name'];
									} else {
										$active_child = 'active';
									}
								?>
								<div class="dropdown-list <?php echo $active_child ?>">
									<?php _e('Manage Plugins') ?>
								</div>
							</a>
							<?php
								foreach ($plugin_list as $plugin) {
									if(substr($plugin['dir_name'], 0, 1) != '_'){
										$active_child = '';
										if($selected_plugin == $plugin['dir_name']){
											$active_child = 'active';
										} ?>
										<a href="?viewpage=<?php echo $item[1] ?>&name=<?php echo $plugin['dir_name'] ?>">
											<div class="dropdown-list <?php echo $active_child ?>">
												<?php _e($plugin['name']) ?>
											</div>
										</a>
										<?php
									}
								}
							?>
						</div>

					<?php
					} else {
						//Regular menu
						echo '<li class="'.$active.'">';
						echo '<a href="?viewpage='.$item[1].'">';
						echo '<div class="li-list" name="dashboard"><i class="fa fa-'.$item[2].'" aria-hidden="true"></i>';
						echo esc_string($item[0]);
						echo '</div></a>';
						echo '</li>';
					}
					$i++;
				}
				?>
			</ul>

			<div class="custom-control custom-switch" style="margin-left: 20px;">
			  <input type="checkbox" class="custom-control-input" id="darkSwitch" onclick="toggleTheme()">
			  <label class="custom-control-label" for="darkSwitch"><?php _e('Dark Mode') ?></label>
			</div>
			<div class="cms justify-content-center" style="display: flex;">
				<a href="https://ayaymedia.com/" target="_blank" style="margin-right: 10px">Ayay Media</a> v<?php echo VERSION ?>
			</div>
		</div>
	</div>
	<div class="content" id="content">
		<?php if( ADMIN_DEMO ){ echo '<div class="alert alert-warning" role="alert">(Admin Demo) Note: All actions are not saved.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'; } ?>

		<h3 class="page-title"><?php echo esc_string($page_name); ?></h3>

		<?php include 'core/'.$page_slug.'.php'; ?>

	</div>
	<span id="cms-version" style="display: none;"><?php echo VERSION ?></span>
</div>
<script type="text/javascript" src="../js/script.js?v=<?php echo VERSION ?>"></script>
<?php if($page_slug == 'dashboard'){
?>
<script type="text/javascript" src="../js/chart/stats.js?v=<?php echo VERSION ?>"></script>
<?php
} ?>
</body>
</html>