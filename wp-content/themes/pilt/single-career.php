 <?php get_header(); ?>

	<div id="content" class="single">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div class="post" id="post-<?php the_ID(); ?>">
		
			<?php $custom_fields = get_post_custom($post->ID); ?>
			<div class="icon center <?php echo $custom_fields['icon'][0];?>"></div>
			<h2><?php the_title(); ?></h2>
			
			<div class="entrytext">
				<div class="container">
					<?php edit_post_link('Edit'); ?>
					<?php the_content(); ?>
					<div class="c50 jobs">
						<div class="icon small apply"></div>
						<!--HubSpot Call-to-Action Code -->
<span class="hs-cta-wrapper" id="hs-cta-wrapper-2fa848ab-ee72-4c68-b096-5120da935057">
    <span class="hs-cta-node hs-cta-2fa848ab-ee72-4c68-b096-5120da935057" id="hs-cta-2fa848ab-ee72-4c68-b096-5120da935057">
        <!--[if lte IE 8]><div id="hs-cta-ie-element"></div><![endif]-->
        <a href="http://cta-redirect.hubspot.com/cta/redirect/232782/2fa848ab-ee72-4c68-b096-5120da935057">Apply for this Job</a>
    </span>
    <script charset="utf-8" src="https://js.hscta.net/cta/current.js"></script>
        <script type="text/javascript">
            hbspt.cta.load(232782, '2fa848ab-ee72-4c68-b096-5120da935057');
        </script>
</span>
<!-- end HubSpot Call-to-Action Code -->
					</div>
					<div class="c50 jobs">
						<div class="icon small send"></div><a href="/mail-form/?clean=1&amp;tcx_subject=<?php the_title();?>&amp;tcx_url=Check out this cool job at VI: <?php the_permalink();?>" class="tcx_button iframe">Send to a Friend</a>
					</div>
				</div>
			</div>

			<div class="container">
				<a href="/careers/" class="back">Back to Careers</a>
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