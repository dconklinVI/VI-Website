<?php
/*
Plugin Name: Simple Facebook Plugin
Plugin URI: http://plugins.topdevs.net/simple-facebook-plugin
Description: Allows you to integrate Facebook Like Box into your WordPress Site.
Version: 1.2.2
Author: Ilya K.
Author URI: http://topdevs.net
License: GPLv2 or later
*/

/**
* Main SF Plugin Class
*
* Contains the main functions for SF and stores variables
*
* @since SF Plugin 1.2
* @author Ilya K.
*/

// Check if class already exist
if ( !class_exists( 'SFPlugin' ) ) {

	// Main plugin class
	class SFPlugin {
		
		public $pluginPath;
		public $pluginUrl;
		public $pluginName;
		
		/**
		* SF Plugin Constructor
		*
		* Gets things started
		*/
		public function __construct( ) {
			$this->pluginPath 	= plugin_dir_path(__FILE__);
			$this->pluginUrl 	= plugin_dir_url(__FILE__);
			
			$this->loadFiles();
			$this->addActions();
			$this->addShortcodes();
		}
		
		/**
		 * Load all the required files.
		 */
		protected function loadFiles() {
			// Include social plugins files
			require_once( $this->pluginPath . 'lib/sfp-like-box.php' );
		
		}
		
		/**
		* Add all the required actions.
		*/
		protected function addActions() {
			
			add_action('widgets_init', array( $this, 'addWidgets') );
			
		}
		
		/**
		* Register all widgets
		*/
		public function addWidgets() {
				
			register_widget('SFPLikeBoxWidget');

		}
		
		/**
		* Register all shortcodes
		*/
		public function addShortcodes() {
		
			add_shortcode('sfp-like-box', 'sfp_like_box_shortcode');
			
		}
		
	} // end SFPlugin class

} // end if !class_exists

// Create new SFPlugin instance
$GLOBALS["sfplugin"] = new SFPlugin();

?>