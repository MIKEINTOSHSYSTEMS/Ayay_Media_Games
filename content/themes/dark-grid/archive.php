<?php include  TEMPLATE_PATH . "/includes/header.php" ?>
<div class="container">
	<div class="game-container">
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
		<div class="content-wrapper">
			<h3 class="item-title"><?php _e('%a Games', esc_string($archive_title)) ?></h3>
			<p><?php _e('%a games in total.', esc_int($total_games)) ?> <?php _e('Page %a of %b', esc_int($cur_page), esc_int($total_page)) ?></p>
			<div class="game-container">
				<div class="grid-layout grid-wrapper">
					<?php foreach ( $games as $game ) { ?>
					<?php include  TEMPLATE_PATH . "/includes/grid.php" ?>
					<?php } ?>
				</div>
			</div>
			<div class="pagination-wrapper">
				<nav aria-label="Page navigation example">
					<ul class="pagination justify-content-center">
						<?php
						$cur_page = 1;
						if(isset($_GET['page'])){
							$cur_page = $_GET['page'];
						}
						if($total_page){
							$max = 8;
							$start = 0;
							$end = $max;
							if($max > $total_page){
								$end = $total_page;
							} else {
								$start = $cur_page-$max/2;
								$end = $cur_page+$max/2;
								if($start < 0){
									$start = 0;
								}
								if($end - $start < $max-1){
									$end = $max;
								}
								if($end > $total_page){
									$end = $total_page;
								}
							}
							if($start > 0){
								echo '<li class="page-item"><a class="page-link" href="'. get_permalink('category', $_GET['slug']) .'&page=1">1</a></li>';
								echo('<li class="page-item disabled"><span class="page-link">...</span></li>');
							}
							for($i = $start; $i<$end; $i++){
								$disabled = '';
								if($cur_page){
									if($cur_page == ($i+1)){
										$disabled = 'disabled';
									}
								}
								echo '<li class="page-item '.$disabled.'"><a class="page-link" href="'. get_permalink('category', $_GET['slug']) .'&page='.($i+1).'">'.($i+1).'</a></li>';
							}
							if($end < $total_page){
								echo('<li class="page-item disabled"><span class="page-link">...</span></li>');
								echo '<li class="page-item"><a class="page-link" href="'. get_permalink('category', $_GET['slug']) .'&page='.$total_page.'">'.$total_page.'</a></li>';
							}
						}
						?>
					</ul>
				</nav>
			</div>
		</div>
		<?php include  TEMPLATE_PATH . "/parts/ad-banner-728.php" ?>
	</div>
</div>
<?php include  TEMPLATE_PATH . "/includes/footer.php" ?>