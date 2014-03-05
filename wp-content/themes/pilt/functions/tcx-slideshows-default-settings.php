<?php
// Track current highest slideshow ID
function tcx_slideshow_general() {
	$options = array(
		'top_id' => 1,
		'last_active' => 1
	);
	return $options;
}

// Default settings for new slideshow
function tcx_slideshow_demo() {
	$options = array(
		'slideshow_id' => '1',
		'slideshow_name' => 'Slideshow Name',
		'mode' => 'responsive',
		'width' => '600',
		'height' => '400',
		'max_width' => 'auto',
		'max_height' => 'auto',
		'slidelinks' => 'off',
		'controller' => 'dots',
		'arrows' => 'on',
		'title_display' => 'on',
		'title_tag' => 'h2',
		'title_class' => 'title',
		'description_display' => 'on',
		'description_tag' => 'p',
		'description_class' => 'description',
		'transition' => 'fade',
		'speed' => '900',
		'delay' => '4000',
		'autoplay' => 'on',
		'easing' => 'swing',
		'carousel_itemwidth' => '200',
		'carousel_min' => '3',
		'carousel_max' => '5',
		'carousel_move' => '1',
		'hoverpause' => 'off',
		'random' => 'off',
		'initial_delay' => '0',
		'top_id' => '0',
		'slides' => array(
			'slide_0' => array(
				'id' => '0',
				'title' => 'New Slide',
				'description' => '',
				'link' => '',
				'lightbox' => 'off',
				'thumbnail' => '/wp-includes/images/blank.gif',
				'full_image' => '/wp-includes/images/blank.gif'
			)
		),
		'inactive_slides' => ''
	);
	return $options;
}

// Register settings
function register_slideshow_demo() {
	// Get and save option for last active slideshow
	add_option('tcx_slideshow_general', tcx_slideshow_general());
	$general_options = get_option('tcx_slideshow_general');

	// Select slideshow to view
	if ($_GET['slideshow_id']) {
		$slideshow_id = $_GET['slideshow_id'];
	} else if ($_POST['slideshow_id']) {
		$slideshow_id = $_POST['slideshow_id'];
	} else {
		$slideshow_id = $general_options['last_active'];
	}
	
	// TODO: replace dependance on settings-updated with a hook or custom post var
	// Update general settings when slideshow is updated
	if ($_REQUEST['settings-updated']) {
		$general_options['last_active'] = $slideshow_id;
		if ($slideshow_id == intval($general_options['top_id'])+1) {
			$general_options['top_id'] = $slideshow_id;
		}
		
		update_option('tcx_slideshow_general', $general_options);
	}

	// Update general settings when deleting a slideshow
	if ($_GET['delete_slideshow']) {
		// Pick new slideshow to mark as Last Active
		if ($slideshow_id == 1) {
			$general_options['last_active'] = '1';
			//echo "la state 1";
		} else if ($slideshow_id == $general_options['last_active']) {
			$general_options['last_active'] = intval($general_options['last_active'])-1;
			//echo "la state 2";
		} else {
			$general_options['last_active'] = intval($general_options['last_active'])-1;
			//echo "la state 3";
		}

		// Pick new slideshow to mark as top ID
		if ($_GET['delete_slideshow'] == $general_options['top_id']) {
			$general_options['top_id'] = intval($general_options['top_id'])-1;
		} else {
			$general_options['top_id'] = $general_options['top_id'];
		}
		/* debug
		echo 'now deleting slideshow ID' . $_GET['delete_slideshow'];
		echo '<br/>new last active is ID' . $general_options['last_active'];
		echo '<br/>new Top ID is ID' . $general_options['top_id']; */

		// Perform delete
		delete_option('tcx_slideshow_'.$_GET['delete_slideshow']);

		update_option('tcx_slideshow_general', $general_options);
	}

	// Register settings fields for all slideshows plus one
	//register_setting('tcx_slideshow', 'tcx_slideshow_demo');
	$j = intval($general_options['top_id'])+1;
	for ($i=1; $i<=$j; $i++) {
		register_setting('tcx_slideshow_'.$i, 'tcx_slideshow_'.$i);
	}
}
add_action('admin_init', 'register_slideshow_demo'); ?>