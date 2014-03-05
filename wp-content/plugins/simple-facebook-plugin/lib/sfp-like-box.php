<?php
/**
* Main Facebook Like Box Social Plagin File. Creates widget, shortcode and template tag.
*
* @package SF Plugin
* @author Ilya K.
*/

/**
* Like Box Widget Class
*
* Contains the main functions for SF and stores variables
*
* @since SF Plugin 1.0
* @author Ilya K.
*/

class SFPLikeBoxWidget extends WP_Widget {
	
	/**
	 * Register widget with WordPress
	 */
	function SFPLikeBoxWidget() {
		$widget_ops = array( 'description' => 'Display Facebook Like Box' );
		parent::WP_Widget( 'facebookwidget', $name = 'SFP - Like Box',  $widget_ops);
	}

	/**
	 * Front-end
	 */
	function widget( $args, $instance ) {
		
		global $sfplugin;
		
		// extract user options
		extract( $args );
		extract( $instance );
		
		echo $before_widget;
		
		// check for title
		$title = apply_filters( 'widget_title', $title );
		if ( ! empty( $title ) ) echo $before_title . $title . $after_title;
		
		// include Like Box view
		include( $sfplugin->pluginPath . 'views/view-like-box.php' );
		
		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved
	 */
	function update( $new_instance, $old_instance ) {
		
		$instance = $old_instance;
		// save new options
		$instance['title']			= strip_tags( $new_instance['title'] );
		$instance['url'] 			= strip_tags( $new_instance['url'] );
		$instance['width']			= strip_tags( $new_instance['width'] );
		$instance['height']			= strip_tags( $new_instance['height'] );
		$instance['colorscheme']	= strip_tags( $new_instance['colorscheme'] );
		$instance['local']			= strip_tags( $new_instance['local'] );
		$instance['faces']			= isset( $new_instance['faces'] );
		$instance['stream']			= isset( $new_instance['stream'] );
		$instance['header']			= isset( $new_instance['header'] );
		$instance['border']			= isset( $new_instance['border'] );
		
		return $instance;
	}

	/**
	 * Back-end form
	 */
	function form( $instance ) {
		
		extract( array_merge( array(
			// default options
			'title'			=> 'Our Facebook Page',
			'url'			=> 'http://www.facebook.com/wordpress',
			'width'			=> '292',
			'height'		=> '',
			'colorscheme' 	=> 'light',
			'faces'			=> true,
			'stream'		=> false,
			'header'		=> true,
			'border'		=> true,
			'local'			=> 'en_US'
		), $instance ) ); ?>
			
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Facebook Page URL:'); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
		</p>
		<table>
			<tr><td>
				<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Like Box width:'); ?></label> 
				</td><td>
				<input size="6" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" />px
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Like Box height:'); ?></label> 
				</td><td>
				<input size="6" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" />px
			</td></tr>
			<tr><td>
				&nbsp;
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('colorschme'); ?>"><?php _e('Color Scheme:'); ?></label> 
				</td><td>
				<input type="radio" name="<?php echo $this->get_field_name('colorscheme'); ?>" value="light" <?php checked(($colorscheme == 'light') ? 1 : 0); ?>/> Light<br />
				<input type="radio" name="<?php echo $this->get_field_name('colorscheme'); ?>" value="dark" <?php checked(($colorscheme == 'dark') ? 1 : 0); ?>/> Dark
			</td></tr>
			<tr><td>
				&nbsp;
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('faces'); ?>"><?php _e('Show Faces'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('faces'); ?>" type="checkbox" name="<?php echo $this->get_field_name('faces'); ?>" <?php checked(isset($faces) ? $faces : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('stream'); ?>"><?php _e('Show Stream'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('stream'); ?>" type="checkbox" name="<?php echo $this->get_field_name('stream'); ?>" <?php checked(isset($stream) ? $stream : 0); ?>/>
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('header'); ?>"><?php _e('Show Header'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('header'); ?>" type="checkbox" name="<?php echo $this->get_field_name('header'); ?>" <?php checked(isset($header) ? $header : 0); ?>/> 
			</td></tr>
			<tr><td>
				<label for="<?php echo $this->get_field_id('border'); ?>"><?php _e('Show Border'); ?></label> 
				</td><td>
				<input id="<?php echo $this->get_field_id('border'); ?>" type="checkbox" name="<?php echo $this->get_field_name('border'); ?>" <?php checked(isset($border) ? $border : 0); ?>/> 
			</td></tr>
		</table>
		<br/>
		<p>
			<label for="<?php echo $this->get_field_id('local'); ?>"><?php _e('Language'); ?></label> 
			<select name="<?php echo $this->get_field_name('local'); ?>">
				<option <?php selected(( $local == 'cs_CZ') ? 1 : 0);?> value="cs_CZ" >Czech</option>
				<option <?php selected(( $local == 'da_DK') ? 1 : 0);?> value="da_DK" >Danish</option>
				<option <?php selected(( $local == 'nl_NL') ? 1 : 0);?> value="nl_NL" >Dutch</option>
				<option <?php selected(( $local == 'en_US') ? 1 : 0);?> value="en_US" >English (US)</option>
				<option <?php selected(( $local == 'en_GB') ? 1 : 0);?> value="en_GB" >English (UK)</option>
				<option <?php selected(( $local == 'et_EE') ? 1 : 0);?> value="et_EE" >Estonian</option>
				<option <?php selected(( $local == 'fr_FR') ? 1 : 0);?> value="fr_FR" >French</option>
				<option <?php selected(( $local == 'de_DE') ? 1 : 0);?> value="de_DE" >German</option>
				<option <?php selected(( $local == 'it_IT') ? 1 : 0);?> value="it_IT" >Italian</option>
				<option <?php selected(( $local == 'ja_JP') ? 1 : 0);?> value="ja_JP" >Japanese</option>
				<option <?php selected(( $local == 'ko_KR') ? 1 : 0);?> value="ko_KR" >Korean</option>
				<option <?php selected(( $local == 'lv_LV') ? 1 : 0);?> value="lv_LV" >Latvian</option>
				<option <?php selected(( $local == 'lt_LT') ? 1 : 0);?> value="lt_LT" >Lithuanian</option>
				<option <?php selected(( $local == 'nb_NO') ? 1 : 0);?> value="nb_NO" >Norwegian (bokmal)</option>
				<option <?php selected(( $local == 'pl_PL') ? 1 : 0);?> value="pl_PL" >Polish</option>
				<option <?php selected(( $local == 'pt_PT') ? 1 : 0);?> value="pt_PT" >Portuguese</option>
				<option <?php selected(( $local == 'ro_RO') ? 1 : 0);?> value="ro_RO" >Romanian</option>
				<option <?php selected(( $local == 'ru_RU') ? 1 : 0);?> value="ru_RU" >Russian</option>
				<option <?php selected(( $local == 'es_LA') ? 1 : 0);?> value="es_LA" >Spanish</option>
				<option <?php selected(( $local == 'es_ES') ? 1 : 0);?> value="es_ES" >Spanish (Spain)</option>
				<option <?php selected(( $local == 'es_CL') ? 1 : 0);?> value="es_CL" >Spanish (Chile)</option>
				<option <?php selected(( $local == 'es_CO') ? 1 : 0);?> value="es_CO" >Spanish (Colombia)</option>
				<option <?php selected(( $local == 'es_MX') ? 1 : 0);?> value="es_MX" >Spanish (Mexico)</option>
				<option <?php selected(( $local == 'es_VE') ? 1 : 0);?> value="es_VE" >Spanish (Venezuela)</option>
				<option <?php selected(( $local == 'sv_SE') ? 1 : 0);?> value="sv_SE" >Swedish</option>
				<option <?php selected(( $local == 'zh_CN') ? 1 : 0);?> value="zh_CN" >Simplified Chinese</option>
				<option <?php selected(( $local == 'zh_TW') ? 1 : 0);?> value="zh_TW" >Traditional Chinese (Taiwan)</option>
				<option <?php selected(( $local == 'th_TH') ? 1 : 0);?> value="th_TH" >Thai</option>
				<option <?php selected(( $local == 'tr_TR') ? 1 : 0);?> value="tr_TR" >Turkish</option>
				<option <?php selected(( $local == 'uk_UA') ? 1 : 0);?> value="uk_UA" >Ukrainian</option>
				<option <?php selected(( $local == 'tl_ST') ? 1 : 0);?> value="tl_ST" >Klingon</option>
			</select>
		</p>
	<?php }
	
} // class SFPLikeBoxWidget

/**
 * Add Like Box 'Shortcode'
 *
 * @since SF Plugin 1.2
 * @author Ilya K.
 */

function sfp_like_box_shortcode ( $args = array() ) {

	global $sfplugin;

	extract( array_merge( array(
			// default options
			'url'			=> 'http://www.facebook.com/wordpress',
			'width'			=> '292',
			'height'		=> '',
			'colorscheme' 	=> 'light',
			'faces'			=> true,
			'stream'		=> false,
			'header'		=> true,
			'border'		=> true,
			'local'			=> 'en_US'
	), $args ) );

	ob_start();

	// include Like Box view
	include( $sfplugin->pluginPath . 'views/view-like-box.php' );

	return ob_get_clean();
}


/**
* Add Like Box 'Template Tag'
* 
* @since SF Plugin 1.2
* @author Ilya K.
*/

function sfp_like_box ( $args = array() ) { 
	
	global $sfplugin;
	
	extract( array_merge( array(
		// default options
		'url'			=> 'http://www.facebook.com/wordpress',
		'width'			=> '292',
		'height'		=> '',
		'colorscheme' 	=> 'light',
		'faces'			=> true,
		'stream'		=> false,
		'header'		=> true,
		'border'		=> true,
		'local'			=> 'en_US'
	), $args ) );
	
	// include Like Box view
	include( $sfplugin->pluginPath . 'views/view-like-box.php' );
}

?>