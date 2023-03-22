<div class="section">

<?php

if(check_purchase_code() && ADMIN_DEMO){
	echo('<div class="bs-callout bs-callout-warning"><p>Please provide your <b>Item Purchase code</b>. You can submit or update your Purchase code on site settings.</p><p>To be able to add a game, you need to provide your Item Purchase code. <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code" target="_blank">Where to get Envato purchase code?</a></p></div>');
} else {

?>

<ul class="nav nav-tabs">
	<li class="nav-item">
		<a class="nav-link active" data-toggle="tab" href="#addgame"><?php _e('Upload game') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#fetch"><?php _e('Fetch games') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#remote"><?php _e('Remote add') ?></a>
	</li>
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#json"><?php _e('JSON Importer') ?></a>
	</li>
</ul>
<!-- Tab panes -->
<div class="tab-content">
	<div class="tab-pane tab-container active" id="addgame">
		<?php
			if(isset($_GET['status'])){
				if($_GET['status'] == 'added'){
					echo '<div class="alert alert-success alert-dismissible fade show" role="alert">'._t('Game added!').'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
				} elseif($_GET['status'] == 'ready'){
					echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">'._t('Game already exist!').'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
				} elseif($_GET['status'] == 'error'){
					$error = json_decode($_GET['error-data']);
					echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><ul>';
					foreach ($error as $value) {
						echo '<li>'._t($value).'</li>';
					}
					echo '</ul><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
				}
			}
		?>
		<form id="form-uploadgame" action="upload.php" enctype="multipart/form-data" method="post">
			<input type="hidden" name="source" value="self"/>
			<input type="hidden" name="tags" value=""/>
			<div class="form-group">
				<label for="title"><?php _e('Game title') ?>:</label>
				<input type="text" class="form-control" name="title" value="" required/>
			</div>
			<?php
				if(CUSTOM_SLUG){ ?>
				<div class="form-group">
					<label for="slug"><?php _e('Game slug') ?>:</label>
					<input type="text" class="form-control" name="slug" placeholder="game-title" value="" minlength="3" maxlength="15" required>
				</div>
				<?php }
			?>
			<div class="form-group">
				<label for="description"><?php _e('Description') ?>:</label>
				<textarea class="form-control" name="description" rows="3" required/></textarea>
			</div>
			<div class="form-group">
				<label for="instructions"><?php _e('Instructions') ?>:</label>
				<textarea class="form-control" name="instructions" rows="3"></textarea>
			</div>
			<label for="gamefile"><?php _e('Game file') ?> (.zip):</label>
			<ul>
				<li>Must contain index.html on root</li>
				<li>Must contain "thumb_1.jpg" (512x384px) on root</li>
				<li>Must contain "thumb_2.jpg"(512x512px) on root</li>
			</ul>
			<div class="input-group mb-3">
				<div class="custom-file">
					<input type="file" name="gamefile" class="custom-file-input" id="input_gamefile" accept=".zip">
					<label class="custom-file-label" for="input_gamefile">Choose file</label>
				</div>
			</div>
			<div class="form-group">
				<label for="width"><?php _e('Game width') ?>:</label>
				<input type="number" class="form-control" name="width" value="1280" required/>
			</div>
			<div class="form-group">
				<label for="height"><?php _e('Game height') ?>:</label>
				<input type="number" class="form-control" name="height" value="720" required/>
			</div>
			<div class="form-group">
				<label for="category"><?php _e('Category') ?>:</label>
				<select multiple class="form-control" name="category[]" size="8" required/>
					<?php
						$results = array();
						$data = Category::getList();
						$categories = $data['results'];
						foreach ($categories as $cat) {
							echo '<option>'.ucfirst($cat->name).'</option>';
						}
					?>
				</select>
			</div>
			<button type="submit" class="btn btn-primary btn-md"><?php _e('Upload game') ?></button>
		</form>
	</div>
	<div class="tab-pane tab-container fade" id="fetch">
		<div class="form-group">
			<label><?php _e('Distributor') ?></label> 
			<select name="distributor" class="form-control" id="distributor-options">
				<option value="" disabled selected hidden><?php _e('Choose game distributor') ?>...</option>
				<option data-toggle="tab" value="#gamedistribution">GameDistribution</option>
				<option data-toggle="tab" value="#gamepix">GamePix</option>
			</select>
		</div>
		<div class="fetch-games tab-container fade" id="gamedistribution">
			<div class="alert alert-warning alert-dismissible fade show" role="alert">You need joined <a href="https://gamedistribution.com/publishers" target="_blank">GameDistribution</a> publisher program to be able to publish their games on your site.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>

			<form id="form-fetch-gamedistribution" class="gamedistribution">
				<div class="form-group">
					<label>Collection</label> 
					<select name="Collection" class="form-control">
						<option value="all">All games</option>
						<option value="exclusive">Exclusive games</option>
						<option selected="selected" value="best">Best new games</option>
						<option value="featured">Hot games</option>
					</select>
				</div>
				<div class="form-group">
					<label>Category</label> 
					<select name="Category" class="form-control">
						<option selected="selected" value="All">All</option>
						<option value=".IO">.IO</option>
						<option value="2 Player">2 Player</option>
						<option value="3D">3D</option>
						<option value="Action">Action</option>
						<option value="Adventure">Adventure</option>
						<option value="Arcade">Arcade</option>
						<option value="Baby">Baby</option>
						<option value="Bejeweled">Bejeweled</option>
						<option value="Boys">Boys</option>
						<option value="Clicker">Clicker</option>
						<option value="Cooking">Cooking</option>
						<option value="Farming">Farming</option>
						<option value="Girls">Girls</option>
						<option value="Hypercasual">Hypercasual</option>
						<option value="Multiplayer">Multiplayer</option>
						<option value="Puzzle">Puzzle</option>
						<option value="Racing">Racing</option>
						<option value="Shooting">Shooting</option>
						<option value="Soccer">Soccer</option>
						<option value="Social">Social</option>
						<option value="Sports">Sports</option>
						<option value="Stickman">Stickman</option>
					</select>
				</div>
				<div class="form-group">
					<label>Item</label> 
					<select name="Limit" class="form-control">
						<option selected="selected" value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
					</select>
				</div>
				<div class="form-group">
					<label>Offset</label> 
					<select name="Offset" class="form-control">
						<option selected="selected" value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				<input type="submit" class="btn btn-primary btn-md" value="<?php _e('Fetch games') ?>"/>
			</form>
		</div>
		<div class="fetch-games tab-container fade" id="gamepix">
			<div class="alert alert-warning alert-dismissible fade show" role="alert">You need joined <a href="https://company.gamepix.com/publishers/" target="_blank">GamePix</a> publisher program to be able to publish their games on your site.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>
			<form id="form-fetch-gamepix" class="gamepix">
				<div class="form-group">
					<label>Sort By</label> 
					<select name="Sort" class="form-control">
						<option value="d" selected>Newest</option>
						<option value="q">Most Played</option>
					</select>
				</div>
				<div class="form-group">
					<label>Category</label> 
					<select name="Category" class="form-control">
						<option value="1">All</option>
						<option value="2">Arcade</option>
						<option value="3">Adventure</option>
						<option value="4">Junior</option>
						<option value="5">Board</option>
						<option value="6">Classic</option>
						<option value="7">Puzzle</option>
						<option value="8">Sports</option>
						<option value="9">Strategy</option>
					</select>
				</div>
				<div class="form-group">
					<label>Item</label> 
					<select name="Limit" class="form-control">
						<option selected="selected" value="10">10</option>
						<option value="20">20</option>
						<option value="30">30</option>
						<option value="40">40</option>
					</select>
				</div>
				<div class="form-group">
					<label>Offset</label> 
					<select name="Offset" class="form-control">
						<option selected="selected" value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</div>
				<input type="submit" class="btn btn-primary btn-md" value="<?php _e('Fetch games') ?>"/>
			</form>
		</div>
		<br>
		<div class="fetch-loading" style="display: none;">
			<h4><?php _e('Fetching games') ?> ...</h4>
		</div>
		<div id="action-info"></div>
		<div class="fetch-list" style="display: none;">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>#</th>
							<th><?php _e('Thumbnail') ?></th>
							<th><?php _e('Game name') ?></th>
							<th><?php _e('Category') ?></th>
							<th><?php _e('URL') ?></th>
							<th><?php _e('Action') ?></th>
						</tr>
					</thead>
					<tbody id="gameList">
					</tbody>
				</table>
			</div>
			<button class="btn btn-primary btn-md" id="add-all"><?php _e('Add all') ?></button>
		</div>
	</div>
	<div class="tab-pane tab-container fade" id="remote">
		<form id="form-remote">
			<div class="form-group">
				<label for="title"><?php _e('Game title') ?>:</label>
				<input type="text" class="form-control" name="title" value="" required />
			</div>
			<?php
				if(CUSTOM_SLUG){ ?>
				<div class="form-group">
					<label for="slug"><?php _e('Game slug') ?>:</label>
					<input type="text" class="form-control" name="slug" placeholder="game-title" value="" minlength="3" maxlength="15" required>
				</div>
				<?php }
			?>
			<div class="form-group">
				<label for="description"><?php _e('Description') ?>:</label>
				<textarea class="form-control" name="description" rows="3" required /></textarea>
			</div>
			<div class="form-group">
				<label for="instructions"><?php _e('Instructions') ?>:</label>
				<textarea class="form-control" name="instructions" rows="3"></textarea>
			</div>
			<div class="form-group">
				<label for="thumb_1"><?php _e('Thumbnail') ?> 512x384:</label>
				<input type="text" class="form-control" name="thumb_1" placeholder="https://example.com/yourgames/thumb_1.jpg" value="" required />
			</div>
			<div class="form-group">
				<label for="thumb_2"><?php _e('Thumbnail') ?> 512x512:</label>
				<input type="text" class="form-control" name="thumb_2" placeholder="https://example.com/yourgames/thumb_2.jpg" value="" required />
			</div>
			<div class="form-group">
				<label for="url"><?php _e('Game URL') ?>:</label>
				<input type="text" class="form-control" name="url" value="" placeholder="https://example.com/yourgames/index.html" required />
			</div>
			<div class="form-group">
				<label for="width"><?php _e('Game width') ?>:</label>
				<input type="number" class="form-control" name="width" value="1280" required />
			</div>
			<div class="form-group">
				<label for="height"><?php _e('Game height') ?>:</label>
				<input type="number" class="form-control" name="height" value="720" required />
			</div>
			<div class="form-group">
				<label for="category"><?php _e('Category') ?>:</label>
				<select multiple class="form-control" name="category" size="8" required />
					<?php
						$results = array();
						$data = Category::getList();
						$categories = $data['results'];
						foreach ($categories as $cat) {
							echo '<option>'.ucfirst($cat->name).'</option>';
						}
					?>
				</select>
			</div>
			<button type="submit" class="btn btn-primary btn-md"><?php _e('Add game') ?></button>
		</form>
	</div>
	<div class="tab-pane tab-container fade" id="json">
		<p>Bulk import your game data with JSON format.</p>
		<p>Read "User Documentation" for sample JSON structure or code.</p>
		<p>Open browser log to see the import progress.</p>
		<p>Paste your JSON data below.</p>
		<form id="form-json">
			<div class="form-group">
				<label for="json-importer">JSON data:</label>
				<textarea class="form-control" name="json-importer" rows="8" required /></textarea>
			</div>
			<button type="submit" class="btn btn-primary btn-md"><?php _e('Import') ?></button>
		</form>
		<br>
		<p>Preview JSON data (Game list) before submited.</p>
		<button class="btn btn-primary btn-md" id="json-preview"><?php _e('Preview') ?></button>
		<br><br>
		<table class="table" style="display: none;" id="table-json-preview">
			<thead>
				<tr>
					<th>#</th>
					<th><?php _e('Title') ?></th>
					<th><?php _e('Slug') ?></th>
					<th><?php _e('URL') ?></th>
					<th><?php _e('Width') ?></th>
					<th><?php _e('Height') ?></th>
					<th><?php _e('Thumb') ?> 1</th>
					<th><?php _e('Thumb') ?> 2</th>
					<th><?php _e('Category') ?></th>
					<th><?php _e('Source') ?></th>
				</tr>
			</thead>
			<tbody id="json-list-preview">
			</tbody>
		</table>
	</div>
</div>
<?php } ?>
</div>