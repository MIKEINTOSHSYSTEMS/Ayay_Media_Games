<div class="navbar-collapse collapse justify-content-end" id="navb">
	<ul class="navbar-nav ml-auto text-uppercase">
		<li class="nav-item">
			<a class="nav-link" href="https://ayaymedia.com">Go to Ayay Media Home Page</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/page/about-us">About Us</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="/register/">Register</a>
		</li>
		<li class="nav-item">
			<?php
			if(is_null($login_user)){
				echo('<a class="nav-link" href="'.get_permalink('login').'">'._t('Login').'</a>');
			}
			?>
		</li>
	</ul>
	<form class="form-inline my-2 my-lg-0 search-bar" action="/index.php">
		<div class="input-group">
			<input type="hidden" name="viewpage" value="search" />
			<input type="text" class="form-control rounded-left search" placeholder="<?php _e('Search game') ?>" name="slug" minlength="2" required />
			<div class="input-group-append">
				<button type="submit" class="btn btn-search" type="button">
					<i class="fa fa-search"></i>
				</button>
			</div>
		</div>
	</form>
</div>