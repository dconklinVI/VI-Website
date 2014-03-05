<?php get_header(); ?>
<div class="container">
	<div class="icon center <?php echo get_query_var('term');?>"></div>
	<h2><strong><?php single_term_title();?></strong> Creative</h2>

	<div id="content" class="archive">

		<div id="gray-gallery">
		<?php global $query_string;
		query_posts($query_string . '&posts_per_page=-1');
		
		if (have_posts()) : while (have_posts()) : the_post();
			echo '<div class="gray"><a href="' . get_permalink() . '">';
			the_post_thumbnail('gray_thumbnail-gray');
			echo '</a><div class="hover">';
			echo '<a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '</span>';
			the_post_thumbnail('gray_thumbnail');
			echo '</a></div></div>';
	
		endwhile; ?>
		</div>

		<?php tcx_paginate(); ?>
	
		<?php else : ?>

		<div class="post">
			<h3>No Posts Found</h3>
		</div>

		<?php endif; ?>
		
	</div>

</div>

<?php get_footer(); ?>