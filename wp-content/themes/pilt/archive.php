<?php get_header(); ?>
<div class="container">
	<?php if(is_category('newsroom')) {?>
	<div class="icon newsroom center"></div>
	<h2>VI's <strong>Newsroom</strong></h2>
	<?php } else {
	if (is_category()) {
		$cat = get_query_var('cat');
		$icon = get_category ($cat);
		echo '<div class="icon center '. $icon->slug . '"></div>';
	}?>
	<h2><?php echo tcx_archive_title(); ?></h2>
	<?php } ?>

	<div id="content" class="blog archive">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3><?php edit_post_link('Edit'); ?>

			<?php if ($tcx_theme_options['blog_postmeta_position'] == 'before') {echo tcx_build_postmeta();} ?>
			
			<div class="entrytext">
				<?php if ($tcx_theme_options['blog_featured_images'] == '1' && has_post_thumbnail()) {echo '<a href="' . get_permalink() . '">' . get_the_post_thumbnail($id, 'thumbnail', array('class' => 'attachment-thumbnail alignleft')) . '</a>';} ?>
				<?php if ($tcx_theme_options['blog_content'] == 'full') {the_content();} else {the_excerpt();} ?>
			</div>

			<?php if ($tcx_theme_options['blog_postmeta_position'] == 'after') {echo tcx_build_postmeta();} ?>

		</div>

		<?php if ($tcx_theme_options['blog_separators'] == '1') {echo '<div class="separator"></div>';} ?>
	
		<?php endwhile; ?>

		<?php tcx_paginate(); ?>
	
		<?php else : ?>

		<div class="post">
			<h3>No Posts Found</h3>
		</div>

		<?php endif; ?>
		
	</div>

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>