<?php
	$index = 0;
	foreach ( $results['pages'] as $page ) {
		$index++;
		?>
<tr>
	<th scope="row"><?php echo esc_int($index); ?></th>
	<td>
		<?php echo esc_string($page->title)?>
	</td>
	<td>
		<?php echo date('j M Y', $page->createdDate) ?>
	</td>
	<td>
		<?php echo esc_string($page->slug)?>
	</td>
	<td><a href="<?php echo get_permalink('page', $page->slug) ?>" target="_blank"><?php _e('Visit') ?></a></td>
	<td><span class="actions"><a class="editpage" href="#" id="<?php echo esc_int($page->id)?>"><i class="fa fa-pencil-alt circle" aria-hidden="true"></i></a><a class="deletepage" href="#" id="<?php echo esc_int($page->id)?>"><i class="fa fa-trash circle" aria-hidden="true"></i></a></span></td>
</tr>
<?php } ?>