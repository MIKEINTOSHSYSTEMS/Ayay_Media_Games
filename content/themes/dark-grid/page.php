<?php include  TEMPLATE_PATH . "/includes/header.php" ?>
<div class="container">
	<div class="game-container">
	<div class="row">
		<div class="col-md-9">
				<h1 class="singlepage-title"><?php echo htmlspecialchars( $page->title )?></h1>
				<div class="page-content">
					<?php echo nl2br($page->content) ?>
				</div>
		</div>
		<div class="col-md-3">
			<?php include  TEMPLATE_PATH . "/parts/sidebar.php" ?>
		</div>
	</div>
	</div>
</div>
<?php include  TEMPLATE_PATH . "/includes/footer.php" ?>