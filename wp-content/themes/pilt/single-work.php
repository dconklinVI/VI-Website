<?php get_header(); ?>

	<div id="content" class="work single">

		<div id="work-slideshow-container">
			<?php
			$custom_fields = get_post_custom($post->ID);
			echo do_shortcode('[tcx_slideshow id="' . $custom_fields["slideshow"][0] .'"]');?>
		</div>

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
			
			<?php edit_post_link('Edit'); ?>
		
			<div class="entrytext reverse">
				<div class="container">
					<h3><?php the_title(); ?></h3>
					<?php the_content(); ?>
				</div>
			</div>

			<div class="container center workcat">
				<?php $category = wp_get_post_terms($post->ID, 'work_categories'); ?>
				<div class="icon center <?php echo $category[0]->slug;?>"></div>
				<h3><?php echo $category[0]->name;?></h3>
				<p><?php echo $category[0]->description;?></p>
				<a href="/expertise/<?php echo $category[0]->slug;?>">Find out More</a>
			</div>
			<?php $next = mod_get_adjacent_post('next', array('work'));?>
			<div class="container">
				<a href="<?php echo get_permalink($next->ID); ?>" class="forward">View Next Project</a>
				<a href="/work" class="back left">Back to Featured Work</a>
			</div>

		</div>
		
		<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
	<div class="post">
		<h2>Post Not Found</h2>
	</div>
	
	<?php endif; ?>
	
	</div>

<?php get_footer(); ?>