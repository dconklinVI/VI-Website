<?php get_header(); ?>
<div class="container">

	<div id="content" class="blog single">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php if ($tcx_theme_options['single_navigation_position'] == 'before' || $tcx_theme_options['single_navigation_position'] == 'both') {echo tcx_single_navigation();} ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?></a></h2><?php edit_post_link('Edit'); ?>
				<?php if ($tcx_theme_options['single_postmeta_position'] == 'before') {echo tcx_build_postmeta();} ?>
				<div id="social-sharrre">
					<div id="social-facebook" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
					<div id="social-twitter" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
					<div id="social-googleplus" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
					<div id="social-stumbleupon" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				</div>
				
				<div class="entrytext">
					<?php the_content(); ?>
					<a href="/engage/the-vi-blog" class="back">Back to Blog</a>
				</div>

				<?php if ($tcx_theme_options['single_postmeta_position'] == 'after') {echo tcx_build_postmeta();} ?>
			</div>
		
		<?php if ($tcx_theme_options['single_navigation_position'] == 'after' || $tcx_theme_options['single_navigation_position'] == 'both') {echo tcx_single_navigation();} ?>
		
		<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
	<div class="post">
		<h2>Post Not Found</h2>
	</div>
	
	<?php endif; ?>
	
	</div>

	<?php get_sidebar(); ?>

</div>

<?php get_footer(); ?>