<?php get_header(); ?>
<div class="container">
	<div class="icon search center"></div>
	<h2>Tell us what you are <strong>looking</strong> for</h2>

	<div id="content" class="blog search archive">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="post" id="post-<?php the_ID(); ?>">
			<h3><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h3><?php edit_post_link('Edit'); ?>

			<?php if ($tcx_theme_options['blog_postmeta_position'] == 'before') {echo tcx_build_postmeta();} ?>

			<div class="entrytext">
				<?php
				if ($tcx_theme_options['blog_content'] == 'full') {
					$excerpt = get_the_content();
				} else {
					$excerpt = get_the_excerpt();
				}
				
				echo $excerpt; ?>
				
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
	<div class="clear"></div>
</div>

<?php get_footer(); ?>