<?php
// Create custom plugin settings menu
function tcx_admin_menu() {
    add_menu_page('Theme Settings', 'Theme Settings', 'edit_theme_options', 'tcx_options_page', 'tcx_options_page', get_bloginfo('template_url') . "/images/icon-settings.png");
    add_submenu_page('tcx_options_page', 'Theme Icons', 'Theme Icons', 'edit_theme_options', 'tcx_theme_icons', 'tcx_icons_page');
    //add_submenu_page('tcx_options_page', 'Slideshows', 'Slideshows', 'edit_theme_options', 'slideshows', 'tcx_slideshows');
    add_submenu_page('tcx_options_page', 'Slideshows', 'Slideshows', 'edit_theme_options', 'tcx_slideshows', 'tcx_slideshows');
}
add_action('admin_menu', 'tcx_admin_menu');

// Queue scripts relevant to Theme Settings area
function tcx_theme_settings_scripts() {
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-effects-core');
    wp_enqueue_script('jquery-ui-sortable');
}    
add_action('admin_init', 'tcx_theme_settings_scripts');

// Add Slideshow Thumanail to Image Size Array
function tcx_add_slideshow_cropped($sizes) {
    $custom_sizes = array(
        'tcx_slideshow_cropped' => 'Slideshow Thumbnail'
    );
    return array_merge($sizes, $custom_sizes);
}
add_filter('image_size_names_choose', 'tcx_add_slideshow_cropped');

/*function mqw_example_contextual_help( $contextual_help, $screen_id) { 
   //echo 'Screen ID = '.$screen_id.'<br />';
     
    switch( $screen_id ) {
        case 'theme-settings_page_tcx_slideshows_dev' :
     // To add a whole tab group
            get_current_screen()->add_help_tab( array(
            'id'        => 'my-help-tab',
            'title'     => __( 'Overview' ),
            'content'   => __( 'Put any text here bla bla bla ....' )
            ) );
            
            break;
        case 'mi_plugin_page' :
            //Just to modify text of first tab
            $contextual_help .= '<p>';
            $contextual_help = __( 'Your text here.' );
            $contextual_help .= '</p>';
            break;
    }
    return $contextual_help;
}
add_filter('contextual_help', 'mqw_example_contextual_help', 10, 2);*/

require_once (dirname(__FILE__).'/tcx-icons-page.php');
require_once (dirname(__FILE__).'/tcx-options-page.php');
require_once (dirname(__FILE__).'/tcx-slideshows.php');

?>