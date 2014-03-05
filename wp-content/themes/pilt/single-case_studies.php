 <?php get_header(); ?>

	<div id="content" class="single">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
			<div class="reverse">
				<div class="container">
					<h3><?php the_title(); ?></h3>
				</div>
			</div>

			<div class="entrytext">
				<div class="container">
					<?php edit_post_link('Edit'); ?>
					<?php the_content(); ?>
					<div id="social-sharrre">
						<div id="social-facebook" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
						<div id="social-twitter" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
						<div id="social-googleplus" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
						<div id="social-stumbleupon" data-url="<?php echo get_permalink();?>" data-text="Check out this page on VI -" data-title=""></div>
					</div>
					<div class="c50 textalignright">
						<div class="icon small send"></div><a href="/mail-form/?clean=1&amp;tcx_subject=<?php the_title();?>&amp;tcx_url=Check out this work by VI: <?php the_permalink();?>" class="tcx_button iframe">Send Email</a>
					</div>
					<div class="c50">
						<?php $custom_fields = get_post_custom($post->ID); ?>
						<div class="icon small pdf"></div><a href="/expertise/download-form/?pdfdownload=<?php echo $custom_fields["pdfdownload"][0];?>" class="tcx_button">Download PDF</a>
					</div>
				</div>
			</div>

			<div class="container">
				<?php $category = wp_get_post_terms($post->ID, 'work_categories'); ?>
				<a href="/expertise/<?php echo $category[0]->slug; ?>" class="back">Back to <?php echo $category[0]->name; ?></a>
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