	<ul id="sidebar">

		<?php if(is_singular('post')) { ?>
		<li class="sidebar-image">
			<?php the_post_thumbnail('thumbnail'); ?>
		</li>
		<?php } ?>
		
		<?php if (is_home() || is_single() || is_archive() || is_404() || is_search()) {
			if (!dynamic_sidebar('Blog Sidebar')) : endif;
		} ?>
			
	</ul>