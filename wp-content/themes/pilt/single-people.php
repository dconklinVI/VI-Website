<?php get_header(); ?>
<div class="container">

	<div id="content" class="people single">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="portrait">
			<?php //the_post_thumbnail('large'); ?>
			<?php $custom_fields = get_post_custom($post->ID); ?>
			<img src="<?php echo $custom_fields["portrait"][0]; ?>" alt="<?php the_title();?>"/>
			<div id="social-sharrre">
				<div id="social-facebook" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-twitter" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-googleplus" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
				<div id="social-stumbleupon" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
			</div>
		</div>

		<div class="post" id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?></h2>
			<?php echo '<h3>' . $custom_fields["position"][0] . '</h3>';

			echo '<div class="icons">';
			if($custom_fields["email"][0]) {echo '<a href="mailto:'.$custom_fields["email"][0].'" class="social-icons email">Email</a>';}
			if($custom_fields["facebook"][0]) {echo '<a href="'.$custom_fields["facebook"][0].'" class="social-icons facebook">Follow on Facebook</a>';}
			if($custom_fields["twitter"][0]) {echo '<a href="'.$custom_fields["twitter"][0].'" class="social-icons twitter">Follow on Twitter</a>';}
			if($custom_fields["linkedin"][0]) {echo '<a href="'.$custom_fields["linkedin"][0].'" class="social-icons linkedin">My profile on LinkedIn</a>';}
			if($custom_fields["googleplus"][0]) {echo '<a href="'.$custom_fields["googleplus"][0].'" class="social-icons googleplus">Connect on Google+</a>';}
			if($custom_fields["instagram"][0]) {echo '<a href="'.$custom_fields["instagram"][0].'" class="social-icons instagram">Find on Instagram</a>';}
			echo '</div>';
			?>
			<?php edit_post_link('Edit'); ?>
		
				<div class="entrytext">
					<?php the_content(); ?>
					<a href="/culture/people" class="back">Back to People</a>
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