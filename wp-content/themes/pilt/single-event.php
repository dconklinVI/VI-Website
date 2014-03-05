<?php get_header(); ?>
<div class="container">

	<div id="content" class="people event single">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="portrait">
			<?php the_post_thumbnail('large'); ?>
			<div id="social-sharrre">
				<div id="social-facebook" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-twitter" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-googleplus" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-stumbleupon" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
			</div>
		</div>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php edit_post_link('Edit'); ?>
		
				<div class="entrytext">
					<?php the_content(); ?>
					<a href="/culture/events" class="back">Back to Events</a>
				</div>

			</div>
		
		<?php comments_template(); ?>
	
	<?php endwhile; else: ?>
	
	<div class="post">
		<h2>Post Not Found</h2>
	</div>
	
	<?php endif; ?>
	
	</div>

</div>

<?php get_footer(); ?>