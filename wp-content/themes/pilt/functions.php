<?php

// Get Theme Options
require_once (TEMPLATEPATH . '/functions/tcx-default-settings.php');
require_once (TEMPLATEPATH . '/functions/tcx-slideshows-default-settings.php');
$tcx_theme_options = get_option('tcx_options', tcx_default_options());

// Custom Styles For Admin and TinyMCE
if (is_admin()) {
	add_action('admin_init', 'tcx_admin_includes');
	function tcx_admin_includes() {
		wp_register_script('adminscripts', get_bloginfo('template_url') . '/script/admin.js');
		wp_enqueue_script('adminscripts');

		wp_register_style('adminstyles', get_bloginfo('template_url') . '/css/admin.css');
		wp_enqueue_style('adminstyles');
		
		add_editor_style('css/editor.css');
	}
	// Create Theme Options Menu
	if (current_user_can('edit_theme_options')) {
		require_once (TEMPLATEPATH . '/functions/tcx-admin.php');
	}
}

// Select Which Template to Use
function tcx_set_template($set_template) {
	if ($_GET['clean'] == '1') { $set_template = TEMPLATEPATH . '/clean.php'; }
	if ($_GET['full-width'] == '1') { $set_template = TEMPLATEPATH . '/full-width.php'; }

	return $set_template;
}
add_filter('template_include', 'tcx_set_template');

// Replace jQuery
if (!is_admin()) {
	wp_deregister_script('jquery');
	wp_register_script('jquery', (get_bloginfo('template_directory')."/script/jquery-1.9.1.min.js"), false, '');
	wp_enqueue_script('jquery');
}

// Enable Featured Thumbnails
add_theme_support('post-thumbnails');
add_image_size('tcx_slideshow_cropped', '200', '200', true);
grayscale_add_image_size('gray_thumbnail', '210', '210', true, grayscale);

// Enable Blog and Feed RSS
add_theme_support('automatic-feed-links');

// Enable Shortcodes in Widgets
add_filter('widget_text', 'do_shortcode');

// Clean Up Header
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_generator');
if (!is_admin()) {
	function remove_l10n() {
		wp_deregister_script('l10n');
	}
	add_action('init', 'remove_l10n'); 
}

// Remove Query Strings from Javascript and CSS Files
add_filter('script_loader_src', 'tcx_script_loader_filter');
function tcx_script_loader_filter($src) {
	$new_src = explode('?', $src);
	return $new_src[0];
}

// Remove Admin Bar
if ($tcx_theme_options['admin_bar'] == 1) {
	add_filter('show_admin_bar', '__return_false');
}

// Remove Curly Quotes
remove_filter('the_content', 'wptexturize');
remove_filter('comment_text', 'wptexturize');

// Inline comment reply form
function tcx_enqueue_comment_reply() {
	if (is_singular() && comments_open() && get_option('thread_comments') ) { 
		wp_enqueue_script('comment-reply'); 
	}
}
add_action('wp_enqueue_scripts', 'tcx_enqueue_comment_reply');

// Postmeta block for archives and single pages
function tcx_build_postmeta() {
	global $tcx_theme_options;
	if ($tcx_theme_options['blog_postmeta'] == '1') {
		$postmeta = '<p class="postmetadata">Posted ';
		if ($tcx_theme_options['postmeta_author'] == '1') {
			$postmeta .= " by " . get_the_author();
		}
		if ($tcx_theme_options['postmeta_date'] == '1') {
			// Removed until WP improves human_time_diff()
			//if ($tcx_theme_options['human_readable_time'] == '1') {
			//	$postmeta .= ", " . human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ago';
			//} else {
				$postmeta .= " on " . get_the_time('F jS, Y');
			//}
		}
		if ($tcx_theme_options['postmeta_cats'] == '1') {
			$postmeta .= " in " . get_the_category_list(', ');
		}
		$postmeta .= ".";
		if ($tcx_theme_options['postmeta_tags'] == '1' && has_tag()) {
			$postmeta .= " Tags: " . get_the_tag_list('',', ');
		}
		$postmeta .= '</p>';
		return $postmeta;
	}
	return false;
}

// Navigation block for single pages
function tcx_single_navigation() {
	global $tcx_theme_options;
	if ($tcx_theme_options['single_navigation'] == '1') {
		$navigation = '<div class="navigation">';
		$navigation .= '<div class="alignleft"><a href="' . get_permalink(get_adjacent_post(false,'',false)) . '">&larr; ' . get_the_title(get_adjacent_post(false,'',false)) . '</a></div>';
		$navigation .= '<div class="alignright"><a href="' . get_permalink(get_adjacent_post(false,'',true)) . '">' . get_the_title(get_adjacent_post(false,'',true)) . ' &rarr;</a></div>';
		$navigation .= '</div>';
		return $navigation;
	}
	return false;
}

// Excerpt Customization
function tcx_excerpt() {
	global $tcx_theme_options;
	$excerpt_length = $tcx_theme_options['excerpt_length'];
	$style = $tcx_theme_options['excerpt_style'];
	if ($style == 'link') {
		$endMore = '&hellip; <a href="' . esc_url(get_permalink()) . '">Continue reading &rarr;</a>';
		$endDone = ' <a href="' . esc_url(get_permalink()) . '">View Post &rarr;</a>';
	} else {
		$endMore = '&hellip; <div class="clear"><a href="' . esc_url(get_permalink()) . '" class="tcx_button excerpt-button">Continue reading</a></div>';
		$endDone = '<div class="clear"><a href="' . esc_url(get_permalink()) . '" class="tcx_button excerpt-button">View Post</a></div>';
	}
	$the_post = get_post($post_id);
	
	$the_excerpt = str_replace(']]>', ']]>', $the_post->post_content);
	$the_excerpt = strip_shortcodes(preg_replace('@<script[^>]*</script>@si', '', $the_excerpt));
	$the_excerpt = strip_tags($the_excerpt);
	$words = explode(' ', $the_excerpt, $excerpt_length + 1);

	$search_query = get_search_query();
	if (!empty($search_query)) {
		$keys = explode(" ",$search_query);
		$the_excerpt = preg_replace('/('.implode('|', $keys) .')/iu', '<strong class="search-excerpt">\0</strong>', $the_excerpt);
	}
	
	if (count($words) > $excerpt_length) {
		array_pop($words);
		array_push($words, $endMore);
		$the_excerpt = implode(' ', $words);
	} else {
		array_push($words, $endDone);
		$the_excerpt = implode(' ', $words);
	}
	
	return $the_excerpt;
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'tcx_excerpt');

// Modified get_adjacent_post() function
// From: http://stackoverflow.com/questions/10376891/make-get-adjacent-post-work-across-custom-post-types
function mod_get_adjacent_post($direction = 'prev', $post_types = 'post') {
	global $post, $wpdb;

	if(empty($post)) return NULL;
	if(!$post_types) return NULL;

	if(is_array($post_types)){
		$txt = '';
		for($i = 0; $i <= count($post_types) - 1; $i++){
			$txt .= "'".$post_types[$i]."'";
			if($i != count($post_types) - 1) $txt .= ', ';
		}
		$post_types = $txt;
	}

	$current_post_date = $post->post_date;

	$join = '';
	$in_same_cat = FALSE;
	$excluded_categories = '';
	$adjacent = $direction == 'prev' ? 'previous' : 'next';
	$op = $direction == 'prev' ? '<' : '>';
	$order = $direction == 'prev' ? 'DESC' : 'ASC';

	$join  = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
	$where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare("WHERE p.post_date $op %s AND p.post_type IN({$post_types}) AND p.post_status = 'publish'", $current_post_date), $in_same_cat, $excluded_categories );
	$sort  = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

	$query = "SELECT p.* FROM $wpdb->posts AS p $join $where $sort";
	$query_key = 'adjacent_post_' . md5($query);
	$result = wp_cache_get($query_key, 'counts');
	if ( false !== $result )
		return $result;

	$result = $wpdb->get_row("SELECT p.* FROM $wpdb->posts AS p $join $where $sort");
	if ( null === $result )
		$result = '';

	wp_cache_set($query_key, $result, 'counts');
	return $result;
}

// Widget Registration
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Home Location Info',
		'id' => 'home-more-info',
		'before_widget' => '<li class="c33">',
		'after_widget' => '</li>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Blog Sidebar',
		'id' => 'sidebar-blog',
		'before_widget' => '<li id="%1$s" class="%2$s my_widget_class">',
		'after_widget' => '</li>',
		'before_title' => '<div class="icon small">',
		'after_title' => '</div>',
	));
	register_sidebar(array(
		'name' => 'Footer',
		'id' => 'footer',
		'before_widget' => '<div class="c33">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar(array(
		'name' => 'Culture Subpages',
		'id' => 'subpages-culture',
		'before_widget' => '<div class="subpages">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
	register_sidebar(array(
		'name' => 'Services Subpages',
		'id' => 'subpages-services',
		'before_widget' => '<div class="subpages">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
	register_sidebar(array(
		'name' => 'Expertise Subpages',
		'id' => 'subpages-expertise',
		'before_widget' => '<div class="subpages">',
		'after_widget' => '</div>',
		'before_title' => '',
		'after_title' => '',
	));
};

add_filter('dynamic_sidebar_params', 'my_widget_class');
function my_widget_class($params) {
	global $widget_num;

	// Widget class
	$class = array();
	$class[] = 'widget';

	// Iterated class
	$widget_num++;
	$class[] = 'widget-' . $widget_num;

	// Join the classes in the array
	$class = join(' ', $class);

	// Interpolate the 'my_widget_class' placeholder
	$params[0]['before_widget'] = str_replace('my_widget_class', $class, $params[0]['before_widget']);
	return $params;
}

// Menu Registration
if (function_exists('register_nav_menus')) {
	register_nav_menus(array(
		'main-menu-left' => 'Main Menu (Left)'
	));
	register_nav_menus(array(
		'main-menu-right' => 'Main Menu (Right)'
	));
}
function fallback_menu() {
	echo '<ul id="menu">' . wp_list_pages("echo=0&title_li=&sort_order=desc") . '</ul>';
}

// Archive Page Title
function tcx_archive_title() {
	if (is_category()) {			
		$title = single_cat_title();
	} elseif (is_tag()) {
		$title = single_tag_title();
	} elseif (is_day()) {
		$title = 'Archive for ' . get_the_time('F jS, Y');
	} elseif (is_month()) {
		$title = 'Archive for ' . get_the_time('F, Y');
	} elseif (is_year()) {
		$title = 'Archive for ' . get_the_time('Y');
	} elseif (is_search()) {
		$title = 'Search Results';
	} elseif (is_author()) {
		$title = 'Author Archive';
	} else {
		$title = 'Archive';
	}
	return $title;
}

// Pagination
function tcx_paginate() {
	global $wp_query, $tcx_theme_options;
	$big = 999999999;
	echo '<div class="pagination">';
	
	if ($mobilePagination == 1) {
		echo paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '?paged=%#%',
			'end_size' => 0,
			'mid_size' => 0,
			'prev_text' => '&larr; Previous Results',
			'next_text' => 'More Results &rarr;',
			'current' => max( 1, get_query_var('paged') ),
			'total' => $wp_query->max_num_pages
		));
	} else {
		if($tcx_theme_options['pagination'] == "simple") {
			echo '<div class="navigation">
				<div class="alignleft">' . get_previous_posts_link("&laquo; Previous Page") . '</div>
				<div class="alignright">' . get_next_posts_link("Next Page &raquo;") . '</div>
			</div>';
		} else {
			echo paginate_links(array(
				'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
				'format' => '?paged=%#%',
				'current' => max( 1, get_query_var('paged') ),
				'total' => $wp_query->max_num_pages
			));
		}
	}
	
	echo '</div>';
}

// Canopy Slideshow Shortcode
function tcx_slideshow_render($atts, $content = null) {  
	extract(shortcode_atts(array(  
		"id" => '1'
	), $atts));
	$slideshow = get_option('tcx_slideshow_'.$id);
	if ($slideshow["mode"] == "responsive") {
		if ($slideshow["max_width"] == "auto") {$maxWidth = '100%';} else {$maxWidth = $slideshow["max_width"].'px';}
		if ($slideshow["max_height"] == "auto") {$maxHeight = '100%';} else {$maxHeight = $slideshow["max_height"].'px';}
		$sizeConstraints = ' style="max-height:'.$maxHeight.'; max-width:'.$maxWidth.';"';
	} else if ($slideshow["mode"] == "fixed") {
		$slideshowSize = ' style="height:'.$slideshow["height"].'px; width:'.$slideshow["width"].'px;"';
		$sizeConstraints = $slideshowSize;
	}

	if ($slideshow["transition"] == "carousel") {
		$transition = "slide";
		$carouselSlide = ' class="carousel"';
		$carouselOptions = ',itemWidth: ' . $slideshow["carousel_itemwidth"] . ',
			minItems: ' . $slideshow["carousel_min"] . ',
			maxItems: ' . $slideshow["carousel_max"] . ',
			move: ' . $slideshow["carousel_move"] . ',
			itemMargin: 30'; //itemMargin must also be set in CSS
	} else {
		$transition = $slideshow["transition"];
		$carouselOptions = "";
	}

	if ($slideshow["autoplay"] == "off" && $slideshow["top_id"] > 1) {$autoPlay = ',start: function(slider) {slider.pause()}';} else {$autoPlay = '';}

	//if (is_singular('work')) {$startAt = ',startAt: 1';} else {$startAt = '';}
	if (is_singular('work')) {$autoPlay = ''; $startAt = ',after: function(slider) {slider.pause()}';} else {$startAt = '';}

	if ($slideshow["controller"] == "dots") {$controller = "true";}
		//else if($slideshow["controller"] == "numbers") {$controller = "false";}
		//else if($slideshow["controller"] == "thumbnails") {$controller = "false";}
		else if($slideshow["controller"] == "off") {$controller = "false";}
	if ($slideshow["arrows"] == "on") {$arrows = "true";} else {$arrows = "false";}
	if ($slideshow["random"] == "on") {$randomize = "true";} else {$randomize = "false";}
	if ($slideshow["hoverpause"] == "off" || $slideshow["autoplay"] == "off") {$hoverpause = "false";} else {$hoverpause = "true";}

	// Special Case for homepage video slideshow
	if ($id == '1') {
		$randomSlide = rand(1,$slideshow['top_id']);
		$splitSrc = explode(".", $slideshow[slides][$randomSlide]['full_image']);
		$endSrc = array_pop($splitSrc);
		$newSrc = implode('.', $splitSrc);

		//$display = '<div class="tcx_slideshow slideshow-' . $id . '"'.$slideshowSize.'><ul class="slides"><li><div class="slide-desc">';
		$display = '<div class="tcx_slideshow">';
		$display .= '<img src="' . $slideshow[slides][$randomSlide]['full_image'] . '" alt="" class="tablet-image" /><video poster="' . $slideshow[slides][$randomSlide]['full_image'] . '" autoplay="autoplay" loop="loop"><source src="' . $newSrc . '.mp4" type="video/mp4" /><source src="' . $newSrc . '.ogg" type="video/ogg" /><source src="' . $newSrc . '.webm" type="video/webm" /></video>';
		$display .= '<div class="container"><div class="slide-desc"><' . $slideshow["description_tag"] . ' class="' . $slideshow["description_class"] . '">' . htmlspecialchars_decode(do_shortcode($slideshow[slides][$randomSlide]['description'])) . '</' . $slideshow["description_tag"] . '>';
		$display .= '</div></div></div>';
		$display .= '<!-- topid: '.$slideshow['top_id']. 'chosen: ' . $randomSlide . '-->';
	} else {
		$display = '<div class="tcx_slideshow slideshow-' . $id . '"'.$slideshowSize.'><ul class="slides">';
		foreach ($slideshow[slides] as $slide) {
			$display .= '<li'.$sizeConstraints.$carouselSlide.'>';
			if ($slideshow['slidelinks'] == 'lightbox') {
				$display .= '<a href="' . $slide['full_image'] . '" class="fancybox" rel="' . $slideshow['slideshow_name']. '">';
			} else if ($slideshow['slidelinks'] == 'custom' && $slide['link']!='') {
				$display .= '<a href="' . $slide['link'] . '" ';
				if ($slide['lightbox'] == 'on') { $display .= 'class="iframe" rel="' . $slideshow['slideshow_name']. '">';}
				else { $display .= '>';}

			}
			$display .= '<img src="' . $slide['full_image'] . '" alt="' . htmlspecialchars_decode($slide['title']) . '" />';
			if ($slideshow['slidelinks'] == 'lightbox' || $slideshow['slidelinks'] == 'custom') {
				if ($slide['link']!='') {
					$display .= '</a>';
				}
			}
			
			if ($slideshow["title_display"] == "on" || $slideshow["description_display"] == "on") {
				if($id == '1') {$display .= '<div class="container">';}
				$display .= '<div class="slide-desc">';
				if ($slideshow["title_display"] == "on") {
					$display .= '<' . $slideshow["title_tag"] . ' class="' . $slideshow["title_class"] . '">' . htmlspecialchars_decode($slide['title']) . '</' . $slideshow["title_tag"] . '>';
				}
				if ($slideshow["description_display"] == "on") {
					$display .= '<' . $slideshow["description_tag"] . ' class="' . $slideshow["description_class"] . '">' . htmlspecialchars_decode(do_shortcode($slide['description'])) . '</' . $slideshow["description_tag"] . '>';
				}
				$display .= '</div>';
				if($id == '1') {$display .= '</div>';}
			}
			$display .= '</li>';
		}
		$display .= '</ul></div><div class="clear"></div>';

		// TODO: Get this into wp_footer without requiring PHP5.3 anonymous functions
		$display .= '<script type="text/javascript">
		$(document).ready(function(){
			$(".slideshow-' . $id . '").flexslider({
				animation: "' . $transition . '",
				easing: "' . $slideshow["easing"] . '",
				slideshowSpeed: ' . $slideshow["delay"] . ',
				animationSpeed: '. $slideshow["speed"] . ',
				directionNav: ' . $arrows . ',
				controlNav: ' . $controller . ',
				initDelay: ' . $slideshow["initial_delay"] . ',
				randomize: ' . $randomize . ',
				pauseOnHover: ' . $hoverpause . $carouselOptions . $autoPlay . $startAt .
			'});
		});
		</script>';
	}

	return $display;
}
add_shortcode("tcx_slideshow", "tcx_slideshow_render");

// Sidebar-to-Shortcode
function tcx_sidebar_to_shortcode($atts) {
	extract(shortcode_atts(array(  
		"sidebar" => ''
	), $atts));
	if($sidebar) {
		ob_start();
		dynamic_sidebar($sidebar);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	} else {
		return;
	}
}
add_shortcode("widget", "tcx_sidebar_to_shortcode");

// Buttons Shortcode
function tcxButton($atts, $content = null) {  
	extract(shortcode_atts(array(  
		"link" => '',
		"title" => '',
		"color" => '',
		"behavior" => ''
	), $atts));
	if ($color == "gray") {$color = ' gray';} else {$color = '';}
	if ($behavior == "new") {$target = ' target="_blank"';}
	else if ($behavior == "lightbox") {$lightbox = ' iframe';}
	return '<a href="'.$link.'" class="tcx_button'.$color.$lightbox.'"'.$target.'>'.$title.'</a>';  
}
add_shortcode("button", "tcxButton");

// Separator Shortcode
function addSep($content = null) {  
	return '<div class="separator"></div>';  
}
add_shortcode("sep", "addSep");

// Get Latest Tweet
function tcx_latest_tweet($atts) {
	extract(shortcode_atts(array(  
		"username" => ''
	), $atts));
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_URL, "http://api.twitter.com/1/statuses/user_timeline.json?id=$username&count=1");
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
	$input = curl_exec($ch);
	$val = json_decode($input,TRUE);
	$text = $val[0]['text'];
	$time = $val[0]['created_at'];
	date_default_timezone_set('America/New_York');
	$time = date('M j, Y @ h:i A', strtotime($time));
	curl_close($ch);
	return $text . '<br/><span class="tweet-time">' . $time . '</span>';
}
add_shortcode("twitter", "tcx_latest_tweet");

// Get Latest Posts
function get_latest_posts($number) {
	global $wpdb;
	$request = "SELECT ID, post_title, post_excerpt FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post' ORDER BY post_date DESC LIMIT $number";
	$posts = $wpdb->get_results($request);
	
	if($posts) {
		$output = '<ul class="recent-posts">';
		foreach ($posts as $posts) {
			$post_title = stripslashes($posts->post_title);
			$permalink = get_permalink($posts->ID);
			$output .= '<li><a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . '</a></li>';
		}
		$output .= '</ul>';
	} else {
		$output .= '<ul class="recent-posts"><li>No posts found</li></ul>';
	}
	echo $output;
}

// Sidebar Selector
/* DISABLED during development of Responsive theme
add_action('add_meta_boxes', 'tcx_sidebar_selector_add_custom_box');
add_action('save_post', 'tcx_sidebar_selector_save_postdata');

function tcx_sidebar_selector_add_custom_box() {
	add_meta_box('tcx_sidebar_selector_sectionid', 'Sidebars', 'tcx_sidebar_selector_meta_box', 'post', 'side');
	add_meta_box('tcx_sidebar_selector_sectionid', 'Sidebars', 'tcx_sidebar_selector_meta_box', 'page', 'side');
} */

/* Meta box */
function tcx_sidebar_selector_meta_box( $post ) {

	wp_nonce_field(plugin_basename( __FILE__ ), 'tcx_sidebar_selector_noncename');

	// Fields
	$active_sidebar_list = get_post_custom_values('active-sidebar');
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
		$checked = strpos($active_sidebar_list[0], $sidebar['id']);
		
		if ($checked !== false) {$checked = " checked";} else {$checked = "";}
		//echo 'LIST:' . $active_sidebar_list[0] . '...' . $sidebar['id'] . '<br/>';
		echo '<label for="' . $sidebar['id'] . '"><input type="checkbox" id="' . $sidebar['id'] . '" name="active-sidebar[]" value="' . $sidebar['id'] . '"' . $checked . '/> ' . $sidebar['name'] . '</label><br/>';
	}
	
	?><form method="post" action="options.php" id="add-new-sidebar">
		<?php settings_fields( 'ups_sidebars_options' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Name</th>
				<td>
					<input id="ups_sidebars[add_sidebar]" class="text" type="text" name="ups_sidebars[add_sidebar]" value="" />
				</td>
			</tr>
		</table>
		<p class="submit" style="padding: 0;">
			<input type="submit" class="button-primary" value="Add Sidebar" />
		</p>
	</form><?php
	
	//print_r(get_post_custom_values('active-sidebar'));
	//$dat = get_post_custom_values('active-sidebar');
	//echo $dat[0];
	//print_r($GLOBALS['wp_registered_sidebars']);
}

/* Save data when post is saved */
function tcx_sidebar_selector_save_postdata($post_id) {
	// Don't submit during autosaves
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) 
		return;

	// Verify against nonce
	if (!wp_verify_nonce( $_POST['tcx_sidebar_selector_noncename'], plugin_basename( __FILE__ )))
		return;

	// Check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can( 'edit_page', $post_id))
			return;
	} else {
		if (!current_user_can( 'edit_post', $post_id))
			return;
	}

	$mydata = $_POST['active-sidebar'];
	//print_r($mydata);

	// Do something with $mydata 
	// probably using add_post_meta(), update_post_meta(), or 
	// a custom table (see Further Reading section below)
	
	//$key1_values = get_post_custom_values('key_1', 76);
	
	update_post_meta($post_id, 'active-sidebar', implode(', ', $_POST['active-sidebar']));
}

// Redirect Users Upon Login Based on User Level
$redirect_by_role['editor'] = get_bloginfo('wpurl') . '/wp-admin/';
$redirect_by_role['subscriber'] = get_bloginfo('wpurl') . '/';
$redirect_all_users = false;

function redirect_users( $user_login ) {
	global $wpdb, $redirect_to, $redirect_by_role, $redirect_all_users;

	$user = get_userdatabylogin($user_login);
	
	if ($redirect_by_role) {
		foreach ( $redirect_by_role as $role => $redirect_url ) {
			if ( isset ( $user->{$wpdb->prefix . 'capabilities'}[$role] ) ) {
				$redirect_to = $redirect_url;
				return false;
			}
		}
	}
}
add_action('wp_login', 'redirect_users');

// Control User Management Based on User Level
class tcx_user_capabilities {
	function tcx_user_capabilities() {
		add_filter( 'editable_roles', array(&$this, 'editable_roles'));
		add_filter( 'map_meta_cap', array(&$this, 'map_meta_cap'),10,4);
	}
	
	// Remove 'Administrator' from the list of roles if the current user is not an admin
	function editable_roles($roles) {
		if( isset($roles['administrator']) && !current_user_can('administrator')){
			unset($roles['administrator']);
		}
		return $roles;
	}
	
	// If someone is trying to edit or delete and admin and that user isn't an admin, don't allow it
	function map_meta_cap($caps, $cap, $user_id, $args) {
		switch($cap) {
			case 'edit_user':
			case 'remove_user':
			case 'promote_user':
				if(isset($args[0]) && $args[0] == $user_id)
					break;
				elseif(!isset($args[0]))
				$caps[] = 'do_not_allow';
				$other = new WP_User(absint($args[0]));
				if( $other->has_cap('administrator')) {
					if(!current_user_can('administrator')) {
						$caps[] = 'do_not_allow';
					}
				}
				break;
			case 'delete_user':
			case 'delete_users':
				if(!isset($args[0]))
					break;
				$other = new WP_User(absint($args[0]));
				if( $other->has_cap('administrator')){
					if(!current_user_can('administrator')) {
						$caps[] = 'do_not_allow';
					}
				}
				break;
			default:
				break;
		}
		return $caps;
	}
}
$tcx_user_caps = new tcx_user_capabilities();

// Set Default Permissions for Editors and Administrators [WIP]
function update_caps() {
	$role = get_role('administrator');

	$caps_to_add =  array(
	'gravityforms_addon_browser',
	'gravityforms_create_form',
	'gravityforms_delete_entries',
	'gravityforms_delete_forms',
	'gravityforms_edit_entries',
	'gravityforms_edit_entry_notes',
	'gravityforms_edit_forms',
	'gravityforms_edit_settings',
	'gravityforms_export_entries',
	'gravityforms_preview_forms',
	'gravityforms_uninstall',
	'gravityforms_view_entries',
	'gravityforms_view_entry_notes',
	'gravityforms_view_settings',
	'gravityforms_view_updates'
	);

	foreach( $caps_to_add as $cap )
	$role->add_cap( $cap );
}

add_action('switch_theme', 'update_caps');

// TinyMCE Customization
function add_tcx_mcebuttons() {
	if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		return;
	
	if (get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_tcx_tinymce_plugins");
	}
}

function add_tcx_tinymce_plugins($plugin_array) {
	$plugin_array['tcxsep'] = get_bloginfo('template_directory').'/functions/tcx-sep/editor_plugin.js';
	$plugin_array['tcxbutton'] = get_bloginfo('template_directory').'/functions/tcx-button/editor_plugin.js';
	$plugin_array['tcxaccordion'] = get_bloginfo('template_directory').'/functions/tcx-accordion/editor_plugin.js';
	$plugin_array['tcxcolumns'] = get_bloginfo('template_directory').'/functions/tcx-columns/editor_plugin.js';
	$plugin_array['searchreplace'] = get_bloginfo('template_directory').'/functions/searchreplace/editor_plugin.js';
	$plugin_array['table'] = get_bloginfo('template_directory').'/functions/tableDropdown/editor_tableplugin.js';
	$plugin_array['tableDropdown'] = get_bloginfo('template_directory').'/functions/tableDropdown/editor_plugin.js';
	
	return $plugin_array;
}

function extended_editor_mce_buttons($buttons) {
	return array(
		"undo", "redo", "separator",
		"bold", "italic", "separator",
		"bullist", "numlist", "charmap", "pastetext", "link", "separator",
		"justifyleft", "justifycenter", "justifyright", "replace", "spellchecker", "separator",
		"tcxbutton", "tcxsep", "tcxcolumns", "tcxaccordion", "separator",
		"tableDropdown", "separator",
		"formatselect"
	);
}
function mce_buttons_2() {
	return array();
}

add_action('init', 'add_tcx_mcebuttons');
add_filter("mce_buttons_2", "mce_buttons_2", 0 );
add_filter("mce_buttons", "extended_editor_mce_buttons", 0);

// Extend TinyMCE Valid Elements
function extend_mce_valid_elements($init) {
	$extStr = "br,br/,div[class|id|style],iframe[class|id|style|src|width|height],p[class|id|style],span[class|id|style],a[class|id|style|href]";
	if (isset($init['extended_valid_elements']) && !empty($init['extended_valid_elements'])) {
		$init['extended_valid_elements'] .= ',' . $extStr;
	} else {
		$init['extended_valid_elements'] = $extStr;
	}
	return $init;
}
add_filter('tiny_mce_before_init', 'extend_mce_valid_elements');

// Filter Gravity Form Action Fields to Enable AJAX
function mobile_gravity_forms() {
	if (is_page_template('mobile.php')) {
		add_filter("gform_form_tag", "form_tag", 10, 2);
		function form_tag($form_tag, $form) {
			global $post;
			$form_tag = preg_replace("|action='(.*?)'|", "action='/wp-testbed/$post->post_name/?clean=1'", $form_tag);
			return $form_tag;
		}
	}
}
add_action('template_redirect', 'mobile_gravity_forms');

// Replace [gallery] Shortcode On Mobile Pages
function tcx_mobile_gallery($atts) {
	global $post;
 
	extract(shortcode_atts(array(
		'orderby' => 'menu_order ASC, ID ASC',
		'id' => $post->ID,
		'itemtag' => 'dl',
		'icontag' => 'dt',
		'captiontag' => 'dd',
		'columns' => 3,
		'size' => 'medium',
		'link' => 'attachment',
		'include' => '',
		'exclude' => ''
	), $atts));
 
	$args = array(
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_parent' => $id,
		'numberposts' => -1,
		'orderby' => $orderby,
		'include' => $include,
		'exclude' => $exclude
		); 
	$images = get_posts($args);
 
	$gallery = "";
	foreach ( $images as $image ) {
		$alt = $image->post_title;
		$img = get_attachment_link($image->ID, $size);
		$thumb = wp_get_attachment_thumb_url($image->ID);
		
		$gallery .= '<a href="' . $img . '" class="mobile-gallery-thumb"><img src="' . $thumb . '" alt="' . $alt . '"></a>';
	}
	return $gallery;
}

// CPT: People
add_action( 'init', 'register_cpt_people' );

function register_cpt_people() {

	$labels = array( 
		'name' => _x( 'People', 'people' ),
		'singular_name' => _x( 'People', 'people' ),
		'add_new' => _x( 'Add New', 'people' ),
		'add_new_item' => _x( 'Add New Person', 'people' ),
		'edit_item' => _x( 'Edit People', 'people' ),
		'new_item' => _x( 'New Person', 'people' ),
		'view_item' => _x( 'View Person', 'people' ),
		'search_items' => _x( 'Search People', 'people' ),
		'not_found' => _x( 'No people found', 'people' ),
		'not_found_in_trash' => _x( 'No people found in Trash, thank goodness', 'people' ),
		'parent_item_colon' => _x( 'Parent People:', 'people' ),
		'menu_name' => _x( 'People', 'people' ),
	);

	$args = array( 
		'labels' => $labels,
		'hierarchical' => false,
		
		'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'taxonomies' => array( 'position' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,  
		
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'people', $args );
}

// CPT: Work
add_action( 'init', 'register_cpt_work' );

function register_cpt_work() {

	$labels = array( 
		'name' => _x( 'Work', 'work' ),
		'singular_name' => _x( 'Job', 'work' ),
		'add_new' => _x( 'Add New', 'work' ),
		'add_new_item' => _x( 'Add New Job', 'work' ),
		'edit_item' => _x( 'Edit Job', 'work' ),
		'new_item' => _x( 'New Job', 'work' ),
		'view_item' => _x( 'View Job', 'work' ),
		'search_items' => _x( 'Search Work', 'work' ),
		'not_found' => _x( 'No work found', 'work' ),
		'not_found_in_trash' => _x( 'No work found in Trash', 'work' ),
		'parent_item_colon' => _x( 'Parent Job:', 'work' ),
		'menu_name' => _x( 'Work', 'work' ),
	);

	$args = array( 
		'labels' => $labels,
		'hierarchical' => true,
		
		'supports' => array( 'title', 'editor', 'excerpt', 'thumbnail', 'revisions', 'page-attributes' ),
		'taxonomies' => array( 'work_categories', 'service_categories' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		
		
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => false,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'work', $args );
}

// CPT: Case Studies
add_action( 'init', 'register_cpt_case_studies' );

function register_cpt_case_studies() {

	$labels = array( 
		'name' => _x( 'Case Study', 'case_studies' ),
		'singular_name' => _x( 'Case Studies', 'case_studies' ),
		'add_new' => _x( 'Add New', 'case_studies' ),
		'add_new_item' => _x( 'Add New Case Studies', 'case_studies' ),
		'edit_item' => _x( 'Edit Case Studies', 'case_studies' ),
		'new_item' => _x( 'New Case Studies', 'case_studies' ),
		'view_item' => _x( 'View Case Studies', 'case_studies' ),
		'search_items' => _x( 'Search Case Study', 'case_studies' ),
		'not_found' => _x( 'No case study found', 'case_studies' ),
		'not_found_in_trash' => _x( 'No case study found in Trash', 'case_studies' ),
		'parent_item_colon' => _x( 'Parent Case Studies:', 'case_studies' ),
		'menu_name' => _x( 'Case Studies', 'case_studies' ),
	);

	$args = array( 
		'labels' => $labels,
		'hierarchical' => true,
		
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
		'taxonomies' => array( 'work_categories', 'service_categories' ),
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'case_studies', $args );
}

// CPT: Careers
add_action( 'init', 'register_cpt_career' );

function register_cpt_career() {

	$labels = array( 
		'name' => _x( 'Careers', 'career' ),
		'singular_name' => _x( 'Career', 'career' ),
		'add_new' => _x( 'Add New', 'career' ),
		'add_new_item' => _x( 'Add New Career', 'career' ),
		'edit_item' => _x( 'Edit Career', 'career' ),
		'new_item' => _x( 'New Career', 'career' ),
		'view_item' => _x( 'View Career', 'career' ),
		'search_items' => _x( 'Search Careers', 'career' ),
		'not_found' => _x( 'No careers found', 'career' ),
		'not_found_in_trash' => _x( 'No careers found in Trash', 'career' ),
		'parent_item_colon' => _x( 'Parent Career:', 'career' ),
		'menu_name' => _x( 'Careers', 'career' ),
	);

	$args = array( 
		'labels' => $labels,
		'hierarchical' => false,
		
		'supports' => array( 'title', 'editor', 'thumbnail', 'revisions' ),
		
		'public' => true,
		'show_ui' => true,
		'show_in_menu' => true,
		
		//'menu_icon' => '/wp-content/themes/pilt/images/icon-careers.png',
		'show_in_nav_menus' => true,
		'publicly_queryable' => true,
		'exclude_from_search' => false,
		'has_archive' => true,
		'query_var' => true,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'career', $args );
}

// CPT: Events
add_action( 'init', 'register_cpt_event' );

function register_cpt_event() {

    $labels = array( 
        'name' => _x( 'Events', 'event' ),
        'singular_name' => _x( 'Event', 'event' ),
        'add_new' => _x( 'Add New', 'event' ),
        'add_new_item' => _x( 'Add New Event', 'event' ),
        'edit_item' => _x( 'Edit Event', 'event' ),
        'new_item' => _x( 'New Event', 'event' ),
        'view_item' => _x( 'View Event', 'event' ),
        'search_items' => _x( 'Search Events', 'event' ),
        'not_found' => _x( 'No events found', 'event' ),
        'not_found_in_trash' => _x( 'No events found in Trash', 'event' ),
        'parent_item_colon' => _x( 'Parent Event:', 'event' ),
        'menu_name' => _x( 'Events', 'event' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'event', $args );
}

// CPT: Shorts
add_action( 'init', 'register_cpt_short' );
function register_cpt_short() {

    $labels = array( 
        'name' => _x( 'Shorts', 'short' ),
        'singular_name' => _x( 'Short', 'short' ),
        'add_new' => _x( 'Add New', 'short' ),
        'add_new_item' => _x( 'Add New Short', 'short' ),
        'edit_item' => _x( 'Edit Short', 'short' ),
        'new_item' => _x( 'New Short', 'short' ),
        'view_item' => _x( 'View Short', 'short' ),
        'search_items' => _x( 'Search Shorts', 'short' ),
        'not_found' => _x( 'No shorts found', 'short' ),
        'not_found_in_trash' => _x( 'No shorts found in Trash', 'short' ),
        'parent_item_colon' => _x( 'Parent Short:', 'short' ),
        'menu_name' => _x( 'Shorts', 'short' ),
    );

    $args = array( 
        'labels' => $labels,
        'hierarchical' => true,
        
        'supports' => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );

    register_post_type( 'short', $args );
}

// Custom Taxonomy: Work Category / Expertise Category
add_action( 'init', 'register_taxonomy_work_categories' );

function register_taxonomy_work_categories() {

	$labels = array( 
		'name' => _x( 'Expertise Categories', 'work_categories' ),
		'singular_name' => _x( 'Expertise Category', 'work_categories' ),
		'search_items' => _x( 'Search Expertise Categories', 'work_categories' ),
		'popular_items' => _x( 'Popular Expertise Categories', 'work_categories' ),
		'all_items' => _x( 'All Expertise Categories', 'work_categories' ),
		'parent_item' => _x( 'Parent Expertise Category', 'work_categories' ),
		'parent_item_colon' => _x( 'Parent Expertise Category:', 'work_categories' ),
		'edit_item' => _x( 'Edit Expertise Category', 'work_categories' ),
		'update_item' => _x( 'Update Expertise Category', 'work_categories' ),
		'add_new_item' => _x( 'Add New Expertise Category', 'work_categories' ),
		'new_item_name' => _x( 'New Expertise Category', 'work_categories' ),
		'separate_items_with_commas' => _x( 'Separate expertise categories with commas', 'work_categories' ),
		'add_or_remove_items' => _x( 'Add or remove expertise categories', 'work_categories' ),
		'choose_from_most_used' => _x( 'Choose from the most used expertise categories', 'work_categories' ),
		'menu_name' => _x( 'Expertise Categories', 'work_categories' ),
	);

	$args = array( 
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,

		'rewrite' => array('slug' => 'vi-expertise'),
		'query_var' => true
	);

	register_taxonomy( 'work_categories', array('work', 'case_studies'), $args );
}

// Custom Taxonomy: Service Category
add_action( 'init', 'register_taxonomy_service_categories' );

function register_taxonomy_service_categories() {

	$labels = array( 
		'name' => _x( 'Service Categories', 'service_categories' ),
		'singular_name' => _x( 'Service Category', 'service_categories' ),
		'search_items' => _x( 'Search Service Categories', 'service_categories' ),
		'popular_items' => _x( 'Popular Service Categories', 'service_categories' ),
		'all_items' => _x( 'All Service Categories', 'service_categories' ),
		'parent_item' => _x( 'Parent Service Category', 'service_categories' ),
		'parent_item_colon' => _x( 'Parent Service Category:', 'service_categories' ),
		'edit_item' => _x( 'Edit Service Category', 'service_categories' ),
		'update_item' => _x( 'Update Service Category', 'service_categories' ),
		'add_new_item' => _x( 'Add New Service Category', 'service_categories' ),
		'new_item_name' => _x( 'New Service Category', 'service_categories' ),
		'separate_items_with_commas' => _x( 'Separate service categories with commas', 'service_categories' ),
		'add_or_remove_items' => _x( 'Add or remove service categories', 'service_categories' ),
		'choose_from_most_used' => _x( 'Choose from the most used service categories', 'service_categories' ),
		'menu_name' => _x( 'Service Categories', 'service_categories' ),
	);

	$args = array( 
		'labels' => $labels,
		'public' => true,
		'show_in_nav_menus' => true,
		'show_ui' => true,
		'show_tagcloud' => true,
		'show_admin_column' => false,
		'hierarchical' => true,

		'rewrite' => array('slug' => 'vi-services'),
		'query_var' => true
	);

	register_taxonomy( 'service_categories', array('work', 'case_studies'), $args );
}

// People CPT Custom Data
add_action('admin_menu', 'add_people_meta');
add_action('save_post', 'save_people_meta');

function add_people_meta() {
	add_meta_box('people', 'Profile Information', 'people_custom', 'people', 'side', 'low');
}

function save_people_meta($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	delete_post_meta($post->ID, "portrait");
	delete_post_meta($post->ID, "position");
	delete_post_meta($post->ID, "email");
	delete_post_meta($post->ID, "facebook");
	delete_post_meta($post->ID, "twitter");
	delete_post_meta($post->ID, "linkedin");
	delete_post_meta($post->ID, "googleplus");
	delete_post_meta($post->ID, "instagram");
	
	if ($_POST["portrait"]) {add_post_meta($post->ID, "portrait", $_POST["portrait"], false);}
	if ($_POST["position"]) {add_post_meta($post->ID, "position", $_POST["position"], false);}
	if ($_POST["email"]) {add_post_meta($post->ID, "email", $_POST["email"], false);}
	if ($_POST["facebook"]) {add_post_meta($post->ID, "facebook", $_POST["facebook"], false);}
	if ($_POST["twitter"]) {add_post_meta($post->ID, "twitter", $_POST["twitter"], false);}
	if ($_POST["linkedin"]) {add_post_meta($post->ID, "linkedin", $_POST["linkedin"], false);}
	if ($_POST["googleplus"]) {add_post_meta($post->ID, "googleplus", $_POST["googleplus"], false);}
	if ($_POST["instagram"]) {add_post_meta($post->ID, "instagram", $_POST["instagram"], false);}
}

function people_custom() {
	global $post;
	global $wpdb;
	
	$custom_fields = get_post_custom($post->ID);
		$portrait = $custom_fields["portrait"][0];
		$position = $custom_fields["position"][0];
		$email = $custom_fields["email"][0];
		$facebook = $custom_fields["facebook"][0];
		$twitter = $custom_fields["twitter"][0];
		$linkedin = $custom_fields["linkedin"][0];
		$googleplus = $custom_fields["googleplus"][0];
		$instagram = $custom_fields["instagram"][0];
	
	echo '
		<p><strong>Portrait Image</strong><input type="text" name="portrait" value="' . $portrait . '" style="width: 100%" /></p>
		<p><strong>Position</strong><input type="text" name="position" value="' . $position . '" style="width: 100%" /></p>
		<p><strong>Email</strong><input type="text" name="email" value="' . $email . '" style="width: 100%" /></p>
		<p><strong>Facebook</strong><input type="text" name="facebook" value="' . $facebook . '" style="width: 100%" /></p>
		<p><strong>Twitter</strong><input type="text" name="twitter" value="' . $twitter . '" style="width: 100%" /></p>
		<p><strong>LinkedIn</strong><input type="text" name="linkedin" value="' . $linkedin . '" style="width: 100%" /></p>
		<p><strong>Google+</strong><input type="text" name="googleplus" value="' . $googleplus . '" style="width: 100%" /></p>
		<p><strong>Instagram</strong><input type="text" name="instagram" value="' . $instagram . '" style="width: 100%" /></p>
	';
}

// Work CPT Custom Data
add_action('admin_menu', 'add_work_meta');
add_action('save_post', 'save_work_meta');

function add_work_meta() {
	add_meta_box('work', 'Slideshow ID', 'work_custom', 'work', 'side', 'low');
}

function save_work_meta($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	delete_post_meta($post->ID, "slideshow");
	
	if ($_POST["slideshow"]) {add_post_meta($post->ID, "slideshow", $_POST["slideshow"], false);}
}

function work_custom() {
	global $post;
	global $wpdb;
	
	$custom_fields = get_post_custom($post->ID);
	$slideshow = $custom_fields["slideshow"][0];
	
	echo '
		<p><strong>Slideshow ID</strong><input type="text" name="slideshow" value="' . $slideshow . '" style="width: 100%" /></p>
	';
}

// Career CPT Custom Data
add_action('admin_menu', 'add_career_meta');
add_action('save_post', 'save_career_meta');

function add_career_meta() {
	add_meta_box('career', 'Icon', 'career_custom', 'career', 'side', 'low');
}

function save_career_meta($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	delete_post_meta($post->ID, "icon");
	
	if ($_POST["icon"]) {add_post_meta($post->ID, "icon", $_POST["icon"], false);}
}

function career_custom() {
	global $post;
	global $wpdb;
	
	$custom_fields = get_post_custom($post->ID);
	$icon = $custom_fields["icon"][0];

	echo '<select name="icon">';
	echo '<option value="creative"';
		if($icon == "creative") {echo ' selected="selected"';}
	echo '>Creative</option>';
	echo  '<option value="socialstrategy"';
		if($icon == "socialstrategy") {echo ' selected="selected"';}
	echo '>Social Strategy</option>';
	echo  '<option value="public-relations"';
		if($icon == "public-relations") {echo ' selected="selected"';}
	echo '>Public Relations</option>';
	echo  '<option value="digital"';
		if($icon == "digital") {echo ' selected="selected"';}
	echo '>Digital</option>';
	echo  '<option value="production"';
		if($icon == "production") {echo ' selected="selected"';}
	echo '>Production</option>';
	echo  '<option value="media-planning"';
		if($icon == "media-planning") {echo ' selected="selected"';}
	echo '>Media Planning</option>';
	echo  '<option value="advertising"';
		if($icon == "advertising") {echo ' selected="selected"';}
	echo '>Advertising</option>';
	echo  '<option value="seo"';
		if($icon == "seo") {echo ' selected="selected"';}
	echo '>SEO</option>';
	echo  '<option value="strategic-planning"';
		if($icon == "strategic-planning") {echo ' selected="selected"';}
	echo '>Strategic Planning</option>';
	echo '</select>';

	return $return;
}

// Case Studies CPT Custom Data
add_action('admin_menu', 'add_case_studies_meta');
add_action('save_post', 'save_case_studies_meta');

function add_case_studies_meta() {
	add_meta_box('case_studies', 'PDF Download', 'case_studies_custom', 'case_studies', 'side', 'low');
}

function save_case_studies_meta($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	delete_post_meta($post->ID, "pdfdownload");
	
	if ($_POST["pdfdownload"]) {add_post_meta($post->ID, "pdfdownload", $_POST["pdfdownload"], false);}
}

function case_studies_custom() {
	global $post;
	global $wpdb;
	
	$custom_fields = get_post_custom($post->ID);
	$pdfdownload = $custom_fields["pdfdownload"][0];
	
	echo '
		<p><strong>PDF Download</strong><input type="text" name="pdfdownload" value="' . $pdfdownload . '" style="width: 100%" /></p>
	';
}

// Shorts CPT Custom Data
add_action('admin_menu', 'add_shorts_meta');
add_action('save_post', 'save_shorts_meta');

function add_shorts_meta() {
	add_meta_box('short', 'Video URL', 'shorts_custom', 'short', 'side', 'low');
}

function save_shorts_meta($post_id) {
	global $post;
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post->ID;
	}
	delete_post_meta($post->ID, "videourl");
	
	if ($_POST["videourl"]) {add_post_meta($post->ID, "videourl", $_POST["videourl"], false);}
}

function shorts_custom() {
	global $post;
	global $wpdb;
	
	$custom_fields = get_post_custom($post->ID);
	$videourl = $custom_fields["videourl"][0];
	
	echo '
		<p><strong>Video URL</strong><input type="text" name="videourl" value="' . $videourl . '" style="width: 100%" /></p>
	';
}

// Pass PDF Download Link to Thank You page
/*function pdfDownloadForeward($entry, $form){
	if($_POST['gform_submit'] == "5") {
		$pdfdownload = $_POST['pdfdownload'];
	}
}
add_action("gform_after_submission", "pdfDownloadForeward", 10, 2);*/
add_filter("gform_confirmation", "custom_confirmation", 10, 4);
function custom_confirmation($confirmation, $form, $lead, $ajax){
	if($form["id"] == "5") {
		$confirmation = array("redirect" =>"http://www.vimarketingandbranding.com/pdf-download/?pdfdownload=".$lead[6]);
	}
	return $confirmation;
}

// Expertise Subpages - List Case Studies
function tcx_list_case_studies($atts) {
	extract(shortcode_atts(array(  
		"category" => ''
	), $atts));
	$args = array('post_type' => 'case_studies', 'posts_per_page' => 2, 'orderby' => 'rand', 'work_categories' => $category);
	$loop = new WP_Query($args);
	$caseStudies = '';
	while ($loop->have_posts()) : $loop->the_post();
		$caseStudies .= '<div class="c50 center"><a href="' . get_permalink() . '" class="icon small results float-left"></a><h4 style="margin-bottom: 0;"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4><a href="' . get_permalink() . '"><small>Sounds Good? Read More...</small></a></div>';
	endwhile;
	return $caseStudies;
}
add_shortcode("case-studies", "tcx_list_case_studies");

// Expertise Subpages - List Work Posts
function tcx_list_work($atts) {
	extract(shortcode_atts(array(  
		"category" => ''
	), $atts));
	$args = array('post_type' => 'work', 'posts_per_page' => 4, 'orderby' => 'rand', 'work_categories' => $category);
	$loop = new WP_Query($args);
	$work = '<div id="gray-gallery">';
	while ($loop->have_posts()) : $loop->the_post();
		$work .= '<div class="gray"><a href="' . get_permalink() . '">' . get_the_post_thumbnail($post->ID, 'gray_thumbnail-gray') . '</a><div class="hover"><a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '</span>' . get_the_post_thumbnail($post->ID, 'gray_thumbnail') . '</a></div></div>';
	endwhile;
	$categorySpace = str_replace("-", " ", $category);
	$work .= '</div><div class="readmore-helper"><a href="/vi-expertise/' . $category . '" class="icon small readmore"></a><a href="/vi-expertise/' . $category . '">See more ' . $categorySpace . ' creative</a></div>';
	return $work;
}
add_shortcode("work", "tcx_list_work");

// Services Subpages - List Case Studies
function tcx_list_case_studies_by_service($atts) {
	extract(shortcode_atts(array(  
		"category" => ''
	), $atts));
	$args = array('post_type' => 'case_studies', 'posts_per_page' => 2, 'orderby' => 'rand', 'service_categories' => $category);
	$loop = new WP_Query($args);
	$caseStudies = '';
	while ($loop->have_posts()) : $loop->the_post();
		$caseStudies .= '<div class="c50 center"><a href="' . get_permalink() . '" class="icon small results float-left"></a><h4 style="margin-bottom: 0;"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h4><a href="' . get_permalink() . '"><small>Sounds Good? Read More...</small></a></div>';
	endwhile;
	return $caseStudies;
}
add_shortcode("case-studies-by-service", "tcx_list_case_studies_by_service");

// Services Subpages - List Work Posts
function tcx_list_work_by_service($atts) {
	extract(shortcode_atts(array(  
		"category" => ''
	), $atts));
	$args = array('post_type' => 'work', 'posts_per_page' => 4, 'orderby' => 'rand', 'service_categories' => $category);
	$loop = new WP_Query($args);
	$work = '<div id="gray-gallery">';
	while ($loop->have_posts()) : $loop->the_post();
		$work .= '<div class="gray"><a href="' . get_permalink() . '">' . get_the_post_thumbnail($post->ID, 'gray_thumbnail-gray') . '</a><div class="hover"><a href="' . get_permalink() . '"><span class="title">' . get_the_title() . '</span>' . get_the_post_thumbnail($post->ID, 'gray_thumbnail') . '</a></div></div>';
	endwhile;
	$categorySpace = str_replace("-", " ", $category);
	$work .= '</div><div class="readmore-helper"><a href="/vi-services/' . $category . '" class="icon small readmore"></a><a href="/vi-services/' . $category . '">See more ' . $categorySpace . ' creative</a></div>';
	return $work;
}
add_shortcode("work-by-service", "tcx_list_work_by_service");

// Expertise Subpages - List Work Posts
function tcx_list_blog_posts($atts) {
	extract(shortcode_atts(array(
		"category" => ''
	), $atts));
	$args = array('post_type' => 'post', 'posts_per_page' => 2, 'category_name' => $category);
	$loop = new WP_Query($args);
	$catPosts = '';
	while ($loop->have_posts()) : $loop->the_post();
		$catPosts .= '<div class="c25">' . get_the_post_thumbnail($post->ID, 'thumbnail') . '</div>';
		$catPosts .= '<div class="c75"><h3>' . get_the_title() . '</h3>' . get_the_excerpt() . '</div><div class="clear"></div>';
	endwhile;
	$categorySpace = str_replace("-", " ", $category);
	$catPosts .= '</div><div class="readmore-helper"><a href="/category/' . $category . '" class="icon small readmore"></a><a href="/category/' . $category . '">Read more ' . $categorySpace . ' posts</a></div>';
	return $catPosts;
}
add_shortcode("blog-posts", "tcx_list_blog_posts");

// Engage Recent Post
function tcx_recent_post($atts) {
	extract(shortcode_atts(array(
		"category" => ''
	), $atts));

	if ($category) {
		$args = array('post_type' => 'post', 'posts_per_page' => 1, 'category_name' => $category);
	} else {
		$args = array('post_type' => 'post', 'posts_per_page' => 1);
	}
	
	$loop = new WP_Query($args);
	$recentPost = '';
	while ($loop->have_posts()) : $loop->the_post();
		$recentPost .= '<div class="c25">' . get_the_post_thumbnail($post->ID, 'thumbnail') . '</div>';
		$recentPost .= '<div class="c75"><h3>' . get_the_title() . '</h3>' . get_the_excerpt() . '</div><div class="clear"></div>';
	endwhile;
	return $recentPost;
}
add_shortcode("recent-post", "tcx_recent_post");


// URL Shortening
require_once (dirname(__FILE__).'/functions/url-shortener.php');

?>