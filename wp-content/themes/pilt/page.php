<?php get_header(); ?>
<?php if(!$fullwidth) {?>
<div class="container">
<? } ?>

<?php if(is_front_page()){$location = "home";} else {$location = "interior";} 
if (is_page(15)) {$section = "people";}
else if (is_page(11)) {$section = "work";}
else if (is_page(23)) {$section = "events";}
else if (is_page(49)) {$section = "careers";}
else if (is_page(720)) {$section = "shorts";}
else if ($post->post_parent == '19') {$section = "services";} ?>

	<?php if (is_home() || is_single()) { get_sidebar(); } ?>
	
	<div id="content" class="<?php echo $location;?>"> 
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
	
		<div class="post" id="post-<?php the_ID(); ?>">
			<div class="entrytext">
				<?php the_content(); ?>
				<?php if ($section == "services") { ?>
					<a href="/expertise/services" class="back">Back to Services</a>
				<?php } ?>
			</div>
			<?php edit_post_link('Edit this page', '<p>', '</p>'); ?>
		</div>

	<?php endwhile; endif; ?>

	</div>

	<?php if ($section == "people") {
		echo '<div id="gray-gallery">';
		$args = array('post_type' => 'people', 'posts_per_page' => -1, 'order' => 'ASC');
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
			$custom_fields = get_post_custom($post->ID);
			echo '<div class="gray"><a href="' . get_permalink() . '">';
			the_post_thumbnail('gray_thumbnail-gray');
			echo '</a><div class="hover">';
			echo '<a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '<br/><small>' . $custom_fields["position"][0] . '</small></span>';
			the_post_thumbnail('gray_thumbnail');
			echo '</a></div></div>';
		endwhile;
		echo '</div><a href="/careers/" class="forward">Join Our Team</a><br/>';
	} else if ($section == "work") {
		echo '<div id="gray-gallery">';
		$args = array('post_type' => 'work', 'posts_per_page' => -1, 'order' => 'ASC');
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
			echo '<div class="gray"><a href="' . get_permalink() . '">';
			the_post_thumbnail('gray_thumbnail-gray');
			echo '</a><div class="hover">';
			echo '<a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '</span>';
			the_post_thumbnail('gray_thumbnail');
			echo '</a></div></div>';
		endwhile;
		echo '</div>';
	} else if ($section == "events") {
		echo '<div id="gray-gallery">';
		$args = array('post_type' => 'event', 'posts_per_page' => -1, 'order' => 'ASC');
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
			echo '<div class="gray"><a href="' . get_permalink() . '">';
			the_post_thumbnail('gray_thumbnail-gray');
			echo '</a><div class="hover">';
			echo '<a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '</span>';
			the_post_thumbnail('gray_thumbnail');
			echo '</a></div></div>';
		endwhile;
		echo '</div>';
	} else if ($section == "shorts") {
		echo '<div id="gray-gallery" class="short-gallery">';
		$args = array('post_type' => 'short', 'posts_per_page' => -1, 'order' => 'ASC');
		$loop = new WP_Query($args);
		while ($loop->have_posts()) : $loop->the_post();
			$custom_fields = get_post_custom($post->ID);
			echo '<div class="gray"><a href="' . $custom_fields['videourl'][0] . '" title="' . get_the_content() . '">';
			the_post_thumbnail('gray_thumbnail-gray');
			echo '</a><div class="hover">';
			echo '<a href="' . $custom_fields['videourl'][0] . '" title="' . get_the_content() . '"><span class="title">' . get_the_title() . '</span>';
			the_post_thumbnail('gray_thumbnail');
			echo '</a></div></div>';
		endwhile;
		echo '</div>';
	}?>
<?php if(!$fullwidth) {?>
</div>
<? } ?>

<?php if ($section == "careers") { ?>
	<div class="clear"></div>
	<div class="entrytext reverse">
		<div class="container">
			<h3 class="career-list-title">What we are looking for now</h3>
		</div>
	</div>
	<div id="career-list" class="container center career-list">
		<?php $args = array('post_type' => 'career', 'posts_per_page' => -1, 'order' => 'ASC');
		$loop = new WP_Query($args);
		if ($loop->have_posts()) {
			$x = 1;
			$icons = '';
			$info = '';
			while ($loop->have_posts()) : $loop->the_post();
				$custom_fields = get_post_custom($post->ID);
				$icons .= '<a href="' . get_permalink() . '" class="icon ' . $custom_fields['icon'][0] . '" rel="desc-' . $x . '"></a>';
				$info .= '<div id="desc-' . $x . '" class="desc-' . $x . ' desc"><h3>' . get_the_title() . '</h3><p><a href="' . get_permalink() . '">Find Out More</a></p></div>';
				$x++;
			endwhile;
		}
		$icons .= '<a href="/careers/job-application/?clean=1" class="icon no-careers iframe" rel="desc-' . $x . '"></a>';
		$info .= '<div id="desc-' . $x . '" class="desc-' . $x . ' desc"><h3>You Tell Us</h3><p>We may not know what we are missing, but give us a try and we might find out all we are missing is you.<br/><a href="/careers/job-application/?clean=1" class="iframe">Find Out More</a></p></div>';
		echo '<div class="career-icons">' . $icons . '</div><div class="career-descriptions">' . $info . '</div>';
		?>
	</div>
<?php } ?>

<?php get_footer(); ?>