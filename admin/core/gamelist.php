<?php
	if(isset($_GET['status'])){
		$class = 'alert-success';
		$message = '';
		if($_GET['status'] == 'deleted'){
			$class = 'alert-warning';
			$message = 'Game removed!';
		}
		echo '<div class="alert '.$class.' alert-dismissible fade show" role="alert">'._t($message).'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
	}
?>
<div class="row">
	<div class="col">
		<form class="has-search">
			<input type="hidden" name="viewpage" value="gamelist" />
			<input type="hidden" name="action" value="search" />
		    <span class="fa fa-search form-control-feedback"></span>
		    <input class="form-control rounded" type="text"  placeholder="<?php _e('Search game') ?>..." name="key" minlength="2" required />
		</form>
	</div>
	<div class="col">
		<form>
			<input type="hidden" name="viewpage" value="gamelist" />
			<input type="hidden" name="action" value="category" />
			<select name="key" class="custom-select" onchange="this.form.submit()">
				<option value="" disabled selected hidden><?php _e('Category') ?></option>
				<?php
					if(isset($_GET['action'])){
						if($_GET['action'] == 'category'){
							$cur_cat_name = esc_string($_GET['key']);
						}
					}
					$selected = '';
					$results = array();
					$data = Category::getList();
					$categories = $data['results'];
					foreach ($categories as $cat) {
						if($cur_cat_name == $cat->name){
							$selected = 'selected';
						} else {
							$selected = '';
						}
						echo '<option '.$selected.'>'.ucfirst($cat->name).'</option>';
					}
				?>
			</select>
		</form>
	</div>
	<div class="col">
		<form class="has-search">
			<input type="hidden" name="viewpage" value="gamelist" />
			<input type="hidden" name="action" value="source" />
		    <span class="fa fa-code form-control-feedback"></span>
		    <input class="form-control rounded" type="text"  placeholder="Source" name="key" minlength="2" required />
		</form>
	</div>
</div>
		
<br>
<div class="section">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>#</th>
					<th><?php _e('ID') ?></th>
					<th><?php _e('Thumbnail') ?></th>
					<th><?php _e('Game Name') ?></th>
					<th><?php _e('Category') ?></th>
					<th><?php _e('Source') ?></th>
					<th><?php _e('URL') ?></th>
					<th><?php _e('Action') ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$index = 0;
				$cur_page = 1;
				if(isset($_GET['page'])){
					$cur_page = $_GET['page'];
				}
				$data;
				if(isset($_GET['action'])){
					if($_GET['action'] == 'search'){
						$data = Game::searchGame($_GET['key'], 20, 20*($cur_page-1));
					} elseif($_GET['action'] == 'category'){
						$cat_id = Category::getIdByName($_GET['key']);
						$data = Category::getListByCategory($cat_id, 20, 20*($cur_page-1));
					} elseif($_GET['action'] == 'source'){
						$data = Game::getListBySource($_GET['key'], 20, 20*($cur_page-1));
					}
				} else {
					$data = get_game_list('new', 20, 20*($cur_page-1));
				}
				$games = $data['results'];
				$total_game = $data['totalRows'];
				$total_page = $data['totalPages'];
				foreach ( $games as $game ) {
					$index++;
					$categories = $game->category;
					?>
				<tr>
					<th scope="row"><?php echo esc_int($index+(20*($cur_page-1))); ?></th>
					<td>
						<?php echo '<strong>'.esc_int($game->id).'</strong>' ?>
					</td>
					<td><img src="<?php echo get_small_thumb($game) ?>" width="60px" height="auto" class="gamelist"></td>
					<td>
						<?php echo esc_string($game->title) ?>
					</td>
					<td><span class="categories"><?php echo esc_string($categories)?></span></td>
					<td>
						<?php echo esc_string($game->source) ?>
					</td>
					<td><a href="<?php echo esc_url($game->url) ?>" target="_blank"><?php _e('Play') ?></a></td>
					<td>
						<span class="actions">
							<a class="editgame" href="#" id="<?php echo esc_int($game->id)?>"><i class="fa fa-pencil-alt circle" aria-hidden="true"></i></a>
							<a href="<?php echo DOMAIN ?>admin/request.php?action=deleteGame&id=<?php echo esc_int($game->id) ?>&redirect=<?php echo DOMAIN ?>admin/dashboard.php?viewpage=gamelist"><i class="fa fa-trash circle" aria-hidden="true"></i></a>
						</span>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>

	<p><?php _e('%a games in total.', esc_int($total_game)) ?></p>
	<div class="pagination-wrapper">
		<nav aria-label="Page navigation">
			<ul class="pagination pg-blue justify-content-center">
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
						echo '<li class="page-item"><a class="page-link" href="'.DOMAIN.'admin/dashboard.php?viewpage=gamelist&page=1">1</a></li>';
						echo('<li class="page-item disabled"><span class="page-link">...</span></li>');
					}
					for($i = $start; $i<$end; $i++){
						$disabled = '';
						if($cur_page){
							if($cur_page == ($i+1)){
								$disabled = 'active disabled';
							}
						}
						echo '<li class="page-item '.$disabled.'"><a class="page-link" href="'.DOMAIN.'admin/dashboard.php?viewpage=gamelist&page='.($i+1).'">'.($i+1).'</a></li>';
					}
					if($end < $total_page){
						echo('<li class="page-item disabled"><span class="page-link">...</span></li>');
						echo '<li class="page-item"><a class="page-link" href="'.DOMAIN.'admin/dashboard.php?viewpage=gamelist&page='.($total_page).'">'.($total_page).'</a></li>';
					}
				}
				?>
			</ul>
		</nav>
		<div class="text-center">
			<form>
				<input type="hidden" value="gamelist" name="viewpage" />
				<div class="form-group">
					<label for="page">Page:</label>
					<select name="page" required>
						<?php
						if($total_page){
							for($i = 0; $i < $total_page; $i++ ){
								$selected = '';
								if(($i+1) == $cur_page){
									$selected = 'selected';
								}
								echo('<option value="'.($i+1).'" '.$selected.'>'.($i+1).'</option>');
							}
						}
						?>
					</select>
					<input type="submit" value="Go"/>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-game" tabindex="-1" role="dialog" aria-labelledby="edit-game-modal-label" aria-hidden="true">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h5 class="modal-title" id="edit-game-label"><?php _e('Edit game') ?></h5>
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span>
		</button>
	  </div>
	  <div class="modal-body">
		<form id="form-editgame">
			<input type="hidden" id="edit-id" name="id" value=""/>
			<div class="form-group">
				<label for="title"><?php _e('Game Title') ?>:</label>
				<input type="text" class="form-control" id="edit-title" name="title" placeholder="Name of the game" required minlength="3" maxlength="255" value=""/>
			</div>
			<div class="form-group">
				<label for="slug"><?php _e('Game Slug') ?>:</label>
				<input type="text" class="form-control" id="edit-slug" name="slug" placeholder="Game url ex: this-is-sample-game" required minlength="3" maxlength="255" value=""/>
			</div>
			<div class="form-group">
				<label for="description"><?php _e('Description') ?>:</label>
				<textarea class="form-control" name="description" id="edit-description" rows="3" placeholder="The description of the game" required minlength="3" maxlength="100000"></textarea>
			</div>
			<div class="form-group">
				<label for="instructions"><?php _e('Instructions') ?>:</label>
				<textarea class="form-control" name="instructions" id="edit-instructions" rows="3" placeholder="The instructions of the game" required minlength="3" maxlength="100000"></textarea>
			</div>
			<div class="form-group">
				<label for="url"><?php _e('Game URL') ?>:</label>
				<input type="text" class="form-control" id="edit-url" name="url" placeholder="https://domain.com/game-title/index.html" required minlength="3" maxlength="255" value=""/>
			</div>
			<div class="form-group">
				<label for="thumb_1">Game thumb_1:</label>
				<input type="text" class="form-control" id="edit-thumb_1" name="thumb_1" placeholder="https://domain.com/game-title/thumb_1.jpg" required minlength="3" maxlength="255" value=""/>
			</div>
			<div class="form-group">
				<label for="thumb_2">Game thumb_2:</label>
				<input type="text" class="form-control" id="edit-thumb_2" name="thumb_2" placeholder="https://domain.com/game-title/thumb_2.jpg" required minlength="3" maxlength="255" value=""/>
			</div>
			<div class="form-group">
				<label for="width"><?php _e('Game width') ?>:</label>
				<input type="number" class="form-control" id="edit-width" name="width" placeholder="1280" required minlength="3" maxlength="5" value=""/>
			</div>
			<div class="form-group">
				<label for="height"><?php _e('Game height') ?>:</label>
				<input type="number" class="form-control" id="edit-height" name="height" placeholder="720" required minlength="3" maxlength="5" value=""/>
			</div>
			<div class="form-group">
				<label for="category"><?php _e('Game category') ?>:</label>
				<select multiple class="form-control" id="edit-category" name="category" size="8" required>
					<?php
					$cats = get_all_categories();
					foreach ($cats as $cat) {
						echo('<option value="'.$cat->name.'">'.$cat->name.'</option>');
					}
					?>
				</select>
			</div>
			<input type="submit" class="btn btn-primary" value="<?php _e('Save changes') ?>" />
			<input type="button" class="btn btn-secondary" data-dismiss="modal" value="<?php _e('Close') ?>" />
		</form>
	  </div>
	</div>
  </div>
</div>