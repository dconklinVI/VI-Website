<?php
// Set default theme settings
function tcx_default_options() {
	$tcx_default_options = array(
		'admin_bar' => 1,
		'breadcrumb' => 0,
		'excerpt_length' => '35',
		'pagination' => 'full',
		'blog_content' => 'excerpt',
		'blog_featured_images' => 1,
		'blog_postdate' => 0,
		'blog_postmeta' => 1,
		'blog_postmeta_position' => 'before',
		'blog_separators' => 1,
		'single_navigation' => 0,
		'single_navigation_position' => 'after',
		'single_postmeta' => 1,
		'single_postmeta_position' => 'before',
		'postmeta_author' => 1,
		'postmeta_date' => 1,
		'postmeta_cats' => 0,
		'postmeta_tags' => 0,
		'share_bar' => 0,
		'share_bar_theme' => 'light',
		'tcx_social_facebook' => 1,
		'tcx_social_twitter' => 1,
		'tcx_social_linkedin' => 0,
		'tcx_social_email' => 1,
		'social_order' => 'order[]=facebook&order[]=twitter&order[]=linkedin&order[]=email'
	);
	return $tcx_default_options;
}

// Register settings
function register_tcx_settings() {
	register_setting('tcx_theme_options', 'tcx_options');
}
add_action('admin_init', 'register_tcx_settings'); ?>